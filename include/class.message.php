<?php

/**
 * The class provides methods for the realization of messages and replies.
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name message
 * @version 0.4.6
 * @package Collabtive
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */

class message {
    public $mylog;

    /**
     * Konstruktor
     * Initialisiert den Eventlog
     */
    function __construct()
    {
        $this->mylog = new mylog;
    }

    /**
     * Creates a new message or a reply to a message
     *
     * @param int $project Project ID the message belongs to
     * @param string $title Title/Subject of the message
     * @param string $text Textbody of the message
     * @param string $tags Tags for the message
     * @param int $user User ID of the user adding the message
     * @param string $username Name of the user adding the message
     * @param int $replyto ID of the message this message is replying to. Standardmessage: 0
     * @return bool
     */
    function add($project, $title, $text, $tags, $user, $username, $replyto, $milestone)
    {
        $project = (int) $project;
        $title = mysql_real_escape_string($title);
        $text = mysql_real_escape_string($text);
        $tags = mysql_real_escape_string($tags);
        $user = (int) $user;
        $username = mysql_real_escape_string($username);
        $replyto = (int) $replyto;
        $milestone = (int) $milestone;
        $posted = time();

        $sql = "INSERT INTO messages (`project`,`title`,`text`,`tags`,`posted`,`user`,`username`,`replyto`,`milestone`) VALUES ($project,'$title','$text','$tags','$posted',$user,'$username',$replyto,$milestone) ";

        $ins = mysql_query($sql);

        $insid = mysql_insert_id();
        if ($ins) {
            $this->mylog->add($title, 'message', 1, $project);
            return $insid;
        } else {
            return false;
        }
    }

