<?php
require("./init.php");
// check if user is logged in
if (!isset($_SESSION["userid"]))
{
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
// check if user is admin
if (!$userpermissions["admin"]["add"])
{
    $errtxt = $langfile["nopermission"];
    $noperm = $langfile["accessdenied"];
    $template->assign("errortext", "$errtxt<br>$noperm");
    $template->display("error.tpl");
    die();
}

$action = getArrayVal($_GET, "action");
$id = getArrayVal($_GET, "id");
// get role details from form
$rolename = getArrayVal($_POST, "name");
$projectperms = getArrayVal($_POST, "permissions_projects");
$mileperms = getArrayVal($_POST, "permissions_milestones");
$taskperms = getArrayVal($_POST, "permissions_tasks");
$messageperms = getArrayVal($_POST, "permissions_messages");
$fileperms = getArrayVal($_POST, "permissions_files");
$trackerperms = getArrayVal($_POST, "permissions_timetracker");
$chatperms = getArrayVal($_POST, "permissions_chat");
$adminperms = getArrayVal($_POST, "permissions_admin");
// create new roles object
$roleobj = (object) new roles();
// add a role
if ($action == "addrole")
{
    $projectperms = $roleobj->sanitizeArray($projectperms);
    $mileperms = $roleobj->sanitizeArray($mileperms);
    $taskperms = $roleobj->sanitizeArray($taskperms);
    $messageperms = $roleobj->sanitizeArray($messageperms);
    $fileperms = $roleobj->sanitizeArray($fileperms);
    $trackerperms = $roleobj->sanitizeArray($trackerperms);
    $chatperms = $roleobj->sanitizeArray($chatperms);
    $adminperms = $roleobj->sanitizeArray($adminperms);

    if ($roleobj->add($rolename, $projectperms, $taskperms, $mileperms, $messageperms, $fileperms, $trackerperms, $chatperms, $adminperms))
    {
        $loc = $url . "admin.php?action=users&mode=roleadded";
        header("Location: $loc");
    }
}
// delete a role
elseif ($action == "delrole")
{
    if ($roleobj->del($id))
    {
        echo "ok";
    }
}
// edit a role
elseif ($action == "editrole")
{
    $rolename = getArrayVal($_POST, "rolename");
    $projectperms = $roleobj->sanitizeArray($projectperms);
    $mileperms = $roleobj->sanitizeArray($mileperms);
    $taskperms = $roleobj->sanitizeArray($taskperms);
    $messageperms = $roleobj->sanitizeArray($messageperms);
    $fileperms = $roleobj->sanitizeArray($fileperms);
    $trackerperms = $roleobj->sanitizeArray($trackerperms);
    $chatperms = $roleobj->sanitizeArray($chatperms);
    $adminperms = $roleobj->sanitizeArray($adminperms);

    if ($roleobj->edit($id, $rolename, $projectperms, $taskperms, $mileperms, $messageperms, $fileperms, $trackerperms, $chatperms, $adminperms))
    {
        $loc = $url . "admin.php?action=users&mode=roleedited";
        header("Location: $loc");
    }
}

?>