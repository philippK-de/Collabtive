Vue.config.silent = true;
//create the vue views, binding data to DOM elements
/*
 * Function to create Vue.js view.
 * It binds together an HTML element with a datasource reactively
 * @param Object myEl An object representing the HTML element to be bound
 */
function createView(myEl) {

    /*
     * Object representing the data model for the view
     * @param Array items An array representing the data to be rendered in the view
     * @param Array pages An array representing the pages for the current datasource url, given pagination parameters
     * @param int limit Represents the database LIMIT parameter for the current page of data
     * @param int offset Represents the database OFFSET parameter for the current page of data
     * @param Array dependencies A list of Vue.js views that depend on the data in  this view, and should be updated if this view is updated
     * @param String url The data URL that delivers the models items
     */
    var myModel = {
        items: [],
        pages: [],
        limit: pagination.itemsPerPage,
        offset: 0,
        currentPage: 1,
        itemsCount: 0,
        itemType: myEl.itemType,
        dependencies: myEl.dependencies,
        url: myEl.url
    };

    /*
     * Send asyncronous request to the url specified in the model.
     *
     */
    new Ajax.Request(myModel.url, {
            method: 'get',
            onSuccess: function (myData) {
                //update the model with the retrieved data
                console.log("url " + myModel.url);
                var responseData = JSON.parse(myData.responseText);
                //one page of data
                myModel.items = responseData.items;
                //total number of items available
                myModel.itemsCount = responseData.count;
                //get the list of pages and add it to the model
                var pages = pagination.listPages(responseData.count);
                myModel.pages = pages;

            },
            onLoading: function () {
                //show loading indicator
                startWait("progress" + myEl.el);
            },
            onComplete: function () {
                //hide loading indicator
                stopWait("progress" + myEl.el);
                //emit an event that indicates the view has finished loading data
                vueview.$emit("iloaded");
            },
            onFailure: function () {

            }
        }
    );
    //create the Vue.js view given the element myEl and the data in myModel, and return the view
    var vueview = new Vue({
        el: "#" + myEl.el,
        data: myModel
    });
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
    //send asyncronous request
    new Ajax.Request(myUrl, {
            method: 'get',
            onSuccess: function (myData) {
                //retrieve data and update the views model with it
                var responseData = JSON.parse(myData.responseText);
                view.$set("items", responseData.items);
                view.$set("pages", pagination.listPages(responseData.count));


                //update dependencies
                if (updateDependencies == true) {
                    //get the array of dependendant views
                    var viewsToUpdate = view.$get("dependencies");

                    if (viewsToUpdate.length > 0) {
                        for (var i = 0; i < viewsToUpdate.length; i++) {
                            //load the same sub pages for dependant views that are loaded for the root view
                            viewsToUpdate[i].$set("limit", view.limit);
                            viewsToUpdate[i].$set("offset", view.offset);
                            //recursive call
                            updateView(viewsToUpdate[i], viewsToUpdate[i].url);
                        }
                    }
                }
            },
            onLoading: function () {
                startWait("progress" + view.$el.id);
            },
            onComplete: function () {
                stopWait("progress" + view.$el.id);
                view.$emit("updated");
            },
            onFailure: function () {

            }
        }
    );
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
        Vue.set(view,"limit",this.itemsPerPage);
        Vue.set(view,"offset",offset);

        //view.$set("limit", this.itemsPerPage);
        //view.$set("offset", offset);
        //update the current page for the view
        Vue.set(view, "currentPage",page);
        //view.$set("currentPage", page);

        //triger the view to be updated
        updateView(view, true);

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
    //get the submit url
    var url = theForm.action;
    //validate the form
    var validates = validateCompleteForm(theForm);

    console.log("validates:" + validates);

    //stop the form event from bubbling up. stops form submit
    event.stopPropagation();
    event.preventDefault();

    if (validates == true) {

        //string holding the final post body
        var pbody = "";
        //loop over form elements
        for (i = 0; i < theForm.elements.length; i++) {
            //one element
            var element = theForm.elements[i];
            //construct post body
            if (element.value != undefined) {
                pbody += "&" + element.name + "=" + element.value;
            }
        }

        //send asyncronous request
        new Ajax.Request(url, {
                method: "POST",
                postBody: pbody,
                onSuccess: function (myData) {
                    if (myData.status == 200) {
                        //update the view belonging to the form
                        updateView(formView, false);
                        //show system message for element added
                        systemMessage.added(formView.$get("itemType"));
                    }
                },
                onLoading: function () {
                    //show loading indicator
                    startWait("progress" + formView.$el);
                },
                onComplete: function () {
                    //hide loading indicator
                    console.log("added");

                    stopWait("progress" + formView.$el);
                },
                onFailure: function () {
                    console.log("error");
                }
            }
        );

    }
}

