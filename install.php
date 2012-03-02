<?php
error_reporting(0);

// Check if directory templates_c exists and is writable
if (!file_exists("./templates_c") or !is_writable("./templates_c")) {
    die("Required folder templates_c does not exist or is not writable. <br>Please create the folder or make it writable in order to proceed.");
}

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
    // connect database.
    $db = new datenbank();
    $conn = $db->connect($db_name, $db_user, $db_pass, $db_host);
    if (!($conn)) {
        $template->assign("errortext", "Database connection could not be established. <br>Please check if database exists and check if login credentials are correct.");
        $template->display("error.tpl");
        die();
    }
    // Create MySQL Tables
    $table1 = mysql_query("CREATE TABLE `company` (
  `ID` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `phone` varchar(255) NOT NULL default '',
  `address1` varchar(255) NOT NULL default '',
  `address2` varchar(255) NOT NULL default '',
  `state` varchar(255) NOT NULL default '',
  `country` varchar(255) NOT NULL default '',
  `logo` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `name` (`name`)
) ENGINE=MyISAM");

    $table2 = mysql_query("CREATE TABLE `company_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `company` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `company` (`company`),
  KEY `user` (`user`)
) ENGINE=MyISAM");

    $table3 = mysql_query("CREATE TABLE `files` (
  `ID` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `desc` varchar(255) NOT NULL default '',
  `project` int(10) NOT NULL default '0',
  `milestone` int(10) NOT NULL default '0',
  `user` int(10) NOT NULL default '0',
  `tags` varchar(255) NOT NULL default '',
  `added` varchar(255) NOT NULL default '',
  `datei` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `folder` int(10) NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `name` (`name`),
  KEY `datei` (`datei`),
  KEY `added` (`added`),
  KEY `project` (`project`),
  KEY `tags` (`tags`)
) ENGINE=MyISAM");

    $table4 = mysql_query("CREATE TABLE `log` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `username` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `action` int(1) NOT NULL default '0',
  `project` int(10) NOT NULL default '0',
  `datum` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `datum` (`datum`),
  KEY `type` (`type`),
  KEY `action` (`action`),
  FULLTEXT KEY `username` (`username`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM");

    $table5 = mysql_query("CREATE TABLE `messages` (
  `ID` int(10) NOT NULL auto_increment,
  `project` int(10) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `tags` varchar(255) NOT NULL,
  `posted` varchar(255) NOT NULL default '',
  `user` int(10) NOT NULL default '0',
  `username` varchar(255) NOT NULL default '',
  `replyto` int(11) NOT NULL default '0',
  `milestone` int(10) NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `project` (`project`),
  KEY `user` (`user`),
  KEY `replyto` (`replyto`),
  KEY `tags` (`tags`)
) ENGINE=MyISAM");

    $table6 = mysql_query("CREATE TABLE `milestones` (
  `ID` int(10) NOT NULL auto_increment,
  `project` int(10) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `start` varchar(255) NOT NULL default '',
  `end` varchar(255) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `name` (`name`),
  KEY `end` (`end`),
  KEY `project` (`project`)
) ENGINE=MyISAM");

    $table7 = mysql_query("CREATE TABLE `milestones_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `milestone` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `user` (`user`),
  KEY `milestone` (`milestone`)
) ENGINE=MyISAM");

    $table8 = mysql_query("CREATE TABLE `projekte` (
  `ID` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `start` varchar(255) NOT NULL default '',
  `end` varchar(255) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  `budget` float NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `status` (`status`)
) ENGINE=MyISAM");

    $table9 = mysql_query("CREATE TABLE `projekte_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `projekt` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `user` (`user`),
  KEY `projekt` (`projekt`)
) ENGINE=MyISAM");

    $table10 = mysql_query("CREATE TABLE `settings` (
  `ID` tinyint(1)  default '0',
  `name` varchar(255)  default '',
  `subtitle` varchar(255)  default '',
  `locale` varchar(6)  default '',
  `timezone` varchar(60) ,
  `dateformat` varchar(50) ,
  `template` varchar(255)  default '',
  `mailnotify` tinyint(1)  default '1',
  `mailfrom` varchar(255) ,
  `mailfromname` varchar(255) ,
  `mailmethod` varchar(5) ,
  `mailhost` varchar(255) ,
  `mailuser` varchar(255) ,
  `mailpass` varchar(255) ,
  `rssuser` varchar(255) ,
  `rsspass` varchar(255) ,
  KEY `ID` (`ID`),
  KEY `name` (`name`),
  KEY `subtitle` (`subtitle`),
  KEY `locale` (`locale`),
  KEY `template` (`template`)
) ENGINE=MyISAM");

    $table11 = mysql_query("CREATE TABLE `tasklist` (
  `ID` int(10) NOT NULL auto_increment,
  `project` int(10) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `start` varchar(255) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  `access` tinyint(4) NOT NULL default '0',
  `milestone` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `status` (`status`),
  KEY `milestone` (`milestone`)
) ENGINE=MyISAM");

    $table12 = mysql_query("CREATE TABLE `tasks` (
  `ID` int(10) NOT NULL auto_increment,
  `start` varchar(255) NOT NULL default '',
  `end` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `liste` int(10) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `project` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `liste` (`liste`),
  KEY `status` (`status`),
  KEY `end` (`end`)
) ENGINE=MyISAM");

    $table13 = mysql_query("CREATE TABLE `tasks_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `task` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `user` (`user`),
  KEY `task` (`task`)
) ENGINE=MyISAM");

    $table14 = mysql_query("
CREATE TABLE `user` (
  `ID` int(10)  auto_increment,
  `name` varchar(255) default '',
  `email` varchar(255) default '',
  `tel1` varchar(255),
  `tel2` varchar(255) ,
  `pass` varchar(255)  default '',
  `company` varchar(255)  default '',
  `lastlogin` varchar(255)  default '',
  `zip` varchar(10) ,
  `gender` char(1)  default '',
  `url` varchar(255)  default '',
  `adress` varchar(255)  default '',
  `adress2` varchar(255)  default '',
  `state` varchar(255)  default '',
  `country` varchar(255)  default '',
  `tags` varchar(255)  default '',
  `locale` varchar(6)  default '',
  `avatar` varchar(255)  default '',
  `rate` varchar(10) ,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `name` (`name`),
  KEY `pass` (`pass`),
  KEY `locale` (`locale`)
) ENGINE=MyISAM");

    $table15 = mysql_query("CREATE TABLE `chat` (
  `ID` int(10) NOT NULL auto_increment,
  `time` varchar(255) NOT NULL default '',
  `ufrom` varchar(255) NOT NULL default '',
  `ufrom_id` int(10) NOT NULL default '0',
  `userto` varchar(255) NOT NULL default '',
  `userto_id` int(10) NOT NULL default '0',
  `text` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM");

    $table16 = mysql_query("CREATE TABLE `files_attached` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `file` int(10) unsigned NOT NULL default '0',
  `message` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `file` (`file`,`message`)
) ENGINE=MyISAM");

    $table17 = mysql_query("CREATE TABLE `timetracker` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `project` int(10) NOT NULL default '0',
  `task` int(10) NOT NULL default '0',
  `comment` text NOT NULL,
  `started` varchar(255) NOT NULL default '',
  `ended` varchar(255) NOT NULL default '',
  `hours` float NOT NULL default '0',
  `pstatus` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `user` (`user`,`project`,`task`),
  KEY `started` (`started`),
  KEY `ended` (`ended`)
) ENGINE=MyISAM");

    $table18 = mysql_query("CREATE TABLE `projectfolders` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `parent` int(10) unsigned NOT NULL default '0',
  `project` int(11) NOT NULL default '0',
  `name` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `project` (`project`)
) ENGINE=MyISAM");

    $table19 = mysql_query("
CREATE TABLE `roles` (
  `ID` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `projects` text NOT NULL,
  `tasks` text NOT NULL,
  `milestones` text NOT NULL,
  `messages` text NOT NULL,
  `files` text NOT NULL,
  `chat` text NOT NULL,
  `timetracker` text NOT NULL,
  `admin` text NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM");

    $table20 = mysql_query("
CREATE TABLE `roles_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL,
  `role` int(10) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM");
    // Checks if tables could be created
    if (!$table1 or !$table2 or !$table3 or !$table4 or !$table5 or !$table6 or !$table7 or !$table8 or !$table9 or !$table10 or !$table11 or !$table12 or !$table13 or !$table14 or !$table15 or !$table16 or !$table17 or !$table18 or !$table19 or !$table20) {
        $template->assign("errortext", "Error: Tables could not be created.");
        $template->display("error.tpl");
        die();
    }
    // Get the servers default timezone
    $timezone = date_default_timezone_get();
    // insert default settings
    $ins = mysql_query("INSERT INTO settings (name,subtitle,locale,timezone,dateformat,template,mailnotify,mailfrom,mailmethod) VALUES ('Collabtive','Projectmanagement','$locale','$timezone','d.m.Y','standard',1,'collabtive@localhost','mail')");

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
    $db = new datenbank();
    $db->connect($db_name, $db_user, $db_pass, $db_host);
    $user = $_POST['name'];
    $pass = $_POST['pass'];
    // create the first user
    $usr = new user();
    $usrid = $usr->add($user, "", 0, $pass);
    if (!$usrid) {
        $template->assign("errortext", "Error: Failed to create first user.");
        $template->display("error.tpl");
        die();
    }
    // insert default roles
    $rolesobj = new roles();

    $adminrid = $rolesobj->add("Admin", array("add" => 1, "edit" => 1, "del" => 1 , "close" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1), array("add" => 1, "edit" => 1, "del" => 1), array("add" => 1, "edit" => 1, "del" => 1, "read" => 1), array("add" => 1), array("add" => 1));

    $userrid = $rolesobj->add("User", array("add" => 1, "edit" => 1, "del" => 0, "close" => 0), array("add" => 1, "edit" => 1, "del" => 0, "close" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1), array("add" => 1, "edit" => 1, "del" => 1, "close" => 1), array("add" => 1, "edit" => 1, "del" => 1), array("add" => 1, "edit" => 1, "del" => 1, "read" => 0), array("add" => 1), array("add" => 0));

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