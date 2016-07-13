var accord_dashboard = new accordion2('blockTasks', {
    classNames: {
        toggle: 'win_none',
        toggleActive: 'win_block',
        content: 'blockaccordion_content'
    }
});
function activateAccordeon(theAccord) {
    accord_dashboard.toggle(cssAll('#blockTasks .blockaccordion_content')[theAccord]);
    setCookie("activeSlideProjectTasks", theAccord);
}


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
function taskFormHandler() {
    this.forms = [];
    this.views = [];
}

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

function handleClose(event){
    var closeToggle = event.target;
    var viewIndex = closeToggle.dataset.viewindex;
    var taskID = closeToggle.dataset.task;
    var projectID = closeToggle.dataset.project;

    closeElement(closeToggle.id,"managetask.php?action=close&tid="+taskID+"id="+projectID, projectTaskViews[viewIndex]);
}
function handleDelete(event){
    var closeToggle = event.target;
    var viewIndex = closeToggle.dataset.viewindex;
    var taskID = closeToggle.dataset.task;
    var projectID = closeToggle.dataset.project;
    var confirmText = closeToggle.dataset.confirmtext;

   confirmDelete(confirmText,"task_"+taskID,"managetask.php?action=del&tid="+taskID+"id="+projectID, projectTaskViews[viewIndex]);
}

function initTasklistViews() {
    var formManager = new taskFormHandler();
    var taskLists = cssAll(".blockaccordion_content");

    projectTaskViews = [];
    for (var i = 0; i < taskLists.length; i++) {
        var taskListID = taskLists[i].dataset.tasklist;
        var projectID = taskLists[i].dataset.project;
        var taskListElement = taskLists[i].id;

        var projectTasksView = createView({
            el: taskListElement,
            itemType: "task",
            url: "managetask.php?action=projectTasks&tlid=" + taskListID + "&id=" + projectID,
            dependencies: []
        });
        projectTaskViews.push(projectTasksView);
    }


    accordeons = [];
    projectTaskViews[projectTaskViews.length-1].afterUpdate(function(){
        var closeToggles = cssAll(".closeElement");
        for(var j=0;j<closeToggles.length;j++)
        {
            closeToggles[j].onclick = function(event)
            {
                handleClose(event);
            }

        }

        var deleteToggles = cssAll(".deleteElement");
        for(var z=0;z<deleteToggles.length;z++)
        {
            deleteToggles[z].onclick = function(event)
            {
                handleDelete(event);
            }

        }

        var taskLists = cssAll(".taskList");
        for(var a=0;a<taskLists.length;a++)
        {
            accordeons.push(new accordion2(taskLists[a].id))
        }
    });
    formManager.forms = document.getElementsByClassName("taskSubmitForm");
    formManager.views = projectTaskViews;

    formManager.bindViews();

}
initTasklistViews();