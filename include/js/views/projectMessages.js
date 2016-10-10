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

function formSubmited()
{
    blindtoggle('addmsg');
    toggleClass('sm_msgs','smooth','nosmooth');
}