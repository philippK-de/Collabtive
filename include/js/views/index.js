//Vue.config.silent = true;
//create the vue views, binding data to DOM elements
function createView(myEl) {
    var myModel = {
        items: [],
        dependencies : myEl.dependencies,
        url: myEl.url
    };

    var vueview = new Vue({
        el: "#" + myEl.el,
        data: myModel
    });

    new Ajax.Request(myEl.url, {
            method: 'get',
            onSuccess: function (myData) {
                //update the model with the retrieved data
                myModel.items = JSON.parse(myData.responseText);
            },
            onLoading: function () {
                //show loading indicator
                startWait("progress" + myEl.el);
            },
            onComplete: function () {
                //hide loading indicator
                stopWait("progress" + myEl.el);
            },
            onFailure: function () {

            }
        }
    );

    return vueview;
}
function updateView(view) {
    new Ajax.Request(view.url, {
            method: 'get',
            onSuccess: function (myData) {
                //console.log(myData.responseText);
                console.log("loaded "+ view.url);
                view.$set("items", JSON.parse(myData.responseText));
                console.log(view.$el.id);

                var viewsToUpdate = view.$get("dependencies");

                if (viewsToUpdate.length > 0) {
                    for(i=0;i<viewsToUpdate.length;i++)
                    {
                        console.log("updated: "+viewsToUpdate[i][0]);
                        updateView(viewsToUpdate[i],viewsToUpdate[i].url);
                    }
                }
            },
            onLoading: function () {
                console.log(view.$el.id);
                startWait("progress" + view.$el.id);
            },
            onComplete: function () {
                stopWait("progress" + view.$el.id);
            },
            onFailure: function () {

            }
        }
    );
}


//create the objects representing the Widgets with their DOM element, DataURL, Dependencies and view managing them
var projects = {
    el: "desktopprojects",
    url: "index.php?action=myprojects",
    dependencies: []
};

var tasks = {
    el: "desktoptasks",
    url: "index.php?action=mytasks",
    dependencies: []
};

var messages = {
    el: "desktopmessages",
    url: "index.php?action=mymessages",
    dependencies: []
};

//create views - binding the data to the dom element
var projectsView = createView(projects);
var tasksView = createView(tasks);
var msgsView = createView(messages);

//setup dependenciens
projectsView.$set("dependencies",[tasksView, msgsView]);



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

