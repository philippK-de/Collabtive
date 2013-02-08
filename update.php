<?php
error_reporting(0);
require("./init.php");
// 0.7
mysql_query("ALTER TABLE `files` CHANGE `type` `type` VARCHAR( 255 )");
// change to new winter template if unmaintained frost template is selected
if ($settings["template"] == "frost") {
    mysql_query("UPDATE `settings` SET `template` = 'winter'");
}
// 1.0
mysql_query("DROP TABLE `settings`");
$table = mysql_query("CREATE TABLE `settings` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `settingsKey` varchar(50) NOT NULL,
  `settingsValue` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  ");

foreach($settings as $setKey => $setVal) {
    $ins = mysql_query("INSERT INTO `settings` (`settingsKey`,`settingsValue`) VALUES ('$setKey','$setVal')");
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