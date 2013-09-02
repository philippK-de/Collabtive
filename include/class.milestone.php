<?php
/**
 * This class provides methods to realize milestones
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name milestone
 * @package Collabtive
 * @version 1.0
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 * @global $mylog
 */
class milestone {
    private $mylog;

    /**
     * Constructor
     * Initialize the event log
     */
    function __construct()
    {
        $this->mylog = new mylog;
    }

    /**
     * Add a milestone
     *
     * @param int $project ID of the associated project
     * @param string $name Name of the milestone
     * @param string $desc Description
     * @param string $end Day the milestone is due
     * @param int $status Status (0 = finished, 1 = open)
     * @return bool
     */
    function add($project, $name, $desc, $start, $end, $status = 1)
    {
        global $conn;

		//Convert end date to timestamp
        $end = strtotime($end);
        $start = strtotime($start);

        $insStmt = $conn->prepare("INSERT INTO milestones (`project`,`name`,`desc`,`start`,`end`,`status`) VALUES (?, ?, ?, ?, ?, ?)");
        $ins = $insStmt->execute(array((int) $project, $name, $desc, $start, $end, (int) $status));

        if ($ins) {
            $insid = $conn->lastInsertId();
            $this->mylog->add($name, 'milestone' , 1, $project);
            return $insid;
        } else {
            return false;
        }
    }

