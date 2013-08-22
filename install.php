<?php
error_reporting(0);
// Check if directory templates_c exists and is writable
if (!file_exists("./templates_c") or !is_writable("./templates_c")) {
    die("Required folder templates_c does not exist or is not writable. <br>Please create the folder or make it writable in order to proceed.");
}

// check if the settings table / object is present. if yes, assume collabtive is already installed and abort
if (!empty($settings)) {
    die("Collabtive seems to be already installed.<br />If this is an error, please clear your database.");
}

session_start();
session_destroy();
session_unset();
setcookie("PHPSESSID", "");
date_default_timezone_set("Europe/Berlin");
require("./init.php");
$action = getArrayVal($_GET, "action");
$locale = getArrayVal($_GET, "locale");

if (!empty($locale)) {
    $_SESSION['userlocale'] = $locale;
} else {
    $locale = $_SESSION['userlocale'];
}
if (empty($locale)) {
    $locale = "en";
}
$template->assign("locale", $locale);
$template->config_dir = "./language/$locale/";
$title = $langfile['installcollabtive'];
$template->assign("title", $title);
$template->template_dir = "./templates/standard/";

if (!$action) {
    // check if required directories are writable
    $configfilechk = is_writable(CL_ROOT . "/config/" . CL_CONFIG . "/config.php");
    $filesdir = is_writable(CL_ROOT . "/files/");
    $templatesdir = is_writable(CL_ROOT . "/templates_c/");
    $phpver = phpversion();
    $is_mbstring_enabled = extension_loaded('mbstring');

    $template->assign("phpver", $phpver);
    $template->assign("configfile", $configfilechk);
    $template->assign("filesdir", $filesdir);
    $template->assign("templatesdir", $templatesdir);
    $template->assign("is_mbstring_enabled", $is_mbstring_enabled);

    $template->display("install1.tpl");
} elseif ($action == "step2") {
    // check if the settings table / object is present. if yes, assume collabtive is already installed and abort
    if (!empty($settings)) {
        die("Collabtive seems to be already installed.<br />If this is an error, please clear your database.");
    }

    $db_host = $_POST['db_host'];
    $db_name = $_POST['db_name'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    // write db login data to config file
    $file = fopen(CL_ROOT . "/config/" . CL_CONFIG . "/config.php", "w+");
    $str = "<?php
\$db_host = '$db_host';\n
\$db_name = '$db_name';\n
\$db_user = '$db_user';\n
\$db_pass = '$db_pass';\n
?>";
    $put = fwrite($file, "$str");
    if ($put) {
        @chmod(CL_ROOT . "/config/" . CL_CONFIG . "/config.php", 0755);
    }
    $installer_include = "yes";
    // connect database.
    require_once("install_mysql.php");
    // Get the servers default timezone
    $timezone = date_default_timezone_get();
    // insert default settings
    /*$defSets = array("name" => "Collabtive", "subtitle" => "Projectmanagement", "locale" => $locale, "timezone" => $timezone, "dateformat" => "d.m.Y", "template" => "standard", "mailnotify" => 1, "mailfrom" => "collabtive@localhost", "mailfromname" => "", "mailmethod" => "mail", "mailhost" => "", "mailuser" => "", "mailpass" => "", "rssuser" => "", "rsspass" => "");
    foreach($defSets as $setKey => $setVal) {
        $ins = $conn->query("INSERT INTO settings (`settingsKey`,`settingsValue`) VALUES ('$setKey','$setVal')");
    }
*/
$ins =	$conn->query("INSERT INTO `settings` (`ID`, `settingsKey`, `settingsValue`) VALUES
(1, 'name', 'Collabtive'),
(2, 'subtitle', 'Collabtive'),
(3, 'locale', 'en'),
(4, 'timezone', '$timezone'),
(5, 'dateformat', 'd.m.Y'),
(6, 'template', 'standard'),
(7, 'mailnotify', '1'),
(8, 'mailfrom', 'collabtive@localhost'),
(9, 'mailfromname', ''),
(10, 'mailmethod', 'mail'),
(11, 'mailhost', ''),
(12, 'mailuser', ''),
(13, 'mailpass', ''),
(14, 'rssuser', ''),
(15, 'rsspass', '')");
    if (!$ins) {
        $template->assign("errortext", "Error: Failed to create initial settings.");
        $template->display("error.tpl");
        die();
    }
    $template->display("install2.tpl");
} elseif ($action == "step3") {
    mkdir(CL_ROOT . "/files/" . CL_CONFIG . "/");
    mkdir(CL_ROOT . "/files/" . CL_CONFIG . "/avatar/", 0777);
    mkdir(CL_ROOT . "/files/" . CL_CONFIG . "/ics/", 0777);

    require(CL_ROOT . "/config/" . CL_CONFIG . "/config.php");
    // Start database connection
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $user = $_POST['name'];
    $pass = $_POST['pass'];
    // create the first user
    $usr = new user();

    $installChk = $usr->getAllUsers();
    if(count($installChk > 0))
    {
		//There already are users. abort install.
		  die("Collabtive seems to be already installed.<br />If this is an error, please clear your database.");
	}

    $usrid = $usr->add($user, "", 0, $pass);
    if (!$usrid) {
        $template->assign("errortext", "Error: Failed to create first user.");
        $template->display("error.tpl");
        die();
    }
    // insert default roles
    $rolesobj = new roles();

    $adminrid = $rolesobj->add("Admin", array("add" => 1, "edit" => 1, "del" => 1 , "close" => 1, "view" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1, "view" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1, "view" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1, "view" => 1), array("add" => 1, "edit" => 1, "del" => 1, "view" => 1), array("add" => 1, "edit" => 1, "del" => 1, "read" => 1, "view" => 1), array("add" => 1), array("add" => 1));

    $userrid = $rolesobj->add("User", array("add" => 1, "edit" => 1, "del" => 0, "close" => 0, "view" => 1), array("add" => 1, "edit" => 1, "del" => 0, "close" => 1, "view" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1, "view" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1, "view" => 1), array("add" => 1, "edit" => 1, "del" => 1, "view" => 1), array("add" => 1, "edit" => 1, "del" => 1, "read" => 0, "view" => 1), array("add" => 1), array("add" => 0));

    $clientrid = $rolesobj->add("Client", array("add" => 0, "edit" => 0, "del" => 0, "close" => 0), array("add" => 0, "edit" => 0, "del" => 0, "close" => 0), array("add" => 0, "edit" => 0, "del" => 0, "close" => 0), array("add" => 0, "edit" => 0, "del" => 0, "close" => 0), array("add" => 0, "edit" => 0, "del" => 0), array("add" => 0, "edit" => 0, "del" => 0, "read" => 0), array("add" => 0), array("add" => 0));

    if (!$adminrid or !$userrid or !$clientrid) {
        $template->assign("errortext", "Error: Failed to create initial roles.");
        $template->display("error.tpl");
        die();
    }
    $rolesobj->assign($adminrid, $usrid);

    $template->display("install3.tpl");
}
?>
