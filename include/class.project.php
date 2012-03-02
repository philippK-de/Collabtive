<?php
/**
 * Die Klasse stellt Methoden bereit um Projekte zu bearbeiten
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name project
 * @package Collabtive
 * @version 0.6
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class project {
    private $mylog;

    /**
     * Konstruktor
     * Initialisiert den Eventlog
     */
    function __construct()
    {
        $this->mylog = new mylog;
    }

    /**
     * Add a project
     *
     * @param string $name Name des Projekts
     * @param string $desc Projektbeschreibung
     * @param string $end Date on which the project is due
     * @param int $assignme Assign yourself to the project
     * @return int $insid ID des neu angelegten Projekts
     */
    function add($name, $desc, $end, $budget, $assignme = 0)
    {
        $name = mysql_real_escape_string($name);
        $desc = mysql_real_escape_string($desc);
        $end = mysql_real_escape_string($end);
        $assignme = (int) $assignme;
        $budget = (float) $budget;

        if ($end > 0) {
            $end = strtotime($end);
        }
        $now = time();

        $ins1 = mysql_query("INSERT INTO projekte (`name`, `desc`, `end`, `start`, `status`, `budget`) VALUES ('$name','$desc','$end','$now',1,'$budget')");

        $insid = mysql_insert_id();
        if ($assignme == 1) {
            $uid = $_SESSION['userid'];
            $this->assign($uid, $insid);
        }
        if ($ins1) {
            mkdir(CL_ROOT . "/files/" . CL_CONFIG . "/$insid/", 0777);
            $this->mylog->add($name, 'projekt', 1, $insid);
            return $insid;
        } else {
            return false;
        }
    }

    /**
     * Imports a project from Basecamp into Collabtive
     *
     * @param string $name Name of the project
     * @param string $desc Description of the project
     * @param string $start Date on which the project was started
     * @param int $status Status of the project
     * @return int $insid ID des neu angelegten Projekts
     */
    function AddFromBasecamp($name, $desc, $start, $status = 1)
    {
        $name = mysql_real_escape_string($name);
        $desc = mysql_real_escape_string($desc);
        $start = mysql_real_escape_string($start);
        $id = (int) $id;
        $status = (int) $status;

        $start = strtotime($start);
        $tod = date("d.m.Y");
        $now = strtotime($tod . " +1week");

        $ins1 = mysql_query("INSERT INTO projekte (`name`, `desc`,`end`, `start`, `status`) VALUES ('$name','$desc','$now','$start','$status')");

        $insid = mysql_insert_id();

        if ($ins1) {
            mkdir(CL_ROOT . "/files/" . CL_CONFIG . "/$insid/", 0777);
            $this->mylog->add($name, 'projekt', 1, $insid);
            return $insid;
        } else {
            return false;
        }
    }

    /**
     * Bearbeitet ein Projekt
     *
     * @param int $id Eindeutige Projektnummer
     * @param string $name Name des Projekts
     * @param string $desc Beschreibungstext
     * @param string $end Date on which the project is due
     * @return bool
     */
    function edit($id, $name, $desc, $end, $budget)
    {
        $id = mysql_real_escape_string($id);
        $name = mysql_real_escape_string($name);
        $desc = mysql_real_escape_string($desc);
        $end = mysql_real_escape_string($end);
        $end = strtotime($end);
        $id = (int) $id;
        $budget = (float) $budget;

        $upd = mysql_query("UPDATE projekte SET name='$name',`desc`='$desc',`end`='$end',budget=$budget WHERE ID = $id");

        if ($upd) {
            $this->mylog->add($name, 'projekt' , 2, $id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Deletes a project including everything else that was assigned to it (e.g. Milestones, tasks, timetracker entries)
     *
     * @param int $id Project ID
     * @return bool
     */
    function del($id)
    {
        $userid = $_SESSION["userid"];
        $id = mysql_real_escape_string($id);
        $id = (int) $id;
        // Delete assignments of tasks of this project to users
        $task = new task();
        $tasks = $task->getProjectTasks($id);
        if (!empty($tasks)) {
            foreach ($tasks as $tas) {
                $del_taskassign = mysql_query("DELETE FROM tasks_assigned WHERE task = $tas[ID]");
            }
        }
        // Delete files and the assignments of these files to the messages they were attached to
        $fil = new datei();
        $files = $fil->getProjectFiles($id, 1000000);
        if (!empty($files)) {
            foreach ($files as $file) {
                $del_files = $fil->loeschen($file[ID]);
            }
        }

        $del_messages = mysql_query("DELETE FROM messages WHERE project = $id");
        $del_milestones = mysql_query("DELETE FROM milestones WHERE project = $id");
        $del_projectassignments = mysql_query("DELETE FROM projekte_assigned WHERE projekt = $id");
        $del_tasklists = mysql_query("DELETE FROM tasklist WHERE project = $id");
        $del_tasks = mysql_query("DELETE FROM tasks WHERE project = $id");
        $del_timetracker = mysql_query("DELETE FROM timetracker WHERE project = $id");

        $del_logentries = mysql_query("DELETE FROM log WHERE project = $id");
        $del = mysql_query("DELETE FROM projekte WHERE ID = $id");

        delete_directory(CL_ROOT . "/files/" . CL_CONFIG . "/$id");
        if ($del) {
            $this->mylog->add($userid, 'projekt', 3, $id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Mark a project as "active / open"
     *
     * @param int $id Eindeutige Projektnummer
     * @return bool
     */
    function open($id)
    {
        $id = mysql_real_escape_string($id);
        $id = (int) $id;

        $upd = mysql_query("UPDATE projekte SET status=1 WHERE ID = $id");
        if ($upd) {
            $nam = mysql_query("SELECT name FROM projekte WHERE ID = $id");
            $nam = mysql_fetch_row($nam);
            $nam = $nam[0];
            $this->mylog->add($nam, 'projekt', 4, $id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Marks a project, its tasks, tasklists and milestones as "finished / closed"
     *
     * @param int $id Eindeutige Projektnummer
     * @return bool
     */
    function close($id)
    {
        $id = mysql_real_escape_string($id);
        $id = (int) $id;

        $mile = new milestone();
        $milestones = $mile->getAllProjectMilestones($id, 100000);
        if (!empty($milestones)) {
            foreach ($milestones as $miles) {
                $close_milestones = mysql_query("UPDATE milestones SET status = 0 WHERE ID = $miles[ID]");
            }
        }

        $task = new task();
        $tasks = $task->getProjectTasks($id);
        if (!empty($tasks)) {
            foreach ($tasks as $tas) {
                $close_tasks = mysql_query("UPDATE tasks SET status = 0 WHERE ID = $tas[ID]");
            }
        }

        $tasklist = new tasklist();
        $tasklists = $tasklist->getProjectTasklists($id);
        if (!empty($tasklists)) {
            foreach ($tasklists as $tl) {
                $close_tasklists = mysql_query("UPDATE tasklist SET status = 0 WHERE ID = $tl[ID]");
            }
        }

        $upd = mysql_query("UPDATE projekte SET status=0 WHERE ID = $id");
        if ($upd) {
            $nam = mysql_query("SELECT name FROM projekte WHERE ID = $id");
            $nam = mysql_fetch_row($nam);
            $nam = $nam[0];
            $this->mylog->add($nam, 'projekt', 5, $id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Weist ein Projekt einem bestimmten Mitglied zu
     *
     * @param int $user Eindeutige Mitgliedsnummer
     * @param int $id Eindeutige Projektnummer
     * @return bool
     */
    function assign($user, $id)
    {
        $user = mysql_real_escape_string($user);
        $id = mysql_real_escape_string($id);
        $user = (int) $user;
        $id = (int) $id;

        $ins = mysql_query("INSERT INTO projekte_assigned (user,projekt) VALUES ($user,$id)");
        if ($ins) {
			$userObj = new user();
			$user = $userObj->getProfile($user);
			$this->mylog->add($user["name"], 'user', 6, $id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Entfernt ein Projekt aus der Zuweisung an ein bestimmtes Mitglied
     *
     * @param int $user User ID
     * @param int $id Project ID
     * @return bool
     */
    function deassign($user, $id)
    {
        $user = mysql_real_escape_string($user);
        $id = mysql_real_escape_string($id);
        $user = (int) $user;
        $id = (int) $id;

        $sql = "DELETE FROM projekte_assigned WHERE user = $user AND projekt = $id";

        $milestone = new milestone();
        $donemiles = $milestone->getDoneProjectMilestones($id);
        if (!empty($donemiles)) {
            foreach ($donemiles as $dm) {
                $sql1 = mysql_query("DELETE FROM milestones_assigned WHERE user = $user AND milestone = $dm[ID]");
            }
        }
        $openmiles = $milestone->getAllProjectMilestones($id, 100000);
        if (!empty($openmiles)) {
            foreach ($openmiles as $om) {
                $sql2 = mysql_query("DELETE FROM milestones_assigned WHERE user = $user AND milestone = $om[ID]");
            }
        }

        $task = new task();
        $tasks = $task->getProjectTasks($id);
        if (!empty($tasks)) {
            foreach ($tasks as $t) {
                $sql3 = mysql_query("DELETE FROM tasks_assigned WHERE user = $user AND task = $t[ID]");
            }
        }

        $del = mysql_query($sql);
        if ($del) {
			$userObj = new user();
			$user = $userObj->getProfile($user);
			$this->mylog->add($user["name"], 'user', 7, $id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gibt alle Daten eines Projekts aus
     *
     * @param int $id Eindeutige Projektnummer
     * @param int $status
     * @return array $project Projektdaten
     */
    function getProject($id)
    {
        $id = (int) $id;

        $sel = mysql_query("SELECT * FROM projekte WHERE ID = $id");
        $project = mysql_fetch_array($sel, MYSQL_ASSOC);

        if (!empty($project)) {
            if ($project["end"]) {
                $daysleft = $this->getDaysLeft($project["end"]);
                $project["daysleft"] = $daysleft;
                $endstring = date("d.m.Y", $project["end"]);
                $project["endstring"] = $endstring;
            } else {
                $project["daysleft"] = "";
            }

            $startstring = date(CL_DATEFORMAT, $project["start"]);
            $project["startstring"] = $startstring;

            $project["name"] = stripslashes($project["name"]);
            $project["desc"] = stripslashes($project["desc"]);
            $project["done"] = $this->getProgress($project["ID"]);

            return $project;
        } else {
            return false;
        }
    }

    /**
     * Listet die aktuellsten Projekte auf
     *
     * @param int $status Bearbeitungsstatus der Projekte (1 = offenes Projekt)
     * @param int $lim Anzahl der anzuzeigenden Projekte
     * @return array $projekte Active projects
     */
    function getProjects($status = 1, $lim = 10)
    {
        $status = mysql_real_escape_string($status);
        $lim = mysql_real_escape_string($lim);
        $status = (int) $status;
        $lim = (int) $lim;

        $projekte = array();
        $sel = mysql_query("SELECT ID FROM projekte WHERE `status`=$status ORDER BY end ASC LIMIT $lim");

        while ($projekt = mysql_fetch_array($sel)) {
            $project = $this->getProject($projekt["ID"]);
            array_push($projekte, $project);
        }

        if (!empty($projekte)) {
            return $projekte;
        } else {
            return false;
        }
    }

    /**
     * Lists all projects assigned to a given member ordered by due date ascending
     *
     * @param int $user Eindeutige Mitgliedsnummer
     * @param int $status Bearbeitungsstatus von Projekten (1 = offenes Projekt)
     * @return array $myprojekte Projekte des Mitglieds
     */
    function getMyProjects($user, $status = 1)
    {
        $user = mysql_real_escape_string($user);
        $status = mysql_real_escape_string($status);
        $user = (int) $user;
        $status = (int) $status;

        $myprojekte = array();
        $sel = mysql_query("SELECT projekt FROM projekte_assigned WHERE user = $user ORDER BY ID ASC");

        while ($projs = mysql_fetch_row($sel)) {
            $projekt = mysql_fetch_array(mysql_query("SELECT ID FROM projekte WHERE ID = $projs[0] AND status=$status"), MYSQL_ASSOC);
            if ($projekt) {
                $project = $this->getProject($projekt["ID"]);
                array_push($myprojekte, $project);
            }
        }

        if (!empty($myprojekte)) {
			// Sort projects by due date ascending
			$date = array();
			foreach ($myprojekte as $key => $row) {
				$date[$key] = $row['end'];
			}
			array_multisort($date, SORT_ASC, $myprojekte);
			
            return $myprojekte;
        } else {
            return false;
        }
    }

    /**
     * Listet alle IDs der Projekte eines Mitglieds auf
     *
     * @param int $user Eindeutige Mitgliedsnummer
     * @return array $myprojekte Projekt-Nummern
     */
    function getMyProjectIds($user)
    {
        $user = mysql_real_escape_string($user);
        $user = (int) $user;

        $myprojekte = array();
        $sel = mysql_query("SELECT projekt FROM projekte_assigned WHERE user = $user ORDER BY end ASC");
        if ($sel) {
            while ($projs = mysql_fetch_row($sel)) {
                $sel2 = mysql_query("SELECT ID FROM projekte WHERE ID = $projs[0]");
                $projekt = mysql_fetch_array($sel2);
                if ($projekt) {
                    array_push($myprojekte, $projekt);
                }
            }
        }
        if (!empty($myprojekte)) {
            return $myprojekte;
        } else {
            return false;
        }
    }

    /**
     * Listet alle einem bestimmen Projekt zugewiesenen Mitglieder auf
     *
     * @param int $project Eindeutige Projektnummer
     * @param int $lim Maximum auszugebender Mitglieder
     * @return array $members Projektmitglieder
     */
    function getProjectMembers($project, $lim = 10, $paginate = true)
    {
        $project = (int) $project;
        $lim = (int) $lim;
        $project = (int) $project;
        $lim = (int) $lim;

        $members = array();

        if ($paginate) {
            $num = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM projekte_assigned WHERE projekt = $project"));
            $num = $num[0];
            $lim = (int)$lim;
            SmartyPaginate::connect();
            // set items per page
            SmartyPaginate::setLimit($lim);
            SmartyPaginate::setTotal($num);

            $start = SmartyPaginate::getCurrentIndex();
            $lim = SmartyPaginate::getLimit();
        } else {
            $start = 0;
        }
        $sel1 = mysql_query("SELECT user FROM projekte_assigned WHERE projekt = $project LIMIT $start,$lim");

        $usr = new user();
        while ($user = mysql_fetch_array($sel1)) {
            $theuser = $usr->getProfile($user[0]);
            array_push($members, $theuser);
        }

        if (!empty($members)) {
            return $members;
        } else {
            return false;
        }
    }

    function countMembers($project)
    {
        $project = (int) $project;
        $num = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM projekte_assigned WHERE projekt = $project"));
        return $num[0];
    }

    /**
     * Progressmeter
     *
     * @param int $project Project ID
     * @return array $done Percent of finished tasks
     */
    function getProgress($project)
    {
        $project = mysql_real_escape_string($project);
        $project = (int) $project;

        $otasks = mysql_query("SELECT COUNT(*) FROM tasks WHERE project = $project AND status = 1");
        $otasks = mysql_fetch_row($otasks);
        $otasks = $otasks[0];

        $clotasks = mysql_query("SELECT COUNT(*) FROM tasks WHERE project = $project AND status = 0");
        $clotasks = mysql_fetch_row($clotasks);
        $clotasks = $clotasks[0];

        $totaltasks = $otasks + $clotasks;
        if ($totaltasks > 0 and $clotasks > 0) {
            $done = $clotasks / $totaltasks * 100;
            $done = round($done);
        } else {
            $done = 0;
        }
        return $done;
    }

    function getProjectFolders($project)
    {
        $project = (int) $project;
        $sel = mysql_query("SELECT * FROM projectfolders WHERE project = $project");

        $folders = array();
        while ($folder = mysql_fetch_array($sel)) {
            array_push($folders, $folder);
        }

        if (!empty($folders)) {
            return $folders;
        } else {
            return false;
        }
    }
    /**
     * Gibt die verbleibenden Tage von einem gegeben Zeitpunkt bis heute zur?ck
     *
     * @param int $end Zu vergleichender Zeitpunkt
     * @return int Verbleibende volle Tage
     */
    private function getDaysLeft($end)
    {
        $tod = date("d.m.Y");
        $start = strtotime($tod);
        $diff = $end - $start;
        return floor($diff / 86400);
    }

    /**
     * Copy a project
     * by: Daniel Tlach <danaketh@gmail.com>,
     * Philipp Kiszka <info@o-dyn.de>
     *
     * @param int $id ID of project to copy
     * @return int $insid New project's ID
     */
    function makecopy($id)
    {
        // copy project
        $q = mysql_query("INSERT INTO projekte (`name`, `desc`, `end`, `start`, `status`, `budget`) SELECT `name`, `desc`, `end`, `start`, `status`, `budget` FROM projekte WHERE ID = " . (int)$id);

        $insid = mysql_insert_id();
        $uid = $_SESSION['userid'];
        $this->assign($uid, $insid);

        $milesobj = new milestone();
        $objtasklist = new tasklist();
        $objtask = new task();

        if ($q) {
            $pname = $this->getProject($insid);
            $name = $pname["name"] . " Copy";
            mysql_query("UPDATE projekte SET `name` = '$name' WHERE ID = " . $insid . " LIMIT 1");
            // now copy the milestones
            $miles = $milesobj->getAllProjectMilestones($id);
            if (!empty($miles)) {
                // go through the milestones
                foreach ($miles as $ms) {
                    // copy milestone
                    $msid = $milesobj->add($insid, $ms["name"] , $ms["desc"] , $ms["end"] , 1);
                    // get all tasklists for milestone
                    $qb = mysql_query("SELECT * FROM tasklist WHERE project = $id AND milestone = $ms[ID]");
                    if ($qb) {
                        // go through the tasklists
                        while ($tl = mysql_fetch_array($qb)) {
                            // copy tasklist
                            $tlid = $objtasklist->add_liste($insid, $tl["name"] , $tl["desc"], 0, $msid);
                            // get tasks for the tasklist
                            $tasks = $objtasklist->getTasksFromList($tl["ID"]);
                            if (!empty($tasks)) {
                                foreach ($tasks as $task) {
                                    $taskobj->add($task["end"], $task["title"] , $task["text"] , $tlid , $uid , $insid);
                                } // tasks END
                            }
                        } // tasklists END
                    }
                } // milestones END
            }
            // get all tasklists and tasks that do not belong to a milestone
            $qb = mysql_query("SELECT * FROM tasklist WHERE project = $id AND milestone = 0");
            if ($qb) {
                // go through the tasklists
                while ($tl = mysql_fetch_array($qb)) {
                    // copy tasklist
                    $tlid = $objtasklist->add_liste($insid, $tl["name"] , $tl["desc"], 0, $msid);
                    // get tasks for the tasklist
                    $tasks = $objtasklist->getTasksFromList($tl["ID"]);
                    if (!empty($tasks)) {
                        foreach ($tasks as $task) {
                            $taskobj->add($task["end"], $task["title"] , $task["text"] , $tlid , $uid , $insid);
                        } // tasks END
                    }
                } // tasklists END
            }

            mkdir(CL_ROOT . "/files/" . CL_CONFIG . "/$insid/", 0777);
            $this->mylog->add($name, 'projekt', 1, $insid);
            return $insid;
        } else {
            return false;
        }
    }
}

?>