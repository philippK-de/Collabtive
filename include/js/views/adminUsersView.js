/**
 * Created by eva on 28.04.2016.
 */
var adminUsers = {
    el: "adminUsers",
    itemType: "user",
    url: "admin.php?action=adminUsers",
    dependencies: []
};

pagination.itemsPerPage = 21;
var adminUsersView = createView(adminUsers);