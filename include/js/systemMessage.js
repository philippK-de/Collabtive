var systemMessage = {
    showFor: 3500,
    fadeDuration: 2.0,
    createMessage: function(elementId)
    {
        new Effect.Appear(elementId, {duration: this.fadeDuration})
        window.setTimeout("new Effect.Fade('" + elementId + "', { duration: "+ this.fadeDuration + " })",this.showFor);
    },
    notify: function(messageType, itemType){
        //get the container element
        var notificationContainer = document.querySelector("#" + itemType + "SystemMessage");
        //make sure it is empty
        notificationContainer.innerHTML = "";
        var icon = notificationContainer.dataset.icon;
        var cssClass = "";
        var text = "";
        if(messageType == "added")
        {
            cssClass = "info_in_green";
            text = notificationContainer.dataset.textAdded;
        }
        else if(messageType == "assigned")
        {
            cssClass = "info_in_green";
            text = notificationContainer.dataset.textAssigned;
        }
        else if(messageType == "closed")
        {
            cssClass = "info_in_green";
            text = notificationContainer.dataset.textClosed;
        }
        else if(messageType == "edited")
        {
            cssClass = "info_in_yellow";
            text = notificationContainer.dataset.textEdited;
        }
        else if(messageType == "deleted")
        {
            cssClass = "info_in_red";
            text = notificationContainer.dataset.textDeleted;
        }
        else if(messageType == "deassigned")
        {
            cssClass = "info_in_red";
            text = notificationContainer.dataset.textDeassigned;
        }

        //construct HTML element
        var notificationHTML = "<span class = \"" + cssClass + "\">";
        notificationHTML += "<img src = \"" + icon + "\" />"  + text + "</span>";

        //write the notification element to the notification container
        notificationContainer.innerHTML = notificationHTML;
        //create the system message
        this.createMessage(notificationContainer.id);
        //console.log(notificationHTML);
    },
    added: function(itemType)
    {
         this.notify("added", itemType);
    },
    closed: function(itemType)
    {
        this.notify("closed", itemType);
    },
    edited: function(itemType)
    {
        this.notify("edited", itemType);
    },
    deleted: function(itemType)
    {
        this.notify("deleted", itemType);
    },
    assigned: function(itemType)
    {
        this.notify("assigned", itemType);
    }

};