<?php
require("./init.php");

$action = getArrayVal($_GET, "action");

if (!isset($_SESSION["userid"])) {


    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}

$task = (object)new task();

//create XSS cleaned version of POST and GET
$cleanGet = cleanArray($_GET);
$cleanPost = cleanArray($_POST);

$tasklist = getArrayVal($_GET, "tasklist");
$tasklist = getArrayVal($_POST, "tasklist");
$mode = getArrayVal($_GET, "mode");

$redir = getArrayVal($_GET, "redir");
$id = getArrayVal($_GET, "id");

$cleanPost["project"] = array();
$cleanPost["project"]['ID'] = $id;
$template->assign("project", $cleanPost["project"]);
// define the active tab in the project navigation
$classes = array("overview" => "overview", "msgs" => "msgs", "tasks" => "tasks_active", "miles" => "miles", "files" => "files", "users" => "users", "tracker" => "tracking");
$template->assign("classes", $classes);

$template->assign("mode", $mode);

/*
 * VIEW ROUTES
 * These are routes that render HTML views to the browser or create side effects
 */
if ($action == "add") {
    // check if user has appropriate permissions
    if (!$userpermissions["tasks"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    // check dates' consistency
    if (strtotime($cleanPost["end"]) < strtotime($cleanPost["start"])) {
        $goback = $langfile["goback"];
        $endafterstart = $langfile["endafterstart"];
        $template->assign("mode", "error");
        $template->assign("errortext", "$endafterstart<br>$goback");
        $template->display("error.tpl");
    } else {
        // add the task
        $taskId = $task->add($cleanPost["start"], $cleanPost["end"], $cleanPost["title"], $cleanPost["text"], $tasklist, $id);
        if ($taskId) {
            // Loop through the selected users from the form and assign them to the task
            foreach ($cleanPost["assigned"] as $member) {
                $task->assign($taskId, $member);
            }
            // if tasks was added and mailnotify is activated, send an email
            if ($settings["mailnotify"]) {
                $projobj = new project();
                $theproject = $projobj->getProject($cleanPost["project"]["ID"]);
                // Check project status
                if ($theproject["status"] != 2) {
                    foreach ($cleanPost["assigned"] as $member) {
                        $usr = (object)new user();
                        $user = $usr->getProfile($member);
                        if (!empty($user["email"]) && $userid != $user["ID"]) {
                            // send email
                            $userlang = readLangfile($user['locale']);

                            $subject = $userlang["taskassignedsubject"] . ' (' . $userlang['by'] . ' ' . $username . ')';

                            $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                                $userlang["taskassignedtext"] .
                                "<h3><a href = \"" . $url . "managetask.php?action=showtask&id=$id&tid=$taskId\">" . $cleanPost["title"] ."</a></h3>" .
                                $cleanPost["text"];

                            $themail = new emailer($settings);

                            $themail->send_mail($user["email"], $subject, $mailcontent);
                        }
                    }
                }
            }


            if(isset($cleanGet["redir"]) && $cleanGet["redir"] == "yes")
            {
                $loc = $url . "managetask.php?action=showproject&id=$id&mode=added";
                header("Location: $loc");
            }
            else{
                echo "ok";
            }
        } else {
            $template->assign("addtask", 0);
        }
    }
} elseif ($action == "editform") {
    // check if user has appropriate permissions
    if (!$userpermissions["tasks"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $thistask = $task->getTask($cleanGet["tid"]);
    $projectObj = new project();

    // Get all the members of the current project
    $members = $projectObj->getProjectMembers($id, $projectObj->countMembers($id));

    // Get the project tasklists and the tasklist the task belongs to
    $tasklistObj = new tasklist();
    $tasklists = $tasklistObj->getProjectTasklists($id);

    $tasklist = $tasklistObj->getTasklist($thistask['liste']);
    $thistask['listid'] = $tasklist['ID'];
    $thistask['listname'] = $tasklist['name'];

    //get the users assigned to this task
    $user = $task->getUser($thistask['ID']);

    $thistask['username'] = $user[1];
    $thistask['userid'] = $user[0];


    $assignedUsers = $task->getUsers($thistask['ID']);

    if ($assignedUsers) {
        foreach ($assignedUsers as $assignedUser) {
            $thistask['users'][] = $assignedUser[0];
        }
    }

    $cleanPost["title"] = $langfile["edittask"];

    $template->assign("members", $members);
    $template->assign("title", $cleanPost["title"]);
    $template->assign("tasklists", $tasklists);
    $template->assign("tl", $tasklist);
    $template->assign("task", $thistask);
    $template->assign("pid", $id);
    $template->assign("showhtml", "no");
    $template->assign("showheader", "no");
    $template->assign("async", "yes");
    $template->display("edittask.tpl");
} elseif ($action == "edit") {
    // check if user has appropriate permissions
    if (!$userpermissions["tasks"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    // check dates' consistency
    if (strtotime($cleanPost["end"]) < strtotime($cleanPost["start"])) {
        $goback = $langfile["goback"];
        $endafterstart = $langfile["endafterstart"];
        $template->assign("mode", "error");
        $template->assign("errortext", "$endafterstart<br>$goback");
        $template->display("error.tpl");
    } else {
        // edit the task
        if ($task->edit($cleanGet["tid"], $cleanPost["start"], $cleanPost["end"], $cleanPost["title"], $cleanPost["text"], $tasklist)) {
            $redir = urldecode($redir);
            if (!empty($cleanPost["assigned"])) {
                //loop through the users to be assigned
                foreach ($cleanPost["assigned"] as $assignee) {
                    //assign the user
                    $assignChk = $task->assign($cleanGet["tid"], $assignee);
                    //if user was assigned and mailnotify is on - send an email
                    if ($assignChk) {
                        if ($settings["mailnotify"]) {
                            //get the user profile for the user to receive the mail
                            $userObj = (object)new user();
                            $user = $userObj->getProfile($assignee);

                            if (!empty($user["email"]) && $userid != $user["ID"]) {
                                //read the langfile for the locale of the user
                                $userlang = readLangfile($user['locale']);

                                //compose the subject
                                $subject = $userlang["taskassignedsubject"] . ' (' . $userlang['by'] . ' ' . $username . ')';

                                //compose the body
                                $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                                    $userlang["taskassignedtext"] .
                                    "<h3><a href = \"" . $url . "managetask.php?action=showtask&id=$id&tid=" . $cleanGet["tid"] ."\">" . $cleanPost["title"] ."</a></h3>" .
                                    $cleanPost["text"];

                                // send email
                                $themail = new emailer($settings);
                                $themail->send_mail($user["email"], $subject, $mailcontent);
                            }
                        }
                    }
                }
            }
            if ($redir) {
                $redir = $url . $redir;
                header("Location: $redir");
            } else {
                $loc = $url . "managetask.php?action=showproject&id=$id&mode=edited";
                header("Location: $loc");
            }
        } else {
            $loc = $url . "managetask.php?action=showproject&id=$id&mode=error";
            header("Location: $loc");
        }
    }
} elseif ($action == "del") {
    // check if user has appropriate permissions
    if (!$userpermissions["tasks"]["del"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if ($task->del($cleanGet["tid"])) {
        // $redir = urldecode($redir);
        if (isset($redir) && $redir) {
            $redir = $url . $redir;
            header("Location: $redir");
        } else {
            echo "ok";
        }
    } else {
        $template->assign("deltask", 0);
    }
} elseif ($action == "open") {
    // check if user has appropriate permissions
    if (!$userpermissions["tasks"]["close"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    if ($task->open($cleanGet["tid"])) {
        // Redir is the url where the user should be redirected, supplied with the initial request
        $redir = urldecode($redir);
        if ($redir) {
            $redir = $url . $redir;
            header("Location: $redir");
        } else {
            echo "ok";
        }
    } else {
        $template->assign("opentask", 0);
    }
} elseif ($action == "close") {
    // check if user has appropriate permissions
    if (!$userpermissions["tasks"]["close"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    //close the task
    //if a redir URL was given, send a header. else its an asyncronous request so return ok
    if ($task->close($cleanGet["tid"])) {
        $redir = urldecode($redir);
        if ($redir) {
            $redir = $url . $redir;
            header("Location: $redir");
        } else {
            echo "ok";
        }
    } else {
        $template->assign("closetask", 0);
    }
} elseif ($action == "assign") {
    //assign the user
    if ($task->assign($id, $user)) {
        //if mailnotify is on - send it
        if ($settings["mailnotify"]) {
            $userObj = (object)new user();
            $user = $userObj->getProfile($user);

            if (!empty($user["email"])) {
                // send email
                $userlang = readLangfile($user['locale']);

                $subject = $userlang["taskassignedsubject"] . ' (' . $userlang['by'] . ' ' . $username . ')';
                $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                    $userlang["taskassignedtext"] .
                    "<h3><a href = \"" . $url . "managetask.php?action=showtask&id=$id&tid=" .$cleanGet["tid"] . "\">" . $cleanPost["title"] . "</a></h3>" .
                    $cleanPost["text"];

                $themail = new emailer($settings);
                $themail->send_mail($user["email"], $subject, $mailcontent);
            }
        }
        $template->assign("assigntask", 1);
        $template->display("mytasks.tpl");
    } else {
        $template->assign("assigntask", 0);
    }
} elseif ($action == "deassign") {
    if ($task->deassign($id, $user)) {
        $template->assign("deassigntask", 1);
        $template->display("mytasks.tpl");
    } else {
        $template->assign("deassigntask", 0);
    }
} elseif ($action == "showproject") {
    if (!$userpermissions["tasks"]["view"]) {
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
    $tasklistObj = new tasklist();
    $projectObj = new project();
    $milestoneObj = new milestone();

    // Get open and closed tasklists
    $lists = $tasklistObj->getProjectTasklists($id);
    $oldlists = $tasklistObj->getProjectTasklists($id, 0);

    // Get number of assignable users
    $project_members = $projectObj->getProjectMembers($id, $projectObj->countMembers($id));
    // Get all the milestones in the project
    $milestones = $milestoneObj->getAllProjectMilestones($id);
    //get the current project
    $project = $projectObj->getProject($id);
    $projectname = $project["name"];

    $template->assign("lists", $lists);
    $template->assign("oldlists", $oldlists);
    $template->assign("title", $langfile["tasks"]);
    $template->assign("milestones", $milestones);
    $template->assign("projectname", $projectname);
    $template->assign("assignable_users", $project_members);


   $template->display("projecttasks.tpl");
}
elseif ($action == "showtask") {
    if (!$userpermissions["tasks"]["view"]) {
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
    $projectObj = new project();
    $project = $projectObj->getProject($id);
    $projectname = $project["name"];


    $taskObj = new task();
    //get the task details
    $task = $taskObj->getTask($cleanGet["tid"]);
    //get the users assigned to the task
    $members = $projectObj->getProjectMembers($id, $projectObj->countMembers($id));

    //get the tasklists for the project, and the tasklist assigned to this task
    $tasklistObj = new tasklist();
    $tasklists = $tasklistObj->getProjectTasklists($id);

    $tasklist = $tasklistObj->getTasklist($task["liste"]);
    $task["listid"] = $tasklist["ID"];
    $task["listname"] = $tasklist["name"];

    $assignedUsers = $taskObj->getUsers($task["ID"]);
    if ($assignedUsers) {
        foreach ($assignedUsers as $assignedUser) {
            $task["users"][] = $assignedUser[0];
        }
    }

    $user = $taskObj->getUser($task["ID"]);
    $task["username"] = $user[1];
    $task["userid"] = $user[0];

    $template->assign("members", $members);
    $template->assign("tasklists", $tasklists);
    $template->assign("tl", $tasklist);
    $template->assign("pid", $id);

    $template->assign("projectname", $projectname);
    $template->assign("title", $langfile["task"]);
    $template->assign("task", $task);
    $template->display("task.tpl");
}

/*
 * DATA ROUTES
 * These are routes that render JSON data structures
 */
elseif($action == "projectTasks")
{
    if (!$userpermissions["tasks"]["view"]) {
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
    $tasklistObj = new tasklist();
    // Get open and closed tasks from list
    $openTasks = $tasklistObj->getTasksFromList($cleanGet["tlid"]);
    $closedTasks = $tasklistObj->getTasksFromList($cleanGet["tlid"],0);

    //assemble array
    $tasks["open"] = $openTasks;
    $tasks["closed"] = $closedTasks;

    //assemble data structure for view
    $openLists["items"] = $tasks;
    $openLists["count"] = count($openTasks);

    echo json_encode($openLists);
}
