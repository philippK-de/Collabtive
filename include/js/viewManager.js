Vue.config.silent = true;
//create the vue views, binding data to DOM elements
/*
 * Function to create Vue.js view.
 * It binds together an HTML element with a datasource reactively
 * @param Object myView An object representing the HTML element to be bound
 */
function createView(myView) {
    /*
     * Object representing the data model for the view
     * @param Array items An array representing the data to be rendered in the view
     * @param Array pages An array representing the pages for the current datasource url, given pagination parameters
     * @param int limit Represents the database LIMIT parameter for the current page of data
     * @param int offset Represents the database OFFSET parameter for the current page of data
     * @param Array dependencies A list of Vue.js views that depend on the data in  this view, and should be updated if this view is updated
     * @param String url The data URL that delivers the models items
     */
    if(myView.loadHandler !== undefined) {
        console.log(myView.loadHandler);
    }
    else
    {
        myView.loadHandler = function(){
            console.log("dummy handler");
        }
    }

    var myModel = {
        items: [],
        pages: [],
        limit: pagination.itemsPerPage,
        offset: 0,
        currentPage: 1,
        itemsCount: 0,
        itemType: myView.itemType,
        url: myView.url,
        dependencies: myView.dependencies
    };
    /* Create the Vue.js view given the element myEl and the data in myModel
     * @param string el The DOM ID of the element to bind the view to
     * @param obj data The JSON object representing the data
     * @param obj methods Method the view exposed
     * @method function afterUpdate(updateHandler) Function to be called on the view. The closure passed in is executed
     * after the data model has been updated and the DOM rendering has finished
     */
    var vueview = new Vue({
        el: "#" + myView.el,
        data: myModel,
        methods: {
            update: function (updateDependencies) {
                updateView(this, updateDependencies);
            },
            afterUpdate: function (updateHandler) {
                this.$on("iloaded", function () {
                    Vue.nextTick(updateHandler);
                });
            },
            afterLoad: function (updateHandler) {
                this.$once("iloaded", function () {
                    Vue.nextTick(updateHandler);
                });
            }
        }
    });

    var ajax = new ajaxRequest(myModel.url, myView.el, function () {
        //update the model with the retrieved data
        const responseData = JSON.parse(ajax.request.responseText);
        //one page of data
        myModel.items = responseData.items;
        //total number of items available
        myModel.itemsCount = responseData.count;
        //get the list of pages and add it to the model
        const pages = pagination.listPages(responseData.count);
        myModel.pages = pages;
        //emit an event that indicates the view has finished loading data
        vueview.$emit("iloaded");
    });
    //actually send the request
    ajax.sendRequest();

    //return the view
    return vueview;
}

/*
 * Function to recursively update a view and its dependencies
 * @param Object view A vue.js view to be updated
 * @param boolean updateDependencies A bool to determine whether dependencies should be updated (default = true)
 */
function updateView(view, updateDependencies) {
    //Default update dependencies to true
    if (updateDependencies === undefined) {
        updateDependencies = true;
    }

    //get the URL from the view and modify it to add the limit and offset for the DB query
    var myUrl = view.url;
    if (view.limit > 0) {
        myUrl += "&limit=" + view.limit
    }
    if (view.offset > 0) {
        myUrl += "&offset=" + view.offset;
    }

    var ajax = new ajaxRequest(myUrl, view.$el.id, function () {
        //retrieve data and update the views model with it
        const responseData = JSON.parse(ajax.request.responseText);
        Vue.set(view, "items", responseData.items);
        Vue.set(view, "pages", pagination.listPages(responseData.count));

        view.$emit("iloaded");
        //update dependencies
        if (updateDependencies == true) {
            //get the array of dependendant views
            const viewsToUpdate = view.$get("dependencies");
            if (viewsToUpdate.length > 0) {
                for (var i = 0; i < viewsToUpdate.length; i++) {
                    //load the same sub pages for dependant views that are loaded for the root view
                    Vue.set(viewsToUpdate[i], "limit", view.limit);
                    Vue.set(viewsToUpdate[i], "offset", view.offset);
                    //recursive call
                    updateView(viewsToUpdate[i], true);
                }
            }
        }
    });
    ajax.sendRequest();
}
/*
 * Pagination for view JS views
 * @param int itemsPerPage defines how many items go on a page
 * @param function listPages return an array representing the the number of pages given a total number of items and itemsPerPage
 * @param function loadPage Load a specific page of data to a view
 * @param function loadNextPage Load the next page relative to the current one
 * @param function loadPrevPage Load the previous page relative to the current one
 */
