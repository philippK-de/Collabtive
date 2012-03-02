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
class roles
{
    function __construct()
    {
    }

    /**
     * Add a role
     * This method takes an array with permissions, serializes it to string, and saves it to the Database
     *
     * @param string $name Name of the role (for display)
     * @param array $tasks Role permissions for tasks
     * @param array $milestones Role permissions for milestones
     * @param array $messages Role permissions for messages
     * @param array $files Role permissions for files
     * @param array $timetracker Role permissions for timetracker
     * @param array $admin
     * @param array $chat
     * @param array $files Role permissions for admin area
     * @return bool
     */
    function add($name, array $projects, array $tasks, array $milestones, array $messages, array $files, array $timetracker, array $chat, array $admin)
    {
        $name = mysql_real_escape_string($name);
        $projects = serialize($projects);
        $tasks = serialize($tasks);
        $milestones = serialize($milestones);
        $messages = serialize($messages);
        $files = serialize($files);
        $timetracker = serialize($timetracker);
        $chat = serialize($chat);
        $admin = serialize($admin);

        $ins = mysql_query("INSERT INTO roles (name,projects,tasks,milestones,messages,files,timetracker,chat,admin) VALUES ('$name','$projects','$tasks','$milestones','$messages','$files','$timetracker','$chat','$admin')");

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

    /**
     * Edit a role
     * This method takes an array with permissions, serializes it to string, and saves it to the Database
     * Additionally it takes the ID of the role to edit
     *
     * @param int $id ID of the role to edit
     * @param string $name Name of the role (for display)
     * @param array $tasks Role permissions for tasks
     * @param array $milestones Role permissions for milestones
     * @param array $messages Role permissions for messages
     * @param array $files Role permissions for files
     * @param array $timetracker Role permissions for timetracker
     * @param array $admin
     * @param array $files Role permissions for admin area
     * @return bool
     */
    function edit($id, $name, array $projects, array $tasks, array $milestones, array $messages, array $files, array $timetracker, array $chat, array $admin)
    {
        $id = (int) $id;
        $name = mysql_real_escape_string($name);
        $projects = serialize($projects);
        $tasks = serialize($tasks);
        $milestones = serialize($milestones);
        $messages = serialize($messages);
        $files = serialize($files);
        $timetracker = serialize($timetracker);
        $chat = serialize($chat);
        $admin = serialize($admin);

        $upd = mysql_query("UPDATE roles SET name='$name',projects='$projects',tasks='$tasks',milestones='$milestones',messages='$messages',files='$files',timetracker='$timetracker',chat='$chat',admin='$admin' WHERE ID = $id");

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
     * Delete a role
     * This method takes the ID of the role to be deleted.
     * It returns true if the deletion was sucessful, otherwise false
     *
     * @param int $id ID of the role to be deleted
     * @return bool
     */
    function del($id)
    {
        $id = (int) $id;
        $del = mysql_query("DELETE FROM roles WHERE ID = $id");
        $del2 = mysql_query("DELETE FROM roles_assigned WHERE role = $id");

        if ($del)
        {
            return true;
        }
        else
        {
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
        $role = (int) $role;
        $user = (int) $user;
        // get the number of roles already assigned to $user
        $chk = mysql_query("SELECT COUNT(*) FROM roles_assigned WHERE user = $user");
        $chk = mysql_fetch_row($chk);
        $chk = $chk[0];
        // If there already is a role assigned to the user, just update this entry
        // Otherwise create a new entry
        if ($chk > 0)
        {
            $ins = mysql_query("UPDATE roles_assigned SET role = $role WHERE user = $user");
        }
        else
        {
            $ins = mysql_query("INSERT INTO roles_assigned (user,role) VALUES ($user,$role)");
        }

        if ($ins)
        {
            return true;
        }
        else
        {
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
        $role = (int) $role;
        $user = (int) $user;

        $del = mysql_query("DELETE FROM roles_assigned WHERE user = $user AND role = $role LIMIT 1");

        if ($del)
        {
            return true;
        }
        else
        {
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
        $roles = array();

        if (!$limit)
        {
            $sel = mysql_query("SELECT ID FROM roles ORDER BY ID DESC");
        }
        else
        {
            $sel = mysql_query("SELECT ID FROM roles ORDER BY ID DESC LIMIT $limit");
        }
        while ($role = mysql_fetch_array($sel, MYSQL_ASSOC))
        {
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

        if (!empty($roles))
        {
            return $roles;
        }
        else
        {
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
        $user = (int) $user;

        $sel = mysql_query("SELECT role FROM roles_assigned WHERE user = $user");
        $usr = mysql_fetch_row($sel);
        $usr = $usr[0];
        if ($usr)
        {
            $role = $this->getRole($usr);
        }
        else
        {
            return false;
        }

        if (!empty($role))
        {
            return $role;
        }
        else
        {
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
        if (!is_array($inarr))
        {
            $inarr = array();
        }
        if (empty($inarr["add"]))
        {
            $inarr["add"] = 0;
        }
        if (empty($inarr["edit"]))
        {
            $inarr["edit"] = 0;
        }
        if (empty($inarr["del"]))
        {
            $inarr["del"] = 0;
        }
        if (empty($inarr["close"]))
        {
            $inarr["close"] = 0;
        }
        if (empty($inarr["read"]))
        {
            $inarr["read"] = 0;
        }

        return (array) $inarr;
    }

    private function getRole($role)
    {
        $role = (int) $role;

        $sel2 = mysql_query("SELECT * FROM roles WHERE ID = $role");
        $therole = mysql_fetch_array($sel2, MYSQL_ASSOC);

        $therole["projects"] = unserialize($therole["projects"]);
        $therole["tasks"] = unserialize($therole["tasks"]);
        $therole["milestones"] = unserialize($therole["milestones"]);
        $therole["messages"] = unserialize($therole["messages"]);
        $therole["files"] = unserialize($therole["files"]);
        $therole["timetracker"] = unserialize($therole["timetracker"]);
        $therole["chat"] = unserialize($therole["chat"]);
        $therole["admin"] = unserialize($therole["admin"]);

        if (!empty($therole))
        {
            return $therole;
        }
        else
        {
            return array();
        }
    }
}

?>
