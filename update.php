<?php
error_reporting(0);
require("./init.php");

// Version independent
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