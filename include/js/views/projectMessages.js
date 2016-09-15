var projectMessages = {
    el: "projectMessages",
    itemType: "message",
    url: "managemessage.php?action=projectMessages",
    dependencies: []
};

var userMessages = {
    el: "userMessages",
    itemType: "message",
    url: "managemessage.php?action=userMessages",
    dependencies: []
};


function formSubmited()
{
    console.log("formsubmitted");
    blindtoggle('addmsg');
    toggleClass('add_butn','butn_link_active','butn_link');
    toggleClass('sm_msgs','smooth','nosmooth');
}