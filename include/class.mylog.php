<?php
/*
 * This class provides methods to realize the logging of different activities
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name mylog
 * @version 1.0
 * @package Collabtive
 * @link http://collabtive.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class mylog {
    /*
    * Constructor
    */
    function __construct()
    {
        $this->userid = getArrayVal($_SESSION, "userid");
        $this->uname = getArrayVal($_SESSION, "username");
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
        global $conn;
        $user = $this->userid;
        $uname = $this->uname;

        $action = (int) $action;
        $project = (int) $project;

        $now = time();

        $insStmt = $conn->prepare("INSERT INTO log (user,username,name,type,action,project,datum) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $ins = $insStmt->execute(array($user, $uname, $name, $type, $action, $project, $now));
        if ($ins) {
            $insid = $conn->lastInsertId();
            return $insid;
        } else {
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
        global $conn;
        $id = (int) $id;

        $delStmt = $conn->prepare("DELETE FROM log WHERE ID = ?");
        $del = $delStmt->execute($id);

		if ($del) {
            return true;
        } else {
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
    function getProjectLog($project, $limit = 25, $offset = 0)
    {
        global $conn;
        $project = (int) $project;
        $limit = (int) $limit;
        $offset = (int) $offset;

        $sel = $conn->prepare("SELECT COUNT(*) FROM log WHERE project = ? ");
    	$sel->execute(array($project));
        $num = $sel->fetch();
        $num = $num[0];
        if ($num > 200) {
            $num = 200;
        }

        $sel2 = $conn->prepare("SELECT * FROM log WHERE project = ? ORDER BY ID DESC LIMIT $limit OFFSET $offset");
    	$sel2->execute(array($project));

        $mylog = array();
        while ($log = $sel2->fetch()) {
            if (!empty($log)) {
                $sel3 = $conn->query("SELECT name FROM projekte WHERE ID = $log[project]");
                $proname = $sel3->fetch();
                $proname = $proname[0];
                $log["proname"] = $proname;
                //$log["proname"] = stripslashes($log["proname"]);
                //$log["username"] = stripslashes($log["username"]);
                $log["name"] = stripslashes($log["name"]);
                array_push($mylog, $log);
            }
        }

        if (!empty($mylog)) {
            return $mylog;
        } else {
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
    function getUserLog($user, $limit = 25)
    {
        global $conn;
        $user = (int) $user;
        $limit = (int) $limit;

        $sel = $conn->prepare("SELECT * FROM log WHERE user = ? ORDER BY ID DESC LIMIT ?");
		$sel->execute(array($user,$limit));

        $mylog = array();
        while ($log = $sel->fetch()) {
            //$log["username"] = stripslashes($log["username"]);
            //$log["name"] = stripslashes($log["name"]);
            array_push($mylog, $log);
        }

        if (!empty($mylog)) {
            return $mylog;
        } else {
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
        global $conn;
        $userid = $_SESSION["userid"];
        $limit = (int) $limit;

        $mylog = array();
        $sel3 = $conn->prepare("SELECT projekt FROM projekte_assigned WHERE user = ?");
    	$sel3->execute(array($userid));

        $prstring = "";
        while ($upro = $sel3->fetch()) {
            $projekt = $upro[0];
            $prstring .= $projekt . ",";
        }

        $prstring = substr($prstring, 0, strlen($prstring)-1);

        if ($prstring) {
            $sel = $conn->query("SELECT * FROM log  WHERE project IN($prstring) OR project = 0 ORDER BY ID DESC LIMIT $limit");

            while ($log = $sel->fetch()) {
                $sel2 = $conn->query("SELECT name FROM projekte WHERE ID = $log[project]");
                $proname = $sel2->fetch();
                $proname = $proname[0];
                $log["proname"] = $proname;
                //$log["proname"] = stripslashes($log["proname"]);
                //$log["username"] = stripslashes($log["username"]);
                //$log["name"] = stripslashes($log["name"]);
                array_push($mylog, $log);
            }
        }

        if (!empty($mylog)) {
            return $mylog;
        } else {
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
    function formatdate($log, $format = "")
    {
        if (!$format) {
            $format = CL_DATEFORMAT . " (H:i:s)";
        }
        $cou = 0;

        if ($log) {
            foreach($log as $thelog) {
                $datetime = date($format, $thelog[7]);
                $log[$cou]["datum"] = $datetime;
                $cou = $cou + 1;
            }
        }

        if (!empty($log)) {
            return $log;
        } else {
            return false;
        }
    }
}

?>