/*
 Show loading indicator
 */
function startWait(indic) {

    document.getElementById(indic).style.display = 'block';
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
function confirmDelete(message, element, url, view) {
    var check = confirm(message);
    if (check == true) {
        deleteElement(element, url, view);
    }
}
function deleteElement(theElement, theUrl, theView) {
    new Ajax.Request(theUrl, {
        method: 'get',
        onSuccess: function (payload) {
            //check if server returns OK response code
            if (payload.responseText == "ok") {
                //remove the DOM element animated
                removeRow(theElement, deleteEndcolor);
                //if a view is passed in, update the view and emit a system message
                if (theView != undefined) {
                    updateView(theView);
                    systemMessage.deleted(theView.$get("itemType"));
                }
                var result = true;
            }
        }
    });
}
function closeElement(theElement, theUrl, theView) {

    new Ajax.Request(theUrl, {
        method: 'get',
        onSuccess: function (payload) {
            if (payload.responseText == "ok") {
                //remove the DOM element animated
                try {
                    removeRow(theElement, closeEndcolor);
                }
                catch (e) {
                }
                //if a view is passed in, update the view and emit a system message
                if (theView != undefined) {
                    updateView(theView);
                    systemMessage.closed(theView.$get("itemType"));
                }

            }
        }
    });

}
/*
 * VUE COMPONENTS
 * Vue components are JS objects that represent dynamic HTML fragments.
 * These can be bound to custom HTML elements - i.e. <element></element>
 */

/*
 * Pagination Component
 * @param obj view a Vue JS view to be paginated
 * @param obj pages a pagination array, representing the available pages for this view
 * @param obj current page Object representing the currently opened page for this view
 */
var paginationComponent = Vue.extend({
    props: ["view", "pages", "currentPage"],
    template: "<template v-if='pages.length > 1'>" +
    "<span id=\"paging\" style=\"margin-left:10px;\"> " +
    "<button style=\"float:none;font-size:9pt;margin:0 1px 0 1px;\" onclick=\"pagination.loadPrevPage({{view}})\"><<</button>" +
    "<span id=\"page{{page.index}}\" v-for=\"page in pages\" style=\"margin-left:2px;\"> " +
    "<button v-bind:style=\"currentPage == page.index ? 'font-size:18px;color:red;float:none;margin:0 0 0 0;' : 'font-size:9pt;float:none;margin:0 0 0 0;'\" onclick=\"pagination.loadPage({{view}},{{page.index}});\">{{page.index}}</button> " +
    "</span> " +
    "<button style=\"float:none;font-size:9pt;margin:0 1px 0 1px;\" onclick=\"pagination.loadNextPage({{view}})\">>></button> " +
    "</span>" +
    "</template>"
});
//bind to <pagination> element
Vue.component("pagination", paginationComponent);

/*
 * Progress element
 * This component renders a visual loading indictaor
 * @param str block The block the loader corresponds to - needed for startWait() / stopWait()
 * @param str loader the image to be shown
 */
var progressComponent = Vue.extend({
    props: ["block", "loader"],
    template: "<div class=\"progress\" id=\"progress{{block}}\" style=\"float:left;display:none;\"> " +
    "   <img src=\"templates/standard/theme/standard/images/symbols/{{loader}}\"/> " +
    "</div>"
});
//bind to <loader> elemet
Vue.component("loader", progressComponent);
