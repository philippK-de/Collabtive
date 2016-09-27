<?php
/**
* This class provides methods to realize timetracking
*
* @author Philipp Kiszka <info@o-dyn.de>
* @name timetracker
* @version 1.0
* @package Collabtive
 * @link http://collabtive.o-dyn.de
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
*/

class timetracker {


    /**
    * Add timetracker entry
    *
    * @param int $user User ID the timetrack belongs to
    * @param int $project Project ID the timetrack belongs to
    * @param int $task Task ID the timetrack belongs to
    * @param string $comment Comment on the timetrack
    * @param string $started Startdate of the tracked time period
    * @param string $ended Enddate of the tracked time period
    * @return int $insid Mysql ID of the inserted timetrack
    */
    function add($user, $project, $task, $comment, $started, $ended, $startday = "", $endday = "")
    {
        global $conn;
        $username = $_SESSION['username'];

        if (!$startday) {
            $startday = date(CL_DATEFORMAT);
        }

        if (!$endday) {
            $endday = $startday;
        }

        $started = $startday . " " . $started;
        $started = strtotime($started);
        $ended = $endday . " " . $ended;
        $ended = strtotime($ended);

        $hours = $ended - $started;
        $hours = $hours / 3600;
        $hours = round($hours, 2);

        if ($started >= $ended) {
            return false;
        }

        $insStmt = $conn->prepare("INSERT INTO timetracker (user,project,task,comment,started,ended,hours,pstatus) VALUES (?,?,?,?,?,?,?,0)");
        $ins = $insStmt->execute(array((int) $user, (int) $project, (int) $task, $comment, $started, $ended, $hours));

        if ($ins) {
            $insid = $conn->lastInsertId();
            $title = $username . " " . $hours . "h";

            return $insid;
        } else {
            return false;
        }
    }

