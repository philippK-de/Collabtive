<?php
include("init.php");
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}

$action = getArrayVal($_GET, "action");


$project = new project();
if (!$action) {

	$task = new task();
    $myprojects = $project->getMyProjects($userid);
    $milestone = new milestone();
    $milestones = array();
    $cou = 0;
    $tasknum = 0;
    $ltasksa = array();
    $tlist = new tasklist();
    $alltasks = array();
    if (!empty($myprojects)) {
        foreach($myprojects as $proj) {
            $tasks = $task->getAllMyProjectTasks($proj["ID"]);
            $lists = $tlist->getProjectTasklists($proj["ID"], 1);
            $donetasks = $task->getMyDoneProjectTasks($proj["ID"], 10000);

            $myprojects[$cou]['tasks'] = $tasks;
            $myprojects[$cou]['oldtasks'] = $donetasks;
            $myprojects[$cou]['lists'] = $lists;

            if (!empty($tasks)) {
                $pcount = count($tasks);
            } else {
                $pcount = 0;
            }
            $myprojects[$cou]['tasknum'] = $pcount;
            if (!empty($tasks)) {
                $tcount = count($tasks);
                $tasknum = $tasknum + $tcount;
                array_push($alltasks, $tasks);
            }

            $cou = $cou + 1;
        }
    }
    $etasks = reduceArray($alltasks);

    $title = $langfile['mytasks'];
    $template->assign("title", $title);
    $template->assign("tasks", $etasks);
    $template->assign("tasknum", count($etasks));
    $template->assign("myprojects", $myprojects);
    $template->display("mytasks.tpl");
} elseif ($action == "pdf") {
    $pdf = new MYPDF("P", PDF_UNIT, "A4", true);
    $pdf->setup($langfile["mytasks"], array(229, 235, 235));

    $headers = array($langfile["title"], $langfile["project"], $langfile["daysleft"]);

    $mtask = new task();
    $msg = new message();

    $myprojects = $project->getMyProjects($userid);
    $messages = array();
    $milestones = array();
    $tasks = array();
    if (!empty($myprojects)) {
        foreach($myprojects as $proj) {
            $task = $mtask->getAllMyProjectTasks($proj["ID"]);
            if (!empty($task)) {
                array_push($tasks, $task);
            }
        }
    }

    $etasks = reduceArray($tasks);
    $fintasks = array();
    foreach($etasks as $etask) {
        array_push($fintasks, array($etask["title"], $etask["pname"], $etask["daysleft"]));
    }
    $pdf->table($headers, $fintasks);

    $pdf->Output("my-tasks-$username.pdf", "D");
}

?>