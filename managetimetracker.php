<?php
require("init.php");
// check if the user is logged in
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
$l = Array();
$l['a_meta_charset'] = 'UTF-8';
$l['a_meta_dir'] = 'ltr';
$l['a_meta_language'] = 'en';
// TRANSLATIONS --------------------------------------
$l['w_page'] = 'page';
// create timetracker instance
$tracker = new timetracker();

$action = getArrayVal($_GET, "action");
$day = getArrayVal($_POST, "day");
$started = getArrayVal($_POST, "started");
$ended = getArrayVal($_POST, "ended");
$tproject = getArrayVal($_POST, "project");
$task = getArrayVal($_POST, "ttask");
$logdate = getArrayVal($_POST, "ttday");
$comment = getArrayVal($_POST, "comment");
$redir = getArrayVal($_GET, "redir");
$mode = getArrayVal($_GET, "mode");
$id = getArrayVal($_GET, "id");
$tid = getArrayVal($_GET, "tid");
$user = getArrayVal($_GET, "user");
$start = getArrayVal($_GET, "start");
$end = getArrayVal($_GET, "end");
$usr = getArrayVal($_GET, "usr");
$taski = getArrayVal($_GET, "task");
$fproject = getArrayVal($_GET, "project");

/**
 * Get strings from the langfile and decode them to ASCII/ANSI
 * Needed for PDF
 */
$strproj = utf8_decode($langfile["project"]);
$strtimetrack = utf8_decode($langfile["timetracker"]);
$struser = utf8_decode($langfile["user"]);
$strstarted = utf8_decode($langfile["started"]);
$strday = utf8_decode($langfile["day"]);
$strended = utf8_decode($langfile["ended"]);
$strhours = utf8_decode($langfile["hours"]);
$strtask = utf8_decode($langfile["task"]);
// $strtimetable = utf8_decode($langfile["timetable"]);
$strcomment = utf8_decode($langfile["comment"]);

if (empty($usr)) {
    $usr = 0;
}
if (empty($taski)) {
    $taski = 0;
}

$template->assign("mode", $mode);
if (isset($id)) {
    $project = array('ID' => $id);
    $template->assign("project", $project);
}

$classes = array("overview" => "overview", "msgs" => "msgs", "tasks" => "tasks", "miles" => "miles", "files" => "files", "users" => "users", "tracker" => "tracking_active");
$template->assign("classes", $classes);

