var systemMessage = {
    showFor: 3000,
    fadeDuration: 1500,
    createMessage: function (elementId) {
        var messageElement = document.getElementById(elementId);
        Velocity(messageElement, "fadeIn", {
            duration: this.fadeDuration,
            complete: function (elm) {
                Velocity(messageElement, "fadeOut", {
                    duration: systemMessage.fadeDuration,
                    delay: systemMessage.showFor
                });
            }
        });
    },
    notify: function (messageType, itemType) {
        //get the container element
        var notificationContainer = document.querySelector("#" + itemType + "SystemMessage");
        var icon = notificationContainer.dataset.icon;
        var cssClass = "";
        var text = "";
        if (messageType == "added") {
            cssClass = "info_in_green";
            text = notificationContainer.dataset.textAdded;
        }
        else if (messageType == "assigned") {
            cssClass = "info_in_green";
            text = notificationContainer.dataset.textAssigned;
        }
        else if (messageType == "closed") {
            cssClass = "info_in_green";
            text = notificationContainer.dataset.textClosed;
        }
        else if (messageType == "edited") {
            cssClass = "info_in_yellow";
            text = notificationContainer.dataset.textEdited;
        }
        else if (messageType == "deleted") {
            cssClass = "info_in_red";
            text = notificationContainer.dataset.textDeleted;
        }
        else if (messageType == "deassigned") {
            cssClass = "info_in_red";
            text = notificationContainer.dataset.textDeassigned;
        }

        //construct HTML text
        var notificationHTML = "<span class = \"" + cssClass + "\">";
        notificationHTML += "<img src = \"" + icon + "\" />" + text + "</span>" +
        "<div class=\"padding-bottom-two-px\"></div>";


        //create notification element
        var notificationElement = document.createElement("div");
        notificationElement.innerHTML = notificationHTML;
        notificationElement.id = "message_" + notificationContainer.childNodes.length + "_" + notificationContainer.id;

        //append the notification element to the notification container
        notificationContainer.appendChild(notificationElement);
        notificationContainer.style.display = "block";

        //create the system message  animation
        this.createMessage(notificationElement.id);
        //console.log(notificationHTML);
    },
    added: function (itemType) {
        this.notify("added", itemType);
    },
    closed: function (itemType) {
        this.notify("closed", itemType);
    },
    edited: function (itemType) {
        this.notify("edited", itemType);
    },
    deleted: function (itemType) {
        this.notify("deleted", itemType);
    },
    assigned: function (itemType) {
        this.notify("assigned", itemType);
    }

};