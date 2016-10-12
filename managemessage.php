<?php
require("./init.php");
// check if user is logged in
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
// create message object
$messageObj = new message();
$objmilestone = (object) new milestone();
// get data from POST and GET
// getArrayVal will return the value from the array if present, or false if not. This way the variable is never undeclared.
$action = getArrayVal($_GET, "action");
$id = getArrayVal($_GET, "id");
$redir = getArrayVal($_GET, "redir");

//Get data from $_POST and $_GET filtered and sanitized by htmlpurifier
$cleanGet = cleanArray($_GET);
$cleanPost = cleanArray($_POST);

$message = getArrayVal($_POST, "text");

$project = array('ID' => $id);
$template->assign("project", $project);
if(isset($cleanGet["mode"])) {
    $template->assign("mode", $cleanGet["mode"]);
}
// define the active tab in the project navigation
$classes = array("overview" => "overview", "msgs" => "msgs_active", "tasks" => "tasks", "miles" => "miles", "files" => "files", "users" => "users", "tracker" => "tracking");
$template->assign("classes", $classes);

if ($action != "mymsgs" and $action != "mymsgs-pdf") {
    // check if the user belongs to the current project. die if he/she doesn't
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
}

if ($action == "addform") {
    // display addform
    $template->display("addmessageform.tpl");
} elseif ($action == "add") {
    // check if the user is allowed to add messages
    if (!$userpermissions["messages"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $milestone = 0;
    if(isset($cleanPost["milestone"]))
    {
        $milestone = $cleanPost["milestone"];
    }
    $title = "";
    if(isset($cleanPost["title"]))
    {
        $title = $cleanPost["title"];
    }

    // add message
    $messageID = $messageObj->add($id, $title, $message, $userid, $username, 0, $milestone);

    if ($messageID) {
        //create a private message
        if(isset($cleanPost["privateRecipient"]) && $cleanPost["privateRecipient"] > 0){
            $messageObj->assignToUser($cleanPost["privateRecipient"], $messageID);
        }

        if(isset($cleanPost["thefiles"])) {
            if ($cleanPost["thefiles"] > 0) {
                // attach existing file
                $messageObj->attachFile($cleanPost["thefiles"], $messageID);
            }
        }


        if ($settings["mailnotify"]) {
            $sendto = getArrayVal($_POST, "sendto");
            $usr = (object) new project();
            $users = $usr->getProjectMembers($id, 10000);
            if ($sendto[0] == "all") {
                $sendto = $users;
                $sendto = reduceArray($sendto);
            } elseif ($sendto[0] == "none") {
                $sendto = array();
            }
            foreach($users as $user) {
                if (!empty($user["email"])) {
                    $userlang = readLangfile($user['locale']);

                    $subject = $userlang["messagewasaddedsubject"] . ' ("' . $cleanPost["title"] . '" - ' . $userlang['by'] . ' ' . $username . ')'; // added message title and author  to subject

                    $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                        $userlang["messagewasaddedtext"] . "<br /><br />" .
                        "<h3><a href = \"" . $url . "managemessage.php?action=showmessage&id=$id&mid=$messageID\">" . $cleanPost["title"] . "</a></h3>".
                         // no need for line break after heading
                        $message;

                    if (is_array($sendto)) {
                        if (in_array($user["ID"], $sendto) && $userid != $user["ID"]) {
                            // send email
                            $themail = new emailer($settings);
                            $themail->send_mail($user["email"], $subject, $mailcontent);
                        }
                    } else {
                        // send email
                        $themail = new emailer($settings);
                        $themail->send_mail($user["email"], $subject, $mailcontent);
                    }
                }
            }
        }
        $loc = $url . "managemessage.php?action=showproject&id=$id&mode=added";
        //header("Location: $loc");
        echo "ok";
    }
} elseif ($action == "editform") {
    // check if the user is allowed to edit messages
    if (!$userpermissions["messages"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // get page title from language file
    $cleanPost["title"] = $langfile["editmessage"];
    $template->assign("title", $cleanPost["title"]);

    //disable full html, for async display
    $template->assign("showhtml","no");
    $template->assign("async","yes");
    // get the message to edit
    $message = $messageObj->getMessage($cleanGet["mid"]);
    $template->assign("message", $message);
    $template->display("editmessageform.tpl");
} elseif ($action == "edit") {
    // check if the user is allowed to edit messages
    if (!$userpermissions["messages"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    // edit the msg
    if ($messageObj->edit($cleanPost["mid"], $cleanPost["title"], $cleanPost["text"])) {
        if ($redir) {
            $redir = $url . $redir;
            header("Location: $redir");
        } else {
            $loc = $url . "managemessage.php?action=showproject&id=$id&mode=edited";
            header("Location: $loc");
        }
    }
} elseif ($action == "del") {
    // check if the user is allowed to delete messages
    if (!$userpermissions["messages"]["del"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // delete the message
    if ($messageObj->del($cleanGet["mid"])) {
        // if a redir target is given, redirect to it. else redirect to standard target.
        if ($redir) {
            echo "ok";
            $redir = $url . $redir;
            header("Location: $redir");
        } else {
            echo "ok";
        }
    }
} elseif ($action == "replyform") {
    // check if the user is allowed to add messages
    if (!$userpermissions["messages"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // get page title from language file
    $cleanPost["title"] = $langfile["reply"];
    $template->assign("title", $cleanPost["title"]);
    // get all notifiable members
    $myproject = new project();
    $pro = $myproject->getProject($id);
    $members = $myproject->getProjectMembers($id, 10000);
    // Get attachable files
    $myfile = new datei();
    $ordner = $myfile->getProjectFiles($id, 1000);
    $message = $messageObj->getMessage($cleanGet["mid"]);

    $template->assign("showhtml","no");
    $template->assign("async","yes");
    $template->assign("message", $message);
    $template->assign("members", $members);
    $template->assign("files", $ordner);
    $template->display("replyform.tpl");
} elseif ($action == "reply") {
    // check if the user is allowed to edit messages
    if (!$userpermissions["messages"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }


    $messageID = $messageObj->add($id, $cleanPost["title"], $message, $userid, $username, $cleanPost["mid"], $cleanPost["milestone"]);
    if ($messageID) {
        if ($cleanPost["thefiles"] > 0) {
            // attach existing file
            $messageObj->attachFile($cleanPost["thefiles"], $messageID);
        } elseif ($cleanPost["thefiles"] == 0 and $cleanPost["numfiles"] > 0) {
            // if upload files are set, upload and attach
            $messageObj->attachFile(0, $messageID, $id);
        }

        if ($settings["mailnotify"]) {
            $sendto = getArrayVal($_POST, "sendto");
            $usr = (object) new project();
            $users = $usr->getProjectMembers($id, 10000);
            if ($sendto[0] == "all") {
                $sendto = $users;
                $sendto = reduceArray($sendto);
            } elseif ($sendto[0] == "none") {
                $sendto = array();
            }
            foreach($users as $user) {
                if (!empty($user["email"])) {
                    $userlang = readLangfile($user['locale']);
                    $subject = $userlang["messagewasaddedsubject"] . ' ("' . $cleanPost["title"] . '" - ' . $userlang['by'] . ' ' . $username . ')'; // added message title and author  to subject
                    $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                        $userlang["messagewasaddedtext"] . "<br /><br />" .
                        "<h3><a href = \"" . $url . "managemessage.php?action=showmessage&id=$id&mid=$messageID\">" . $cleanPost["title"]. "</a></h3>".
                         // no need for line break after heading
                        $message;

                    if (is_array($sendto)) {
                        if (in_array($user["ID"], $sendto) && $userid != $user["ID"]) {
                            // send email
                            $themail = new emailer($settings);
                            $themail->send_mail($user["email"], $subject, $mailcontent);
                        }
                    } else {
                        // send email
                        $themail = new emailer($settings);
                        $themail->send_mail($user["email"], $subject, $mailcontent);
                    }
                }
            }
        }

        $loc = $url . "managemessage.php?action=showmessage&mid=" . $cleanPost["mid"] . "&id=$id&mode=replied";
        header("Location: $loc");
    }
} elseif ($action == "showproject") {
    if (!$userpermissions["messages"]["view"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    // get project's name
    $myproject = new project();
    $pro = $myproject->getProject($id);
    $members = $myproject->getProjectMembers($id, 10000);
    $projectname = $pro['name'];
    $template->assign("projectname", $projectname);
    // get the page title
    $cleanPost["title"] = $langfile['messages'];
    $template->assign("title", $cleanPost["title"]);


    // get files of the project
    $datei = new datei();
    $cleanPost["thefiles"] = $datei->getAllProjectFiles($id);

    $milestones = $objmilestone->getAllProjectMilestones($id, 10000);

    $template->assign("milestones", $milestones);
    $template->assign("projectname", $projectname);
    $template->assign("files", $cleanPost["thefiles"]);
    $template->assign("members", $members);
    $template->display("projectmessages.tpl");
}
elseif($action == "projectMessages")
{
    if (!$userpermissions["messages"]["view"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // get all messages of this project

    $offset = 0;
    if(isset($cleanGet["offset"]))
    {
        $offset = $cleanGet["offset"];
    }
    $limit = 20;
    if(isset($cleanGet["limit"]))
    {
        $limit = $cleanGet["limit"];
    }

    $userMessages = $messageObj->getUserMessages($userid, $limit, $offset);
    $projectMessages = $messageObj->getProjectMessages($id, $limit, $offset);

    $messages["public"] = $projectMessages;
    $messages["private"] = $userMessages;

    $jsonMessages["items"] = $messages;
    $jsonMessages["count"] = $number = $messageObj->countProjectMessages($id);

    echo json_encode($jsonMessages);
}
elseif($action == "userMessages")
{
    if (!$userpermissions["messages"]["view"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // get all messages of this project

    $offset = 0;
    if(isset($cleanGet["offset"]))
    {
        $offset = $cleanGet["offset"];
    }
    $limit = 20;
    if(isset($cleanGet["limit"]))
    {
        $limit = $cleanGet["limit"];
    }

   $userMessages = $messageObj->getUserMessages($userid, $limit, $offset);


    $jsonMessages["items"] = $userMessages;
    $jsonMessages["count"] = $number = $messageObj->countUserMessages($userid);

    echo json_encode($jsonMessages);
}
elseif ($action == "showmessage") {
    if (!$userpermissions["messages"]["view"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    // get the message and its replies
    $message = $messageObj->getMessage($cleanGet["mid"]);
     //if this message is a reply to another message, get its parent message
     if($message["replyto"] > 0) {
         $message["parentMessage"] = $messageObj->getMessage($message["replyto"], false);
     }

    $myproject = new project();
    $pro = $myproject->getProject($id);
    // get all notifiable members
    $members = $myproject->getProjectMembers($id, 10000);
    // get all attachable files
    $myfile = new datei();
    $ordner = $myfile->getProjectFiles($id, 1000);

    $projectname = $pro['name'];
    $cleanPost["title"] = $langfile['message'];

    $template->assign("projectname", $projectname);
    $template->assign("title", $cleanPost["title"]);
    $template->assign("message", $message);
    $template->assign("replies", $message["listReplies"]);
    $template->assign("ordner", $ordner);
    $template->assign("files", $ordner);
    $template->assign("members", $members);
    $template->display("message.tpl");
}
elseif ($action == "message") {
    if (!$userpermissions["messages"]["view"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    // get the message and its replies
    $message = $messageObj->getMessage($cleanGet["mid"]);
    $myMessage["count"] = 1;
    $myMessage["items"] = $message;

    echo json_encode($myMessage, JSON_PRETTY_PRINT);
}
elseif ($action == "export-project") {
    $l = Array();
    $l['a_meta_charset'] = 'UTF-8';
    $l['a_meta_dir'] = 'ltr';
    $l['a_meta_language'] = 'en';
    // TRANSLATIONS --------------------------------------
    $l['w_page'] = 'page';

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_DATA, '', 20));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 8));
    $pdf->SetHeaderMargin(0);
    $pdf->SetFont(PDF_FONT_NAME_DATA, "", 11);

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setLanguageArray($l);

    $pdf->getAliasNbPages();
    $pdf->AddPage();
    // check if the user is allowed to edit messages
    if (!$userpermissions["messages"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // get all messages of this project
    $messages = $messageObj->getProjectMessages($id);
    // get project's name
    $myproject = new project();
    $pro = $myproject->getProject($id);
    $projectname = $pro['name'];
    $template->assign("projectname", $projectname);
    // get the page title
    $cleanPost["title"] = $langfile['messages'];

    if (!empty($messages)) {
        $mcount = count($messages);
    } else {
        $mcount = 0;
    }

    $htmltable = "<h1>$projectname / $langfile[messages]</h1><table border=\"1\" bordercolor = \"#d9dee8\" >";

    if (!empty($messages)) {
        foreach($messages as $message) {
            $htmltable .= "
		<tr bgcolor=\"#d9dee8\" style=\"font-weight:bold;\">
		<th align=\"left\">$langfile[message]: $message[title] $langfile[by]: $message[username] ($message[postdate])</th>
		</tr>
		<tr><td >$message[text]</td></tr>
		";
        }
    } else {
        $htmltable .= "
		<tr><td >0 $langfile[messages]</td></tr>
		";
    }

    $pdf->writeHTML($htmltable, true, 0, true, 0);
    $pdf->Output("project-$id-messages.pdf", "D");
} elseif ($action == "export-single") {
    $l = Array();
    $l['a_meta_charset'] = 'UTF-8';
    $l['a_meta_dir'] = 'ltr';
    $l['a_meta_language'] = 'en';
    // TRANSLATIONS --------------------------------------
    $l['w_page'] = 'page';

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_DATA, '', 20));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 8));
    $pdf->SetHeaderMargin(0);
    $pdf->SetFont(PDF_FONT_NAME_DATA, "", 11);

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setLanguageArray($l);

    $pdf->AliasNbPages();
    $pdf->AddPage();
    // check if the user is allowed to edit messages
    if (!$userpermissions["messages"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // get all messages of this project
    $message = $messageObj->getMessage($cleanGet["mid"]);
    // get project's name
    $myproject = new project();
    $pro = $myproject->getProject($id);
    $projectname = $pro['name'];
    $template->assign("projectname", $projectname);
    // get the page title
    $cleanPost["title"] = $langfile['messages'];

    $htmltable = "<h1>$message[title]</h1><table border=\"1\" bordercolor = \"#d9dee8\" >
	<tr bgcolor=\"#d9dee8\" style=\"font-weight:bold;\">
	<th align=\"left\">$langfile[project]: $projectname - $langfile[by]: $message[username] ($message[endstring])</th>
	</tr>
    <tr><td >$message[text]</td></tr></table>";
    $pdf->writeHTML($htmltable, true, 0, true, 0);
    $pdf->Output("message$mid.pdf", "D");
}
elseif ($action == "mymsgs")
{
    // create new project and file objects
    $project = new project();
    $myfile = new datei();
    // get all uof the users projects
    $myprojects = $project->getMyProjects($userid, 1, 0, 10000);
    $cou = 0;
    $messages = array();
    // loop through the projects and get messages and files for each project
    if (!empty($myprojects))
    {
        foreach($myprojects as $proj)
        {
            $message = $messageObj->getProjectMessages($proj["ID"]);
            $ordner = $myfile->getProjectFiles($proj["ID"], 1000);
            $milestones = $objmilestone->getProjectMilestones($proj["ID"], 10000);
            if(!empty($message))
            {
                array_push($messages,$message);
            }
            $myprojects[$cou]["milestones"] = $milestones;
            $myprojects[$cou]["messages"] = $message;
            $myprojects[$cou]["files"] = $ordner;
            $cou = $cou + 1;
        }
    }
    $emessages = reduceArray($messages);

    // print_r($myprojects);
    $cleanPost["title"] = $langfile['mymessages'];
    $template->assign("title", $cleanPost["title"]);
    $members = $project->getProjectMembers($id, 10000);
    $template->assign("members", $members);
    $template->assign("messages", $emessages);
    $template->assign("msgnum", count($emessages));
    $template->assign("myprojects", $myprojects);
    $template->display("mymessages.tpl");
}

