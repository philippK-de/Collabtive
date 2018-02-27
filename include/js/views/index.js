//function to update the calendar
function updateCalendar(myCalendar, newMonth, newYear) {
    var currentUrl = myCalendar.$get("url");
    var calendarUrl = currentUrl + "&y=" + newYear + "&m=" + newMonth;
    Vue.set(myCalendar, "url", calendarUrl);
    updateView(myCalendar);

}

/*
 * Handler function to be called when form was successfully submited
 */
function formSubmited() {
    blindtoggle('form_addmyproject');
    toggleClass('sm_deskprojects', 'smooth', 'nosmooth');
    toggleClass("add_butn_myprojects", 'butn_link_active', 'butn_link');
}

function initializeBlockaccordeon() {

    //get the blocks
    var theBlocks = document.querySelectorAll("#block_index > div .headline > a");
    //loop through the blocks and add the accordion toggle link to the onclick handler of toggles
    for (var i = 0; i < theBlocks.length; i++) {
        //get the id of the current html element
        var theId = theBlocks[i].getAttribute("id");
        blockIds.push(theId);

        //get the index of the last opened block
        var theCook = readCookie("activeSlideIndex");

        //console.log(theCook);
        var openSlide;
        if (theCook > 0) {
            openSlide = theCook;
        }

        //get the onclick action of the current block
        var theAction = theBlocks[i].getAttribute("onclick");
        //add a call to activate accordeon
        theAction += "activateAccordeon(" + i + ");";
        // theBlocks[i].setAttribute("onclick", theAction);

    }
    //activateAccordeon(openSlide);
    activateAccordeon(0);
}
/**
 * This will activate the accordion with the supplied index
 */
openSlide = 0;
blockIds = [];
function activateAccordeon(theAccord) {
    //activate the block in the block accordion
    accordIndex.toggle(document.querySelectorAll('#block_index .blockaccordion_content')[theAccord]);
    //set a cookie to save the accordeon last clicked
    setCookie("activeSlideIndex", theAccord);
}


//initialize blocks accordeon
//this creates the object on which methods are called later
var accordIndex = new accordion2('block_index', {
    classNames: {
        toggle: 'win_none',
        toggleActive: 'win_block',
        content: 'blockaccordion_content'
    }
});




var projectsViewDependencies = [];
//create the objects representing the Widgets with their DOM element, DataURL, Dependencies and view managing them
var projects = {
    el: "desktopprojects",
    url: "index.php?action=myprojects",
    itemType: "project",
    sortBy: "projekt",
    sortDirection: "DESC",
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

var calendarView;

var tasksView;
var accord_tasks;

var messagesView;
var accord_msgs;
function initialiseView(viewName) {
    if (viewName == "tasks") {
        if (!tasksView) {
            tasksView = createView(tasks);
            //add this view to the dependencies of projectsView
            projectsViewDependencies.push(tasksView);

            // open the slide after data has loaded
            tasksView.afterLoad(function () {
                activateAccordeon(1);
            });
            tasksView.afterUpdate(function () {
                accord_tasks = new accordion2('desktoptasks');
            });
        }
        else {
            tasksView.update();
            // open the slide right away
            activateAccordeon(1);
        }
    }
    if (viewName == "calendar") {
        if (!calendarView) {
            calendarView = createView(desktopCalendar);
            // open the slide after data has loaded
            calendarView.afterLoad(function () {
                activateAccordeon(2);
            });
        }
        else {
            calendarView.update();
            // open the slide right away
            activateAccordeon(2);
        }

    }
    if (viewName == "messages") {
        if (!messagesView) {
            messagesView = createView(messages);
            //add this view to the dependencies of projectsView
            projectsViewDependencies.push(messagesView);

            // open the slide after data has loaded
            messagesView.afterLoad(function () {
                activateAccordeon(3);
            });
            messagesView.afterUpdate(function () {
                accord_msgs = new accordion2('desktopmessages');
            });

        }
        else {
            messagesView.update();
            // open the slide right away
            activateAccordeon(3);
        }

    }

}
//create views - binding the data to the dom element
var projectsView = createView(projects);
//var calendarView = createView(desktopCalendar);
//get the form to be submitted
var addProjectForm = document.getElementById("addprojectform");
//assign the view to be updated after submitting to the formView variable
var formView = projectsView;
//add an event listener capaturing the submit event of the form
//add submitForm() as the handler for the event, and bind the form view to it
addProjectForm.addEventListener("submit", submitForm.bind(formView));

projectsView.afterLoad(initializeBlockaccordeon);
//initialize accordeons

var accord_projects;
projectsView.afterUpdate(function () {
    accord_projects = new accordion2('desktopprojects');
    createUsersTree(projectsView);

});





