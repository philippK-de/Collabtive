//endcolor for close element flashing
closeEndcolor = '#377814';
//endcolor for delete element flashing
deleteEndcolor = '#c62424';
//various ajax functions
function change(script, element) {
    var ajax = new Ajax.Updater({success: element}, script, {method: 'get', evalScripts: true});
}
function changeshow(script, element, theindicator) {

    var ajax = new Ajax.Updater({success: element}, script, {method: 'get', evalScripts: true, onCreate: startWait(theindicator)});

}
function changePost(script, element, pbody) {
    var ajax = new Ajax.Updater({success: element}, script, {method: 'post', postBody: pbody, evalScripts: true});
}


function make_inputs(num) {
    var url = 'manageajax.php?action=makeinputs&num=' + num;
    change(url, 'inputs');
}

function show_addtask(id) {
    var theElement = document.getElementById(id);
    var slideDuration = 600;

    Velocity(theElement, "slideDown", {
        duration: slideDuration
    });
    theElement.dataset.slidestate = "down";
}

function slideUp(elm) {
    var slideDuration = 600;
    Velocity(elm, "slideUp", {
        duration: slideDuration
    });
    elm.dataset.slidestate = "up";
}

function slideDown(elm) {
    var slideDuration = 600;
    Velocity(elm, "slideDown", {
        duration: slideDuration
    });
    elm.dataset.slidestate = "down";
}

function blindtoggle(id) {
    var theElement = document.getElementById(id);

    if (theElement.dataset.slidestate == "down") {
        slideUp(theElement);
    }
    else {
        slideDown(theElement);
    }
}

function fadeToggle(id) {
    new Effect.toggle(id, 'appear');
}


function toggleBlock(id) {
    var theBlock = document.getElementById(id);
    var theBlockToggle = document.getElementById(id + '_toggle');
    var state = theBlock.style.display;

    if (state == "none") {
        setCookie(id, '1', '30', '/', '', '');
        theBlock.style.display = "block";
        theBlockToggle.className = 'win_block';
    }
    else if (state == "block" || state == "") {
        setCookie(id, '0', '30', '/', '', '');
        theBlock.style.display = "none";
        theBlockToggle.className = 'win_none';
    }
}

function hasClass(elm, className) {
    if (elm.classList) {
        return elm.classList.contains(className);
    }
    else {
        return new RegExp('(^| )' + className + '( |$)', 'gi').test(elm.className);
    }
}
function switchClass(id, class1, class2) {
    try {
        $($('selectedid').value).className = class2;
    } catch (e) {
    }
    try {
        $('selectedid').value = id;
        $($('selectedid').value).className = class1;
    }
    catch (e) {
    }

}
function toggleClass(elm, class1, class2) {
    var theElement;
    if (elm.id === undefined) {
        theElement = document.getElementById(elm);
    }
    else {
        theElement = elm;
    }

    if (hasClass(theElement, class1)) {
        theElement.className = class2;
    }
    else {
        theElement.className = class1;
    }

}


function changeElements(element, classname) {
    var loop = $$(element);
    for (var i = 0; i < loop.length; i++) {
        loop[i].className = classname;
    }
}

function makeTimer(funct, duration) {
    window.setTimeout(funct, duration);
}

function confirmit(text, url) {
    check = confirm(text);
    url = decodeURI(url);
    if (check == true) {
        window.location = url;
    }

}

function confirmfunction(text, toCall) {
    var check = confirm(text);
    if (check == true) {
        eval(toCall);
    }
}

