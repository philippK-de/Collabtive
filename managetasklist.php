<?php
include("init.php");
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
$liste = (object) new tasklist();
$objmilestone = (object) new milestone();

//Get data from $_POST and $_GET filtered and sanitized by htmlpurifier
$cleanGet = cleanArray($_GET);
$cleanPost = cleanArray($_POST);

$action = getArrayVal($_GET, "action");
$id = getArrayVal($_GET, "id");
$tlid = getArrayVal($_GET, "tlid");
$mode = getArrayVal($_GET, "mode");


$project = array();
$project['ID'] = $id;
$classes = array("overview" => "overview",
    "msgs" => "msgs",
    "tasks" => "tasks_active",
    "miles" => "miles",
    "files" => "files",
    "users" => "users",
    "tracker" => "tracking"
    );
$template->assign("classes", $classes);
if (!chkproject($userid, $id)) {
    $errtxt = $langfile["notyourproject"];
    $noperm = $langfile["accessdenied"];
    $template->assign("errortext", "$errtxt<br>$noperm");
    $template->assign("mode", "error");
    $template->display("error.tpl");
    die();
}

if ($action == "addform") {
    $milestones = $objmilestone->getAllProjectMilestones($id, 10000);

    $title = $langfile['addtasklist'];
    $template->assign("title", $title);

    $template->assign("milestones", $milestones);
    $template->assign("projectid", $project);
    $template->display("addtasklist.tpl");
} elseif ($action == "add") {
    if ($liste->add_liste($id, $cleanPost["name"], $cleanPost["desc"], 0, $cleanPost["milestone"])) {
        $loc = $url . "managetask.php?action=showproject&id=$id&mode=listadded";
        header("Location: $loc");
    } else {
        $template->assign("addliste", 0);
    }
}
if ($action == "editform") {
    if (!$userpermissions["tasks"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $tasklist = $liste->getTasklist($tlid);

    $milestone = $objmilestone->getMilestone($tasklist["milestone"]);
    $tasklist["milestonename"] = $milestone["name"];

    $milestones = $objmilestone->getAllProjectMilestones($id, 10000);
    $project = array();
    $project["ID"] = $id;

    $projectObj = (object) new project();

    $projectDetails = $projectObj->getProject($id);

    $template->assign("title", $langfile["edittasklist"]);
    $template->assign("projectname",  $projectDetails["name"]);
    $template->assign("showhead", 1);
    $template->assign("milestones", $milestones);
    $template->assign("tasklist", $tasklist);
    $template->assign("project", $project);
    $template->display("edittasklist.tpl");
} elseif ($action == "edit") {
    if (!$userpermissions["tasks"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    if ($liste->edit_liste($tlid, $cleanPost["name"], $cleanPost["desc"], $cleanPost["milestone"])) {
        $loc = $url . "managetasklist.php?action=showtasklist&id=$id&tlid=$tlid&mode=edited";
        header("Location: $loc");
    } else {
        $template->assign("editliste", 0);
    }
} elseif ($action == "del") {
    if (!$userpermissions["tasks"]["del"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    if ($liste->del_liste($tlid)) {
        $loc = $url . "managetask.php?action=showproject&id=$id&mode=listdeleted";
        header("Location: $loc");
    } else {
        $template->assign("delliste", 0);
    }
} elseif ($action == "close") {
    if (!$userpermissions["tasks"]["close"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if ($liste->close_liste($tlid)) {
        $loc = $url . "managetask.php?action=showproject&id=$id&mode=listclosed";
        header("Location: $loc");
    } else {
        $template->assign("closeliste", 0);
    }
} elseif ($action == "open") {
    if (!$userpermissions["tasks"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if ($liste->open_liste($tlid)) {
        $loc = $url . "managetask.php?action=showproject&id=$id&mode=listopened";
        header("Location: $loc");
        // echo "ok";
    } else {
        $template->assign("openliste", 0);
    }
} elseif ($action == "showtasklist") {
    $myproject = (object) new project();
    $project_members = $myproject->getProjectMembers($id,$myproject->countMembers($id),false);

    $pro = $myproject->getProject($id);
    $projectname = $pro["name"];
    $template->assign("projectname", $projectname);

    $tasklist = $liste->getTasklist($tlid);
    $tasks = $liste->getTasksFromList($tlid);
    $tasklist["tasknum"] = count($tasks);
    $donetasks = $liste->getTasksFromList($tlid, 0);
    $tasklist["donetasknum"] = count($donetasks);

    $milestones = $objmilestone->getAllProjectMilestones($id, 10000);
    $template->assign("milestones", $milestones);

    $title = $langfile['tasklist'];
    $template->assign("title", $title);
    $template->assign("classes", $classes);
    $template->assign("tasklist", $tasklist);
    $template->assign("assignable_users", $project_members);
    $template->assign("tasks", $tasks);
    $template->assign("donetasks", $donetasks);
    $template->assign("project", $project);
    $template->display("tasklist.tpl");
}

?>