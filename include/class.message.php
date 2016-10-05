<?php

/**
 * The class provides methods for the realization of messages and replies.
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name message
 * @version 2.0
 * @package Collabtive
 * @link http://collabtive.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class message
{
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
     * @param int $milestone ID of the milestone to attach this message to
     * @return bool
     */
    function add($project, $title, $text, $user, $username, $replyto, $milestone)
    {
        global $conn, $mylog;

        $insStmt = $conn->prepare("INSERT INTO messages (`project`,`title`,`text`,`tags`,`posted`,`user`,`username`,`replyto`,`milestone`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? )");
        $ins = $insStmt->execute(array((int)$project, $title, $text, "", time(), (int)$user, $username, (int)$replyto, (int)$milestone));

        $insid = $conn->lastInsertId();
        if ($ins) {
            $mylog->add($title, 'message', 1, $project);
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
        global $conn, $mylog;

        $updStmt = $conn->prepare("UPDATE `messages` SET `title`=?, `text`=? WHERE ID = ?");
        $upd = $updStmt->execute(array($title, $text, (int)$id));

        if ($upd) {
            $projStmt = $conn->prepare("SELECT project FROM messages WHERE ID = ?");
            $projStmt->execute(array($id));

            $project = $projStmt->fetch();
            $projectId = $project[0];
            $mylog->add($title, 'message', 2, $projectId);
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
        global $conn, $mylog;
        $id = (int)$id;

        $msgStmt = $conn->prepare("SELECT title,project FROM messages WHERE ID = ?");
        $msgStmt->execute(array($id));
        $msg = $msgStmt->fetch();

        //delete the message
        $delStmt = $conn->prepare("DELETE FROM messages WHERE ID = ?");
        $del = $delStmt->execute(array($id));

        //delete replies to this message
        $deleteRepliesStmt = $conn->prepare("DELETE FROM messages WHERE replyto = ?");
        $deleteRepliesStmt->execute(array($id));

        //delete user<->message connections
        $deleteUsersAssingedStmt = $conn->prepare("DELETE FROM messages_assigned WHERE message = ?");
        $deleteUsersAssingedStmt->execute(array($id));

        //delete file attachments
        $deleteFileAttachmentsStmt = $conn->prepare("DELETE FROM files_attached WHERE message = ?");
        $deleteFileAttachmentsStmt->execute(array($id));


        if ($del) {
            $mylog->add($msg[0], 'message', 3, $msg[1]);
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
    function getMessage($id, $recurseReplies = true)
    {
        global $conn;
        $id = (int)$id;

        $messageStmt = $conn->prepare("SELECT * FROM messages WHERE ID = ? LIMIT 1");
        $messageStmt->execute(array($id));
        $message = $messageStmt->fetch();

        $milesobj = new milestone();
        if (!empty($message)) {
            $user = new user();
            $avatar = $user->getAvatar($message["user"]);

            $message["gender"] = "m";

            $project = $conn->query("SELECT name FROM projekte WHERE ID = $message[project]")->fetch();
            $message["pname"] = $project[0];
            $posted = date(CL_DATEFORMAT . " - H:i", $message["posted"]);
            $message["postdate"] = $posted;
            $message["endstring"] = $posted;
            $message["avatar"] = $avatar;

            if ($recurseReplies) {
                //get children
                $replies = $this->getReplies($id);
                if (!$replies) {
                    $message["replies"] = 0;
                } else {
                    $message["replies"] = count($replies);
                }
                $message["listReplies"] = $replies;
            }
            //assume no files
            $message["hasFiles"] = false;

            //get files attached to this message
            $attached = $this->getAttachedFiles($message["ID"]);
            $message["files"] = $attached;

            //if there is files set hasFiles to true, else false
            if (!empty($attached)) {
                $message["hasFiles"] = true;
            }

            $message["hasMilestones"] = false;
            $miles = array();
            if ($message["milestone"] > 0) {
                $miles = $milesobj->getMilestone($message["milestone"]);
                $message["hasMilestones"] = true;
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
        $id = (int)$id;

        $sel = $conn->prepare("SELECT ID FROM messages WHERE replyto = ? ORDER BY posted DESC");
        $sel->execute(array($id));

        $replies = array();

        while ($reply = $sel->fetch()) {
            if (!empty($reply)) {
                $replyMessage = $this->getMessage($reply["ID"]);
                array_push($replies, $replyMessage);
                //recursive call to get all replies to this reply
                $this->getReplies($reply["ID"]);
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
        $limit = (int)$limit;
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

        $prstring = substr($prstring, 0, strlen($prstring) - 1);
        if ($prstring) {
            $sel1 = $conn->query("SELECT ID FROM messages WHERE project IN($prstring) ORDER BY posted DESC LIMIT $limit ");
            $messages = array();

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
     * Returns all messages belonging to a project (excluding replies and personal messages).
     *
     * @param int $project Unique project number
     * @param int $limit Limit for the DB query
     * @param int $offset Offset for the DB query
     * @return array $messages Project messages
     */
    function getProjectMessages($project, $limit = 0, $offset = 0)
    {
        global $conn;
        $project = (int)$project;
        $limit = (int) $limit;
        $offset = (int) $offset;

        $messages = array();
        if ($limit > 0) {
            $projectMessagesStmt = $conn->prepare("
                  SELECT messages.ID FROM messages
                  LEFT JOIN messages_assigned ON messages.ID = messages_assigned.message
                  WHERE messages_assigned.message IS NULL
                  AND project = ?
                  AND replyto = 0
                  ORDER BY posted DESC
                  LIMIT $limit
                  OFFSET $offset");

        } else {
            $projectMessagesStmt = $conn->prepare("
                  SELECT messages.ID FROM messages
                  LEFT JOIN messages_assigned ON messages.ID = messages_assigned.message
                  WHERE messages_assigned.message IS NULL
                  AND project = ?
                  AND replyto = 0
                  ORDER BY posted DESC");

        }

        $projectMessagesStmt->execute(array($project));

        while ($messageId = $projectMessagesStmt->fetch()) {
            $message = $this->getMessage($messageId["ID"]);
            array_push($messages, $message);
        }

        if (!empty($messages)) {
            return $messages;
        } else {
            return false;
        }
    }

    function getUserMessages($user, $limit = 10, $offset = 0){
        global $conn;
        $user = (int)$user;
        $limit = (int) $limit;
        $offset = (int) $offset;

        $messages = array();
        $userMessagesStmt =
            $conn->prepare("SELECT message FROM messages_assigned WHERE user = ? ORDER BY ID DESC LIMIT $limit OFFSET $offset");

        $userMessagesStmt->execute(array($user));

        while ($messageId = $userMessagesStmt->fetch()) {
            $message = $this->getMessage($messageId["message"]);
            if($message){
                array_push($messages, $message);
            }
        }

        if (!empty($messages)) {
            return $messages;
        } else {
            return false;
        }
    }

    /**
     * Counts all messages belonging to a project (excluding replies and personal messages).
     *
     * @param int $project Unique project number
     * @return array $number Number of project messages
     */
    function countProjectMessages($project)
    {
        global $conn;
        $project = (int) $project;

        $messages = array();
        $sel1 = $conn->prepare("
                  SELECT COUNT(*) FROM messages
                  LEFT JOIN messages_assigned ON messages.ID = messages_assigned.message
                  WHERE messages_assigned.message IS NULL
                  AND project = ?
                  AND replyto = 0");

        $sel1->execute(array($project));

        $number = $sel1->fetch();
        $number = $number["COUNT(*)"];

        return $number;
    }

    /**
     * Returns all messages belonging to a project (without answers)
     *
     * @param int $project Eindeutige Nummer des Projekts
     * @return array $messages Nachrichten zum Projekt
     */
    function countUserMessages($user)
    {
        global $conn;
        $user = (int)$user;

        $sel1 = $conn->prepare("SELECT COUNT(*) FROM messages_assigned WHERE user = ?");
        $sel1->execute(array($user));

        $number = $sel1->fetch();
        $number = $number["COUNT(*)"];

        return $number;
    }
    function assignToUser($user, $message)
    {
        global $conn;
        $message = (int)$message;
        $user = (int)$user;

        $updStmt = $conn->prepare("INSERT INTO messages_assigned (user,message) VALUES (?,?)");
        $upd = $updStmt->execute(array($user, $message));

        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Attach a file to a message
     *
     * @param int $fileId ID of the file to be attached
     * @param int $messageId ID of the message where the file will be attached
     * @param int $project optional param denoting the project ID where the file will be uploaded to (if so)
     * @return bool
     */
    function attachFile($fileId, $messageId, $project = 0)
    {
        global $conn;
        $fileId = (int)$fileId;
        $messageId = (int)$messageId;

        // If a file ID is given, the given file will be attached
        // If no file ID is given, the file will be uploaded to the project defined by $id and then attached
        if ($fileId > 0) {
            $insStmt = $conn->prepare("INSERT INTO files_attached (file,message) VALUES (?,?)");
            $ins = $insStmt->execute(array($fileId, $messageId));
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
        $msg = (int)$msg;

        $files = array();
        $sel = $conn->prepare("SELECT file FROM files_attached WHERE message = ?");
        $sel->execute(array($msg));

        while ($file = $sel->fetch()) {
            $sel2 = $conn->query("SELECT * FROM files WHERE ID = $file[0]");
            $thisfile = $sel2->fetch();
            $thisfile["type"] = str_replace("/", "-", $thisfile["type"]);

            //get systemSettings
            $settingsObj = new settings();
            $settings = $settingsObj->getSettings();
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

            $thisfile["shortName"] = substr($thisfile["name"], 0, 12);

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