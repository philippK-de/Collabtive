<?php
require("init.php");
if (!isset($_SESSION['userid'])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}

$project = (object) new project();

$action = getArrayVal($_GET, "action");
$redir = getArrayVal($_GET, "redir");
$id = getArrayVal($_GET, "id");
$usr = getArrayVal($_GET, "user");
$assignto = getArrayVal($_POST, "assignto");
$name = getArrayVal($_POST, "name");
$desc = getArrayVal($_POST, "desc");
$end = getArrayVal($_POST, "end");
$status = getArrayVal($_POST, "status");
$user = getArrayVal($_POST, "user");
$assign = getArrayVal($_POST, "assginme");
$budget = getArrayVal($_POST, "budget");

$projectid = array();
$projectid['ID'] = $id;
$template->assign("project", $projectid);

$strproj = utf8_decode($langfile["project"]);
$struser = utf8_decode($langfile["user"]);
$activity = $langfile["activity"];
$straction = utf8_decode($langfile["action"]);
$strtext = utf8_decode($langfile["text"]);
$strdate = utf8_decode($langfile["day"]);
$strstarted = utf8_decode($langfile["started"]);
$strdays = utf8_decode($langfile["daysleft"]);
$strdue = utf8_decode($langfile["due"]);
$stropen = utf8_decode($langfile["openprogress"]);
$strdone = utf8_decode($langfile["done"]);
$strdesc = utf8_decode($langfile["description"]);

$l = Array();
$l['a_meta_charset'] = 'UTF-8';
$l['a_meta_dir'] = 'ltr';
$l['a_meta_language'] = 'en';
$l['w_page'] = 'page';

$mode = getArrayVal($_GET, "mode");
$template->assign("mode", $mode);
// define the active tab in the project navigation
$classes = array("overview" => "overview_active", "msgs" => "msgs", "tasks" => "tasks", "miles" => "miles", "files" => "files", "users" => "users", "tracker" => "tracking");
$template->assign("classes", $classes);

