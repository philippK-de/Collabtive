<?php
// Files we need
require_once 'include/Sabre/autoload.php';
require("init.php");
// class MyDirectory extends Sabre_DAV_Collection {
class MyDirectory extends Sabre_DAV_FS_Directory implements Sabre_DAV_ICollection, Sabre_DAV_IQuota {
    private $myPath;
    private $proObj;
    private $user;
    function __construct($myPath)
    {
        $this->myPath = $myPath;
        $this->proObj = new project();
        $this->fileObj = new datei();
    }
    public function createFile($name, $data = null)
    {
        $newPath = $this->myPath . '/' . $name;
        file_put_contents($newPath, $data);
        $this->fileObj->add_file(basename($newPath), "", 1, 0, "", $newPath, "", "");
    }
    /**
     * Creates a new subdirectory
     *
     * @param string $name
     * @return void
     */
    public function createDirectory($name)
    {
        $newPath = $this->myPath . '/' . $name;
        mkdir($newPath);
    }
    /**
     * Deletes all files in this directory, and then itself
     *
     * @return void
     */
    public function delete()
    {
        foreach($this->getChildren() as $child) $child->delete();
        rmdir($this->path);
    }
    function getChildren()
    {
        $children = array();
        // Loop through the directory, and create objects for each node
        foreach(scandir($this->myPath) as $node) {
            // Ignoring files staring with .
            if ($node[0] === '.') continue;

            $children[] = $this->getChild($node);
        }

        return $children;
    }

    function getChild($name)
    {
        if (strstr($name, "-")) {
            $name = explode("-", $name);
            $name = $name[1];
        }
        $path = $this->myPath . '/' . $name;
        // We have to throw a NotFound exception if the file didn't exist
        if (!file_exists($path)) die('The file with name: ' . $name . ' could not be found');
        // Some added security
        if ($name[0] == '.') throw new Sabre_DAV_Exception_NotFound('Access denied');

        if (is_dir($path)) {
            return new MyDirectory($path);
        } else {
            return new MyFile($path);
        }
    }

    function childExists($name)
    {
        if (strstr($name, "-")) {
            $name = explode("-", $name);
            $name = $name[1];
        }
        return file_exists($this->myPath . '/' . $name);
    }

    function getName()
    {
        $tmpname = (int) basename($this->myPath);
        if ($tmpname > 0) {
            $user = $_SESSION["userid"];

            if (chkproject($user, $tmpname)) {
                $name = $this->proObj->getProject($tmpname);
                $name = $name["name"];
                if ($name and $tmpname) {
                    return $name . "-" . $tmpname;
                }
            }
        } else {
            return basename($this->myPath);
        }
    }

    /**
     * Returns available diskspace information
     *
     * @return array
     */
    public function getQuotaInfo()
    {
        return array(
            disk_total_space($this->myPath) - disk_free_space($this->myPath),
            disk_free_space($this->myPath)
            );
    }
}
class MyFile extends Sabre_DAV_FS_File implements Sabre_DAV_IFile {
    private $myPath;

    function __construct($myPath)
    {
        $this->myPath = $myPath;
    }
    public function put($data)
    {
        file_put_contents($this->$myPath, $data);
    }
    function getName()
    {
        return basename($this->myPath);
    }

    function get()
    {
        return fopen($this->myPath, 'r');
    }

    function getSize()
    {
        return filesize($this->myPath);
    }

    function getETag()
    {
        return '"' . md5_file($this->myPath) . '"';
    }
    /**
     * Returns the mime-type for a file
     *
     * If null is returned, we'll assume application/octet-stream
     *
     * @return mixed
     */
    public function getContentType()
    {
        return null;
    }
    public function delete()
    {
        unlink($this->$myPath);
    }
}

$auth = new Sabre_HTTP_BasicAuth();

$result = $auth->getUserPass();
$aUser = $result[0];
$aPass = $result[1];

$userObj = new user();

$profile = $userObj->getProfile($userObj->getId($aUser));
if (!$profile) {
    $auth->requireLogin();

    echo "Username doesn't exist!\n";
    die();
}

if ($profile["pass"] != sha1(trim($aPass))) {
    $auth->requireLogin();

    echo "Wrong password!\n";
    die();
}
$userObj->login($aUser, $aPass);
/*
if (!$result || $result[0]!=$u || $result[1]!=$p) {

    $auth->requireLogin();
    echo "Authentication required\n";
    die();

}
*/
// Now we're creating a whole bunch of objects
// Change public to something else, if you are using a different directory for your files
// $rootDirectory = new Sabre_DAV_FS_Directory('files/standard');
$rootDirectory = new MyDirectory('files/standard');
// The server object is responsible for making sense out of the WebDAV protocol
// Now we create an ObjectTree, which dispatches all requests to your newly created file system
$objectTree = new Sabre_DAV_ObjectTree($rootDirectory);
// The object tree needs in turn to be passed to the server class
$server = new Sabre_DAV_Server($rootDirectory);
// If your server is not on your webroot, make sure the following line has the correct information
// $server->setBaseUri('/~evert/mydavfolder'); // if its in some kind of home directory
$server->setBaseUri('/test/dav.php/'); // if you can't use mod_rewrite, use server.php as a base uri
// $server->setBaseUri('/'); // ideally, SabreDAV lives on a root directory with mod_rewrite sending every request to server.php
// The lock manager is reponsible for making sure users don't overwrite each others changes. Change 'data' to a different
// directory, if you're storing your data somewhere else.
$lockBackend = new Sabre_DAV_Locks_Backend_File('files/davdata/');
$lockPlugin = new Sabre_DAV_Locks_Plugin($lockBackend);
// $server->addPlugin($lockPlugin);
$plugin = new Sabre_DAV_Browser_Plugin();
$server->addPlugin($plugin);
// All we need to do now, is to fire up the server
$server->exec();

?>