<?php
require("init.php");
// check if the user is logged in
if (!isset($_SESSION["userid"]))
{
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

if (empty($usr))
{
    $usr = 0;
}
if (empty($taski))
{
    $taski = 0;
}

$template->assign("mode", $mode);
if (isset($id))
{
    $project = array('ID' => $id);
    $template->assign("project", $project);
}

$classes = array("overview" => "overview", "msgs" => "msgs", "tasks" => "tasks", "miles" => "miles", "files" => "files", "users" => "users", "tracker" => "tracking_active");
$template->assign("classes", $classes);

if ($action == "add")
{
	$worked = $_POST["worked"];
    $ajaxreq = $_GET["ajaxreq"];
    if ($ajaxreq == 1) {
        $lodate = date("d.m.Y");
        $started = date("H:i:s", $started);
        $ended = date("H:i:s", $ended);

        $comment = "";
    }

	if ($tracker->add($userid, $tproject, $task, $comment , $started, $ended, $logdate))
    {
        $redir = urldecode($redir);
        if ($redir)
        {
            $redir = $url . $redir;
            header("Location: $redir");
        } elseif ($ajaxreq == 1)
        {
            echo "ok";
        }
        else
        {
            $loc = $url . "manageproject.php?action=showproject&id=$tproject&mode=timeadded";
            header("Location: $loc");
        }
    }
    else
    {
        $goback = $langfile["goback"];
        $endafterstart = $langfile["endafterstart"];
        $template->assign("mode", "error");
        $template->assign("errortext", "$endafterstart<br>$goback");
        $template->display("error.tpl");
    }
} elseif ($action == "editform")
{
    // create task and user instance
    $task = new task();
    $user = new user();
    // get track to edit
    $track = $tracker->getTrack($tid);
    // get username
    $member = $user->getProfile($track["user"]);
    $track["username"] = $member["name"];
    if ($track["task"] != 0)
    {
        // get task
        $thetask = $task->getTask($track["task"]);
        if (empty($thetask["title"]))
        {
            $taskname = substr($thetask["text"], 0, 30);
        }
        else
        {
            $taskname = substr($thetask["title"], 0, 30);
        }
        $track["taskname"] = $taskname;
    }
    $template->assign("track", $track);

    $tasks = $task->getProjectTasks($id);
    for ($i = 0; $i < count($tasks); $i++)
    {
        if (empty($tasks[$i]["title"]))
        {
            $name = substr($tasks[$i]["text"], 0, 30);
        }
        else
        {
            $name = substr($tasks[$i]["title"], 0, 30);
        }
        $tasks[$i]["name"] = $name;
    }
    $template->assign("tasks", $tasks);

    $title = $langfile['edittimetracker'];
    $template->assign("title", $title);

    $template->display("edittrackform.tpl");
} elseif ($action == "edit")
{
    $started = $day . " " . $started;
    $started = strtotime($started);
    $ended = $day . " " . $ended;
    $ended = strtotime($ended);
    if ($tracker->edit($tid, $task, $comment, $started, $ended))
    {
        if ($redir)
        {
            $redir = $url . $redir;
            header("Location: $redir");
        }
        else
        {
            $loc = $url . "managetimetracker.php?action=showproject&id=$id&mode=edited";
            header("Location: $loc");
        }
    }
} elseif ($action == "del")
{
    /*
    if (!$userpermissions["timetracker"]["del"])
    {
        $template->assign("errortext", "Permission denied.");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }
    */

    if ($tracker->del($tid))
    {
        $redir = urldecode($redir);
        if ($redir)
        {
            $loc = $url . $redir;
            header("Location: $loc");
        }
        else
        {
            // $loc = $url . "managetimetracker.php?action=showproject&id=$id&mode=deleted";
            echo "ok";
        }
    }
} elseif ($action == "projectxls")
{
    if (!chkproject($userid, $id))
    {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }
    $excel = new xls(CL_ROOT . "/files/" . CL_CONFIG . "/ics/timetrack-$id.xls");

    $line = array($struser, $strtask, $strcomment, $strday, $strstarted, $strended, $strhours);
    $excel->writeHeadLine($line, "128");
    if (!empty($start) and !empty($end))
    {
        $track = $tracker->getProjectTrack($id, $usr, $taski, $start, $end, 1000);
    }
    else
    {
        $track = $tracker->getProjectTrack($id, $usr , $taski, 0, 0, 1000);
    }

    if (!empty($track))
    {
        foreach($track as $tra)
        {
            $hrs = round($tra["hours"], 2);
            $hrs = str_replace(".", ",", $hrs);
            $myArr = array($tra["uname"], $tra["tname"], $tra["comment"], $tra["daystring"], $tra["startstring"], $tra["endstring"], $hrs);
            $excel->writeLine($myArr);
        }

        $totaltime = $tracker->getTotalTrackTime($track);
        $totaltime = str_replace(".", ",", $totaltime);
        $line = array("Total:", "", "", "", $totaltime);
        $excel->writeRow();
        $excel->writeColspan("<b>Total:</b>", 6);
        $excel->writeCol("<b>$totaltime</b>");
    }

    $excel->close();
    $loc = $url . "files/" . CL_CONFIG . "/ics/timetrack-$id.xls";
    header("Location: $loc");
} elseif ($action == "projectpdf")
{
    if (!chkproject($userid, $id))
    {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $sel = mysql_query("SELECT name FROM projekte WHERE ID = $id");
    $pname = mysql_fetch_row($sel);
    $pname = $pname[0];

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
    $pdf->SetHeaderData("", 0, "" , $langfile["timetable"] . " " . $pname);

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_DATA, '', 20));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 8));
    $pdf->SetHeaderMargin(0);
    $pdf->SetFont(PDF_FONT_NAME_DATA, "", 11);

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setLanguageArray($l);

    $pdf->AliasNbPages();
    $pdf->AddPage();

    $htmltable = "<table border=\"1\" bordercolor = \"#d9dee8\" width=\"500\">
	<tr bgcolor=\"#d9dee8\" style=\"font-weight:bold;\">
	<th align=\"center\" width=\"80\">$langfile[user]</th>
    <th align=\"center\" width=\"125\">$langfile[task]</th>
    <th align=\"center\" width=\"125\">$langfile[comment]</th>
    <th align=\"center\" width=\"130\">$langfile[started] - $langfile[ended]</th>
    <th align=\"center\" width=\"40\">$langfile[hours]</th>
	</tr>";
    if (!empty($start) and !empty($end))
    {
        $track = $tracker->getProjectTrack($id, $usr, $taski, $start, $end, 1000);
    }
    else
    {
        $track = $tracker->getProjectTrack($id, $usr , $taski, 0, 0, 1000);
    }
    $thetrack = array();
    if (!empty($track))
    {
        $i = 0;
        foreach($track as $tra)
        {
            if (empty($tra["tname"]))
            {
                $tra["tname"] = "";
            }
            $hrs = round($tra["hours"], 2);
            $hrs = number_format($hrs, 2, ",", ".");

            $tra["comment"] = strip_tags($tra["comment"]);

            if ($i % 2 == 0)
            {
                $fill = "#ffffff";
            }
            else
            {
                $fill = "#d9dee8";
            }
            $i = $i + 1;

            $htmltable .= "<tr bgcolor=\"$fill\">
			<td width=\"80\">$tra[uname]</td>
			<td width=\"125\">$tra[tname]</td>
			<td width=\"125\">$tra[comment]</td>
			<td width=\"130\" align=\"center\">$tra[daystring] / $tra[startstring] - $tra[endstring]</td>
			<td width=\"40\" align=\"right\">$hrs</td>
			</tr>";
        }

        $totaltime = $tracker->getTotalTrackTime($track);
        $totaltime = str_replace(".", ",", $totaltime);

        $htmltable .= "<tr style=\"font-weight:bold;\"><td colspan=\"5\" align=\"right\">$totaltime</td></tr></table>";

        $pdf->writeHTML($htmltable, true, 0, true, 0);
        $pdf->Output("project-$id-timetable.pdf", "D");
    }
} elseif ($action == "userxls")
{
    $excel = new xls(CL_ROOT . "/files/" . CL_CONFIG . "/ics/user-$id-timetrack.xls");

    $line = array($strproj, $strtask, $strcomment, $strday, $strstarted, $strended, $strhours);
    $excel->writeHeadLine($line, "128");
    if (!empty($start) and !empty($end))
    {
        $track = $tracker->getUserTrack($id, $fproject, $taski, $start, $end);
    }
    else
    {
        $track = $tracker->getUserTrack($id, $fproject, $taski, 0, 0 , 1000);
    }
    if (!empty($track))
    {
        foreach($track as $tra)
        {
            $hrs = round($tra["hours"], 2);
            $hrs = str_replace(".", ",", $hrs);
            $myArr = array($tra["pname"], $tra["tname"], $tra["comment"], $tra["daystring"], $tra["startstring"], $tra["endstring"], $hrs);
            $excel->writeLine($myArr);
        }

        $totaltime = $tracker->getTotalTrackTime($track);
        $totaltime = str_replace(".", ",", $totaltime);
        $line = array("Total:", "", "", "" , $totaltime);
        $excel->writeRow();
        $excel->writeColspan("<b>Total:</b>", 6);
        $excel->writeCol("<b>$totaltime</b>");
    }

    $excel->close();
    $loc = $url . "files/" . CL_CONFIG . "/ics/user-$id-timetrack.xls";
    header("Location: $loc");
} elseif ($action == "userpdf")
{
    if (!empty($start) and !empty($end))
    {
        $track = $tracker->getUserTrack($id, $fproject, $taski, $start, $end);
    }
    else
    {
        $track = $tracker->getUserTrack($id, $fproject, $taski, 0, 0, 1000);
    }
    $thetrack = array();

    $totaltime = $tracker->getTotalTrackTime($track);
    $totaltime = str_replace(".", ",", $totaltime);
    $id = mysql_real_escape_string($id);
    $sel = mysql_query("SELECT name FROM user WHERE ID = $id");
    $uname = mysql_fetch_array($sel);
    $uname = $uname[0];

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
    $pdf->SetHeaderData("", 0, "", $langfile["timetable"] . " " . $uname);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_DATA, '', 20));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 8));
    $pdf->SetHeaderMargin(0);
    $pdf->SetFont(PDF_FONT_NAME_DATA, "", 11);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setLanguageArray($l);
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $htmltable = "<table border=\"1\" bordercolor = \"#d9dee8\" >
	<tr bgcolor=\"#d9dee8\" style=\"font-weight:bold;\">
	<th  width=\"80\" align=\"center\">$langfile[project]</th><th width=\"125\" align=\"center\">$langfile[task]</th><th width=\"125\" align=\"center\">$langfile[comment]</th><th width=\"140\" align=\"center\">$langfile[started] - $langfile[ended]</th><th width=\"40\" align=\"center\">$langfile[hours]</th>
	</tr>";

    if (!empty($track))
    {
        $i = 0;
        foreach($track as $tra)
        {
            if (empty($tra["tname"]))
            {
                $tra["tname"] = "";
            }
            $hrs = round($tra["hours"], 2);
            $hrs = number_format($hrs, 2, ",", ".");

            $tra["comment"] = strip_tags($tra["comment"]);
            if ($i % 2 == 0)
            {
                $fill = "#ffffff";
            }
            else
            {
                $fill = "#d9dee8";
            }
            $i = $i + 1;

            $htmltable .= "<tr bgcolor=\"$fill\">
			<td width=\"80\">$tra[pname]</td>
			<td width=\"125\">$tra[tname]</td>
			<td width=\"125\">$tra[comment]</td>
			<td width=\"140\" align=\"center\">$tra[daystring] / $tra[startstring] - $tra[endstring]</td>
			<td width=\"40\" align=\"right\">$hrs</td>
			</tr>";
        }

        $totaltime = $tracker->getTotalTrackTime($track);
        $totaltime = str_replace(".", ",", $totaltime);

        $htmltable .= "<tr><td colspan=\"5\" align=\"right\">$totaltime</td></tr></table>";

        $pdf->writeHTML($htmltable, true, 0, true, 0);
        $pdf->Output("user-$uname-timetable.pdf", "D");
    }
} elseif ($action == "showproject")
{
    if (!chkproject($userid, $id))
    {
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
    if (!$usr)
    {
        if (!$userpermissions["timetracker"]["read"])
        {
            $usr = $userid;
        }
        else
        {
            $usr = 0;
        }
    }
    if (!empty($start) and !empty($end))
    {
        $track = $tracker->getProjectTrack($id, $usr, $taski, $start, $end,25);
    }
    else
    {
        $track = $tracker->getProjectTrack($id, $usr, $taski,0,0,25);
    }
    if (!empty($track))
    {
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