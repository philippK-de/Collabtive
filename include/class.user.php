<?php
/**
 * Provides methods to interact with users
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name user
 * @version 0.7
 * @package Collabtive
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or laterg
 */
class user
{
    public $mylog;

    /**
     * Constructor
     * Initializes event log
     */
    function __construct()
    {
        $this->mylog = new mylog;
    }

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
        $name = mysql_real_escape_string($name);
        $email = mysql_real_escape_string($email);
		$company = mysql_real_escape_string($company);
		$pass = mysql_real_escape_string($pass);
        $locale = mysql_real_escape_string($locale);
        $tags = mysql_real_escape_string($tags);
        $rate = (float) $rate;

        $pass = sha1($pass);

        $ins1 = mysql_query("INSERT INTO user (name,email,company,pass,locale,tags,rate) VALUES ('$name','$email','$company','$pass','$locale','$tags','$rate')");

        if ($ins1)
        {
            $insid = mysql_insert_id();
            $this->mylog->add($name, 'user', 1, 0);
            return $insid;
        }
        else
        {
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
        $name = mysql_real_escape_string($name);
        $realname = mysql_real_escape_string($realname);
		$company = mysql_real_escape_string($company);
        $email = mysql_real_escape_string($email);
        $tel1 = mysql_real_escape_string($tel1);
        $tel2 = mysql_real_escape_string($tel2);
        $zip = mysql_real_escape_string($zip);
        $gender = mysql_real_escape_string($gender);
        $url = mysql_real_escape_string($url);
        $address1 = mysql_real_escape_string($address1);
        $address2 = mysql_real_escape_string($address2);
        $state = mysql_real_escape_string($state);
        $country = mysql_real_escape_string($country);
        $tags = mysql_real_escape_string($tags);
        $locale = mysql_real_escape_string($locale);
        $avatar = mysql_real_escape_string($avatar);

        $rate = (float) $rate;
        $id = (int) $id;

        if ($avatar != "")
        {
            $upd = mysql_query("UPDATE user SET name='$name',email='$email',tel1='$tel1', tel2='$tel2',company='$company',zip='$zip',gender='$gender',url='$url',adress='$address1',adress2='$address2',state='$state',country='$country',tags='$tags',locale='$locale',avatar='$avatar',rate='$rate' WHERE ID = $id");
        }
        else
        {
            $upd = mysql_query("UPDATE user SET name='$name',email='$email', tel1='$tel1', tel2='$tel2', company='$company',zip='$zip',gender='$gender',url='$url',adress='$address1',adress2='$address2',state='$state',country='$country',tags='$tags',locale='$locale',rate='$rate' WHERE ID = $id");
        }
        if ($upd)
        {
            $this->mylog->add($name, 'user', 2, 0);
            return true;
        }
        else
        {
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
		$email = mysql_real_escape_string($email);

		$sel = mysql_query("SELECT ID, email FROM user");
		while ($user = mysql_fetch_array($sel))
		{
			if ($user["email"] == $email)
			{
				$id = $user["ID"];
			}
		}

		if (isset($id))
		{
 			$dummy = array_merge(range('0', '9'), range('a', 'z'), range('A', 'Z'),range('0','9'));
 			shuffle($dummy);
 			mt_srand((double)microtime()*1000000);
 			$newpass = "";
 			for ($i = 1; $i <= 10; $i++)
 			{
 				$swap = mt_rand(0,count($dummy)-1);
 				$tmp = $dummy[$swap];
 				$newpass .= $tmp;
 			}

			$sha1pass = sha1($newpass);

			$upd = mysql_query("UPDATE user SET `pass` = '$sha1pass' WHERE ID = $id");
			if ($upd)
			{
				return $newpass;
			}
			else
			{
				return false;
			}
		}
        else
        {
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
        $oldpass = mysql_real_escape_string($oldpass);
        $newpass = mysql_real_escape_string($newpass);
        $repeatpass = mysql_real_escape_string($repeatpass);
        $id = (int) $id;

        if ($newpass != $repeatpass)
        {
            return false;
        }
        $id = mysql_real_escape_string($id);
        $newpass = sha1($newpass);

        $oldpass = sha1($oldpass);
        $chk = mysql_query("SELECT ID, name FROM user WHERE ID = $id AND pass = '$oldpass'");
        $chk = mysql_fetch_row($chk);
        $chk = $chk[0];
        $name = $chk[1];
        if (!$chk)
        {
            return false;
        }

        $upd = mysql_query("UPDATE user SET pass='$newpass' WHERE ID = $id");
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
     * Change password as admin
     *
     * @param int $id User ID
     * @param string $newpass New password
     * @param string $repeatpass Repetition of the new password
     * @return bool
     */
    function admin_editpass($id, $newpass, $repeatpass)
    {
        $newpass = mysql_real_escape_string($newpass);
        $repeatpass = mysql_real_escape_string($repeatpass);
        $id = (int) $id;

        if ($newpass != $repeatpass)
        {
            return false;
        }
        $id = mysql_real_escape_string($id);
        $newpass = sha1($newpass);

        $upd = mysql_query("UPDATE user SET pass='$newpass' WHERE ID = $id");
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
     * Delete a user
     *
     * @param int $id User ID
     * @return bool
     */
    function del($id)
    {
        $id = (int) $id;

        $chk = mysql_query("SELECT name FROM user WHERE ID = $id");
        $chk = mysql_fetch_row($chk);
        $name = $chk[0];

        $del = mysql_query("DELETE FROM user WHERE ID = $id");
        $del2 = mysql_query("DELETE FROM projekte_assigned WHERE user = $id");
        $del3 = mysql_query("DELETE FROM milestones_assigned WHERE user = $id");
        $del4 = mysql_query("DELETE FROM tasks_assigned WHERE user = $id");
        $del5 = mysql_query("DELETE FROM log WHERE user = $id");
        $del6 = mysql_query("DELETE FROM timetracker WHERE user = $id");
		$del7 = mysql_query("DELETE FROM roles_assigned WHERE user = $id");
        if ($del)
        {
            $this->mylog->add($name, 'user', 3, 0);
            return true;
        }
        else
        {
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
        $id = (int) $id;

        $sel = mysql_query("SELECT * FROM user WHERE ID = $id");
        $profile = mysql_fetch_array($sel);
        if (!empty($profile))
        {
            $profile["name"] = stripslashes($profile["name"]);
            if (isset($profile["company"]))
            {
                $profile["company"] = stripslashes($profile["company"]);
            }
            if (isset($profile["adress"]))
            {
                $profile["adress"] = stripslashes($profile["adress"]);
            }
            if (isset($profile["adress2"]))
            {
                $profile["adress2"] = stripslashes($profile["adress2"]);
            }
            if (isset($profile["state"]))
            {
                $profile["state"] = stripslashes($profile["state"]);
            }
            if (isset($profile["country"]))
            {
                $profile["country"] = stripslashes($profile["country"]);
            }
            $tagsobj = new tags();
            $profile["tagsarr"] = $tagsobj->splitTagStr($profile["tags"]);

            $rolesobj = (object) new roles();
            $profile["role"] = $rolesobj->getUserRole($profile["ID"]);

            return $profile;
        }
        else
        {
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

        $sel = mysql_query("SELECT avatar FROM user WHERE ID = $id");
        $profile = mysql_fetch_row($sel);
        $profile = $profile[0];

        if (!empty($profile))
        {
            return $profile;
        }
        else
        {
            return false;
        }
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
        if (!$user)
        {
            return false;
        }
        $user = mysql_real_escape_string($user);
        $pass = mysql_real_escape_string($pass);
        $pass = sha1($pass);

        $sel1 = mysql_query("SELECT ID,name,locale,lastlogin,gender FROM user WHERE (name = '$user' OR email = '$user') AND pass = '$pass'");
        $chk = mysql_fetch_array($sel1);
        if ($chk["ID"] != "")
        {
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

            if ($staylogged == 1)
            {
                setcookie("PHPSESSID", "$seid", time() + 14 * 24 * 3600);
            }
            $upd1 = mysql_query("UPDATE user SET lastlogin = '$now' WHERE ID = $userid");
            return true;
        }
        else
        {
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
     * @return array $users Registrierte Mitglieder
     */
    function getAllUsers($lim = 10)
    {
        $lim = (int) $lim;

        $sel = mysql_query("SELECT COUNT(*) FROM `user`");
        $num = mysql_fetch_row($sel);
        $num = $num[0];
        SmartyPaginate::connect();
        // set items per page
        SmartyPaginate::setLimit($lim);
        SmartyPaginate::setTotal($num);

        $start = SmartyPaginate::getCurrentIndex();
        $lim = SmartyPaginate::getLimit();

       $sel2 = mysql_query("SELECT ID FROM `user` ORDER BY ID DESC LIMIT $start,$lim");

        $users = array();
        while ($user = mysql_fetch_array($sel2))
        {
            array_push($users, $this->getProfile($user["ID"]));
        }

        if (!empty($users))
        {
            return $users;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get all users who are logged in
     *
     * @param int $offset Allowed time from last login
     * @return array $users
     */
    function getOnlinelist($offset = 300)
    {
        $offset = (int) $offset;
        $time = time();
        $now = $time - $offset;

        $sel = mysql_query("SELECT * FROM user WHERE lastlogin >= $now");

        $users = array();

        while ($user = mysql_fetch_array($sel))
        {
            $user["name"] = stripslashes($user["name"]);
            $user["company"] = stripslashes($user["company"]);
            $user["adress"] = stripslashes($user["adress"]);
            $user["adress2"] = stripslashes($user["adress2"]);
            $user["state"] = stripslashes($user["state"]);
            $user["country"] = stripslashes($user["country"]);
            array_push($users, $user);
        }

        if (!empty($users))
        {
            return $users;
        }
        else
        {
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
        $user = (int) $user;
        $offset = (int) $offset;

        $time = time();
        $now = $time - $offset;

        $sel = mysql_query("SELECT ID FROM user WHERE lastlogin >= $now AND ID = $user");
        $user = mysql_fetch_row($sel);

        if (!empty($user))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get a user's ID
     *
     * @param string $user Username
     * @return int $theid
     */
    function getId($user){
        $user = mysql_real_escape_string($user);

        $sel = mysql_query("SELECT ID FROM user WHERE name = '$user'");
        $id = mysql_fetch_row($sel);
        $id = $id[0];

        $theid = array();

        $theid["ID"] = $id;

        if($id > 0)
        {
            return $theid;
        }
        else
        {
            return array();
        }
    }
}

?>