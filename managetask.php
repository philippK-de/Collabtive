<?php
require("./init.php");

$action = getArrayVal($_GET, "action");

if (!isset($_SESSION["userid"])) {

    if ($action == "ical" || $action == "icalshort"){
      // spawn basic auth request here
      // most probably this is not the best location for this basic auth code. feel free to move it to whereever it should be.
      // in the ideal case, this kind of basic auth should also be available for the RSS feed!
      if (!isset($_SERVER['PHP_AUTH_USER'])) {
	$msg="Collabtive";
	if ($action == "ical") {
	  $msg .=". Also try action=icalshort for alternative display.";
	}
        header('WWW-Authenticate: Basic realm="'.$msg.'"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Error 401: Not authorized!';
      } else {
	// try login with given credentials
	$user = (object) new user();
	if ($user->login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
          $loc = $url . "managetask.php?action=" . $action;
          header("Location: $loc");
        } else {
          header('HTTP/1.0 401 Unauthorized');
          echo 'Error 401: Not authorized!';
        }
      }
      exit;
    } else {
      $template->assign("loginerror", 0);
      $template->display("login.tpl");
      die();
    }
}

$task = (object) new task();

$tasklist = getArrayVal($_GET, "tasklist");
$mode = getArrayVal($_GET, "mode");
$tid = getArrayVal($_GET, "tid");
$start = getArrayVal($_POST, "start");
$end = getArrayVal($_POST, "end");
$project = getArrayVal($_POST, "project");
$assigned = getArrayVal($_POST, "assigned");
$tasklist = getArrayVal($_POST, "tasklist");
$text = getArrayVal($_POST, "text");
$title = getArrayVal($_POST, "title");
$redir = getArrayVal($_GET, "redir");
$id = getArrayVal($_GET, "id");

$project = array();
$project['ID'] = $id;
$template->assign("project", $project);
// define the active tab in the project navigation
$classes = array("overview" => "overview", "msgs" => "msgs", "tasks" => "tasks_active", "miles" => "miles", "files" => "files", "users" => "users", "tracker" => "tracking");
$template->assign("classes", $classes);

$template->assign("mode", $mode);

if ($action == "addform") {
    // check if user has appropriate permissions
    if (!$userpermissions["tasks"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $day = getArrayVal($_GET, "theday");
    $month = getArrayVal($_GET, "themonth");
    $year = getArrayVal($_GET, "theyear");

    $project = new project();
    $tlist = new tasklist();

    $lists = $lists = $tlist->getProjectTasklists($id, 1);
    $project_members = $project->getProjectMembers($id);

    $template->assign("year", $year);
    $template->assign("month", $month);
    $template->assign("day", $day);
    $template->assign("assignable_users", $project_members);
    $template->assign("tasklists", $lists);
    $template->assign("tasklist_id", $tasklist);
    $template->display("addtaskform.tpl");
} elseif ($action == "add") {
    // check if user has appropriate permissions
    if (!$userpermissions["tasks"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    // check dates' consistency
    if ($end < $start) {
		$goback = $langfile["goback"];
		$endafterstart = $langfile["endafterstart"];
		$template->assign("mode", "error");
		$template->assign("errortext", "$endafterstart<br>$goback");
		$template->display("error.tpl");
	} else {
		// add the task
		$tid = $task->add($start, $end, $title, $text, $tasklist, $id);
		if ($tid) {
			// Loop through the selected users from the form and assign them to the task
			foreach($assigned as $member) {
				$task->assign($tid, $member);
			}
			// if tasks was added and mailnotify is activated, send an email
			if ($settings["mailnotify"]) {
				$projobj = new project();
				$theproject = $projobj->getProject($project["ID"]);
				// Check project status
				if ($theproject["status"] != 2)
				{
					foreach($assigned as $member) {
						$usr = (object) new user();
						$user = $usr->getProfile($member);
						if (!empty($user["email"]) && $userid != $user["ID"]) {
							// send email
							$userlang = readLangfile($user['locale']);

							$subject = $userlang["taskassignedsubject"] . ' (' . $userlang['by'] . ' ' . $username . ')';

							$mailcontent = $userlang["hello"] . ",<br /><br/>" .
										$userlang["taskassignedtext"] .
										"<h3><a href = \"" . $url . "managetask.php?action=showtask&id=$id&tid=$tid\">$title</a></h3>".
										$text;

							$themail = new emailer($settings);

							$themail->send_mail($user["email"], $subject , $mailcontent);
						}
					}
				}
			}
			$loc = $url . "managetask.php?action=showproject&id=$id&mode=added";
			header("Location: $loc");
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

    $thistask = $task->getTask($tid);
    $project = new project();
    // Get all the members of the current project
    $members = $project->getProjectMembers($id, $project->countMembers($id));
    // Get the project tasklists and the tasklist the task belongs to
    $tasklist = new tasklist();
    $tasklists = $tasklist->getProjectTasklists($id);
    $tl = $tasklist->getTasklist($thistask['liste']);
    $thistask['listid'] = $tl['ID'];
    $thistask['listname'] = $tl['name'];

    $user = $task->getUser($thistask['ID']);

    $thistask['username'] = $user[1];
    $thistask['userid'] = $user[0];

    $tmp = $task->getUsers($thistask['ID']);

    if ($tmp) {
        foreach ($tmp as $value) {
            $thistask['users'][] = $value[0];
        }
    }
    $title = $langfile["edittask"];

    $template->assign("members", $members);
    $template->assign("title", $title);
    $template->assign("tasklists", $tasklists);
    $template->assign("tl", $tl);
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
    if ($end < $start) {
		$goback = $langfile["goback"];
		$endafterstart = $langfile["endafterstart"];
		$template->assign("mode", "error");
		$template->assign("errortext", "$endafterstart<br>$goback");
		$template->display("error.tpl");
	} else {
		// edit the task
		if ($task->edit($tid, $start, $end, $title, $text, $tasklist)) {
			$redir = urldecode($redir);
			if (!empty($assigned)) {
				foreach($assigned as $assignee) {
					$assignChk = $task->assign($tid, $assignee);
					if ($assignChk) {
						if ($settings["mailnotify"]) {
							$usr = (object) new user();
							$user = $usr->getProfile($assignee);

							if (!empty($user["email"]) && $userid != $user["ID"]) {
								$userlang = readLangfile($user['locale']);

								$subject = $userlang["taskassignedsubject"] . ' (' . $userlang['by'] . ' ' . $username . ')';

								$mailcontent = $userlang["hello"] . ",<br /><br/>" .
											$userlang["taskassignedtext"] .
											"<h3><a href = \"" . $url . "managetask.php?action=showtask&id=$id&tid=$tid\">$title</a></h3>".
											$text;

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
    if ($task->del($tid)) {
        // $redir = urldecode($redir);
        if ($redir) {
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

    if ($task->open($tid)) {
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
    if ($task->close($tid)) {
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
    if ($task->assign($id, $user)) {
        if ($settings["mailnotify"]) {
            $usr = (object) new user();
            $user = $usr->getProfile($user);

            if (!empty($user["email"])) {
                // send email
                $userlang = readLangfile($user['locale']);

                $subject = $userlang["taskassignedsubject"] . ' (' . $userlang['by'] .' '. $username . ')';
                $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                               $userlang["taskassignedtext"] .
                               "<h3><a href = \"" . $url . "managetask.php?action=showtask&id=$id&tid=$tid\">$title</a></h3>".
                               $text;

                $themail = new emailer($settings);
                $themail->send_mail($user["email"], $subject , $mailcontent);
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
    $tasklist = new tasklist();
    // Get open and closed tasklists
    $lists = $tasklist->getProjectTasklists($id);
    $oldlists = $tasklist->getProjectTasklists($id, 0);
    // Get number of assigned users
    $myproject = new project();
    $project_members = $myproject->getProjectMembers($id, $myproject->countMembers($id));
    // Get all the milestones in the project
    $milestone = new milestone();
    $milestones = $milestone->getAllProjectMilestones($id);

    $pro = $myproject->getProject($id);
    $projectname = $pro["name"];
    $title = $langfile['tasks'];

    $template->assign("title", $title);
    $template->assign("milestones", $milestones);
    $template->assign("projectname", $projectname);
    $template->assign("assignable_users", $project_members);

    $template->assign("lists", $lists);
    $template->assign("oldlists", $oldlists);
    $template->display("projecttasks.tpl");
} elseif ($action == "showtask") {
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
    $myproject = new project();
    $pro = $myproject->getProject($id);
    $projectname = $pro["name"];

    $title = $langfile['task'];

    $mytask = new task();
    $task = $mytask->getTask($tid);

    $members = $myproject->getProjectMembers($id, $myproject->countMembers($id));
    $tasklist = new tasklist();
    $tasklists = $tasklist->getProjectTasklists($id);
    $tl = $tasklist->getTasklist($task['liste']);
    $task['listid'] = $tl['ID'];
    $task['listname'] = $tl['name'];

    $tmp = $mytask->getUsers($task['ID']);
    if ($tmp) {
        foreach ($tmp as $value) {
            $task['users'][] = $value[0];
        }
    }

    $user = $mytask->getUser($task['ID']);
    $task['username'] = $user[1];
    $task['userid'] = $user[0];

    $template->assign("members", $members);
    $template->assign("tasklists", $tasklists);
    $template->assign("tl", $tl);
    $template->assign("pid", $id);

    $template->assign("projectname", $projectname);
    $template->assign("title", $title);
    $template->assign("task", $task);
    $template->display("task.tpl");
} elseif ($action == "ical") {
    $mytask = new task();
    $task = $mytask->getIcal($userid);
} elseif ($action == "icalshort") {
    $mytask = new task();
    $task = $mytask->getIcal($userid,false);
}
