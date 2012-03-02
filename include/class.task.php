<?php
/**
 * This class provides methods to realize tasks
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name task
 * @package Collabtive
 * @version 0.5.5
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 * @global $mylog
 */
class task
{
    private $mylog;
    private $plugins;

    /**
     * Constructor
     * Initializes the event log
     */
    function __construct()
    {
        $this->mylog = new mylog;

    }

    /**
     * Add a task
     *
     * @param string $end Date the task is due
     * @param string $title Title of the task (optional)
     * @param string $text Description of the task
     * @param int $liste Tasklist the task is associated with
     * @param int $assigned ID of the user who has to complete the task
     * @param int $project ID of the project the task is associated with
     * @return int $insid New task's ID
     */
    function add($end, $title, $text, $liste, $project)
    {
        $end = mysql_real_escape_string($end);
        $title = mysql_real_escape_string($title);
        $text = mysql_real_escape_string($text);
        $liste = (int) $liste;
        $project = (int) $project;

        $end_fin = strtotime($end);

        if (empty($end_fin))
        {
            $end_fin = $end;
        }

        $start = time();
        // write to db
        $ins = mysql_query("INSERT INTO tasks (start,end,title,text,liste,status,project) VALUES ('$start','$end_fin','$title','$text',$liste,1,$project)");
        if ($ins)
        {
            $insid = mysql_insert_id();
            // logentry
            $nameproject = $this->getNameProject($insid);
            $this->mylog->add($nameproject[0], 'task', 1, $nameproject[1]);
            return $insid;
        }
        else
        {
            return false;
        }
    }

