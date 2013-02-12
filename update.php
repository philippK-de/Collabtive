<?php
error_reporting(0);
require("./init.php");
// 0.7
$conn->query("ALTER TABLE `files` CHANGE `type` `type` VARCHAR( 255 )");
// change to new winter template if unmaintained frost template is selected
if ($settings["template"] == "frost") {
    $conn->query("UPDATE `settings` SET `template` = 'winter'");
}
// 1.0
if ($conn->query("DROP TABLE `settings`")) {
    $table = $conn->query("CREATE TABLE `settings` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `settingsKey` varchar(50) NOT NULL,
  `settingsValue` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  ");
}
foreach($settings as $setKey => $setVal) {
    $ins = $conn->query("INSERT INTO `settings` (`settingsKey`,`settingsValue`) VALUES ('$setKey','$setVal')");
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
$opt1 = $conn->query("OPTIMIZE TABLE `files`");
$opt2 = $conn->query("OPTIMIZE TABLE `files_attached`");
$opt3 = $conn->query("OPTIMIZE TABLE `log`");
$opt4 = $conn->query("OPTIMIZE TABLE `messages`");
$opt5 = $conn->query("OPTIMIZE TABLE `milestones`");
$opt6 = $conn->query("OPTIMIZE TABLE `milestones_assigned`");
$opt7 = $conn->query("OPTIMIZE TABLE `projectfolders`");
$opt8 = $conn->query("OPTIMIZE TABLE `projekte`");
$opt9 = $conn->query("OPTIMIZE TABLE `projekte_assigned`");
$opt10 = $conn->query("OPTIMIZE TABLE `roles`");
$opt11 = $conn->query("OPTIMIZE TABLE `roles_assigned`");
$opt12 = $conn->query("OPTIMIZE TABLE `settings`");
$opt13 = $conn->query("OPTIMIZE TABLE `tasklist`");
$opt14 = $conn->query("OPTIMIZE TABLE `tasks`");
$opt15 = $conn->query("OPTIMIZE TABLE `tasks_assigned`");
$opt16 = $conn->query("OPTIMIZE TABLE `timetracker`");
$opt17 = $conn->query("OPTIMIZE TABLE `user`");

$template->display("update.tpl");

?>