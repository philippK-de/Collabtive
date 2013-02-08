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

class message
{
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
				global $conn;

        $insStmt = $conn->prepare("INSERT INTO messages (`project`,`title`,`text`,`tags`,`posted`,`user`,`username`,`replyto`,`milestone`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, )");
				$ins = $insStmt->execute(array((int) $project, $title, $text, $tags, time(), (int) $user, $username, (int) $replyto, (int) $milestone));

        $insid = $conn->lastInsertId();
        if ($ins)
        {
            $this->mylog->add($title, 'message', 1, $project);
            return $insid;
        }
        else
        {
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
				global $conn;

        $updStmt = $conn->query("UPDATE messages SET title=?, text=?, tags=? WHERE ID = ?");
				$upd = $updStmt->execute(array($title, $text, $tags, (int) $id));

        if ($upd)
        {
            $proj = $conn->query("SELECT project FROM messages WHERE ID = $id")->fetch();
            $proj = $proj[0];
            $this->mylog->add($title, 'message', 2, $proj);
            return true;
        }
        else
        {
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
				global $conn;
        $id = (int) $id;

        $msg = $conn->query("SELECT title,project FROM messages WHERE ID = $id")->fetch();

        $del = $conn->query("DELETE FROM messages WHERE ID = $id LIMIT 1");
        $del2 = $conn->query("DELETE FROM messages WHERE replyto = $id");
        $del3 = $conn->query("DELETE FROM files_attached WHERE message = $id");
        if ($del)
        {
            $this->mylog->add($msg[0], 'message', 3, $msg[1]);
            return true;
        }
        else
        {
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
				global $conn;
        $id = (int) $id;

        $message = $conn->query("SELECT * FROM messages WHERE ID = $id LIMIT 1")->fetch();

        $tagobj = new tags();
        $milesobj = new milestone();
        if (!empty($message))
        {
            $replies = $conn->query("SELECT COUNT(*) FROM messages WHERE replyto = $id")->fetch();
            $replies = $replies[0];

            $user = new user();
            $avatar = $user->getAvatar($message["user"]);

            $ds = $conn->query("SELECT gender FROM user WHERE ID = $message[user]")->fetch();
            $gender = $ds[0];
            $message["gender"] = $gender;

            $project = $conn->query("SELECT name FROM projekte WHERE ID = $message[project]")->fetch();
            $project = $project[0];
            $project["name"] = stripslashes($project["name"]);
            $message["pname"] = $project;

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
            if ($message["milestone"] > 0)
            {
                $miles = $milesobj->getMilestone($message["milestone"]);
            }
            else
            {
                $miles = array();
            }

            $message["milestones"] = $miles;

            return $message;
        }
        else
        {
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
				global $conn;
        $id = (int) $id;

        $sel = $conn->query("SELECT ID FROM messages WHERE replyto = $id ORDER BY posted DESC");
        $replies = array();

        $tagobj = new tags();
        $milesobj = new milestone();
        $user = new user();
        while ($reply = $sel->fetch())
        {
            if (!empty($reply))
            {
                $thereply = $this->getMessage($reply["ID"]);
                array_push($replies, $thereply);
            }
        }
        if (!empty($replies))
        {
            return $replies;
        }
        else
        {
            return false;
        }
    }

    function getLatestMessages($limit = 5)
    {
				global $conn;
        $limit = (int) $limit;

        $userid = $_SESSION["userid"];
        $sel3 = $conn->query("SELECT projekt FROM projekte_assigned WHERE user = $userid");
        $prstring = "";
        while ($upro = $sel3->fetch())
        {
            $projekt = $upro[0];
            $prstring .= $projekt . ",";
        }

        $prstring = substr($prstring, 0, strlen($prstring)-1);
        if ($prstring)
        {
            $sel1 = $conn->query("SELECT ID FROM messages WHERE project IN($prstring) ORDER BY posted DESC LIMIT $limit ");
            $messages = array();

            $tagobj = new tags();
            $milesobj = new milestone();
            while ($message = $sel1->fetch())
            {
                $themessage = $this->getMessage($message["ID"]);
                array_push($messages, $themessage);
            }
        }
        if (!empty($messages))
        {
            return $messages;
        }
        else
        {
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
				global $conn;
        $project = (int) $project;

        $messages = array();
        $sel1 = $conn->query("SELECT ID FROM messages WHERE project = $project AND replyto = 0 ORDER BY posted DESC");

        $tagobj = new tags();
        $milesobj = new milestone();

        while ($message = $sel1->fetch())
        {
            $themessage = $this->getMessage($message["ID"]);
            array_push($messages, $themessage);
        }

        if (!empty($messages))
        {
            return $messages;
        }
        else
        {
            return false;
        }
    }

    function attachFile($fid, $mid, $id = 0)
    {
				global $conn;
        $fid = (int) $fid;
        $mid = (int) $mid;
        $id = (int) $id;

        $myfile = new datei();
        if ($fid > 0)
        {
            $ins = $conn->query("INSERT INTO files_attached (ID,file,message) VALUES ('',$fid,$mid)");
        }
        else
        {
            $num = $_POST["numfiles"];

            $chk = 0;
						$insStmt= $conn->prepare("INSERT INTO files_attached (ID,file,message) VALUES ('',?,?)");
            for($i = 1;$i <= $num;$i++)
            {
                $fid = $myfile->upload("userfile$i", "files/" . CL_CONFIG . "/$id", $id);
                $ins = $insStmt->execute(array($fid,$mid));
            }
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

    private function getAttachedFiles($msg)
    {
				global $conn;
        $msg = (int) $msg;

        $files = array();
        $sel = $conn->query("SELECT file FROM files_attached WHERE message = $msg");
        while ($file = $sel->fetch())
        {
            $sel2 = $conn->query("SELECT * FROM files WHERE ID = $file[0]");
            $thisfile = $sel2->fetch();
            $thisfile["type"] = str_replace("/", "-", $thisfile["type"]);
            if (isset($thisfile["desc"]))
            {
                $thisfile["desc"] = stripslashes($thisfile["desc"]);
            }
            if (isset($thisfile["tags"]))
            {
                $thisfile["tags"] = stripslashes($thisfile["tags"]);
            }
            if (isset($thisfile["title"]))
            {
                $thisfile["title"] = stripslashes($thisfile["title"]);
            }
            $set = new settings();
            $settings = $set->getSettings();
            $myfile = "./templates/" . $settings["template"] . "/images/files/" . $thisfile["type"] . ".png";
            if (stristr($thisfile["type"], "image"))
            {
                $thisfile["imgfile"] = 1;
            } elseif (stristr($thisfile["type"], "text"))
            {
                $thisfile["imgfile"] = 2;
            }
            else
            {
                $thisfile["imgfile"] = 0;
            }

            if (!file_exists($myfile))
            {
                $thisfile["type"] = "none";
            }
            array_push($files, $thisfile);
        }

        if (!empty($files))
        {
            return $files;
        }
        else
        {
            return false;
        }
    }
}

?>