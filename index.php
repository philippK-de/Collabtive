<?php
require("./init.php");
// check if the user is loged in
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $mode = getArrayVal($_GET, "mode");
    $template->assign("mode", $mode);
    $template->display("login.tpl");
    die();
}
// collabtive doesn't seem to be installed properly , redirect to installer
if (empty($db_name) or empty($db_user)) {
    if ($db_driver == "mysql") {
        $loc = $url . "install.php";
        header("Location: " . $loc);
    }
}
// Set the desktop icon in the top icon menue
$mainclasses = array("desktop" => "active",
    "profil" => "",
    "admin" => ""
    );
$template->assign("mainclasses", $mainclasses);
// create objects
$project = new project();
$customer = new company();
$milestone = new milestone();
$mtask = new task();
$msg = new message();
// create arrays to hold data
$messages = array();
$milestones = array();
$tasks = array();
// create a counter for the foreach loop
$cou = 0;
// If user has projects, loop through them and get the messages and tasks belonging to those projects
if (!empty($myOpenProjects)) {
    foreach($myOpenProjects as $proj) {
        // get all the tasks in this project that are assigned to the current user
        $task = $mtask->getAllMyProjectTasks($proj["ID"], 100);
        // get all messages in the project
        $msgs = $msg->getProjectMessages($proj["ID"]);
        // write those to arrays
        if (!empty($msgs)) {
            array_push($messages, $msgs);
        }

        if (!empty($task)) {
            array_push($tasks, $task);
        }

        $cou = $cou + 1;
    }
}
$myClosedProjects = $project->getMyProjects($userid, 0);
// If the user is allowed to add projects, also get all users to assign to those projects
if ($userpermissions["projects"]["add"]) {
    $user = new user();
    $users = $user->getAllUsers(1000000);
    $template->assign("users", $users);

    $company = new company();
    $companies = $company->getAllCompanies();
    $template->assign("customers", $companies);
}
// by default the arrays have a level for each project, whcih contains arrays for each message/task . reduce array flattens this to have all messages/tasks of all projects in one structure
if (!empty($messages)) {
    $messages = reduceArray($messages);
}
$etasks = reduceArray($tasks);
// Create sort array for multisort by adding the daysleft value to a sort array
$sort = array();
foreach($etasks as $etask) {
    array_push($sort, $etask["daysleft"]);
}
// Sort using array_multisort
array_multisort($sort, SORT_NUMERIC, SORT_ASC, $etasks);
// On Admin Login check for updates
$mode = getArrayVal($_GET, "mode");
if ($mode == "login") {
    $chkLim = 0;
    // only check if an admin logs in
    if ($userpermissions["admin"]["add"]) {
        // only check 1/2 of the times an admin logs in, to reduce server load
        $chkLim = mt_rand(1, 2);
        if ($chkLim == 1) {
            $updateChk = getUpdateNotify();
            if (!empty($updateChk)) {
                if ($updateChk->pubDate > CL_PUBDATE) {
                    $template->assign("isUpdated", true);
                    $template->assign("updateNotify", $updateChk);
                }
            }
        }
    }
}
// Get todays date and count tasks, projects and messages for display
$today = date("d");
$tasknum = count($etasks);
$projectnum = count($myOpenProjects);
$oldProjectnum = count($myClosedProjects[0]);
$msgnum = count($messages);
$title = $langfile["desktop"];
// Assign everything to the template engine
$template->assign("title", $title);
$template->assign("today", $today);

$template->assign("myprojects", $myOpenProjects);
$template->assign("oldprojects", $myClosedProjects);
$template->assign("projectnum", $projectnum);
$template->assign("closedProjectnum", $oldProjectnum);
$template->assign("projectov", "yes");

$template->assign("mode", $mode);

$template->assign("tasks", $etasks);
$template->assign("tasknum", $tasknum);

$template->assign("messages", $messages);
$template->assign("msgnum", $msgnum);
$template->display("index.tpl");

?>
