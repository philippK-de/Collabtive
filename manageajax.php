<?php
require("init.php");
if (!isset($_SESSION["userid"]))
{
    $template->assign("loginerror", 0);
    $mode = getArrayVal($_GET, "mode");
    $template->assign("mode", $mode);
    $template->display("login.tpl");
    die();
}

$milestone = new milestone();

$action = getArrayVal($_GET, "action");
$mid = getArrayVal($_GET, "mid");

$cleanGet = cleanArray($_GET);

$mode = getArrayVal($_GET, "mode");
$template->assign("mode", $mode);

$id = getArrayVal($_GET, "id");
$start = getArrayVal($_GET, "start");
$end = getArrayVal($_GET, "end");
$project = array('ID' => $id);
$template->assign("project", $project);

$pro = new project();
if (!$id) {
    $id = 0;
}
$template->assign("id", $id);
//This is used to put file lists into tinymce for selection
if ($action == "jsonfiles") {
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $myfile = new datei();
    $ordner = $myfile->getAllProjectFiles($id);
    if (!empty($ordner)) {
        $json = "var tinyMCEImageList = new Array(\n";
        foreach($ordner as $file) {
            if ($file["imgfile"] == 1) {
                $json .= "[\"$file[datei]\", \"managefile.php?action=downloadfile&id=$file[project]&file=$file[ID]\"],\n";
            }
        }
        $json = substr($json, 0, strlen($json)-2);
        $json .= ");";
    } else {
        $json = "";
    }
    echo $json;
}
//this is used to display the calendar on the desktop
if($action == "indexCalendar")
{
    $currentDay = date("j");
    $currentMonth = date("n");
    $currentYear = date("Y");

    $selectedMonth = getArrayVal($_GET, "m");
    $selectedYear = getArrayVal($_GET, "y");
    if (!$selectedMonth) {
        $selectedMonth = $currentMonth;
    }
    if (!$selectedYear) {
        $selectedYear = $currentYear;
    }

    $nextMonth = $selectedMonth + 1;
    $previousMonth = $selectedMonth -1;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear = $selectedYear + 1;
    } else {
        $nextYear = $selectedYear;
    }
    if ($previousMonth < 1) {
        $previousMonth = 12;
        $previousYear = $selectedYear-1;
    } else {
        $previousYear = $selectedYear;
    }

    $today = date("d");

    $calobj = new calendar();
    $cal = $calobj->getCal($selectedMonth, $selectedYear);
    $weeks = $cal->calendar;
    // print_r($weeks);

    $monthName = strtolower(date('F', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)));
    $monthName = $langfile[$monthName];

    $calendar["weeks"] = $weeks;
    $calendar["monthName"] = $monthName;


    $calendar["selectedYear"] = $selectedYear;
    $calendar["currentYear"] = $currentYear;
    $calendar["nextYear"] = $nextYear;
    $calendar["previousYear"] = $previousYear;

    $calendar["selectedMonth"] = $selectedMonth;
    $calendar["currentMonth"] = $currentMonth;
    $calendar["nextMonth"] = $nextMonth;
    $calendar["previousMonth"] = $previousMonth;

    $calendar["currentDay"] = $currentDay;

    $indexCalendar["items"] = $calendar;
    $indexCalendar["count"] = count($weeks);

    echo json_encode($indexCalendar);


}
elseif ($action == "projectCalendar") {
    if (!chkproject($userid, $cleanGet["id"])) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }

    $currentDay = date("j");
    $currentMonth = date("n");
    $currentYear = date("Y");

    $selectedMonth = getArrayVal($_GET, "m");
    $selectedYear = getArrayVal($_GET, "y");
    if (!$selectedMonth) {
        $selectedMonth = $currentMonth;
    }
    if (!$selectedYear) {
        $selectedYear = $currentYear;
    }

    $nextMonth = $selectedMonth + 1;
    $previousMonth = $selectedMonth -1;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear = $selectedYear + 1;
    } else {
        $nextYear = $selectedYear;
    }
    if ($previousMonth < 1) {
        $previousMonth = 12;
        $previousYear = $selectedYear-1;
    } else {
        $previousYear = $selectedYear;
    }

    $today = date("d");

    $calobj = new calendar();
    $cal = $calobj->getCal($selectedMonth, $selectedYear, $cleanGet["id"]);
    $weeks = $cal->calendar;
    // print_r($weeks);

    $monthName = strtolower(date('F', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)));
    $monthName = $langfile[$monthName];

    $calendar["weeks"] = $weeks;
    $calendar["monthName"] = $monthName;


    $calendar["selectedYear"] = $selectedYear;
    $calendar["currentYear"] = $currentYear;
    $calendar["nextYear"] = $nextYear;
    $calendar["previousYear"] = $previousYear;

    $calendar["selectedMonth"] = $selectedMonth;
    $calendar["currentMonth"] = $currentMonth;
    $calendar["nextMonth"] = $nextMonth;
    $calendar["previousMonth"] = $previousMonth;

    $calendar["currentDay"] = $currentDay;

    $indexCalendar["items"] = $calendar;
    $indexCalendar["count"] = count($weeks);

    echo json_encode($indexCalendar);

}
elseif($action == "chkconn")
{
	$dbHost = getArrayVal($_GET,"dbhost");
	$dbUser = getArrayVal($_GET,"dbuser");
	$dbName = getArrayVal($_GET,"dbname");
	$dbPass = getArrayVal($_GET,"dbpass");
	$chk = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
	 echo $chk;
}

