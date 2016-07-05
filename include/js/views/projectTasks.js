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

function initTasklistViews() {
    var taskLists = document.getElementsByClassName("blockaccordion_content");

    for (var a = 0; a < taskLists.length; a++) {
        var taskListID = taskLists[a].dataset.list;
        var projectID = taskLists[a].dataset.project;
        var taskListElement = taskLists[a].id;

        var projectTasks = {
            el: taskListElement,
            itemType: "task",
            url: "managetask.php?action=projectTasks&tlid=" + taskListID + "&id=" + projectID,
            dependencies: []
        };

        var projectTasksView = createView(projectTasks);

        var addTaskForm = document.getElementById("addtaskform" + taskListID);
        formView = projectTasksView;
        addTaskForm.addEventListener("submit", submitForm.bind(formView));


        projectTasksView.afterLoad(function(){
            console.log("formview after update");

        });
        var accord = new accordion2(taskListElement);


        console.log(projectTasks);

    }
    console.log(taskLists);

}
initTasklistViews();