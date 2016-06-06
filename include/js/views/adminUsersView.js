/**
 * Created by eva on 28.04.2016.
 */
var adminUsers = {
    el: "adminUsers",
    itemType: "user",
    url: "admin.php?action=adminUsers",
    dependencies: []
};

var adminRoles = {
  el: "adminRoles",
  itemType: "role",
  url: "manageroles.php?action=adminRoles",
  dependencies: []
};

pagination.itemsPerPage = 21;
var adminUsersView = createView(adminUsers);

pagination.itemsPerPage = 10;
var adminRolesView = createView(adminRoles);

var accord_roles;
adminRolesView.$on("iloaded",function(){
        console.log("roles loaded");
    accord_roles = new accordion2('acc_roles');
});