adminProjects = {
    el: "adminProjects",
    itemType: "project",
    url: "admin.php?action=adminProjects",
    dependencies: []
};
pagination.itemsPerPage = 20;
var adminProjectsView = createView(adminProjects);

/*
 * Handler function to be called when form was successfully submited
 */
function formSubmited() {
    blindtoggle('form_addmyproject');
    toggleClass('add_myprojects', 'add-active', 'add');
    toggleClass('sm_myprojects', 'smooth', 'nosmooth');
    toggleClass("add_butn_myprojects", 'butn_link_active', 'butn_link');
}


//get the form to be submitted
var addProjectForm = document.getElementById("addprojectform");
//assign the view to be updated after submitting to the formView variable
var formView = adminProjectsView;
addProjectForm.addEventListener("submit", submitForm.bind(formView));


adminProjectsView.limit = 20;
adminProjectsView.afterUpdate(function(){
    accord_projects = new accordion2('acc_projects');
});
