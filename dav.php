<?php
// Files we need
require_once 'include/dav/autoload.php';
require("init.php");
// class MyDirectory extends Sabre_DAV_Collection {
use Sabre\DAV;
use Sabre\HTTP\URLUtil;


class MyDirectory implements DAV\ICollection, DAV\IQuota
{

    private $myPath;

    function __construct($myPath)
    {

        $this->myPath = $myPath;
        $this->fileObj = new datei();
        $this->projectObj = new project();

    }


    function getChildren()
    {
        $children = array();
        // Loop through the directory, and create objects for each node
        foreach (scandir($this->myPath) as $node) {
            // Ignoring files staring with .
            if ($node[0] === '.') {
                continue;
            }
            //if its a directory check if the user belongs to the project
            if (is_dir($this->myPath . "/" . $node)) {
                if (chkproject($_SESSION["userid"], $node)) {
                    $children[] = $this->getChild($node);
                }
            } else {
                $children[] = $this->getChild($node);
            }
        }
        return $children;
    }

    function getChild($name)
    {
        $path = $this->myPath . '/' . $name;

        // We have to throw a NotFound exception if the file didn't exist
        if (!file_exists($path)) {
            //split up the display name, to infer the ID which is the real folder name
            $projectId = explode("-", $name);
            $path = $this->myPath . "/" . $projectId[1];
            if (!file_exists($path)) {
                throw new DAV\Exception\NotFound('The file with name: ' . $projectId[1] . ' could not be found');
            }
        }
        if (is_dir($path)) {
            return new MyDirectory($path);
        } else {
            return new MyFile($path);
        }

    }
    /**
     * Checks if a child exists.
     *
     * @param string $name
     * @return bool
     */
    function childExists($name) {
        $path = $this->myPath . '/' . $name;
        // We have to throw a NotFound exception if the file didn't exist
        if (!file_exists($path)) {
            //split up the display name, to infer the ID which is the real folder name
            $projectId = explode("-", $name);
            $path = $this->myPath . "/" . $projectId[1];
            if (!file_exists($path)) {
                return false;
            }
        }
        return true;

    }

    function getName()
    {
        $projectId = basename($this->myPath);
        $folderName = $this->projectObj->getProject($projectId);
        //return $folderName["name"] . basename($this->myPath);
        //echo $this->myPath;
        if ($projectId > 0) {
            return $folderName["name"] . "-" . $projectId;
        } else {
            return $projectId;
        }
    }
    /**
     * Returns available diskspace information
     *
     * @return array
     */
    function getQuotaInfo() {
        $absolute = realpath($this->myPath);
        return [
            disk_total_space($absolute) - disk_free_space($absolute),
            disk_free_space($absolute)
        ];

    }

    /**
     * Deleted the current node
     *
     * @return void
     */
    function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * Renames the node
     *
     * @param string $name The new name
     * @return void
     */
    function setName($name) {

        list($parentPath, ) = URLUtil::splitPath($this->path);
        list(, $newName) = URLUtil::splitPath($name);

        $newPath = $parentPath . '/' . $newName;
        rename($this->myPath, $newPath);

        $this->myPath = $newPath;

    }

