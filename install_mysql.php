<?php
		if($installer_include != "yes")
		{
		die("this file can only be included");
		}
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        if (!($conn)) {
            $template->assign("errortext", "Database connection could not be established. <br>Please check if database exists and check if login credentials are correct.");
            $template->display("error.tpl");
            die();
        }
        // Create MySQL Tables
        $table1 = $conn->query("CREATE TABLE IF NOT EXISTS `company` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $table2 = $conn->query("CREATE TABLE `company_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `company` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `company` (`company`),
  KEY `user` (`user`)
) ENGINE=MyISAM");

        $table3 = $conn->query("CREATE TABLE `files` (
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

        $table4 = $conn->query("CREATE TABLE `log` (
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

        $table5 = $conn->query("CREATE TABLE `messages` (
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

        $table6 = $conn->query("CREATE TABLE `milestones` (
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

        $table7 = $conn->query("CREATE TABLE `milestones_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `milestone` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `user` (`user`),
  KEY `milestone` (`milestone`)
) ENGINE=MyISAM");

        $table8 = $conn->query("CREATE TABLE `projekte` (
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

        $table9 = $conn->query("CREATE TABLE `projekte_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `projekt` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `user` (`user`),
  KEY `projekt` (`projekt`)
) ENGINE=MyISAM");

        $table10 = $conn->query("CREATE TABLE `settings` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `settingsKey` varchar(50) NOT NULL,
  `settingsValue` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  ");

        $table11 = $conn->query("CREATE TABLE `tasklist` (
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

        $table12 = $conn->query("CREATE TABLE `tasks` (
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

        $table13 = $conn->query("CREATE TABLE `tasks_assigned` (
  `ID` int(10) NOT NULL auto_increment,
  `user` int(10) NOT NULL default '0',
  `task` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `user` (`user`),
  KEY `task` (`task`)
) ENGINE=MyISAM");

        $table14 = $conn->query("
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

        $table15 = $conn->query("CREATE TABLE `chat` (
  `ID` int(10) NOT NULL auto_increment,
  `time` varchar(255) NOT NULL default '',
  `ufrom` varchar(255) NOT NULL default '',
  `ufrom_id` int(10) NOT NULL default '0',
  `userto` varchar(255) NOT NULL default '',
  `userto_id` int(10) NOT NULL default '0',
  `text` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM");

        $table16 = $conn->query("CREATE TABLE `files_attached` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `file` int(10) unsigned NOT NULL default '0',
  `message` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `file` (`file`,`message`)
) ENGINE=MyISAM");

        $table17 = $conn->query("CREATE TABLE `timetracker` (
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

        $table18 = $conn->query("CREATE TABLE `projectfolders` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `parent` int(10) unsigned NOT NULL default '0',
  `project` int(11) NOT NULL default '0',
  `name` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `project` (`project`)
) ENGINE=MyISAM");

        $table19 = $conn->query("
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

        $table20 = $conn->query("
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

?>