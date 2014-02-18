<?php
/**
 * Class datei (file) provides methods to handle files and folders
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name datei
 * @package Collabtive
 * @version 2.0
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
        // Initialize event log
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
            $secure_name=$id.'.'.$this->secure_name($folder);
            // Create the folder
            $makefolder = CL_ROOT . "/files/" . CL_CONFIG . "/$project/$secure_name/";
            if (!file_exists($makefolder)) {
                if (mkdir($makefolder, 0777, true)) {
                    // Folder created
                    $this->mylog->add($folder, 'folder', 1, $project);
                    return true;
                }
            } else {
                // Folder already existed, return false
                return false;
            }
        } else {
            // Folder could not be created, return false
            return false;
        }
    }

    /**
     * Delete a folder
     * Deletes the given folder as well as all of its files and subfolders.
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
        if ($folder === false){
        	return true; // this looks weird. but if the folder, we want to delete is gone (however this happened), the deletion is somehow successfull
        }
        $files = $this->getProjectFiles($project, 10000, $id);
        
        // Delete all files in the folder from database and filesystem
        if (!empty($files)) {
            foreach($files as $file) {
                $this->loeschen($file["ID"]);
            }
        }
        
        // Recursively delete any nested subfolders
        if (!empty($folder["subfolders"])) {
            foreach($folder["subfolders"] as $sub) {
                $this->deleteFolder($sub["ID"], $sub["project"]);
            }
        }
        
				$secure_name=$id.'.'.$this->secure_name($folder['name']);
        
        $del = $conn->query("DELETE FROM projectfolders WHERE ID = $id");
        if ($del) {
            // remove directory
            $foldstr = CL_ROOT . "/files/" . CL_CONFIG . "/$project/$secure_name/";
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
        if ($folder===false){
        	return false; // otherwise you will get all subfolders having parent = "", i.e. all upper level folders of all projects, which is especially bad if you are going to delete those!
        }
        $folder["subfolders"] = $this->getSubFolders($folder["ID"]);
        $folder["abspath"] = $this->getAbsolutePathName($folder);

        return $folder;
    }

    /**
     * Recursively get all subfolders of a folder
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
     * Get all the folders in a project, starting from a given folder
     *
     * @param int $project Project ID
     * @param int $parent Parent folder ID (default: 0 => root folder)
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
     * Get a VIRTUAL absolute path name. This does not reflect the real file storage location, but the location as displayed on the web interface.
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
    
    /**
     * replaces umlauts and any non-alphabetic characters
     * @param String $filename a file name
     * @return String the converted filename
     */
    function secure_name($filename){
    	
    	$map=array(
        'Ä' => 'Ae',
        'ä' => 'ae',
    		'Ö' => 'Oe',
        'ö' => 'oe',
        'Ü' => 'Ue',
        'ü' => 'ue',
        'ß' => 'ss');
    	$filename=strtr($filename, $map);
    	$filename = preg_replace("/\W/", "", $filename);    	// remove whitespace
    	return $filename; 
    }
    
    // FILE METHODS
    /**
     * Upload a file
     * Does filename sanitizing as well as MIME-type determination
     * Also adds the file to the database using add_file()
     *
     * @param string $fname Name of the HTML form field POSTed from
     * @param string $dest_dir Destination directory
     * @param int $project Project ID of the associated project
     * @param int $folder
     * @return bool
     */
    function upload($fname, $dest_dir, $project, $folder = 0)
    {
        // Get data from form
        $name = $_FILES[$fname]['name'];
        $mimetype = $_FILES[$fname]['type'];
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
        
        // If no filename is given, abort
        if (empty($name)) {
            return false;
        }

        $desc = $_POST['desc'];

        $tagobj = new tags();
        $tags = $tagobj->formatInputTags($tags);
        
        $pathinfo = pathinfo($name);
        $extension= $pathinfo['extension'];
        $subname = $this->secure_name($pathinfo['filename']);
        
        // if its a php file, treat it as plaintext so its not executed when opened in the browser.
        if (stristr($extension, "php")) {
            $extension = "txt";
            $mimetype = "text/plain";
        }
        
        // Create a random number
        $randval = mt_rand(1, 99999);
        // if filename is longer than 200 chars, cut it.
        if (strlen($subname) > 200) {
            $subname = substr($subname, 0, 200);
        }
        
        // Assemble the final filename from the original name plus the random value.
        // This is to ensure that files with the same name do not overwrite each other.
        $name = $subname . "_" . $randval . "." . $extension;
        // Relative path, used for display / url construction in the file manager
        $relative_path = $dest_dir . "/" . $name;
        // Absolute file system path used to move the file to its final location
        $path = $root . "/" . $relative_path;

        if (!file_exists($path)) {
            if (move_uploaded_file($tmp_name, $path)) {
                // $filesize = filesize($path);
                if ($project > 0) {
                    // File did not already exist, was uploaded, and a project is set
                    // Now add the file to the database, log the upload event and return the file ID
                    chmod($path, 0755);
                    $fid = $this->add_file($name, $desc, $project, 0, "$tags", $relative_path, "$mimetype", $title, $folder, $visstr);                    
                    if (!empty($title)) {
                        $this->mylog->add($title, 'file', 1, $project);
                    } else {
                        $this->mylog->add($name, 'file', 1, $project);
                    }
                    
                    return $fid;

                } else {
                    // No project means the file is not added to the database willfully. Return file name
                    return $name;
                }
            } else {
                // File was not uploaded / error occured. Return false
                return false;
            }
        } else {
            // File already exists. Return false
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
        
        $pathinfo = pathinfo($name);
        $extension= $pathinfo['extension'];
        $subname = $this->secure_name($pathinfo['filename']);

        // if its a php file, treat it as plaintext so its not executed when opened in the browser.
        if (stristr($extension, "php")) {
            $extension = "txt";
            $mimetype = "text/plain";
        }
        
        $randval = mt_rand(1, 99999);
        // if filename is longer than 200 chars, cut it.
        if (strlen($subname) > 200) {
            $subname = substr($subname, 0, 200);
        }
        $title = $name;        
        $name = $subname . "_" . $randval . "." . $extension;
        $relative_path = $target . "/" . $name;
        $path = $root . "/" . $relative_path;

        if (!file_exists($path)) {
            if (move_uploaded_file($tmp_name, $path)) {
                // $filesize = filesize($path);
                if ($project > 0) {
                    /**
                     * file did not already exist, was uploaded, and a project is set
                     * add the file to the database, add the upload event to the log and return the file ID.
                     */
                    chmod($path, 0755);
                    $fid = $this->add_file($name, " ", $project, 0, "", $relative_path, $mimetype, $title, $folder, $visstr); // empty tags and description
                    $this->mylog->add($name, 'file', 1, $project);
                    return $fid;
                } else {
                    // No project means the file is not added to the database wilfully. Return file name
                    return $name;
                }
            } else {
                // File was not uploaded / error occured. Return false
                return false;
            }
        } else {
            // File already exists. Return false
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
        
        // Get project for logging
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
            
            // Delete attachments of the file (prevents abandoned objects in messages)
            $del2 = $conn->query("DELETE FROM files_attached WHERE file = $datei");

            if ($del) {
                // Only remove the file from file system if deletion from database was successful
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
        
        // Get the file from the database
        $file = $conn->query("SELECT * FROM files WHERE ID=$id")->fetch();

        if (!empty($file)) {
            // Determine if there is a MIME-type icon corresponding to the file's MIME-type. If not, set 'none'
            $file['type'] = str_replace("/", "-", $file["type"]);

            // Get settings (needed to add a different MIME-type icon per theme for each file)
            $set = new settings();
            $settings = $set->getSettings();

            // Construct the path to the MIME-type icon
            $myfile = "./templates/" . $settings["template"] . "/images/files/" . $file['type'] . ".png";
            if (!file_exists($myfile)) {
                $file['type'] = "none";
            }
            
            // Determine if it is an image or text file or some other kind of file (required for lightbox)
            if (stristr($file['type'], "image")) {
                $file['imgfile'] = 1;
            } elseif (stristr($file['type'], "text")) {
                $file['imgfile'] = 2;
            } else {
                $file['imgfile'] = 0;
            }
            
            // Split the tags string into an array, and also count how many tags the file has
            $tagobj = new tags();
            $thetags = $tagobj->splitTagStr($file["tags"]);;
            $file["tagsarr"] = $thetags;
            $file["tagnum"] = count($file["tagsarr"]);
            
            // Strip slashes from title, desc and tags
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
        
        if ($thefolder===false){
        	return false;
        }
        // Build filesystem paths
        $targetstr = "files/" . CL_CONFIG . "/" . $thefile["project"] . "/" . $thefolder["name"] . "/" . $thefile["name"];
        $rootstr = CL_ROOT . "/" . $thefile["datei"];
        
        // Update database
        $upd = $conn->query("UPDATE files SET datei = '$targetstr', folder = '$thefolder[ID]' WHERE ID = $thefile[ID]");
        
        // Move the file physically
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
        
        // If folder is given, return files from this folder, otherwise return files from root folder
        if ($folder > 0) {
            $fold = "files/" . CL_CONFIG . "/$id/$folder/";
            $sel = $conn->query("SELECT COUNT(*) FROM files WHERE project = $id AND folder = $folder ORDER BY ID DESC");
        } else {
            $sel = $conn->query("SELECT COUNT(*) FROM files WHERE project = $id AND folder = 0 ORDER BY ID DESC");
        }
        $num = $sel->fetch();
        $num = $num[0];
        
        // Set items per page
        SmartyPaginate::connect();
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
     * @param string $type MIME type
     * @param string $title Title of the file
     * @param int $ folder Optional parameter (holds ID of subfolder the file is uploaded to [0 = root directory])
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
