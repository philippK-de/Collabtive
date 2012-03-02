<?php
/*
 * This class provides methods to realize the logging of different activities
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name mylog
 * @version 0.4.5
 * @package Collabtive
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class mylog
{
    /*
    * Constructor
    */
    function __construct()
    {
        $this->userid = getArrayVal($_SESSION,"userid");
        $this->uname = getArrayVal($_SESSION,"username");
    }

    /*
     * Add an event log entry
     *
     * @param string $name Name of the affected object
     * @param string $type Type of the affected object
     * @param int $action Action (1 = added, 2 = edited, 3 = deleted, 4 = opened, 5 = finished, 6 = assigned, 7 = deleted assignment)
     * @param int $project Project ID
     * @return bool
     */
    function add($name, $type, $action, $project)
    {
        $user = $this->userid;
        $uname = $this->uname;

        $name = mysql_real_escape_string($name);
        $type = mysql_real_escape_string($type);
        $action = (int) $action;
        $project = (int) $project;

        $now = time();

        $ins = mysql_query("INSERT INTO log (user,username,name,type,action,project,datum) VALUES ('$user','$uname','$name','$type',$action,$project,'$now')");
        if ($ins)
        {
            $insid = mysql_insert_id();
            return $insid;
        }
        else
        {
            return false;
        }
    }

    /*
     * Delete an event log entry
     *
     * @param int $id Log entry ID
     * @return bool
     */
    function del($id)
    {
        $id = (int) $id;

        $del = mysql_query("DELETE FROM log WHERE ID = $id LIMIT 1");
        if ($del)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*
     * Return all log entries associated with a given project
     *
     * @param int $project Project ID
     * @param int $limit Number of entries to return
     * @return array $mylog Log entries
     */
    function getProjectLog($project, $lim = 10)
    {
        $project =(int) $project;
        $lim = (int) $lim;

        $sel = mysql_query("SELECT COUNT(*) FROM log WHERE project = $project ");
		$num = mysql_fetch_row($sel);
        $num = $num[0];
       	if($num > 200)
       	{
		    $num = 200;
		}
		SmartyPaginate::connect();
        // set items per page
        SmartyPaginate::setLimit($lim);
        SmartyPaginate::setTotal($num);

		$start = SmartyPaginate::getCurrentIndex();
        $lim = SmartyPaginate::getLimit();
        $sql = "SELECT * FROM log WHERE project = $project ORDER BY ID DESC LIMIT $start,$lim";
		$sel2 = mysql_query($sql);

        $mylog = array();
        while ($log = mysql_fetch_array($sel2))
        {
            if (!empty($log))
            {
                $sel3 = mysql_query("SELECT name FROM projekte WHERE ID = $log[project]");
                $proname = mysql_fetch_array($sel3);
                $proname = $proname[0];
                $log["proname"] = $proname;
                $log["proname"] = stripslashes($log["proname"]);
                $log["username"] = stripslashes($log["username"]);
                $log["name"] = stripslashes($log["name"]);
                array_push($mylog, $log);
            }
        }

        if (!empty($mylog))
        {
            return $mylog;
        }
        else
        {
            return false;
        }
    }

    /*
     * Return the log of the latest activities of a given user
     *
     * @param int $user User ID
     * @param int $limit Number of entries to return
     * @return array $mylog Latest entries
     */
    function getUserLog($user, $limit = 10)
    {
        $user = (int) $user;
        $limit = (int) $limit;

        $sel = mysql_query("SELECT * FROM log WHERE user = $user ORDER BY ID DESC LIMIT $limit");

        $mylog = array();
        while ($log = mysql_fetch_array($sel))
        {
            $log["username"] = stripslashes($log["username"]);
            $log["name"] = stripslashes($log["name"]);
            array_push($mylog, $log);
        }

        if (!empty($mylog))
        {
            return $mylog;
        }
        else
        {
            return false;
        }
    }

    /*
     * Return the latest log entries
     *
     * @param int $limit Number of entries to return
     * @return array $mylog Latest entries
     */
    function getLog($limit = 5)
    {
        $userid = $_SESSION["userid"];
        $limit = (int) $limit;

        $mylog = array();
        $sel3 = mysql_query("SELECT projekt FROM projekte_assigned WHERE user = $userid");
        $prstring = "";
        while ($upro = mysql_fetch_row($sel3))
        {
            $projekt = $upro[0];
            $prstring .= $projekt . ",";
        }

        $prstring = substr($prstring, 0, strlen($prstring)-1);

        if ($prstring)
        {
            $sel = mysql_query("SELECT * FROM log  WHERE project IN($prstring) OR project = 0 ORDER BY ID DESC LIMIT $limit");

            while ($log = mysql_fetch_array($sel))
            {
                $sel2 = mysql_query("SELECT name FROM projekte WHERE ID = $log[project]");
                $proname = mysql_fetch_array($sel2);
                $proname = $proname[0];
                $log["proname"] = $proname;
                $log["proname"] = stripslashes($log["proname"]);
                $log["username"] = stripslashes($log["username"]);
                $log["name"] = stripslashes($log["name"]);
                array_push($mylog, $log);
            }
        }

        if (!empty($mylog))
        {
            return $mylog;
        }
        else
        {
            return false;
        }
    }

    /*
     * Format the date of an entry
     *
     * @param int $log Log entry ID
     * @param string $format Wanted format
     * @return array $log Entry with the formatted time
     */
    function formatdate($log, $format = "d.m.y (H:i:s)")
    {
        $cou = 0;

        if ($log)
        {
            foreach($log as $thelog)
            {
                $datetime = date($format, $thelog[7]);
                $log[$cou]["datum"] = $datetime;
                $cou = $cou + 1;
            }
        }

        if (!empty($log))
        {
            return $log;
        }
        else
        {
            return false;
        }
    }
}
?>