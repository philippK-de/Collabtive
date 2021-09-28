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

function createUsersTree(view, treeName = "usersTree") {
    if (view.items.open) {
        renderUsers(view.items.open, treeName);
    }

}