
function taskFormHandler() {
    this.forms = [];
    this.views = [];
}

/*
 * Bind submit forms onsubmit event to the submit Handler
 */
taskFormHandler.prototype.bindViews = function () {
    var forms = this.forms;
    var views = this.views;
    for (var i = 0; i < forms.length; i++) {
        forms[i].onsubmit = function (event) {
            var viewIndex = event.currentTarget.dataset.index;
            event.preventDefault();
            event.stopPropagation();
            handleForm(event, views[viewIndex]);
        }
    }
};
var handleForm = function (event, view) {
    //Default update dependencies to true
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
        for (var i = 0; i < theForm.elements.length; i++) {
            //one element
            var element = theForm.elements[i];
            //construct post body
            //if multiple is set, its a select element
            if(element.attributes.multiple != undefined)
            {
                var selectPostStr = "";
                //loop over the options and assign the selected values to the post body
                for(var j=0;j<element.options.length;j++)
                {
                    var option = element.options[j];
                    if(option.selected){
                         selectPostStr += "&" + element.name + "=" + encodeURIComponent(option.value);
                    }
                }
                if(selectPostStr != ""){
                    postBody += selectPostStr;
                }
            }
            else if (element.value != undefined) {
                //these are fields with single values. assign the element value
                postBody += "&" + element.name + "=" + encodeURIComponent(element.value);
            }
        }

        //send the ajax request
        var ajax = new ajaxRequest(url, "", function () {
            var response = ajax.request.responseText;
            if (response == "ok") {
                //update the view belonging to the form
                view.update();
                //show system message for element added
                systemMessage.added(view.$get("itemType"));
                //try calling the formSubmited() handler that can be defined

                var tasklistID = theForm.dataset.tasklist;
                blindtoggle("form_" + tasklistID);
                toggleClass("add_butn_" + tasklistID, "butn_link_active", "butn_link");
                toggleClass("sm_" + tasklistID, "smooth", "nosmooth");
            }
            else
            {
                console.log(response);
            }
        });
        ajax.requestType = "POST";
        ajax.postBody = postBody;
        ajax.sendRequest();
    }
};

/*
 * Handler function that will be bound to the onclick even of the close buttons
 */
function handleStatus(event, status) {
    var closeToggle = event.target;
    var viewIndex = closeToggle.dataset.viewindex;
    var taskID = closeToggle.dataset.task;
    var projectID = closeToggle.dataset.project;

    var url;
    if (status == "open") {
        url = "managetask.php?action=open&tid=" + taskID + "id=" + projectID;
    }
    else if (status == "close") {
        url = "managetask.php?action=close&tid=" + taskID + "id=" + projectID;
    }
    closeElement("task_" + taskID, url, projectTaskViews[viewIndex]);
}
/*
 * Handler function that will be bound to the onclick even of the delete buttons
 */
function handleDelete(event) {
    var closeToggle = event.target;
    var viewIndex = closeToggle.dataset.viewindex;
    var taskID = closeToggle.dataset.task;
    var projectID = closeToggle.dataset.project;
    var confirmText = closeToggle.dataset.confirmtext;

    confirmDelete(confirmText, "task_" + taskID, "managetask.php?action=del&tid=" + taskID + "id=" + projectID, projectTaskViews[viewIndex]);
}

function initTasklistViews() {
    var formManager = new taskFormHandler();
    var taskLists = cssAll(".blockaccordion_content");

    projectTaskViews = [];
    accordeons = [];
    for (var i = 0; i < taskLists.length; i++) {
        var taskListID = taskLists[i].dataset.tasklist;
        var projectID = taskLists[i].dataset.project;
        var taskListElementID = taskLists[i].id;

        //create view
        var projectTasksView = createView({
            el: taskListElementID,
            itemType: "task",
            url: "managetask.php?action=projectTasks&tlid=" + taskListID + "&id=" + projectID,
            dependencies: []
        });

        //run after each data update
        projectTasksView.afterUpdate(function () {
            //get all tasklists and create an accordion for each one
            var taskLists = cssAll(".taskList");
            for (var a = 0; a < taskLists.length; a++) {
                accordeons.push(new accordion2(taskLists[a].id))
            }

            //get the close toggles and bind the close handler
            var closeToggles = cssAll(".closeElement");
            for (var b = 0; b < closeToggles.length; b++) {
                closeToggles[b].onclick = function (event) {
                    handleStatus(event, "close");
                }
            }

            //get the delete toggles and bind the delete handler
            var reopenToggles = cssAll(".openElement");
            for (var d = 0; d < reopenToggles.length; d++) {
                reopenToggles[d].onclick = function (event) {
                    handleStatus(event, "open");
                }

            }

            //get the delete toggles and bind the delete handler
            var deleteToggles = cssAll(".deleteElement");
            for (var c = 0; c < deleteToggles.length; c++) {
                deleteToggles[c].onclick = function (event) {
                    handleDelete(event);
                }

            }
        });
        projectTaskViews.push(projectTasksView);
    }

    formManager.forms = document.getElementsByClassName("taskSubmitForm");
    formManager.views = projectTaskViews;

    formManager.bindViews();
}
//create blockaccordion
var accord_dashboard = new accordion2('blockTasks', {
    classNames: {
        toggle: 'win_none',
        toggleActive: 'win_block',
        content: 'blockaccordion_content'
    }
});

/*
 * Function to be bound to the onclick handlers of the block accordoen
 */
function activateAccordeon(theAccord) {
    accord_dashboard.toggle(cssAll('#blockTasks .blockaccordion_content')[theAccord]);
    setCookie("activeSlideProjectTasks", theAccord);
}
/*
 * Initialize block accordeon
 */
window.addEventListener("load", function () {
    var theBlocks = cssAll("#blockTasks > div[class~='headline'] > a");

    //loop through the blocks and add the accordion toggle link
    var openSlide = 0;
    for (var i = 0; i < theBlocks.length; i++) {
        var theAction = theBlocks[i].getAttribute("onclick");
        theAction += "activateAccordeon(" + i + ");";
        theBlocks[i].setAttribute("onclick", theAction);
    }
    activateAccordeon(0);

});
window.addEventListener("load", initTasklistViews());