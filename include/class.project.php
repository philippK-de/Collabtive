<?php

/**
 * Die Klasse stellt Methoden bereit um Projekte zu bearbeiten
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name project
 * @package Collabtive
 * @version 3
 * @link http://collabtive.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class project
{
    /**
     * Add a project
     *
     * @param string $name Name des Projekts
     * @param string $desc Projektbeschreibung
     * @param string $end Date on which the project is due
     * @param float $budget Budget for this project
     * @param int $assignme Assign yourself to the project
     * @return int $insid ID des neu angelegten Projekts
     */
    function add($name, $desc, $end, $budget, $assignme = 0)
    {
        global $conn, $mylog;

        if ($end > 0) {
            $end = strtotime($end);
        }

        $now = time();

        $ins1Stmt = $conn->prepare("INSERT INTO projekte (`name`, `desc`, `end`, `start`, `status`, `budget`) VALUES (?,?,?,?,1,?)");
        $ins1 = $ins1Stmt->execute(array($name, $desc, $end, $now, (float)$budget));

        $insid = $conn->lastInsertId();
        if ((int)$assignme == 1) {
            $uid = $_SESSION['userid'];
            $this->assign($uid, $insid);
        }
        if ($ins1) {
            mkdir(CL_ROOT . "/files/" . CL_CONFIG . "/$insid/", 0777);
            $mylog->add($name, 'projekt', 1, $insid);
            return $insid;
        } else {
            return false;
        }
    }

    function addProjectFromExcel($inputFileName)
    {
        include("./include/PHPExcel/IOFactory.php");

        $objReader = new PHPExcel_Reader_Excel2007();

        $objPHPExcel = $objReader->load($inputFileName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        $limiter = "#";

        $projectObj = new project();
        $userObj = new user();
        $milestoneObj = new milestone();
        $tasklistObj = new tasklist();
        $taskObj = new task();
        $companyObj = new company();

        $projectname = $sheetData[2]["A"];
        $projectdescription = $sheetData[3]["A"];
        $projectdue = $sheetData[4]["A"];
        $projectbudget = $sheetData[5]["A"];
        $projectcustomer = $sheetData[6]["A"];
        $projectpriority = $sheetData[7]["A"];

        $projectId = $projectObj->add($projectname, $projectdescription, $projectdue, $projectbudget, $projectpriority);

        $customer = $companyObj->getCompanyByName($projectcustomer);
        if ($customer) {
            $companyObj->assign($customer["ID"], $projectId);
        }

        // USERS
        for ($i = 1; $i < count($sheetData); $i++) {
            $username = $sheetData[$i]["B"];

            if ($username) {
                $user = $userObj->getUserByName($username);

                if ($user) {
                    $projectObj->assign($user["ID"], $projectId);
                }
            } else {
                break;
            }
        }
        // MILESTONES
        for ($i = 1; $i < count($sheetData); $i++) {
            $milestone = explode($limiter, $sheetData[$i]["C"]);

            if ($milestone) {
                $milestoneObj->add($projectId, $milestone[0], $milestone[1], $milestone[2], $milestone[3]);
            } else {
                break;
            }
        }
        // TASK LISTS
        for ($i = 1; $i < count($sheetData); $i++) {
            $tasklist = explode($limiter, $sheetData[$i]["D"]);

            if ($tasklist) {
                if ($tasklist[3]) {
                    $milestone = $milestoneObj->getMilestoneByName($tasklist[3]);
                    if ($milestone) {
                        $tasklistObj->add_liste($projectId, $tasklist[0], $tasklist[1], $tasklist[2], 0, $milestone["ID"]);
                    }
                } else {
                    $tasklistObj->add_liste($projectId, $tasklist[0], $tasklist[1], $tasklist[2]);
                }
            } else {
                break;
            }
        }
        // TASKS
        for ($i = 1; $i < count($sheetData); $i++) {
            $task = explode($limiter, $sheetData[$i]["E"]);

            if ($task) {
                $tasklist = $tasklistObj->getTasklistByName($task[5]);
                {
                    $taskid = $taskObj->add($task[2], $task[3], $task[0], $task[4], $tasklist["ID"], $projectId, $task[6], $task[7]);
                }
                $user = $userObj->getUserByName($task[1]);
                if ($taskid) {
                    if ($user) {
                        $taskObj->assign($taskid, $user["ID"]);
                    }
                }
            } else {
                break;
            }
        }

        return $projectId;
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
        global $conn, $mylog;
        $end = strtotime($end);
        $id = (int)$id;
        $budget = (float)$budget;
        $name = htmlspecialchars($name);

        $updStmt = $conn->prepare("UPDATE projekte SET name=?,`desc`=?,`end`=?,budget=? WHERE ID = ?");
        $upd = $updStmt->execute(array($name, $desc, $end, $budget, $id));

        if ($upd) {
            $mylog->add($name, 'projekt', 2, $id);
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
        global $conn, $mylog;
        $userid = $_SESSION["userid"];
        $id = (int)$id;
        // Delete assignments of tasks of this project to users
        $task = new task();
        $tasks = $task->getProjectTasks($id);
        if (!empty($tasks)) {
            foreach ($tasks as $tas) {
                $task->del($tas["ID"]);
            }
        }
        // Delete files and the assignments of these files to the messages they were attached to
        $fileObject = new datei();
        $files = $fileObject->getProjectFiles($id, 1000000);
        if (!empty($files)) {
            foreach ($files as $file) {
                $fileObject->loeschen($file["ID"]);
            }
        }

        $del_messages = $conn->prepare("DELETE FROM messages WHERE project = ?");
        $del_messages->execute(array($id));

        $del_milestones = $conn->prepare("DELETE FROM milestones WHERE project = ?");
        $del_milestones->execute(array($id));

        $del_projectassignments = $conn->prepare("DELETE FROM projekte_assigned WHERE projekt = ?");
        $del_projectassignments->execute(array($id));

        $del_tasklists = $conn->prepare("DELETE FROM tasklist WHERE project = ?");
        $del_tasklists->execute(array($id));

        $del_tasks = $conn->prepare("DELETE FROM tasks WHERE project = ?");
        $del_tasks->execute(array($id));

        $del_timetracker = $conn->prepare("DELETE FROM timetracker WHERE project = ?");
        $del_timetracker->execute(array($id));

        $del_customer = $conn->prepare("DELETE FROM customers_assigned WHERE project = ?");
        $del_customer->execute(array($id));

        $del_logentries = $conn->prepare("DELETE FROM log WHERE project = ?");
        $del_logentries->execute(array($id));

        $del = $conn->prepare("DELETE FROM projekte WHERE ID = ?");
        $delchk = $del->execute(array($id));

        delete_directory(CL_ROOT . "/files/" . CL_CONFIG . "/$id");
        if ($delchk) {
            $mylog->add($userid, 'projekt', 3, $id);
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
        global $conn, $mylog;
        $id = (int)$id;

        $updStmt = $conn->prepare("UPDATE projekte SET status=1 WHERE ID = ?");
        $upd = $updStmt->execute(array($id));

        if ($upd) {
            $nam = $conn->query("SELECT name FROM projekte WHERE ID = $id")->fetch();
            $nam = $nam[0];
            $mylog->add($nam, 'projekt', 4, $id);
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
        global $conn, $mylog;
        $id = (int)$id;

        $mile = new milestone();
        $milestones = $mile->getAllProjectMilestones($id, 100000);
        if (!empty($milestones)) {
            $close_milestones = $conn->prepare("UPDATE milestones SET status = 0 WHERE ID = ?");
            foreach ($milestones as $miles) {
                $close_milestones->execute(array($miles["ID"]));
            }
        }

        $task = new task();
        $tasks = $task->getProjectTasks($id);
        if (!empty($tasks)) {
            $close_tasks = $conn->prepare("UPDATE tasks SET status = 0 WHERE ID = ?");
            foreach ($tasks as $tas) {
                $close_tasks->execute(array($tas["ID"]));
            }
        }

        $tasklist = new tasklist();
        $tasklists = $tasklist->getProjectTasklists($id);
        if (!empty($tasklists)) {
            $close_tasklists = $conn->prepare("UPDATE tasklist SET status = 0 WHERE ID = ?");
            foreach ($tasklists as $tl) {
                $close_tasklists->execute(array($tl["ID"]));
            }
        }

        $updStmt = $conn->prepare("UPDATE projekte SET status=0 WHERE ID = ?");
        $upd = $updStmt->execute(array($id));

        if ($upd) {
            $nam = $conn->query("SELECT name FROM projekte WHERE ID = $id")->fetch();
            $nam = $nam[0];
            $mylog->add($nam, 'projekt', 5, $id);
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
        global $conn, $mylog;

        $insStmt = $conn->prepare("INSERT INTO projekte_assigned (user,projekt) VALUES (?,?)");
        $ins = $insStmt->execute(array((int)$user, (int)$id));
        if ($ins) {
            $userObj = new user();
            $user = $userObj->getProfile($user);
            $mylog->add($user["name"], 'user', 6, $id);
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
        global $conn, $mylog;

        $sqlStmt = $conn->prepare("DELETE FROM projekte_assigned WHERE user = ? AND projekt = ?");

        $milestone = new milestone();
        // Delete the users assignments to closed milestones
        $doneMilestones = $milestone->getDoneProjectMilestones($id);
        if (!empty($doneMilestones)) {
            $doneMilestonesStmt = $conn->prepare("DELETE FROM milestones_assigned WHERE user = ? AND milestone = ?");
            foreach ($doneMilestones as $dm) {
                $doneMilestonesStmt->execute(array((int)$user, $dm['ID']));
            }
        }
        // Delete the users assignments to open milestones
        $openMilestones = $milestone->getAllProjectMilestones($id, 100000);
        if (!empty($openMilestones)) {
            $openMilestonesStmt = $conn->prepare("DELETE FROM milestones_assigned WHERE user = ? AND milestone = ?");
            foreach ($openMilestones as $openMilestone) {
                $openMilestonesStmt->execute(array((int)$user, $openMilestone['ID']));
            }
        }

        $taskObj = new task();
        $tasks = $taskObj->getProjectTasks($id);
        // Delete tasks assignments of the user
        if (!empty($tasks)) {
            $tasksStmt = $conn->prepare("DELETE FROM tasks_assigned WHERE user = ? AND task = ?");
            foreach ($tasks as $task) {
                $tasksStmt->execute(array((int)$user, $task['ID']));
            }
        }
        // Finally remove the user from the project
        $del = $sqlStmt->execute(array((int)$user, (int)$id));
        if ($del) {
            $userObj = new user();
            $user = $userObj->getProfile($user);
            $mylog->add($user["name"], 'user', 7, $id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gibt alle Daten eines Projekts aus
     *
     * @param int $id Eindeutige Projektnummer
     * @return array $project Projektdaten
     */
    function getProject($id)
    {
        global $conn;
        $id = (int)$id;

        $projectStmt = $conn->prepare("SELECT * FROM projekte WHERE ID = ?");
        $projectStmt->execute(array($id));

        $project = $projectStmt->fetch();

        if (!empty($project)) {
            if ($project["end"]) {
                $daysleft = $this->getDaysLeft($project["end"]);
                $project["daysleft"] = $daysleft;

                /*
                 * Determine if project is late or is today
                 * This is for display
                 */
                $project["islate"] = false;
                if ($project["daysleft"] < 1) {
                    $project["islate"] = true;
                }

                $project["istoday"] = false;
                if ($project["daysleft"] == 0) {
                    $project["istoday"] = true;
                }

                //Format endstring according to system dateformat
                $project["endstring"] = date(CL_DATEFORMAT, $project["end"]);
            } else {
                $project["daysleft"] = "";
            }

            $project["startstring"] = date(CL_DATEFORMAT, $project["start"]);

            $project["done"] = $this->getProgress($project["ID"]);

            $companyObj = new company();
            $project["customer"] = $companyObj->getProjectCompany($id);

            return $project;
        } else {
            return false;
        }
    }

    /**
     * Listet die aktuellsten Projekte auf
     *
     * @param int $status Status of the project (1=open, 2= closed)
     * @param int $limit Anzahl der anzuzeigenden Projekte
     * @param int offset The offset from which to fetch data from
     * @return array $projekte Active projects
     */
    function getProjects($status = 1, $limit = 10, $offset = 0)
    {
        global $conn;

        $status = (int)$status;
        $limit = (int)$limit;
        $offset = (int)$offset;

        $projects = array();

        $projectIdStmt = $conn->prepare("SELECT `ID` FROM projekte WHERE `status`= ? ORDER BY `end` ASC LIMIT $limit OFFSET $offset");
        $projectIdStmt->execute(array($status));

        while ($projectId = $projectIdStmt->fetch()) {
            $project = $this->getProject($projectId["ID"]);
            array_push($projects, $project);
        }

        if (!empty($projects)) {
            return $projects;
        } else {
            return false;
        }
    }

    /**
     * Counts all open or closed projects.
     *
     * @param int $status Status of the project (1=open, 2= closed)
     * @return int $count Number of open or closed projects
     */
    function countProjects($status = 1)
    {
        global $conn;

        $status = (int) $status;

        $countStmt = $conn->prepare("SELECT COUNT(*) FROM projekte WHERE `status`= ?");
        $countStmt->execute(array($status));

        $count = $countStmt->fetch();
        $count = $count["COUNT(*)"];

        return $count;
    }

    /**
     * Lists all projects assigned to a given member ordered by due date ascending
     *
     * @param int $user User id
     * @param int $status Status of a project (1= open, 0 = closed)
     * @param int $limit Limit for the SQL statement
     * @param int $offset Offset for the SQL statement
     * @return array $myProjects Projects for this user
     */
    function getMyProjects($user, $status = 1, $offset = 0, $limit = 10)
    {
        global $conn;

        $myProjects = array();
        $user = (int)$user;
        $status = (int)$status;
        $offset = (int)$offset;
        $limit = (int)$limit;
        //get the projekt IDs of projects assigned to this user
        $selAssigned = $conn->query("SELECT projekt, status FROM projekte_assigned JOIN projekte ON projekte.ID = projekte_assigned.projekt WHERE user = $user AND projekte.status = $status ORDER BY projekt DESC LIMIT $limit OFFSET $offset");

        $projectsAssigned = $selAssigned->fetchAll();

        //loop by reference and assign the project details to eahc project
        foreach ($projectsAssigned as $project) {
            if (!empty($project[0])) {
                array_push($myProjects,$this->getProject($project[0]));
            }
        }

        if (!empty($myProjects)) {
            return $myProjects;
        } else {
            return false;
        }
    }

    /**
     * Counts all project assigned to a given member.
     *
     * @param int $user ID of the user
     * @param int $status Status of the project (1=open, 2= closed)
     * @return int $count Number of member's projects
     */
    function countMyProjects($user, $status = 1)
    {
        global $conn;

        $user = (int) $user;
        $status = (int) $status;

        $countStmt = $conn->prepare("SELECT COUNT(*) FROM projekte_assigned JOIN projekte ON projekte.ID = projekte_assigned.projekt WHERE user = $user AND projekte.status = $status");
        $countStmt->execute(array($status));

        $count = $countStmt->fetch();
        $count = $count["COUNT(*)"];

        return $count;
    }

    /**
     * Lists all project IDs assigned to a user
     *
     * @param int $user ID of the user
     * @return array $myProjects Project IDs for user
     */
    function getMyProjectIds($user)
    {
        global $conn;

        $myProjects = array();

        $sel = $conn->prepare("SELECT projekt FROM projekte_assigned WHERE user = ? ORDER BY end ASC");
        $sel->execute(array($user));

        if ($sel) {
            while ($projs = $sel->fetch()) {
                $sel2 = $conn->query("SELECT ID FROM projekte WHERE ID = " . $projs[0]);
                $projekt = $sel2->fetch();
                if ($projekt) {
                    array_push($myProjects, $projekt);
                }
            }
        }
        if (!empty($myProjects)) {
            return $myProjects;
        } else {
            return false;
        }
    }

    /**
     * Lists all the users in a project
     *
     * @param int $project ProjectId
     * @param int $lim Max number of members to return
     * @param bool $paginate Should pagination take place
     * @return array $members Projektmitglieder
     */
    function getProjectMembers($project, $lim = 10, $paginate = true)
    {
        global $conn;
        $project = (int)$project;
        $lim = (int)$lim;
        $project = (int)$project;
        $lim = (int)$lim;

        $members = array();

        if ($paginate) {
            $num = $conn->query("SELECT COUNT(*) FROM projekte_assigned WHERE projekt = $project")->fetch();
            $num = $num[0];
            $lim = (int)$lim;

            $start = 0;
        } else {
            $start = 0;
        }

        $userAssignedQuery = $conn->query("SELECT user FROM projekte_assigned WHERE projekt = $project LIMIT $start,$lim");

        $userObject = new user();
        while ($user = $userAssignedQuery->fetch()) {
            $theUser = $userObject->getProfile($user[0]);
            array_push($members, $theUser);
        }

        if (!empty($members)) {
            return $members;
        } else {
            return false;
        }
    }
    /**
     * Lists all the users in a project
     *
     * @param int $project ProjectId
     * @param int $limit Max number of members to return
     * @param bool $paginate Should pagination take place
     * @return array $members Projektmitglieder
     */
    function listProjectMembers($project, $limit = 10, $offset = 0)
    {
        global $conn;
        $project = (int)$project;
        $limit = (int)$limit;
        $offset = (int)$offset;

        $members = array();

        $userAssignedQuery = $conn->query("SELECT user FROM projekte_assigned WHERE projekt = $project LIMIT $limit OFFSET $offset");

        $userObject = new user();
        while ($user = $userAssignedQuery->fetch()) {
            $theUser = $userObject->getProfile($user[0]);
            array_push($members, $theUser);
        }

        if (!empty($members)) {
            return $members;
        } else {
            return false;
        }
    }
    /**
     * Counts all members in a project.
     *
     * @param int $project Project ID
     * @return int $num Number of members
     */
    function countMembers($project)
    {
        global $conn;
        $project = (int) $project;

        $numStmt = $conn->prepare("SELECT COUNT(*) FROM projekte_assigned WHERE projekt = ?");
        $numStmt->execute(array($project));

        $num = $numStmt->fetch();

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
        $project = (int)$project;

        $openTasksStmt = $conn->prepare("SELECT COUNT(*) FROM tasks WHERE project = ? AND status = 1");
        $openTasksStmt->execute(array($project));

        $openTasks = $openTasksStmt->fetch();
        $openTasks = $openTasks[0];

        $closedTasksStmt = $conn->prepare("SELECT COUNT(*) FROM tasks WHERE project = ? AND status = 0");
        $closedTasksStmt->execute(array($project));

        $closedTasks = $closedTasksStmt->fetch();
        $closedTasks = $closedTasks[0];

        $totaltasks = $openTasks + $closedTasks;
        if ($totaltasks > 0 and $closedTasks > 0) {
            $done = $closedTasks / $totaltasks * 100;
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
        $project = (int)$project;

        $sel = $conn->prepare("SELECT * FROM projectfolders WHERE project = ?");
        $sel->execute(array($project));

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