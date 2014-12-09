<?php
/**
 * This class provides methods for searching content
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name search
 * @version 0.4.6
 * @package Collabtive
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class search {
	function __construct()
	{
	}

	function dosearch($query, $project = 0)
	{
		if (empty($query)) {
			return false;
		}
		if ($project == 0) {
			$projects = $this->searchProjects($query);
			$milestones = $this->searchMilestones($query);
			if ($_SESSION["userpermissions"]["admin"]["add"]) {
				$messages = $this->searchMessage($query);
			} else {
				$messages = array();
			}
			$tasks = $this->searchTasks($query);
			$files = $this->searchFiles($query);
			$user = $this->searchUser($query);

			$result = array_merge($projects, $milestones, $tasks, $messages , $files, $user);
		} else {
			$milestones = $this->searchMilestones($query, $project);
			if ($_SESSION["userpermissions"]["admin"]["add"]) {
				$messages = $this->searchMessage($query, $project);
			} else {
				$messages = array();
			}
			$tasks = $this->searchTasks($query, $project);
			$files = $this->searchFiles($query, $project);
			$user = $this->searchUser($query, $project);

			$result = array_merge($milestones, $tasks, $messages , $files, $user);
		}

		if (!empty($result)) {
			return $result;
		} else {
			return false;
		}
	}

	function searchProjects($query)
	{
		global $conn;

		$selStmt = $conn->prepare("SELECT `ID`,`name`,`desc`,`status` FROM projekte WHERE `name` LIKE ? OR `desc` LIKE ? OR ID = ? AND status=1");
		$selStmt->execute(array("%{$query}%", "%{$query}%", $query));

		$projects = array();
		while ($result = $selStmt->fetch()) {
			if (!empty($result)) {
				$result["type"] = "project";
				$result["icon"] = "projects.png";
				//$result["name"] = stripslashes($result["name"]);
				//$result["desc"] = stripslashes($result["desc"]);
				$result["url"] = "manageproject.php?action=showproject&amp;id=$result[ID]";
				array_push($projects, $result);
			}
		}

		if (!empty($projects)) {
			return $projects;
		} else {
			return array();
		}
	}

	function searchMilestones($query, $project = 0)
	{
		global $conn;
		$project = (int) $project;

		if ($project > 0) {
			$sel = $conn->prepare("SELECT `ID`,`name`,`desc`,`status`,`project` FROM milestones WHERE `name` LIKE ? OR `desc` LIKE ? AND project = ? AND status=1");
			$sel->execute(array("%{$query}%", "%{$query}%", $project));
		} else {
			$sel = $conn->prepare("SELECT `ID`,`name`,`desc`,`status`,`project` FROM milestones WHERE `name` LIKE ? OR `desc` LIKE ? AND status=1");
			$sel->execute(array("%{$query}%", "%{$query}%"));
		}

		$milestones = array();
		while ($result = $sel->fetch()) {
			if (!empty($result)) {
				$project = $conn->query("SELECT name FROM projekte WHERE ID = $result[project]")->fetch();
				$project = $project[0];

				$result["pname"] = $project;
				$result["type"] = "milestone";
				$result["icon"] = "miles.png";
				$result["name"] = stripslashes($result["name"]);
				$result["desc"] = stripslashes($result["desc"]);
				$result["url"] = "managemilestone.php?action=showmilestone&amp;msid=$result[ID]&id=$result[project]";
				array_push($milestones, $result);
			}
		}

		if (!empty($milestones)) {
			return $milestones;
		} else {
			return array();
		}
	}

	function searchMessage($query, $project = 0)
	{
		global $conn;
		$project = (int) $project;

		if ($project > 0) {
			$sel = $conn->prepare("SELECT `ID`,`title`,`text`,`posted`,`user`,`username`,`project` FROM messages WHERE `title` LIKE ? OR `text` LIKE ? AND project = ? ");
			$sel->execute(array("%{$query}%", "%{$query}%", $project));
		} else {
			$sel = $conn->prepare("SELECT `ID`,`title`,`text`,`posted`,`user`,`username`,`project` FROM messages WHERE `title` LIKE ? OR `text` LIKE ?");
			$sel->execute(array("%{$query}%", "%{$query}%"));
		}

		$messages = array();
		while ($result = $sel->fetch()) {
			if (!empty($result)) {
				$project = $conn->query("SELECT name FROM projekte WHERE ID = $result[project]")->fetch();
				$project = $project[0];

				$result["pname"] = $project;
				$result["type"] = "message";
				$result["icon"] = "msgs.png";
				$result["title"] = stripslashes($result["title"]);
				$result["text"] = stripslashes($result["text"]);
				$result["username"] = stripslashes($result["username"]);
				$posted = date("d.m.y - H:i", $result["posted"]);
				$result["endstring"] = $posted;
				$result["url"] = "managemessage.php?action=showmessage&amp;mid=$result[ID]&id=$result[project]";
				array_push($messages, $result);
			}
		}

		if (!empty($messages)) {
			return $messages;
		} else {
			return array();
		}
	}

	function searchTasks($query, $project = 0)
	{
		global $conn;
		$project = (int) $project;

		if ($project > 0) {
			$sel = $conn->prepare("SELECT `ID`,`title`,`text`,`status`,`project` FROM tasks WHERE `title` LIKE ? OR `text` LIKE ? AND project = ? AND status=1");
			$sel->execute(array("%{$query}%", "%{$query}%", $project));
		} else {
			$sel = $conn->prepare("SELECT `ID`,`title`,`text`,`status`,`project` FROM tasks WHERE `title` LIKE ? OR `text` LIKE ? AND status=1");
			$sel->execute(array("%{$query}%", "%{$query}%"));
		}

		$tasks = array();
		while ($result = $sel->fetch()) {
			if (!empty($result)) {
				$project = $conn->query("SELECT name FROM projekte WHERE ID = $result[project]")->fetch();
				$project = $project[0];

				$result["pname"] = $project;
				$result["type"] = "task";
				$result["icon"] = "task.png";
				$result["title"] = stripslashes($result["title"]);
				$result["text"] = stripslashes($result["text"]);
				$result["url"] = "managetask.php?action=showtask&amp;tid=$result[ID]&id=$result[project]";
				array_push($tasks, $result);
			}
		}

		if (!empty($tasks)) {
			return $tasks;
		} else {
			return array();
		}
	}

	function searchFiles($query, $project = 0)
	{
		global $conn;
		$project = (int) $project;

		if ($project > 0) {
			$sel = $conn->prepare("SELECT `ID`,`name`,`desc`,`type`,`datei`,`title`,`project` FROM `files` WHERE `name` LIKE ? OR `desc` LIKE ? OR `title` LIKE ? AND project = ?");
			$sel->execute(array("%{$query}%", "%{$query}%", "%{$query}%", $project));
		} else {
			$sel = $conn->prepare("SELECT `ID`,`name`,`desc`,`type`,`datei`,`title`,`project` FROM `files` WHERE `name` LIKE ? OR `desc` LIKE ? OR `title` LIKE ?");
			$sel->execute(array("%{$query}%", "%{$query}%", "%{$query}%"));
		}

		$files = array();
		while ($result = $sel->fetch()) {
			if (!empty($result)) {
				$project = $conn->query("SELECT name FROM projekte WHERE ID = $result[project]")->fetch();
				$project = $project[0];

				$result["pname"] = $project;
				$result["ftype"] = str_replace("/", "-", $result["type"]);
				$set = new settings();
				$settings = $set->getSettings();
				$myfile = CL_ROOT . "/templates/" . $settings["template"] . "/images/symbols/files/" . $result["ftype"] . ".png";
				if (stristr($result["type"], "image")) {
					$result["imgfile"] = 1;
				} elseif (stristr($result['type'], "text")) {
					$result["imgfile"] = 2;
				} else {
					$result["imgfile"] = 0;
				}

				if (!file_exists($myfile)) {
					$result["ftype"] = "none";
				}
				$result["title"] = stripslashes($result["title"]);
				$result["desc"] = stripslashes($result["desc"]);
				// $result["tags"] = stripslashes($result["tags"]);
				$result["type"] = "file";
				$result[3] = "file";
				$result["icon"] = "files.png";
				array_push($files, $result);
			}
		}

		if (!empty($files)) {
			return $files;
		} else {
			return array();
		}
	}

	function searchUser($query)
	{
		global $conn;

		$sel = $conn->query("SELECT `ID`,`email`,`name`,`avatar`,`lastlogin`, `gender` FROM user WHERE name LIKE " . $conn->quote("%{$query}%"));

		$user = array();
		while ($result = $sel->fetch()) {
			if (!empty($result)) {
				$result["type"] = "user";
				$result["name"] = stripslashes($result["name"]);
				$result["url"] = "manageuser.php?action=profile&amp;id=$result[ID]";
				$result["type"] = "user";
				$result[3] = "user";
				$result["icon"] = ($result['gender'] == "m" ? "user-marker-male.png" : "user-marker-female.png");
				array_push($user, $result);
			}
		}

		if (!empty($user)) {
			return $user;
		} else {
			return array();
		}
	}

	//Limit search results to objects which the user belongs to
	function limitResult(array $result, $userid)
	{
		$finresult = array();
		$userid = (int) $userid;
		foreach($result as $res) {
			if ($res["type"] != "project" and $res["type"] != "user") {
				if (chkproject($userid, $res["project"])) {
					array_push($finresult, $res);
				}
			} else {
				if (chkproject($userid, $res["ID"])) {
					array_push($finresult, $res);
				}
			}
		}
		return $finresult;
	}
}

?>