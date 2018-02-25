//function to update the calendar
function updateCalendar(myCalendar, newMonth, newYear) {
    var currentUrl = myCalendar.$get("url");
    var calendarUrl = currentUrl + "&y=" + newYear + "&m=" + newMonth;
    Vue.set(myCalendar, "url", calendarUrl);
    updateView(myCalendar);

}

/*
 * Handler function to be called when form was successfully submited
 */
function formSubmited() {
    blindtoggle('form_addmyproject');
    toggleClass('sm_deskprojects', 'smooth', 'nosmooth');
    toggleClass("add_butn_myprojects", 'butn_link_active', 'butn_link');
}

function initializeBlockaccordeon() {

    //get the blocks
    var theBlocks = document.querySelectorAll("#block_index > div .headline > a");
    //loop through the blocks and add the accordion toggle link to the onclick handler of toggles
    for (var i = 0; i < theBlocks.length; i++) {
        //get the id of the current html element
        var theId = theBlocks[i].getAttribute("id");
        blockIds.push(theId);

        //get the index of the last opened block
        var theCook = readCookie("activeSlideIndex");

        //console.log(theCook);
        var openSlide;
        if (theCook > 0) {
            openSlide = theCook;
        }

        //get the onclick action of the current block
        var theAction = theBlocks[i].getAttribute("onclick");
        //add a call to activate accordeon
        theAction += "activateAccordeon(" + i + ");";
        theBlocks[i].setAttribute("onclick", theAction);
    }
    //activateAccordeon(openSlide);
    activateAccordeon(0);
}
/**
 * This will activate the accordion with the supplied index
 */
openSlide = 0;
blockIds = [];
function activateAccordeon(theAccord) {
    //activate the block in the block accordion
    accordIndex.toggle(document.querySelectorAll('#block_index .blockaccordion_content')[theAccord]);
    //set a cookie to save the accordeon last clicked
    setCookie("activeSlideIndex", theAccord);
}


//initialize blocks accordeon
//this creates the object on which methods are called later
var accordIndex = new accordion2('block_index', {
    classNames: {
        toggle: 'win_none',
        toggleActive: 'win_block',
        content: 'blockaccordion_content'
    }
});

/*
 * Render a treeview of tasklists for a milestone
 */
function renderMilestones(treeItems, treeName) {
    var basicImgPath = "templates/standard/theme/standard/images/symbols/";
    //loop over all top level items for the tree
    for (var i = 0; i < treeItems.length; i++) {
        //ID of the current item to draw a tree for
        var itemId = treeItems[i].ID;
        //current milestone
        var milestone = treeItems[i].milestones;
        //initialise tree component
        var milestoneTree = new dTree(treeName + itemId);
        milestoneTree.add(0, -1, '');

        //add the milestone
        milestoneTree.add("ml" + milestone.ID, 0, milestone.name, "#", "", "", basicImgPath + "milestone.png", basicImgPath + "milestone.png", true);


        var tasklists = milestone.tasklists;
        if (tasklists != undefined) {
            //loop over tasklists
            for (var j = 0; j < tasklists.length; j++) {
                var tasklist = tasklists[j];
                //tasks for this list
                var tasklistTasks = tasklist.tasks;

                //add tasklist to tree
                milestoneTree.add("tl" + tasklist.ID, "ml" + milestone.ID, tasklist.name, "managetasklist.php?action=showtasklist&id=" + tasklist.project + "&tlid=" + tasklist.ID, "", "", basicImgPath + "tasklist.png", basicImgPath + "tasklist.png", true);

                if (tasklistTasks.length > 0) {
                    //loop tasks in this list
                    for (var k = 0; k < tasklistTasks.length; k++) {
                        //add task to project tree
                        milestoneTree.add("ta" + tasklistTasks[k].ID, "tl" + tasklistTasks[k].liste, tasklistTasks[k].title, "managetask.php?action=showtask&tid=" + tasklistTasks[k].ID + "&id=" + tasklistTasks[k].project, "", "", basicImgPath + "task.png", basicImgPath + "task.png", "", tasklistTasks[k].daysleft);
                    }
                }

            }
            //write the tree to the target element
            cssId(treeName + itemId).innerHTML = milestoneTree;
            //export global variable so the tree is clickable
            window[treeName + itemId] = milestoneTree;
        }


    }
}
function createMilestonesTree(view, treeName = "milestoneTree") {
    var treeItems = view.items;
    if (treeItems) {
        renderMilestones(treeItems, treeName)
    }
}

