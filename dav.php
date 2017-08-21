<?php
require_once("include/dav/autoload.php");
require("init.php");

use Sabre\DAV;
use Sabre\DAV\Auth;

//root directory to scan for files
$publicDir = new myDavDirectory("files/standard");

//create the server
$server = new DAV\Server($publicDir);
//set base uri to this file
$server->setBaseUri("/" . CL_DIRECTORY . "/dav.php");

/* SERVER PLUGINS */


/*
 * Create the auth backend using the callback method
 */
$authBackend = new Sabre\DAV\Auth\Backend\BasicCallBack(function ($userName, $password) {
    $userObj = new user();
    if ($userObj->login($userName, $password)) {
        return true;
    } else {
        return false;
    }

});

// Creating auth Plugin from the backend
$authPlugin = new Auth\Plugin($authBackend);

$lockBackend = new DAV\Locks\Backend\File("files/standard/ics/davlocks.lock");
$lockPlugin = new DAV\Locks\Plugin($lockBackend);

// Adding the plugin to the server.
$server->addPlugin($authPlugin);
$server->addPlugin($lockPlugin);
$server->addPlugin(new \Sabre\DAV\Browser\GuessContentType());
$server->addPlugin(new DAV\Browser\Plugin());
$server->exec();