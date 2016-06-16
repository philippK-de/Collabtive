adminProjects = {
    el: "adminProjects",
    itemType: "project",
    url: "admin.php?action=adminProjects",
    dependencies: []
};
pagination.itemsPerPage = 20;
var adminProjectsView = createView(adminProjects);


var accord_projects;
adminProjectsView.limit = 20;
adminProjectsView.afterUpdate(function(){
    accord_projects = new accordion2('acc_projects');
});