function setCookie(name, value, expires, path, domain, secure) {
    var today = new Date();
    today.setTime(today.getTime());
    if (expires) {
        expires = expires * 1000 * 60 * 60 * 24;
    }
    var expires_date = new Date(today.getTime() + (expires));

    document.cookie = name + "=" + escape(value) +
    ( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
    ( ( path ) ? ";path=" + path : "" ) +
    ( ( domain ) ? ";domain=" + domain : "" ) +
    ( ( secure ) ? ";secure" : "" );
}
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function getnow(field) {
    var currenttime = new Date();
    var hours = currenttime.getHours();
    var minutes = currenttime.getMinutes();
    if (hours < 10) {
        hours = "0" + hours;
    }
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    var clocklocation = $(field);
    clocklocation.value = hours + ":" + minutes;
}

/* Clock */
function calctime() {
    var currenttime = new Date();
    var hours = currenttime.getHours();
    var minutes = currenttime.getMinutes();
    var seconds = currenttime.getSeconds();
    if (hours < 10) {
        hours = "0" + hours;
    }
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    if (seconds < 10) {
        seconds = "0" + seconds;
    }
    var clocklocation = $("digitalclock");
    clocklocation.innerHTML = hours + ":" + minutes + ":" + seconds;
    setTimeout("calctime()", 1000);
}

//add search provider
function addEngine(url) {
    window.external.AddSearchProvider(url);
}


function sortit() {

}
function sortBlock(blockId, sortmode) {
    var theBlock = document.getElementById(blockId);
    var tbodyCollection = theBlock.getElementsByTagName("tbody");
    var tbodies = [];

    for (var j = 0; j < tbodyCollection.length; j++) {
        tbodies.push(tbodyCollection[j]);
    }
    var bodyIds = new Array();
    for (var i = 0; i < tbodies.length; i++) {
        var tdtitle = tbodies[i].getAttribute("rel");
        var titleArr = tdtitle.split(",");

        tbodies[i].setAttribute("theid", Number(titleArr[0]));
        tbodies[i].setAttribute("title", titleArr[1]);
        tbodies[i].setAttribute("daysleft", titleArr[2]);
        tbodies[i].setAttribute("project", titleArr[3]);
        tbodies[i].setAttribute("theuser", titleArr[4]);
        tbodies[i].setAttribute("done", titleArr[5]);
        if (tbodies[i].getAttribute("sortorder") == "asc") {
            tbodies[i].setAttribute("sortorder", "desc");
        }
        else {
            tbodies[i].setAttribute("sortorder", "asc");
        }

        theBlock.removeChild(tbodies[i]);
    }

    if (sortmode == "daysleft") {
        tbodies.sort(daysort);
    }
    else if (sortmode == "done") {
        tbodies.sort(done);
    }
    else if (sortmode == "project") {
        tbodies.sort(sortByProject);
    }
    else if (sortmode == "byuser") {
        tbodies.sort(sortByUser);
    }
    else {
        tbodies.sort(sortByTitle);
    }

    for (var a = 0; a < tbodies.length; a++) {
        theBlock.appendChild(tbodies[a]);
        var tbodyId = tbodies[a].getAttribute("theid");
        var toggle = document.getElementById(blockId + "toggle" + tbodyId);
        toggle.setAttribute("onclick", "javascript:accord_tasks.activate(document.querySelector('#taskhead_content" + a + "'));");
    }
}

function done(a, b) {
    var x = a.getAttribute("done");
    var y = b.getAttribute("done");
    var sortorder = b.getAttribute("sortorder");

    //desc
    if (sortorder == "asc") {
        return y - x
    }
    else {
        //asc
        return x - y
    }
}

function daysort(a, b) {
    var x = a.getAttribute("daysleft");
    var y = b.getAttribute("daysleft");
    var sortorder = b.getAttribute("sortorder");

    //desc
    if (sortorder == "asc") {
        return y - x
    }
    else {
        //asc
        return x - y
    }
}

function sortByTitle(a, b) {
    var x = a.title.toLowerCase();
    var y = b.title.toLowerCase();
    var sortorder = b.getAttribute("sortorder");

    if (sortorder == "asc") {
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    }
    else {
        return ((x > y) ? -1 : ((x < y) ? 1 : 0));
    }
}

function sortByProject(a, b) {
    var x = a.getAttribute("project");
    var y = b.getAttribute("project");
    var sortorder = b.getAttribute("sortorder");

    if (sortorder == "asc") {
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    }
    else {
        return ((x > y) ? -1 : ((x < y) ? 1 : 0));
    }
}

function sortByUser(a, b) {
    var x = a.getAttribute("theuser");
    var y = b.getAttribute("theuser");
    var sortorder = b.getAttribute("sortorder");

    if (sortorder == "asc") {
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    }
    else {
        return ((x > y) ? -1 : ((x < y) ? 1 : 0));
    }
}

function sortByDays(a, b) {
    var x = a.getAttribute("daysleft");
    var y = b.getAttribute("daysleft");

    var sortorder = b.getAttribute("sortorder");

    if (sortorder == "asc") {
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    }
    else {
        return ((x > y) ? -1 : ((x < y) ? 1 : 0));
    }
}


