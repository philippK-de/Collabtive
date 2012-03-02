<?php
include("init.php");
if (!isset($_SESSION["userid"]))
{
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
$project = new project();
$user = new user();
$myprojects = $project->getMyProjects($userid);
$oldprojects = $project->getMyProjects($userid, 0);
$pnum = count($myprojects) + count($oldprojects);

$users = $user->getAllUsers(1000000);
$template->assign("users", $users);

$title = $langfile["myprojects"];

$template->assign("title", $title);
$template->assign("projectnum",$pnum);
$template->assign("myprojects", $myprojects);
$template->assign("oldprojects", $oldprojects);

$template->display("myprojects.tpl");

?>