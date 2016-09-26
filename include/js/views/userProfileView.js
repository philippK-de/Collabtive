/**
 * Created by philipp on 29.04.2016.
 */
var userProfileProjects = {
    el: "userProjects",
    itemType: "project",
    url: "manageuser.php?action=userProjects",
    dependencies: []
};


var userProfileTimetracker = {
    el: "userTimetracker",
    itemType: "timetracker",
    url: "manageuser.php?action=userTimetracker",
    dependencies: []
};
blockIds = [];
function activateAccordeon(theAccord) {
    accordUserprofile.toggle(cssAll('#userProfile .blockaccordion_content')[theAccord]);
}
/*
 * Function to iterate over the blocks in a blockaccordeon and initialise it correctly
 */

function initializeBlockaccordeon() {
    //get the blocks
    var theBlocks = document.querySelectorAll("#userProfile > div .headline > a");

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
