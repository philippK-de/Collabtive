
function toggleSidebar() {
    var contentRight = cssId("content-right");
    var contentLeft = cssId("content-left");
    //read the state of the sidebar from data attribute data-opened
    var isOpen = contentRight.dataset.opened;

    var rightWidth;
    var rightHeight;
    var rightSubWidth;
    var leftWidth;
    var togglesBgPosition;

    //if the sidebar is open, set dimensions for a closed sidebar
    if (isOpen == "true") {
        rightWidth = 100;
        rightHeight = 30;
        rightSubWidth = 80;
        leftWidth = 862;
        //this is for the win_none/block toggles
        togglesBgPosition = 795;

        //set the data attribute indicating the state of the sidebar
        contentRight.dataset.opened = false;
    }
    //if its not open, its closed - set dimensions for opened sidebar
    else {
        rightWidth = 220;
        rightHeight = 200;
        rightSubWidth = 184;
        leftWidth = 742;
        togglesBgPosition = 678;

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
            width: rightWidth,
            height: rightHeight
        },
        {
            complete: function () {
                cssId("sidebar-content").style.display = "block";
            }
        });

   //animate main content area
    Velocity(contentLeft, {
        width: leftWidth
    }, {queue: false});

    //animate position of blocktoggles
    var blockToggles = cssAll(".win_block,.win_none");
    for (var i = 0; i < blockToggles.length; i++) {
        Velocity(blockToggles[i], {
            backgroundPositionX: togglesBgPosition
        }, {queue: false});
    }
}