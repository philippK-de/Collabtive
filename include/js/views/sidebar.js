function showSidebar() {
    //animate right sidebar  - increase size
    Velocity(cssId("content-right"), {
            width: 220,
            height: 200
        },
        {
            complete: function () {
                cssId("sidebar-content").style.display = "block";
                cssId("sidebar-overlay").style.display = "none";
            }
        });

    var contentIns = cssAll(".content-right-in");

    for(var j=0;j<contentIns.length;j++)
    {
        Velocity(contentIns[j], {
            width: 184
        }, {
            queue: false
        });
    }
    //decrease size of main content area
    Velocity(cssId("content-left"), {
        width: 742
    }, {queue: false});

    //animate position of blocktoggles back to original state
    var blockToggles = cssAll(".win_block,.win_none");
    for (var i = 0; i < blockToggles.length; i++) {
        Velocity(blockToggles[i], {
            backgroundPositionX: 678
        }, {queue: false});
    }
    //cssId("content-right").style.width = "220px";
    //cssId("sidebar-content").style.display = "block";
    //cssId("sidebar-overlay").style.display = "none";

    //cssId("content-left").style.width = "742px";
}

function hideSidebar() {
    //animate content right - decrease size
    Velocity(cssId("content-right"), {
            width: 100,
            height: 35
        },
        {
            complete: function () {
                //hide the sidebar contents and show the overlay toggle
                cssId("sidebar-content").style.display = "none";
                cssId("sidebar-overlay").style.display = "block";
            }
        }
    );
    var contentIns = cssAll(".content-right-in");

    for(var j=0;j<contentIns.length;j++)
    {
        Velocity(contentIns[j], {
            width: 80
        }, {
            queue: false
        });
    }


    //increase size of main content
    Velocity(cssId("content-left"), {
        width: 862
    }, {queue: false});

    //animate position of blocktoggles too
    var blockToggles = cssAll(".win_block,.win_none");

    for (var i = 0; i < blockToggles.length; i++) {
        Velocity(blockToggles[i], {
            backgroundPositionX: 795
        }, {queue: false});
    }


    //cssId("content-right").style.width = "100px";
    //cssId("sidebar-overlay").style.display = "block";

    //cssId("content-left").style.width = "862px";

}