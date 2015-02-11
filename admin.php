<?php

require("./init.php");
$action = getArrayVal($_GET, "action");
$mode = getArrayVal($_GET, "mode");
$id = getArrayVal($_GET, "id");
$name = getArrayVal($_POST, "name");
$subtitle = getArrayVal($_POST, "subtitle");
$isadmin = getArrayVal($_POST, "isadmin");
$email = getArrayVal($_POST, "email");
$tel1 = getArrayVal($_POST, "tel1");
$tel2 = getArrayVal($_POST, "tel2");
$pass = getArrayVal($_POST, "pass");
$company = getArrayVal($_POST, "company");
$address1 = getArrayVal($_POST, "address1");
$address2 = getArrayVal($_POST, "address2");
$tags = "";
$state = getArrayVal($_POST, "state");
$country = getArrayVal($_POST, "country");
$locale = getArrayVal($_POST, "locale");
$desc = getArrayVal($_POST, "desc");
$end = getArrayVal($_POST, "end");
$assign = getArrayVal($_POST, "assignme");
$assignto = getArrayVal($_POST, "assignto");
$language = getArrayVal($_POST, "language");
$timezone = getArrayVal($_POST, "timezone");
$dateformat = getArrayVal($_POST, "dateformat");
$templ = getArrayVal($_POST, "template");
$redir = getArrayVal($_GET, "redir");
$turl = getArrayVal($_POST, "web");
$gender = getArrayVal($_POST, "gender");
$zip = getArrayVal($_POST, "zip");
$newpass = getArrayVal($_POST, "newpass");
$repeatpass = getArrayVal($_POST, "repeatpass");
$rate = getArrayVal($_POST, "rate");
$budget = getArrayVal($_POST, "budget");
$role = getArrayVal($_POST, "role");
$rssuser = getArrayVal($_POST, "rssuser");
$rsspass = getArrayVal($_POST, "rsspass");

$template->assign("mode", $mode);
// get the available languages
$languages = getAvailableLanguages();
$template->assign("languages", $languages);

$user = new user();
$project = new project();
$companyObj = new company();
$theset = new settings();
$mainclasses = array("desktop" => "",
    "profil" => "",
    "admin" => "active"
    );
$template->assign("mainclasses", $mainclasses);

//check if the user is an admin
if (!$userpermissions["admin"]["add"] and $action != "addpro") {
    $errtxt = $langfile["nopermission"];
    $noperm = $langfile["accessdenied"];
    $template->assign("errortext", "$errtxt<br>$noperm");
    $template->display("error.tpl");
    die();
}

