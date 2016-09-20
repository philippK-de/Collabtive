<?php
if ($installer_include != "yes") {
    die("this file can only be included");
}

$conn = NULL;

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
} catch (PDOException $e) {
    $template->assign("errortext", "Database connection could not be established. <br>
                                Please check if database exists and check if login credentials are correct. <br>\"" . ($e->getMessage()) . "\"");

    $template->display("error.tpl");
    die();
}

// Create MySQL Tables
$createQueries = [];

// Create queries
$companyTable = "CREATE TABLE IF NOT EXISTS `company` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(64) NOT NULL,
  `mobile` varchar(64) NOT NULL,
  `url` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip` varchar(16) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`ID`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $companyTable);

$customersAssignedTable = "CREATE TABLE IF NOT EXISTS `customers_assigned` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `customer` int(10) NOT NULL,
  `project` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $customersAssignedTable);

$filesTable = "CREATE TABLE IF NOT EXISTS `files` (
  `ID` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `desc` varchar(255) NOT NULL default '',
  `project` int(10) NOT NULL default '0',
  `milestone` int(10) NOT NULL default '0',
  `user` int(10) NOT NULL default '0',
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
  KEY `project` (`project`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $filesTable);

$logTable = "CREATE TABLE IF NOT EXISTS `log` (
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
  KEY `action` (`action`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $logTable);

$messagesTable = "CREATE TABLE IF NOT EXISTS `messages` (
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
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $messagesTable);

$messagesAssignedTable = "CREATE TABLE IF NOT EXISTS `messages_assigned` (
      `ID` int(10) NOT NULL auto_increment,
      `user` int(10) NOT NULL,
      `message` int(10) NOT NULL,
      PRIMARY KEY (`ID`),
      KEY `user` (`user`)
    ) DEFAULT CHARSET=utf8;";
array_push($createQueries, $messagesAssignedTable);

$milestonesTable = "CREATE TABLE IF NOT EXISTS `milestones` (
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
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $milestonesTable);

$milestonesAssignedTable = "CREATE TABLE IF NOT EXISTS `milestones_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `milestone` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `user` (`user`),
  KEY `milestone` (`milestone`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $milestonesAssignedTable);

$projekteTable = "CREATE TABLE IF NOT EXISTS `projekte` (
  `ID` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `start` varchar(255) NOT NULL default '',
  `end` varchar(255) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  `budget` float NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `status` (`status`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $projekteTable);

$projekteAssignedTable = "CREATE TABLE IF NOT EXISTS `projekte_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `projekt` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `user` (`user`),
  KEY `projekt` (`projekt`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $projekteAssignedTable);

$settingsTable = "CREATE TABLE IF NOT EXISTS `settings` (
  `ID` int(10) NOT NULL auto_increment,
  `settingsKey` varchar(50) NOT NULL,
  `settingsValue` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $settingsTable);

$tasklistTable = "CREATE TABLE IF NOT EXISTS `tasklist` (
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
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $tasklistTable);

$tasksTable = "CREATE TABLE IF NOT EXISTS `tasks` (
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
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $tasksTable);

$tasksAssignedTable = "CREATE TABLE IF NOT EXISTS `tasks_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `task` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `user` (`user`),
  KEY `task` (`task`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $tasksAssignedTable);

$userTable = "
CREATE TABLE IF NOT EXISTS `user` (
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
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $userTable);

$filesAttachedTable = "CREATE TABLE IF NOT EXISTS `files_attached` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `file` int(10) unsigned NOT NULL default '0',
  `message` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `file` (`file`,`message`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $filesAttachedTable);

$timetrackerTable = "CREATE TABLE IF NOT EXISTS `timetracker` (
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
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $timetrackerTable);

$projectfoldersTable = "CREATE TABLE IF NOT EXISTS `projectfolders` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `parent` int(10) unsigned NOT NULL default '0',
  `project` int(11) NOT NULL default '0',
  `name` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `project` (`project`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $projectfoldersTable);

$rolesTable = "
CREATE TABLE IF NOT EXISTS `roles` (
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
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $rolesTable);

$rolesAssignedTable = "
CREATE TABLE IF NOT EXISTS `roles_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL,
  `role` int(10) NOT NULL,
  PRIMARY KEY  (`ID`)
)  DEFAULT CHARSET=utf8";
array_push($createQueries, $rolesAssignedTable);

// Perform queries on database
foreach ($createQueries as $createQuery) {
    if (!$conn->query($createQuery)) {
        $template->assign("errortext", "Error: Tables could not be created.");
        $template->display("error.tpl");
        die();
    }
}
?>
