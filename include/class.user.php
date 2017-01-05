<?php
/**
 * Provides methods to interact with users
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name user
 * @version 0.7
 * @package Collabtive
 * @link http://collabtive.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or laterg
 */
class user {


    /**
     * Creates a user
     *
     * @param string $name Name of the member
     * @param string $email E-mail address of the member
     * @param string $company Company of the member
     * @param string $pass Password
     * @param string $locale Localisation
     * @param float $rate Hourly rate
     * @return int $insid ID of the newly created member
     */
    function add($name, $email, $company, $pass, $locale = "", $tags = "", $rate = 0.0)
    {
        global $conn,$mylog;
        $hash = $this->hash($pass);

        $ins1Stmt = $conn->prepare("INSERT INTO user (name,email,company,pass,locale,tags,rate) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $ins1 = $ins1Stmt->execute(array($name, $email, $company, $hash, $locale, $tags, $rate));

        if ($ins1) {
            $insid = $conn->lastInsertId();
            $mylog->add($name, 'user', 1, 0);
            return $insid;
        } else {
            return false;
        }
    }

    /**
     * Edits a member
     *
     * @param int $id Member ID
     * @param string $name Member name
     * @param string $realname realname
     * @param string $role role
     * @param string $email Email
     * @param string $company Company of the member
     * @param string $zip ZIP-Code
     * @param string $gender Gender
     * @param string $url URL
     * @param string $address1 Adressline1
     * @param string $address2 Addressline2
     * @param string $state State
     * @param string $country Country
     * @param string $locale Localisation
     * @param string $avatar Avatar
     * @return bool
     */
    function edit($id, $name, $realname, $email, $tel1, $tel2, $company, $zip, $gender, $url, $address1, $address2, $state, $country, $tags, $locale, $avatar = "", $rate = 0.0)
    {
        global $conn,$mylog;

        $rate = (float) $rate;
        $id = (int) $id;

        if ($avatar != "") {
            $updStmt = $conn->prepare("UPDATE user SET name=?, email=?, tel1=?, tel2=?, company=?, zip=?, gender=?, url=?, adress=?, adress2=?, state=?, country=?, tags=?, locale=?, avatar=?, rate=? WHERE ID = ?");
            $upd = $updStmt->execute(array($name, $email, $tel1, $tel2, $company, $zip, $gender, $url, $address1, $address2, $state, $country, $tags, $locale, $avatar, $rate, $id));
        } else {
            $updStmt = $conn->prepare("UPDATE user SET name=?, email=?, tel1=?, tel2=?, company=?, zip=?, gender=?, url=?, adress=?, adress2=?, state=?, country=?, tags=?, locale=?, rate=? WHERE ID = ?");
            $upd = $updStmt->execute(array($name, $email, $tel1, $tel2, $company, $zip, $gender, $url, $address1, $address2, $state, $country, $tags, $locale, $rate, $id));
        }

        if ($upd) {
            $mylog->add($name, 'user', 2, 0);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Generate a new password and send it to the user's e-mail address
     *
     * @param string $email E-mail address entered by the user
     * @return string
     */
    function resetPassword($email)
    {
        global $conn;

        $user = $conn->query("SELECT ID, email, locale FROM user WHERE email={$conn->quote($email)} LIMIT 1")->fetch();

        if ($user["email"] == $email) {
            $id = $user["ID"];
            $locale = $user['locale'];
        }

        if (isset($id)) {
            $dummy = array_merge(range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'));
            shuffle($dummy);
            mt_srand((double)microtime() * 1000000);
            $newpass = "";
            for ($i = 1; $i <= 10; $i++) {
                $swap = mt_rand(0, count($dummy)-1);
                $tmp = $dummy[$swap];
                $newpass .= $tmp;
            }

            $sha1pass = sha1($newpass);

            $upd = $conn->query("UPDATE user SET `pass` = '$sha1pass' WHERE ID = $id");
            if ($upd) {
                return array('newpass'=>$newpass, 'locale'=>$locale);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Change password
     *
     * @param int $id Member ID
     * @param string $oldpass Old password
     * @param string $newpass New password
     * @param string $repeatpass Repetition of the new password
     * @return bool
     */
    function editpass($id, $oldpass, $newpass, $repeatpass)
    {
        global $conn;
        $id = (int) $id;

        if ($newpass != $repeatpass) {
            return false;
        }
        $newpass = sha1($newpass);

        $oldpass = sha1($oldpass);
        $chk = $conn->query("SELECT ID, name FROM user WHERE ID = $id AND pass = {$conn->quote($oldpass)}")->fetch();
        $chk = $chk[0];
        $name = $chk[1];
        if (!$chk) {
            return false;
        }

        $upd = $conn->query("UPDATE user SET pass={$conn->quote($newpass)} WHERE ID = $id");
        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Change password as admin
     *
     * @param int $id User ID
     * @param string $newpass New password
     * @param string $repeatpass Repetition of the new password
     * @return bool
     */
    function admin_editpass($id, $newpass, $repeatpass)
    {
        global $conn;
        $id = (int) $id;

        if ($newpass != $repeatpass) {
            return false;
        }
        $newpass = sha1($newpass);

        $upd = $conn->query("UPDATE user SET pass={$conn->quote($newpass)} WHERE ID = $id");
        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a user
     *
     * @param int $id User ID
     * @return bool
     */
    function del($id)
    {
        global $conn,$mylog;
        $id = (int) $id;

        $chk = $conn->query("SELECT name FROM user WHERE ID = $id")->fetch();
        $name = $chk[0];

        $del = $conn->query("DELETE FROM user WHERE ID = $id");
        $del2 = $conn->query("DELETE FROM projekte_assigned WHERE user = $id");
        $del3 = $conn->query("DELETE FROM milestones_assigned WHERE user = $id");
        $del4 = $conn->query("DELETE FROM tasks_assigned WHERE user = $id");
        $del5 = $conn->query("DELETE FROM log WHERE user = $id");
        $del6 = $conn->query("DELETE FROM timetracker WHERE user = $id");
        $del7 = $conn->query("DELETE FROM roles_assigned WHERE user = $id");
        if ($del) {
            $mylog->add($name, 'user', 3, 0);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get a user profile
     *
     * @param int $id User ID
     * @return array $profile Profile
     */
    function getProfile($id)
    {
        global $conn;
        $id = (int) $id;

        $sel = $conn->prepare("SELECT * FROM user WHERE ID = ?");
        $sel->execute(array($id));

		$profile = $sel->fetch();
        if (!empty($profile)) {

            $rolesobj = (object) new roles();
            $profile["role"] = $rolesobj->getUserRole($profile["ID"]);

            return $profile;
        } else {
            return false;
        }
    }
    function getUserByName($name){
        global $conn;
        $name = $conn->quote($name);

        $sel = $conn->query("SELECT ID FROM user WHERE name = $name");
        $profileId = $sel->fetch();
        if($profileId > 0){
            return $this->getProfile($profileId["ID"]);
        } else {
            return false;
        }
    }
    /**
     * Get the avatar of a user
     *
     * @param int $id User ID
     * @return array $profile Avatar
     */
    function getAvatar($id)
    {
        $id = (int) $id;
        global $conn;
        $sel = $conn->prepare("SELECT avatar FROM user WHERE ID = ?");
    	$sel->execute(array($id));

        $profile = $sel->fetch();
        $profile = $profile[0];

        if (!empty($profile)) {
            return $profile;
        } else {
            return false;
        }
    }

    /**
     * Returns a hash of the password.
     *
     * @param $password
     * @return string
     */
    function hash($password) {
        $hashedPassword = sha1($password);

        return $hashedPassword;
    }

    /**
     * Log a user in
     *
     * @param string $user User name
     * @param string $pass Password
     * @return bool
     */
    function login($user, $pass)
    {
        global $conn;

        if (!$user) {
            return false;
        }
        $user = $conn->quote($user);
        $hash = $this->hash($pass);

        $sel1 = $conn->query("SELECT ID,name,locale,lastlogin,gender FROM user WHERE (name = $user OR email = $user) AND pass = '$hash'");
        $chk = $sel1->fetch();
        if ($chk["ID"] != "") {
            $rolesobj = new roles();
            $now = time();
            $_SESSION['userid'] = $chk['ID'];
            $_SESSION['username'] = stripslashes($chk['name']);
            $_SESSION['lastlogin'] = $now;
            $_SESSION['userlocale'] = $chk['locale'];
            $_SESSION['usergender'] = $chk['gender'];
            $_SESSION["userpermissions"] = $rolesobj->getUserRole($chk["ID"]);

            $userid = $_SESSION['userid'];
            $seid = session_id();
            $staylogged = getArrayVal($_POST, 'staylogged');

            if ($staylogged == 1) {
                setcookie("PHPSESSID", "$seid", time() + 14 * 24 * 3600);
            }
            $upd1 = $conn->query("UPDATE user SET lastlogin = '$now' WHERE ID = $userid");
            return true;
        } else {
            return false;
        }
    }

    /**
     * Logout
     *
     * @return bool
     */
    function logout()
    {
        session_start();
        session_destroy();
        session_unset();
        setcookie("PHPSESSID", "");
        return true;
    }

    /**
     * Returns all users
     *
     * @param int $lim Limit
     * @param int $offset Offset
     * @return array $users Registered members
     */
    function getAllUsers($limit = 10, $offset = 0)
    {
        global $conn;

        $limit = (int) $limit;
        $offset = (int) $offset;

        $sel2 = $conn->query("SELECT ID FROM `user` ORDER BY ID DESC LIMIT $limit OFFSET $offset");

        $users = array();
        while ($user = $sel2->fetch()) {
            array_push($users, $this->getProfile($user["ID"]));
        }

        if (!empty($users)) {
            return $users;
        } else {
            return false;
        }
    }

    /**
     * Get all users who are logged in
     *
     * @param int $offset Allowed time from last login
     * @return array $users
     */
    function getOnlinelist($offset = 200)
    {
        global $conn;

        $offset = (int) $offset;
        $time = time();
        $now = $time - $offset;

        $sel = $conn->query("SELECT * FROM user WHERE lastlogin >= $now");

        $users = array();

        while ($user = $sel->fetch()) {
            array_push($users, $user);
        }

        if (!empty($users)) {
            return $users;
        } else {
            return false;
        }
    }

    /**
     * Is the given user logged in?
     *
     * @param int $user Member ID
     * @param int $offset Allowed time from last login
     * @return bool
     */
    function isOnline($user, $offset = 30)
    {
        global $conn;

        $user = (int) $user;
        $offset = (int) $offset;

        $time = time();
        $now = $time - $offset;

        $sel = $conn->query("SELECT ID FROM user WHERE lastlogin >= $now AND ID = $user");
        $user = $sel->fetch();

        if (!empty($user)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get a user's ID
     *
     * @param string $user Username
     * @return int $theid
     */
    function getId($user)
    {
        global $conn;

        $sel = $conn->query("SELECT ID FROM user WHERE name = {$conn->quote($user)}");
        $id = $sel->fetch();
        $id = $id[0];

        $theid = array();

        $theid["ID"] = $id;

        if ($id > 0) {
            return $theid;
        } else {
            return array();
        }
    }
}

?>
