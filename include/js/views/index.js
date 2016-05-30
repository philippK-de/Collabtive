//function to update the calendar
function updateCalendar(myCalendar, newMonth, newYear)
{
    var currentUrl = myCalendar.$get("url");
    var calendarUrl = currentUrl + "&y=" + newYear + "&m=" + newMonth;
    Vue.set(myCalendar,"url",calendarUrl);
    updateView(myCalendar);

}

//create the objects representing the Widgets with their DOM element, DataURL, Dependencies and view managing them
var projects = {
    el: "desktopprojects",
    url: "index.php?action=myprojects",
    itemType: "project",
    dependencies: []
};

var tasks = {
    el: "desktoptasks",
    itemType: "task",
    url: "index.php?action=mytasks",
    dependencies: []
};

var messages = {
    el: "desktopmessages",
    itemType: "message",
    url: "index.php?action=mymessages",
    dependencies: []
};

var calendar = {
  el: "desktopCalendar",
  itemType: "calendar",
  url: "manageajax.php?action=indexCalendar",
  dependencies: []
};



//create views - binding the data to the dom element

var projectsView = createView(projects);
var tasksView = createView(tasks);
var msgsView = createView(messages);
var calendarView = createView(calendar);
//setup dependenciens
projectsView.$set("dependencies", [tasksView, msgsView]);


//get the form to be submitted
var addProjectForm = document.getElementById("addprojectform");
//assign the view to be updated after submitting to the formView variable
var formView = projectsView;
//add an event listener capaturing the submit event of the form
//add submitForm() as the handler for the event, and bind the form view to it
addProjectForm.addEventListener("submit", submitForm.bind(formView));


//load calendar
//changeshow('manageajax.php?action=newcal', 'thecal', 'progress');

//initialize accordeons
try {
    //var accord_projects = new accordion('projecthead');
    var accord_projects = new accordion2('desktopprojects');
    var accord_tasks = new accordion2('taskhead');
    var accord_msgs = new accordion2('activityhead');
}
catch (e) {
}


//create blocks accordeon
var accordIndex = new accordion2('block_index', {
    classNames: {
        toggle: 'win_none',
        toggleActive: 'win_block',
        content: 'blockaccordion_content'
    }
});

/**
 *
 * This will activate the accordion with the supplied index
 *
 */
// /loop through the blocks and add the accordion toggle link
openSlide = 0;
blockIds = [];
function activateAccordeon(theAccord) {
    //activate the block in the block accordion
    accordIndex.toggle(document.querySelectorAll('#block_index .blockaccordion_content')[theAccord]);
    //set a cookie to save the accordeon last clicked
    setCookie("activeSlideIndex", theAccord);
}
//get the blocks
//var theBlocks = $$("#block_index > div .headline > a");
var theBlocks = document.querySelectorAll("#block_index > div .headline > a");
//console.log(theBlocks);


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

