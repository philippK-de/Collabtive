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
    var treeItems = view.items.public;
    //if there is private messages, merge them into the tree items array
    if (view.items.private.length > 0) {
        treeItems = treeItems.concat(view.items.private);
    }

    if (treeItems != undefined) {
        var basicImgPath = "templates/standard/theme/standard/images/symbols/";
        //loop over all top level items for the tree
        for (var i = 0; i < treeItems.length; i++) {
            //ID of the current item to draw a tree for
            var itemId = treeItems[i].ID;
            //current milestone
            var milestone = treeItems[i].milestones;
            //initialise tree component
            var messageTree = new dTree('milestoneTree' + itemId);
            messageTree.add(0, -1, '');

            //add the milestone
            messageTree.add("ml" + milestone.ID, 0, milestone.name, "managemilestone.php?action=showproject&id=" + milestone.project, "", "", basicImgPath + "tasklist.png", basicImgPath + "milestone.png", true);
            var tasklists = milestone.tasklists;
            if (tasklists != undefined) {
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
                //write the tree to the target element
                cssId("milestoneTree" + itemId).innerHTML = messageTree;
                //export global variable so the tree is clickable
                window["milestoneTree" + itemId] = messageTree;
            }


        }
    }
}
function renderFilesTree(view) {
    var treeName = "filesTree";
    var basicImgPath = "templates/standard/theme/standard/images/symbols/";

    var treeItems = view.items.public;
    //if there is private messages, merge them into the tree items array
    if (view.items.private.length > 0) {
        treeItems = treeItems.concat(view.items.private);
    }

    if (treeItems != undefined) {
        //loop over all top level items for the tree
        for (var i = 0; i < treeItems.length; i++) {
            //ID of the current item to draw a tree for
            var itemId = treeItems[i].ID;

            //initialise tree component
            var messageTree = new dTree(treeName + itemId);
            messageTree.add(0, -1, '');

            var hasFiles = treeItems[i].hasFiles;
            if (hasFiles) {
                var files = treeItems[i].files;
                for (var l = 0; l < files.length; l++) {
                    messageTree.add("fi" + files[l].ID, 0, files[l].title, "managefile.php?action=downloadfile&amp;id=" + files[l].project + "&amp;file=" + files[l].ID, "", "", basicImgPath + "files.png", basicImgPath + "files.png", "", 0);
                }
                //write the tree to the target element

                cssId(treeName + itemId).innerHTML = messageTree;
                //export global variable so the tree is clickable
                window[treeName + itemId] = messageTree;
            }
        }
    }
}
function formSubmited() {
    blindtoggle('addmsg');
    toggleClass('sm_msgs', 'smooth', 'nosmooth');
}