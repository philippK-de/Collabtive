<?php
/*
This is the BETA version of the Collabtive Web API.
It allows you to export data from Collabtive for use in Web applications.
By default the data is sent in XML format. To switch the output to JSON, you have to append mode=json to your queries.

**************
Authorization
**************
In order to prevent data from being served to unauthorized users, the Collabtive web api implements the same logins as Collabtive itself.
You have to append the parameters "username" and "pass" to every query you issue to the API.
These parameters need to contain username and Password of a valid Collabtive user.

Example:http://www.example.com/api.php?username=your_user&pass=your_pass

This authorizes you against the API, but performs no API action.
More on using the API below.

******
Usage
******
The Collabtive API can be used by issuing a GET request with several parameters.
An example: http://www.example.com/api.php?action=user.list.get&username=your_user&pass=your_pass
Calling this URL gets you a list of all users registered in the system in XML format.

*****************************
The parameters available are:
*****************************
action = specifies the API action you want to call
id = specifies the ID of the object to be fetched (optional)
user = specifies the ID of the user to be fetched (optional)
mode = specifies if the output is rendered in XML or JSON (XML is the default)

Another example:
http://www.example.com/api.php?action=user.tasks.get&user=1&username=your_user&pass=your_pass&mode=json
This gives you all tasks of the user with ID1 in JSON format.

*/
require("./init.php");
die("<span style = \"color:red;\">The API is deactivated. The API does not properly enforce permissions yet. For example every user can request every project / task etc. <br />To activate the API remove the die() statement in api.php line 38.<br /><strong>DO NOT USE THE API ON PRODUCTION SERVERS !</strong></span>");
$userobj = new user();
$usr = $_GET["username"];
$pass = $_GET["pass"];

$pass = urldecode($pass);
$usr = urldecode($usr);
if (!$userobj->login($usr, $pass)) {
    die("not authorized");
}

// variables
$action = getArrayVal($_GET, "action");
$id = getArrayVal($_GET, "id");
$user = getArrayVal($_GET, "user");
// output in xml or json
$mode = getArrayVal($_GET, "mode");
// create new array to xml converter
$xml = new toXml();
// Projects
if ($action == "project.get") {
    $obj = (object) new project();
    $theData = $obj->getProject($id);
    $theRootNode = "project";
} elseif ($action == "myprojects.get") {
    $obj = (object) new project();
    $theData = $obj->getMyProjects($id);

    $theRootNode = "myprojects";
} elseif ($action == "project.members.get") {
    $obj = (object) new project();
    $theData = $obj->getProjectMembers($id);
    $theRootNode = "members";
}
// Users
elseif ($action == "user.profile.get") {
    $obj = (object) new user();
    $theData = $obj->getProfile($id);
    $theRootNode = "user";
} elseif ($action == "user.id.get") {
    $obj = (object) new user();
    $theData = $obj->getId($id);
    $theRootNode = "user";
} elseif ($action == "user.list.get") {
    $obj = (object) new user();
    $theData = $obj->getAllUsers(100000);
    $theRootNode = "userlist";
} elseif ($action == "user.tasks.get") {
    $obj = (object) new task();
    $project = new project();

    $myprojects = $project->getMyProjects($user, 1, 0, 10000);
    $theData = array();

    foreach($myprojects as $proj) {
        $theArr = $obj->getAllMyProjectTasks($proj["ID"], 10000, $user);
        if (!empty($theArr)) {
            foreach($theArr as $task) {
                array_push($theData, array("ID" => $task["ID"], "name" => $task["title"]));
            }
        }
    }

    $theRootNode = "tasks";
}
// convert to XML or JSON
if ($mode == "json") {
    $theXml = $xml->arrToJSON($theData);
} else {
    $theXml = $xml->arrToXml($theData, $theRootNode);
}
// output to the user
echo $theXml;

?>