function renderFiles(items, treeName) {
    var basicImgPath = "templates/standard/theme/standard/images/symbols/";

    //loop over all top level items for the tree
    for (var i = 0; i < items.length; i++) {
        //ID of the current item to draw a tree for
        var itemId = items[i].ID;

        //initialise tree component
        var filesTree = new dTree(treeName + itemId);
        filesTree.add(0, -1, '');

        var hasFiles = items[i].hasFiles;
        if (hasFiles) {
            var files = items[i].files;
            for (var l = 0; l < files.length; l++) {
                filesTree.add("fi" + files[l].ID, 0, files[l].title, "managefile.php?action=downloadfile&amp;id=" + files[l].project + "&amp;file=" + files[l].ID, "", "", basicImgPath + "files.png", basicImgPath + "files.png", "", 0);
            }
            //write the tree to the target element
            cssId(treeName + itemId).innerHTML = filesTree;
            //export global variable so the tree is clickable
            window[treeName + itemId] = filesTree;
        }
    }
}

function createFilesTree(view, treeName = "filesTree") {
    var treeItems = view.items;
    if (treeItems != undefined) {
        renderFiles(treeItems, treeName);
    }
}

function renderUsers(items, treeName) {
    var basicImgPath = "templates/standard/theme/standard/images/symbols/";
    //loop over all top level items for the tree
    for (var i = 0; i < items.length; i++) {
        //ID of the current item to draw a tree for
        var itemId = items[i].ID;

        //initialise tree component
        var userTree = new dTree(treeName + itemId);
        userTree.add(0, -1, '');

        var users = items[i].members;
        for (var l = 0; l < users.length; l++) {
            userTree.add("us" + users[l].ID, 0, users[l].name, "manageuser.php?action=profile&amp;id=" + users[l].ID, "", "", basicImgPath + "user.png", basicImgPath + "user.png", "", 0);
        }
        //write the tree to the target element
        cssId(treeName + itemId).innerHTML = userTree;
        //export global variable so the tree is clickable
        window[treeName + itemId] = userTree;

    }
}

function createUsersTree(view, treeName="usersTree"){
    if(view.items.open){
        renderUsers(view.items.open, treeName);
    }

}


var projectsViewDependencies = [];
//create the objects representing the Widgets with their DOM element, DataURL, Dependencies and view managing them
var projects = {
    el: "desktopprojects",
    url: "index.php?action=myprojects",
    itemType: "project",
    sortBy: "projekt",
    sortDirection: "DESC",
    dependencies: []
};

var tasks = {
    el: "desktoptasks",
    itemType: "task",
    url: "index.php?action=mytasks",
    dependencies: []
};

var messages = {
    el: "desktopmessages",
    itemType: "message",
    url: "index.php?action=mymessages",
    dependencies: []
};

var desktopCalendar = {
    el: "desktopCalendar",
    itemType: "calendar",
    url: "manageajax.php?action=indexCalendar",
    dependencies: []
};

//create views - binding the data to the dom element
var projectsView = createView(projects);
var calendarView = createView(desktopCalendar);
//get the form to be submitted
var addProjectForm = document.getElementById("addprojectform");
//assign the view to be updated after submitting to the formView variable
var formView = projectsView;
//add an event listener capaturing the submit event of the form
//add submitForm() as the handler for the event, and bind the form view to it
addProjectForm.addEventListener("submit", submitForm.bind(formView));

projectsView.afterLoad(initializeBlockaccordeon);
//initialize accordeons

var accord_projects;
projectsView.afterUpdate(function () {
    accord_projects = new accordion2('desktopprojects');
    createUsersTree(projectsView);

});





