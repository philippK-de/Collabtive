function openSidebar() {
    //animate right sidebar
    Velocity(cssId("content-right"), {
            width: 220
        },
        {
            complete: function () {
                cssId("sidebar-content").style.display = "block";
                cssId("sidebar-overlay").style.display = "none";
            }
        });
    //animate inner content too
    Velocity(css(".content-right-in"), {
        width: 220
    }, {queue: false});

    Velocity(cssId("content-left"), {
        width: 742
    }, {queue: false});
    //cssId("content-right").style.width = "220px";
    //cssId("sidebar-content").style.display = "block";
    //cssId("sidebar-overlay").style.display = "none";

    //cssId("content-left").style.width = "742px";
}

function closeSidebar() {
    Velocity(cssId("content-right"), {
            width: 100
        }
    );
    Velocity(css(".content-right-in"), {
        width: 80
    }, {
        queue: false,
        complete: function () {
            cssId("sidebar-content").style.display = "none";
            cssId("sidebar-overlay").style.display = "block";
        }
    });
    Velocity(cssId("content-left"), {
        width: 862
    }, {queue: false});


    //cssId("content-right").style.width = "100px";
    //cssId("sidebar-overlay").style.display = "block";

    //cssId("content-left").style.width = "862px";

}