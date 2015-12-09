<?php
require("./init.php");
// check if user is logged in
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
$cleanGet = cleanArray($_GET);
$cleanPost = cleanArray($_POST);
// create message object
$msg = new message();
$objmilestone = (object) new milestone();
// get data from POST and GET
// getArrayVal will return the value from the array if present, or false if not. This way the variable is never undeclared.
$action = getArrayVal($_GET, "action");
$redir = getArrayVal($_GET, "redir");


$title = getArrayVal($_POST, "title");
$mid_post = getArrayVal($_POST, "mid");
$text = getArrayVal($_POST, "text");
$milestone = getArrayVal($_POST, "milestone");

$project = array('ID' => $cleanGet["id"]);
$template->assign("project", $project);
$template->assign("mode", $cleanGet["mode"]);
// define the active tab in the project navigation
$classes = array("overview" => "overview", "msgs" => "msgs_active", "tasks" => "tasks", "miles" => "miles", "files" => "files", "users" => "users", "tracker" => "tracking");
$template->assign("classes", $classes);

if ($action != "mymsgs" and $action != "mymsgs-pdf") {
    // check if the user belongs to the current project. die if he/she doesn't
    if (!chkproject($userid, $cleanGet["id"])) {
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
    // add message
    $themsg = $msg->add($cleanGet["id"], $title, $cleanPost["text"], $userid, $username, 0, $milestone);

    if ($themsg) {
        if ($cleanPost["thefiles"] > 0) {
            // attach existing file
            $msg->attachFile($cleanPost["thefiles"], $themsg);
        } elseif ($cleanPost["thefiles"] == 0 and $cleanPost["numfiles"] > 0) {
            // if upload files are set, upload and attach
            $msg->attachFile(0, $themsg, $cleanGet["id"]);
        }

        if ($settings["mailnotify"]) {
            $sendto = getArrayVal($_POST, "sendto");
            $usr = (object) new project();
            $users = $usr->getProjectMembers($cleanGet["id"], 10000);
            if ($sendto[0] == "all") {
                $sendto = $users;
                $sendto = reduceArray($sendto);
            } elseif ($sendto[0] == "none") {
                $sendto = array();
            }
            foreach($users as $user) {
                if (!empty($user["email"])) {
                    $userlang = readLangfile($user['locale']);

                    $subject = $userlang["messagewasaddedsubject"] . ' ("' . $title . '" - ' . $userlang['by'] . ' ' . $username . ')'; // added message title and author  to subject

                    $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                                   $userlang["messagewasaddedtext"] . "<br /><br />" .
                                   "<h3><a href = \"" . $url . "managemessage.php?action=showmessage&id=" . $cleanGet["id"]. "&mid=$themsg\">$title</a></h3>". // no need for line break after heading
                                   $cleanPost["text"];

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
        $loc = $url . "managemessage.php?action=showproject&id=" . $cleanGet["id"]. "&mode=added";
        header("Location: $loc");
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
    $title = $langfile["editmessage"];
    $template->assign("title", $title);

	//disable full html, for async display
	$template->assign("showhtml","no");
	$template->assign("async","yes");
    // get the message to edit
    $cleanPost["text"] = $msg->getMessage($cleanGet["mid"]);
    $template->assign("message", $cleanPost["text"]);
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
    if ($msg->edit($mid_post, $title, $text)) {
        if ($redir) {
            $redir = $url . $redir;
            header("Location: $redir");
        } else {
            $loc = $url . "managemessage.php?action=showproject&id=" . $cleanGet["id"]. "&mode=edited";
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
    if ($msg->del($cleanGet["mid"])) {
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
    $title = $langfile["reply"];
    $template->assign("title", $title);
    // get all notifiable members
    $myproject = new project();
    $pro = $myproject->getProject($cleanGet["id"]);
    $members = $myproject->getProjectMembers($cleanGet["id"], 10000);
    // Get attachable files
    $myfile = new datei();
    $ordner = $myfile->getProjectFiles($cleanGet["id"], 1000);
    $cleanPost["text"] = $msg->getMessage($cleanGet["mid"]);

	$template->assign("showhtml","no");
	$template->assign("async","yes");
    $template->assign("message", $cleanPost["text"]);
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


    $themsg = $msg->add($cleanGet["id"], $title, $cleanPost["text"], $userid, $username, $mid_post, $milestone);
    if ($themsg) {
        if ($cleanPost["thefiles"] > 0) {
            // attach existing file
            $msg->attachFile($cleanPost["thefiles"], $themsg);
        } elseif ($cleanPost["thefiles"] == 0 and $cleanPost["numfiles"] > 0) {
            // if upload files are set, upload and attach
            $msg->attachFile(0, $themsg, $cleanGet["id"]);
        }

        if ($settings["mailnotify"]) {
            $sendto = getArrayVal($_POST, "sendto");
            $usr = (object) new project();
            $users = $usr->getProjectMembers($cleanGet["id"], 10000);
            if ($sendto[0] == "all") {
                $sendto = $users;
                $sendto = reduceArray($sendto);
            } elseif ($sendto[0] == "none") {
                $sendto = array();
            }
            foreach($users as $user) {
                if (!empty($user["email"])) {
                    $userlang = readLangfile($user['locale']);

                    $subject = $userlang["messagewasaddedsubject"] . ' ("' . $title . '" - ' . $userlang['by'] . ' ' . $username . ')'; // added message title and author  to subject

                    $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                                   $userlang["messagewasaddedtext"] . "<br /><br />" .
                                   "<h3><a href = \"" . $url . "managemessage.php?action=showmessage&id=" . $cleanGet["id"]. "&mid=$themsg\">$title</a></h3>". // no need for line break after heading
                                   $cleanPost["text"];

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

        $loc = $url . "managemessage.php?action=showmessage&mid=$mid_post&id=" . $cleanGet["id"] . "&mode=replied";
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
    if (!chkproject($userid, $cleanGet["id"])) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    // get all messages of this project
    $messages = $msg->getProjectMessages($cleanGet["id"]);
    // get project's name
    $myproject = new project();
    $pro = $myproject->getProject($cleanGet["id"]);

    $members = $myproject->getProjectMembers($cleanGet["id"], 10000);
    $projectname = $pro['name'];
    $template->assign("projectname", $projectname);
    // get the page title
    $title = $langfile['messages'];
    $template->assign("title", $title);

    if (!empty($messages)) {
        $mcount = count($messages);
    } else {
        $mcount = 0;
    }
    // get files of the project
    $datei = new datei();
    $cleanPost["thefiles"] = $datei->getAllProjectFiles($cleanGet["id"]);

    $milestones = $objmilestone->getAllProjectMilestones($cleanGet["id"], 10000);

    $template->assign("milestones", $milestones);
    $template->assign("projectname", $projectname);
    $template->assign("files", $cleanPost["thefiles"]);
    $template->assign("messages", $messages);
    $template->assign("members", $members);
    $template->assign("messagenum", $mcount);
    $template->display("projectmessages.tpl");
} elseif ($action == "showmessage") {
    // get the message and its replies
    $cleanPost["text"] = $msg->getMessage($cleanGet["mid"]);
    $replies = $msg->getReplies($cleanGet["mid"]);

    $myproject = new project();
    $pro = $myproject->getProject($cleanGet["id"]);
    // get all notifiable members
    $members = $myproject->getProjectMembers($cleanGet["id"], 10000);
    // get all attachable files
    $myfile = new datei();
    $ordner = $myfile->getProjectFiles($cleanGet["id"], 1000);

    $projectname = $pro['name'];
    $title = $langfile['message'];

    $template->assign("projectname", $projectname);
    $template->assign("title", $title);
    $template->assign("mode", $cleanGet["mode"]);
    $template->assign("message", $cleanPost["text"]);
    $template->assign("ordner", $ordner);
    $template->assign("replies", $replies);
    $template->assign("files", $ordner);
    $template->assign("members", $members);
    $template->display("message.tpl");
} elseif ($action == "export-project") {
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
    $messages = $msg->getProjectMessages($cleanGet["id"]);
    // get project's name
    $myproject = new project();
    $pro = $myproject->getProject($cleanGet["id"]);
    $projectname = $pro['name'];
    $template->assign("projectname", $projectname);
    // get the page title
    $title = $langfile['messages'];

    if (!empty($messages)) {
        $mcount = count($messages);
    } else {
        $mcount = 0;
    }

    $htmltable = "<h1>$projectname / $langfile[messages]</h1><table border=\"1\" bordercolor = \"#d9dee8\" >";

    if (!empty($messages)) {
        foreach($messages as $cleanPost["text"]) {
            $htmltable .= "
		<tr bgcolor=\"#d9dee8\" style=\"font-weight:bold;\">
		<th align=\"left\">$langfile[message]: " . $cleanPost["text"][title] . $langfile[by] . ":" .  $cleanPost["text"][username] . "(" . $cleanPost["text"][postdate] . ")</th>
		</tr>
		<tr><td >" . $cleanPost["text"][text] . "</td></tr>
		";
        }
    } else {
        $htmltable .= "
		<tr><td >0 $langfile[messages]</td></tr>
		";
    }

    $pdf->writeHTML($htmltable, true, 0, true, 0);
    $pdf->Output("project-" . $cleanGet["id"]. "-messages.pdf", "D");
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
    $cleanPost["text"] = $msg->getMessage($cleanGet["mid"]);
    // get project's name
    $myproject = new project();
    $pro = $myproject->getProject($cleanGet["id"]);
    $projectname = $pro['name'];
    $template->assign("projectname", $projectname);
    // get the page title
    $title = $langfile['messages'];

    $htmltable = "<h1>" . $cleanPost["text"][title] . "</h1><table border=\"1\" bordercolor = \"#d9dee8\" >
	<tr bgcolor=\"#d9dee8\" style=\"font-weight:bold;\">
	<th align=\"left\">$langfile[project]: $projectname - $langfile[by]: ". $cleanPost["text"][username] ." (" . $cleanPost["text"][endstring] . ")</th>
	</tr>
    <tr><td >" . $cleanPost["text"][text]. "</td></tr></table>";
    $pdf->writeHTML($htmltable, true, 0, true, 0);
    $pdf->Output("message$mid.pdf", "D");
}
elseif ($action == "mymsgs-pdf") {
    $l = Array();
    $l['a_meta_charset'] = 'UTF-8';
    $l['a_meta_dir'] = 'ltr';
    $l['a_meta_language'] = 'en';

    $l['w_page'] = 'page';
    // set some PDF options
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
    $title = $langfile['mymessages'];
    $pdf->SetHeaderData("", 0, "" , $title);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_DATA, '', 20));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 8));
    $pdf->SetHeaderMargin(0);
    $pdf->SetFont(PDF_FONT_NAME_DATA, "", 11);

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setLanguageArray($l);


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
    $messages = $msg->getProjectMessages($cleanGet["id"]);
    // get project's name
    $myproject = new project();
    $pro = $myproject->getProject($cleanGet["id"]);
    $projectname = $pro['name'];
    $template->assign("projectname", $projectname);
    // get the page title
    // create new project and file objects
    $project = new project();
    $myfile = new datei();
    // get all uof the users projects
    $myprojects = $project->getMyProjects($userid);
    $cou = 0;
    $messages = array();
    // loop through the projects and get messages and files for each project
    if (!empty($myprojects)) {
        foreach($myprojects as $proj) {
            $cleanPost["text"] = $msg->getProjectMessages($proj["ID"]);
            array_push($messages, $cleanPost["text"]);
        }
    }
    // flatten array
    $messages = reduceArray($messages);

    if (!empty($messages)) {
        $mcount = count($messages);
    } else {
        $mcount = 0;
    }
    // construct html table for pdf export
    $htmltable = "<table border=\"1\" bordercolor = \"#d9dee8\" >";

    if (!empty($messages)) {
        foreach($messages as $cleanPost["text"]) {
            $htmltable .= "
      	<tr bgcolor=\"#d9dee8\" style=\"font-weight:bold;\">
      	<th align=\"left\">$langfile[message]: " . $cleanPost["text"][title] . $langfile[by] . ":".  $cleanPost["text"][username] ." (" . $cleanPost["text"][postdate] . ") </th>
      	</tr>
      	<tr><td >" . $cleanPost["text"][text] . "</td></tr>
      	";
        }
    } else {
        $htmltable .= "
	 	<tr><td >$langfile[none] $langfile[messages]</td></tr>
	 	";
    }

    $htmltable .= "</table>";
    // write it to PDF
    $pdf->writeHTML($htmltable, true, 0, true, 0);
    $pdf->Output("mymessages-" . $cleanGet["id"] . ".pdf", "D");
}

?>
