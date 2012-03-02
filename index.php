<?php
require("./init.php");

if (!isset($_SESSION["userid"]))
{
    $template->assign("loginerror", 0);
    $mode = getArrayVal($_GET, "mode");
    $template->assign("mode", $mode);
    $template->display("login.tpl");
    die();
}
// collabtive doesn't seem to be installed properly , redirect to installer
if (empty($db_name) or empty($db_user))
{
    $loc = $url . "install.php";
    header("Location: " . $loc);
}
$mainclasses = array("desktop" => "active",
    "profil" => "",
    "admin" => ""
    );
$template->assign("mainclasses", $mainclasses);

$project = new project();
$milestone = new milestone();
$mtask = new task();
$msg = new message();

$myprojects = $project->getMyProjects($userid);
$messages = array();
$milestones = array();
$tasks = array();
$cou = 0;

if (!empty($myprojects))
{
    foreach($myprojects as $proj)
    {
        $task = $mtask->getAllMyProjectTasks($proj["ID"], 100);
        $msgs = $msg->getProjectMessages($proj["ID"]);

        if (!empty($msgs))
        {
            array_push($messages, $msgs);
        }

        if (!empty($task))
        {
            array_push($tasks, $task);
        }

        $cou = $cou + 1;
    }
}

if($userpermissions["projects"]["add"])
{
$user = new user();
$users = $user->getAllUsers(1000000);
$template->assign("users", $users);
}

if (!empty($messages))
{
    $messages = reduceArray($messages);
}
$etasks = reduceArray($tasks);

$sort = array();
foreach($etasks as $etask)
{
    array_push($sort, $etask["daysleft"]);
}
array_multisort($sort, SORT_NUMERIC, SORT_ASC, $etasks);

if (getArrayVal($_COOKIE, "taskhead"))
{
    $taskstyle = "display:" . $_COOKIE['taskhead'];
    $template->assign("taskstyle", $taskstyle);
    $taskbar = "win_" . $_COOKIE['taskhead'];
}
else
{
    $taskbar = "win_block";
}
if (getArrayVal($_COOKIE, "mileshead"))
{
    $milestyle = "display:" . $_COOKIE['mileshead'];
    $template->assign("milestyle", $milestyle);
    $milebar = "win_" . $_COOKIE['mileshead'];
}
else
{
    $milebar = "win_block";
}
if (getArrayVal($_COOKIE, "projecthead"))
{
    $projectstyle = "display:" . $_COOKIE['projecthead'];
    $template->assign("projectstyle", $projectstyle);
    $projectbar = "win_" . $_COOKIE['projecthead'];
}
else
{
    $projectbar = "win_block";
}
if (getArrayVal($_COOKIE, "activityhead"))
{
    $actstyle = "display:" . $_COOKIE['activityhead'];
    $template->assign("actstyle", $actstyle);
    $activitybar = "win_" . $_COOKIE['activityhead'];
}
else
{
    $activitybar = "win_block";
}

$today = date("d");
$tasknum = count($etasks);
$projectnum = count($myprojects);
$msgnum = count($messages);

$title = $langfile["desktop"];

$template->assign("title", $title);
$template->assign("taskbar", $taskbar);
$template->assign("milebar", $milebar);
$template->assign("projectbar", $projectbar);
$template->assign("actbar", $activitybar);


$template->assign("today", $today);

$template->assign("myprojects", $myprojects);
$template->assign("projectnum", $projectnum);
$mode = getArrayVal($_GET, "mode");
$template->assign("mode", $mode);

$template->assign("tasks", $etasks);
$template->assign("tasknum", $tasknum);

$template->assign("messages", $messages);
$template->assign("msgnum", $msgnum);
$template->display("index.tpl");

?>