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
    $num = $_POST['numfiles'];

    if ($upfolder) {
        $thefolder = $myfile->getFolder($upfolder);
        $thefolder = $thefolder["name"];
        $upath = "files/" . CL_CONFIG . "/$id/" . $thefolder;
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
    $files = $myfile->getProjectFiles($id);
    $finfiles = array();
    if (!empty($files)) {
        foreach($files as $file) {
            if ($file["visible"]) {
                $filevis = unserialize($file["visible"]);

                if (is_array($filevis)) {
                    if (in_array($userpermissions["ID"], $filevis)) {
                        array_push($finfiles, $file);
                    }
                } else {
                    array_push($finfiles, $file);
                }
            } else {
                array_push($finfiles, $file);
            }
        }
    }

    $filenum = count($finfiles);
    if (empty($finfiles)) {
        $filenum = 0;
    }
    $folders = $myfile->getProjectFolders($id);

    $finfolders = array();
    if (!empty($folders)) {
        foreach($folders as $folder) {
            if ($folder["visible"]) {
                $foldvis = unserialize($folder["visible"]);

                if (is_array($foldvis)) {
                    if (in_array($userpermissions["ID"], $foldvis)) {
                        array_push($finfolders, $folder);
                    }
                } else {
                    array_push($finfolders, $folder);
                }
            } else {
                array_push($finfolders, $folder);
            }
        }
    }
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
    $template->assign("files", $finfiles);
    $template->assign("filenum", $filenum);
    $template->assign("folders", $finfolders);
    $template->assign("members", $members);
    $template->assign("roles", $allroles);

    $template->assign("allfolders", $allfolders);
    $template->assign("postmax", $POST_MAX_SIZE);
    $template->display("projectfiles.tpl");
} elseif ($action == "addfolder") {
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
    $file = $_GET["file"];
    $file = substr($file, 4, strlen($file)-4);

    $target = $_GET["target"];
    $myfile->moveFile($file, $target);
}

?>