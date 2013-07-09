<?php
include("init.php");

$user = (object) new user();

$action = getArrayVal($_GET, "action");
$id = getArrayVal($_GET, "id");
$mode = getArrayVal($_GET, "mode");

if ($action != "login" and $action != "logout" and $action != "resetpassword" and $action != "loginerror") {
    if (!isset($_SESSION["userid"])) {
        $template->assign("loginerror", 0);
        $template->display("login.tpl");
        die();
    }
}

$name = getArrayVal($_POST, "name");
$realname = getArrayVal($_POST, "realname");
$role = getArrayVal($_POST, "role");
$email = getArrayVal($_POST, "email");
$tel1 = getArrayVal($_POST, "tel1");
$tel2 = getArrayVal($_POST, "tel2");
$company = getArrayVal($_POST, "company");
$address1 = getArrayVal($_POST, "address1");
$address2 = getArrayVal($_POST, "address2");
$state = getArrayVal($_POST, "state");
$country = getArrayVal($_POST, "country");
$locale = getArrayVal($_POST, "locale");
$tags = getArrayVal($_POST, "tags");
$oldpass = getArrayVal($_POST, "oldpass");
$newpass = getArrayVal($_POST, "newpass");
$repeatpass = getArrayVal($_POST, "repeatpass");
$admin = getArrayVal($_POST, "admin");
$turl = getArrayVal($_POST, "web");
$gender = getArrayVal($_POST, "gender");
$zip = getArrayVal($_POST, "zip");
$taski = getArrayVal($_GET, "task");
$fproject = getArrayVal($_GET, "project");

$template->assign("mode", $mode);
// get the available languages
$languages = getAvailableLanguages();
$template->assign("languages", $languages);

$project = array();
$project['ID'] = $id;
$template->assign("project", $project);
// set css classes for menue buttons
$classes = array("overview" => "overview", "msgs" => "msgs", "tasks" => "tasks", "miles" => "miles", "files" => "files", "users" => "users_active", "tracker" => "tracking");

$mainclasses = array("desktop" => "",
    "profil" => "active",
    "admin" => ""
    );
$template->assign("mainclasses", $mainclasses);
$template->assign("classes", $classes);