if ($action == "editform") {
    if (!$userpermissions["projects"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }
    $thisproject = $project->getProject($id);
    $title = $langfile["editproject"];

    $template->assign("title", $title);
    $template->assign("project", $thisproject);
    $template->assign("showhtml", "no");
    $template->assign("showheader", "no");
    $template->assign("async", "yes");
    $template->display("editform.tpl");
} elseif ($action == "edit") {
    if (!$userpermissions["projects"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }

    if (!$end) {
        $end = 0;
    }

    if ($project->edit($id, $name, $desc, $end, $budget)) {
        header("Location: manageproject.php?action=showproject&id=$id&mode=edited");
    } else {
        $template->assign("editproject", 0);
    }
} elseif ($action == "del") {
    if (!$userpermissions["projects"]["del"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }
    if ($project->del($id)) {
        if ($redir) {
            $redir = $url . $redir;
            header("Location: $redir");
        } else {
            echo "ok";
        }
    } else {
        $template->assign("delproject", 0);
    }
} elseif ($action == "open") {
    if (!$userpermissions["projects"]["close"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }
    $id = $_GET['id'];
    if ($project->open($id)) {
        header("Location: manageproject.php?action=showproject&id=$id");
    } else {
        $template->assign("openproject", 0);
    }
} elseif ($action == "close") {
    if (!$userpermissions["projects"]["close"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $id = $_GET['id'];
    if ($project->close($id)) {
        echo "ok";
    } else {
        $template->assign("closeproject", 0);
    }
} elseif ($action == "assign") {
    if (!$userpermissions["projects"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if ($project->assign($user, $id)) {
        if ($settings["mailnotify"]) {
            $usr = (object) new user();
            $user = $usr->getProfile($user);

            if (!empty($user["email"])) {
                // send email
                $themail = new emailer($settings);
                $themail->send_mail($user["email"], $langfile["projectassignedsubject"] , $langfile["hello"] . ",<br /><br/>" . $langfile["projectassignedtext"] . " <a href = \"" . $url . "manageproject.php?action=showproject&id=$id\">" . $url . "manageproject.php?action=showproject&id=$id</a>");
            }
        }
        if ($redir) {
            $loc = $url . $redir;
        } else {
            $loc = $url . "manageuser.php?action=showproject&id=$id&mode=assigned";
        }
        header("Location: $loc");
    }
} elseif ($action == "deassignform") {
    if (!$userpermissions["projects"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $userobj = new user();
    $user = $userobj->getProfile($usr);
    $proj = $project->getProject($id);
    // Get members of the project
    $members = $project->getProjectMembers($id);

    $title = $langfile["deassignuser"];

    $template->assign("title", $title);
    $template->assign("redir", $redir);
    $template->assign("user", $user);
    $template->assign("project", $proj);
    $template->assign("members", $members);
    $template->display("deassignuserform.tpl");
} elseif ($action == "deassign") {
    if (!$userpermissions["projects"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $task = new task();
    $tasks = $task->getAllMyProjectTasks($id, 100, $usr);

    if ($id > 0 and $assignto > 0) {
        if (!empty($tasks)) {
            foreach($tasks as $mytask) {
                if ($task->deassign($mytask["ID"], $usr)) {
                    $task->assign($mytask["ID"], $assignto);
                }
            }
        }
    } else {
        if (!empty($tasks)) {
            foreach($tasks as $mytask) {
                $task->del($mytask["ID"]);
            }
        }
    }

    if ($project->deassign($usr, $id)) {
        if ($redir) {
            $redir = $url . $redir;
            $redir = $redir . "&mode=deassigned";
            header("Location: $redir");
        } else {
            header("Location: manageuser.php?action=showproject&id=$id&mode=deassigned");
        }
    }
} elseif ($action == "projectlogpdf") {
    if (!$userpermissions["admin"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");

        $template->display("error.tpl");
        die();
    }

    $pdf = new MYPDF("P", PDF_UNIT, "A4", true);

    $tproject = $project->getProject($id);
    $headstr = $tproject["name"] . " " . $activity;
    $pdf->setup($headstr,array(235,234,234));

    $headers = array($langfile["action"],$langfile["day"],$langfile["user"]);

    $thelog = new mylog();
    $datlog = array();
    $tlog = $thelog->getProjectLog($id, 100000);
    $tlog = $thelog->formatdate($tlog, CL_DATEFORMAT . " / H:i:s");
    if (!empty($tlog)) {
        $i = 0;
        foreach($tlog as $logged) {

            if ($logged["action"] == 1) {
                $actstr = $langfile["added"];
            } elseif ($logged["action"] == 2) {
                $actstr = $langfile["edited"];
            } elseif ($logged["action"] == 3) {
                $actstr = $langfile["deleted"];
            } elseif ($logged["action"] == 4) {
                $actstr = $langfile["opened"];
            } elseif ($logged["action"] == 5) {
                $actstr = $langfile["closed"];
            } elseif ($logged["action"] == 6) {
                $actstr = $langfile["assigned"];
            }
            $i = $i + 1;

            $obstr = $logged["name"];

			array_push($datlog,array($obstr . " " . $langfile["was"] . " " . $actstr,$logged["datum"],$logged["username"]));
        }
    }
	$pdf->table($headers,$datlog);
    $pdf->Output("project-$id-log.pdf", "D");
} elseif ($action == "projectlogxls") {
    if (!$userpermissions["admin"]["add"]) {
        $template->assign("errortext", "Permission denied.");
        $template->display("error.tpl");
        die();
    }

    $excel = new xls(CL_ROOT . "/files/" . CL_CONFIG . "/ics/project-$id-log.xls");

    $headline = array(" ", $strtext, $straction, $strdate, $struser);
    $excel->writeHeadLine($headline, "128");

    $thelog = new mylog();
    $datlog = array();
    $tlog = $thelog->getProjectLog($id, 100000);
    $tlog = $thelog->formatdate($tlog, "d.m.y");
    if (!empty($tlog)) {
        foreach($tlog as $logged) {
            if ($logged["type"] == "datei") {
                $logged["type"] = "file";
            } elseif ($logged["type"] == "projekt") {
                $logged["type"] = "project";
            } elseif ($logged["type"] == "track") {
                $logged["type"] = "timetracker";
            }

            $icon = utf8_decode($langfile[$logged["type"]]);
            if ($logged["action"] == 1) {
                $actstr = utf8_decode($langfile["added"]);
            } elseif ($logged["action"] == 2) {
                $actstr = utf8_decode($langfile["edited"]);
            } elseif ($logged["action"] == 3) {
                $actstr = utf8_decode($langfile["deleted"]);
            } elseif ($logged["action"] == 4) {
                $actstr = utf8_decode($langfile["opened"]);
            } elseif ($logged["action"] == 5) {
                $actstr = utf8_decode($langfile["closed"]);
            } elseif ($logged["action"] == 6) {
                $actstr = utf8_decode($langfile["assigned"]);
            }

            $obstr = $logged["name"];
            $obstr = utf8_decode($obstr);
            $obstr = substr($obstr, 0, 75);

            $data = array($icon, $obstr, $actstr, $logged["datum"], $logged["username"]);
            $excel->writeLine($data);
        }
    }
    $excel->close();
    $loc = $url . "files/" . CL_CONFIG . "/ics/project-$id-log.xls";
    header("Location: $loc");
} elseif ($action == "showproject") {
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }

    if (getArrayVal($_COOKIE, "milehead")) {
        $milestyle = "display:" . $_COOKIE['milehead'];
        $template->assign("milestyle", $milestyle);
        $milebar = "win_" . $_COOKIE['milehead'];
    } else {
        $milebar = "win_block";
    }
    if (getArrayVal($_COOKIE, "trackerhead")) {
        $trackstyle = "display:" . $_COOKIE['trackerhead'];
        $template->assign("trackstyle", $trackstyle);
        $trackbar = "win_" . $_COOKIE['trackerhead'];
    } else {
        $trackbar = "win_block";
    }
    if (getArrayVal($_COOKIE, "loghead")) {
        $logstyle = "display:" . $_COOKIE['loghead'];
        $template->assign("logstyle", $logstyle);
        $logbar = "win_" . $_COOKIE['loghead'];
    } else {
        $logbar = "win_block";
    }
    if (getArrayVal($_COOKIE, "status")) {
        $statstyle = "display:" . $_COOKIE['status'];
        $template->assign("statstyle", $statstyle);
        $statbar = "win_" . $_COOKIE['status'];
    } else {
        $statbar = "win_block";
    }
    $template->assign("milebar", $milebar);
    $template->assign("trackbar", $trackbar);
    $template->assign("logbar", $logbar);
    $template->assign("statbar", $statbar);
    $milestone = (object) new milestone();
    $mylog = (object) new mylog();
    $task = new task();
    $ptasks = $task->getProjectTasks($id, 1);
    $today = date("d");

    $log = $mylog->getProjectLog($id);
    $log = $mylog->formatdate($log);

    $tproject = $project->getProject($id);
    $done = $project->getProgress($id);

    $cloud = new tags();
    $cloud->cloudlimit = 1;
    $thecloud = $cloud->getTagcloud($id);
    if (strlen($thecloud) > 0) {
        $template->assign("cloud", $thecloud);
    }

    $title = $langfile['project'];
    $title = $title . " " . $tproject["name"];
    $template->assign("title", $title);

    $template->assign("project", $tproject);
    $template->assign("done", $done);

    $template->assign("ptasks", $ptasks);
    $template->assign("today", $today);

    $template->assign("log", $log);
    SmartyPaginate::assign($template);
    $template->display("project.tpl");
} elseif ($action == "cal") {
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }

    $thisd = date("j");
    $thism = date("n");
    $thisy = date("Y");

    $m = getArrayVal($_GET, "m");
    $y = getArrayVal($_GET, "y");
    if (!$m) {
        $m = $thism;
    }
    if (!$y) {
        $y = $thisy;
    }

    $nm = $m + 1;
    $pm = $m -1;
    if ($nm > 12) {
        $nm = 1;
        $ny = $y + 1;
    } else {
        $ny = $y;
    }
    if ($pm < 1) {
        $pm = 12;
        $py = $y-1;
    } else {
        $py = $y;
    }

    $today = date("d");

    $calobj = new calendar();
    $cal = $calobj->getCal($m, $y, $id);
    $weeks = $cal->calendar;
    // print_r($weeks);
    $mstring = strtolower(date('F', mktime(0, 0, 0, $m, 1, $y)));
    $mstring = $langfile[$mstring];
    $template->assign("mstring", $mstring);

    $template->assign("m", $m);
    $template->assign("y", $y);
    $template->assign("thism", $thism);
    $template->assign("thisd", $thisd);
    $template->assign("thisy", $thisy);
    $template->assign("nm", $nm);
    $template->assign("pm", $pm);
    $template->assign("ny", $ny);
    $template->assign("py", $py);
    $template->assign("weeks", $weeks);
    $template->assign("id", $id);
    $template->display("calbody_project.tpl");
}