    /**
     * Edit a task
     *
     * @param int $id Task ID
     * @param string $end Due date
     * @param string $title Title of the task
     * @param string $text Task description
     * @param int $liste Tasklist
     * @param int $assigned ID of the user who has to complete the task
     * @return bool
     */
    function edit($id, $end, $title, $text, $liste)
    {
        $end = mysql_real_escape_string($end);
        $title = mysql_real_escape_string($title);
        $text = mysql_real_escape_string($text);
        $id = (int) $id;
        $liste = (int) $liste;

        $end = strtotime($end);

        $upd = mysql_query("UPDATE tasks SET `end`='$end',`title`='$title', `text`='$text', `liste`=$liste WHERE ID = $id");
		mysql_query("DELETE FROM tasks_assigned WHERE `task` = $id");


        if ($upd)
        {
            $nameproject = $this->getNameProject($id);
            $this->mylog->add($nameproject[0], 'task', 2, $nameproject[1]);
            return true;
        }
        else
        {
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
        $id = (int) $id;

        $nameproject = $this->getNameProject($id);
        $del = mysql_query("DELETE FROM tasks WHERE ID = $id LIMIT 1");
        if ($del)
        {
            $del2 = mysql_query("DELETE FROM tasks_assigned WHERE task=$id");
            $this->mylog->add($nameproject[0], 'task', 3, $nameproject[1]);
            return true;
        }
        else
        {
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
        $id = (int) $id;

        $upd = mysql_query("UPDATE tasks SET status = 1 WHERE ID = $id");
        if ($upd)
        {
            $nameproject = $this->getNameProject($id);
            $this->mylog->add($nameproject[0], 'task', 4, $nameproject[1]);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Close a task. If it's the last task of its tasklist, the list gets closed, too.
     *
     * @param int $id Task ID
     * @return bool
     */
    function close($id)
    {
        $id = (int) $id;

        $upd = mysql_query("UPDATE tasks SET status = 0 WHERE ID = $id");

        /*
        $sql = mysql_query("SELECT liste FROM tasks WHERE ID = $id");
        $liste = mysql_fetch_row($sql);
        $sql2 = mysql_query("SELECT count(*) FROM tasks WHERE liste = $liste[0] AND status = 1");
        $cou = mysql_fetch_row($sql2);
        // if this is the last task in its list, close the list too.
        if ($cou[0] == 0)
        {
            $tasklist = new tasklist();
            $tasklist->close_liste($liste[0]);
        }
		*/

        if ($upd)
        {
            $nameproject = $this->getNameProject($id);
            $this->mylog->add($nameproject[0], 'task', 5, $nameproject[1]);
            return true;
        }
        else
        {
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
        $task = (int) $task;
        $id = (int) $id;

        $upd = mysql_query("INSERT INTO tasks_assigned (user,task) VALUES ($id,$task)");
        if ($upd)
        {
            return true;
        }
        else
        {
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
        $task = (int) $task;
        $id = (int) $id;

        $upd = mysql_query("DELETE FROM tasks_assigned WHERE user = $id AND task = $task");
        if ($upd)
        {
            return true;
        }
        else
        {
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
        $id = (int) $id;

        $sel = mysql_query("SELECT * FROM tasks WHERE ID = $id");
        $task = mysql_fetch_array($sel, MYSQL_ASSOC);
        if (!empty($task))
        {
            // format datestring according to dateformat option

            if (is_numeric($task['end'])) {
            	$endstring = date(CL_DATEFORMAT, $task["end"]);
            } else {
            	$endstring = date(CL_DATEFORMAT, strtotime($task["end"]));
            }
            // get list and projectname of the task
            $details = $this->getTaskDetails($task);
            $list = $details["list"];
            $pname = $details["pname"];
            // get remainig days until due date
            $tage = $this->getDaysLeft($task['end']);

            $usel = mysql_query("SELECT user FROM tasks_assigned WHERE task = $task[ID]");
            $users = array();
            while ($usr = mysql_fetch_row($usel))
            {
                array_push($users, $usr[0]);
                $task["user"] = "All";
                $task["user_id"] = $users;
            }
            if (count($users) == 1)
            {
            	$usrobj = new user();
                $usr = $users[0];
                $user = $usrobj->getProfile($usr);
                $task["user"] = stripslashes($user["name"]);
                $task["users"] = array($user);
                $task["user_id"] = $user["ID"];

            }
            elseif(count($users) > 1)
            {

                $usrobj = new user();
                $task["users"] = array();
                $task["user"] = "";
                $task["user_id"] = 0;
                foreach($users as $user)
                {
                    $usr = $usrobj->getProfile($user);
                    $task["user"] .=  $usr["name"] . " ";
                	array_push($task["users"],$usr);
				}
            }

            $task["endstring"] = $endstring;

            $task["title"] = stripslashes($task["title"]);
            $task["text"] = stripslashes($task["text"]);
            $task["pname"] = stripslashes($pname);
            $task["list"] = $list;
            $task["daysleft"] = $tage;

            return $task;
        }
        else
        {
            return false;
        }
    }

    /**
     * Return all open tasks of a project
     *
     * @param int $project Project ID
     * @return array $lists Tasks
     */
    function getProjectTasks($project, $status = 1)
    {
        $project = (int) $project;
        $status = (int) $status;

        $lists = array();
        if ($status !== false)
        {
            $sel2 = mysql_query("SELECT ID FROM tasks WHERE project = $project AND status=$status");
        }
        else
        {
            $sel2 = mysql_query("SELECT ID FROM tasks WHERE project = $project");
        }
        while ($tasks = mysql_fetch_array($sel2, MYSQL_ASSOC))
        {
            $task = $this->getTask($tasks["ID"]);
            array_push($lists, $task);
        }

        if (!empty($lists))
        {
            return $lists;
        }
        else
        {
            return false;
        }
    }

    /**
     * Return all active / open tasks of a given project
     *
     * @param int $project Project ID
     * @param int $limit Number of tasks to return
     * @return array $lists Tasks
     */
    function getMyProjectTasks($project, $limit = 10)
    {
        $project = (int) $project;
        $limit = (int) $limit;

        $user = $_SESSION['userid'];
        $lists = array();
        $now = time();

        $sel2 = mysql_query("SELECT ID FROM tasks WHERE project = $project AND status=1 AND end > $now ORDER BY `end` ASC LIMIT $limit");

        while ($tasks = mysql_fetch_array($sel2, MYSQL_ASSOC))
        {
            $chk = mysql_fetch_row(mysql_query("SELECT ID FROM tasks_assigned WHERE user = $user AND task = $tasks[ID]"));
            $chk = $chk[0];
            if ($chk)
            {
                $task = $this->getTask($tasks["ID"]);
                array_push($lists, $task);
            }
        }

        if (!empty($lists))
        {
            return $lists;
        }
        else
        {
            return false;
        }
    }

    /**
     * Return open tasks from a given project a user
     *
     * @param int $project Project ID
     * @param int $limit Number of tasks to return
     * @param int $user User ID (0 means the user, to whom the session belongs)
     * @return array $lists Tasks
     */
    function getAllMyProjectTasks($project, $limit = 10, $user = 0)
    {
        $project = (int) $project;
        $limit = (int) $limit;
        $user = (int) $user;

        if ($user < 1)
        {
            $user = $_SESSION['userid'];
        }
        $lists = array();
        $now = time();

        $sel2 = mysql_query("SELECT tasks.*,tasks_assigned.user FROM tasks,tasks_assigned WHERE tasks.ID = tasks_assigned.task HAVING tasks_assigned.user = $user AND tasks.project = $project AND status=1 ORDER BY `end` ASC ");

        while ($tasks = mysql_fetch_array($sel2, MYSQL_ASSOC))
        {
            $task = $this->getTask($tasks["ID"]);
            array_push($lists, $task);
        }

        if (!empty($lists))
        {
            return $lists;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns all late tasks of a user from a given project
     *
     * @param int $project Project ID
     * @param int $limit Number of tasks to return
     * @return array $lists Tasks
     */
    function getMyLateProjectTasks($project, $limit = 10)
    {
        $project = (int) $project;
        $limit = (int) $limit;

        $user = $_SESSION["userid"];
        $lists = array();
        $tod = date("d.m.Y");
        $now = strtotime($tod);

        $sel2 = mysql_query("SELECT tasks.*,tasks_assigned.user FROM tasks,tasks_assigned WHERE tasks.ID = tasks_assigned.task HAVING tasks_assigned.user = $user AND tasks.project = $project  AND status=1 AND end < $now ORDER BY `end` ASC LIMIT $limit");
        while ($tasks = mysql_fetch_array($sel2, MYSQL_ASSOC))
        {
            $task = $this->getTask($tasks["ID"]);
            array_push($lists, $task);
        }

        if (!empty($lists))
        {
            return $lists;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns all tasks of today of a user from a given project
     *
     * @param int $project Project ID
     * @param int $limit Number of tasks to return
     * @return array $lists Tasks
     */
    function getMyTodayProjectTasks($project, $limit = 10)
    {
        $project = (int) $project;
        $limit = (int) $limit;

        $user = $_SESSION["userid"];
        $tod = date("d.m.Y");
        $lists = array();
        $now = strtotime($tod);

        $sel2 = mysql_query("SELECT tasks.*,tasks_assigned.user FROM tasks,tasks_assigned WHERE tasks.ID = tasks_assigned.task HAVING tasks_assigned.user = $user AND tasks.project = $project  AND status=1 AND end = '$now' ORDER BY `end` ASC LIMIT $limit");

        while ($tasks = mysql_fetch_array($sel2, MYSQL_ASSOC))
        {
            $task = $this->getTask($tasks["ID"]);
            array_push($lists, $task);
        }

        if (!empty($lists))
        {
            return $lists;
        }
        else
        {
            return false;
        }
    }

    /**
     * Return all done tasks of a user from a given project
     *
     * @param int $project Project ID
     * @param int $limit Number of tasks to return
     * @return array $lists Tasks
     */
    function getMyDoneProjectTasks($project, $limit = 5)
    {
        $project = (int) $project;
        $limit = (int) $limit;

        $user = $_SESSION["userid"];
        $lists = array();
        $now = time();

        $sel2 = mysql_query("SELECT tasks.*,tasks_assigned.user FROM tasks,tasks_assigned WHERE tasks.ID = tasks_assigned.task HAVING tasks_assigned.user = $user AND tasks.project = $project AND status=0 ORDER BY `end` ASC LIMIT $limit");

        while ($tasks = mysql_fetch_array($sel2, MYSQL_ASSOC))
        {
            $task = $this->getTask($tasks["ID"]);
            array_push($lists, $task);
        }

        if (!empty($lists))
        {
            return $lists;
        }
        else
        {
            return false;
        }
    }

	/**
     * Return all tasks (from a project) due on the specified date
     *
	 * @param int $m Month
	 * @param int $y Year
	 * @param int $d Day
     * @param int $project Project ID (Default: 0 = all projects)
     * @return array $timeline Tasks
     */
    function getTodayTasks($m, $y, $d, $project = 0)
    {
        $m = (int) $m;
        $y = (int) $y;

        if ($m > 9)
        {
            $startdate = date($d . "." . $m . "." . $y);
        }
        else
        {
            $startdate = date($d . ".0" . $m . "." . $y);
        }
        $starttime = strtotime($startdate);

        $user = (int) $_SESSION["userid"];
        $timeline = array();

        if ($project > 0)
        {
            $sql = "SELECT * FROM tasks  WHERE status=1 AND project = $project AND end = '$starttime'";
        }
        else
        {
			$sql = "SELECT tasks.*,tasks_assigned.user,projekte.name AS pname FROM tasks,tasks_assigned,projekte WHERE tasks.ID = tasks_assigned.task AND tasks.project = projekte.ID HAVING tasks_assigned.user = $user AND status=1 AND end = '$starttime'";
        }
        $sel1 = mysql_query($sql);

        while ($stone = mysql_fetch_array($sel1, MYSQL_ASSOC))
        {
            $stone["daysleft"] = $this->getDaysLeft($stone["end"]);
            array_push($timeline, $stone);
        }

        if (!empty($timeline))
        {
            return $timeline;
        }
        else
        {
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
        $id = (int) $id;

        $sql = mysql_query("SELECT user FROM tasks_assigned WHERE task = $id");
        $user = mysql_fetch_row($sql);

        if (!empty($user))
        {
            $sel2 = mysql_query("SELECT name FROM user WHERE ID = $user[0]");
            $uname = mysql_fetch_row($sel2);
            $uname = $uname[0];
            $user[1] = stripslashes($uname);

            return $user;
        }
        else
        {
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
        $id = (int) $id;

        $sql = mysql_query("SELECT user FROM tasks_assigned WHERE task = $id");
        if (mysql_num_rows($sql) > 0)
        {
            $result = array();
            while ($user = mysql_fetch_row($sql)) {


                $sel2 = mysql_query("SELECT name FROM user WHERE ID = $user[0]");
                $uname = mysql_fetch_row($sel2);
                $uname = $uname[0];
                $user[1] = stripslashes($uname);

                $result[] = $user;
            }
            return $result;
        }
        else
        {
            return false;
        }
    }

    /**
     * Export all tasks of a user via iCal
     *
     * @param int $user User ID
     * @return bool
     */
    function getIcal($user)
    {
        $user = (int) $user;

        $username = $_SESSION["username"];
        $project = new project();
        $myprojects = $project->getMyProjects($user);
        $tasks = array();
        if (!empty($myprojects))
        {
            foreach($myprojects as $proj)
            {
                $task = $this->getAllMyProjectTasks($proj["ID"], 10000);

                if (!empty($task))
                {
                    array_push($tasks, $task);
                }
            }
        }

        $etasks = reduceArray($tasks);
        require("class.ical.php");
        $heute = date("d-m-y");

        $cal = new vcalendar();
        $fname = "tasks_" . $username . ".ics";
        $cal->setConfig('directory', CL_ROOT . '/files/' . CL_CONFIG . '/ics');
        $cal->setConfig('filename', $fname);
        $cal->setConfig('unique_id' , '');
        $cal->setProperty('X-WR-CALNAME' , "Collabtive Aufgaben für " . $username);
        $cal->setProperty('X-WR-CALDESC' , '');
        $cal->setProperty('CALSCALE' , 'GREGORIAN');
        $cal->setProperty('METHOD' , 'PUBLISH');
        foreach($etasks as $etask)
        {
            // split date in Y / M / D / h / min / sek variables
            $jahr = date("Y", $etask["start"]);
            $monat = date("m", $etask["start"]);
            $tag = date("d", $etask["start"]);
            $std = date("h", $etask["start"]);
            $min = date("i", $etask["start"]);
            $sek = date("s", $etask["start"]);
            // split date in Y / M / D / h / min / sek variables
            $ejahr = date("Y", $etask['end']);
            $emonat = date("m", $etask['end']);
            $etag = date("d", $etask['end']);
            $estd = date("h", $etask['end']);
            $emin = date("i", $etask['end']);
            $esek = date("s", $etask['end']);

            $e = new vevent();
            $e->setProperty('categories' , $etask['list']);
            $e->setProperty('dtstart' , $jahr, $monat, $tag, $std, $min); // 24 dec 2007 19.30
            $e->setProperty('due' , $ejahr, $emonat, $etag, $estd, $emin); // 24 dec 2007 19.30
            $e->setProperty('dtend' , $ejahr, $emonat, $etag, $estd, $emin);
            $e->setProperty('description' , $etask["text"]);
            $e->setProperty('status' , "NEEDS-ACTION");
            // $e->setProperty('comment' , $etask[text]);
            $e->setProperty('summary' , $etask["title"]);

            $e->setProperty('location' , 'Work');
            $cal->setComponent($e);
        }
        $cal->returnCalendar();

        return true;
    }

    /**
     * Return a tasks project name and tasklist name
     *
     * @param array $task Task ID
     * @return array $details Name of associated project and tasklist
     */
    private function getTaskDetails(array $task)
    {
        $psel = mysql_query("SELECT name FROM projekte WHERE ID = $task[project]");
        $pname = mysql_fetch_row($psel);
        $pname = stripslashes($pname[0]);

        $list = mysql_query("SELECT name FROM tasklist WHERE ID = $task[liste]");
        $list = mysql_fetch_row($list);
        $list = stripslashes($list[0]);

        if (isset($list) or isset($pname))
        {
            $details = array("list" => $list, "pname" => $pname);
        }

        if (!empty($details))
        {
            return $details;
        }
        else
        {
            return false;
        }
    }

    /**
     * Return the number of left days until a task is due
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
    private function getNameProject($id)
    {
        $id = (int) $id;

        $nam = mysql_query("SELECT text,liste,title FROM tasks WHERE ID = $id");
        $nam = mysql_fetch_row($nam);
        $text = stripslashes($nam[2]);
        $list = $nam[1];
        $sel2 = mysql_query("SELECT project FROM tasklist WHERE ID = $list");
        $project = mysql_fetch_row($sel2);
        $project = $project[0];
        $nameproject = array($text, $project);

        if (!empty($nameproject))
        {
            return $nameproject;
        }
        else
        {
            return false;
        }
    }
}

?>