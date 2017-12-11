<?php
include("init.php");

$user = (object)new user();

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

//Get Data from $_POST and $_GET filtered and sanitized by htmlpurifier
$cleanGet = cleanArray($_GET);
$cleanPost = cleanArray($_POST);

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

/*
 * VIEW ROUTES
 * These are routes that render HTML views to the browser or create side effects
 */
if ($action == "loginerror") {
    $template->display("resetpassword.tpl");
} elseif ($action == "resetpassword") {
    $cleanPost["newpass"] = $user->resetPassword($cleanPost["email"]);
    if ($cleanPost["newpass"] !== false) {
        $langFile = readLangfile($cleanPost["newpass"]['locale']);

        $subject = $langfile["projectpasswordsubject"];

        $mailcontent = $langfile["hello"] . ",<br /><br/>" .
            $langfile["projectpasswordtext"] . "<br /><br />" .
            $langfile["newpass"] . ": " . $cleanPost["newpass"]['newpass'] . "<br />" .
            $langfile["login"] . ": <a href = \"$url\">$url</a>";
        // Send e-mail with new password
        $themail = new emailer($settings);
        $themail->send_mail($cleanPost["email"], $subject, $mailcontent);

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
    } // Login Error
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
    foreach ($languages as $lang) {
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
    $_SESSION['userlocale'] = $cleanPost["locale"];
    $_SESSION['username'] = $cleanPost["name"];

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

        $allowedFiletypes = array("image/jpeg", "image/png", "image/gif", "image/pjpeg");
        if (!in_array($typ, $allowedFiletypes)) {
            $loc = $url . "manageuser.php?action=profile&id=$userid";
            header("Location: $loc");
            die();
        }

        // If it is a PHP file, treat as plain text so it is not executed when opened in the browser

        $allowedExtensions = array(".jpg", ".jpeg", ".gif", ".pjpeg");
        if (!in_array($erweiterung, $allowedExtensions)) {
            $erweiterung = "txt";
            $typ = "text/plain";
        }


        for ($i = 0; $i < $workteile; $i++) {
            $subname .= $teilnamen[$i];
        }
        list($usec, $sec) = explode(' ', microtime());
        $seed = (float)$sec + ((float)$usec * 100000);
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

        if ($user->edit($userid, $cleanPost["name"], $cleanPost["realname"], $cleanPost["email"], $cleanPost["tel1"], $cleanPost["tel2"], "", $cleanPost["zip"], $cleanPost["gender"], $cleanPost["web"], $cleanPost["address1"], $cleanPost["address2"], $cleanPost["state"], $cleanPost["country"], "", $cleanPost["locale"], $avatar, 0)) {
            if (!empty($cleanPost["oldpass"]) and !empty($cleanPost["newpass"]) and !empty($cleanPost["repeatpass"])) {
                $user->editpass($userid, $cleanPost["oldpass"], $cleanPost["newpass"], $cleanPost["repeatpass"]);
            }
            $loc = $url . "manageuser.php?action=profile&id=$userid&mode=edited";
            header("Location: $loc");
        }
    } else {
        if ($user->edit($userid, $cleanPost["name"], $cleanPost["realname"], $cleanPost["email"], $cleanPost["tel1"], $cleanPost["tel2"], $cleanPost["company"], $cleanPost["zip"], $cleanPost["gender"], $cleanPost["web"], $cleanPost["address1"], $cleanPost["address2"], $cleanPost["state"], $cleanPost["country"], "", $cleanPost["locale"], "", 0)) {
            if (isset($cleanPost["oldpass"]) and isset($cleanPost["newpass"]) and isset($cleanPost["repeatpass"])) {
                $user->editpass($userid, $cleanPost["oldpass"], $cleanPost["newpass"], $cleanPost["repeatpass"]);
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
    $start = getArrayVal($_GET, "start");
    $end = getArrayVal($_GET, "end");

    $profile = $user->getProfile($id);

    $title = $langfile['userprofile'];
    $template->assign("title", $title);
    $template->assign("user", $profile);

    // This is done specifically to address an XSS vulnerability
    // caused by rendering two user inputs on same line in HTML.
    // They must be escaped together and rendered as one.
    $template->assign("zipcity", purify(implode(' ', [$profile['zip'], $profile['adress2']])));

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
    $projectObj = (object)new project();
    $alluser = $user->getAllUsers(10000);
    $users = array();

    foreach ($alluser as $all) {
        if (!chkproject($all['ID'], $id)) {
            array_push($users, $all);
        }
    }

    $members = $projectObj->getProjectMembers($id, 14);
    $pro = $projectObj->getProject($id);

    $projectname = $pro['name'];
    $title = $langfile['members'];

    $template->assign("title", $title);
    $template->assign("projectname", $projectname);
    $template->assign("members", $members);
    $template->assign("users", $users);
    $template->display("projectmembers.tpl");
} elseif ($action == "onlinelist") {
    $onlinelist = $user->getOnlinelist();
    if (!empty($onlinelist)) {
        echo "<ul>";
        foreach ($onlinelist as $online) {

            echo "<li>" . "<a class=\"user\" href = \"manageuser.php?action=profile&id=" . $online["ID"] . "\">" . $online["name"] . "</a></li>";
        }
        echo "</ul>";
    }
} elseif ($action == "vcard") {
    $theuser = $user->getProfile($id);

    $vCard = (object)new vCard($theuser["locale"]);

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
} /*
 * DATA ROUTES
 * These are routes that render JSON data structures
 */
elseif ($action == "projectMembers") {
    if (!chkproject($userid, $id)) {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("mode", "error");
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $offset = 0;
    if (isset($cleanGet["offset"])) {
        $offset = $cleanGet["offset"];
    }
    $limit = 21;
    if (isset($cleanGet["limit"])) {
        $limit = $cleanGet["limit"];
    }

    $projectObj = new project();

    $members = $projectObj->listProjectMembers($id, $limit, $offset);

    $projectMembers["count"] = $projectObj->countMembers($id);
    $projectMembers["items"] = $members;

    echo json_encode($projectMembers);

} elseif ($action == "userProjects") {
    if ($userpermissions["admin"]["add"] || $userid == $id) {
        $offset = 0;
        if (isset($cleanGet["offset"])) {
            $offset = $cleanGet["offset"];
        }
        $limit = 10;
        if (isset($cleanGet["limit"])) {
            $limit = $cleanGet["limit"];
        }

        $projectObj = (object)new project();

        $openProjects = $projectObj->getMyProjects($id, 1, $offset, $limit);
        $openProjectsNum = $projectObj->countMyProjects($id);

        $i = 0;
        if (!empty($openProjects)) {
            foreach ($openProjects as &$openProject) {
                $projectMembers = $projectObj->getProjectMembers($openProject["ID"], 1000);
                $openProject["members"] = $projectMembers;
                $i = $i + 1;
            }
        }

        $projects["items"] = $openProjects;
        $projects["count"] = $openProjectsNum;
    }
    if (!empty($projects)) {
        echo json_encode($projects);
    } else {
        echo json_encode(array());
    }

} elseif ($action == "userTimetracker") {
    if ($userpermissions["admin"]["add"] || $userid == $id) {
        $tracker = (object)new timetracker();

        $track = array();
        $offset = 0;
        if (isset($cleanGet["offset"])) {
            $offset = $cleanGet["offset"];
        }
        $limit = 10;
        if (isset($cleanGet["limit"])) {
            $limit = $cleanGet["limit"];
        }

        $track = $tracker->getUserTrack($id, 0, 0, 0, 0, $limit, $offset);
        $userTrack["items"] = $track;
        $trackCount = count($tracker->getUserTrack($id, 0, 0, 0, 0, 10000000));
        $userTrack["count"] = $trackCount;
    }
    if (!empty($userTrack)) {
        echo json_encode($userTrack);
    } else {
        echo json_encode(array());
    }
}