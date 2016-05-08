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

$action = getArrayVal($_GET,"action");

$cleanGet = cleanArray($_GET);

// Set the desktop icon in the top icon menue
$mainclasses = array("desktop" => "active",
    "profil" => "",
    "admin" => ""
    );
$template->assign("mainclasses", $mainclasses);

// create objects

$project = new project();
$milestone = new milestone();
$mtask = new task();
$msg = new message();

// create arrays to hold data
$messages = array();
$milestones = array();
$tasks = array();
// create a counter for the foreach loop
$cou = 0;

$offset = 0;
if(isset($cleanGet["offset"]))
{
    $offset = $cleanGet["offset"];
}
$limit = 10;
if(isset($cleanGet["limit"]))
{
    $limit = $cleanGet["limit"];
}

$myOpenProjects = $project->getMyProjects($userid, 1, $offset, $limit);
$projectnum = $project->countMyProjects($userid, 1);
$template->assign("openProjects", $myOpenProjects);

// If user has projects, loop through them and get the messages and tasks belonging to those projects
if (!empty($myOpenProjects)) {
    foreach($myOpenProjects as $proj) {
        // get all the tasks in this project that are assigned to the current user
        $task = $mtask->getAllMyProjectTasks($proj["ID"]);
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
$messageCount = count($messages);

$etasks = reduceArray($tasks);

// Create sort array for multisort by adding the daysleft value to a sort array
$sort = array();
foreach($etasks as $etask) {
    array_push($sort, $etask["project"]);
}
// Sort using array_multisort
array_multisort($sort, SORT_NUMERIC, SORT_DESC, $etasks);
$taskCount = count($etasks);

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

if(!$action) {
// Assign everything to the template engine
    $template->assign("title", $langfile["desktop"]);
    $template->assign("today", $today);

    $template->assign("closedProjectnum", $project->countMyProjects($userid, 0));
    $template->assign("projectov", "yes");

    $template->assign("mode", $mode);

    $template->assign("tasknum", $taskCount);
    $template->assign("msgnum", $messageCount);
    $template->display("index.tpl");
}
elseif($action == "myprojects")
{
    //create datastructure for projects
    $projects["open"] = $myOpenProjects;
    $projects["closed"] = $project->getMyProjects($userid, 0);

    //add projects to datastructure for JSON
    $myprojects["items"] = $projects;
    //number of open projects total
    $myprojects["count"] = $projectnum;

    echo json_encode($myprojects);
}
elseif($action == "mytasks")
{
    $myTasks["items"] = $etasks;
    $myTasks["count"] = count($etasks);

    echo json_encode($myTasks);
}
elseif($action == "mymessages")
{
    $myMessages["items"] = $messages;
    $myMessages["count"] = count($messages);
    echo json_encode($myMessages);

}
?>
