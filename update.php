<?php
error_reporting(0);
require("./init.php");


// VERSION-DEPENDENT
//2.0
function randomPassword() {
	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 16; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}
$filePass = randomPassword();
$path = "./include/phpseclib";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

$conn->query("CREATE TABLE IF NOT EXISTS `customers_assigned` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `customer` int(10) NOT NULL,
  `project` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$oldTemplate = $settings["template"];
$template->assign("theme",$oldTemplate);
queryWithParameters('INSERT INTO `settings` (`ID` ,`settingsKey` ,`settingsValue`) VALUES (NULL , \'theme\', ?);', array($oldTemplate));
$conn->query("UPDATE `settings` SET `template`='standard'");

queryWithParameters('INSERT INTO `settings` (`ID`, `settingsKey`, `settingsValue`) VALUES (NULL, \'filePass\', ?);', array($filePass));

$filesList = $conn->query("SELECT * FROM `files`")->fetchAll();
$fileObj = new datei();

foreach($filesList as $file)
{
	$tmpFile = CL_ROOT . "/" . $file["datei"];
	$fileObj->encryptFile($tmpFile,$filePass);
}
//drop tags field from files
$conn->query("ALTER TABLE `files` DROP `tags`");
// VERSION-INDEPENDENT

// Clear templates cache
$handle = opendir($template->compile_dir);
while (false !== ($file = readdir($handle))) {
    if ($file != "." and $file != "..") {
        unlink(CL_ROOT . "/" . $template->compile_dir . "/" . $file);
    }
}

// Optimize tables
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
