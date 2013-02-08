<?php
require("./init.php");
mysql_query("DROP TABLE `chat`");
mysql_query("DROP TABLE `company`");
mysql_query("DROP TABLE `company_assigned`");
mysql_query("DROP TABLE `files`");
mysql_query("DROP TABLE `files_attached`");
mysql_query("DROP TABLE `log`");
mysql_query("DROP TABLE `messages`");
mysql_query("DROP TABLE `milestones`");
mysql_query("DROP TABLE `milestones_assigned`");
mysql_query("DROP TABLE `projectfolders`");
mysql_query("DROP TABLE `projekte`");
mysql_query("DROP TABLE `projekte_assigned`");
mysql_query("DROP TABLE `roles`");
mysql_query("DROP TABLE `roles_assigned`");
mysql_query("DROP TABLE `settings`");
mysql_query("DROP TABLE `tasklist`");
mysql_query("DROP TABLE `tasks`");
mysql_query("DROP TABLE `tasks_assigned`");
mysql_query("DROP TABLE `timetracker`");
mysql_query("DROP TABLE `user`");

?>