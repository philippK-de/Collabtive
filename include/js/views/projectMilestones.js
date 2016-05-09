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

var accord_miles_late = new accordion2('lateMilestones');
var accord_miles_new = new accordion2('currentMilestones');
var accord_miles_upcoming = new accordion2('upcomingMilestones');

//create blocks accordeon
var accordIndex = new accordion2('projectMilestones', {
    classNames: {
        toggle: 'win_none',
        toggleActive: 'win_block',
        content: 'blockaccordion_content'
    }
});

// /loop through the blocks and add the accordion toggle link
openSlide = 0;
blockIds = [];
function activateAccordeon(theAccord) {
    //activate the block in the block accordion
    accordIndex.toggle(document.querySelectorAll('#projectMilestones .blockaccordion_content')[theAccord]);
    //set a cookie to save the accordeon last clicked
    setCookie("activeSlideIndex", theAccord);
}
//var theBlocks = $$("#block_dashboard > div .headline > a");
var theBlocks = document.querySelectorAll("#projectMilestones > div[class~='headline'] > a");

//loop through the blocks and add the accordion toggle link
openSlide = 0;
for(i=0;i<theBlocks.length;i++)
{
    theCook = readCookie("activeSlideProject");
    if(theCook > 0)
    {
        openSlide = theCook;
    }

    var theAction = theBlocks[i].getAttribute("onclick");
    theAction += "activateAccordeon("+i+");";
    theBlocks[i].setAttribute("onclick",theAction);
    //console.log(theBlocks[i].getAttribute("onclick"));
}

activateAccordeon(1);
