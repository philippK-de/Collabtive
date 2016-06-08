function filterTimetrackerView(event)
{
    event.stopPropagation();
    event.preventDefault();

    var filterForm = event.currentTarget;
    var baseUrl = projectTimetrackerView.$get("url");
    console.log(baseUrl);

    var startDate = document.getElementById("start").value;
    var endDate = document.getElementById("end").value;
    var user = document.getElementById("usr").value;
    var task = document.getElementById("ttask").value;

    var queryUrl = "";
    if(startDate != "")
    {
        queryUrl += "&start=" + startDate;
    }
    if(endDate != "")
    {
        queryUrl += "&end=" + endDate;
    }
    if(user > 0)
    {
        queryUrl += "&usr=" + user;
    }
    if(task > 0)
    {
        queryUrl += "&task=" + task;
    }

    if(queryUrl != "")
    {
        baseUrl += queryUrl;
        Vue.set(projectTimetrackerView,"url",baseUrl);
        updateView(projectTimetrackerView);
    }

    console.log("query: " + queryUrl);
    console.log("final: " + baseUrl);
}

var projectTimetracker = {
    el: "projectTimetracker",
    itemType: "timetracker",
    url: "managetimetracker.php?action=projectTimetracker",
    dependencies: []
};

