var projectMilestones = {
    el: "currentMilestones",
    itemType: "milestone",
    url: "managemilestone.php?action=projectMilestones",
    dependencies: []
};

var lateProjectMilestones = {
    el: "lateMilestones",
    itemType: "milestone",
    url: "managemilestone.php?action=lateProjectMilestones",
    dependencies: []
};

var upcomingProjectMilestones = {
    el: "upcomingMilestones",
    itemType: "milestone",
    url: "managemilestone.php?action=upcomingProjectMilestones",
    dependencies: []
};


//create blocks accordeon
var accordIndex = new accordion2('projectMilestones', {
    classNames: {
        toggle: 'win_none',
        toggleActive: 'win_block',
        content: 'blockaccordion_content'
    }
});

/*
 * Render a treeview of tasklists for a milestone
 */
function renderMilestoneTree(view) {

    var openMilestones = view.items.open != undefined ? view.items.open : view.items;
    if (openMilestones != undefined) {
        var basicImgPath = "templates/standard/theme/standard/images/symbols/";
        //loop over open milestones
        for (var i = 0; i < openMilestones.length; i++) {
            var milestoneId = openMilestones[i].ID;

            //initialise tree component
            var milestoneTree = new dTree('projectTree' + openMilestones[i].ID);
            milestoneTree.add(0, -1, '');

            var tasklists = openMilestones[i].tasklists;
            if (tasklists.length > 0) {
                //loop over tasklists
                for (var j = 0; j < tasklists.length; j++) {
                    var tasklist = tasklists[j];
                    //tasks for this list
                    var tasklistTasks = tasklist.tasks;

                    //add tasklist to tree
                    milestoneTree.add("tl" + tasklist.ID, 0, tasklist.name, "managetasklist.php?action=showtasklist&id=" + tasklist.project + "&tlid=" + tasklist.ID, "", "", basicImgPath + "tasklist.png", basicImgPath + "tasklist.png", true);

                    if (tasklistTasks.length > 0) {
                        //loop tasks in this list
                        for (var k = 0; k < tasklistTasks.length; k++) {
                            //add task to project tree
                            milestoneTree.add("ta" + tasklistTasks[k].ID, "tl" + tasklistTasks[k].liste, tasklistTasks[k].title, "managetask.php?action=showtask&tid=" + tasklistTasks[k].ID + "&id=" + tasklistTasks[k].project, "", "", basicImgPath + "task.png", basicImgPath + "task.png", "", tasklistTasks[k].daysleft);
                        }
                    }

                }
            }
            var messages = openMilestones[i].messages != undefined ? openMilestones[i].messages : 0;
            if(messages.length > 0){
                for(var l=0;l<messages.length;l++) {
                    milestoneTree.add("msg" + messages[l].ID, 0, messages[l].title, "managemessage.php?action=showmessage&id=" + messages[l].projekt + "&mid=" + messages[l].ID, messages[l].text, "", basicImgPath +"msgs.png", basicImgPath + "msgs.png");
                }
            }
            //write the tree to the target element
            cssId("milestoneTree_" + milestoneId).innerHTML = milestoneTree;
            //export global variable so the tree is clickable
            window["projectTree" + milestoneId] = milestoneTree;
        }
    }
}

/*
* Function to copy a tasklist entry in the add milestone form
 */
function copyTasklist(){
    var container = cssId("tasklistsContainer");
    var field = container.lastElementChild;
    var fieldCopy = field.cloneNode(true);

    container.appendChild(fieldCopy);
}

//will be called after form has been submitted
function formSubmited() {
    blindtoggle('addstone');
    toggleClass('add_butn_current', 'butn_link_active', 'butn_link');
    toggleClass('sm_miles', 'smooth', 'nosmooth');
}

openSlide = 0;
var blockIds = [];
function activateAccordeon(theAccord) {
    //activate the block in the block accordion
    accordIndex.toggle(cssAll('#projectMilestones .blockaccordion_content')[theAccord]);
    //set a cookie to save the accordeon last clicked
    setCookie("activeSlideIndex", theAccord);
}

