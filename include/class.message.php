<?php

/**
 * The class provides methods for the realization of messages and replies.
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name message
 * @version 2.0
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
    function add($project, $title, $text, $user, $username, $replyto, $milestone)
    {
        global $conn;

        $insStmt = $conn->prepare("INSERT INTO messages (`project`,`title`,`text`,`posted`,`user`,`username`,`replyto`,`milestone`) VALUES (?, ?, ?, ?, ?, ?, ?, ? )");
        $ins = $insStmt->execute(array((int) $project, $title, $text, time(), (int) $user, $username, (int) $replyto, (int) $milestone));

        $insid = $conn->lastInsertId();
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
    function edit($id, $title, $text)
    {
        global $conn;

        $updStmt = $conn->prepare("UPDATE `messages` SET `title`=?, `text`=? WHERE ID = ?");
        $upd = $updStmt->execute(array($title, $text, (int) $id));

        if ($upd) {
            $proj = $conn->query("SELECT project FROM messages WHERE ID = $id")->fetch();
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
        global $conn;
        $id = (int) $id;

        $msg = $conn->query("SELECT title,project FROM messages WHERE ID = $id")->fetch();

        $del = $conn->query("DELETE FROM messages WHERE ID = $id LIMIT 1");
        $del2 = $conn->query("DELETE FROM messages WHERE replyto = $id");
        $del3 = $conn->query("DELETE FROM files_attached WHERE message = $id");
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
        global $conn;
        $id = (int) $id;

        $messageStmt = $conn->prepare("SELECT * FROM messages WHERE ID = ? LIMIT 1");
		$messageStmt->execute(array($id));
    	$message = $messageStmt->fetch();

        $milesobj = new milestone();
        if (!empty($message)) {
            $replies = $conn->query("SELECT COUNT(*) FROM messages WHERE replyto = $id")->fetch();
            $replies = $replies[0];

            $user = new user();
            $avatar = $user->getAvatar($message["user"]);

            $ds = $conn->query("SELECT gender FROM user WHERE ID = $message[user]")->fetch();
            $gender = $ds[0];
            $message["gender"] = $gender;

            $project = $conn->query("SELECT name FROM projekte WHERE ID = $message[project]")->fetch();
            $message["pname"] = $project[0];
            $posted = date(CL_DATEFORMAT . " - H:i", $message["posted"]);
            $message["postdate"] = $posted;
            $message["endstring"] = $posted;
            $message["replies"] = $replies;
            $message["avatar"] = $avatar;
            $message["title"] = $message["title"];
            $message["text"] = $message["text"];
            $message["username"] = $message["username"];

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
        global $conn;
        $id = (int) $id;

        $sel = $conn->prepare("SELECT ID FROM messages WHERE replyto = ? ORDER BY posted DESC");
    	$sel->execute(array($id));

        $replies = array();

        $milesobj = new milestone();
        $user = new user();
        while ($reply = $sel->fetch()) {
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

    /**
     * Returns the most recent messages of a user, from the projects he is assigned to
     *
     * @param int $limit Limits the number of messages to return.
     * @return array $message Eigenschaften der Nachricht
     */
    function getLatestMessages($limit = 25)
    {
        global $conn;
        $limit = (int) $limit;
        // Get the id of the logged in user and get his projects
        $userid = $_SESSION["userid"];
    	$userid = (int)$userid;

        $sel3 = $conn->query("SELECT projekt FROM projekte_assigned WHERE user = $userid");
        // Assemble a string of project IDs the user belongs to for IN() query.
        $prstring = "";
        while ($upro = $sel3->fetch()) {
            $projekt = $upro[0];
            $prstring .= $projekt . ",";
        }

        $prstring = substr($prstring, 0, strlen($prstring)-1);
        if ($prstring) {
            $sel1 = $conn->query("SELECT ID FROM messages WHERE project IN($prstring) ORDER BY posted DESC LIMIT $limit ");
            $messages = array();

            $milesobj = new milestone();
            while ($message = $sel1->fetch()) {
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
        global $conn;
        $project = (int) $project;

        $messages = array();
        $sel1 = $conn->prepare("SELECT ID FROM messages WHERE project = ? AND replyto = 0 ORDER BY posted DESC");
		$sel1->execute(array($project));

        $milesobj = new milestone();

        while ($message = $sel1->fetch()) {
            $themessage = $this->getMessage($message["ID"]);
            array_push($messages, $themessage);
        }

        if (!empty($messages)) {
            return $messages;
        } else {
            return false;
        }
    }

    /**
     * Attach a file to a message
     *
     * @param int $fid ID of the file to be attached
     * @param int $mid ID of the message where the file will be attached
     * @param int $id optional param denoting the project ID where the file will be uploaded to (if so)
     * @return bool
     */
    function attachFile($fid, $mid, $id = 0)
    {
        global $conn;
        $fid = (int) $fid;
        $mid = (int) $mid;
        $id = (int) $id;

        $myfile = new datei();
        // If a file ID is given, the given file will be attached
        // If no file ID is given, the file will be uploaded to the project defined by $id and then attached
        if ($fid > 0) {
            $ins = $conn->query("INSERT INTO files_attached (ID,file,message) VALUES ('',$fid,$mid)");
        } else {
            $num = $_POST["numfiles"];

            $chk = 0;
            $insStmt = $conn->prepare("INSERT INTO files_attached (ID,file,message) VALUES ('',?,?)");
            for($i = 1;$i <= $num;$i++) {
                $fid = $myfile->upload("userfile$i", "files/" . CL_CONFIG . "/$id", $id);
                $ins = $insStmt->execute(array($fid, $mid));
            }
        }
        if ($ins) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get files attached to a message
     *
     * @param int $msg ID of the message
     * @return array $files Attached files
     */
    private function getAttachedFiles($msg)
    {
        global $conn;
        $msg = (int) $msg;

        $files = array();
        $sel = $conn->query("SELECT file FROM files_attached WHERE message = $msg");
        while ($file = $sel->fetch()) {
            $sel2 = $conn->query("SELECT * FROM files WHERE ID = $file[0]");
            $thisfile = $sel2->fetch();
            $thisfile["type"] = str_replace("/", "-", $thisfile["type"]);

            $set = new settings();
            $settings = $set->getSettings();
        	// Construct the path to the MIME-type icon
        	$myfile = "./templates/" . $settings["template"] . "/theme/" . $settings["theme"] . "/images/files/" . $thisfile['type'] . ".png";
        	if (!file_exists($myfile)) {
        		$thisfile['type'] = "none";
        	}

        	// Determine if it is an image or text file or some other kind of file (required for lightbox)
        	if (stristr($thisfile['type'], "image")) {
        		$thisfile['imgfile'] = 1;
        	} elseif (stristr($thisfile['type'], "text")) {
        		$thisfile['imgfile'] = 2;
        	} else {
        		$thisfile['imgfile'] = 0;
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