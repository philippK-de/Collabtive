<?php
/**
 * This class provides user roles
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name roles
 * @package Collabtive
 * @version 0.5
 * @link http://www.o-dyn.de
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
        $del = queryWithParameters('DELETE FROM roles WHERE ID = ?;', array($id));
        $del2 = queryWithParameters('DELETE FROM roles_assigned WHERE role = ?;', array($id));

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
        $chk = queryWithParameters('SELECT COUNT(*) FROM roles_assigned WHERE user = ?;', array($user))->fetch();
        $chk = $chk[0];
        // If there already is a role assigned to the user, just update this entry
        // Otherwise create a new entry
        if ($chk > 0) {
            $ins = queryWithParameters('UPDATE roles_assigned SET role = $role WHERE user = ?;', array($user));
        } else {
            $ins = queryWithParameters('INSERT INTO roles_assigned (user,role) VALUES (?, ?);', array($user,$role));
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

        $del = queryWithParameters('DELETE FROM roles_assigned WHERE user = ? AND role = ? LIMIT 1;', array($user, $role));

        if ($del) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all available roles
     *
     * @param bool $limit Limit the query or show all ?
     * @return array $roles Array with roles
     */
    function getAllRoles($limit = false)
    {
        global $conn;
        $roles = array();

        if (!$limit) {
            $sel = $conn->query("SELECT ID FROM roles ORDER BY ID DESC");
        } else {
            $sel = queryWithParameters('SELECT ID FROM roles ORDER BY ID DESC LIMIT ?;', array($limit));
        } while ($role = $sel->fetch()) {
            /**
             * $role["projects"] = unserialize($role["projects"]);
             * $role["tasks"] = unserialize($role["tasks"]);
             * $role["milestones"] = unserialize($role["milestones"]);
             * $role["messages"] = unserialize($role["messages"]);
             * $role["files"] = unserialize($role["files"]);
             * $role["timetracker"] = unserialize($role["timetracker"]);
             * $role["admin"] = unserialize($role["admin"]);
             */
            // array_push($roles, $role);
            $therole = $this->getRole($role["ID"]);
            array_push($roles, $therole);
        }

        if (!empty($roles)) {
            return $roles;
        } else {
            return array();
        }
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

        $usr = queryWithParameters('SELECT role FROM roles_assigned WHERE user = ?;', array($user))->fetch();
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
        $sel2 = queryWithParameters('SELECT * FROM roles WHERE ID = ?;', array($role));
        $therole = $sel2->fetch();
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
