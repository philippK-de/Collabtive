var accord_dashboard = new accordion2('blockTasks', {
    classNames: {
        toggle: 'win_none',
        toggleActive: 'win_block',
        content: 'blockaccordion_content'
    }
});
function activateAccordeon(theAccord) {
    accord_dashboard.toggle(document.querySelectorAll('#blockTasks .blockaccordion_content')[theAccord]);
    setCookie("activeSlideProjectTasks", theAccord);
}


window.addEventListener("load", function () {
    var theBlocks = document.querySelectorAll("#blockTasks > div[class~='headline'] > a");

    //loop through the blocks and add the accordion toggle link
    var openSlide = 0;
    for (var i = 0; i < theBlocks.length; i++) {
        var theAction = theBlocks[i].getAttribute("onclick");
        theAction += "activateAccordeon(" + i + ");";
        theBlocks[i].setAttribute("onclick", theAction);
    }
    activateAccordeon(0);

});
function formHandler() {
    this.forms = [];
    this.views = [];
    this.callbacks = [];
}

formHandler.prototype.bindViews = function () {
    var forms = this.forms;
    var views = this.views;
    for (var i = 0; i < forms.length; i++) {
        forms[i].onsubmit = function (event) {
            event.preventDefault();
            event.stopPropagation();
            handleForm(event, views[event.currentTarget.dataset.index]);
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
                view.update();
                //show system message for element added
                systemMessage.added(view.$get("itemType"));
                //try calling the formSubmited() handler that can be defined

                var tasklistID = theForm.dataset.tasklist;
                blindtoggle("form_" + tasklistID);
                toggleClass("add_butn_" + tasklistID, "butn_link_active", "butn_link");
                toggleClass("sm_" + tasklistID, "smooth", "nosmooth");
            }
        });
        ajax.requestType = "POST";
        ajax.postBody = postBody;
        ajax.sendRequest();
    }
};


function initTasklistViews() {
    var formManager = new formHandler();
    var taskLists = document.getElementsByClassName("blockaccordion_content");

    var projectTaskViews = [];
    for (var a = 0; a < taskLists.length; a++) {
        var taskListID = taskLists[a].dataset.tasklist;
        var projectID = taskLists[a].dataset.project;
        var taskListElement = taskLists[a].id;

        var projectTasksView = createView({
            el: taskListElement,
            itemType: "task",
            url: "managetask.php?action=projectTasks&tlid=" + taskListID + "&id=" + projectID,
            dependencies: []
        });

        projectTasksView.afterUpdate(function () {
            var accord = new accordion2(taskListElement);
        });

        projectTaskViews.push(projectTasksView);
    }

    formManager.forms = document.getElementsByClassName("taskSubmitForm");
    formManager.views = projectTaskViews;

    formManager.bindViews();

}
initTasklistViews();