var pagination = {
    itemsPerPage: 10,
    listPages: function (numTotal) {
        //round up the number of pages
        var pagenum = Math.ceil(numTotal / this.itemsPerPage);
        var pages = [];

        //loop through the pages and create a page object for each page, having its index and limit
        for (var i = 0; i < pagenum; i++) {
            var index = i + 1;
            var page = {
                index: index,
                limit: index * this.itemsPerPage
            };
            pages.push(page);
        }

        return pages;

    },
    loadPage: function (view, page) {

        //calculate the offset for the DB query
        var offset = page * this.itemsPerPage - this.itemsPerPage;

        //set the new limit and offset to the view
        Vue.set(view, "limit", this.itemsPerPage);
        Vue.set(view, "offset", offset);

        //view.$set("limit", this.itemsPerPage);
        //view.$set("offset", offset);
        //update the current page for the view
        Vue.set(view, "currentPage", page);
        //view.$set("currentPage", page);

        //triger the view to be updated
        view.update(true);

    },
    loadNextPage: function (view) {
        //get current page
        var currentPage = view.$get("currentPage");
        //get total number of pages
        var numberOfPages = Math.ceil(view.$get("itemsCount") / this.itemsPerPage);

        //increment by one
        var nextPage = currentPage + 1;

        //if the next page would be beyond the last page, set nextPage to lastPage
        if (nextPage > numberOfPages) {
            nextPage = numberOfPages;
        }

        this.loadPage(view, nextPage);
    },
    loadPrevPage: function (view) {
        //get current page
        var currentPage = view.$get("currentPage");

        var nextPage = currentPage - 1;

        if (nextPage < 1) {
            nextPage = 1;
        }
        this.loadPage(view, nextPage);
    }
};

/*
 * Function to asyncronously submit a form
 * This is to be used with form.addEventListener()
 * the event gets automatically passed in
 * formView has to be passed in with bind(formView)
 * ex: formElement.addEventListener("submit", submitForm.bind(formView));
 *
 */
function submitForm(event) {
    //get the form
    var theForm = event.currentTarget;
    //get the server url for the ajax call from the action = attribute of the <form>
    var url = theForm.action;
    //validate the form
    var validates = validateCompleteForm(theForm);

    //stop the form event from bubbling up. stops form submit
    event.stopPropagation();
    event.preventDefault();

    if (validates == true) {
        //string holding the final post body
        var postBody = "";
        //loop over form elements
        for (i = 0; i < theForm.elements.length; i++) {
            //one element
            var element = theForm.elements[i];
            //construct post body
            if (element.value != undefined) {
                postBody += "&" + element.name + "=" + element.value;
            }
        }
        //send the ajax request
        var ajax = new ajaxRequest(url, "", function () {
            var response = ajax.request.responseText;
            if (response == "ok") {
                //update the view belonging to the form
                formView.update(false);
                //show system message for element added
                systemMessage.added(formView.$get("itemType"));
                //try calling the formSubmited() handler that can be defined
                try {
                    formSubmited();
                }
                catch (e) {
                }
            }
        });
        ajax.requestType = "POST";
        ajax.postBody = postBody;
        ajax.sendRequest();
    }
}

/*
 Show loading indicator
 */
function startWait(indic) {
    document.getElementById(indic).style.display = "block";
}
/*
 hide loading indicator
 */
function stopWait(indic) {
    document.getElementById(indic).style.display = 'none';
}
//endcolor for close element flashing
closeEndcolor = '#377814';
//endcolor for delete element flashing
deleteEndcolor = '#c62424';
/*
 * Function to display a confirm prompt, then call deleteElement();
 */
function confirmDelete(message, element, url, view) {
    var check = confirm(message);
    if (check == true) {
        deleteElement(element, url, view);
    }
}
/*
 * Function to delete an element from the datamodel / db as well as the DOM
 */
function deleteElement(theElement, theUrl, theView) {
    var ajax = new ajaxRequest(theUrl, "", function () {
        //check if server returns OK response code
        if (ajax.request.responseText == "ok") {
            //remove the DOM element animated
            removeRow(theElement, deleteEndcolor);
            //if a view is passed in, update the view and emit a system message
            if (theView != undefined) {
                theView.update();
                systemMessage.deleted(theView.$get("itemType"));
            }
            var result = true;
        }
    });

    ajax.sendRequest();
}
function closeElement(theElement, theUrl, theView) {
    var ajax = new ajaxRequest(theUrl, "", function () {
        //check if server returns OK response code
        if (ajax.request.responseText == "ok") {
            //remove the DOM element animated
            removeRow(theElement, closeEndcolor);
            //if a view is passed in, update the view and emit a system message
            if (theView != undefined) {
                theView.update();
                systemMessage.closed(theView.$get("itemType"));
            }
            var result = true;
        }
    });

    ajax.sendRequest();
}
function removeRow(row, color) {
    var rowElement = document.getElementById(row);
    if (rowElement != null) {
        //set bg color to white
        rowElement.style.backgroundColor = "#FFFFFF";
        /*
         * Velocity animation
         * The first call animated the background color
         * When this animation completes, a 2nd call is made to fade out the cell after a delay
         */
        Velocity(rowElement, {
            backgroundColor: color,
            backgroundColorAlpha: 0.6,
            colorAlpha:0.6
        }, {
            complete: function () {
                Velocity(rowElement, "fadeOut", {
                    delay: 1500,
                    duration: 2500
                });
            }
        });
    }
}

/*
 * Register a vue filter to limit the length of strings rendered
 */
Vue.filter("truncate", function (value, maxLength) {
    if (value.length >= maxLength) {
        return value.substr(0, maxLength) + "...";
    }
    else {
        return value;
    }
});