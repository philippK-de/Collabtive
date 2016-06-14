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

var accord_miles_late = new accordion2('lateMilestones');
var accord_miles_new = new accordion2('currentMilestones');
var accord_miles_upcoming = new accordion2('upcomingMilestones');

function formSubmited()
{
    console.log("formsubmitted");
    blindtoggle('addstone');
    toggleClass('add_butn_current','butn_link_active','butn_link');
    toggleClass('sm_miles','smooth','nosmooth');
}

openSlide = 0;
var blockIds = [];
function activateAccordeon(theAccord) {
    //activate the block in the block accordion
    accordIndex.toggle(document.querySelectorAll('#projectMilestones .blockaccordion_content')[theAccord]);
    //set a cookie to save the accordeon last clicked
    setCookie("activeSlideIndex", theAccord);
}

