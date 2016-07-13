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

//will be called after form has been submitted
function formSubmited()
{
    blindtoggle('addstone');
    toggleClass('add_butn_current','butn_link_active','butn_link');
    toggleClass('sm_miles','smooth','nosmooth');
}

openSlide = 0;
var blockIds = [];
function activateAccordeon(theAccord) {
    //activate the block in the block accordion
    accordIndex.toggle(cssAll('#projectMilestones .blockaccordion_content')[theAccord]);
    //set a cookie to save the accordeon last clicked
    setCookie("activeSlideIndex", theAccord);
}

