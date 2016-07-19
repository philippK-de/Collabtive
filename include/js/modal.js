/*
 * Function to check if a modal has already been opened
 */
function checkModal() {
    var modalOpen = false;
    try {
        var modalCheck = document.getElementById("modal_container");
        if (modalCheck != null) {
            modalOpen = modalCheck;
        }
    }
    catch (e) {
    }

    return modalOpen;
}

/*
 * Function to open modal window
 */
function openModal(elementId) {
    //get the modal to be opened
    var modalElement = document.querySelector("#" + elementId);

    //check if there is already a modal open, if yes close it
    var checkModalOpen = checkModal();
    if (checkModalOpen !== false) {
        //if a modal is open, close the opened modal
        closeModal(checkModalOpen.dataset.originalid);
    }

    //set the orginalid to the current element id
    //this is needed cause the active modal always has the id modal_container
    modalElement.dataset.originalid = modalElement.id;
    modalElement.id = "modal_container";
    modalElement.style.zIndex = 99;
    modalElement.style.position = "fixed";
    modalElement.style.maxHeight = "500px";
    modalElement.style.maxWidth = "600px";
    modalElement.style.top = "50%";
    modalElement.style.left = "50%";
    modalElement.style.marginTop = "-250px"; //negative half of height
    modalElement.style.marginLeft = "-300px"; //negative half of width

    modalElement.style.display = "block";
}

/*
 * Close a modal
 * @param str originalid a string representing the original element ID of the modal window.
 * This is needed to reset the ID value to its original state, since the open modal window always receives the "modal_container" id
 */
function closeModal(originalid) {
    //get the element to be closed
    var modalElement = document.querySelector("#modal_container");

    //reset the the to the original ID
    modalElement.id = originalid;
    modalElement.style.display = "none";
}