    /**
     * Returns the last modification time, as a unix timestamp
     *
     * @return int
     */
    function getLastModified() {

        return filemtime($this->myPath);

    }
    /**
     * Creates a new file in the directory
     *
     * Data will either be supplied as a stream resource, or in certain cases
     * as a string. Keep in mind that you may have to support either.
     *
     * After successful creation of the file, you may choose to return the ETag
     * of the new file here.
     *
     * The returned ETag must be surrounded by double-quotes (The quotes should
     * be part of the actual string).
     *
     * If you cannot accurately determine the ETag, you should not return it.
     * If you don't store the file exactly as-is (you're transforming it
     * somehow) you should also not return an ETag.
     *
     * This means that if a subsequent GET to this new file does not exactly
     * return the same contents of what was submitted here, you are strongly
     * recommended to omit the ETag.
     *
     * @param string $name Name of the file
     * @param resource|string $data Initial payload
     * @return null|string
     */
    function createFile($name, $data = null) {
        $newPath = $this->myPath . '/' . $name;
        if (!file_exists($newPath)) {
            //split up the display name, to infer the ID which is the real folder name
            $pathName = explode("-", $name);
            $newPath = $this->myPath . "/" . $pathName[1];
        }
        file_put_contents($newPath, $data);
        clearstatcache(true, $newPath);

    }
    /**
     * Creates a new subdirectory
     *
     * @param string $name
     * @return void
     */
    function createDirectory($name) {
        $newPath = $this->myPath . '/' . $name;
        if (!file_exists($newPath)) {
            //split up the display name, to infer the ID which is the real folder name
            $pathName = explode("-", $name);
            $newPath = $this->myPath . "/" . $pathName[1];
        }
        $newPath = $this->myPath . '/' . $name;
        mkdir($newPath);
        clearstatcache(true, $newPath);

    }
}

class MyFile implements DAV\IFile
{

    private $myPath;

    function __construct($myPath)
    {
        $this->myPath = $myPath;
        $this->fileObj = new datei();
    }

    function getName()
    {
        global $url;
        $subPos = strpos($url, "dav.php/");
        if($subPos){
            $url = substr($url, 0, $subPos);
        }
        $file = $this->fileObj->getFileByName(basename($this->myPath));
        return basename($this->myPath);
    }

    function get()
    {
        global $url;
        $file = $this->fileObj->getFileByName(basename($this->myPath));
        $subPos = strpos($url, "dav.php/");
        if($subPos){
            $url = substr($url, 0, $subPos);
        }
        return fopen($url . "managefile.php?action=downloadfile&id=$file[project]&file=$file[ID]", 'r');
        // return fopen($this->myPath,'r');
    }

    function getSize()
    {
        return filesize($this->myPath);
    }

    function getETag()
    {
        return '"' . md5_file($this->myPath) . '"';
    }

    function put($data){
        file_put_contents($this->myPath, $data);
        clearstatcache(true, $this->myPath);
    }

    /**
     * Delete the current file
     *
     * @return void
     */
    function delete() {

        unlink($this->myPath);

    }
    /**
     * Returns the mime-type for a file
     *
     * If null is returned, we'll assume application/octet-stream
     *
     * @return mixed
     */
    function getContentType() {

        return null;

    }
    function setName($name) {

        list($parentPath, ) = URLUtil::splitPath($this->myPath);
        list(, $newName) = URLUtil::splitPath($name);

        $newPath = $parentPath . '/' . $newName;
        rename($this->myPath, $newPath);

        $this->path = $newPath;

    }
    /**
     * Returns the last modification time, as a unix timestamp
     *
     * @return int
     */
    function getLastModified() {

        return filemtime($this->myPath);

    }
}

use Sabre\DAV\Auth;

// Creating the backend.
$authBackend = new Sabre\DAV\Auth\Backend\BasicCallBack(function ($userName, $password) {
    $userObj = new user();
    if ($userObj->login($userName, $password)) {
        return true;
    } else {
        return false;
    }

});
// Creating the plugin.
$authPlugin = new Auth\Plugin($authBackend);

$lockBackend = new DAV\Locks\Backend\File('files/standard/ics/');
$lockPlugin = new DAV\Locks\Plugin($lockBackend);

// Make sure there is a directory in your current directory named 'public'. We will be exposing that directory to WebDAV
$publicDir = new MyDirectory('files/standard');
$server = new DAV\Server($publicDir);
$server->setBaseUri('/collabtive-ide/dav.php');
// Adding the plugin to the server.
$server->addPlugin($authPlugin);
$server->addPlugin($lockPlugin);
$server->addPlugin(new DAV\Browser\Plugin());
$server->exec();