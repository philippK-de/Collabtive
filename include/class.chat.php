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
        $content = strip_tags($content);
        $content = mysql_real_escape_string($content);
        $userto = mysql_real_escape_string($userto);
        $userto_id = mysql_real_escape_string($userto_id);
        $now = time();

        mysql_query("INSERT INTO chat (ID,time,ufrom,ufrom_id,userto,userto_id,text) VALUES ('','$now','$username','$userid','$userto','$userto_id','$content')");
    }

    function pull($userto_id)
    {
        $cook = "chatstart" . $userto_id;
        $start = $_COOKIE["$cook"];

        if (!$start)
        {
            $start = 0;
        }

        $sel = mysql_query("SELECT * FROM chat WHERE ufrom_id IN($userid,$userto_id) AND userto_id IN($userid,$userto_id) AND time > $start ORDER by time ASC");

        while ($chat = mysql_fetch_array($sel))
        {
            $date = date("H:i", $chat["time"]);
            echo "[$date] <b>$chat[ufrom]:</b> $chat[text]";
            echo "<br />";
        }
    }

    function chk()
    {
        $now = time();
        $now = $now - 20;

        $sel = mysql_query("SELECT ufrom_id,ufrom FROM chat WHERE userto_id  = $userid AND time > $now");

        while ($chk = mysql_fetch_row($sel))
        {
            $cook = "chatwin" . $chk[0];
            if (!$_COOKIE[$cook])
            {
                echo "<script type = \"text/javascript\">openChatwin('$chk[1]',$chk[0]);</script>";
            }
        }
        $mynow = time();
        mysql_query("UPDATE user SET lastlogin='$mynow' WHERE ID = $userid");
    }
}
?>