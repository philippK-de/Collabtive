<?php
require("init.php");
if (!isset($_SESSION['userid'])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}

$project = new project();
$myoldprojects = $project->getMyProjects($userid, 0);

$template->assign("myoldprojects", $myoldprojects);
$template->display("manageclosedprojects.tpl");
?>