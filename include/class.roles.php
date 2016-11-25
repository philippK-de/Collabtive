<?php
/**
 * This class provides user roles
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name roles
 * @package Collabtive
 * @version 3
 * @link http://collabtive.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class roles {
    function __construct()
    {
    }

    /**
     * Add a role
     * This method takes an array with permissions, serializes it to string, and saves it to the Database
     *
     * @param string $name Name of the role (for display)
     * @param array $projects Role permissions for projects
     * @param array $tasks Role permissions for tasks
     * @param array $milestones Role permissions for milestones
     * @param array $customers Role permissions for customers
     * @param array $messages Role permissions for messages
     * @param array $files Role permissions for files
     * @param array $timetracker Role permissions for timetracker
     * @param array $admin
     * @param array $chat
     * @return bool
     */
    function add($name, array $projects, array $tasks, array $milestones, array $messages, array $files, array $timetracker, array $chat, array $admin)
    {
        global $conn;
        $projects = serialize($projects);
        $tasks = serialize($tasks);
        $milestones = serialize($milestones);
        $messages = serialize($messages);
        $files = serialize($files);
        $timetracker = serialize($timetracker);
        $chat = serialize($chat);
        $admin = serialize($admin);

        $insStmt = $conn->prepare("INSERT INTO roles (name,projects,tasks,milestones,messages,files,timetracker,chat,admin) VALUES (?,?,?,?,?,?,?,?,?)");
        $ins = $insStmt->execute(array($name, $projects, $tasks, $milestones, $messages, $files, $timetracker, $chat, $admin));

        if ($ins) {
            $insid = $conn->lastInsertId();
            return $insid;
        } else {
            return false;
        }
    }

    /**
     * Edit a role
     * This method takes an array with permissions, serializes it to string, and saves it to the Database
     * Additionally it takes the ID of the role to edit
     *
     * @param int $id ID of the role to edit
     * @param string $name Name of the role (for display)
     * @param array $projects Role permissions for projects
     * @param array $tasks Role permissions for tasks
     * @param array $milestones Role permissions for milestones
     * @param array $customers Role permissions for customers
     * @param array $messages Role permissions for messages
     * @param array $files Role permissions for files
     * @param array $timetracker Role permissions for timetracker
     * @param array $chat
     * @param array $admin
     * @return bool
     */
    function edit($id, $name, array $projects, array $tasks, array $milestones, array $messages, array $files, array $timetracker, array $chat, array $admin)
    {
        global $conn;
        $id = (int) $id;
        $projects = serialize($projects);
        $tasks = serialize($tasks);
        $milestones = serialize($milestones);
        $messages = serialize($messages);
        $files = serialize($files);
        $timetracker = serialize($timetracker);
        $chat = serialize($chat);
        $admin = serialize($admin);

        $updStmt = $conn->prepare("UPDATE roles SET name=?,projects=?,tasks=?,milestones=?,messages=?,files=?,timetracker=?,chat=?,admin=? WHERE ID = ?");
        $upd = $updStmt->execute(array($name, $projects, $tasks, $milestones, $messages, $files, $timetracker, $chat, $admin, $id));

        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a role
     * This method takes the ID of the role to be deleted.
     * It returns true if the deletion was sucessful, otherwise false
     *
     * @param int $id ID of the role to be deleted
     * @return bool
     */
    function del($id)
    {
        global $conn;
        $id = (int) $id;
        $del = $conn->prepare("DELETE FROM roles WHERE ID = ?");
        $del->execute(array($id));

        $del2 = $conn->prepare("DELETE FROM roles_assigned WHERE role = ?");
        $del2->execute(array($id));

        if ($del) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Assign a role to a user
     * Assigns role $role to user $user
     *
     * @param int $role ID of the role
     * @param int $user ID of the user
     * @return bool
     */
    function assign($role, $user)
    {
        global $conn;
        $role = (int) $role;
        $user = (int) $user;
        // get the number of roles already assigned to $user
        $chk = $conn->query("SELECT COUNT(*) FROM roles_assigned WHERE user = $user")->fetch();
        $chk = $chk[0];
        // If there already is a role assigned to the user, just update this entry
        // Otherwise create a new entry
        if ($chk > 0) {
            $insStmt = $conn->prepare("UPDATE roles_assigned SET role = ? WHERE user = ?");
            $ins = $insStmt->execute(array($role, $user));
        } else {
            $insStmt = $conn->prepare("INSERT INTO roles_assigned (user,role) VALUES (?,?)");
            $ins = $insStmt->execute(array($user, $role));
        }

        if ($ins) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Deassign a role from a user
     * Remove role $role from user $user
     *
     * @param int $role ID of the role
     * @param int $user ID of the user
     * @return bool
     */
    function deassign($role, $user)
    {
        global $conn;
        $role = (int) $role;
        $user = (int) $user;

        $del = $conn->prepare("DELETE FROM roles_assigned WHERE user = ? AND role = ? LIMIT 1");
        $del->execute(array($user, $role));

        if ($del) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all available roles
     *
     * @param int $limit Limit to number of entries
     * @param int $offset Start from number of entry
     * @return array $roles Array with roles
     */
    function getAllRoles($limit = 10, $offset = 0)
    {
        $limit = (int) $limit;
        $offset = (int) $offset;

        global $conn;
        $sel = $conn->query("SELECT ID FROM roles ORDER BY ID DESC LIMIT $limit OFFSET $offset");

        $roles = array();
        while ($role = $sel->fetch()) {
            array_push($roles, $this->getRole($role["ID"]));
        }

        if (!empty($roles)) {
            return $roles;
        } else {
            return false;
        }
    }

    /**
     * Counts all roles.
     *
     * @return int $count Number of roles
     */
    function countAllRoles()
    {
        global $conn;

        $countStmt = $conn->prepare("SELECT COUNT(*) FROM roles");
        $countStmt->execute();

        $count = $countStmt->fetch();
        $count = $count["COUNT(*)"];

        return $count;
    }

    /**
     * Translate name of default roles
     *
     * Intended for viewing translated list of AllRoles.
     * Be sure that rolenames in output
     * are not used for other things than viewing.
     *
     * Default Roles are Admin, User, Client
     *
     * @param array $roles Array with names to translate
     * @return array $roles Array with translated role names
     */

    /**
     * Get the role of a user
     * This is mainly called by class user
     *
     * @param int $user ID of the user
     * @return bool
     */
    function getUserRole($user)
    {
        global $conn;
        $user = (int) $user;

        $userStmt = $conn->prepare("SELECT role FROM roles_assigned WHERE user = ?");
        $userStmt->execute(array($user));

        $usr = $userStmt->fetch();
        $usr = $usr[0];

        if ($usr) {
            $role = $this->getRole($usr);
        } else {
            return false;
        }

        if (!empty($role)) {
            return $role;
        } else {
            return array();
        }
    }

    /**
     * make sure all the fields are either 1 or 0 , fill empty ones with 0
     * This is mainly called when adding a role
     *
     * @param array $inarr Array to sanitize
     * @return array $inarr Sanitized array
     */
    function sanitizeArray($inarr)
    {
        if (!is_array($inarr)) {
            $inarr = array();
        }
        if (empty($inarr["add"])) {
            $inarr["add"] = 0;
        }
        if (empty($inarr["edit"])) {
            $inarr["edit"] = 0;
        }
        if (empty($inarr["del"])) {
            $inarr["del"] = 0;
        }
        if (empty($inarr["close"])) {
            $inarr["close"] = 0;
        }
        if (empty($inarr["read"])) {
            $inarr["read"] = 0;
        }
        if (empty($inarr["view"])) {
            $inarr["view"] = 0;
        }

        return (array) $inarr;
    }

    /**
     * Get an array for a role
     *
     * @param int $role Role ID
     * @return array $therole Role as array
     */
    private function getRole($role)
    {
        global $conn;
        $role = (int) $role;
        // Get the serialized strings from the db
        $sel = $conn->prepare("SELECT * FROM roles WHERE ID = ?");
        $sel->execute(array($role));

        $therole = $sel->fetch();
        // Unserialize to an array
        $therole["projects"] = unserialize($therole["projects"]);
        $therole["tasks"] = unserialize($therole["tasks"]);
        $therole["milestones"] = unserialize($therole["milestones"]);
        $therole["messages"] = unserialize($therole["messages"]);
        $therole["files"] = unserialize($therole["files"]);
        $therole["timetracker"] = unserialize($therole["timetracker"]);
        $therole["chat"] = unserialize($therole["chat"]);
        $therole["admin"] = unserialize($therole["admin"]);

        if (!empty($therole)) {
            return $therole;
        } else {
            return array();
        }
    }
}

?>
