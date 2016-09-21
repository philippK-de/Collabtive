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

/*
 * VIEW ROUTES
 * These are routes that render HTML views to the browser
 */
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
        $liste = (object)new tasklist();
        if ($liste->add_liste($id, $cleanPost["name"], $cleanPost["desc"], 0, $milestone_id)) {
            //$loc = $url . "managetask.php?action=showproject&id=$id&mode=listadded";
            $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=added";
        } else {
            $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=added";
        }
        // header("Location: $loc");
        echo "ok";
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

    // Get milestone ID and start date from form
    $mid = $cleanPost["mid"];
    $start = $cleanPost["start"];
    $end = $cleanPost["end"];

    // Edit the milestone
    $changeAllDueDates = $cleanPost["changeallduedates"];

    if ($changeAllDueDates == "on") {
        $milestoneData = $milestone->getMilestone($mid);

        $endOffset = strtotime($end) - $milestoneData["end"];

        $milestoneTasklist = $milestone->getMilestoneTasklists($mid);

        $updStmt = $conn->prepare("UPDATE tasks SET `end`=? WHERE ID = ?");
        foreach ($milestoneTasklist as $tasklist) {
            foreach ($tasklist["tasks"] as $task) {
                $newEnd = $task["end"] + $endOffset;
                $upd = $updStmt->execute(array($newEnd, $task["ID"]));
            }
        }
    }

    // Edit the milestone
    if ($milestone->edit($mid, $cleanPost["name"], $cleanPost["desc"], $start, $end)) {
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

    // Get the project name
    $project = $projectObj->getProject($id);

    $template->assign("title", $langfile["milestones"]);
    $template->assign("projectname", $project["name"]);

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


/*
 * DATA ROUTES
 * These are routes that render JSON data structures
 */
elseif ($action == "projectMilestones") {
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
    // Get projects milestones, and todays project milestones
    $stones = $milestone->getProjectMilestones($id);
    $stones2 = $milestone->getTodayProjectMilestones($id);
    // Get closed milestones and milestones that are late
    $donestones = $milestone->getDoneProjectMilestones($id);

    if (empty($stones2)) {
        $stones2 = array();
    }
    if (empty($stones)) {
        $stones = array();
    }


    // merge the current milestones
    $stones = array_merge($stones, $stones2);

    $milestones["open"] = $stones;
    $milestones["closed"] = $donestones;

    $projectStones["items"] = $milestones;
    $projectStones["count"] = count($stones);

    echo json_encode($projectStones);
} elseif ($action == "lateProjectMilestones") {
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
    //get late miletones
    $latestones = $milestone->getLateProjectMilestones($id);
    // Count late milestones
    if (!empty($latestones)) {
        $latestones = $milestone->formatdate($latestones);
    }

    $lateProjectStones["items"] = $latestones;
    $lateProjectStones["count"] = count($latestones);

    echo json_encode($lateProjectStones);
} elseif ($action == "upcomingProjectMilestones") {
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
    // Get upcoming milestones, that is milestones with a start date in the future
    $upcomingStones = $milestone->getUpcomingProjectMilestones($id);

    $upcomingProjectStones["items"] = $upcomingStones;
    $upcomingProjectStones["count"] = count($upcomingStones);

    echo json_encode($upcomingProjectStones);
}