<?php
require("init.php");
if (!isset($_SESSION['userid'])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}

$project = (object) new project();
$company = (object) new company();

$action = getArrayVal($_GET, "action");

$cleanGet = cleanArray($_GET);
$cleanPost = cleanArray($_POST);

$projectid = array();
$projectid['ID'] = $cleanGet["id"];
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

$pdfLanguage = Array();
$pdfLanguage['a_meta_charset'] = 'UTF-8';
$pdfLanguage['a_meta_dir'] = 'ltr';
$pdfLanguage['a_meta_language'] = 'en';
$pdfLanguage['w_page'] = 'page';

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

    $thisproject = $project->getProject($cleanGet["id"]);
    $title = $langfile["editproject"];

    $template->assign("title", $title);
    $template->assign("project", $thisproject);
    $template->assign("showhtml", "no");
    $template->assign("showheader", "no");
    $template->assign("projectov", "yes");
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
    // If no end is set, default to 0
    if (!$cleanPost["end"]) {
        $cleanPost["end"] = 0;
    }

    $id = $cleanGet["id"];
    $end = $cleanPost["end"];
    $changeAllDueDates = $cleanPost["changeallduedates"];

    if($changeAllDueDates == "on"){
        $projectData = $project->getProject($id);
        $oldEnd = $projectData["end"];

        // Only update dependencies if project has an old and new due date
        if ($end != 0 && $oldEnd) {
            $endOffset = strtotime($end) - $oldEnd;

            // Update tasks
            $taskObj = new task();
            $projectTasks = $taskObj->getProjectTasks($id);

            $taskUpdStmt = $conn->prepare("UPDATE tasks SET `end`=? WHERE ID = ?");
            foreach($projectTasks as $task)
            {
                $newEnd = $task["end"] + $endOffset;
                $upd = $taskUpdStmt->execute(array($newEnd, $task["ID"]));
            }

            // Update milestones
            $milestoneObj = new milestone();
            $projectMilestones = $milestoneObj->getAllProjectMilestones($id, 10000);

            $milestoneUpdStmt = $conn->prepare("UPDATE milestones SET `end`=? WHERE ID = ?");
            foreach($projectMilestones as $milestone)
            {
                $newEnd = $milestone["end"] + $endOffset;
                $upd = $milestoneUpdStmt->execute(array($newEnd, $milestone["ID"]));
            }
        }
    }

    if ($project->edit($id, $cleanPost["name"], $cleanPost["desc"], $end, $cleanPost["budget"])) {
        header("Location: manageproject.php?action=showproject&id=" . $cleanGet["id"] . "&mode=edited");
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
    if ($project->del($cleanGet["id"])) {
        if (isset($cleanGet["redir"])) {
            $cleanGet["redir"] = $url . $cleanGet["redir"];
            header("Location: " . $cleanGet["redir"]);
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
    $cleanGet["id"] = $_GET['id'];
    if ($project->open($cleanGet["id"])) {
        header("Location: manageproject.php?action=showproject&id=" . $cleanGet["id"]);
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
    $cleanGet["id"] = $_GET['id'];
    if ($project->close($cleanGet["id"])) {
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
    if ($project->assign($cleanPost["user"], $cleanGet["id"])) {
        if ($settings["mailnotify"]) {
            $cleanGet["user"] = (object) new user();
            $cleanPost["user"] = $cleanGet["user"]->getProfile($cleanPost["user"]);

            if (!empty($cleanPost["user"]["email"])) {
                $userlang = readLangfile($cleanPost["user"]['locale']);

                $subject = $userlang["projectassignedsubject"] . ' (' . $userlang['by'] . ' ' . $username . ')';

                $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                $userlang["projectassignedtext"] .
                " <a href = \"" . $url . "manageproject.php?action=showproject&id=" . $cleanGet["id"]. "\">" . $url . "manageproject.php?action=showproject&id=" . $cleanGet["id"] . "</a>";
                // send email
                $themail = new emailer($settings);
                $themail->send_mail($cleanPost["user"]["email"], $subject , $mailcontent);
            }
        }
        if ($cleanGet["redir"]) {
            $loc = $url . $cleanGet["redir"];
        } else {
            $loc = $url . "manageuser.php?action=showproject&id=" . $cleanGet["id"] . "&mode=assigned";
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
    $cleanPost["user"] = $userobj->getProfile($cleanGet["user"]);
    $proj = $project->getProject($cleanGet["id"]);
    // Get members of the project
    $members = $project->getProjectMembers($cleanGet["id"]);

    $title = $langfile["deassignuser"];

    $template->assign("title", $title);
    $template->assign("redir", $cleanGet["redir"]);
    $template->assign("user", $cleanPost["user"]);
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
    $tasks = $task->getAllMyProjectTasks($cleanGet["id"], $cleanGet["user"]);

    if ($cleanGet["id"] > 0 and $cleanPost["assignto"] > 0) {
        if (!empty($tasks)) {
            foreach($tasks as $mytask) {
                if ($task->deassign($mytask["ID"], $cleanGet["user"])) {
                    $task->assign($mytask["ID"], $cleanPost["assignto"]);
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

    if ($project->deassign($cleanGet["user"], $cleanGet["id"])) {
        if ($cleanGet["redir"]) {
            $cleanGet["redir"] = $url . $cleanGet["redir"];
            $cleanGet["redir"] = $cleanGet["redir"] . "&mode=deassigned";
            header("Location: " . $cleanGet["redir"]);
        } else {
            header("Location: manageuser.php?action=showproject&id=" . $cleanGet["id"] . "&mode=deassigned");
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

    $tproject = $project->getProject($cleanGet["id"]);
    $headstr = $tproject["name"] . " " . $activity;
    $pdf->setup($headstr, array(235, 234, 234));

    $headers = array($langfile["action"], $langfile["day"], $langfile["user"]);

    $datlog = array();
    $tlog = $mylog->getProjectLog($cleanGet["id"], 100000);
    $tlog = $mylog->formatdate($tlog, CL_DATEFORMAT . " / H:i:s");
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

            array_push($datlog, array($obstr . " " . $langfile["was"] . " " . $actstr, $logged["datum"], $logged["username"]));
        }
    }
    $pdf->table($headers, $datlog);
    $pdf->Output("project-" . $cleanGet["id"] . "-log.pdf", "D");
} elseif ($action == "projectlogxls") {
    if (!$userpermissions["admin"]["add"]) {
        $template->assign("errortext", "Permission denied.");
        $template->display("error.tpl");
        die();
    }

    $excelFile = fopen(CL_ROOT . "/files/" . CL_CONFIG . "/ics/project-" . $cleanGet["id"] . "-log.csv", "w");

    $headline = array(" ", $strtext, $straction, $strdate, $struser);
    fputcsv($excelFile, $headline);
    $datlog = array();
    $tlog = $mylog->getProjectLog($cleanGet["id"], 100000);
    $tlog = $mylog->formatdate($tlog, CL_DATEFORMAT);
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
            fputcsv($excelFile, $data);
        }
    }
    fclose($excelFile);
    $loc = $url . "files/" . CL_CONFIG . "/ics/project-" . $cleanGet["id"] . "-log.csv";
    header("Location: $loc");
} elseif ($action == "showproject") {
    if (!chkproject($userid, $cleanGet["id"])) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }

    $milebar = "win_block";
    $trackbar = "win_block";
    $logbar = "win_block";
    $statbar = "win_block";

    $template->assign("milebar", $milebar);
    $template->assign("trackbar", $trackbar);
    $template->assign("logbar", $logbar);
    $template->assign("statbar", $statbar);
    $template->assign("projectov", "no");

    $milestone = (object) new milestone();
    $task = new task();
    $ptasks = $task->getProjectTasks($cleanGet["id"], 1);
    $today = date("d");



    $tproject = $project->getProject($cleanGet["id"]);
    $done = $project->getProgress($cleanGet["id"]);

    $title = $langfile['project'];
    $title = $title . " " . $tproject["name"];
    $template->assign("title", $title);
    $template->assign("tree", $milestone->getAllProjectMilestones($cleanGet["id"], 1000));

    $template->assign("project", $tproject);
    $template->assign("done", $done);

    $template->assign("ptasks", $ptasks);
    $template->assign("today", $today);

    $template->assign("log",array());
    $template->display("project.tpl");
}
elseif($action == "projectLog")
{
    if (!chkproject($userid, $cleanGet["id"])) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }
    $offset = 0;
    if(isset($cleanGet["offset"]))
    {
        $offset = $cleanGet["offset"];
    }
    $limit = 25;
    if(isset($cleanGet["limit"]))
    {
        $limit = $cleanGet["limit"];
    }

    $mylog = (object) new mylog();
    $log = $mylog->getProjectLog($cleanGet["id"], $limit, $offset);
    $log = $mylog->formatdate($log);

    $projectLog["items"] = $log;
    $projectLog["count"] = count($mylog->getProjectLog($cleanGet["id"],1000000000));

    echo json_encode($projectLog);
}
elseif ($action == "tasklists") {
    $listObj = new tasklist();
    $theLists = $listObj->getProjectTasklists($cleanGet["id"]);
    echo "<option value=\"-1\" selected=\"selected\">$langfile[chooseone]</option>";
    foreach($theLists as $list) {
        echo "<option value = \"$list[ID]\">$list[name]</option>";
    }
}