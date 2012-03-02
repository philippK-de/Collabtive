<?php
/**
 * This class provides methods to realize timetracking
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name timetracker
 * @version 0.7.5
 * @package Collabtive
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */

class timetracker
{
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
    function add($user, $project, $task, $comment, $started, $ended, $logday = "")
    {
        $started = mysql_real_escape_string($started);
        $ended = mysql_real_escape_string($ended);
        $comment = mysql_real_escape_string($comment);
        $username = $_SESSION['username'];
        $user = (int) $user;
        $project = (int) $project;
        $task = (int) $task;

		if(!$logday)
		{
        	$startdate = date("d.m.Y");
		}
		else
		{
			$startdate = $logday;
		}

        $started = $startdate . " " . $started;
        $started = strtotime($started);
        $ended = $startdate . " " . $ended;
        $ended = strtotime($ended);

        $hours = $ended - $started;
        $hours = $hours / 3600;
        $hours = round($hours, 2);

        if ($started >= $ended)
        {
            return false;
        }

        $ins = mysql_query("INSERT INTO timetracker (user,project,task,comment,started,ended,hours,pstatus) VALUES ($user,$project,$task,'$comment','$started','$ended','$hours',0)");

        if ($ins)
        {
            $insid = mysql_insert_id();
            $title = $username . " " . $hours . "h";

            return $insid;
        }
        else
        {
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
        $comment = mysql_real_escape_string($comment);
        $started = (int) $started;
        $ended = (int) $ended;
        $id = (int) $id;
        $task = (int) $task;

        if ($started >= $ended)
        {
            return false;
        }

        $hours = $ended - $started;
        $hours = $hours / 3600;
        $hours = round($hours, 2);

        $upd = mysql_query("UPDATE timetracker SET task='$task',comment='$comment',started='$started',ended='$ended',hours='$hours' WHERE ID = $id");

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
     * Delete timetracker entry
     *
     * @return bool
     */
    function del($id)
    {
        $id = (int) $id;

        $del = mysql_query("DELETE FROM timetracker WHERE ID = $id");

        if ($del)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function setPaystatus($pstatus, $id)
    {
        $pstatus = (int) $pstatus;
        $id = (int) $id;
        $upd = mysql_query("UPDATE timetracker SET pstatus = $pstatus WHERE ID = $id");

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
     * Return a timetrack
     *
     * @param int $id Timetrack ID
     * @return array $track
     */
    function getTrack($id)
    {
        $id = (int) $id;

        $sel = mysql_query("SELECT * FROM timetracker WHERE ID = $id");
        $track = array();
        $track = mysql_fetch_array($sel);

        if (!empty($track))
        {
            if (isset($track["started"]) and isset($track["ended"]))
            {
                $hours = $track["ended"] - $track["started"];
                $hours = $hours / 3600;
                $hours = round($hours, 2);
                $track["hours"] = $hours;

                $day = date("d.m.Y", $track["started"]);
                $track["started"] = date("H:i", $track["started"]);
                $track["ended"] = date("H:i", $track["ended"]);

                $track["day"] = $day;
            }

            if (isset($track["comment"]))
            {
                $track["comment"] = stripslashes($track["comment"]);
            }

            return $track;
        }
        else
        {
            return false;
        }
    }

    function getUserTrack($user, $project = 0, $task = 0, $start = 0, $end = 0 , $lim = 50)
    {
        $start = mysql_real_escape_string($start);
        $end = mysql_real_escape_string($end);
        $user = (int) $user;
        $project = (int) $project;
        $lim = (int) $lim;

        if ($project > 0)
        {
            $sql = "SELECT * FROM timetracker WHERE user = $user AND project = $project";
            $num = "SELECT COUNT(*) FROM timetracker WHERE project = $project AND user = $user";
            $order = " ORDER BY ended ASC";
        }
        else
        {
            $sql = "SELECT * FROM timetracker WHERE user = $user";
            $num = "SELECT COUNT(*) FROM timetracker WHERE user = $user";
            $order = " ORDER BY ended ASC";
        }

        if ($task > 0)
        {
            $sql .= " AND task = $task";
            $num .= " AND task = $task";
        }

        if ($start > 0 and $end > 0)
        {
            $start = strtotime($start);
            $end = strtotime($end . " +1 day");
            $end = $end - 1;
			$sql .= " AND ended >=$start AND ended<=$end ";
            $num .= " AND ended >=$start AND ended<=$end ";
        }

        if ($num)
        {
            $num = mysql_fetch_row(mysql_query($num));
            $num = $num[0];
        }
        else
        {
            $num = 0;
        }

        $sql = $sql . $order;
        SmartyPaginate::connect();
        SmartyPaginate::setLimit($lim);
        SmartyPaginate::setTotal($num);

        $start = SmartyPaginate::getCurrentIndex();
        $lim = SmartyPaginate::getLimit();

        $limi = " LIMIT $start,$lim";
        $sql = $sql . $limi;

        $sel = mysql_query($sql);
        $track = array();
        $ttask = new task();

        if (isset($sel))
        {
            while ($data = @mysql_fetch_array($sel))
            {
                $endstring = date("H:i", $data["ended"]);
                $startstring = date("H:i", $data["started"]);
                $daystring = date("d.m.y", $data["ended"]);
                $tasks = $ttask->getTask($data["task"]);

                if (!empty($tasks))
                {
                    $tasks = $tasks["title"];
                    $data["tname"] = $tasks;
                }

                $pname = mysql_query("SELECT name FROM projekte WHERE ID = $data[project]");
                $pname = mysql_fetch_row($pname);
                $pname = stripslashes($pname[0]);

                $uname = mysql_query("SELECT name FROM user WHERE ID = $data[user]");
                $uname = mysql_fetch_row($uname);
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

        if (!empty($track))
        {
            return $track;
        }
        else
        {
            return false;
        }
    }

    function getProjectTrack($project, $user = 0, $task = 0, $start = 0, $end = 0, $lim = 50)
    {
        $start = mysql_real_escape_string($start);
        $end = mysql_real_escape_string($end);
        $project = (int) $project;
        $user = (int) $user;
        $lim = (int) $lim;

        if ($user > 0)
        {
            $sql = "SELECT * FROM timetracker WHERE project = $project AND user = $user";
            $num = "SELECT COUNT(*) FROM timetracker WHERE project = $project AND user = $user";
            $order = " ORDER BY ended ASC";
        }
        else
        {
            $sql = "SELECT * FROM timetracker WHERE project = $project";
            $num = "SELECT COUNT(*) FROM timetracker WHERE project = $project";
            $order = " ORDER BY ended ASC";
        }

        if ($task > 0)
        {
            $sql .= " AND task = $task";
            $num .= " AND task = $task";
        }

        if ($start > 0 and $end > 0)
        {
            $start = strtotime($start);
            $end = strtotime($end . " +1 day");
            $end = $end - 1;
            $sql .= " AND ended >=$start AND ended<=$end ";
            $num .= " AND ended >=$start AND ended<=$end ";
        }

        if ($num)
        {
            $num = mysql_fetch_row(mysql_query($num));
            $num = $num[0];
        }
        else
        {
            $num = 0;
        }

        $sql = $sql . $order;
        SmartyPaginate::connect();
        SmartyPaginate::setLimit($lim);
        SmartyPaginate::setTotal($num);

        $start = SmartyPaginate::getCurrentIndex();
        $lim = SmartyPaginate::getLimit();

        $limi = " LIMIT $start,$lim ";
        $sql = $sql . $limi;

        $sel = mysql_query($sql);

        $track = array();
        $ttask = new task();

        if (isset($sel))
        {
            while ($data = @mysql_fetch_array($sel))
            {
                $endstring = date("H:i", $data["ended"]);
                $startstring = date("H:i", $data["started"]);
                $daystring = date("d.m.y", $data["ended"]);
                $tasks = $ttask->getTask($data["task"]);

                if (!empty($tasks))
                {
                    $tasks = $tasks["title"];
                    $data["tname"] = $tasks;
                }

                $pname = mysql_query("SELECT name FROM projekte WHERE ID = $data[project]");
                $pname = mysql_fetch_row($pname);
                $pname = stripslashes($pname[0]);

                $uname = mysql_query("SELECT name FROM user WHERE ID = $data[user]");
                $uname = mysql_fetch_row($uname);
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

        if (!empty($track))
        {
            return $track;
        }
        else
        {
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

        foreach($track as $data)
        {
            $totaltime = $totaltime + $data["hours"];
        }

        if (!($totaltime > 0))
        {
            $totaltime = 0;
        }

        return $totaltime;
    }
}

?>