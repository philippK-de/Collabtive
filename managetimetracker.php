<?php
require("init.php");
// check if the user is logged in
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
$language = Array();
$language['a_meta_charset'] = 'UTF-8';
$language['a_meta_dir'] = 'ltr';
$language['a_meta_language'] = 'en';
$language['w_page'] = 'page';

// create timetracker instance
$tracker = new timetracker();

//Get data from $_POST and $_GET filtered and sanitized by htmlpurifier
$cleanGet = cleanArray($_GET);
$cleanPost = cleanArray($_POST);

$action = getArrayVal($_GET, "action");
$redir = getArrayVal($_GET, "redir");
$mode = getArrayVal($_GET, "mode");
$id = getArrayVal($_GET, "id");


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

if (empty($cleanGet["usr"])) {
    $cleanGet["usr"] = 0;
}
if (empty($cleanGet["task"])) {
    $cleanGet["task"] = 0;
}

$template->assign("mode", $mode);
if (isset($id)) {
    $project = array('ID' => $id);
    $template->assign("project", $project);
}

$classes = array("overview" => "overview", "msgs" => "msgs", "tasks" => "tasks", "miles" => "miles", "files" => "files", "users" => "users", "tracker" => "tracking_active");
$template->assign("classes", $classes);

/*
 * VIEW ROUTES
 * These are routes that render HTML views to the browser or create side effects
 */


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
        $cleanPost["started"] = date("H:i:s", $cleanPost["started"]);
        $cleanPost["ended"] = date("H:i:s", $cleanPost["ended"]);

        $cleanPost["comment"] = "";
    }

    if ($tracker->add($userid, $cleanPost["project"], $cleanPost["ttask"], $cleanPost["comment"], $cleanPost["started"], $cleanPost["ended"], $cleanPost["ttday"], $cleanPost["ttendday"])) {
        $redir = urldecode($redir);
        if ($redir) {
            $redir = $url . $redir;
            header("Location: $redir");
        } elseif ($ajaxreq == 1) {
            echo "ok";
        } else {
            $loc = $url . "manageproject.php?action=showproject&id=" . $cleanPost["project"] . "&mode=timeadded";
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
    $cleanPost["ttask"] = new task();
    $cleanGet["user"] = new user();
    // get track to edit
    $track = $tracker->getTrack($cleanGet["tid"]);
    // get username
    $member = $cleanGet["user"]->getProfile($track["user"]);
    $track["username"] = $member["name"];
    if ($track["task"] != 0) {
        // get task
        $thetask = $cleanPost["ttask"]->getTask($track["task"]);
        if (empty($thetask["title"])) {
            $taskname = substr($thetask["text"], 0, 30);
        } else {
            $taskname = substr($thetask["title"], 0, 30);
        }
        $track["taskname"] = $taskname;
    }
    $template->assign("track", $track);
    // get current and closed tasks
    $newtasks = $cleanPost["ttask"]->getProjectTasks($id);
    $oldtasks = $cleanPost["ttask"]->getProjectTasks($id, false);
    // if the project has both - merge them into one array
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
    // construct timestamps;
    $cleanPost["started"] = $cleanPost["day"] . " " . $cleanPost["started"];
    $cleanPost["started"] = strtotime($cleanPost["started"]);
    $cleanPost["ended"] = $cleanPost["endday"] . " " . $cleanPost["ended"];
    $cleanPost["ended"] = strtotime($cleanPost["ended"]);
    //edit the entry
    if ($tracker->edit($cleanGet["tid"], $cleanPost["ttask"], $cleanPost["comment"], $cleanPost["started"], $cleanPost["ended"])) {
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

    if ($tracker->del($cleanGet["tid"])) {
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
    //create a new CSV file
    $excelFile = fopen(CL_ROOT . "/files/" . CL_CONFIG . "/ics/timetrack-$id.csv", "w");

    //put the column headers to csv
    $line = array($struser, $strtask, $strcomment, $strday, $strstarted, $strended, $strhours);
    fputcsv($excelFile, $line);

    if (!empty($cleanGet["start"]) and !empty($cleanGet["end"])) {
        $track = $tracker->getProjectTrack($id, $cleanGet["usr"], $cleanGet["task"], $cleanGet["start"], $cleanGet["end"], false);
    } else {
        $track = $tracker->getProjectTrack($id, $cleanGet["usr"], $cleanGet["task"], 0, 0, false);
    }

    if (!empty($track)) {
        foreach ($track as $tra) {
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

    $id = (int)$id;
    // get the project name
    $projectNameQuery = $conn->query("SELECT name FROM projekte WHERE ID = $id");
    $projectName = $projectNameQuery->fetchColumn();
    // create a new PDF in portrait orientation, A4 format
    $pdf = new MYPDF("P", PDF_UNIT, "A4", true);
    // Set the header
    $headstr = $langfile["timetable"] . " " . $projectName;
    $pdf->setup($headstr, array(239, 232, 229));

    // headers for table columns
    $headers = array($langfile["user"], $langfile["task"], $langfile["comment"], $langfile["started"] . " - " . $langfile["ended"], $langfile["hours"]);

    // if a filter has been applied, get only those timetracks
    if (!empty($cleanGet["start"]) and !empty($cleanGet["end"])) {
        $track = $tracker->getProjectTrack($id, $cleanGet["usr"], $cleanGet["task"], $cleanGet["start"], $cleanGet["end"], false);
    } else {
        $track = $tracker->getProjectTrack($id, $cleanGet["usr"], $cleanGet["task"], 0, 0, false);
    }
    // array representing the content of the table. each field is a column
    $thetrack = array();
    if (!empty($track)) {
        $i = 0;
        foreach ($track as $timetrack) {
            if (empty($timetrack["tname"])) {
                $timetrack["tname"] = "";
            }
            $hrs = round($timetrack["hours"], 2);
            $hrs = number_format($hrs, 2, ",", ".");

            $timetrack["comment"] = strip_tags($timetrack["comment"]);

            $i = $i + 1;
            // write the table line
            array_push($thetrack, array($timetrack["uname"], $timetrack["tname"], $timetrack["comment"], $timetrack["daystring"] . "/" . $timetrack["startstring"] . "-" . $timetrack["endstring"], $hrs));
        }
    }
    // put it all to the PDF and output the file
    $pdf->table($headers, $thetrack);
    $pdf->Output("project-$id-timetable.pdf", "D");
} elseif ($action == "userxls") {
    $excelFile = fopen(CL_ROOT . "/files/" . CL_CONFIG . "/ics/user-$id-timetrack.csv", "w");

    $line = array($strproj, $strtask, $strcomment, $strday, $strstarted, $strended, $strhours);
    fputcsv($excelFile, $line);
    if (!empty($cleanGet["start"]) and !empty($cleanGet["end"])) {
        $track = $tracker->getUserTrack($id, $cleanGet["project"], $cleanGet["task"], $cleanGet["start"], $cleanGet["end"], false);
    } else {
        $track = $tracker->getUserTrack($id, $cleanGet["project"], $cleanGet["task"], 0, 0, false);
    }
    if (!empty($track)) {
        foreach ($track as $tra) {
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
    if (!empty($cleanGet["start"]) and !empty($cleanGet["end"])) {
        $track = $tracker->getUserTrack($id, $cleanGet["project"], $cleanGet["task"], $cleanGet["start"], $cleanGet["end"], false);
    } else {
        $track = $tracker->getUserTrack($id, $cleanGet["project"], $cleanGet["task"], 0, 0, false);
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
        foreach ($track as $tra) {
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
    // Check if the user belongs to this project
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
    $task = getArrayVal($_POST, "task");
    // get open project tasks for filtering
    $projectObj = new project();
    $projectUsers = $projectObj->getProjectMembers($id, 1000, false);
    $project = $projectObj->getProject($id);

    $taskObj = new task();
    $projectTasks = $taskObj->getProjectTasks($id, 1);


    $template->assign("projectname", $project["name"]);
    $template->assign("users", $projectUsers);
    $title = $langfile["timetracker"];
    $template->assign("title", $title);
    $template->assign("ptasks", $projectTasks);
    $template->assign("usr", $cleanGet["usr"]);
    $template->assign("task", $cleanGet["task"]);


    $template->display("tracker_project.tpl");
}

/*
 * DATA ROUTES
 * These are routes that render JSON data structures
 */
elseif ($action == "projectTimetracker") {
    if (!$userpermissions["timetracker"]["view"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // Check if the user belongs to this project
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $tracker = (object)new timetracker();
    // If the user can not read tt entries from other user, set the user filter to the current user id.
    if (!$cleanGet["usr"]) {
        if (!$userpermissions["timetracker"]["read"]) {
            $cleanGet["usr"] = $userid;
        } else {
            $cleanGet["usr"] = 0;
        }
    }

    $offset = 0;
    if (isset($cleanGet["offset"])) {
        $offset = $cleanGet["offset"];
    }
    $limit = 25;
    if (isset($cleanGet["limit"])) {
        $limit = $cleanGet["limit"];
    }
    if (!empty($cleanGet["start"]) and !empty($cleanGet["end"])) {
        $track = $tracker->getProjectTrack($id, $cleanGet["usr"], $cleanGet["task"], $cleanGet["start"], $cleanGet["end"], $limit, $offset);
        $trackCount = count($tracker->getProjectTrack($id, $cleanGet["usr"], $cleanGet["task"], $cleanGet["start"], $cleanGet["end"], 100000000));
    } else {
        $track = $tracker->getProjectTrack($id, $cleanGet["usr"], $cleanGet["task"], 0, 0, $limit,$offset);
        $trackCount = count($tracker->getProjectTrack($id, $cleanGet["usr"], $cleanGet["task"], 0, 0, 10000000000));
    }
    if (!empty($track)) {
        $projectTrack["items"] = $track;
        $projectTrack["count"] = $trackCount;
        echo json_encode($projectTrack);
    }
    else
    {
        echo json_encode(array());
    }

}

?>
