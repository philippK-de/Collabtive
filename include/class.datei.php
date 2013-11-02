<?php
/**
 * class datei (file) provides methods to handle files and folders
 *
 * @author Open Dynamics / Philipp Kiszka <info@o-dyn.de>
 * @name datei
 * @package Collabtive
 * @version 1.0
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class datei {
    /**
     * Constructor
     *
     * @access protected
     */
    function __construct()
    {
        // Initialize event lof
        $this->mylog = new mylog;
    }
    // FOLDER METHODS
    /**
     * Create a new folder
     *
     * @param int $parent ID of the parent folder
     * @param int $project ID of the project the folder belongs to
     * @param string $folder Name of the new folder
     * @param string $desc Description of the new folder
     * @param strin $visible Visibility of the new folder
     * @return bool
     */
    function addFolder($parent, $project, $folder, $desc, $visible = "")
    {
        global $conn;
        $project = (int) $project;
        // insert the folder into the db
        $insStmt = $conn->prepare("INSERT INTO projectfolders (parent, project, name, description, visible) VALUES (?, ?, ?, ?, ?)");
        $ins = $insStmt->execute(array($parent, $project, $folder, $desc, $visible));
        if ($ins) {
            $id = $conn->lastInsertId();
            // create the folder
            $makefolder = CL_ROOT . "/files/" . CL_CONFIG . "/$project/_{$id}_/";
            if (!file_exists($makefolder)) {
                if (mkdir($makefolder, 0777, true)) {
                    // folder created
                    $this->mylog->add($folder, 'folder', 1, $project);
                    return true;
                }
            } else {
                // folder already existed, return false
                return false;
            }
        } else {
            // folder could not be created, return false
            return false;
        }
    }

    /**
     * Delete a folder
     * Deletes the given folder with all files in it and all of its subfolders.
     *
     * @param int $id Folder ID
     * @param int $project Project ID
     * @return bool
     */
    function deleteFolder($id, $project)
    {
        global $conn;
        $id = (int) $id;
        $project = (int) $project;
        $folder = $this->getFolder($id);

        $files = $this->getProjectFiles($project, 10000, $id);
        // delete all the files in the folder from the database (and filesystem as well)
        if (!empty($files)) {
            foreach($files as $file) {
                $this->loeschen($file["ID"]);
            }
        }
        // Recursive call to delete any subfolders nested
        if (!empty($folder["subfolders"])) {
            foreach($folder["subfolders"] as $sub) {
                $this->deleteFolder($sub["ID"], $sub["project"]);
            }
        }
        $del = $conn->query("DELETE FROM projectfolders WHERE ID = $id");
        if ($del) {
            // remove directory
            $foldstr = CL_ROOT . "/files/" . CL_CONFIG . "/$project/_" . $id . "_/";
            if (!file_exists($foldstr)){ // for compatibility with folders created in previous versions of collabtive
              $foldstr = CL_ROOT . "/files/" . CL_CONFIG . "/$project/" . $folder["name"] . "/";
            }
            delete_directory($foldstr);
            $this->mylog->add($folder["name"], 'folder', 3, $project);
            return true;
        }
    }

    /**
     * Get a folder
     *
     * @param int $id Folder ID
     * @return array $folder
     */
    function getFolder($id)
    {
        global $conn;
        $id = (int) $id;
        $folder = $conn->query("SELECT * FROM projectfolders WHERE ID = $id LIMIT 1")->fetch();
        $folder["subfolders"] = $this->getSubFolders($folder["ID"]);
        $folder["abspath"] = $this->getAbsolutePathName($folder);

        return $folder;
    }

    /**
     * Recursively get all subfolders of a given folder
     *
     * @param int $parent ID of the parent folder
     * @return array $folders
     */
    function getSubFolders($parent)
    {
        global $conn;
        $parent = (int) $parent;
        $sel = $conn->query("SELECT * FROM projectfolders WHERE parent = $parent ORDER BY ID ASC");

        $folders = array();

        while ($folder = $sel->fetch()) {
            $folder["subfolders"] = $this->getSubFolders($folder["ID"]);
            $folder["abspath"] = $this->getAbsolutePathName($folder);
            array_push($folders, $folder);
        }

        if (!empty($folders)) {
            return $folders;
        } else {
            return false;
        }
    }

    /**
     * Get all the folders in a project
     *
     * @param int $project Project ID
     * @param int $parent Parent folder ID
     * @return array $folders
     */
    function getProjectFolders($project, $parent = 0)
    {
        global $conn;
        $project = (int) $project;

        $sel = $conn->query("SELECT * FROM projectfolders WHERE project = $project AND parent = $parent ORDER BY ID ASC");
        $folders = array();

        while ($folder = $sel->fetch()) {
            $folder["subfolders"] = $this->getSubFolders($folder["ID"]);
            $folder["abspath"] = $this->getAbsolutePathName($folder);
            array_push($folders, $folder);
        }

        if (!empty($folders)) {
            return $folders;
        } else {
            return false;
        }
    }

    /**
     * Get all the folders in a project
     *
     * @param string $id project Project ID
     * @return array $folders
     */
    function getAllProjectFolders($project)
    {
        global $conn;
        $project = (int) $project;

        $sel = $conn->query("SELECT * FROM projectfolders WHERE project = $project ORDER BY ID ASC");
        $folders = array();

        while ($folder = $sel->fetch()) {
            $folder["subfolders"] = $this->getSubFolders($folder["ID"]);
            $folder["abspath"] = $this->getAbsolutePathName($folder);
            array_push($folders, $folder);
        }

        if (!empty($folders)) {
            return $folders;
        } else {
            return false;
        }
    }

    /**
     * Get an absolute path name
     * Returns the absolute name (relative to the root-directory of the project) of a folder.
     *
     * @param array $folder The folder to be inspected
     * @return string Absolute path/name of the folder
     */
    function getAbsolutePathName($folder)
    {
        global $conn;
        if ($folder['parent'] == 0) {
            return "/" . $folder['name'];
        } else {
            $sel = $conn->query("SELECT * FROM projectfolders WHERE ID = " . $folder['parent']);
            $parent = $sel->fetch();

            return $this->getAbsolutePathName($parent) . "/" . $folder['name'];
        }
    }
    // FILE METHODS
    /**
     * Upload a file
     * Does filename sanitizing as well as MIME-type determination
     * Also adds the file to the database using add_file()
     *
     * @param string $fname Name of the HTML form field POSTed from
     * @param string $ziel Destination directory
     * @param int $project Project ID of the associated project
     * @param int $folder
     * @return bool
     */
    function upload($fname, $ziel, $project, $folder = 0)
    {
        // Get data from form
        $name = $_FILES[$fname]['name'];
        $typ = $_FILES[$fname]['type'];
        $size = $_FILES[$fname]['size'];
        $tmp_name = $_FILES[$fname]['tmp_name'];
        $tstr = $fname . "-title";
        $tastr = $fname . "-tags";

        /* Remove ?!
		$visible = $_POST["visible"];

        if (!empty($visible[0])) {
            $visstr = serialize($visible);
        } else {
            $visstr = "";
        }
		*/
        $title = $_POST[$tstr];
        $tags = $_POST[$tastr];
        $error = $_FILES[$fname]['error'];
        $root = CL_ROOT;
        // if no filename is given, abort
        if (empty($name)) {
            return false;
        }

        $desc = $_POST['desc'];

        $tagobj = new tags();
        $tags = $tagobj->formatInputTags($tags);
        // find the extension
        $teilnamen = explode(".", $name);
        $teile = count($teilnamen);
        $workteile = $teile - 1;
        $erweiterung = $teilnamen[$workteile];
        $subname = "";
        // if its a php file, treat it as plaintext so its not executed when opened in the browser.
        if (stristr($erweiterung, "php")) {
            $erweiterung = "txt";
            $typ = "text/plain";
        }
        // Re assemble the file name from the exploded array, without the extension
        for ($i = 0; $i < $workteile; $i++) {
            $subname .= $teilnamen[$i];
        }
        // Create a random number
        $randval = mt_rand(1, 99999);
        // only allow a-z , 0-9 in filenames, substitute other chars with _
        $subname = str_replace("ä", "ae" , $subname);
        $subname = str_replace("ö", "oe" , $subname);
        $subname = str_replace("ü", "ue" , $subname);
        $subname = str_replace("ß", "ss" , $subname);
        $subname = preg_replace("/[^-_0-9a-zA-Z]/", "_", $subname);
        // remove whitespace
        $subname = preg_replace("/\W/", "", $subname);
        // if filename is longer than 200 chars, cut it.
        if (strlen($subname) > 200) {
            $subname = substr($subname, 0, 200);
        }
        // Assemble the final filename from the original name plus the random value.
        // This is to ensure that files with the same name do not overwrite each other.
        $name = $subname . "_" . $randval . "." . $erweiterung;
        // Absolute file system path used to move the file to its final location
        $datei_final = $root . "/" . $ziel . "/" . $name;
        // Relative path, used for display / url construction in the file manager
        $datei_final2 = $ziel . "/" . $name;

        if (!file_exists($datei_final)) {
            if (move_uploaded_file($tmp_name, $datei_final)) {
                // $filesize = filesize($datei_final);
                if ($project > 0) {
                    // file did not already exist, was uploaded, and a project is set
                    // add the file to the database, add the upload event to the log and return the file ID.
                    chmod($datei_final, 0755);
                    $fid = $this->add_file($name, $desc, $project, 0, "$tags", $datei_final2, "$typ", $title, $folder, $visstr);
                    if (!empty($title)) {
                        $this->mylog->add($title, 'file', 1, $project);
                    } else {
                        $this->mylog->add($name, 'file', 1, $project);
                    }
                    return $fid;
                } else {
                    // no project means the file is not added to the database wilfully. return file name.
                    return $name;
                }
            } else {
                // file was not uploaded / error occured. return false
                return false;
            }
        } else {
            // file already exists. return false
            return false;
        }
    }

    /**
     * Upload a file
     * Does filename sanitizing as well as MIME-type determination
     * Also adds the file to the database using add_file()
     *
     * @param string $name original name of the uploaded file
     * @param string $tmp_name path to temporary file after upload
     * @param string $mimetype determines the file type 
     * @param int $size the size of the file
     * @param $target the folder in which the file shall be stored
     * @param int $project Project ID of the associated project
     * @param $folder the id of the folder
     * @return bool
     */
    function uploadAsync($name, $tmp_name, $mimetype, $size, $target, $project, $folder = 0)
    {
        $visible = "";
        $visstr = "";
        $root = CL_ROOT;

        if (empty($name)) {
            return false;
        }
        
        // find the extension
        $name_parts = explode(".", $name);
        $part_count = count($name_parts);
        $dummy = $part_count - 1;
        $extension = $name_parts[$dummy];
        $subname = "";
        // if its a php file, treat it as plaintext so its not executed when opened in the browser.
        if (stristr($extension, "php")) {
            $extension = "txt";
            $mimetype = "text/plain";
        }

        for ($i = 0; $i < $dummy; $i++) {
            $subname .= $name_parts[$i];
        }

        $randval = mt_rand(1, 99999);
        // only allow a-z , 0-9 in filenames, substitute other chars with _
        $subname = str_replace("ä", "ae" , $subname);
        $subname = str_replace("ö", "oe" , $subname);
        $subname = str_replace("ü", "ue" , $subname);
        $subname = str_replace("ß", "ss" , $subname);
        $subname = preg_replace("/[^-_0-9a-zA-Z]/", "_", $subname);
        // remove whitespace
        $subname = preg_replace("/\W/", "", $subname);
        // if filename is longer than 200 chars, cut it.
        if (strlen($subname) > 200) {
            $subname = substr($subname, 0, 200);
        }

        $name = $subname . "_" . $randval . "." . $extension;
        $path = $root . "/" . $target . "/" . $name;
        $relative_path = $target . "/" . $name;

        if (!file_exists($path)) {
            if (move_uploaded_file($tmp_name, $path)) {
                // $filesize = filesize($path);
                if ($project > 0) {
                    /**
                     * file did not already exist, was uploaded, and a project is set
                     * add the file to the database, add the upload event to the log and return the file ID.
                     */
                    if (!$title) {
                        $title = $name;
                    }
                    chmod($path, 0755);
                    $fid = $this->add_file($name, $desc, $project, 0, $tags, $relative_path, $mimetype, $title, $folder, $visstr);
                    if (!empty($title)) {
                        $this->mylog->add($title, 'file', 1, $project);
                    } else {
                        $this->mylog->add($name, 'file', 1, $project);
                    }
                    return $fid;
                } else {
                    // no project means the file is not added to the database wilfully. return file name.
                    return $name;
                }
            } else {
                // file was not uploaded / error occured. return false
                return false;
            }
        } else {
            // file already exists. return false
            return false;
        }
    }

    /**
     * Edit a file
     *
     * @param int $id File ID
     * @param string $title Title of the file
     * @param string $desc Description text
     * @param string $tags Associated tags (not yet implemented)
     * @return bool
     */
    function edit($id, $title, $desc, $tags)
    {
        global $conn;
        $id = (int) $id;
        // get project for logging
        $proj = $conn->query("SELECT project FROM files WHERE ID = $id")->fetch();
        $project = $proj[0];

        $sql = $conn->prepare("UPDATE files SET `title` = ?, `desc` = ?, `tags` = ? WHERE id = ?");
        $upd = $sql->execute(array($title, $desc, $tags, $id));

        if ($sql) {
            $this->mylog->add($title, 'file' , 2, $project);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a file
     *
     * @param int $datei File ID
     * @return bool
     */
    function loeschen($datei)
    {
        global $conn;
        $datei = (int) $datei;

        $thisfile = $conn->query("SELECT datei,name,project,title FROM files WHERE ID = $datei")->fetch();
        if (!empty($thisfile)) {
            $fname = $thisfile[1];
            $project = $thisfile[2];
            $ftitle = $thisfile[3];
            $thisfile = $thisfile[0];

            $delfile = "./" . $thisfile;

            if (!file_exists($delfile)) {
                return false;
            }
            $del = $conn->query("DELETE FROM files WHERE ID = $datei");
            // Delete attachments of the file also. Prevents abandoned objects in messages.
            $del2 = $conn->query("DELETE FROM files_attached WHERE file = $datei");

            if ($del) {
                // only remove the file from the filesystem if the delete from the database was successful
                if (unlink($delfile)) {
                    if ($ftitle != "") {
                        $this->mylog->add($ftitle, 'file', 3, $project);
                    } else {
                        $this->mylog->add($fname, 'file', 3, $project);
                    }
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Return a file
     *
     * @param string $id File ID
     * @return array $file File details
     */
    function getFile($id)
    {
        global $conn;
        $id = (int) $id;
        // get the file from MySQL
        $file = $conn->query("SELECT * FROM files WHERE ID=$id")->fetch();

        if (!empty($file)) {
            // determine if there is an mimetype icon corresponding to the files mimetype. If not set 'none'
            $file['type'] = str_replace("/", "-", $file["type"]);

            $set = new settings();
            // Get settings. this is needed to add a different mimetype icon per theme to each file.
            $settings = $set->getSettings();
            // construct the path to the mimetype icon
            $myfile = "./templates/" . $settings["template"] . "/images/files/" . $file['type'] . ".png";
            if (!file_exists($myfile)) {
                $file['type'] = "none";
            }
            // determine if its an image or textfile or some other file. this is needed for lightboxes
            if (stristr($file['type'], "image")) {
                $file['imgfile'] = 1;
            } elseif (stristr($file['type'], "text")) {
                $file['imgfile'] = 2;
            } else {
                $file['imgfile'] = 0;
            }
            // split the tags string into an array, and also count how many tags the file has
            $tagobj = new tags();
            $thetags = $tagobj->splitTagStr($file["tags"]);;
            $file["tagsarr"] = $thetags;
            $file["tagnum"] = count($file["tagsarr"]);
            // strip slashes from titles , desc and tags
            $file["title"] = stripslashes($file["title"]);
            $file["desc"] = stripslashes($file["desc"]);
            $file["tags"] = stripslashes($file["tags"]);
            $file["size"] = filesize(realpath($file["datei"])) / 1024;
            $file["size"] = round($file["size"]);
            $file["addedstr"] = date(CL_DATEFORMAT, $file["added"]);
            // Attach data about the user who uploaded the file
            $userobj = new user();
            $file["userdata"] = $userobj->getProfile($file["user"]);

            return $file;
        } else {
            return false;
        }
    }

    /**
     * Move a file to another folder
     *
     * @param int $file File ID
     * @param int $folder Folder ID
     * @return bool
     */
    function moveFile($file, $target)
    {
        global $conn;
        $file = (int) $file;
        $target = (int)$target;
        // Get the file
        $thefile = $this->getFile($file);
        // Get the target folder
        $thefolder = $this->getFolder($target);
        // Build filesystem paths
        $targetstr = "files/" . CL_CONFIG . "/" . $thefile["project"] . "/" . $thefolder["name"] . "/" . $thefile["name"];
        $rootstr = CL_ROOT . "/" . $thefile["datei"];
        // update database
        $upd = $conn->query("UPDATE files SET datei = '$targetstr', folder = '$thefolder[ID]' WHERE ID = $thefile[ID]");
        // move the file physically
        return rename($rootstr, $targetstr);
    }

    /**
     * List all files associated to a given project
     *
     * @param string $id Project ID
     * @param int $lim Limit
     * @param int $folder Folder
     * @return array $files Found files
     */
    function getProjectFiles($id, $lim = 25, $folder = "")
    {
        global $conn;
        $id = (int) $id;
        $lim = (int) $lim;
        $folder = (int) $folder;
        // If folder is given return files from this folder, otherwise return files from the root folder
        if ($folder > 0) {
            $fold = "files/" . CL_CONFIG . "/$id/$folder/";
            $sel = $conn->query("SELECT COUNT(*) FROM files WHERE project = $id AND folder = $folder ORDER BY ID DESC");
        } else {
            $sel = $conn->query("SELECT COUNT(*) FROM files WHERE project = $id AND folder = 0 ORDER BY ID DESC");
        }
        $num = $sel->fetch();
        $num = $num[0];
        SmartyPaginate::connect();
        // set items per page
        SmartyPaginate::setLimit($lim);
        SmartyPaginate::setTotal($num);

        $start = SmartyPaginate::getCurrentIndex();
        $lim = SmartyPaginate::getLimit();

        $files = array();

        if ($folder > 0) {
            $sql = "SELECT ID FROM files WHERE project = $id AND folder = $folder ORDER BY  ID DESC LIMIT $start,$lim";
            $sel2 = $conn->query($sql);
        } else {
            $sel2 = $conn->query("SELECT ID FROM files WHERE project = $id AND folder = 0 ORDER BY  ID DESC LIMIT $start,$lim");
        } while ($file = $sel2->fetch()) {
            if (!empty($file)) {
                array_push($files, $this->getFile($file["ID"]));
            }
        }

        if (!empty($files)) {
            return $files;
        } else {
            return false;
        }
    }

    /**
     * List all files associated to a given project regardless of folder
     *
     * @param string $id Project ID
     * @param int $lim Limit
     * @param int $folder Folder
     * @return array $files Found files
     */
    function getAllProjectFiles($id)
    {
        global $conn;
        $id = (int) $id;

        $files = array();

        $sel2 = $conn->query("SELECT ID FROM files WHERE project = $id  ORDER BY  ID DESC");

        while ($file = $sel2->fetch()) {
            if (!empty($file)) {
                array_push($files, $this->getFile($file["ID"]));
            }
        }

        if (!empty($files)) {
            return $files;
        } else {
            return false;
        }
    }

    /**
     * Seed the random number generator
     *
     * @return float $value Initial value
     */
    private function make_seed()
    {
        list($usec, $sec) = explode(' ', microtime());
        $value = (float) $sec + ((float) $usec * 100000);
        return $value;
    }

    /**
     * Add a file to the database
     *
     * @param string $name Filename
     * @param string $desc Description
     * @param int $project ID of the associated project
     * @param int $milestone ID of the associated milestone
     * @param string $tags Tags for the file (not yet implemented)
     * @param string $datei File path
     * @param string $type MIME Type
     * @param string $title Title of the file
     * @param int $ folder Optional parameter. It holds the ID of the subfolder the file is uploaded to (0 = root directory)
     * @return bool $insid
     */
    function add_file($name, $desc, $project, $milestone, $tags, $datei, $type, $title = " ", $folder = 0, $visstr = "")
    {
        global $conn;
        if (!$desc) {
            $desc = " ";
        }
        $project = (int) $project;
        $milestone = (int) $milestone;
        $folder = (int) $folder;
        $userid = $_SESSION["userid"];
        $now = time();

        $insStmt = $conn->prepare("INSERT INTO files (`name`, `desc`, `project`, `milestone`, `user`, `tags`, `added`, `datei`, `type`, `title`, `folder`, `visible`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $ins = $insStmt->execute(array($name, $desc, $project, $milestone, $userid, $tags, $now, $datei, $type, $title, $folder, $visstr));
        if ($ins) {
            $insid = $conn->lastInsertId();
            return $insid;
        } else {
            return false;
        }
    }
}

?>
