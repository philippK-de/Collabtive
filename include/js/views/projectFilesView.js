var projectFiles = {
  el: "projectFiles",
  itemType: "file",
  url: "managefile.php?action=projectFiles",
  dependencies: []
};


function handleDrop(evt){
    evt.stopPropagation();
    evt.preventDefault();
    var elm = evt.target;
    var folderId = elm.getAttribute("data-folderid");

    console.log("droped");
    var fileId = evt.dataTransfer.getData("text/plain");

    var dropedElm = document.getElementById("fli_"+fileId);
    dropedElm.style.display = "none";


    change('managefile.php?action=movefile&id=100&file='+fileId+'&target='+folderId,'jslog');
    console.log("fileid " + fileId + "folder " + folderId);
}

function handleDragStart(evt) {
    console.log("drag start");
    evt.dataTransfer.effectAllowed = 'move';

    var elm = evt.target;
    var elmData = elm.getAttribute("data-fileid");

    evt.dataTransfer.setData("text/plain",elmData);

    elm.style.opacity = '0.4';  // this / e.target is the source node.

}

function handleDragOver(evt) {
    if (evt.preventDefault) {
        evt.preventDefault(); // Necessary. Allows us to drop.
    }
     evt.dataTransfer.dropEffect   = 'move';  // See the section on the DataTransfer object.

    return false;
}

function handleDragEnter(evt) {
    // this / e.target is the current hover target.
    var elm = evt.target;
    var data = evt.dataTransfer.getData("text/plain");

    var wrapperElm = document.getElementById("iw_"+data);
    elm.classList.add('dragover');
}

function handleDragLeave(evt) {
    var elm = evt.target;

    elm.classList.remove('dragover');  // this / e.target is previous target element.
}