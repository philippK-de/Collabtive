Vue.config.silent = true;
//create the vue views, binding data to DOM elements
projectsView = createView("desktopprojects","index.php?action=myprojects");
tasksView = createView("desktoptasks","index.php?action=mytasks");
msgsView = createView("desktopmessages","index.php?action=mymessages");


//initialize accordeons
try {
    var accord_projects = new accordion('projecthead');
}
catch (e) {
}
try {
    var accord_tasks = new accordion('taskhead');
}
catch (e) {
}
try {
    var accord_msgs = new accordion('activityhead');
}
catch (e) {
}
//load calendar
changeshow('manageajax.php?action=newcal', 'thecal', 'progress');

//create blocks accordeon
var accordIndex = new accordion('block_index', {
    classNames: {
        toggle: 'acc_toggle',
        toggleActive: 'acctoggle_active',
        content: 'acc_content'
    }
});

/**
 *
 * This will activate the accordion with the supplied index
 *
 **/
function activateAccordeon(theAccord) {
    //activate the block in the block accordion
    accordIndex.activate($$('#block_index .acc_toggle')[theAccord]);
    //change the state of the arrow in the titlebar
    changeElements("#" + blockIds[theAccord] + " > a.win_block", "win_none");
    //set a cookie to save the accordeon last clicked
    setCookie("activeSlideIndex", theAccord);
}
//get the blocks
var theBlocks = $$("#block_index > div .headline > a");
//console.log(theBlocks);

//loop through the blocks and add the accordion toggle link
openSlide = 0;
blockIds = [];
for (i = 0; i < theBlocks.length; i++) {
    //get the id of the current html element
    var theId = theBlocks[i].getAttribute("id");

    blockIds.push(theId);
    //get the index of the last opened block
    theCook = readCookie("activeSlideIndex");
    //console.log(theCook);
    if (theCook > 0) {
        openSlide = theCook;
    }

    //get the onclick action of the current block
    var theAction = theBlocks[i].getAttribute("onclick");
    //add a call to activate accordeon
    theAction += "activateAccordeon(" + i + ");";
    theBlocks[i].setAttribute("onclick", theAction);
    //console.log(theBlocks[i].getAttribute("onclick"));
}


//accordIndex.activate($$('#block_index .acc_toggle')[0]);
//activateAccordeon(openSlide);
activateAccordeon(0);

