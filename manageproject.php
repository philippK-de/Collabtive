<?php
require("init.php");
if (!isset($_SESSION['userid']))
{
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

if ($action == "editform")
{
    if (!$userpermissions["projects"]["edit"])
    {
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
    $template->display("editform.tpl");
} elseif ($action == "edit")
{
    if (!$userpermissions["projects"]["edit"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }

    if(!$end)
	{
		$end = 0;
	}

    if ($project->edit($id, $name, $desc, $end, $budget))
    {
        header("Location: manageproject.php?action=showproject&id=$id&mode=edited");
    }
    else
    {
        $template->assign("editproject", 0);
    }
} elseif ($action == "del")
{
    if (!$userpermissions["projects"]["del"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }
    if ($project->del($id))
    {
        if ($redir)
        {
            $redir = $url . $redir;
            header("Location: $redir");
        }
        else
        {
            echo "ok";
        }
    }
    else
    {
        $template->assign("delproject", 0);
    }
} elseif ($action == "open")
{
    if (!$userpermissions["projects"]["close"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }
    $id = $_GET['id'];
    if ($project->open($id))
    {
        header("Location: manageproject.php?action=showproject&id=$id");
    }
    else
    {
        $template->assign("openproject", 0);
    }
} elseif ($action == "close")
{
    if (!$userpermissions["projects"]["close"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $id = $_GET['id'];
    if ($project->close($id))
    {
        echo "ok";
    }
    else
    {
        $template->assign("closeproject", 0);
    }
} elseif ($action == "assign")
{
    if (!$userpermissions["projects"]["edit"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if ($project->assign($user, $id))
    {
        if ($settings["mailnotify"])
        {
            $usr = (object) new user();
            $user = $usr->getProfile($user);

            if (!empty($user["email"]))
            {
                // send email
                $themail = new emailer($settings);
                $themail->send_mail($user["email"], $langfile["projectassignedsubject"] , $langfile["hello"] . ",<br /><br/>" . $langfile["projectassignedtext"] . " <a href = \"" . $url . "manageproject.php?action=showproject&id=$id\">" . $url . "manageproject.php?action=showproject&id=$id</a>");
            }
        }
        if ($redir)
        {
            $loc = $url . $redir;
        }
        else
        {
            $loc = $url . "manageuser.php?action=showproject&id=$id&mode=assigned";
        }
        header("Location: $loc");
    }
} elseif ($action == "deassignform")
{
    if (!$userpermissions["projects"]["edit"])
    {
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
} elseif ($action == "deassign")
{
    if (!$userpermissions["projects"]["edit"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $task = new task();
    $tasks = $task->getAllMyProjectTasks($id, 100, $usr);

    if ($id > 0 and $assignto > 0)
    {
        if (!empty($tasks))
        {
            foreach($tasks as $mytask)
            {
                if ($task->deassign($mytask["ID"], $usr))
                {
                    $task->assign($mytask["ID"], $assignto);
                }
            }
        }
    }
    else
    {
        if (!empty($tasks))
        {
            foreach($tasks as $mytask)
            {
                $task->del($mytask["ID"]);
            }
        }
    }

    if ($project->deassign($usr, $id))
    {
        if ($redir)
        {
            $redir = $url . $redir;
            $redir = $redir . "&mode=deassigned";
            header("Location: $redir");
        }
        else
        {
            header("Location: manageuser.php?action=showproject&id=$id&mode=deassigned");
        }
    }
} elseif ($action == "projectlogpdf")
{
    if (!$userpermissions["admin"]["add"])
    {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");

        $template->display("error.tpl");
        die();
    }

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
    $tproject = $project->getProject($id);
    $headstr = $tproject["name"] . " " . $activity;

    $pdf->SetHeaderData("", 0, "" , $headstr);

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

    $htmltable = "<table border=\"1\" >
	<tr bgcolor=\"#d9dee8\" style=\"font-weight:bold;\">
	<th width=\"20\"></th><th align=\"center\">$langfile[title]</th><th align=\"center\">$langfile[action]</th><th align=\"center\">$langfile[day]</th><th>$langfile[user]</th>
	</tr>";

    $thelog = new mylog();
    $datlog = array();
    $tlog = $thelog->getProjectLog($id, 100000);
    $tlog = $thelog->formatdate($tlog, "d.m.y");
    if (!empty($tlog))
    {
        $i = 0;
        foreach($tlog as $logged)
        {
            if ($logged["type"] == "datei")
            {
                $logged["type"] = "file";
                $icon = "templates/standard/images/symbols/files.png";
            } elseif ($logged["type"] == "projekt")
            {
                $logged["type"] = "project";
                $icon = "templates/standard/images/symbols/projects.png";
            } elseif ($logged["type"] == "track")
            {
                $logged["type"] = "timetracker";
                $icon = "templates/standard/images/symbols/timetracker.png";
            } elseif ($logged["type"] == "task")
            {
                $icon = "templates/standard/images/symbols/task.png";
            } elseif ($logged["type"] == "message")
            {
                $icon = "templates/standard/images/symbols/msgs.png";
            } elseif ($logged["type"] == "milestone")
            {
                $icon = "templates/standard/images/symbols/miles.png";
            } elseif ($logged["type"] == "tasklist")
            {
                $icon = "templates/standard/images/symbols/tasklist.png";
            }
            
            if ($logged["action"] == 1)
            {
                $actstr = $langfile["added"];
            } elseif ($logged["action"] == 2)
            {
                $actstr = $langfile["edited"];
            } elseif ($logged["action"] == 3)
            {
                $actstr = $langfile["deleted"];
            } elseif ($logged["action"] == 4)
            {
                $actstr = $langfile["opened"];
            } elseif ($logged["action"] == 5)
            {
                $actstr = $langfile["closed"];
            } elseif ($logged["action"] == 6)
            {
                $actstr = $langfile["assigned"];
            }

            if ($i % 2 == 0)
            {
                $fill = "#ffffff";
            }
            else
            {
                $fill = "#d9dee8";
            }
            $i = $i + 1;

            $obstr = $logged["name"];

            $htmltable .= "<tr bgcolor=\"$fill\">
			<td width=\"20\"><img height=\"20\" width=\"20\" src=\"$icon\" /></td>
			<td>$obstr</td>
			<td>$actstr</td>
			<td>$logged[datum]</td>
			<td>$logged[username]</td>
			</tr>";
        }
    }
    $htmltable .= "</table>";
    $pdf->writeHTML($htmltable, true, 0, true, 0);
    $pdf->lastPage();
    $pdf->Output("project-$id-log.pdf", "D");
} elseif ($action == "projectlogxls")
{
    if (!$userpermissions["admin"]["add"])
    {
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
    if (!empty($tlog))
    {
        foreach($tlog as $logged)
        {
            if ($logged["type"] == "datei")
            {
                $logged["type"] = "file";
            } elseif ($logged["type"] == "projekt")
            {
                $logged["type"] = "project";
            } elseif ($logged["type"] == "track")
            {
                $logged["type"] = "timetracker";
            }

            $icon = utf8_decode($langfile[$logged["type"]]);
            if ($logged["action"] == 1)
            {
                $actstr = utf8_decode($langfile["added"]);
            } elseif ($logged["action"] == 2)
            {
                $actstr = utf8_decode($langfile["edited"]);
            } elseif ($logged["action"] == 3)
            {
                $actstr = utf8_decode($langfile["deleted"]);
            } elseif ($logged["action"] == 4)
            {
                $actstr = utf8_decode($langfile["opened"]);
            } elseif ($logged["action"] == 5)
            {
                $actstr = utf8_decode($langfile["closed"]);
            } elseif ($logged["action"] == 6)
            {
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
} elseif ($action == "pdfreport")
{
    $theproject = $project->getProject($id);
    $done = $project->getProgress($id);
    $open = 100 - $done;

    $headline = array(array(" ", $strtext, $straction, $strdate, $struser), array(20, 115, 20, 20, 20));
    $thelog = (object) new mylog();
    $datlog = array();
    $tlog = $thelog->getProjectLog($id, 25);
    $tlog = $thelog->formatdate($tlog, "d.m.y");
    if (!empty($tlog))
    {
        foreach($tlog as $logged)
        {
            if ($logged["type"] == "datei")
            {
                $logged["type"] = "file";
            } elseif ($logged["type"] == "projekt")
            {
                $logged["type"] = "project";
            } elseif ($logged["type"] == "track")
            {
                $logged["type"] = "timetracker";
            }

            $icon = utf8_decode($langfile[$logged["type"]]);
            if ($logged["action"] == 1)
            {
                $actstr = utf8_decode($langfile["added"]);
            } elseif ($logged["action"] == 2)
            {
                $actstr = utf8_decode($langfile["edited"]);
            } elseif ($logged["action"] == 3)
            {
                $actstr = utf8_decode($langfile["deleted"]);
            } elseif ($logged["action"] == 4)
            {
                $actstr = utf8_decode($langfile["opened"]);
            } elseif ($logged["action"] == 5)
            {
                $actstr = utf8_decode($langfile["closed"]);
            } elseif ($logged["action"] == 6)
            {
                $actstr = utf8_decode($langfile["assigned"]);
            }

            $obstr = $logged["name"];
            $obstr = utf8_decode($obstr);
            $obstr = substr($obstr, 0, 75);

            $data = array($icon, $obstr, $actstr, $logged["datum"], $logged["username"], $logged["action"]);
            array_push($datlog, $data);
        }
    }
    include("./include/class.fpdf.php");

    $pdf = (object) new pdfhtml();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(80);
    $pdf->Cell(30, 10, "Report for $theproject[name]", 0, 0, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 14);
    $started = $strstarted . ":";

    $pdf->writeHTML("<b>$started</b> $theproject[startstring]<br><b>$strdue:</b> $theproject[endstring]<br><br><br><b>$strdesc:</b><br>$theproject[desc]<br><br>");

    $pdf->SetFont('Arial', 'B', 18);
    $pdf->writeHTML("$activity<br>");
    $headstr = $theproject["name"] . " " . $activity;
    $headstr = utf8_decode($headstr);
    $pdf->FancyTable($headline, $datlog, 9, $headstr);
    $pdf->SetFont('Arial', '', 14);
    // Chart
    $data = array($stropen => $open, $strdone => $done);
    $pdf->SetXY(100, 20);
    $col1 = array(255, 0, 0);
    $col2 = array(33, 215, 3);
    $pdf->PieChart(95, 30, $data, '%l (%p)', array($col1, $col2));
    $pdf->SetXY(10, 50);

    $pdf->Output("project$id-report.pdf", "d");
} elseif ($action == "donegraph")
{
    include_once("./include/flash-chart.php");
    $g = (object) new graph();
    $g->bg_colour = "#FFFFFF";
    $done = $_GET['done'];
    $open = 100 - $done;
    $g->pie(90, '#505050', '{font-size: 12px; color: #404040;');
    $donestr = $langfile["done"];
    $openstr = $langfile["openprogress"];

    $thelink = "managetask.php?action=showproject&id=$id";
    $thelink = urlencode($thelink);

    $thelinks = array($thelink, $thelink);

    if ($done < $open)
    {
        $data = array($open, $done);
        $g->pie_values($data, array($openstr, $donestr), $thelinks);
        $g->pie_slice_colours(array('#FF0000', '#21D703'));
    }
    else
    {
        $data = array($done, $open);
        $g->pie_values($data, array($donestr, $openstr), $thelinks);
        $g->pie_slice_colours(array('#21D703', '#FF0000'));
    }

    $g->set_tool_tip('#val#%');

    echo $g->render();
} elseif ($action == "showproject")
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

    if (getArrayVal($_COOKIE, "milehead"))
    {
        $milestyle = "display:" . $_COOKIE['milehead'];
        $template->assign("milestyle", $milestyle);
        $milebar = "win_" . $_COOKIE['milehead'];
    }
    else
    {
        $milebar = "win_block";
    }
    if (getArrayVal($_COOKIE, "trackerhead"))
    {
        $trackstyle = "display:" . $_COOKIE['trackerhead'];
        $template->assign("trackstyle", $trackstyle);
        $trackbar = "win_" . $_COOKIE['trackerhead'];
    }
    else
    {
        $trackbar = "win_block";
    }
    if (getArrayVal($_COOKIE, "loghead"))
    {
        $logstyle = "display:" . $_COOKIE['loghead'];
        $template->assign("logstyle", $logstyle);
        $logbar = "win_" . $_COOKIE['loghead'];
    }
    else
    {
        $logbar = "win_block";
    }
    if (getArrayVal($_COOKIE, "status"))
    {
        $statstyle = "display:" . $_COOKIE['status'];
        $template->assign("statstyle", $statstyle);
        $statbar = "win_" . $_COOKIE['status'];
    }
    else
    {
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
    if (strlen($thecloud) > 0)
    {
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
} elseif ($action == "cal")
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

    $thisd = date("j");
    $thism = date("n");
    $thisy = date("Y");

    $m = getArrayVal($_GET, "m");
    $y = getArrayVal($_GET, "y");
    if (!$m)
    {
        $m = $thism;
    }
    if (!$y)
    {
        $y = $thisy;
    }

    $nm = $m + 1;
    $pm = $m -1;
    if ($nm > 12)
    {
        $nm = 1;
        $ny = $y + 1;
    }
    else
    {
        $ny = $y;
    }
    if ($pm < 1)
    {
        $pm = 12;
        $py = $y-1;
    }
    else
    {
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