    /**
     * Edit a milestone
     *
     * @param int $id Milestone ID
     * @param string $name Name
     * @param string $desc Description
     * @param string $end Day it is due
     * @return bool
     */
    function edit($id, $name, $desc, $start, $end)
    {
        global $conn;
        $id = (int) $id;
        $start = strtotime($start);
        $end = strtotime($end);

        $updStmt = $conn->prepare("UPDATE milestones SET `name`=?, `desc`=?, `start`=?, `end`=? WHERE ID=?");
        $upd = $updStmt->execute(array($name, $desc, $start, $end, $id));
        if ($upd) {
            $nam = $conn->query("SELECT project,name FROM milestones WHERE ID = $id")->fetch();
            $project = $nam[0];
            $name = $nam[1];

            $this->mylog->add($name, 'milestone' , 2, $project);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a milestone
     *
     * @param int $id Milestone ID
     * @return bool
     */
    function del($id)
    {
        global $conn;
        $id = (int) $id;

        $nam = $conn->query("SELECT project,name FROM milestones WHERE ID = $id");
        $del = $conn->query("DELETE FROM milestones WHERE ID = $id");
        $del1 = $conn->query("DELETE FROM milestones_assigned WHERE milestone = $id");
        if ($del) {
            $nam = $nam->fetch();
            $project = $nam[0];
            $name = $nam[1];

            $this->mylog->add($name, 'milestone', 3, $project);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Mark a milestone as open / active
     *
     * @param int $id Milestone ID
     * @return bool
     */
    function open($id)
    {
        global $conn;
        $id = (int) $id;

        $upd = $conn->query("UPDATE milestones SET status = 1 WHERE ID = $id");

        if ($upd) {
            $nam = $conn->query("SELECT project,name FROM milestones WHERE ID = $id");
            $nam = $nam->fetch();
            $project = $nam[0];
            $name = $nam[1];

            $this->mylog->add($name, 'milestone', 4, $project);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Marka milestone as finished
     *
     * @param int $id Milestone ID
     * @return bool
     */
    function close($id)
    {
        global $conn;
        $id = (int) $id;

        $upd = $conn->query("UPDATE milestones SET status = 0 WHERE ID = $id");
        //Get attached tasklists
        $tasklists = $this->getMilestoneTasklists($id);
        //Loop through tasklists , close all tasks in them, then close tasklist itself
		if (!empty($tasklists)) {
            foreach ($tasklists as $tasklist) {
                $tl = new tasklist();
                $tasks = $tl->getTasksFromList($tasklist[ID]);
                foreach ($tasks as $task) {
                    $close_task = $conn->query("UPDATE tasks SET status = 0 WHERE ID = $task[ID]");
                }
                $close_tasklist = $conn->query("UPDATE tasklist SET status = 0 WHERE ID = $tasklist[ID]");
            }
        }

        if ($upd) {
            $nam = $conn->query("SELECT project,name FROM milestones WHERE ID = $id");
            $nam = $nam->fetch();
            $project = $nam[0];
            $name = $nam[1];

            $this->mylog->add($name, 'milestone', 5, $project);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Assign a milestone to a user
     *
     * @param int $milestone Milestone ID
     * @param int $user User ID
     * @return bool
     */
    function assign($milestone, $user)
    {
        global $conn;
        $milestone = (int) $milestone;
        $user = (int) $user;

        $upd = $conn->query("INSERT INTO milestones_assigned (NULL,$user,$milestone)");
        if ($upd) {
            $nam = $conn->query("SELECT project,name FROM milestones WHERE ID = $id");
            $nam = $nam->fetch();
            $project = $nam[0];
            $name = $nam[1];

            $this->mylog->add($name, 'milestone', 6, $project);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete the assignment of a milestone to a given user
     *
     * @param int $milestone Milestone ID
     * @param int $user User ID
     * @return bool
     */
    function deassign($milestone, $user)
    {
        global $conn;
        $milestone = (int) $milestone;
        $user = (int) $user;

        $upd = $conn->query("DELETE FROM milestones_assigned WHERE user = $user AND milestone = $milestone");
        if ($upd) {
            $nam = $conn->query("SELECT project,name FROM milestones WHERE ID = $id");
            $nam = $nam->fetch();
            $project = $nam[0];
            $name = $nam[1];

            $this->mylog->add($name, 'milestone', 7, $project);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return a milestone with its tasklists
     *
     * @param int $id Milestone ID
     * @return array $milestone Milestone details
     */
    function getMilestone($id)
    {
        global $conn;
        $id = (int) $id;

        $sel = $conn->query("SELECT * FROM milestones WHERE ID = $id");
        $milestone = $sel->fetch();

        if (!empty($milestone)) {
        	//Format start and end date for display
            $endstring = date(CL_DATEFORMAT, $milestone["end"]);
            $milestone["endstring"] = $endstring;
            $milestone["fend"] = $endstring;

            $startstring = date(CL_DATEFORMAT, $milestone["start"]);
            $milestone["startstring"] = $startstring;

            $milestone["name"] = stripslashes($milestone["name"]);
            $milestone["desc"] = stripslashes($milestone["desc"]);

			//Get the name of the project where the message was posted for display
            $psel = $conn->query("SELECT name FROM projekte WHERE ID = $milestone[project]");
            $pname = $psel->fetch();
            $pname = $pname[0];
            $milestone["pname"] = $pname;
            $milestone["pname"] = stripslashes($milestone["pname"]);

			//Daysleft contains a signed number, dayslate an unsigned one that only applies if the milestone is late
            $dayslate = $this->getDaysLeft($milestone["end"]);
            $milestone["daysleft"] = $dayslate;
            $dayslate = str_replace("-", "" , $dayslate);
            $milestone["dayslate"] = $dayslate;

			//Get attached tasklists and messages
            $tasks = $this->getMilestoneTasklists($milestone["ID"]);
            $milestone["tasklists"] = $tasks;
            $messages = $this->getMilestoneMessages($milestone["ID"]);
            $milestone["messages"] = $messages;

            return $milestone;
        } else {
            return false;
        }
    }

    /**
     * Return the latest milestones
     *
     * @param int $status Status (0 = finished, 1 = open)
     * @param int $lim Number of milestones to return
     * @return array $milestones Details of the milestones
     */
    function getMilestones($status = 1, $lim = 100)
    {
        global $conn;
        $status = (int) $status;
        $lim = (int) $lim;

        $milestones = array();

        $sel = $conn->query("SELECT ID FROM milestones WHERE `status`=$status  ORDER BY `end` ASC LIMIT $lim");

        while ($milestone = $sel->fetch()) {
            $themilestone = $this->getMilestone($milestone["ID"]);
            array_push($milestones, $themilestone);
        }

        if (!empty($milestones)) {
            return $milestones;
        } else {
            return false;
        }
    }

    /**
     * Return all finished milestones of a given project
     *
     * @param int $project Project ID
     * @return array $stones Details of the milestones
     */
    function getDoneProjectMilestones($project)
    {
        global $conn;
        $project = (int) $project;

        $sel = $conn->query("SELECT ID FROM milestones WHERE project = $project AND status = 0 ORDER BY `end` ASC");
        $stones = array();

        while ($milestone = $sel->fetch()) {
            $themilestone = $this->getMilestone($milestone["ID"]);
            array_push($stones, $themilestone);
        }

        if (!empty($stones)) {
            return $stones;
        } else {
            return false;
        }
    }

    /**
     * Return all late milestones of a given project
     *
     * @param int $project Project ID
     * @param int $lim Number of milestones to return
     * @return array $milestones Dateils of the late milestones
     */
    function getLateProjectMilestones($project, $lim = 100)
    {
        global $conn;
        $project = (int) $project;
        $lim = (int) $lim;

        $tod = date("d.m.Y");
        $now = strtotime($tod);
        $milestones = array();

        $sql = "SELECT ID FROM milestones WHERE project = $project AND end < $now AND status = 1 ORDER BY end ASC LIMIT $lim";

        $sel1 = $conn->query($sql);
        while ($milestone = $sel1->fetch()) {
            if (!empty($milestone)) {
                $themilestone = $this->getMilestone($milestone["ID"]);
                array_push($milestones, $themilestone);
            }
        }

        if (!empty($milestones)) {
            return $milestones;
        } else {
            return false;
        }
    }

    /**
     * Return all open milestones of a given project
     *
     * @param int $project Project ID
     * @param int $lim Number of milestones to return
     * @return array $milestones Details of the open milestones
     */
    function getAllProjectMilestones($project, $lim = 100)
    {
        global $conn;
        $project = (int) $project;
        $lim = (int) $lim;

        $tod = date("d.m.Y");
        $now = strtotime($tod);
        $milestones = array();
        $sql = "SELECT ID FROM milestones WHERE project = $project AND status = 1 ORDER BY end ASC LIMIT $lim";

        $sel1 = $conn->query($sql);
        while ($milestone = $sel1->fetch()) {
            if (!empty($milestone)) {
                $themilestone = $this->getMilestone($milestone["ID"]);
                array_push($milestones, $themilestone);
            }
        }

        if (!empty($milestones)) {
            return $milestones;
        } else {
            return false;
        }
    }

    /**
     * Return all milestone of a given project, that are not late
     *
     * @param int $project Project ID
     * @param int $lim Number of milestones to return
     * @return array $milestones Details of the milestones
     */
    function getProjectMilestones($project, $lim = 100)
    {
        global $conn;
        $project = (int) $project;
        $lim = (int) $lim;

        $now = time();
        $milestones = array();
        $sql = "SELECT * FROM milestones WHERE project = $project AND end > $now AND status = 1 ORDER BY end ASC";

        if ($lim > 0) {
            $sql .= " LIMIT $lim";
        }

        $sel1 = $conn->query($sql);
        while ($milestone = $sel1->fetch()) {
            $themilestone = $this->getMilestone($milestone["ID"]);
            array_push($milestones, $themilestone);
        }

        if (!empty($milestones)) {
            return $milestones;
        } else {
            return false;
        }
    }

    /**
     * Return all milestones of a projects, that are due today
     *
     * @param int $project Project ID
     * @param int $lim Number of milestones to return
     * @return array $milestones Details of the milestones
     */

    function getTodayProjectMilestones($project, $lim = 100)
    {
        global $conn;
        $project = (int) $project;
        $lim = (int) $lim;

        $tod = date("d.m.Y");
        $now = strtotime($tod);
        $milestones = array();

        $sel1 = $conn->query("SELECT * FROM milestones WHERE project = $project AND end = '$now' AND status = 1 ORDER BY end ASC LIMIT $lim");
        while ($milestone = $sel1->fetch()) {
            $themilestone = $this->getMilestone($milestone["ID"]);
            array_push($milestones, $themilestone);
        }

        if (!empty($milestones)) {
            return $milestones;
        } else {
            return false;
        }
    }

    /**
     * Return all milestones of that belong to the loggedin user, due on a given day.
     * This method is needed for populating the calendar widget with data.
     *
     * @param int $m Month Month, without leading zero (e.g. 5 for march)
     * @param int $y Year Year in format yyyy
     * @param int $d Day Without leading zero (e.g. 1 for the 1st of the month $m in year $y)
     * @return array $milestones Details of the milestones
     */
    function getTodayMilestones($m, $y, $d, $project = 0)
    {
        global $conn;
        $m = (int) $m;
        $y = (int) $y;

        if ($m > 9) {
            $startdate = date($d . "." . $m . "." . $y);
        } else {
            $startdate = date($d . ".0" . $m . "." . $y);
        }
        $starttime = strtotime($startdate);
        $user = (int) $_SESSION["userid"];

        $timeline = array();

        if ($project > 0) {
            $sel1 = $conn->query("SELECT * FROM milestones WHERE project =  $project AND status=1 AND end = '$starttime' ORDER BY `end` ASC");
        } else {
            $sel1 = $conn->query("SELECT milestones.*,projekte_assigned.user,projekte.name AS pname FROM milestones,projekte_assigned,projekte WHERE milestones.project = projekte_assigned.projekt AND milestones.project = projekte.ID HAVING projekte_assigned.user = $user AND status=1 AND end = '$starttime'");
        } while ($stone = $sel1->fetch()) {
            $stone["daysleft"] = $this->getDaysLeft($stone["end"]);
            array_push($timeline, $stone);
        }

        if (!empty($timeline)) {
            return $timeline;
        } else {
            return array();
        }
    }

    /**
     * Return all open tasklists associated to a given milestones
     *
     * @param int $milestone Milestone ID
     * @return array $lists Details of the tasklists
     */
    private function getMilestoneTasklists($milestone)
    {
        global $conn;
        $milestone = (int) $milestone;

		$objtasklist = new tasklist();

        $sel = $conn->query("SELECT ID FROM tasklist WHERE milestone = $milestone AND status = 1 ORDER BY ID ASC");
        $lists = array();
        if ($milestone) {
            while ($listId = $sel->fetch()) {
                array_push($lists, $objtasklist->getTasklist($listId["ID"]));
            }
        }
        if (!empty($lists)) {
            return $lists;
        } else {
            return false;
        }
    }

    private function getMilestoneMessages($milestone)
    {
        global $conn;
        $milestone = (int) $milestone;
        $objmessage = new message();

        $sel = $conn->query("SELECT title,ID,milestone FROM messages WHERE milestone = $milestone");
        $msgs = array();
        while ($msg = $sel->fetch()) {
            array_push($msgs, $msg);
        }
        if (!empty($msgs)) {
            return $msgs;
        }
    }

    /**
     * Return the days left from today until a given point in time
     *
     * @param int $end Point in time
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
     * Format a milestone's timestamp
     *
     * @param int $milestones Milestone ID
     * @param int $format Wanted time format
     * @return array $milestones Milestone with the formatted timestamp
     */
    function formatdate(array $milestones)
    {
        $cou = 0;

        if ($milestones) {
            foreach($milestones as $stone) {
                $datetime = date(CL_DATEFORMAT, $stone[5]);
                $milestones[$cou]["due"] = $datetime;
                $cou = $cou + 1;
            }
        }

        if (!empty($milestones)) {
            return $milestones;
        } else {
            return false;
        }
    }
}

?>