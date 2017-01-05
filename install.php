<?php
error_reporting(0);

// Check if directory 'templates_c' exists and is writable
if (!file_exists('./templates_c') and !mkdir('./templates_c')){
	die("Required folder 'templates_c' does not exist and could not be created. <br>Please create the folder and make it writable in order to proceed.");
}

if (!is_writable("./templates_c")) {
	die("Required folder 'templates_c' is not writable. <br>Please make the folder writable in order to proceed.");
}

// check if the settings table / object is present. If yes, assume Collabtive is already installed and abort
if (!empty($settings)) {
    die("Collabtive seems to be already installed.<br />If this is an error, please clear your database.");
}

session_start();
session_destroy();
session_unset();
setcookie("PHPSESSID", "");
date_default_timezone_set("Europe/Berlin");
$db_driver = "sqlite";
require("./init.php");

//error_reporting(0);

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

$title = $langfile['installcollabtive'];

$template->config_dir = "./language/$locale/";
$template->template_dir = "./templates/standard/";

$installSettings["template"] = "standard";
$installSettings["theme"] = "standard";

$template->assign("locale", $locale);
$template->assign("title", $title);
$template->assign("settings", $installSettings);

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
    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

        for ($i = 0; $i < 16; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }

	//create a random password to encrypt files with
    $filePass = randomPassword();

    // check if the settings table / object is present. if yes, assume collabtive is already installed and abort
    if (!empty($settings)) {
        die("Collabtive seems to be already installed.<br />If this is an error, please clear your database.");
    }

    $db_host = $_POST['db_host'];
    $db_name = $_POST['db_name'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $db_driver = $_POST['db_driver'];

    // write db login data to config file
    $file = fopen(CL_ROOT . "/config/" . CL_CONFIG . "/config.php", "w+");
    $str = "<?php
\$db_host = '$db_host';\n
\$db_name = '$db_name';\n
\$db_user = '$db_user';\n
\$db_pass = '$db_pass';\n
\$db_driver = '$db_driver';\n
?>";

    $put = fwrite($file, "$str");
    if ($put) {
        @chmod(CL_ROOT . "/config/" . CL_CONFIG . "/config.php", 0755);
    }

	//this will be checked in install_mysql to make sure it can only be run from the installer
    $installer_include = "yes";

    // connect database.
    switch ($db_driver) {
        case "mysql":
            require_once("install_mysql.php");
            break;
        case "sqlite":
            $conn = new PDO("sqlite:" . CL_ROOT . "/files/collabtive.sdb");
            break;
    }

    // Get the servers default timezone
    $timezone = date_default_timezone_get();

    // insert default settings
    $defSets = array("name" => "Collabtive", "subtitle" => "Projectmanagement", "locale" => $locale, "timezone" => $timezone, "dateformat" => "d.m.Y", "template" => "standard", "mailnotify" => 1, "mailfrom" => "collabtive@localhost", "mailfromname" => "", "mailmethod" => "mail", "mailhost" => "", "mailuser" => "", "mailpass" => "", "rssuser" => "", "rsspass" => "", "theme" => "standard", "filePass" => $filePass);

    foreach($defSets as $setKey => $setVal) {
        $ins = $conn->query("INSERT INTO settings (`settingsKey`,`settingsValue`) VALUES ('$setKey','$setVal')");
    }

    if (!$ins) {
        $template->assign("errortext", "Error: Failed to create initial settings.");
        $template->display("error.tpl");
        die();
    }

    $template->display("install2.tpl");
} elseif ($action == "step3") {
	//create required folders
    mkdir(CL_ROOT . "/files/" . CL_CONFIG . "/");
    mkdir(CL_ROOT . "/files/" . CL_CONFIG . "/avatar/", 0777);
    mkdir(CL_ROOT . "/files/" . CL_CONFIG . "/ics/", 0777);

	//include the database cnfig
    require(CL_ROOT . "/config/" . CL_CONFIG . "/config.php");

    // Start database connection
    switch ($db_driver) {
        case "mysql":
            $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
            break;
        case "sqlite":
            $conn = new PDO("sqlite:" . CL_ROOT . "/files/collabtive.sdb");
            break;
    }

    $user = getArrayVal($_POST, "name");
    $pass = getArrayVal($_POST, "pass");

    // create the first user
    $usr = new user();

	//check if there are existing users, in which case collabtive is already installed
    $installChk = $usr->getAllUsers();
    if ($installChk) {
        // There already are users. abort install.
        die("Collabtive seems to be already installed.<br />If this is an error, please clear your database.");
    }

	//add the first user
    $usrid = $usr->add($user, "", "", $pass);
    if (!$usrid) {
        $template->assign("errortext", "Error: Failed to create first user.");
        $template->display("error.tpl");
        die();
    }

    // insert default roles
    $rolesobj = new roles();

    $adminrid = $rolesobj->add("Admin",                                         // name
        array("add" => 1, "edit" => 1, "del" => 1 , "close" => 1, "view" => 1), // projects
        array("add" => 1, "edit" => 1, "del" => 1, "close" => 1, "view" => 1),  // tasks
        array("add" => 1, "edit" => 1, "del" => 1, "close" => 1, "view" => 1),  // milestones
        array("add" => 1, "edit" => 1, "del" => 1, "close" => 1, "view" => 1),  // messages
        array("add" => 1, "edit" => 1, "del" => 1, "view" => 1),                // files
        array("add" => 1, "edit" => 1, "del" => 1, "read" => 1, "view" => 1),   // time tracking
        array("add" => 1),                                                      // chat
        array("add" => 1)                                                       // administration
    );

    $userrid = $rolesobj->add("User",                                            // name
        array("add" => 1, "edit" => 1, "del" => 0, "close" => 0, "view" => 1),   // projects
        array("add" => 1, "edit" => 1, "del" => 0, "close" => 1, "view" => 1),   // tasks
        array("add" => 1, "edit" => 1, "del" => 1, "close" => 1, "view" => 1),   // milestones
        array("add" => 1, "edit" => 1, "del" => 1, "close" => 1, "view" => 1),   // messages
        array("add" => 1, "edit" => 1, "del" => 1, "view" => 1),                 // files
        array("add" => 1, "edit" => 1, "del" => 1, "read" => 0, "view" => 1),    // time tracking
        array("add" => 1),                                                       // chat
        array("add" => 0)                                                        // administration
    );

    $clientrid = $rolesobj->add("Client",                                        // name
        array("add" => 0, "edit" => 0, "del" => 0, "close" => 0, "view" => 0),   // projects
        array("add" => 0, "edit" => 0, "del" => 0, "close" => 0, "view" => 1),   // tasks
        array("add" => 0, "edit" => 0, "del" => 0, "close" => 0, "view" => 0),   // milestones
        array("add" => 0, "edit" => 0, "del" => 0, "close" => 0, "view" => 0),   // messages
        array("add" => 0, "edit" => 0, "del" => 0, "view" => 1),                 // files
        array("add" => 0, "edit" => 0, "del" => 0, "read" => 0, "view" => 0),    // time tracking
        array("add" => 0),                                                       // chat
        array("add" => 0)                                                        // administration
    );

    if (!$adminrid or !$userrid or !$clientrid) {
        $template->assign("errortext", "Error: Failed to create initial roles.");
        $template->display("error.tpl");
        die();
    }
    $rolesobj->assign($adminrid, $usrid);

    $template->display("install3.tpl");
}

?>