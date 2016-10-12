<?php

/**
 * This class provides methods to realize tasks
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @author Eva Kiszka <eva@o-dyn.de>
 * @name task
 * @package Collabtive
 * @version 2.0
 * @link http://collabtive.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class task
{
    /**
     * Add a task
     *
     * @param string $start Start date of the task
     * @param string $end Date the task is due
     * @param string $title Title of the task (optional)
     * @param string $text Description of the task
     * @param int $list Tasklist the task is associated with
     * @param int $assigned ID of the user who has to complete the task
     * @param int $project ID of the project the task is associated with
     * @return int $insid New task's ID
     */
    function add($start, $end, $title, $text, $list, $project)
    {
        global $conn, $mylog;
        $list = (int)$list;
        $project = (int)$project;
        // convert strings to timestamps
        $startTime = strtotime($start);
        $endTime = strtotime($end);
        // if strtotime doesnt return something, set the final value to the value given in the function call
        if (empty($startTime)) {
            $startTime = $start;
        }
        if (empty($endTime)) {
            $endTime = $end;
        }
        // write to db
        $insStmt = $conn->prepare("INSERT INTO tasks (start, end, title, text, liste, status, project) VALUES (?, ?, ?, ?, ?, 1, ?)");
        $ins = $insStmt->execute(array($startTime, $endTime, $title, $text, $list, $project));

        if ($ins) {
            $insid = $conn->lastInsertId();
            // logentry
            $projectProperties = $this->getProjectProperties($insid);
            $mylog->add($projectProperties[0], 'task', 1, $projectProperties[1]);
            return $insid;
        } else {
            return false;
        }
    }

    /**
     * Edit a task
     *
     * @param int $id Task ID
     * @param string $start Start date
     * @param string $end Due date
     * @param string $title Title of the task
     * @param string $text Task description
     * @param int $list Tasklist
     * @param int $assigned ID of the user who has to complete the task
     * @return bool
     */
    function edit($id, $start, $end, $title, $text, $list)
    {
        global $conn, $mylog;
        $id = (int)$id;
        $list = (int)$list;
        // convert time string to timestamp
        $start = strtotime($start);
        $end = strtotime($end);

        $updStmt = $conn->prepare("UPDATE tasks SET `start`=?, `end`=?, `title`=?, `text`=?, `liste`=? WHERE ID = ?");
        $upd = $updStmt->execute(array($start, $end, $title, $text, $list, $id));

        if ($upd) {
            // Remove all the users from the task. Done to ensure no double assigns occur since the handler scripts call this::assign() on their own.
            $delAssignStmt = $conn->prepare("DELETE FROM tasks_assigned WHERE `task` = ?");
            $delAssignStmt->execute(array($id));

            $projectProperties = $this->getProjectProperties($id);
            $mylog->add($projectProperties[0], 'task', 2, $projectProperties[1]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a task
     *
     * @param int $id Task ID
     * @return bool
     */
    function del($id)
    {
        global $conn, $mylog;
        $id = (int)$id;
        // get task text and project
        // we need to do this before deleting the task here
        $projectProperties = $this->getProjectProperties($id);

        $delStmt = $conn->prepare("DELETE FROM tasks WHERE ID = ?");
        $del = $delStmt->execute(array($id));

        if ($del) {
            $delAssignStmt = $conn->prepare("DELETE FROM tasks_assigned WHERE task = ?");
            $delAssign = $delAssignStmt->execute(array($id));
            // Add a log entry with the task text
            $mylog->add($projectProperties[0], 'task', 3, $projectProperties[1]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Reactivate / open a task
     *
     * @param int $id Task ID
     * @return bool
     */
    function open($id)
    {
        global $conn, $mylog;
        $id = (int)$id;

        $updStmt = $conn->prepare("UPDATE tasks SET status = 1 WHERE ID = ?");
        $upd = $updStmt->execute(array($id));

        if ($upd) {
            // get task text and project
            $projectProperties = $this->getProjectProperties($id);
            // Add a log entry with the task text
            $mylog->add($projectProperties[0], 'task', 4, $projectProperties[1]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Close a task
     *
     * @param int $id Task ID
     * @return bool
     */
    function close($id)
    {
        global $conn, $mylog;
        $id = (int)$id;

        $updStmt = $conn->prepare("UPDATE tasks SET status = 0 WHERE ID = ?");
        $upd = $updStmt->execute(array($id));

        if ($upd) {
            // get task text and project
            $projectProperties = $this->getProjectProperties($id);
            // Add a log entry with the task text
            $mylog->add($projectProperties[0], 'task', 5, $projectProperties[1]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Assign a task to a user
     *
     * @param int $task Task ID
     * @param int $id User ID
     * @return bool
     */
    function assign($task, $id)
    {
        global $conn;
        $task = (int)$task;
        $id = (int)$id;

        $updStmt = $conn->prepare("INSERT INTO tasks_assigned (user,task) VALUES (?,?)");
        $upd = $updStmt->execute(array($id, $task));

        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete the assignment of a task to a user
     *
     * @param int $task Task ID
     * @param int $id User ID
     * @return bool
     */
    function deassign($task, $id)
    {
        global $conn;
        $task = (int)$task;
        $id = (int)$id;

        $updStmt = $conn->prepare("DELETE FROM tasks_assigned WHERE user = ? AND task = ?");
        $upd = $updStmt->execute(array($id, $task));

        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return a task
     *
     * @param int $id Task ID
     * @return array $task Task details
     */
    function getTask($id)
    {
        global $conn;
        $id = (int)$id;
        // get the task
        $taskStmt = $conn->prepare("SELECT * FROM tasks WHERE ID = ?");
        $taskStmt->execute(array($id));
        $task = $taskStmt->fetch();

        if (!empty($task)) {
            // format datestring according to dateformat option
            if (is_numeric($task['start'])) {
                $startstring = date(CL_DATEFORMAT, $task["start"]);
            } else {
                $startstring = date(CL_DATEFORMAT, strtotime($task["start"]));
            }
            if (is_numeric($task['end'])) {
                $endstring = date(CL_DATEFORMAT, $task["end"]);
            } else {
                $endstring = date(CL_DATEFORMAT, strtotime($task["end"]));
            }

            // get list and projectname of the task
            $details = $this->getTaskDetails($task);
            $list = $details["list"];
            $projectName = $details["projectName"];

            // get remaining days until due date
            $daysLeft = $this->getDaysLeft($task['end']);

            // Get the user(s) assigned to the task from the db
            $assignedUserStmt = $conn->query("SELECT user FROM tasks_assigned WHERE task = $task[ID]");

            $users = array();
            // fetch the assigned user(s)
            while ($assignedUser = $assignedUserStmt->fetch()) {
                // push the assigned users to an array
                array_push($users, $assignedUser[0]);
                $task["user"] = "All";
                $task["user_id"] = $users;
            }
            $userObj = new user();

            if (count($users) == 1) {
                // If only one user is assigned, get his profile and add him to users, user_id fields
                $user = $userObj->getProfile($users[0]);
                //if there is only one user, the user field contains the string with his name
                $task["user"] = $user["name"];
                //users contains an array with only the single user
                $task["users"] = array($user);
                //user id contains the id of the user
                $task["user_id"] = $user["ID"];
            } elseif (count($users) > 1) {
                // if there is more than one user push them to the users field. no user or user_id field is present.
                $task["users"] = array();
                $task["user"] = "";
                $task["user_id"] = 0;
                //loop through the users and push each one to the user array
                foreach ($users as $assignedUser) {
                    $user = $userObj->getProfile($assignedUser);
                    $task["user"] .= $user["name"] . " ";
                    array_push($task["users"], $user);
                }
            }

            $task["startstring"] = $startstring;
            $task["endstring"] = $endstring;

            $task["pname"] = $projectName;
            $task["list"] = $list;
            $task["daysleft"] = $daysLeft;

            $task["islate"] = false;
            if ($task["daysleft"] < 1) {
                $task["islate"] = true;
            }

            $task["istoday"] = false;
            if ($task["daysleft"] == 0) {
                $task["istoday"] = true;
            }

            //$taskCommentObj = new taskComments();
            //$task["comments"] = $taskCommentObj->getCommentsByTask($id);

            return $task;
        } else {
            return false;
        }
    }

    /**
     * Return all open tasks of a project
     *
     * @param int $project Project ID
     * @param int $status Status of the tasks to be returned (default=1)
     * @return array $lists Tasks
     */
    function getProjectTasks($project, $status = 1)
    {
        global $conn;
        $project = (int)$project;
        $status = (int)$status;

        $projectTasks = array();
        // if a status is given, query with status - else get all the tasks in the project
        if ($status !== false) {
            $projectTasksStmt = $conn->prepare("SELECT ID FROM tasks WHERE project = ? AND status=?");
            $projectTasksStmt->execute(array($project, $status));
        } else {
            $projectTasksStmt = $conn->prepare("SELECT ID FROM tasks WHERE project = ?");
            $projectTasksStmt->execute(array($project));
        }
        while ($tasks = $projectTasksStmt->fetch()) {
            $task = $this->getTask($tasks["ID"]);
            array_push($projectTasks, $task);
        }

        if (!empty($projectTasks)) {
            return $projectTasks;
        } else {
            return false;
        }
    }

    /**
     * Return all active / open tasks of a given project and user
     *
     * @param int $project Project ID
     * @param int $limit Number of tasks to return
     * @return array $lists Tasks
     */
    function getMyProjectTasks($project, $limit = 10)
    {
        global $conn;
        $project = (int)$project;
        $limit = (int)$limit;
        // Get the id of the currently logged in user.
        $user = $_SESSION['userid'];
        $user = (int)$user;

        $projectTasks = array();
        $now = time();

        $projectTasksStmt = $conn->prepare("SELECT ID FROM tasks WHERE project = ? AND status=1 AND end > ? ORDER BY `end` ASC LIMIT $limit");
        $projectTasksStmt->execute(array($project, $now));

        while ($tasks = $projectTasksStmt->fetch()) {
            $isTaskAssigned = $conn->query("SELECT ID FROM tasks_assigned WHERE user = $user AND task = $tasks[ID]")->fetch();
            $isTaskAssigned = $isTaskAssigned[0];
            if ($isTaskAssigned) {
                $task = $this->getTask($tasks["ID"]);
                array_push($projectTasks, $task);
            }
        }

        if (!empty($projectTasks)) {
            return $projectTasks;
        } else {
            return false;
        }
    }

    /**
     * Return open tasks from a given project a user
     *
     * @param int $project Project ID
     * @param int $user User ID (0 means the user, to whom the session belongs)
     * @return array $lists Tasks
     */
    function getAllMyProjectTasks($project, $user = 0, $status = 1)
    {
        global $conn;
        $project = (int)$project;
        // If no user is given, use the currently logged in one.
        if ($user < 1) {
            $user = $_SESSION['userid'];
        }
        $user = (int)$user;
        $projectTasks = array();

        $projectTasksStmt = $conn->prepare("SELECT tasks.*,tasks_assigned.user FROM tasks,tasks_assigned WHERE tasks.ID = tasks_assigned.task AND tasks_assigned.user = ? AND tasks.project = ? AND status=? ORDER BY `end` ASC ");
        $projectTasksStmt->execute(array($user, $project, $status));

        while ($tasks = $projectTasksStmt->fetch()) {
            $task = $this->getTask($tasks["ID"]);
            array_push($projectTasks, $task);
        }

        if (!empty($projectTasks)) {
            return $projectTasks;
        } else {
            return false;
        }
    }
    /**
     * Return open tasks from a given project a user
     *
     * @param int $user User ID (0 means the user, to whom the session belongs)
     * @param int $status Status of the tasks to be returned (1 = open, 0 = closed)
     * @return array $lists Tasks
     */
    function getMyTasks($user, $status = 1)
    {
        global $conn;
        $status = (int)$status;
        $user = (int)$user;
        $myTasks = array();

        $projectTasksStmt = $conn->prepare("SELECT tasks.*,tasks_assigned.user FROM tasks,tasks_assigned WHERE tasks.ID = tasks_assigned.task AND tasks_assigned.user = ? AND status= ?  ORDER BY `project` ASC ");
        $projectTasksStmt->execute(array($user, $status));

        while ($tasks = $projectTasksStmt->fetch()) {
            $task = $this->getTask($tasks["ID"]);
            array_push($myTasks, $task);
        }

        if (!empty($myTasks)) {
            return $myTasks;
        } else {
            return false;
        }
    }

    /**
     * Return all tasks (from a project) due on the specified date
     *
     * @param int $month Month
     * @param int $year Year
     * @param int $day Day
     * @param int $project Project ID (Default: 0 = all projects)
     * @return array $timeline Tasks
     */
    function getTodayTasks($month, $year, $day, $project = 0)
    {
        global $conn;
        $month = (int)$month;
        $year = (int)$year;

        if ($month > 9) {
            $startdate = date($day . "." . $month . "." . $year);
        } else {
            $startdate = date($day . ".0" . $month . "." . $year);
        }
        $starttime = strtotime($startdate);

        $user = (int)$_SESSION["userid"];

        $todaysTasks = array();

        if ($project > 0) {
            $todayTasksStmt = $conn->prepare("SELECT * FROM tasks  WHERE status=1 AND project = ? AND end = '$starttime'");
            $todayTasksStmt->execute(array($project));
        } else {
            $todayTasksStmt = $conn->prepare("SELECT tasks.*,tasks_assigned.user,projekte.name AS pname FROM tasks,tasks_assigned,projekte WHERE tasks.ID = tasks_assigned.task AND tasks.project = projekte.ID AND tasks_assigned.user = ? AND tasks.status=1 AND tasks.end = '$starttime'");
            $todayTasksStmt->execute(array($user));
        }
        while ($task = $todayTasksStmt->fetch()) {
            $task["daysleft"] = $this->getDaysLeft($task["end"]);
            array_push($todaysTasks, $task);
        }

        if (!empty($todaysTasks)) {
            return $todaysTasks;
        } else {
            return array();
        }
    }

    /**
     * Return the owner of a given task
     *
     * @param int $id Task ID
     * @return array $user ID of the user who has to complete the task
     */
    function getUser($id)
    {
        global $conn;
        $id = (int)$id;

        $userStmt = $conn->prepare("SELECT user FROM tasks_assigned WHERE task = ?");
        $userStmt->execute(array($id));
        $user = $userStmt->fetch();

        if (!empty($user)) {
            $userName = $conn->query("SELECT name FROM user WHERE ID = $user[0]")->fetch();
            $userName = $userName[0];
            $user[1] = stripslashes($userName);

            return $user;
        } else {
            return false;
        }
    }

    /**
     * Return the owner of a given task
     *
     * @param int $id Task ID
     * @return array $user ID of the users who has to complete the task
     */
    function getUsers($id)
    {
        global $conn;
        $id = (int)$id;

        $sql = $conn->prepare("SELECT user FROM tasks_assigned WHERE task = ?");
        $sql->execute(array($id));

        $result = array();
        while ($user = $sql->fetch()) {
            $sel2 = $conn->query("SELECT name FROM user WHERE ID = $user[0]");
            $uname = $sel2->fetch();
            $uname = $uname[0];
            $user[1] = stripslashes($uname);

            $result[] = $user;
        }
        return $result;
    }

    /**
     * Return a task's project name and tasklist name
     *
     * @param array $task Task ID
     * @return array $details Name of associated project and tasklist
     */
    private function getTaskDetails(array $task)
    {
        global $conn;
        $projectNameStmt = $conn->prepare("SELECT name FROM projekte WHERE ID = ?");
        $projectNameStmt->execute(array($task["project"]));
        $projectName = $projectNameStmt->fetch();
        $projectName = stripslashes($projectName[0]);

        $listStmt = $conn->prepare("SELECT name FROM tasklist WHERE ID = ?");
        $listStmt->execute(array($task["liste"]));
        $list = $listStmt->fetch();
        $list = stripslashes($list[0]);

        if (isset($list) or isset($projectName)) {
            $details = array("list" => $list, "projectName" => $projectName);
        }

        if (!empty($details)) {
            return $details;
        } else {
            return false;
        }
    }

    /**
     * Return the number of days left until a task is due
     *
     * @param string $end Timestamp of the date the task is due
     * @return int $days Days left
     */
    private function getDaysLeft($end)
    {
        $tod = date("d.m.Y");
        $now = strtotime($tod);
        $diff = $end - $now;
        $days = floor($diff / 86400);
        return $days;
    }

    /**
     * Return the name of the associated project and text of a given task
     *
     * @param int $id Task ID
     * @return array $nameproject Name and project
     */
    private function getProjectProperties($id)
    {
        global $conn;
        $id = (int)$id;

        $namStmt = $conn->prepare("SELECT `text`,`liste`,`title` FROM `tasks` WHERE ID = ?");
        $namStmt->execute(array($id));

        $name = $namStmt->fetch();
        $list = $name[1];
        $projectStmt = $conn->prepare("SELECT `project` FROM `tasklist` WHERE ID = ?");
        $projectStmt->execute(array($list));

        $project = $projectStmt->fetch();
        $project = $project[0];

        $nameproject = array($name[2], $project);
        if (!empty($nameproject)) {
            return $nameproject;
        } else {
            return false;
        }
    }
}

?>
