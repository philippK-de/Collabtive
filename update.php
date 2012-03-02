<?php
error_reporting(0);
require("./init.php");
// 0.5.5
mysql_query("ALTER TABLE `roles` ADD `chat` TEXT AFTER `timetracker`");
mysql_query("ALTER TABLE `user` CHANGE `company` `company` VARCHAR( 256 )");
// 0.6
mysql_query("ALTER TABLE `projectfolders` ADD `parent` INT(10) unsigned NOT NULL default '0' AFTER `ID`");
mysql_query("ALTER TABLE `projectfolders` ADD `visible` text NOT NULL AFTER `description`");
mysql_query("ALTER TABLE `files` ADD `visible` text NOT NULL");
mysql_query("ALTER TABLE `files` ADD `user` INT(10) NOT NULL default '0' AFTER `milestone`");
mysql_query("ALTER TABLE `user` CHANGE `zip` `zip` VARCHAR( 10 )"); // overlooked with 0.5

// 0.6.3
mysql_query("ALTER TABLE `files` DROP `seenby`");
// 0.7
mysql_query("ALTER TABLE `files` CHANGE `type` `type` VARCHAR( 255 )");
//change to new winter template if unmaintained frost template is selected
if ($settings["template"] == "frost") {
    mysql_query("UPDATE `settings` SET `template` = 'winter'");
}
// version independent
// clear templates cache
$handle = opendir($template->compile_dir);
while (false !== ($file = readdir($handle))) {
    if ($file != "." and $file != "..") {
        unlink(CL_ROOT . "/" . $template->compile_dir . "/" . $file);
    }
}
// optimize tables
$opt1 = mysql_query("OPTIMIZE TABLE `files`");
$opt2 = mysql_query("OPTIMIZE TABLE `files_attached`");
$opt3 = mysql_query("OPTIMIZE TABLE `log`");
$opt4 = mysql_query("OPTIMIZE TABLE `messages`");
$opt5 = mysql_query("OPTIMIZE TABLE `milestones`");
$opt6 = mysql_query("OPTIMIZE TABLE `milestones_assigned`");
$opt7 = mysql_query("OPTIMIZE TABLE `projectfolders`");
$opt8 = mysql_query("OPTIMIZE TABLE `projekte`");
$opt9 = mysql_query("OPTIMIZE TABLE `projekte_assigned`");
$opt10 = mysql_query("OPTIMIZE TABLE `roles`");
$opt11 = mysql_query("OPTIMIZE TABLE `roles_assigned`");
$opt12 = mysql_query("OPTIMIZE TABLE `settings`");
$opt13 = mysql_query("OPTIMIZE TABLE `tasklist`");
$opt14 = mysql_query("OPTIMIZE TABLE `tasks`");
$opt15 = mysql_query("OPTIMIZE TABLE `tasks_assigned`");
$opt16 = mysql_query("OPTIMIZE TABLE `timetracker`");
$opt17 = mysql_query("OPTIMIZE TABLE `user`");

$template->display("update.tpl");

?>