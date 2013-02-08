<?php
/*
 * This class provides methods to realize a chat.
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name chat
 * @version 0.4
 * @package Collabtive
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class chat
{
    /**
    * Constructor
    *
    * @access protected
    */
    function __construct()
    {
    }

    /**
    * Start a chat
    *
    * @param string $userto Username
    * @param string $userto_id User ID
    * @return bool $cookies_set
    */
    function start($userto, $userto_id)
    {
        $now = time();
        $now = $now - 35;
        $cook = "chatstart" . $userto_id;
        $cook2 = "chatwin" . $userto_id;

        $cookie1 = setcookie("$cook", "$now");
        $cookie2 = setcookie("$cook2", "1");
        $cookies_set = ($cookie1 AND $cookie2);
        return ($cookies_set);
    }

    function post($content, $userto, $userto_id)
    {
				global $conn;

        $insStmt = $conn->prepare("INSERT INTO chat (ID, time, ufrom, ufrom_id, userto, userto_id, text) VALUES ('',?, ?, ?, ?, ?, ?)");
				$ins = $insStmt->execute(array(time(), $username, $userid, $userto, $userto_id, strip_tags($content)));
    }

    function pull($userto_id)
    {
				global $conn;
        $cook = "chatstart" . $userto_id;
        $start = $_COOKIE["$cook"];

        if (!$start)
        {
            $start = 0;
        }

        $sel = $conn->query("SELECT * FROM chat WHERE ufrom_id IN($userid,$userto_id) AND userto_id IN($userid,$userto_id) AND time > $start ORDER by time ASC");

        while ($chat = $sel->fetch())
        {
            $date = date("H:i", $chat["time"]);
            echo "[$date] <b>$chat[ufrom]:</b> $chat[text]";
            echo "<br />";
        }
    }

    function chk()
    {
				global $conn;
        $now = time();
        $now = $now - 20;

        $sel = $conn->query("SELECT ufrom_id,ufrom FROM chat WHERE userto_id  = $userid AND time > $now");

        while ($chk = $sel->fetch())
        {
            $cook = "chatwin" . $chk[0];
            if (!$_COOKIE[$cook])
            {
                echo "<script type = \"text/javascript\">openChatwin('$chk[1]',$chk[0]);</script>";
            }
        }
        $mynow = time();
        $conn->query("UPDATE user SET lastlogin='$mynow' WHERE ID = $userid");
    }
}
?>