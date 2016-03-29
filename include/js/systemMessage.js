var systemMessage = {
    notify: function(type){
        //get the container element
        var notificationContainer = document.querySelector("#systemMessage");
        var icon = notificationContainer.dataset.icon;
        var cssClass = "";
        var text = "";
        if(type == "added")
        {
            cssClass = "info_in_green";
            text = notificationContainer.dataset.textAdded;
        }
        else if(type == "closed")
        {
            cssClass = "info_in_green";
            text = notificationContainer.dataset.textClosed;
        }
        else if(type == "edited")
        {
            cssClass = "info_in_yellow";
            text = notificationContainer.dataset.textEdited;
        }
        else if(type == "deleted")
        {
            cssClass = "info_in_red";
            text = notificationContainer.dataset.textDeleted;
        }

        //construct HTML element
        var notificationHTML = "<span class = \"" + cssClass + "\">";
        notificationHTML += "<img src = \"" + icon + "\" />"  + text + "</span>";

        notificationContainer.innerHTML = notificationHTML;
        systemMsg(notificationContainer.id);
        console.log(notificationHTML);
    },
    added: function()
    {
         this.notify("added");
    },
    edited: function()
    {
        this.notify("edited");
    },
    deleted: function()
    {
        this.notify("deleted");
    }
};