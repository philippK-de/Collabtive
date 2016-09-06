/*
 * Function to slide the sidebar open or closed
 */
function toggleSidebar() {
    var contentRight = cssId("content-right");
    var contentLeft = cssId("content-left");
    var sideBarContent = cssId("sidebar-content");
    //read the state of the sidebar from data attribute data-opened
    var isOpen = contentRight.dataset.opened;

    var rightWidth;
    var rightSubWidth;
    var leftWidth;
    var togglesBgPosition;

    //if the sidebar is open, set dimensions for a closed sidebar
    if (isOpen == "true") {
        rightWidth = 80;
        rightSubWidth = 60;
        leftWidth = 882;

        //set the data attribute indicating the state of the sidebar
        contentRight.dataset.opened = false;
    }
    //if its not open, its closed - set dimensions for opened sidebar
    else {
        rightWidth = 220;
        rightSubWidth = 184;
        leftWidth = 742;

        //set the data attribute indicating the state of the sidebar
        contentRight.dataset.opened = true;
    }

    //animate right sidebar
    //animate inner content first
    var contentIns = cssAll(".content-right-in");
    for (var j = 0; j < contentIns.length; j++) {
        Velocity(contentIns[j], {
            width: rightSubWidth
        });
    }
    Velocity(contentRight, {
            width: rightWidth
        },
        {
            begin: function () {
                if (isOpen == "true") {
                    sideBarContent.style.display = "none";
                }
                else {
                    sideBarContent.style.display = "block";
                }
            }
        });

    //animate main content area
    Velocity(contentLeft, {
        width: leftWidth
    }, {queue: false});
}