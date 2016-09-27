<?php
require("init.php");
if (!isset($_SESSION['userid'])) {
    $template->assign("loginerror", 0);
    $template->display("login.tpl");
    die();
}
//check if the user is an admin
if (!$userpermissions["admin"]["add"] and $action != "addpro") {
	$errtxt = $langfile["nopermission"];
	$noperm = $langfile["accessdenied"];
	$template->assign("errortext", "$errtxt<br>$noperm");
	$template->display("error.tpl");
	die();
}

$customer = (object) new company();
$action = getArrayVal($_GET,"action" );
$id = getArrayVal($_GET,"id" );

if($action == "editform")
{
	$customer = $customer->getCompany($id);
	$template->assign("customer",$customer);
	$template->display("forms/editcustomer.tpl");
}
elseif($action == "edit")
{
	$customerData = array();
	$customerData["id"] = $id;
	$customerData["company"] = getArrayVal($_POST,"company");
	$customerData["contact"] = getArrayVal($_POST,"contact");
	$customerData["email"] = getArrayVal($_POST,"email");
	$customerData["phone"] = getArrayVal($_POST,"tel1");
	$customerData["mobile"] = getArrayVal($_POST,"tel2");
	$customerData["url"] = getArrayVal($_POST,"web");
	$customerData["address"] = getArrayVal($_POST,"address");
	$customerData["zip"] = getArrayVal($_POST,"zip");
	$customerData["city"] = getArrayVal($_POST,"city");
	$customerData["country"] = getArrayVal($_POST,"country");
	$customerData["state"] = getArrayVal($_POST,"state");
	$customerData["desc"] = getArrayVal($_POST,"desc");
	if($customer->edit($customerData))
	{
		header("Location: admin.php?action=customers&mode=edited");
	}
}
elseif($action == "del")
{
	if($customer->del($id))
	{
		echo "ok";
	}
}