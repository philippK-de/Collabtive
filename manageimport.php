<?php
require("init.php");

$action = getArrayVal($_GET, "action");

$mainclasses = array("desktop" => "desktop",
    "profil" => "profil",
    "admin" => "admin_active"
    );
$template->assign("mainclasses", $mainclasses);

//check if the user is admin
if (!$userpermissions["admin"]["add"])
{
    $errtxt = $langfile["nopermission"];
    $noperm = $langfile["accessdenied"];
    $template->assign("errortext", "$errtxt<br>$noperm");
    $template->display("error.tpl");
}

//basecamp import
if ($action == "basecamp")
{
    // create new file object
    $myfile = new datei();
    // create new importer object
    $importer = new importer();
    // upload the file
    $up = $myfile->upload("importfile", "files/" . CL_CONFIG . "/ics", 0);
    if ($up)
    {
        $importer->importBasecampXmlFile(CL_ROOT . "/files/" . CL_CONFIG .  "/ics/$up");
	}
    //delete the imported file
    unlink(CL_ROOT . "/files/" . CL_CONFIG . "/ics/$up");
	$loc = $url . "admin.php?action=system&mode=imported&msg=$importer->msgCount&peop=$importer->peopleCount&pro=$importer->projectCount&tsk=$importer->taskCount";
	header("Location: $loc");
}

?>