    /**
     * Edits a message
     *
     * @param int $id Eindeutige Nummer der Nachricht
     * @param string $title Titel der Nachricht
     * @param string $text Text der Nachricht
     * @param string $tags Tags for the message
     * @return bool
     */
    function edit($id, $title, $text, $tags)
    {
        $id = (int) $id;
        $title = mysql_real_escape_string($title);
        $text = mysql_real_escape_string($text);
        $tags = mysql_real_escape_string($tags);

        $upd = mysql_query("UPDATE messages SET title='$title', text='$text', tags='$tags' WHERE ID = $id");

        if ($upd) {
            $proj = mysql_query("SELECT project FROM messages WHERE ID = $id");
            $proj = mysql_fetch_row($proj);
            $proj = $proj[0];
            $this->mylog->add($title, 'message', 2, $proj);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Deletes a message and all dependent messages
     *
     * @param int $id Eindeutige Nummer der Nachricht
     * @return bool
     */
    function del($id)
    {
        $id = (int) $id;

        $msg = mysql_query("SELECT title,project FROM messages WHERE ID = $id");
        $msg = mysql_fetch_row($msg);

        $del = mysql_query("DELETE FROM messages WHERE ID = $id LIMIT 1");
        $del2 = mysql_query("DELETE FROM messages WHERE replyto = $id");
        $del3 = mysql_query("DELETE FROM files_attached WHERE message = $id");
        if ($del) {
            $this->mylog->add($msg[0], 'message', 3, $msg[1]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return a message including its answers
     *
     * @param int $id Eindeutige Nummer der Nachricht
     * @return array $message Eigenschaften der Nachricht
     */
    function getMessage($id)
    {
        $id = (int) $id;

        $sel = mysql_query("SELECT * FROM messages WHERE ID = $id LIMIT 1");
        $message = mysql_fetch_array($sel, MYSQL_ASSOC);

        $tagobj = new tags();
        $milesobj = new milestone();
        if (!empty($message)) {
            $replies = mysql_query("SELECT COUNT(*) FROM messages WHERE replyto = $id");
            $replies = mysql_fetch_row($replies);
            $replies = $replies[0];

            $user = new user();
            $avatar = $user->getAvatar($message["user"]);

            $sel = mysql_query("SELECT gender FROM user WHERE ID = $message[user]");
            $ds = mysql_fetch_row($sel);
            $gender = $ds[0];
            $message["gender"] = $gender;

            $project = mysql_query("SELECT name FROM projekte WHERE ID = $message[project]");
            $project = mysql_fetch_row($project);
            $project = $project[0];
            if ($project) {
                $project["name"] = stripslashes($project["name"]);
                $message["pname"] = $project;
            }
            else
            {
				$message["pname"] = "";
			}
            $posted = date(CL_DATEFORMAT . " - H:i", $message["posted"]);
            $message["postdate"] = $posted;
            $message["endstring"] = $posted;
            $message["replies"] = $replies;
            $message["avatar"] = $avatar;
            $message["title"] = stripslashes($message["title"]);
            $message["text"] = stripslashes($message["text"]);
            $message["username"] = stripslashes($message["username"]);
            $message["tagsarr"] = $tagobj->splitTagStr($message["tags"]);
            $message["tagnum"] = count($message["tagsarr"]);

            $attached = $this->getAttachedFiles($message["ID"]);
            $message["files"] = $attached;
            if ($message["milestone"] > 0) {
                $miles = $milesobj->getMilestone($message["milestone"]);
            } else {
                $miles = array();
            }

            $message["milestones"] = $miles;

            return $message;
        } else {
            return false;
        }
    }

    /**
     * Return all answers to a given message
     *
     * @param int $id Eindeutige Nummer der Nachricht
     * @return array $replies Antworten zur Nachricht
     */
    function getReplies($id)
    {
        $id = (int) $id;

        $sel = mysql_query("SELECT ID FROM messages WHERE replyto = $id ORDER BY posted DESC");
        $replies = array();

        $tagobj = new tags();
        $milesobj = new milestone();
        $user = new user();
        while ($reply = mysql_fetch_array($sel)) {
            if (!empty($reply)) {
                $thereply = $this->getMessage($reply["ID"]);
                array_push($replies, $thereply);
            }
        }
        if (!empty($replies)) {
            return $replies;
        } else {
            return false;
        }
    }

    function getLatestMessages($limit = 5)
    {
        $limit = (int) $limit;

        $userid = $_SESSION["userid"];
        $sel3 = mysql_query("SELECT projekt FROM projekte_assigned WHERE user = $userid");
        $prstring = "";
        while ($upro = mysql_fetch_row($sel3)) {
            $projekt = $upro[0];
            $prstring .= $projekt . ",";
        }

        $prstring = substr($prstring, 0, strlen($prstring)-1);
        if ($prstring) {
            $sel1 = mysql_query("SELECT ID FROM messages WHERE project IN($prstring) ORDER BY posted DESC LIMIT $limit ");
            $messages = array();

            $tagobj = new tags();
            $milesobj = new milestone();
            while ($message = mysql_fetch_array($sel1)) {
                $themessage = $this->getMessage($message["ID"]);
                array_push($messages, $themessage);
            }
        }
        if (!empty($messages)) {
            return $messages;
        } else {
            return false;
        }
    }

    /**
     * Returns all messages belonging to a project (without answers)
     *
     * @param int $project Eindeutige Nummer des Projekts
     * @return array $messages Nachrichten zum Projekt
     */
    function getProjectMessages($project)
    {
        $project = (int) $project;

        $messages = array();
        $sel1 = mysql_query("SELECT ID FROM messages WHERE project = $project AND replyto = 0 ORDER BY posted DESC");

        $tagobj = new tags();
        $milesobj = new milestone();

        while ($message = mysql_fetch_array($sel1)) {
            $themessage = $this->getMessage($message["ID"]);
            array_push($messages, $themessage);
        }

        if (!empty($messages)) {
            return $messages;
        } else {
            return false;
        }
    }

    function attachFile($fid, $mid, $id = 0)
    {
        $fid = (int) $fid;
        $mid = (int) $mid;
        $id = (int) $id;

        $myfile = new datei();
        if ($fid > 0) {
            $ins = mysql_query("INSERT INTO files_attached (ID,file,message) VALUES ('',$fid,$mid)");
        } else {
            $num = $_POST["numfiles"];

            $chk = 0;
            for($i = 1;$i <= $num;$i++) {
                $fid = $myfile->upload("userfile$i", "files/" . CL_CONFIG . "/$id", $id);
                $ins = mysql_query("INSERT INTO files_attached (ID,file,message) VALUES ('',$fid,$mid)");
            }
        }
        if ($ins) {
            return true;
        } else {
            return false;
        }
    }

    private function getAttachedFiles($msg)
    {
        $msg = (int) $msg;

        $files = array();
        $sel = mysql_query("SELECT file FROM files_attached WHERE message = $msg");
        while ($file = mysql_fetch_row($sel)) {
            $sel2 = mysql_query("SELECT * FROM files WHERE ID = $file[0]");
            $thisfile = mysql_fetch_array($sel2);
            $thisfile["type"] = str_replace("/", "-", $thisfile["type"]);
            if (isset($thisfile["desc"])) {
                $thisfile["desc"] = stripslashes($thisfile["desc"]);
            }
            if (isset($thisfile["tags"])) {
                $thisfile["tags"] = stripslashes($thisfile["tags"]);
            }
            if (isset($thisfile["title"])) {
                $thisfile["title"] = stripslashes($thisfile["title"]);
            }
            $set = new settings();
            $settings = $set->getSettings();
            $myfile = "./templates/" . $settings["template"] . "/images/files/" . $thisfile["type"] . ".png";
            if (stristr($thisfile["type"], "image")) {
                $thisfile["imgfile"] = 1;
            } elseif (stristr($thisfile["type"], "text")) {
                $thisfile["imgfile"] = 2;
            } else {
                $thisfile["imgfile"] = 0;
            }

            if (!file_exists($myfile)) {
                $thisfile["type"] = "none";
            }
            array_push($files, $thisfile);
        }

        if (!empty($files)) {
            return $files;
        } else {
            return false;
        }
    }
}

?>