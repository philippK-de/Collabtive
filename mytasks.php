<?php
include("init.php");
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
$task = new task();

$project = new project();
$myprojects = $project->getMyProjects($userid);
$milestone = new milestone();
$milestones = array();
$cou = 0;
$tasknum = 0;
$ltasksa = array();
$tlist = new tasklist();
if (!empty($myprojects)) {
    foreach($myprojects as $proj) {
        $tasks = $task->getAllMyProjectTasks($proj["ID"], 10000);
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
        }
        $cou = $cou + 1;
    }
}

$title = $langfile['mytasks'];
$template->assign("title", $title);
$template->assign("tasknum", $tasknum);
$template->assign("myprojects", $myprojects);
$template->display("mytasks.tpl");

?>