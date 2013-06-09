<?php
require("./init.php");

if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $mode = getArrayVal($_GET, "mode");
    $template->assign("mode", $mode);
    $template->display("login.tpl");
    die();
}
// collabtive doesn't seem to be installed properly , redirect to installer
if (empty($db_name) or empty($db_user)) {
    $loc = $url . "install.php";
    header("Location: " . $loc);
}

//Set the desktop icon in the top icon menue
$mainclasses = array("desktop" => "active",
    "profil" => "",
    "admin" => ""
    );
$template->assign("mainclasses", $mainclasses);

//create objects
$project = new project();
$customer = new company();
$milestone = new milestone();
$mtask = new task();
$msg = new message();

$myprojects = $project->getMyProjects($userid);
$messages = array();
$milestones = array();
$tasks = array();
$cou = 0;

//If users has projects, loop through them and get the messages and tasks belonging to those projects
if (!empty($myprojects)) {
    foreach($myprojects as $proj) {
        $task = $mtask->getAllMyProjectTasks($proj["ID"], 100);
        $msgs = $msg->getProjectMessages($proj["ID"]);

        if (!empty($msgs)) {
            array_push($messages, $msgs);
        }

        if (!empty($task)) {
            array_push($tasks, $task);
        }

        $cou = $cou + 1;
    }
}

//If the user is allowed to add projects, also get users to assign to those projects
if ($userpermissions["projects"]["add"]) {
    $user = new user();
    $users = $user->getAllUsers(1000000);
    $template->assign("users", $users);

}

//by default the arrays have a level for each project, whcih contains arrays for each message/task . reduce array flattens this to have all messages/tasks of all projects in one structure
if (!empty($messages)) {
    $messages = reduceArray($messages);
}
$etasks = reduceArray($tasks);

//Create sort array for multisort by adding the daysleft value to a sort array
$sort = array();
foreach($etasks as $etask) {
    array_push($sort, $etask["daysleft"]);
}
//Sort using array_multisort
array_multisort($sort, SORT_NUMERIC, SORT_ASC, $etasks);

//Make toggle state of blocks on the desktop permanent by using cookies
if (getArrayVal($_COOKIE, "taskhead")) {
    $taskstyle = "display:" . $_COOKIE['taskhead'];
    $template->assign("taskstyle", $taskstyle);
    $taskbar = "win_" . $_COOKIE['taskhead'];
} else {
    $taskbar = "win_block";
}
if (getArrayVal($_COOKIE, "mileshead")) {
    $milestyle = "display:" . $_COOKIE['mileshead'];
    $template->assign("milestyle", $milestyle);
    $milebar = "win_" . $_COOKIE['mileshead'];
} else {
    $milebar = "win_block";
}
if (getArrayVal($_COOKIE, "projecthead")) {
    $projectstyle = "display:" . $_COOKIE['projecthead'];
    $template->assign("projectstyle", $projectstyle);
    $projectbar = "win_" . $_COOKIE['projecthead'];
} else {
    $projectbar = "win_block";
}
if (getArrayVal($_COOKIE, "activityhead")) {
    $actstyle = "display:" . $_COOKIE['activityhead'];
    $template->assign("actstyle", $actstyle);
    $activitybar = "win_" . $_COOKIE['activityhead'];
} else {
    $activitybar = "win_block";
}

//On Admin Login check for updates
$mode = getArrayVal($_GET, "mode");
if ($mode == "login") {
	$chkLim = 0;
    // only check if an admin logs in
    if ($userpermissions["admin"]["add"]) {
        // only check 1/2 of the times an admin logs in, to reduce server load
        $chkLim = mt_rand(1, 2);
        if ($chkLim == 1) {
            $updateChk = getUpdateNotify();
            if ($updateChk->pubDate > CL_PUBDATE) {
                $template->assign("isUpdated", true);
                $template->assign("updateNotify", $updateChk);
            }
        }
    }
}
//Get todays date and count tasks, projects and messages for display
$today = date("d");
$tasknum = count($etasks);
$projectnum = count($myprojects);
$msgnum = count($messages);

$title = $langfile["desktop"];

//Assign everything to the template engine
$template->assign("title", $title);
$template->assign("taskbar", $taskbar);
$template->assign("milebar", $milebar);
$template->assign("projectbar", $projectbar);
$template->assign("actbar", $activitybar);

$template->assign("today", $today);

$template->assign("myprojects", $myprojects);
$template->assign("projectnum", $projectnum);
$template->assign("projectov", "yes");

$template->assign("mode", $mode);

$template->assign("tasks", $etasks);
$template->assign("tasknum", $tasknum);

$template->assign("messages", $messages);
$template->assign("msgnum", $msgnum);
$template->display("index.tpl");
?>
