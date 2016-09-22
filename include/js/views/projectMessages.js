var projectMessages = {
    el: "projectMessages",
    itemType: "message",
    url: "managemessage.php?action=projectMessages",
    dependencies: []
};

var userMessages = {
    el: "userMessages",
    itemType: "message",
    url: "managemessage.php?action=userMessages",
    dependencies: []
};

var accordMessages;
function activateAccordeon(theAccord) {
    accordMessages.toggle(cssAll('#projectMessagesContainer .accordion_content')[theAccord]);
}
function initializeBlockaccordeon() {
    //get the blocks
    var theBlocks = document.querySelectorAll("#projectMessagesContainer > div .headline > a");

    //loop through the blocks and add the accordion toggle link to the onclick handler of toggles
    for (i = 0; i < theBlocks.length; i++) {
        //get the onclick action of the current block
        var theAction = theBlocks[i].getAttribute("onclick");
        //add a call to activate accordeon
        theAction += "activateAccordeon(" + i + ");";
        theBlocks[i].setAttribute("onclick", theAction);
    }
    activateAccordeon(0);
}

function formSubmited()
{
    console.log("formsubmitted");
    blindtoggle('addmsg');
    toggleClass('add_butn','butn_link_active','butn_link');
    toggleClass('sm_msgs','smooth','nosmooth');
}