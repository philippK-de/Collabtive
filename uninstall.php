<?php
require("./init.php");
$conn->query("DROP TABLE `chat`");
$conn->query("DROP TABLE `company`");
$conn->query("DROP TABLE `company_assigned`");
$conn->query("DROP TABLE `files`");
$conn->query("DROP TABLE `files_attached`");
$conn->query("DROP TABLE `log`");
$conn->query("DROP TABLE `messages`");
$conn->query("DROP TABLE `milestones`");
$conn->query("DROP TABLE `milestones_assigned`");
$conn->query("DROP TABLE `projectfolders`");
$conn->query("DROP TABLE `projekte`");
$conn->query("DROP TABLE `projekte_assigned`");
$conn->query("DROP TABLE `roles`");
$conn->query("DROP TABLE `roles_assigned`");
$conn->query("DROP TABLE `settings`");
$conn->query("DROP TABLE `tasklist`");
$conn->query("DROP TABLE `tasks`");
$conn->query("DROP TABLE `tasks_assigned`");
$conn->query("DROP TABLE `timetracker`");
$conn->query("DROP TABLE `user`");

?>