if ($action == "add") {
    if (!$userpermissions["timetracker"]["add"]) {
        $template->assign("errortext", "Permission denied.");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }

    $worked = $_POST["worked"];
    $ajaxreq = $_GET["ajaxreq"];
    if ($ajaxreq == 1) {
        $lodate = date("d.m.Y");
        $started = date("H:i:s", $started);
        $ended = date("H:i:s", $ended);

        $comment = "";
    }

    if ($tracker->add($userid, $tproject, $task, $comment , $started, $ended, $logdate)) {
        $redir = urldecode($redir);
        if ($redir) {
            $redir = $url . $redir;
            header("Location: $redir");
        } elseif ($ajaxreq == 1) {
            echo "ok";
        } else {
            $loc = $url . "manageproject.php?action=showproject&id=$tproject&mode=timeadded";
            header("Location: $loc");
        }
    } else {
        $goback = $langfile["goback"];
        $endafterstart = $langfile["endafterstart"];
        $template->assign("mode", "error");
        $template->assign("errortext", "$endafterstart<br>$goback");
        $template->display("error.tpl");
    }
} elseif ($action == "editform") {
    if (!$userpermissions["timetracker"]["edit"]) {
        $template->assign("errortext", "Permission denied.");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }
    // create task and user instance
    $task = new task();
    $user = new user();
    // get track to edit
    $track = $tracker->getTrack($tid);
    // get username
    $member = $user->getProfile($track["user"]);
    $track["username"] = $member["name"];
    if ($track["task"] != 0) {
        // get task
        $thetask = $task->getTask($track["task"]);
        if (empty($thetask["title"])) {
            $taskname = substr($thetask["text"], 0, 30);
        } else {
            $taskname = substr($thetask["title"], 0, 30);
        }
        $track["taskname"] = $taskname;
    }
    $template->assign("track", $track);

    $newtasks = $task->getProjectTasks($id);
    $oldtasks = $task->getProjectTasks($id, false);
    if ($newtasks and $oldtasks) {
        $tasks = array_merge($newtasks, $oldtasks);
    } else {
        $tasks = $newtasks;
    }
    for ($i = 0; $i < count($tasks); $i++) {
        if (empty($tasks[$i]["title"])) {
            $name = substr($tasks[$i]["text"], 0, 30);
        } else {
            $name = substr($tasks[$i]["title"], 0, 30);
        }
        $tasks[$i]["name"] = $name;
    }
    $template->assign("tasks", $tasks);

    $title = $langfile['edittimetracker'];
    $template->assign("title", $title);

    $template->display("edittrackform.tpl");
} elseif ($action == "edit") {
    if (!$userpermissions["timetracker"]["edit"]) {
        $template->assign("errortext", "Permission denied.");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }

    $started = $day . " " . $started;
    $started = strtotime($started);
    $ended = $day . " " . $ended;
    $ended = strtotime($ended);
    if ($tracker->edit($tid, $task, $comment, $started, $ended)) {
        if ($redir) {
            $redir = $url . $redir;
            header("Location: $redir");
        } else {
            $loc = $url . "managetimetracker.php?action=showproject&id=$id&mode=edited";
            header("Location: $loc");
        }
    }
} elseif ($action == "del") {
    if (!$userpermissions["timetracker"]["del"]) {
        $template->assign("errortext", "Permission denied.");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }

    if ($tracker->del($tid)) {
        $redir = urldecode($redir);
        if ($redir) {
            $loc = $url . $redir;
            header("Location: $loc");
        } else {
            // $loc = $url . "managetimetracker.php?action=showproject&id=$id&mode=deleted";
            echo "ok";
        }
    }
} elseif ($action == "projectxls") {
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }
    $excelFile = fopen(CL_ROOT . "/files/" . CL_CONFIG . "/ics/timetrack-$id.csv", "w");

    $line = array($struser, $strtask, $strcomment, $strday, $strstarted, $strended, $strhours);
    fputcsv($excelFile, $line);

    if (!empty($start) and !empty($end)) {
        $track = $tracker->getProjectTrack($id, $usr, $taski, $start, $end, 1000);
    } else {
        $track = $tracker->getProjectTrack($id, $usr , $taski, 0, 0, 1000);
    }

    if (!empty($track)) {
        foreach($track as $tra) {
            $hrs = round($tra["hours"], 2);
            $hrs = str_replace(".", ",", $hrs);
            $myArr = array($tra["uname"], $tra["tname"], $tra["comment"], $tra["daystring"], $tra["startstring"], $tra["endstring"], $hrs);
            fputcsv($excelFile, $myArr);
        }
    }

    fclose($excelFile);
    $loc = $url . "files/" . CL_CONFIG . "/ics/timetrack-$id.csv";
    header("Location: $loc");
} elseif ($action == "projectpdf") {
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
		global $conn;
    $pname = $conn->query("SELECT name FROM projekte WHERE ID = $id");
    $pname = $pname->fetchColumn();

    $pdf = new MYPDF("P", PDF_UNIT, "A4", true);
    $headstr = $langfile["timetable"] . " " . $pname;
    $pdf->setup($headstr, array(239, 232, 229));

    $headers = array($langfile["user"], $langfile["task"], $langfile["comment"], $langfile["started"] . " - " . $langfile["ended"], $langfile["hours"]);

    if (!empty($start) and !empty($end)) {
        $track = $tracker->getProjectTrack($id, $usr, $taski, $start, $end, 1000);
    } else {
        $track = $tracker->getProjectTrack($id, $usr , $taski, 0, 0, 1000);
    }
    $thetrack = array();
    if (!empty($track)) {
        $i = 0;
        foreach($track as $tra) {
            if (empty($tra["tname"])) {
                $tra["tname"] = "";
            }
            $hrs = round($tra["hours"], 2);
            $hrs = number_format($hrs, 2, ",", ".");

            $tra["comment"] = strip_tags($tra["comment"]);

            $i = $i + 1;
            array_push($thetrack, array($tra["uname"], $tra["tname"], $tra["comment"], $tra["daystring"] . "/" . $tra["startstring"] . "-" . $tra["endstring"], $hrs));
        }

        $pdf->table($headers, $thetrack);
        $pdf->Output("project-$id-timetable.pdf", "D");
    }
} elseif ($action == "userxls") {
    $excelFile = fopen(CL_ROOT . "/files/" . CL_CONFIG . "/ics/user-$id-timetrack.csv", "w");

    $line = array($strproj, $strtask, $strcomment, $strday, $strstarted, $strended, $strhours);
    fputcsv($excelFile, $line);
    if (!empty($start) and !empty($end)) {
        $track = $tracker->getUserTrack($id, $fproject, $taski, $start, $end);
    } else {
        $track = $tracker->getUserTrack($id, $fproject, $taski, 0, 0 , 1000);
    }
    if (!empty($track)) {
        foreach($track as $tra) {
            $hrs = round($tra["hours"], 2);
            $hrs = str_replace(".", ",", $hrs);
            $myArr = array($tra["pname"], $tra["tname"], $tra["comment"], $tra["daystring"], $tra["startstring"], $tra["endstring"], $hrs);
            fputcsv($excelFile, $myArr);
        }
    }
    fclose($excelFile);
    $loc = $url . "files/" . CL_CONFIG . "/ics/user-$id-timetrack.csv";
    header("Location: $loc");
} elseif ($action == "userpdf") {
    if (!empty($start) and !empty($end)) {
        $track = $tracker->getUserTrack($id, $fproject, $taski, $start, $end);
    } else {
        $track = $tracker->getUserTrack($id, $fproject, $taski, 0, 0, 1000);
    }
    $thetrack = array();

    $totaltime = $tracker->getTotalTrackTime($track);
    $totaltime = str_replace(".", ",", $totaltime);
    $uname = $conn->query("SELECT name FROM user WHERE ID = {$conn->quote($id)}")->fetch();
    $uname = $uname[0];

    $pdf = new MYPDF("P", PDF_UNIT, "A4", true);
    $pdf->setup($langfile["timetable"] . " " . $uname, array(239, 232, 229));

    $headers = array($langfile["project"], $langfile["task"], $langfile["comment"], $langfile["started"] . " - " . $langfile["ended"], $langfile["hours"]);

    $thetrack = array();
    if (!empty($track)) {
        foreach($track as $tra) {
            if (empty($tra["tname"])) {
                $tra["tname"] = "";
            }
            $hrs = round($tra["hours"], 2);
            $hrs = number_format($hrs, 2, ",", ".");

            $tra["comment"] = strip_tags($tra["comment"]);
            array_push($thetrack, array($tra["pname"], $tra["tname"], $tra["comment"], $tra["daystring"] . "/" . $tra["startstring"] . "-" . $tra["endstring"], $hrs));
        }

        $totaltime = $tracker->getTotalTrackTime($track);
        $totaltime = str_replace(".", ",", $totaltime);

        $pdf->table($headers, $thetrack);
        $pdf->Output("user-$uname-timetable.pdf", "D");
    }
} elseif ($action == "showproject") {
    if (!$userpermissions["timetracker"]["view"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $start = getArrayVal($_POST, "start");
    $end = getArrayVal($_POST, "end");
    $usr = getArrayVal($_POST, "usr");
    $taski = getArrayVal($_POST, "task");

    $task = new task();
    $ptasks = $task->getProjectTasks($id, 1);
    $tracker = (object) new timetracker();
    if (!$usr) {
        if (!$userpermissions["timetracker"]["read"]) {
            $usr = $userid;
        } else {
            $usr = 0;
        }
    }

    if (!empty($start) and !empty($end)) {
        $track = $tracker->getProjectTrack($id, $usr, $taski, $start, $end, 25);
    } else {
        $track = $tracker->getProjectTrack($id, $usr, $taski, 0, 0, 25);
    }
    if (!empty($track)) {
        $totaltime = $tracker->getTotalTrackTime($track);
        $template->assign("totaltime", $totaltime);
        $template->assign("fproject", $fproject);
        $template->assign("start", $start);
        $template->assign("end", $end);
    }
    $pro = new project();
    $usrs = $pro->getProjectMembers($id, 1000, false);
    $proj = $pro->getProject($id);
    $projectname = $proj["name"];
    $template->assign("projectname", $projectname);
    $template->assign("users", $usrs);
    $title = $langfile["timetracker"];
    $template->assign("title", $title);
    $template->assign("ptasks", $ptasks);
    $template->assign("start", $start);
    $template->assign("end", $end);
    $template->assign("usr", $usr);
    $template->assign("task", $taski);
    $template->assign("tracker", $track);
    SmartyPaginate::assign($template);
    $template->display("tracker_project.tpl");
}

?>