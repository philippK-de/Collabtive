<?php
error_reporting(0);

// Check if directory templates_c exists and is writable
if (!file_exists("./templates_c") or !is_writable("./templates_c")) {
    die("Required folder templates_c does not exist or is not writable. <br>Please create the folder or make it writable in order to proceed.");
}
require("./init.php");

// VERSION-DEPENDENT

//3.0
// Create table for assignment of personal messages
$conn->query("
        CREATE TABLE IF NOT EXISTS `messages_assigned`
          (
             `id`      INT(10) NOT NULL auto_increment,
             `user`    INT(10) NOT NULL,
             `message` INT(10) NOT NULL,
             PRIMARY KEY (`id`),
             KEY `user` (`user`)
          )
        DEFAULT charset=utf8;
");


// VERSION-INDEPENDENT

// Clear templates cache
$handle = opendir($template->compile_dir);
while (false !== ($file = readdir($handle))) {
    if ($file != "." and $file != "..") {
        unlink(CL_ROOT . "/" . $template->compile_dir . "/" . $file);
    }
}

// Optimize tables
$conn->query("OPTIMIZE TABLE `company`");
$conn->query("OPTIMIZE TABLE `customers_assigned`");
$conn->query("OPTIMIZE TABLE `files`");
$conn->query("OPTIMIZE TABLE `files_attached`");
$conn->query("OPTIMIZE TABLE `log`");
$conn->query("OPTIMIZE TABLE `messages`");
$conn->query("OPTIMIZE TABLE `messages_assigned`");
$conn->query("OPTIMIZE TABLE `milestones`");
$conn->query("OPTIMIZE TABLE `milestones_assigned`");
$conn->query("OPTIMIZE TABLE `projectfolders`");
$conn->query("OPTIMIZE TABLE `projekte`");
$conn->query("OPTIMIZE TABLE `projekte_assigned`");
$conn->query("OPTIMIZE TABLE `roles`");
$conn->query("OPTIMIZE TABLE `roles_assigned`");
$conn->query("OPTIMIZE TABLE `settings`");
$conn->query("OPTIMIZE TABLE `tasklist`");
$conn->query("OPTIMIZE TABLE `tasks`");
$conn->query("OPTIMIZE TABLE `tasks_assigned`");
$conn->query("OPTIMIZE TABLE `timetracker`");
$conn->query("OPTIMIZE TABLE `user`");

$template->display("update.tpl");

?>