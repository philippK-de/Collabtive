<?php
include("./init.php");
// check if user is logged in
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
$path = "./include/phpseclib";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
$myfile = new datei();

$POST_MAX_SIZE = ini_get('post_max_size');
$POST_MAX_SIZE = $POST_MAX_SIZE . "B";

$id = getArrayVal($_GET, "id");
$thisfile = getArrayVal($_GET, "file");
$mode = getArrayVal($_GET, "mode");
$action = getArrayVal($_GET, "action");

$name = getArrayVal($_POST, "name");
$desc = getArrayVal($_POST, "desc");
$tags = getArrayVal($_POST, "tags");
$title = getArrayVal($_POST, "title");
$upfolder = getArrayVal($_POST, "upfolder");

$project = array('ID' => $id);
$template->assign("project", $project);

$template->assign("mode", $mode);

$classes = array("overview" => "overview",
    "msgs" => "msgs",
    "tasks" => "tasks",
    "miles" => "miles",
    "files" => "files_active",
    "users" => "users",
    "tracker" => "tracking"
    );
$template->assign("classes", $classes);
if (!chkproject($userid, $id)) {
    $errtxt = $langfile["notyourproject"];
    $noperm = $langfile["accessdenied"];
    $template->assign("errortext", "$errtxt<br>$noperm");
    $template->display("error.tpl");
    die();
}
if ($action == "upload") {
    if (!$userpermissions["files"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $num = $_POST['numfiles'];

    if ($upfolder) {
    	$thefolder = $myfile->getFolder($upfolder);
    	$absfolder = $myfile->getAbsolutePathName($thefolder);
    	if($absfolder == "/")
    	{
    		$absfolder = "";
    	}
    	$thefolder = $thefolder["name"];
    	$upath = "files/" . CL_CONFIG . "/$id" . $absfolder;
    } else {
        $upath = "files/" . CL_CONFIG . "/$id";
        $upfolder = 0;
    }
    $chk = 0;
    for($i = 1;$i <= $num;$i++) {

		$fid = $myfile->upload("userfile$i", $upath, $id, $upfolder);
        $fileprops = $myfile->getFile($fid);

        if ($settings["mailnotify"]) {
            $sendto = getArrayVal($_POST, "sendto");
            $usr = (object) new project();
            $pname = $usr->getProject($id);
            $users = $usr->getProjectMembers($id, 10000);
            if ($sendto[0] == "all") {
                $sendto = $users;
                $sendto = reduceArray($sendto);
            } elseif ($sendto[0] == "none") {
                $sendto = array();
            }
            foreach($users as $user) {
                if (!empty($user["email"])) {
                    $userlang=readLangfile($user['locale']);

                    // check if subfolder exists, else root folder
                    $whichfolder = (!empty($thefolder)) ? $thefolder : $userlang["rootdir"];

                    // assemble content only once. no need to do this repeatedly
                    $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                                   $userlang["filecreatedtext"] . "<br /><br />" .
                                   $userlang["project"] . ": " . $pname["name"] . "<br />" .
                                   $userlang["folder"] . ": " . $whichfolder . "<br />" .
                                   $userlang["file"] . ":  <a href = \"" . $url . $fileprops["datei"] . "\">" . $url . $fileprops["datei"] . "</a>";

                    $subject = $userlang["filecreatedsubject"] . " (". $userlang['by'] . ' '. $username . ")";

                    if (is_array($sendto)) {
                        if (in_array($user["ID"], $sendto)) {
                            // send email
                            $themail = new emailer($settings);
                            $themail->send_mail($user["email"], $subject, $mailcontent);
                        }
                    } else {
                        // send email
                        $themail = new emailer($settings);
                        $themail->send_mail($user["email"], $subject, $mailcontent); // why was there no content before?
                    }
                }
            }
        }
    }
    $loc = $url .= "managefile.php?action=showproject&id=$id&mode=added";
  	//header("Location: $loc");
} elseif ($action == "uploadAsync") {
    if (!$userpermissions["files"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if ($upfolder) {
        $thefolder = $myfile->getFolder($upfolder);
    	$absfolder = $myfile->getAbsolutePathName($thefolder);
    	if($absfolder == "/")
    	{
    		$absfolder = "";
    	}
        $thefolder = $thefolder["name"];
        $upath = "files/" . CL_CONFIG . "/$id" . $absfolder;
    } else {
        $upath = "files/" . CL_CONFIG . "/$id";
        $upfolder = 0;
    }
    $num = count($_FILES);
    $chk = 0;
    //Loop through uploaded files
	foreach($_FILES as $file) {
		//encrypt files in their tmp location
    	$myfile->encryptFile($file["tmp_name"], $settings["filePass"]);
		//upload them to the files folder and add to the database
        $fid = $myfile->uploadAsync($file["name"], $file["tmp_name"], $file["type"], $file["size"], $upath, $id, $upfolder);
        //get the new file object
		$fileprops = $myfile->getFile($fid);

		//send mail notifications
        if ($settings["mailnotify"]) {
            $sendto = getArrayVal($_POST, "sendto");
            $usr = (object) new project();
            $pname = $usr->getProject($id);
            $users = $usr->getProjectMembers($id, 10000);
            if ($sendto[0] == "all") {
                $sendto = $users;
                $sendto = reduceArray($sendto);
            } elseif ($sendto[0] == "none") {
                $sendto = array();
            }
            foreach($users as $user) {
                if (!empty($user["email"])) {
                    $userlang=readLangfile($user['locale']);

                    // check if subfolder exists, else root folder
                    $whichfolder = (!empty($thefolder)) ? $thefolder : $userlang["rootdir"];

                    // assemble content only once. no need to do this repeatedly
                    $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                                   $userlang["filecreatedtext"] . "<br /><br />" .
                                   $userlang["project"] . ": " . $pname["name"] . "<br />" .
                                   $userlang["folder"] . ": " . $whichfolder . "<br />" .
                                   $userlang["file"] . ":  <a href = \"" . $url . $fileprops["datei"] . "\">" . $url . $fileprops["datei"] . "</a>";

                    $subject = $userlang["filecreatedsubject"] . " (". $userlang['by'] . ' '. $username . ")";

                    if (is_array($sendto)) {
                        if (in_array($user["ID"], $sendto)) {
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
    }
    $loc = $url .= "managefile.php?action=showproject&id=$id&mode=added";
    // header("Location: $loc");
    echo "UPLOADED";
} elseif ($action == "editform") {
    if (!$userpermissions["files"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $file = $myfile->getFile($thisfile);
    $title = $langfile["editfile"];

    $myproject = new project();
    $pro = $myproject->getProject($id);
    $projectname = $pro["name"];

    $template->assign("title", $title);
    $template->assign("file", $file);
    $template->assign("projectname", $projectname);

    $template->display("editfileform.tpl");
} elseif ($action == "edit") {
    if (!$userpermissions["files"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $tagobj = new tags();
    $tags = $tagobj->formatInputTags($tags);
    if ($myfile->edit($thisfile, $title, $desc, $tags)) {
        $loc = $url .= "managefile.php?action=showproject&id=$id&mode=edited";
        header("Location: $loc");
    }
} elseif ($action == "delete") {
    if (!$userpermissions["files"]["del"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    if ($myfile->loeschen($thisfile)) {
        echo "ok";
    }
} elseif ($action == "zipexport") {
    $topfad = CL_ROOT . "/files/" . CL_CONFIG . "/$id" . "/projectfiles" . $id . ".zip";
    $zip = new PclZip($topfad);

    if (file_exists($topfad)) {
        if (unlink($topfad)) {
            $create = $zip->create(CL_ROOT . "/files/" . CL_CONFIG . "/$id/", PCLZIP_OPT_REMOVE_ALL_PATH);
        }
    } else {
        $create = $zip->create(CL_ROOT . "/files/" . CL_CONFIG . "/$id/", PCLZIP_OPT_REMOVE_ALL_PATH);
    }
    if ($create != 0) {
        $loc = $url . "files/" . CL_CONFIG . "/$id" . "/projectfiles" . $id . ".zip";
        header("Location: $loc");
    }
} elseif ($action == "folderexport") {
    $thefolder = $myfile->getFolder($thisfile);

    $topfad = CL_ROOT . "/files/" . CL_CONFIG . "/$id" . "/folder" . $thefolder["ID"] . ".zip";
    $zip = new PclZip($topfad);

    if (file_exists($topfad)) {
        if (unlink($topfad)) {
            $create = $zip->create(CL_ROOT . "/files/" . CL_CONFIG . "/$id/$thefolder[name]/", PCLZIP_OPT_REMOVE_ALL_PATH);
        }
    } else {
        $create = $zip->create(CL_ROOT . "/files/" . CL_CONFIG . "/$id/$thefolder[name]/", PCLZIP_OPT_REMOVE_ALL_PATH);
    }
    if ($create != 0) {
        $loc = $url . "/files/" . CL_CONFIG . "/$id" . "/folder" . $thefolder["ID"] . ".zip";
        header("Location: $loc");
    }
} elseif ($action == "showproject") {
    if (!$userpermissions["files"]["view"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $files = $myfile->getProjectFiles($id);

    $filenum = count($files);
    if (empty($finfiles)) {
        $filenum = 0;
    }
    $folders = $myfile->getProjectFolders($id);

    $allfolders = $myfile->getAllProjectFolders($id);
    $myproject = new project();
    $pro = $myproject->getProject($id);
    $members = $myproject->getProjectMembers($id, 10000);
    $rolesobj = new roles();
    $allroles = $rolesobj->getAllRoles();

    $projectname = $pro["name"];
    $title = $langfile['files'];

    $template->assign("title", $title);
    $template->assign("projectname", $projectname);
    SmartyPaginate::assign($template);
    $template->assign("files", $files);
    $template->assign("filenum", $filenum);
    $template->assign("folders", $folders);
    $template->assign("members", $members);
    $template->assign("roles", $allroles);

    $template->assign("allfolders", $allfolders);
    $template->assign("postmax", $POST_MAX_SIZE);
    $template->display("projectfiles.tpl");
} elseif ($action == "addfolder") {
    if (!$userpermissions["files"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $name = getArrayVal($_POST, "foldertitle");
    $desc = getArrayVal($_POST, "folderdesc");
    $parent = getArrayVal($_POST, "folderparent");

    if ($myfile->addFolder($parent, $id, $name, $desc)) {
        $loc = $url .= "managefile.php?action=showproject&id=$id&mode=folderadded";
        header("Location: $loc");
    }
} elseif ($action == "delfolder") {
    if (!$userpermissions["files"]["del"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $ajaxreq = $_GET["ajax"];
    $folder = getArrayVal($_GET, "folder");
    if ($myfile->deleteFolder($folder, $id)) {
        if ($ajaxreq = 1) {
            echo "ok";
        } else {
            $loc = $url .= "managefile.php?action=showproject&id=$id&mode=folderdel";
            header("Location: $loc");
        }
    }
} elseif ($action == "movefile") {

    if (!$userpermissions["files"]["edit"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $file = $_GET["file"];
    $file = substr($file, 4, strlen($file)-4);

    $target = $_GET["target"];
    $myfile->moveFile($file, $target);
}
elseif($action == "downloadfile")
{
	if (!$userpermissions["files"]["view"]) {
		$errtxt = $langfile["nopermission"];
		$noperm = $langfile["accessdenied"];
		$template->assign("errortext", "$errtxt<br>$noperm");
		$template->display("error.tpl");
		die();
	}

	//get the file ID.
	$fileId = getArrayVal($_GET,"file");
	$thefile = $myfile->getFile($fileId);

	//getFile path and filesize
	$filePath = $thefile["datei"];
	$fsize =  filesize($filePath);

	//Send HTTP headers for dowonload
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
	header('Content-Transfer-Encoding: binary');
	header('Connection: Keep-Alive');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header("Content-length: $fsize");
	//Try to decrypt the file
	$plaintext = $myfile->decryptFile($filePath, $settings["filePass"]);

	//no plaintext means file was not encrypted or not decrypted. however deliver to unmodified file
	if(!$plaintext)
	{
		$plaintext = file_get_contents($filePath);
	}
	//Render the content
	echo $plaintext;

}

?>
