//function to update the calendar
function updateCalendar(myCalendar, newMonth, newYear) {
    var currentUrl = myCalendar.$get("url");
    var calendarUrl = currentUrl + "&y=" + newYear + "&m=" + newMonth;
    Vue.set(myCalendar, "url", calendarUrl);
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

var desktopCalendar = {
    el: "desktopCalendar",
    itemType: "calendar",
    url: "manageajax.php?action=indexCalendar",
    dependencies: []
};


//create views - binding the data to the dom element

var projectsView = createView(projects);
var tasksView = createView(tasks);
var msgsView = createView(messages);
var calendarView = createView(desktopCalendar);
//setup dependenciens
projectsView.$set("dependencies", [tasksView, msgsView]);

/*
 * Function to check if a modal has already been opened
 */
function checkModal() {
    var modalOpen = false;
    try {
        var modalCheck = document.getElementById("modal_container");
        if (modalCheck != null) {
            modalOpen = modalCheck;
        }
    }
    catch (e) {
    }

    return modalOpen;
}

/*
 * Function to open modal window
 */
function openModal(elementId) {
    //get the modal to be opened
    var modalElement = document.querySelector("#" + elementId);

    //check if there is already a modal open, if yes close it
    var checkModalOpen = checkModal();
    if (checkModalOpen !== false) {
        //if a modal is open, close the opened modal
        closeModal(checkModalOpen.dataset.originalid);
    }

    //set the orginalid to the current element id
    //this is needed cause the active modal always has the id modal_container
    modalElement.dataset.originalid = modalElement.id;
    modalElement.id = "modal_container";
    modalElement.style.zIndex = 99;
    modalElement.style.position = "fixed";
    modalElement.style.top = "50%";
    modalElement.style.left = "50%";

    modalElement.style.display = "block";
}

/*
* Close a modal
* @param str originalid a string representing the original element ID of the modal window.
* This is needed to reset the ID value to its original state, since the open modal window always receives the "modal_container" id
 */
function closeModal(originalid) {
    //get the element to be closed
    var modalElement = document.querySelector("#modal_container");

    //reset the the to the original ID
    modalElement.id = originalid;
    modalElement.style.display = "none";
}

calendarView.$on("iloaded", function () {
    Vue.nextTick(function () {
        console.log("next tick");
    })
});

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

