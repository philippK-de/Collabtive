/**
 * Created by philipp on 28.04.2016.
 */
var adminCustomers = {
  el: "adminCustomers",
  itemType: "customer",
  url: "admin.php?action=adminCustomers",
  dependencies: []
};

var accord_customers;
var adminCustomersView = createView(adminCustomers);

adminCustomersView.afterUpdate(function(){
    accord_customers = new accordion2('acc_customers');
});