/*
 * Function to check if a modal has already been opened
 */
function checkModal() {
    var modalOpen = false;
    try {
        var modalCheck = cssId("modal_container");
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
        closeModal();
    }

    //create the modal container
    var modalContainer = document.createElement("div");
    modalContainer.id = "modal_container";
    modalContainer.zIndex = 99;
    modalContainer.style.position = "fixed";
    modalContainer.style.maxHeight = "500px";
    modalContainer.style.maxWidth = "600px";
    modalContainer.style.top = "50%";
    modalContainer.style.left = "50%";
    modalContainer.style.marginTop = "-250px";
    modalContainer.style.marginLeft = "-300px";
    modalContainer.style.opacity = 1.0;
    modalContainer.style.display = "none";

    modalContainer.className = modalElement.className;  //add the text node to the newly created div.
    modalContainer.innerHTML = modalElement.innerHTML;  //add the text node to the newly created div.

    // add the newly created element and its content into the DOM
    var parentElement = css("body");
    parentElement.appendChild(modalContainer);

    Velocity(modalContainer,"fadeIn",{duration: 600});
}

/*
 * Close a modal
 * @param str originalid a string representing the original element ID of the modal window.
 * This is needed to reset the ID value to its original state, since the open modal window always receives the "modal_container" id
 */
function closeModal() {
    //get the element to be closed
    var modalContainer = css("#modal_container");

    //reset the the to the original ID
    //modalElement.style.display = "none";
    Velocity(modalContainer,"fadeOut",{
        duration: 600,
        complete: function(){
            modalContainer.parentElement.removeChild(modalContainer);
        }
    });


}