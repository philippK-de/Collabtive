//endcolor for close element flashing
closeEndcolor = '#377814';
//endcolor for delete element flashing
deleteEndcolor = '#c62424';
//various ajax functions
/*
 * Object to send and receive/handle ajax requests
 * @param string url url the to be requested
 * @param string indicator DOM ID of the progress indicator element
 * @param function loadHandler Function to handle the onload() event of the xmlhttprequest
 */
function ajaxRequest(url, indicator, loadHandler) {
    //Default indicator to nothing
    if (indicator === undefined) {
        indicator = "";
    }


    this.request = new XMLHttpRequest();
    this.requestType = "GET";
    this.postBody = "";
    this.url = url;
    this.indicator = indicator;
    this.loadHandler = loadHandler;
}


//actually send the request
ajaxRequest.prototype.sendRequest = function () {
    //add the custom load handler function to the onload event
    this.request.onload = this.loadHandler;
    //if an indicator has been passed in, implement the onloadstart and onloadend callbacks to show/hide it
    if (this.indicator != "") {
        /*
         * Event Handlers
         * Onloadstart handler fires once before transfer starts
         */
        //dirty hack to make the indicator visible inside the closures
        const theIndicator = this.indicator;
        this.request.onloadstart = function (evt) {
            var progressIndicator = cssId("progress" + theIndicator);
            if (progressIndicator !== null && progressIndicator !== undefined) {
                progressIndicator.style.display = "block";
            }
        }
        //Onloadend handler fires once after onload has been dispatched
        this.request.onloadend = function (evt) {
            var progressIndicator = cssId("progress" + theIndicator);
            if (progressIndicator !== null && progressIndicator !== undefined) {
                progressIndicator.style.display = "none";
            }
        }
    }
    //open the request and send
    this.request.open(this.requestType, this.url);

    //if its a post request, send with special header and postbody
    if (this.requestType == "POST") {
        //Send the proper header information along with the request
        this.request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        this.request.send(this.postBody);
    }
    else {
        this.request.send();
    }
}

function css(selector) {
    return document.querySelector(selector);
}

function cssAll(selector) {
    return document.querySelectorAll(selector);
}

function cssId(id) {
    return document.getElementById(id);
}

/*
 * Function to update the HTML of an element, with the return value from a script called with XHR
 * @param string script The URL of the API endpoint
 * @param element the ID of the element to be updated
 * @return void
 */
function change(script, element) {
    var ajax = new ajaxRequest(script, "", function () {
        //element to be updated
        var targetElement = document.getElementById(element);
        //response data
        const responseData = ajax.request.responseText;

        //update the target element
        targetElement.innerHTML = responseData;

        //get scripts in the transmitted HTML, and eval them
        var javaScripts = targetElement.getElementsByTagName("script");
        for (var i = 0; i < javaScripts.length; i++) {
            //this is a hack but a needed one
            eval(javaScripts[i].innerHTML);
        }
    });
    ajax.sendRequest();
}

/*
 * Slide an element up
 * @param obj elm DOM element representing the element to be slided open
 */
function slideUp(elm) {
    var slideDuration = 600;
    Velocity(elm, "slideUp", {
        duration: slideDuration
    });
    elm.dataset.slidestate = "up";
}

/*
 * Slide an element up
 * @param obj elm DOM element representing the element to be slided closed
 */
function slideDown(elm) {
    var slideDuration = 600;
    Velocity(elm, "slideDown", {
        duration: slideDuration
    });
    elm.dataset.slidestate = "down";
}

/*
 * Toggle an element sliding up/down.
 * This slides an element up or down depending on its former slide state
 * @param str id ID of the element to be slided open/closed
 */
function blindtoggle(id) {
    var theElement = document.getElementById(id);

    if (theElement.dataset.slidestate == "down") {
        slideUp(theElement);
    }
    else {
        slideDown(theElement);
    }
}

/*
 * Open or close a DOM block
 * @param str id ID of the block to be opened or closed.
 */
function toggleBlock(id) {
    //get the block and block toggle
    var theBlock = document.getElementById(id);
    //the toggle is the arrow visually representing the blocks state
    var theBlockToggle = document.getElementById(id + '_toggle');
    //the current state of the block
    var blockState = theBlock.style.display;

    //closed
    if (blockState == "none") {
        setCookie(id, '1', '30', '/', '', '');
        theBlock.style.display = "block";
        theBlockToggle.className = 'win_block';
    }
    //open
    else if (blockState == "block" || blockState == "") {
        setCookie(id, '0', '30', '/', '', '');
        theBlock.style.display = "none";
        theBlockToggle.className = 'win_none';
    }
}

/*
 * Function to check if an element has a CSS class attached to it
 * @param obj elm DOM element to be checked
 * @param str className Name of the class to be checked for
 * @return bool
 */
function hasClass(elm, className) {
    if (elm.classList) {
        return elm.classList.contains(className);
    }
    else {
        return new RegExp('(^| )' + className + '( |$)', 'gi').test(elm.className);
    }
}

function getSelectedValue(selectElement) {
    var element = cssId(selectElement);
    return element.options[element.selectedIndex].value;
}
/*
 * Function to toggle an element between 2 CSS classes
 * @param obj elm can be an id string or DOM object
 * @return void
 */
function toggleClass(elm, class1, class2) {
    var theElement;
    //if the elm has no id property, an ID string has been passed in
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

function confirmit(text, url) {
    check = confirm(text);
    url = decodeURI(url);
    if (check == true) {
        window.location = url;
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

//add search provider
function addEngine(url) {
    window.external.AddSearchProvider(url);
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


