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
     * @param int $customerID
     * @param int $assignme Assign yourself to the project
     * @return int $insid ID des neu angelegten Projekts
     */
    function add($name, $desc, $end, $budget, $assignme = 0)
    {
        global $conn;

        if ($end > 0) {
            $end = strtotime($end);
        }
        $now = time();
        
        $name = htmlspecialchars($name);

        $ins1Stmt = $conn->prepare("INSERT INTO projekte (`name`, `desc`, `end`, `start`, `status`, `budget`) VALUES (?,?,?,?,1,?)");
        $ins1 = $ins1Stmt->execute(array($name, $desc, $end, $now, (float) $budget));

        $insid = $conn->lastInsertId();
        if ((int) $assignme == 1) {
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
        global $conn;
        $id = (int) $id;
        $status = (int) $status;

        $start = strtotime($start);
        $tod = date("d.m.Y");
        $now = strtotime($tod . " +1week");

        $ins1Stmt = $conn->prepare("INSERT INTO projekte (`name`, `desc`,`end`, `start`, `status`) VALUES (?, ?, ?, ?, ?)");
        $ins1 = $ins1Stmt->execute(array($name, $desc, $now, $start, $status));

        $insid = $conn->lastInsertId();

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
     * @param int $id Project ID
     * @param string $name Name of the project
     * @param string $desc Description of the project
     * @param string $end Date on which the project is due
     * @param int $customerID id of a customer
     * @return bool
     */
    function edit($id, $name, $desc, $end, $budget)
    {
        global $conn;
        $end = strtotime($end);
        $id = (int) $id;
        $budget = (float) $budget;
        $name = htmlspecialchars($name);

        $updStmt = $conn->prepare("UPDATE projekte SET name=?,`desc`=?,`end`=?,budget=? WHERE ID = ?");
        $upd = $updStmt->execute(array($name, $desc, $end, $budget, $id));

        if ($upd) {
            $this->mylog->add($name, 'projekt' , 2, $id);
            return true;
        } else
            return false;
    }

    /**
     * Deletes a project including everything else that was assigned to it (e.g. Milestones, tasks, timetracker entries)
     *
     * @param int $id Project ID
     * @return bool
     */
    function del($id)
    {
        global $conn;
        $userid = $_SESSION["userid"];
        $id = (int) $id;
        // Delete assignments of tasks of this project to users
        $task = new task();
        $tasks = $task->getProjectTasks($id);
        if (!empty($tasks)) {
            foreach ($tasks as $tas) {
                $del_taskassign = $conn->query("DELETE FROM tasks_assigned WHERE task = $tas[ID]");
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

        $del_messages = $conn->query("DELETE FROM messages WHERE project = $id");
        $del_milestones = $conn->query("DELETE FROM milestones WHERE project = $id");
        $del_projectassignments = $conn->query("DELETE FROM projekte_assigned WHERE projekt = $id");
        $del_tasklists = $conn->query("DELETE FROM tasklist WHERE project = $id");
        $del_tasks = $conn->query("DELETE FROM tasks WHERE project = $id");
        $del_timetracker = $conn->query("DELETE FROM timetracker WHERE project = $id");

        $del_logentries = $conn->query("DELETE FROM log WHERE project = $id");
        $del = $conn->query("DELETE FROM projekte WHERE ID = $id");

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
        global $conn;
        $id = (int) $id;

        $upd = $conn->query("UPDATE projekte SET status=1 WHERE ID = $id");
        if ($upd) {
            $nam = $conn->query("SELECT name FROM projekte WHERE ID = $id")->fetch();
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
        global $conn;
        $id = (int) $id;

        $mile = new milestone();
        $milestones = $mile->getAllProjectMilestones($id, 100000);
        if (!empty($milestones)) {
            foreach ($milestones as $miles) {
                $close_milestones = $conn->query("UPDATE milestones SET status = 0 WHERE ID = $miles[ID]");
            }
        }

        $task = new task();
        $tasks = $task->getProjectTasks($id);
        if (!empty($tasks)) {
            foreach ($tasks as $tas) {
                $close_tasks = $conn->query("UPDATE tasks SET status = 0 WHERE ID = $tas[ID]");
            }
        }

        $tasklist = new tasklist();
        $tasklists = $tasklist->getProjectTasklists($id);
        if (!empty($tasklists)) {
            foreach ($tasklists as $tl) {
                $close_tasklists = $conn->query("UPDATE tasklist SET status = 0 WHERE ID = $tl[ID]");
            }
        }

        $upd = $conn->query("UPDATE projekte SET status=0 WHERE ID = $id");
        if ($upd) {
            $nam = $conn->query("SELECT name FROM projekte WHERE ID = $id")->fetch();
            $nam = $nam[0];
            $this->mylog->add($nam, 'projekt', 5, $id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Asssigns a user to a project
     *
     * @param int $user ID of user to be assigned
     * @param int $id ID of project to assign to
     * @return bool
     */
    function assign($user, $id)
    {
        global $conn;

        $insStmt = $conn->prepare("INSERT INTO projekte_assigned (user,projekt) VALUES (?,?)");
        $ins = $insStmt->execute(array((int) $user, (int) $id));
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
     * Removes a user from a project
     *
     * @param int $user User ID of user to remove
     * @param int $id Project ID of project to remove from
     * @return bool
     */
    function deassign($user, $id)
    {
        global $conn;

        $sqlStmt = $conn->prepare("DELETE FROM projekte_assigned WHERE user = ? AND projekt = ?");

        $milestone = new milestone();
        // Delete the users assignments to closed milestones
        $donemiles = $milestone->getDoneProjectMilestones($id);
        if (!empty($donemiles)) {
            $sql1Stmt = $conn->prepare("DELETE FROM milestones_assigned WHERE user = ? AND milestone = ?");
            foreach ($donemiles as $dm) {
                $sql1 = $sql1Stmt->execute(array((int) $user, $dm['ID']));
            }
        }
        // Delete the users assignments to open milestones
        $openmiles = $milestone->getAllProjectMilestones($id, 100000);
        if (!empty($openmiles)) {
            $sql2Stmt = $conn->prepare("DELETE FROM milestones_assigned WHERE user = ? AND milestone = ?");
            foreach ($openmiles as $om) {
                $sql2 = $sql2Stmt->execute(array((int) $user, $om['ID']));
            }
        }

        $task = new task();
        $tasks = $task->getProjectTasks($id);
        // Delete tasks assignments of the user
        if (!empty($tasks)) {
            $sql3Stmt = $conn->prepare("DELETE FROM tasks_assigned WHERE user = ? AND task = ?");
            foreach ($tasks as $t) {
                $sql3 = $sql3Stmt->execute(array((int) $user, $t['ID']));
            }
        }
        // Finally remove the user from the project
        $del = $sqlStmt->execute(array((int) $user, (int) $id));
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
        global $conn;
        $id = (int) $id;

        $sel = $conn->prepare("SELECT * FROM projekte WHERE ID = ?");
        $selStmt = $sel->execute(array($id));

        $project = $sel->fetch();

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
        global $conn;

        $status = (int) $status;
        $lim = (int) $lim;

        $projekte = array();

        $sel = $conn->prepare("SELECT `ID` FROM projekte WHERE `status`= ? ORDER BY `end` ASC LIMIT $lim");
        $selStmt = $sel->execute(array($status));

        while ($projekt = $sel->fetch()) {
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
        global $conn;

        $myprojekte = array();
        $user = (int) $user;

        $sel = $conn->prepare("SELECT projekt FROM projekte_assigned WHERE user = ? ORDER BY ID ASC");
        $selStmt = $sel->execute(array($user));

        while ($projs = $sel->fetch()) {
            $projekt = $conn->query("SELECT ID FROM projekte WHERE ID = " . $projs[0] . " AND status={$conn->quote((int) $status)}")->fetch();
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
     * Lists all project IDs assigned to a user
     *
     * @param int $user ID of the user
     * @return array $myprojekte Project IDs for user
     */
    function getMyProjectIds($user)
    {
        global $conn;

        $myprojekte = array();
        $sel = $conn->prepare("SELECT projekt FROM projekte_assigned WHERE user = ? ORDER BY end ASC");
        $selStmt = $sel->execute(array($user));

        if ($sel) {
            while ($projs = $sel->fetch()) {
                $sel2 = $conn->query("SELECT ID FROM projekte WHERE ID = " . $projs[0]);
                $projekt = $sel2->fetch();
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
     * Lists all the users in a project
     *
     * @param int $project Eindeutige Projektnummer
     * @param int $lim Maximum auszugebender Mitglieder
     * @return array $members Projektmitglieder
     */
    function getProjectMembers($project, $lim = 10, $paginate = true)
    {
        global $conn;
        $project = (int) $project;
        $lim = (int) $lim;
        $project = (int) $project;
        $lim = (int) $lim;

        $members = array();

        if ($paginate) {
            $num = $conn->query("SELECT COUNT(*) FROM projekte_assigned WHERE projekt = $project")->fetch();
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
        $sel1 = $conn->query("SELECT user FROM projekte_assigned WHERE projekt = $project LIMIT $start,$lim");

        $usr = new user();
        while ($user = $sel1->fetch()) {
            $theuser = $usr->getProfile($user[0]);
            array_push($members, $theuser);
        }

        if (!empty($members)) {
            return $members;
        } else {
            return false;
        }
    }

    /**
     * Count members in a project
     *
     * @param int $project Project ID
     * @return int $num Number of members
     */
    function countMembers($project)
    {
        global $conn;
        $project = (int) $project;
        $num = $conn->query("SELECT COUNT(*) FROM projekte_assigned WHERE projekt = $project")->fetch();
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
        global $conn;
        $project = (int) $project;

        $otasks = $conn->query("SELECT COUNT(*) FROM tasks WHERE project = $project AND status = 1")->fetch();
        $otasks = $otasks[0];

        $clotasks = $conn->query("SELECT COUNT(*) FROM tasks WHERE project = $project AND status = 0")->fetch();
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

    /**
     * Liste the folders in a project
     *
     * @param int $project Project ID
     * @return array $folders Folders in the project
     */
    function getProjectFolders($project)
    {
        global $conn;
        $project = (int) $project;

        $sel = $conn->prepare("SELECT * FROM projectfolders WHERE project = ?");
        $selStmt = $sel->execute(array($project));

        $folders = array();
        while ($folder = $sel->fetch()) {
            array_push($folders, $folder);
        }

        if (!empty($folders)) {
            return $folders;
        } else {
            return false;
        }
    }
    /**
     * Get days until a specified date
     *
     * @param int $end End date to compare with
     * @return int Remaining days
     */
    private function getDaysLeft($end)
    {
        $tod = date("d.m.Y");
        $start = strtotime($tod);
        $diff = $end - $start;
        return floor($diff / 86400);
    }
}

?>