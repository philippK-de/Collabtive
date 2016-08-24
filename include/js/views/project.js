//function to update the calendar
function updateCalendar(myCalendar, newMonth, newYear) {
    var currentUrl = myCalendar.$get("url");
    var calendarUrl = currentUrl + "&y=" + newYear + "&m=" + newMonth;
    Vue.set(myCalendar, "url", calendarUrl);
    updateView(myCalendar);

}
window.addEventListener("load", function () {
    /* new Effect.Morph('completed', {
     style: 'width:{/literal}{$done}{literal}%',
     duration: 4.0
     });*/
});

var projectCalendar = {
    el: "desktopCalendar",
    itemType: "calendar",
    url: "manageajax.php?action=projectCalendar",
    dependencies: []
}

var accord_dashboard = new accordion2('block_dashboard', {
    classNames: {
        toggle: 'win_none',
        toggleActive: 'win_block',
        content: 'accordion_content'
    }
});


function activateAccordeon(theAccord) {

    accord_dashboard.toggle(cssAll('#block_dashboard .accordion_content')[theAccord]);
    setCookie("activeSlideProject", theAccord);
}


