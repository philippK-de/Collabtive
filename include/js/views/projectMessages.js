var projectMessages = {
    el: "projectMessagesContainer",
    itemType: "message",
    url: "managemessage.php?action=projectMessages",
    dependencies: []
};

var accordMessages;
openSlide = 0;
blockIds = [];
function activateAccordeon(theAccord) {
    accordMessages.toggle(cssAll('#projectMessagesContainer .blockaccordion_content')[theAccord]);
}
function initializeBlockaccordeon() {
    //get the blocks
    var theBlocks = document.querySelectorAll("#projectMessagesContainer > div .headline > a");

    //loop through the blocks and add the accordion toggle link to the onclick handler of toggles
    for (i = 0; i < theBlocks.length; i++) {
        var theId = theBlocks[i].getAttribute("id");
        blockIds.push(theId);
        //get the onclick action of the current block
        var theAction = theBlocks[i].getAttribute("onclick");
        //add a call to activate accordeon
        theAction += "activateAccordeon(" + i + ");";
        theBlocks[i].setAttribute("onclick", theAction);
    }
    activateAccordeon(0);
}

/*
 * Render a treeview of tasklists for a milestone
 */
function renderMilestoneTree(view) {

    console.log();
    var treeItems = view.items.public;
    if (treeItems != undefined) {
        var basicImgPath = "templates/standard/theme/standard/images/symbols/";
        //loop over open milestones
        for (var i = 0; i < treeItems.length; i++) {
            //ID of the current item to draw a tree for
            var itemId = treeItems[i].ID;
            //current milestone
            var milestone = treeItems[i].milestones;

            //initialise tree component
            var messageTree = new dTree('milestoneTree' + itemId);
            messageTree.add(0, -1, '');

            //add the milestone
            messageTree.add("ml" + milestone.ID, 0, milestone.name, "managetasklist.php?action=showtasklist&id=" + milestone.project + "&tlid=" + milestone.ID, "", "", basicImgPath + "tasklist.png", basicImgPath + "milestone.png", true);


            var tasklists = milestone.tasklists;
            if (tasklists.length > 0) {
                //loop over tasklists
                for (var j = 0; j < tasklists.length; j++) {
                    var tasklist = tasklists[j];
                    //tasks for this list
                    var tasklistTasks = tasklist.tasks;

                    //add tasklist to tree
                    messageTree.add("tl" + tasklist.ID, "ml" + milestone.ID, tasklist.name, "managetasklist.php?action=showtasklist&id=" + tasklist.project + "&tlid=" + tasklist.ID, "", "", basicImgPath + "tasklist.png", basicImgPath + "tasklist.png", true);

                    if (tasklistTasks.length > 0) {
                        //loop tasks in this list
                        for (var k = 0; k < tasklistTasks.length; k++) {
                            //add task to project tree
                            messageTree.add("ta" + tasklistTasks[k].ID, "tl" + tasklistTasks[k].liste, tasklistTasks[k].title, "managetask.php?action=showtask&tid=" + tasklistTasks[k].ID + "&id=" + tasklistTasks[k].project, "", "", basicImgPath + "task.png", basicImgPath + "task.png", "", tasklistTasks[k].daysleft);
                        }
                    }

                }
            }
            var files = treeItems[i].files;
            if(files.length > 0){
                for(var l=0;l<files.length;l++){
                    messageTree.add("fi" + files[l].ID, "ml" + milestone.ID, files[l].title, "managefile.php?action=downloadfile&amp;id="+files[l].project+"&amp;file="+files[l].ID, "", "", basicImgPath + "files.png", basicImgPath + "files.png", "", 0);
                }
            }
            console.log(files);
            //write the tree to the target element
            cssId("milestoneTree" + itemId).innerHTML = messageTree;
            //export global variable so the tree is clickable
            window["milestoneTree" + itemId] = messageTree;
        }
    }
}
function formSubmited()
{
    blindtoggle('addmsg');
    toggleClass('sm_msgs','smooth','nosmooth');
}