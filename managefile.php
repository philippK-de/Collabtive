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
$fileObj = new datei();

$cleanGet = cleanArray($_GET);
$cleanPost = cleanArray($_POST);
//read the maximum file size for file uploads from PHP
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
if ($action == "uploadAsync") {
    if (!$userpermissions["files"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "<h2>$errtxt</h2><br>$noperm");
        $template->display("error.tpl");
        die();
    }
	//if a folder for upload is set
	//otherwhise use the root folder
    if ($upfolder) {
        $thefolder = $fileObj->getFolder($upfolder);
    	$absfolder = $fileObj->getAbsolutePathName($thefolder);
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
	//how many files to upload
    $num = count($_FILES);
    $chk = 0;
    //Loop through uploaded files
	foreach($_FILES as $file) {
		//encrypt files in their tmp location
    	$fileObj->encryptFile($file["tmp_name"], $settings["filePass"]);
		//upload them to the files folder and add to the database
        $fid = $fileObj->uploadAsync($file["name"], $file["tmp_name"], $file["type"], $file["size"], $upath, $id, $upfolder);
        //get the new file object
		$fileprops = $fileObj->getFile($fid);

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
        	//loop through the users in the project
            foreach($users as $user) {
                if (!empty($user["email"])) {
                    $userlang=readLangfile($user['locale']);

                    // check if subfolder exists, else root folder
                    $whichfolder = (!empty($thefolder)) ? $thefolder : $userlang["rootdir"];
					$fileUrl= $url . "managefile.php?action=downloadfile&id=".$fileprops["project"]."&file=" .  $fileprops["ID"] ;
                    // assemble content only once. no need to do this repeatedly
                    $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                                   $userlang["filecreatedtext"] . "<br /><br />" .
                                   $userlang["project"] . ": " . $pname["name"] . "<br />" .
                                   $userlang["folder"] . ": " . $whichfolder . "<br />" .
                                   $userlang["file"] . ":  <a href = \"" . $fileUrl . "\">" . $fileUrl . "</a>";
                	//$userlang["file"] . ":  <a href = \"" . $url . $fileprops["datei"] . "\">" . $url . $fileprops["datei"] . "</a>";

                    $subject = $userlang["filecreatedsubject"] . " (". $userlang['by'] . ' '. $username . ")";
					//if sendto is an array multiple users need to be notified
                    if (is_array($sendto)) {
                    	//check if the current user is in the notifications array
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
    $file = $fileObj->getFile($thisfile);
    $title = $langfile["editfile"];

    $projectObj = new project();
    $pro = $projectObj->getProject($id);
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
    if ($fileObj->edit($thisfile, $title, $desc, "")) {
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
    if ($fileObj->loeschen($thisfile)) {
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
    $thefolder = $fileObj->getFolder($thisfile);

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

    $projectObj = new project();
    $rolesobj = new roles();
    //get folders
    $folders = $fileObj->getAllProjectFolders($id);

	//get the project
    $pro = $projectObj->getProject($id);
	//get the project members
    $members = $projectObj->getProjectMembers($id, 10000);
   	//get all roles
    $allroles = $rolesobj->getAllRoles(10000);

    //default to gridview
    if(!isset($cleanGet["viewmode"]) || !$cleanGet["viewmode"])
    {
        $cleanGet["viewmode"] = "grid";
    }

    //html page title
    $template->assign("title", $langfile['files']);
    $template->assign("projectname", $pro["name"]);

    $template->assign("viewmode", $cleanGet["viewmode"]);

    $template->assign("folders", $folders);
    $template->assign("members", $members);

    $template->assign("roles", $allroles);

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

    if ($fileObj->addFolder($parent, $id, $name, $desc)) {
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
    
    if ($fileObj->deleteFolder($folder, $id)) {
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
   // $file = substr($file, 4, strlen($file)-4);

    $target = $_GET["target"];
    echo "$target $file";
   echo  $fileObj->moveFile($file, $target);
}
elseif($action == "projectFiles")
{
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $offset = 0;
    if(isset($cleanGet["offset"]))
    {
        $offset = $cleanGet["offset"];
    }
    $limit = 24;
    if(isset($cleanGet["limit"]))
    {
        $limit = $cleanGet["limit"];
    }

    $POST_MAX_SIZE = ini_get('post_max_size');
    $POST_MAX_SIZE = $POST_MAX_SIZE . "B";

    $folder = getArrayVal($_GET, "folder");

    $files = $fileObj->getJsonProjectFiles($id, $limit, $folder, $offset);
    $projectFiles = array("files"=>array());
    $filenum = $fileObj->countJsonProjectFiles($id, $folder);

    if (!empty($files)) {
        foreach($files as $file) {
            array_push($projectFiles["files"], $file);
        }
    }

    //if no folder is selected, get all the project folders
    if ($folder == 0) {
        $projectFolders = $fileObj->getProjectFolders($id);
        $currentFolder = array("parent" => 0, "name" => $langfile["rootdir"], "abspath" => $langfile["rootdir"]);
    } else {
        $projectFolders = $fileObj->getProjectFolders($id, $folder);
        $currentFolder = $fileObj->getFolder($folder);
    }

    $projectFiles["folders"] = $projectFolders;
    $projectFiles["currentFolder"] = $currentFolder;

    $jsonFiles["items"] = $projectFiles;
    $jsonFiles["count"] = $filenum;

   echo json_encode($jsonFiles);

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
	$thefile = $fileObj->getFile($fileId);

	//getFile path and filesize
	$filePath = $thefile["datei"];

	//Send HTTP headers for dowonload
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
	header('Content-Transfer-Encoding: binary');
	header('Connection: Keep-Alive');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	//Try to decrypt the file
	$plaintext = $fileObj->decryptFile($filePath, $settings["filePass"]);

	//no plaintext means file was not encrypted or not decrypted. however deliver to unmodified file
	if(!$plaintext)
	{
		$plaintext = file_get_contents($filePath);
	}
	
	//Render the content
	echo $plaintext;
}


?>