//Administration of users
if ($action == "users") {
    // Set the users tab active
    $classes = array("overview" => "overview",
        "system" => "system",
        "users" => "active"
        );
    $template->assign("classes", $classes);

	$roleobj = (object) new roles();
    // Get 25 users
    $users = $user->getAllUsers(25);
    // Get All Projects
    $projects = $project->getProjects(1, 10000);

    $roles = $roleobj->getAllRoles();
    $i2 = 0;

    if (!empty($users)) {
        foreach($users as $usr) {
            $i = 0;
            // Check if a user is assigned to a project
            if (!empty($projects)) {
                foreach ($projects as $pro) {
                    if (chkproject($usr["ID"], $pro["ID"])) {
                        $chk = 1;
                    } else {
                        $chk = 0;
                    }
                    $projects[$i]['assigned'] = $chk;
                    $i = $i + 1;
                }
            }

            $users[$i2]['projects'] = $projects;
            // Format the lastlogin timestamp to a string for display
            if (!empty($users[$i2]['lastlogin'])) {
                $users[$i2]["lastlogin"] = date(CL_DATEFORMAT . " / H:i:s", $users[$i2]['lastlogin']);
            }
            $i2 = $i2 + 1;
        }
    }
    $title = $langfile['useradministration'];
    $template->assign("title", $title);
    SmartyPaginate::assign($template);
    $template->assign("users", $users);
    $template->assign("projects", $projects);
    $template->assign("roles", $roles);
    $template->display("adminusers.tpl");
}
//add new user
elseif ($action == "adduser") {
    // Get the system locale and set it as the default locale for the new user
    $sysloc = $settings["locale"];
    // Add the user
    $newid = $user->add($name, $email, $company, $pass, $sysloc, $tags, $rate);
    //user has been created successfully
	if ($newid) {
		//are there projects the userd should be assigned to
        if (!empty($assignto)) {
            // Assign the user to all selected projects
            foreach ($assignto as $proj) {
                $project->assign($newid, $proj);
            }
        }
        $roleobj = (object) new roles();
        $roleobj->assign($role, $newid);

        if ($settings["mailnotify"]) {
            if (!empty($email)) {
                $subject = $langfile["profileaddedsubject"] . ' (' . $langfile['by'] . ' ' . $username . ')';
                $mailcontent = $langfile["hello"] . ",<br /><br/>" .
                $langfile["profileaddedtext"] . "<br /><br />" .
                $langfile["profileusername"] . ":&nbsp;" . "$name<br />" .
                $langfile["profilepass"] . ":&nbsp;" . "$pass<br /><br />" .
                "<a href = \"$url\">$url</a>";
                // send email
                $themail = new emailer($settings);
                $themail->send_mail($email, $subject, $mailcontent);
            }
        }
        header("Location: admin.php?action=users&mode=added");
    } else {
        // There has been an error, go back
        $goback = $langfile["goback"];
        $endafterstart = $langfile["endafterstart"];
        $template->assign("errortext", "The email address $email or the username $name already exists in the user database.<br>$goback");
        $template->display("error.tpl");
    }
} elseif ($action == "editform") {
    // This is for editing any user as an admin. This provides more options than the normal user edit
    $roleobj = (object) new roles();
    $roles = $roleobj->getAllRoles();

    $languages_fin = array();
    foreach($languages as $lang) {
        $lang2 = $langfile[$lang];
        $fin = countLanguageStrings($lang);

        if (!empty($lang2)) {
            $lang2 .= " (" . $fin . "%)";
            $fin = array("val" => $lang, "str" => $lang2);
        } else {
            $lang2 = $lang . " (" . $fin . "%)";
            $fin = array("val" => $lang, "str" => $lang2);
        }
        array_push($languages_fin, $fin);
    }
    $template->assign("languages_fin", $languages_fin);

    $user = $user->getProfile($id);
    $roleobj = (object) new roles();
    $roles = $roleobj->getAllRoles();

    $title = $langfile['useradministration'];
    $template->assign("title", $title);
    $template->assign("user", $user);
    $template->assign("roles", $roles);
    $template->display("edituseradminform.tpl");
} elseif ($action == "edituser") {

    $roleobj = new roles();
    $roleobj->assign($role, $id);
    if ($id == $userid) {
        $_SESSION['userlocale'] = $locale;
        $_SESSION['username'] = $name;
    }
    if (!isset($isadmin)) {
        $isadmin = 1;
    }
    // Upload of avatar
    if (!empty($_FILES['userfile']['name'])) {
        $fname = $_FILES['userfile']['name'];
        $typ = $_FILES['userfile']['type'];
        $size = $_FILES['userfile']['size'];
        $tmp_name = $_FILES['userfile']['tmp_name'];
        $error = $_FILES['userfile']['error'];
        $root = "./";

        $desc = getArrayVal($_POST,"desc" );
        $teilnamen = explode(".", $fname);
        $teile = count($teilnamen);
        $workteile = $teile - 1;
        $erweiterung = $teilnamen[$workteile];

        $subname = "";
        if ($typ != "image/jpeg" and $typ != "image/png" and $typ != "image/gif") {
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

        if ($user->edit($id, $name, "" , $email, $tel1, $tel2, $company, $zip, $gender, $turl, $address1, $address2, $state, $country, $tags, $locale, $avatar, $rate)) {
            if (!empty($newpass) and !empty($repeatpass)) {
                $user->admin_editpass($id, $newpass, $repeatpass);
            }
            header("Location: admin.php?action=users&mode=edited");
        }
    } else {
        if ($user->edit($id, $name, "", $email, $tel1, $tel2, $company, $zip, $gender, $turl, $address1, $address2, $state, $country, $tags, $locale, "", $rate)) {
            if (!empty($newpass) and !empty($repeatpass)) {
                $user->admin_editpass($id, $newpass, $repeatpass);
            }
            header("Location: admin.php?action=users&mode=edited");
        }
    }
} elseif ($action == "deleteuserform") {
    $usr = $user->getProfile($id);
    // Get user's projects
    $proj = new project();
    $projects = $proj->getMyProjects($id);
    // Get members of each project
    if (!empty($projects)) {
        for ($i = 0; $i < count($projects); $i++) {
            $members = $proj->getProjectMembers($projects[$i]["ID"]);
            $projects[$i]["members"] = $members;
        }

        $title = $langfile["deleteuser"];

        $template->assign("title", $title);
        $template->assign("user", $usr);
        $template->assign("projects", $projects);
        $template->display("admindeluserform.tpl");
    } else {
        if ($user->del($id)) {
            header("Location: admin.php?action=users&mode=deleted");
        }
    }
} elseif ($action == "deleteuser") {
    $id = getArrayVal($_POST, "id");
    $uprojects = getArrayVal($_POST, "uprojects");

    $proarr = array();
    foreach($uprojects as $upro) {
        $dat = explode("#", $upro);
        $todo = array("project" => $dat[0], "user" => $dat[1]);
        array_push($proarr, $todo);
    }

    if (!empty($proarr)) {
        $task = new task();

        foreach($proarr as $proj) {
            $tasks = $task->getAllMyProjectTasks($proj["project"], 100, $id);
            if ($proj["project"] > 0 and $proj["user"] > 0) {
                if (!empty($tasks)) {
                    foreach($tasks as $mytask) {
                        if ($task->deassign($mytask["ID"], $id)) {
                            $task->assign($mytask["ID"], $proj["user"]);
                        }
                    }
                }
            } else {
                if (!empty($tasks)) {
                    foreach($tasks as $mytask) {
                        $task->del($mytask["ID"]);
                    }
                }
            }
        }
    }

    if ($user->del($id)) {
        header("Location: admin.php?action=users&mode=deleted");
    }
} elseif ($action == "massassign") {
    $projects = $_POST['projects'];

    $user = $_POST['id'];
    $allprojects = $project->getProjects(1, 10000);
    $allpro = array();
    foreach($allprojects as $pro) {
        array_push($allpro, $pro[ID]);
    }

    if (!empty($allpro) and !empty($projects)) {
        $diff = array_diff($allpro, $projects);
    } elseif (empty($projects)) {
        $diff = $allpro;
    }

    if (!empty($projects)) {
        foreach($projects as $pro) {
            if (!chkproject($user, $pro)) {
                if ($settings["mailnotify"]) {
                    $usr = (object) new user();
                    $tuser = $usr->getProfile($user);

                    if (!empty($tuser["email"])) {
                        $userlang = readLangfile($tuser['locale']);

                        $subject = $userlang["projectassignedsubject"] . ' (' . $userlang['by'] . ' ' . $username . ')';
                        $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                        $userlang["projectassignedtext"] .

                        " <a href = \"" . $url . "manageproject.php?action=showproject&id=$pro\">" . $url . "manageproject.php?action=showproject&id=$pro</a>";
                        // send email
                        $themail = new emailer($settings);
                        $themail->send_mail($tuser["email"], $subject , $mailcontent);
                    }
                }
                $project->assign($user, $pro);
            }
        }
    }
    if (!empty($diff)) {
        foreach($diff as $mydiff) {
            $project->deassign($user, $mydiff);
        }
    }

    header("Location: admin.php?action=users&mode=de-assigned");
}
//Administer projects
elseif ($action == "projects") {
    $classes = array("overview" => "active",
        "system" => "system",
        "users" => "users"
        );
    $title = $langfile['projectadministration'];
    $template->assign("title", $title);
    $template->assign("classes", $classes);
    $opros = $project->getProjects(1, 10000);
    $clopros = $project->getProjects(0, 10000);
    $i = 0;
    $users = $user->getAllUsers(1000000);
    if (!empty($opros)) {
        foreach($opros as $opro) {
            $membs = $project->getProjectMembers($opro["ID"], 1000);
            $opros[$i]['members'] = $membs;
            $i = $i + 1;
        }
        $template->assign("opros", $opros);
    }

    $customers = $companyObj->getAllCompanies();

    $template->assign("customers", $customers);
    $template->assign("users", $users);
    $template->assign("clopros", $clopros);
    $template->display("adminprojects.tpl");
}
//add new project
elseif ($action == "addpro") {
    if (!$userpermissions["projects"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    if (!$end) {
        $end = 0;
    }

	//add the project
    $add = $project->add($name, $desc, $end, $budget, 0);
	//project has been added
    if ($add) {
        foreach ($assignto as $member) {
            $project->assign($member, $add);

            if ($settings["mailnotify"]) {
                $usr = (object) new user();
                $user = $usr->getProfile($member);

                if (!empty($user["email"])) {
                    $userlang = readLangfile($user['locale']);

                    $subject = $userlang["projectassignedsubject"] . ' (' . $userlang['by'] . ' ' . $username . ')';
                    $mailcontent = $userlang["hello"] . ",<br /><br/>" .
                    $userlang["projectassignedtext"] .
                    " <a href = \"" . $url . "manageproject.php?action=showproject&id=$add\">" . $url . "manageproject.php?action=showproject&id=$add</a>";
                    // send email
                    $themail = new emailer($settings);
                    $themail->send_mail($user["email"], $subject , $mailcontent);
                }
            }
        }
    	if($company > 0)
    	{
    		$companyObj->assign($company, $add);
    	}
    	header("Location: manageproject.php?action=showproject&id=$add");
    }
} elseif ($action == "closepro") {
    if ($project->close($id)) {
        echo "ok";
        // header("Location: admin.php?action=projects&mode=closed");
    }
} elseif ($action == "openpro") {
    if ($project->open($id)) {
        header("Location: admin.php?action=projects&mode=opened");
    }
} elseif ($action == "deletepro") {
    if ($project->del($id)) {
        // header("Location: admin.php?action=projects&mode=deleted");
        echo "ok";
    }
} elseif ($action == "customers") {
    $classes = array("overview" => "overview",
        "customer" => "active",
        "system" => "system",
        "users" => "users"
        );
    $title = $langfile['customeradministration'];
    $template->assign("title", $title);
    $template->assign("classes", $classes);
    $allcust = $companyObj->getAllCompanies();
    // $clopros = $project->getProjects(0, 10000);
    /* $i = 0;
    $users = $user->getAllUsers(1000000);
    if (!empty($opros)) {
        foreach($opros as $opro) {
            $membs = $project->getProjectMembers($opro["ID"], 1000);
            $opros[$i]['members'] = $membs;
            $i = $i + 1;
        }
        $template->assign("opros", $opros);
    }*/

    $template->assign("allcust", $allcust);
    $template->display("admincustomers.tpl");
} elseif ($action == "addcust") {
    if (!$userpermissions["admin"]["add"]) {
        $errtxt = $langfile["nopermission"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    if (!$end) {
        $end = 0;
    }
    $data = array('company' => getArrayVal($_POST, "company"),
        'contact' => getArrayVal($_POST, "contact"),
        'email' => getArrayVal($_POST, "email"),
        'phone' => getArrayVal($_POST, "tel1"),
        'mobile' => getArrayVal($_POST, "tel2"),
        'url' => getArrayVal($_POST, "web"),
        'address' => getArrayVal($_POST, "address"),
        'zip' => getArrayVal($_POST, "zip"),
        'city' => getArrayVal($_POST, "city"),
        'country' => getArrayVal($_POST, "country"),
        'state' => getArrayVal($_POST, "state"),
        'desc' => getArrayVal($_POST, "desc")
        );
    $add = $companyObj->add($data);
    if ($add)
        header("Location: admin.php?action=customers&mode=added");
}
elseif($action == "assigncustomer")
{

}
elseif ($action == "system") {
    $classes = array("overview" => "overview",
        "system" => "active",
        "users" => "users"
        );

    $languages_fin = array();
    foreach($languages as $lang) {
        $fin = countLanguageStrings($lang);
        if (!empty($langfile[$lang])) {
            $lang2 = $langfile[$lang];
            $lang2 .= " (" . $fin . "%)";
            $fin = array("val" => $lang, "str" => $lang2);
        } else {
            $lang2 = $lang . " (" . $fin . "%)";
            $fin = array("val" => $lang, "str" => $lang2);
        }
        array_push($languages_fin, $fin);
    }

    $msgcount = getArrayVal($_GET, "msg");
    $peoplecount = getArrayVal($_GET, "peop");
    $procount = getArrayVal($_GET, "pro");
    $taskcount = getArrayVal($_GET, "tsk");

    $template->assign("msgcount", $msgcount);
    $template->assign("peoplecount", $peoplecount);
    $template->assign("procount", $procount);
    $template->assign("taskcount", $taskcount);

    $template->assign("languages_fin", $languages_fin);
    $title = $langfile["systemadministration"];
    $template->assign("title", $title);
    $template->assign("classes", $classes);
    $sets = $theset->getSettings();
    $templates = $theset->getTemplates();
    $themes = $theset->getThemes($settings["template"]);
    $template->assign("themes", $themes);
    $template->assign("settings", $sets);
    $timezones = DateTimeZone::listIdentifiers();
    $template->assign("timezones", $timezones);
    $template->assign("templates", $templates);
    $template->display("editsettings.tpl");
} elseif ($action == "editsets") {
    $theme = getArrayVal($_POST, "theme");
    if ($theset->editSettings($name, $subtitle, $locale, $timezone, $dateformat, $templ, $theme, $rssuser, $rsspass)) {
        $handle = opendir($template->compile_dir);
        while (false !== ($file = readdir($handle))) {
            if ($file != "." and $file != "..") {
                unlink(CL_ROOT . "/" . $template->compile_dir . "/" . $file);
            }
        }
        $_SESSION["userlocale"] = $locale;
        $users = $user->getAllUsers(100000);
        foreach($users as $theuser) {
            // set the new locale for all the users
            $user->edit($theuser["ID"], $theuser["name"], $theuser["realname"], $theuser["email"], $theuser["tel1"], $theuser["tel2"], $theuser["company"], $theuser["zip"], $theuser["gender"], $theuser["url"], $theuser["adress"], $theuser["adress2"], $theuser["state"], $theuser["country"], $theuser["tags"], $locale, "", $theuser["rate"]);
        }
        header("Location: admin.php?action=system&mode=edited");
    }
} elseif ($action == "editmailsets") {
    $status = getArrayVal($_POST, "mailstatus");
    $mailfrom = getArrayVal($_POST, "mailfrommail");
    $mailfromname = getArrayVal($_POST, "mailfromname");
    $method = getArrayVal($_POST, "mailmethod");
    $server = getArrayVal($_POST, "server");
    $mailuser = getArrayVal($_POST, "mailuser");
    $mailpass = getArrayVal($_POST, "mailpass");

    if ($theset->editMailsettings($status, $mailfrom, $mailfromname, $method, $server, $mailuser, $mailpass)) {
        header("Location: admin.php?action=system&mode=edited");
    }
}

?>
