<?php
require("./init.php");
// check if user is logged in
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}

$milestone = new milestone();

$action = getArrayVal($_GET, "action");
$mid = getArrayVal($_GET, "mid");

$mode = getArrayVal($_GET, "mode");
$template->assign("mode", $mode);

//Get data from $_POST and $_GET filtered and sanitized by htmlpurifier
$cleanGet = cleanArray($_GET);
$cleanPost = cleanArray($_POST);

$id = getArrayVal($_GET, "id");

$project = array();
$project['ID'] = $id;
$template->assign("project", $project);
// define the active tab in the project navigation
$classes = array("overview" => "overview", "msgs" => "msgs", "tasks" => "tasks", "miles" => "miles_active", "files" => "files", "users" => "users", "tracker" => "tracking");
$template->assign("classes", $classes);
// check if the user belongs to the current project. die if he does not.
if (!chkproject($userid, $id)) {
    $errtxt = $langfile["notyourproject"];
    $noperm = $langfile["accessdenied"];
    $template->assign("errortext", "$errtxt<br>$noperm");
    $template->display("error.tpl");
    die();
}
if ($action == "add") {
    if (!$userpermissions["milestones"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // Get start date from form
    $start = getArrayVal($_POST, "start");

    $milestone_id = $milestone->add($id, $cleanPost["name"], $cleanPost["desc"], $start, $cleanPost["end"], 1);
    if ($milestone_id) {
        $liste = (object) new tasklist();
        if ($liste->add_liste($id, $cleanPost["name"], $cleanPost["desc"], 0, $milestone_id)) {
            //$loc = $url . "managetask.php?action=showproject&id=$id&mode=listadded";
        	$loc = $url . "managemilestone.php?action=showproject&id=$id&mode=added";
        } else {
            $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=added";
        }
        header("Location: $loc");
    }
} elseif ($action == "editform") {
    if (!$userpermissions["milestones"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $projectObj = new project();
    //get project info
    $project = $projectObj->getProject($id);

    // Get milestone info
    $milestone = $milestone->getMilestone($mid);

    $template->assign("title", $langfile["editmilestone"]);
    $template->assign("projectname", $project["name"]);
    $template->assign("milestone", $milestone);
    $template->display("editmilestone.tpl");
} elseif ($action == "edit") {
    if (!$userpermissions["milestones"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // Edit the milestone
    if ($milestone->edit($cleanPost["mid"], $cleanPost["name"], $cleanPost["desc"], $cleanPost["start"], $cleanPost["end"])) {
        $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=edited";
        header("Location: $loc");
    } else {
        $template->assign("editmilestone", 0);
    }
} elseif ($action == "del") {
    if (!$userpermissions["milestones"]["del"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    // Delete the milestone
    if ($milestone->del($mid)) {
        echo "ok";
        // $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=deleted";
        // header("Location: $loc");
    } else {
        $template->assign("delmilestone", 0);
    }
} elseif ($action == "open") {
    $project = $_GET['project'];
    if ($milestone->open($mid)) {
        $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=opened";
        header("Location: $loc");
    } else {
        $template->assign("openmilestone", 0);
    }
} elseif ($action == "close") {
    // $project = $_GET['project'];
    if ($milestone->close($mid)) {
        // $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=closed";
        // header("Location: $loc");
        echo "ok";
    } else {
        $template->assign("closemilestone", 0);
    }
} elseif ($action == "assign") {
    if ($milestone->assign($cleanPost["user"], $mid)) {
        $template->assign("assignmilestone", 1);
        $template->display("projectmilestones.tpl");
    } else {
        $template->assign("assignmilestone", 0);
    }
} elseif ($action == "deassign") {
    if ($milestone->deassign($cleanPost["user"], $mid)) {
        $template->assign("deassignmilestone", 1);
        $template->display("projectmilestones.tpl");
    } else {
        $template->assign("deassignmilestone", 0);
    }
} elseif ($action == "showproject") {
    if (!$userpermissions["milestones"]["view"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // Check if the current user belongs to the project
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $projectObj = new project();
    $today = date("d");
    // Get projects milestones, and todays project milestones
    $stones = $milestone->getProjectMilestones($id);
    $stones2 = $milestone->getTodayProjectMilestones($id);

    if (empty($stones2)) {
        $stones2 = array();
    }
    if (empty($stones)) {
        $stones = array();
    }
    // merge the current milestones
    $stones = array_merge($stones, $stones2);
    // Get closed milestones and milestones that are late
    $donestones = $milestone->getDoneProjectMilestones($id);
    //get late miletones
    $latestones = $milestone->getLateProjectMilestones($id);
    // Count late milestones
    if (!empty($latestones)) {
        $latestones = $milestone->formatdate($latestones);
    }
    $countlate = 0;
    if (!empty($latestones)) {
        $countlate = count($latestones);
    }
    // Get upcoming milestones, that is milestones with a start date in the future
    $upcomingStones = $milestone->getUpcomingProjectMilestones($id);
    $countUpcoming = 0;
    if (!empty($upcomingStones)) {
        $countUpcoming = count($upcomingStones);
    }
    // Get the project name
    $project = $projectObj->getProject($id);

    $template->assign("milestones", $stones);
    $template->assign("title", $langfile["milestones"]);
    $template->assign("projectname",  $project["name"]);
    $template->assign("latemilestones", $latestones);
    $template->assign("upcomingStones", $upcomingStones);
    $template->assign("upcomingcount", $countUpcoming);
    $template->assign("donemilestones", $donestones);
    $template->assign("countlate", $countlate);
    $template->assign("project", $project);
    $template->display("projectmilestones.tpl");
} elseif ($action == "mileslist") {
    $stones = $milestone->getProjectMilestones($id);
    if (!empty($stones)) {
        $stones2 = $milestone->getTodayProjectMilestones($id);
    }
    if (!empty($stones2)) {
        $stones = array_merge($stones, $stones2);
    }
    $template->assign("milestones", $stones);
    $template->display("mileslist.tpl");
} elseif ($action == "showmilestone") {
    $msid = $_GET['msid'];

    $myproject = new project();
    $pro = $myproject->getProject($id);
    $projectname = $pro["name"];
    $template->assign("projectname", $projectname);

    $milestone = $milestone->getMilestone($msid);
    $title = $langfile['milestone'];
    $template->assign("title", $title);
    $template->assign("project", $project);
    $template->assign("milestone", $milestone);
    $template->display("milestone.tpl");
}