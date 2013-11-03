<?php
include("./init.php");
// check if user is logged in
if (!isset($_SESSION["userid"])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
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
    		$thefolder = $thefolder["name"];
    		$secure_name=$upfolder.'.'.$myfile->secure_name($thefolder);
        $upath = "files/" . CL_CONFIG . "/$id/$secure_name";
        if (!file_exists($upath)){ // those lines are for compatibility with former versions, which created named folders
          $upath = "files/" . CL_CONFIG . "/$id/" . $thefolder;
        }
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
                    if (is_array($sendto)) {
                        if (in_array($user["ID"], $sendto)) {
                            // check if subfolder exists, else root folder
                            $whichfolder = (!empty($thefolder)) ? $thefolder : $langfile["rootdir"];
                            // send email
                            $themail = new emailer($settings);
                            $themail->send_mail($user["email"], $langfile["filecreatedsubject"], $langfile["hello"] . ",<br /><br/>" . $langfile["filecreatedtext"] . "<br /><br />" . $langfile["project"] . ": " . $pname["name"] . "<br />" . $langfile["folder"] . ": " . $whichfolder . "<br />" . $langfile["file"] . ":  <a href = \"" . $url . $fileprops["datei"] . "\">" . $url . $fileprops["datei"] . "</a>");
                        }
                    } else {
                        // send email
                        $themail = new emailer($settings);
                        $themail->send_mail($user["email"], $langfile["filecreatedsubject"], "");
                    }
                }
            }
        }
    }
    $loc = $url .= "managefile.php?action=showproject&id=$id&mode=added";
    header("Location: $loc");
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
      	$thefolder = $thefolder["name"];
    	  $secure_name=$upfolder.'.'.$myfile->secure_name($thefolder);
      	$upath = "files/" . CL_CONFIG . "/$id/$secure_name";
      	if (!file_exists($upath)){ // those lines are for compatibility with former versions, which created named folders
    		  $upath = "files/" . CL_CONFIG . "/$id/" . $thefolder;
    	  }
    } else {
        $upath = "files/" . CL_CONFIG . "/$id";
        $upfolder = 0;
    }
    $num = count($_FILES);
    $chk = 0;
    foreach($_FILES as $file) {
        $fid = $myfile->uploadAsync($file["name"], $file["tmp_name"], $file["type"], $file["size"], $upath, $id, $upfolder);
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
                    if (is_array($sendto)) {
                        if (in_array($user["ID"], $sendto)) {
                            // check if subfolder exists, else root folder
                            $whichfolder = (!empty($thefolder)) ? $thefolder : $langfile["rootdir"];
                            // send email
                            $themail = new emailer($settings);
                            $themail->send_mail($user["email"], $langfile["filecreatedsubject"], $langfile["hello"] . ",<br /><br/>" . $langfile["filecreatedtext"] . "<br /><br />" . $langfile["project"] . ": " . $pname["name"] . "<br />" . $langfile["folder"] . ": " . $whichfolder . "<br />" . $langfile["file"] . ":  <a href = \"" . $url . $fileprops["datei"] . "\">" . $url . $fileprops["datei"] . "</a>");
                        }
                    } else {
                        // send email
                        $themail = new emailer($settings);
                        $themail->send_mail($user["email"], $langfile["filecreatedsubject"], "");
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
    $foldername = $thefolder['name'];
    $secure_name=$thisfile.'.'.$myfile->secure_name($foldername);    
    $relativePath='/files/' . CL_CONFIG . "/$id/$secure_name";
    if (!file_exists(CL_ROOT . $relativePath)){
    	$relativePath='/files/' . CL_CONFIG . "/$id/$thefolder[name]"; // for compatibility with files/folders created with former versions
    }
    
    $zipPath = CL_ROOT . $relativePath . '.zip';
    $zip = new PclZip($zipPath);

    if (file_exists($zipPath)) {
        if (unlink($zipPath)) {
            $create = $zip->create(CL_ROOT . $relativePath, PCLZIP_OPT_REMOVE_ALL_PATH);
        }
    } else {
        $create = $zip->create(CL_ROOT . $relativePath, PCLZIP_OPT_REMOVE_ALL_PATH);
    }
    if ($create != 0) {
    	$loc=$url.$relativePath.'.zip';
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
    $visible = getArrayVal($_POST, "visible");
    if (empty($visible[0])) {
        $visible = "";
    }
    if ($myfile->addFolder($parent, $id, $name, $desc, $visible)) {
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

?>
