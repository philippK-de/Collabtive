function filterTimetrackerView(event) {
    //stop the normal form submission
    event.stopPropagation();
    event.preventDefault();

    //get the form object and the baseUrl
    var filterForm = event.currentTarget;
    var baseUrl = filterForm.action;

    //get the form fields for filtering
    var startDate = cssId("start").value;
    var endDate = cssId("end").value;
    var user = cssId("usr").value;
    var task = cssId("ttask").value;

    //construct the GET parameters for the url
    var queryUrl = "";
    if (startDate != "") {
        queryUrl += "&start=" + startDate;
    }
    if (endDate != "") {
        queryUrl += "&end=" + endDate;
    }
    if (user > 0) {
        queryUrl += "&usr=" + user;
    }
    if (task > 0) {
        queryUrl += "&task=" + task;
    }

    if (queryUrl != "") {
        //if a query was created, append it to the base url
        baseUrl += queryUrl;

        //update the view
        Vue.set(projectTimetrackerView, "url", baseUrl);
        updateView(projectTimetrackerView);
    }
}

function getTimetrackerReport(project, format) {
    var baseUrl;
    if(format == "pdf") {
        baseUrl = "managetimetracker.php?action=projectpdf&id=" + project;
    }
    else if(format == "xls")
    {
        baseUrl = "managetimetracker.php?action=projectxls&id=" + project;
    }
    //get the form fields for filtering
    var startDate = cssId("start").value;
    var endDate = cssId("end").value;
    var user = cssId("usr").value;
    var task = cssId("ttask").value;

    //construct the GET parameters for the url
    var queryUrl = "";
    if (startDate != "") {
        queryUrl += "&start=" + startDate;
    }
    if (endDate != "") {
        queryUrl += "&end=" + endDate;
    }
    if (user > 0) {
        queryUrl += "&usr=" + user;
    }
    if (task > 0) {
        queryUrl += "&task=" + task;
    }

    //if a query was created, append it to the base url
    baseUrl += queryUrl;
    console.log(baseUrl);
    window.location.replace(baseUrl);
}
var projectTimetracker = {
    el: "projectTimetracker",
    itemType: "timetracker",
    url: "managetimetracker.php?action=projectTimetracker",
    dependencies: []
};

