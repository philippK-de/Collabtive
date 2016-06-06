//function to update the calendar
function updateCalendar(myCalendar, newMonth, newYear)
{
    var currentUrl = myCalendar.$get("url");
    var calendarUrl = currentUrl + "&y=" + newYear + "&m=" + newMonth;
    Vue.set(myCalendar,"url",calendarUrl);
    updateView(myCalendar);

}
window.addEventListener("load", function () {
    new Effect.Morph('completed', {
        style: 'width:{/literal}{$done}{literal}%',
        duration: 4.0
    });
});

var projectCalendar = {
    el: "desktopCalendar",
    itemType: "calendar",
    url: "manageajax.php?action=projectCalendar",
    dependencies: []
}

var accord_dashboard    = new accordion2('block_dashboard', {
    classNames: {
        toggle: 'win_none',
        toggleActive: 'win_block',
        content: 'accordion_content'
    }
});


function activateAccordeon(theAccord) {

    accord_dashboard.toggle(document.querySelectorAll('#block_dashboard .accordion_content')[theAccord]);
    setCookie("activeSlideProject", theAccord);
}

//var theBlocks = $$("#block_dashboard > div .headline > a");
var theBlocks = document.querySelectorAll("#block_dashboard > div .headline > a");

//loop through the blocks and add the accordion toggle link
var openSlide = 0;
for (i = 0; i < theBlocks.length; i++) {
    var theCook = readCookie("activeSlideProject");
    if (theCook > 0) {
        openSlide = theCook;
    }

    var theAction = theBlocks[i].getAttribute("onclick");
    theAction += "activateAccordeon(" + i + ");";
    theBlocks[i].setAttribute("onclick", theAction);
    //console.log(theBlocks[i].getAttribute("onclick"));
}


//accordIndex.activate($$('#block_index .acc_toggle')[0]);
activateAccordeon(0);