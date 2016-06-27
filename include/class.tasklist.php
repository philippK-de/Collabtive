<?php
/**
 * This class provides methods to realize tasklists
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @package Collabtive
 * @name tasklist
 * @version 1.0
 * @link http://collabtive.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class tasklist {


    /**
     * Add a tasklist
     *
     * @param int $project ID of the associated project
     * @param string $name Name of the tasklist
     * @param string $desc Description of the tasklist
     * @param int $access Access level (0 = public)
     * @param int $milestone ID of the associated milestone (0 = no association)
     * @return bool
     */
    function add_liste($project, $name, $desc, $access = 0, $milestone = 0)
    {
        global $conn,$mylog;

        $insStmt = $conn->prepare("INSERT INTO tasklist (`project`, `name`, `desc`, `start`, `status`, `access`, `milestone`) VALUES (?, ?, ?, ?, 1, ?, ?)");
        $ins = $insStmt->execute(array((int) $project, $name, $desc, time(), (int) $access, (int) $milestone));

        if ($ins) {
            $insid = $conn->lastInsertId();
            $mylog->add($name, 'tasklist', 1, $project);
            return $insid;
        } else {
            return false;
        }
    }

    /**
     * Edit a tasklist
     *
     * @param int $id Tasklist ID
     * @param string $name Tasklist name
     * @param string $desc Tasklist description
     * @param int $milestone ID of the associated milestone
     * @return bool
     */
    function edit_liste($id, $name, $desc, $milestone)
    {
        global $conn,$mylog;
        $name = htmlspecialchars($name);
        $updStmt = $conn->prepare("UPDATE tasklist SET `name` = ?, `desc` = ?, `milestone` = ? WHERE ID = ?");
        $upd = $updStmt->execute(array($name, $desc, $milestone, $id));
        if ($upd) {
            $projectStmt = $conn->prepare("SELECT project FROM tasklist WHERE ID = ?");
            $projectStmt->execute(array($id));

            $project = $projectStmt->fetch();
            $project = $project[0];

            $mylog->add($name, 'tasklist', 2, $project);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a tasklist
     *
     * @param int $id Tasklist ID
     * @return bool
     */
    function del_liste($id)
    {
        global $conn,$mylog;
        $id = (int) $id;

        $sel = $conn->query("SELECT project, name FROM tasklist WHERE ID = $id");
        $del = $conn->query("DELETE FROM tasklist WHERE ID = $id");
        if ($del) {
            $tasks1 = $this->getTasksFromList($id);
            $taskobj = new task();
            if (!empty($tasks1)) {
                foreach($tasks1 as $task) {
                    $taskobj->del($task["ID"]);
                }
            }
            $tasks2 = $this->getTasksFromList($id, 0);
            if (!empty($tasks2)) {
                foreach($tasks2 as $task) {
                    $taskobj->del($task["ID"]);
                }
            }
            $sel1 = $sel->fetch();
            $proj = $sel1[0];
            $name = $sel1[1];
            $mylog->add($name, 'tasklist', 3, $proj);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Reactivate / open a tasklist
     *
     * @param int $id Tasklist ID
     * @return bool
     */
    function open_liste($id)
    {
        global $conn,$mylog;
        $id = (int) $id;

        $updStmt = $conn->prepare("UPDATE tasklist SET status = 1 WHERE ID = ?");
		$upd = $updStmt->execute(array($id));

        if ($upd) {
            $nam = $conn->query("SELECT project, name FROM tasklist WHERE ID = $id")->fetch();
            $project = $nam[0];
            $name = $nam[1];

            $mylog->add($name, 'tasklist', 4, $project);
            return true;
        } else {
            return false;
        }

    }

    /**
     * Finish / close a tasklist
     *
     *
     * @param int $id Tasklist ID
     * @param bool $closeMilestones Determines if the parent milestone is closed too if $id is the last assigned tasklist to that ms
     * @return bool
     */
    function close_liste($id, $closeMilestones = true)
    {
        global $conn,$mylog;
        $id = (int) $id;

        $updStmt = $conn->prepare("UPDATE tasklist SET status = 0 WHERE ID = ?");
    	$upd = $updStmt->execute(array($id));

        if ($closeMilestones) {
            // Close assigned milestone too, if no other open tasklists are assigned to it
            $milestone = $conn->query("SELECT milestone FROM tasklist WHERE ID = $id")->fetch();
            if ($milestone[0] > 0) {
                $cou = $conn->query("SELECT count(*) FROM tasklist WHERE milestone = $milestone[0] AND status = 1")->fetch();

                if ($cou[0] == 0) {
                    $miles = new milestone();
                    $miles->close($milestone[0]);
                }
            }
        }
        // Close tasks in this list
        $tasks = $this->getTasksFromList($id);
        if (!empty($tasks)) {
            $taskobj = new task();
            foreach($tasks as $task) {
                $taskobj->close($task["ID"]);
            }
        }
        // Log entry
        if ($upd) {
            $nam = $conn->query("SELECT project, name FROM tasklist WHERE ID = $id")->fetch();
            $project = $nam[0];
            $name = $nam[1];

            $mylog->add($name, 'tasklist', 5, $project);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return all tasklists (including its open tasks) associated with a given project
     *
     * @param int $project Project ID
     * @param int $status Tasklist status (0 = Finished, 1 = Active)
     * @return array $tasklists Details of the tasklists
     */
    function getProjectTasklists($project, $status = 1)
    {
        global $conn;
        $project = (int) $project;
        $status = (int) $status;

        $sel = $conn->prepare("SELECT * FROM tasklist WHERE project = ? AND status=?");
    	$sel->execute(array($project,$status));

        $tasklists = array();

        $taskobj = new task();
        while ($list = $sel->fetch()) {
            $sel2 = $conn->query("SELECT ID FROM tasks WHERE liste = $list[ID] AND status=1 ORDER BY `end`,`title` ASC");
            $list['tasks'] = array();
            while ($tasks = $sel2->fetch()) {
                array_push($list['tasks'], $taskobj->getTask($tasks["ID"]));
            }

            $sel3 = $conn->query("SELECT ID FROM tasks WHERE liste = $list[ID] AND status=0 ORDER BY `end` ASC");
            $list['oldtasks'] = array();
            while ($oldtasks = $sel3->fetch()) {
                array_push($list['oldtasks'], $taskobj->getTask($oldtasks["ID"]));
            }

            array_push($tasklists, $list);
        }

        if (!empty($tasklists)) {
            return $tasklists;
        } else {
            return false;
        }
    }

    /**
     * Return a tasklist
     *
     * @param int $id Taskist ID
     * @return array $tasklist Tasklist details
     */
    function getTasklist($id)
    {
        global $conn;

        $selStmt = $conn->prepare("SELECT * FROM `tasklist` WHERE ID = ?");
        $sel = $selStmt->execute(array($id));
        $tasklist = $selStmt->fetch();

        if (!empty($tasklist)) {
            $startstring = date(CL_DATEFORMAT, $tasklist["start"]);
            $tasklist["startstring"] = $startstring;
            $tasklist["tasks"] = $this->getTasksFromList($tasklist["ID"]);

            return $tasklist;
        } else {
            return false;
        }
    }

    /**
     * Return all open or all finished tasks of a given tasklist
     *
     * @param int $id Tasklist ID
     * @param int $status Status of the tasks (0 = finished, 1 = open)
     * @return array $tasks Details of the tasks
     */
    function getTasksFromList($id, $status = 1)
    {
        global $conn;
        $id = (int) $id;
        $status = (int) $status;

        $taskobj = new task();

        $sel = $conn->prepare("SELECT ID FROM tasks WHERE `liste` = ? AND `status` = ? ORDER BY `end`,`title` ASC");
    	$sel->execute(array($id,$status));

        $tasks = array();
        while ($task = $sel->fetch()) {
            array_push($tasks, $taskobj->getTask($task["ID"]));
        }

        if (!empty($tasks)) {
            return $tasks;
        } else {
            return false;
        }
    }
}

?>