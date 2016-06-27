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


/*
* Function to toggle a checkbox between 1 / 0 values
 */
function toggleCheckbox()
{
    if(this.checked)
    {
        this.value = 1;
    }
    else
    {
        this.value = 0;
    }
}

/*
* Bind the toggleCheckbox function to all the inputs in the document
 */
window.addEventListener("load",function()
{
    var allCheckboxes = document.getElementsByTagName("input");
    for(var i = 0; i < allCheckboxes.length; i++) {
        if(allCheckboxes[i].type == "checkbox") {
            allCheckboxes[i].addEventListener("click",toggleCheckbox);
        }
    }
});

/*
* Handler function to be called when addRoles form was submitted
 */
function formSubmited()
{
    console.log("formsubmitted");
    blindtoggle('form_addmyroles');
    toggleClass('add_myprojects','add-active','add');
    toggleClass('add_butn_myprojects','butn_link_active','butn_link');
    toggleClass('sm_myprojects','smooth','nosmooth');
}

var accord_roles;
var addRoleForm;
var formView;

adminRolesView.afterUpdate(function(){
    accord_roles = new accordion2('acc_roles');
    addRoleForm = document.getElementById("addRoleForm");
    formView = adminRolesView;
    addRoleForm.addEventListener("submit", submitForm.bind(formView));
});