    /**
    * Edit timetracker entry
    *
    * @param int $id Timetrack ID to edit
    * @param int $task Task ID the timetrack belongs to
    * @param string $comment Comment on the timetrack
    * @param string $started Startdate of the tracked time period
    * @param string $ended Enddate of the tracked time period
    * @return bool
    */
    function edit($id, $task, $comment, $started, $ended)
    {
        global $conn;

        if ($started >= $ended) {
            return false;
        }

        $hours = $ended - $started;
        $hours = $hours / 3600;
        $hours = round($hours, 2);

        $updStmt = $conn->prepare("UPDATE timetracker SET task=?, comment=?, started=?, ended=?, hours=? WHERE ID = ?");
        $upd = $updStmt->execute(array((int) $task, $comment, (int) $started, (int) $ended, $hours, (int) $id));

        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Delete timetracker entry
    *
    * @return bool
    */
    function del($id)
    {
        global $conn;
        $id = (int) $id;

        $del = $conn->query("DELETE FROM timetracker WHERE ID = $id");

        if ($del) {
            return true;
        } else {
            return false;
        }
    }

    function setPaystatus($pstatus, $id)
    {
        global $conn;
        $pstatus = (int) $pstatus;
        $id = (int) $id;
        $upd = $conn->query("UPDATE timetracker SET pstatus = $pstatus WHERE ID = $id");

        if ($upd) {
            return true;
        } else {
            return false;
        }
    }
    /**
    * Return a timetrack
    *
    * @param int $id Timetrack ID
    * @return array $track
    */
    function getTrack($id)
    {
        global $conn;
        $id = (int) $id;

        $sel = $conn->query("SELECT * FROM timetracker WHERE ID = $id");
        $track = array();
        $track = $sel->fetch();

        if (!empty($track)) {
            if (isset($track["started"]) and isset($track["ended"])) {
                $hours = $track["ended"] - $track["started"];
                $hours = $hours / 3600;
                $hours = round($hours, 2);
                $track["hours"] = $hours;

                $day = date(CL_DATEFORMAT, $track["started"]);
                $endday = date(CL_DATEFORMAT, $track["ended"]);
                $track["started"] = date("H:i", $track["started"]);
                $track["ended"] = date("H:i", $track["ended"]);

                $track["day"] = $day;
                $track["endday"] = $endday;
            }

            return $track;
        } else {
            return false;
        }
    }

    function getUserTrack($user, $project = 0, $task = 0, $start = 0, $end = 0 , $lim = 25, $offset = 0)
    {
        global $conn;
        $user = (int) $user;
        $project = (int) $project;
        $lim = (int) $lim;
        $task = (int) $task;
        // $start = (int) $start; // those are strings, not numbers
        // $end = (int) $end;
        if ($project > 0) {
            $sql = "SELECT * FROM timetracker WHERE user = $user AND project = $project";
            $num = "SELECT COUNT(*) FROM timetracker WHERE user = $user AND project = $project";
            $order = " ORDER BY ID DESC";
        } else {
            $sql = "SELECT * FROM timetracker WHERE user = $user";
            $num = "SELECT COUNT(*) FROM timetracker WHERE user = $user";
            $order = " ORDER BY ID DESC";
        }

        if ($task > 0) {
            $sql .= " AND task = $task";
            $num .= " AND task = $task";
        }

        if ($start > 0 and $end > 0) {
            $start = strtotime($start);
            $end = strtotime($end . " +1 day");
            $end = $end - 1;
            $sql .= " AND ended >=$start AND ended<=$end ";
            $num .= " AND ended >=$start AND ended<=$end ";
        }

        if ($num) {
            $num = $conn->query($num)->fetch();
            $num = $num[0];
        } else {
            $num = 0;
        }
		//needs to be refactroed
        if ($lim > 0) {
            $sql = $sql . $order;

            $start = 0;
            $limi = " LIMIT $lim OFFSET $offset ";
            $sql = $sql . $limi;
        }

        $sel = $conn->query($sql);
        $track = array();
        $ttask = new task();

        if (isset($sel)) {
            while ($data = @$sel->fetch()) {
                $endstring = date("H:i", $data["ended"]);
                $startstring = date("H:i", $data["started"]);
                $daystring = date("d.m.y", $data["ended"]);
                $tasks = $ttask->getTask($data["task"]);

                if (!empty($tasks)) {
                    $tasks = $tasks["title"];
                    $data["tname"] = $tasks;
                }

                $pname = $conn->query("SELECT name FROM projekte WHERE ID = $data[project]")->fetch();
                $pname = stripslashes($pname[0]);

                $uname = $conn->query("SELECT name FROM user WHERE ID = $data[user]")->fetch();
                $uname = stripslashes($uname[0]);

                $data["endstring"] = $endstring;
                $data["startstring"] = $startstring;
                $data["daystring"] = $daystring;
                $data["uname"] = $uname;
                $data["pname"] = $pname;
                $data["comment"] = stripslashes($data["comment"]);
                $data["comment"] = nl2br($data["comment"]);
                array_push($track, $data);
            }
        }

        if (!empty($track)) {
            return $track;
        } else {
            return false;
        }
    }

    function getProjectTrack($project, $user = 0, $task = 0, $start = 0, $end = 0, $lim = 25, $offset=0)
    {
        global $conn;
        $project = (int) $project;
        $user = (int) $user;
        $lim = (int) $lim;
        // make sure task is an array - this needs to be refactored
        if (!is_array($task)) {
            $task = array($task);
        }
        // make sure all the fields are ints
        for ($index = 0; $index < count($task);$index++) {
            $task[$index] = (int) $task[$index];
        }
        $task = join(',', $task);

        if ($user > 0) {
            $sql = "SELECT * FROM timetracker WHERE project = $project AND user = $user";
            $num = "SELECT COUNT(*) FROM timetracker WHERE project = $project AND user = $user";
            $order = " ORDER BY ID DESC";
        } else {
            $sql = "SELECT * FROM timetracker WHERE project = $project";
            $num = "SELECT COUNT(*) FROM timetracker WHERE project = $project";
            $order = " ORDER BY ID DESC";
        }

        if ($task > 0) {
            $sql .= " AND task in ($task)";
            $num .= " AND task in ($task)";
        }

        if ($start > 0 and $end > 0) {
            $start = strtotime($start);
            $end = strtotime($end . " +1 day");
            $end = $end - 1;
            $sql .= " AND ended >=$start AND ended<=$end ";
            $num .= " AND ended >=$start AND ended<=$end ";
        }

        if ($num) {
            $num = $conn->query($num)->fetch();
            $num = $num[0];
        } else {
            $num = 0;
        }

        $sql = $sql . $order;
        // needs to be refactored
        if ($lim > 0) {

            $limi = "  LIMIT $lim OFFSET $offset";
            $sql = $sql . $limi;
        }
        $sel = $conn->query($sql);

        $track = array();
        $ttask = new task();

        if (isset($sel)) {
            while ($data = @$sel->fetch()) {
                $endstring = date("H:i", $data["ended"]);
                $startstring = date("H:i", $data["started"]);
                $daystring = date(CL_DATEFORMAT, $data["ended"]);
                $task = $ttask->getTask($data["task"]);

                if (!empty($task)) {
                    $data["task"] = $task;
                    $data["hasTask"] = true;
                    $data["tname"] = $task["title"];
                }
                else
                {
                    $data["hasTask"] = false;
                }

                $pname = $conn->query("SELECT name FROM projekte WHERE ID = $data[project]")->fetch();
                $pname = stripslashes($pname[0]);

                $uname = $conn->query("SELECT name FROM user WHERE ID = $data[user]")->fetch();
                $uname = stripslashes($uname[0]);

                $data["endstring"] = $endstring;
                $data["startstring"] = $startstring;
                $data["daystring"] = $daystring;
                $data["uname"] = $uname;
                $data["pname"] = $pname;
                $data["comment"] = stripslashes($data["comment"]);
                $data["comment"] = nl2br($data["comment"]);
                array_push($track, $data);
            }
        }

        if (!empty($track)) {
            return $track;
        } else {
            return false;
        }
    }

    /**
    * Get total time spent on a given timetrack
    *
    * @param array $track Timetrack to evaluate
    * @return float $totaltime Total time spent on the timetrack
    */
    function getTotalTrackTime(array $track)
    {
        $totaltime = 0;

        foreach($track as $data) {
            $totaltime = $totaltime + $data["hours"];
        }

        if (!($totaltime > 0)) {
            $totaltime = 0;
        }

        return $totaltime;
    }
}

?>