if ($action == "loginerror") {
    $template->display("resetpassword.tpl");
} elseif ($action == "resetpassword") {
    $newpass = $user->resetPassword($email);
    if ($newpass != "") {
        // Send e-mail with new password
        $themail = new emailer($settings);
        $themail->send_mail($email, $langfile["projectpasswordsubject"], $langfile["hello"] . ",<br /><br/>" . $langfile["projectpasswordtext"] . "<br /><br />" . $langfile["newpass"] . ": " . "$newpass<br />" . $langfile["login"] . ": <a href = \"$url\">$url</a>");

        $template->assign("success", 1);
        $template->display("resetpassword.tpl");
    } else {
        $template->assign("loginerror", 1);
        $template->display("resetpassword.tpl");
    }
} elseif ($action == "login") {
    $openid = getArrayVal($_POST, "openid");
    $username = getArrayVal($_POST, "username");
    $pass = getArrayVal($_POST, "pass");
    // Normal login
    if ($user->login($username, $pass)) {
        $loc = $url . "index.php?mode=login";
        header("Location: $loc");
    }
    // Login Error
    else {
        $template->assign("loginerror", 1);
        $template->assign("mailnotify", $settings["mailnotify"]);
        $template->display("login.tpl");
    }
} elseif ($action == "logout") {
    if ($user->logout()) {
        header("Location: index.php?mode=logout");
    }
} elseif ($action == "addform") {
    $title = $langfile['adduser'];
    $template->assign("title", $title);
    $template->display("adduserform.tpl");
} elseif ($action == "editform") {
    $languages_fin = array();
    foreach($languages as $lang) {
        $fin = countLanguageStrings($lang);

        if (!($langfile[$lang] == "")) {
            $lang2 = $langfile[$lang];
        } else {
            $lang2 = $lang;
        }

        $lang2 .= " (" . $fin . "%)";
        $fin = array("val" => $lang, "str" => $lang2);

        array_push($languages_fin, $fin);
    }
    $template->assign("languages_fin", $languages_fin);

    $title = $langfile['edituser'];

    $template->assign("title", $title);
    $euser = $user->getProfile($userid);
    $template->assign("user", $euser);

    $template->display("edituserform.tpl");
} elseif ($action == "edit") {
    $_SESSION['userlocale'] = $locale;
    $_SESSION['username'] = $name;

    if (!empty($_FILES['userfile']['name'])) {
        $fname = $_FILES['userfile']['name'];
        $typ = $_FILES['userfile']['type'];
        $size = $_FILES['userfile']['size'];
        $tmp_name = $_FILES['userfile']['tmp_name'];
        $error = $_FILES['userfile']['error'];
        $root = "./";

        $teilnamen = explode(".", $fname);
        $teile = count($teilnamen);
        $workteile = $teile - 1;
        $erweiterung = $teilnamen[$workteile];

        $subname = "";
        if ($typ != "image/jpeg" and $typ != "image/png" and $typ != "image/gif" and $typ != "image/pjpeg") {
            $loc = $url . "manageuser.php?action=profile&id=$userid";
            header("Location: $loc");
            die();
        }
        // don't upload php scripts
        if ($erweiterung == "php" or $erweiterung == "pl") {
            $loc = $url . "manageuser.php?action=profile&id=$userid";
            header("Location: $loc");
            die();
        }

        for ($i = 0; $i < $workteile; $i++) {
            $subname .= $teilnamen[$i];
        }
        list($usec, $sec) = explode(' ', microtime());
        $seed = (float) $sec + ((float) $usec * 100000);
        srand($seed);
        $randval = rand(1, 999999);

        $subname = preg_replace("/[^-_0-9a-zA-Z]/", "_", $subname);
        $subname = preg_replace("/\W/", "", $subname);

        if (strlen($subname) > 200) {
            $subname = substr($subname, 0, 200);
        }

        $fname = $subname . "_" . $randval . "." . $erweiterung;

        $datei_final = CL_ROOT . "/files/" . CL_CONFIG . "/avatar/" . $fname;

        if (move_uploaded_file($tmp_name, $datei_final)) {
            $avatar = $fname;
        }

        if ($user->edit($userid, $name, $realname, $email, $tel1, $tel2, "", $zip, $gender, $turl, $address1, $address2, $state, $country, "", $locale, $avatar, 0)) {
            if (!empty($oldpass) and !empty($newpass) and !empty($repeatpass)) {
                $user->editpass($userid, $oldpass, $newpass, $repeatpass);
            }
            $loc = $url . "manageuser.php?action=profile&id=$userid&mode=edited";
            header("Location: $loc");
        }
    } else {
        if ($user->edit($userid, $name, $realname, $email, $tel1, $tel2, $company, $zip, $gender, $turl, $address1, $address2, $state, $country, "", $locale, "", 0)) {
            if (isset($oldpass) and isset($newpass) and isset($repeatpass)) {
                $user->editpass($userid, $oldpass, $newpass, $repeatpass);
            }
            $loc = $url . "manageuser.php?action=profile&id=$userid&mode=edited";
            header("Location: $loc");
        }
    }
} elseif ($action == "del") {
    if (!$userpermissions["admin"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->assign("mode", "error");
        $template->display("error.tpl");
        die();
    }
    if ($user->del($id)) {
        $loc = $url . "admin.php?action=users&mode=deleted";
        header("Location: $loc");
    }
} elseif ($action == "profile") {
    $thetag = new tags();
    $start = getArrayVal($_GET, "start");
    $end = getArrayVal($_GET, "end");
    $proj = (object) new project();
    if ($userpermissions["admin"]["add"]) {
        $projects = $proj->getMyProjects($id);
        $i = 0;
        if (!empty($projects)) {
            foreach($projects as $opro) {
                $membs = $proj->getProjectMembers($opro["ID"], 1000);
                $projects[$i]['members'] = $membs;
                $i = $i + 1;
            }
            $template->assign("opros", $projects);
        }
    }
    $tracker = (object) new timetracker();

    $track = array();
    if (!empty($start) and !empty($end)) {
        $track = $tracker->getUserTrack($id, $fproject, $taski, $start, $end);
    } elseif (is_array($fproject)) {
        foreach ($fproject as $fpro) {
            $ptrack = $tracker->getUserTrack($id, $fpro, $taski, $start, $end);
            if (!empty($ptrack)) {
                foreach ($ptrack as $mytrack) {
                    array_push($track, $mytrack);
                }
            }
        }
    } else {
        $track = $tracker->getUserTrack($id, $fproject, $taski);
    }
    if (!empty($track)) {
        $totaltime = $tracker->getTotalTrackTime($track);
        $template->assign("totaltime", $totaltime);
        $template->assign("fproject", $fproject);
        $template->assign("start", $start);
        $template->assign("end", $end);
    }
    $template->assign("tracker", $track);
    SmartyPaginate::assign($template);
    $profile = $user->getProfile($id);

    $title = $langfile['userprofile'];
    $template->assign("title", $title);
    $template->assign("user", $profile);

    $template->display("userprofile.tpl");
} elseif ($action == "showproject") {
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("mode", "error");
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }
    $mainclasses = array("desktop" => "desktop",
        "profil" => "profil",
        "admin" => "admin"
        );
    $template->assign("mainclasses", $mainclasses);
    $proj = (object) new project();
    $alluser = $user->getAllUsers(10000);
    $users = array();

    foreach($alluser as $all) {
        if (!chkproject($all['ID'], $id)) {
            array_push($users, $all);
        }
    }
    SmartyPaginate::disconnect();

    $members = $proj->getProjectMembers($id, 14);
    $pro = $proj->getProject($id);

    $projectname = $pro['name'];
    $title = $langfile['members'];

    $template->assign("title", $title);
    $template->assign("projectname", $projectname);
    SmartyPaginate::assign($template);
    $template->assign("members", $members);
    $template->assign("users", $users);
    $template->display("projectmembers.tpl");
} elseif ($action == "onlinelist") {
    $onlinelist = $user->getOnlinelist();
    if (!empty($onlinelist)) {
        echo "<ul>";
        foreach($onlinelist as $online) {
            if ($online["avatar"]) {
                $userpic = "thumb.php?pic=files/" . CL_CONFIG . "/avatar/$online[avatar]&width=90";
            } elseif ($online["gender"] == "f") {
                $userpic = "thumb.php?pic=templates/standard/images/no-avatar-female.jpg&amp;width=90";
            } else {
                $userpic = "thumb.php?pic=templates/standard/images/no-avatar-male.jpg&amp;width=90";
            }
            echo "<li>" . "<a class=\"user\" href = \"manageuser.php?action=profile&id=$online[ID]\">$online[name]<div><img src = \"$userpic\" /></div> </a>";
            if ($online['ID'] != $userid and $userpermissions["chat"]["add"]) {
                echo " <a class=\"chat\" href = \"javascript:openChatwin('$online[name]',$online[ID]);\" title=\"chat\"></a>";
            } elseif ($online['ID'] == $userid and $userpermissions["chat"]["add"]) {
                echo " <a class=\"chat-user\" ></a>";
            }
            echo "</li>";
        }
        echo "</ul>";
    }
} elseif ($action == "vcard") {
    $theuser = $user->getProfile($id);

    $vCard = (object) new vCard($theuser["locale"]);

    $vCard->setFirstName($theuser["name"]);
    $vCard->setCompany($theuser["company"]);
    $vCard->setOrganisation($theuser["company"]);
    $vCard->setPostalStreet($theuser["adress"]);
    $vCard->setPostalZIP($theuser["zip"]);
    $vCard->setWorkZIP($theuser["zip"]);
    $vCard->setHomeZIP($theuser["zip"]);
    $vCard->setPostalCity($theuser["adress2"]);
    $vCard->setHomeCity($theuser["adress2"]);
    $vCard->setPostalRegion($theuser["state"]);
    $vCard->setPostalCountry($theuser["country"]);
    $vCard->setWorkStreet($theuser["adress"]);
    $vCard->setWorkCity($theuser["adress2"]);
    $vCard->setWorkRegion($theuser["state"]);
    $vCard->setWorkCountry($theuser["country"]);
    $vCard->setUrlWork($theuser["url"]);
    $vCard->setEMail($theuser["email"]);

    header('Content-Type: text/x-vcard');
    header('Content-Disposition: inline; filename=' . $theuser["name"] . '_' . date("d-m-Y") . '.vcf');

    echo $vCard->getCardOutput();
}

?>