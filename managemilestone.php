<?php
require("./init.php");
// check if user is logged in
if (!isset($_SESSION["userid"]))
{
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}

$milestone = new milestone();

$action = getArrayVal($_GET, "action");
$mid = getArrayVal($_GET, "mid");

$mode = getArrayVal($_GET, "mode");
$template->assign("mode", $mode);

$id = getArrayVal($_GET, "id");
$name = getArrayVal($_POST, "name");
$desc = getArrayVal($_POST, "desc");
$status = getArrayVal($_POST, "status");
$user = getArrayVal($_POST, "user");
$end = getArrayVal($_POST, "end");
$lim = getArrayVal($_POST, "lim");

$project = array();
$project['ID'] = $id;
$template->assign("project", $project);
// define the active tab in the project navigation
$classes = array("overview" => "overview", "msgs" => "msgs", "tasks" => "tasks", "miles" => "miles_active", "files" => "files", "users" => "users", "tracker" => "tracking");
$template->assign("classes", $classes);
// check if the user belongs to the current project. die if he does not.
if (!chkproject($userid, $id))
{
    $errtxt = $langfile["notyourproject"];
    $noperm = $langfile["accessdenied"];
    $template->assign("errortext", "$errtxt<br>$noperm");
    $template->display("error.tpl");
    die();
}
if ($action == "addform")
{
    if (!$userpermissions["milestones"]["add"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $day = getArrayVal($_GET, "theday");
    $month = getArrayVal($_GET, "themonth");
    $year = getArrayVal($_GET, "theyear");

    $pro = new project();
    $tpro = $pro->getProject($id);

    $title = $langfile['addmilestone'];
    $projectname = $tpro["name"];

    $template->assign("year", $year);
    $template->assign("month", $month);
    $template->assign("day", $day);
    $template->assign("projectname", $projectname);
    $template->assign("title", $title);
    $template->assign("showhtml", "yes");
    $template->display("addmilestone.tpl");
} elseif ($action == "add")
{
    if (!$userpermissions["milestones"]["add"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $status = 1;
    if ($milestone->add($id, $name, $desc, $end, $status))
    {
        $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=added";
        header("Location: $loc");
    }
} elseif ($action == "editform")
{
    if (!$userpermissions["milestones"]["edit"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $pro = new project();
    $tpro = $pro->getProject($id);
    $projectname = $tpro["name"];

    $title = $langfile['editmilestone'];
    $template->assign("title", $title);

    $milestone = $milestone->getMilestone($mid);

    $template->assign("projectname", $projectname);
    $template->assign("milestone", $milestone);
    $template->display("editmilestone.tpl");
} elseif ($action == "edit")
{
    if (!$userpermissions["milestones"]["edit"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $mid = $_POST['mid'];
    if ($milestone->edit($mid, $name, $desc, $end))
    {
        $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=edited";
        header("Location: $loc");
    }
    else
    {
        $template->assign("editmilestone", 0);
    }
} elseif ($action == "del")
{
    if (!$userpermissions["milestones"]["del"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $project = $_GET['project'];
    if ($milestone->del($mid))
    {
        $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=deleted";
        header("Location: $loc");
    }
    else
    {
        $template->assign("delmilestone", 0);
    }
} elseif ($action == "open")
{
    $project = $_GET['project'];
    if ($milestone->open($mid))
    {
        $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=opened";
        header("Location: $loc");
    }
    else
    {
        $template->assign("openmilestone", 0);
    }
} elseif ($action == "close")
{
    $project = $_GET['project'];
    if ($milestone->close($mid))
    {
        $loc = $url . "managemilestone.php?action=showproject&id=$id&mode=closed";
        header("Location: $loc");
    }
    else
    {
        $template->assign("closemilestone", 0);
    }
} elseif ($action == "assign")
{
    if ($milestone->assign($user, $mid))
    {
        $template->assign("assignmilestone", 1);
        $template->display("projectmilestones.tpl");
    }
    else
    {
        $template->assign("assignmilestone", 0);
    }
} elseif ($action == "deassign")
{
    if ($milestone->deassign($user, $mid))
    {
        $template->assign("deassignmilestone", 1);
        $template->display("projectmilestones.tpl");
    }
    else
    {
        $template->assign("deassignmilestone", 0);
    }
} elseif ($action == "showproject")
{
    $pro = new project();

    $today = date("d");
    $latestones = $milestone->getLateProjectMilestones($id);
    $donestones = $milestone->getDoneProjectMilestones($id);
    if (!empty($latestones))
    {
        $latestones = $milestone->formatdate($latestones);
    }
    $countlate = 0;
    if (!empty($latestones))
    {
        $countlate = count($latestones);
    }

    $tpro = $pro->getProject($id);
    $projectname = $tpro["name"];
    $title = $langfile['milestones'];

    $stones = $milestone->getProjectMilestones($id);
    $stones2 = $milestone->getTodayProjectMilestones($id);

    if (empty($stones2))
    {
        $stones2 = array();
    }
    if (empty($stones))
    {
        $stones = array();
    }
    $stones = array_merge($stones, $stones2);

    $template->assign("milestones", $stones);
    $template->assign("title", $title);
    $template->assign("projectname", $projectname);
    $template->assign("latemilestones", $latestones);
    $template->assign("donemilestones", $donestones);
    $template->assign("countlate", $countlate);
    $template->assign("project", $project);
    $template->display("projectmilestones.tpl");
} elseif ($action == "mileslist")
{
    $stones = $milestone->getProjectMilestones($id);
    if (!empty($stones))
    {
        $stones2 = $milestone->getTodayProjectMilestones($id);
    }
    if (!empty($stones2))
    {
        $stones = array_merge($stones, $stones2);
    }
    $template->assign("milestones", $stones);
    $template->display("mileslist.tpl");
} elseif ($action == "milescal")
{
    $pro = new project();
    $timeline1 = $pro->getTimeline($id, 0, 7);
    $timeline2 = $pro->getTimeline($id, 7, 14);
    $timeline3 = $pro->getTimeline($id, 14, 21);
    $timeline4 = $pro->getTimeline($id, 21, 28);
    $timestr = $pro->getTimestr();
    $today = date("d");
    $timestring = array();
    foreach($timestr as $times)
    {
        $times = $langfile[$times];
        array_push($timestring, $times);
    }
    $tpro = $pro->getProject($id);
    $projectname = $tpro["name"];
    $title = $langfile['milestones'];

    if (!empty($timeline1))
    {
        $template->assign("timeline1", $timeline1);
        $template->assign("timeline2", $timeline2);
        $template->assign("timeline3", $timeline3);
        $template->assign("timeline4", $timeline4);
        $template->assign("timestr", $timestring);
        $template->assign("today", $today);
    }

    $template->assign("title", $title);
    $template->display("milescal.tpl");
} elseif ($action == "showmilestone")
{
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
