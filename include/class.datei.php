<?php

/**
 * Class datei (file) provides methods to handle files and folders
 *
 * @author Philipp Kiszka <info@o-dyn.de>
 * @name datei
 * @package Collabtive
 * @version 2.0
 * @link http://collabtive.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class datei
{
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
    function addFolder($parent, $project, $folder, $desc)
    {
        global $conn, $mylog;

        $project = (int)$project;
        $thepath = $this->getAbsolutePathName($this->getFolder($parent));
        // if its the root path, don't append any slashes
        if ($thepath == "/") {
            $thepath = "";
        }
        // Make a copy of the original folder name before replacing umlauts
        // This is for display in the system log
        $folderOrig = $folder;
        // Replace umlauts
        $folder = str_replace("ä", "ae", $folder);
        $folder = str_replace("ö", "oe", $folder);
        $folder = str_replace("ü", "ue", $folder);
        $folder = str_replace("ß", "ss", $folder);
        // Remove whitespace
        $folder = preg_replace("/\W/", "", $folder);
        $folder = preg_replace("/[^-_0-9a-zA-Z]/", "_", $folder);
        // Insert folder into database
        $insStmt = $conn->prepare("INSERT INTO projectfolders (parent, project, name, description, visible) VALUES (?, ?, ?, ?, ?)");
        $ins = $insStmt->execute(array($parent, $project, $folder, $desc, ""));

        if ($ins) {
            // Construct the path to the new folder
            $makefolder = CL_ROOT . "/files/" . CL_CONFIG . "/$project" . $thepath . "/" . $folder . "/";
            // Create the folder in the filesystem, if it doesnt exist
            if (!file_exists($makefolder)) {
                if (mkdir($makefolder, 0777, true)) {
                    // Folder created
                    $mylog->add($folderOrig, 'folder', 1, $project);
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
        global $conn, $mylog;

        $id = (int)$id;
        $project = (int)$project;
        // retrieve the folder info from the database
        $folder = $this->getFolder($id);
        // get files for this folder
        $files = $this->getProjectFiles($project, 10000, $id);
        // Delete all files in the folder from database and filesystem
        if (!empty($files)) {
            foreach ($files as $file) {
                $this->loeschen($file["ID"]);
            }
        }
        // Recursively delete any nested subfolders
        if (!empty($folder["subfolders"])) {
            foreach ($folder["subfolders"] as $sub) {
                $this->deleteFolder($sub["ID"], $sub["project"]);
            }
        }
        // Delete the folder from the database
        $delStmt = $conn->prepare("DELETE FROM projectfolders WHERE ID = ?");
        $del = $delStmt->execute(array($id));

        if ($del) {
            // Remove directory
            $foldstr = CL_ROOT . "/files/" . CL_CONFIG . "/$project/" . $folder["name"] . "/";
            delete_directory($foldstr);
            $mylog->add($folder["name"], 'folder', 3, $project);

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

        $id = (int)$id;

        $folderStmt = $conn->prepare("SELECT * FROM projectfolders WHERE ID = ? LIMIT 1");
        $folderStmt->execute(array($id));
        $folder = $folderStmt->fetch();

        if (!$folder) {
            return false;
        }
        //recursively get subfolders
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

        $parent = (int)$parent;

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

        $project = (int)$project;

        $sel = $conn->prepare("SELECT * FROM projectfolders WHERE project = ? AND parent = ? ORDER BY ID ASC");
        $sel->execute(array($project, $parent));

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

        $project = (int)$project;

        $sel = $conn->prepare("SELECT * FROM projectfolders WHERE project = ? ORDER BY ID ASC");
        $sel->execute(array($project));

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
     * Get an absolute path name of a folder
     * Returns the absolute name (relative to the root directory of the project) of a folder.
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
     * @return bool
     */
    function uploadAsync($name, $tmp_name, $typ, $size, $ziel, $project, $folder = 0)
    {
        global $mylog;
        $visible = "";
        $visstr = "";
        $root = CL_ROOT;
        if (empty($name)) {
            return false;
        }
        // Find the extension
        $teilnamen = explode(".", $name);
        $teile = count($teilnamen);
        $workteile = $teile - 1;
        $erweiterung = $teilnamen[$workteile];
        $subname = "";
        // If it is a PHP file, treat as plain text so it is not executed when opened in the browser
        if (stristr($erweiterung, "php")) {
            $erweiterung = "txt";
            $typ = "text/plain";
        }

        for ($i = 0; $i < $workteile; $i++) {
            $subname .= $teilnamen[$i];
        }

        $randval = mt_rand(1, 99999);
        // Only allow a-z, 0-9 in filenames, substitute other chars with _
        $subname = str_replace("ä", "ae", $subname);
        $subname = str_replace("Ä", "Ae", $subname);
        $subname = str_replace("ö", "oe", $subname);
        $subname = str_replace("Ö", "Oe", $subname);
        $subname = str_replace("ü", "ue", $subname);
        $subname = str_replace("Ü", "Ue", $subname);
        $subname = str_replace("ß", "ss", $subname);
        $subname = preg_replace("/[^-_0-9a-zA-Z]/", "_", $subname);
        // Remove whitespace
        $subname = preg_replace("/\W/", "", $subname);
        // If filename is longer than 200 chars, cut it
        if (strlen($subname) > 200) {
            $subname = substr($subname, 0, 200);
        }

        $name = $subname . "_" . $randval . "." . $erweiterung;
        $datei_final = $root . "/" . $ziel . "/" . $name;
        $datei_final2 = $ziel . "/" . $name;

        if (!file_exists($datei_final)) {
            if (move_uploaded_file($tmp_name, $datei_final)) {
                if ($project > 0) {
                    // File did not already exist, was uploaded, and a project is set
                    // Now add the file to the database, log the upload event and return the file ID.
                    if (!$title) {
                        $title = $name;
                    }

                    chmod($datei_final, 0755);

                    $fid = $this->add_file($name, $desc, $project, 0, $datei_final2, "$typ", $title, $folder, "");

                    if (!empty($title)) {
                        $mylog->add($title, 'file', 1, $project);
                    } else {
                        $mylog->add($name, 'file', 1, $project);
                    }
                    // encrypt the uploaded file
                    // $this->encryptFile($datei_final);
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
        global $conn, $mylog;

        $id = (int)$id;
        // Get project for logging
        $proj = $conn->query("SELECT project FROM files WHERE ID = $id")->fetch();

        $project = $proj[0];

        $sql = $conn->prepare("UPDATE files SET `title` = ?, `desc` = ?, `tags` = ? WHERE id = ?");
        $upd = $sql->execute(array($title, $desc, $tags, $id));

        if ($sql) {
            $mylog->add($title, 'file', 2, $project);
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
        global $conn, $mylog;
        $datei = (int)$datei;

        $thisfile = $conn->query("SELECT datei, name, project, title FROM files WHERE ID = $datei")->fetch();

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
                        $mylog->add($ftitle, 'file', 3, $project);
                    } else {
                        $mylog->add($fname, 'file', 3, $project);
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

        $id = (int)$id;
        // Get the file from the database
        $fileStmt = $conn->prepare("SELECT * FROM files WHERE ID= ?");
        $fileStmt->execute(array($id));

        $file = $fileStmt->fetch();

        if (!empty($file)) {
            // Determine if there is a MIME-type icon corresponding to the file's MIME-type. If not, set 'none'
            $file['type'] = str_replace("/", "-", $file["type"]);
            // Get settings (needed to add a different MIME-type icon per theme for each file)
            $set = new settings();
            $settings = $set->getSettings();
            // Construct the path to the MIME-type icon
            $myfile = "./templates/" . $settings["template"] . "/theme/" . $settings["theme"] . "/images/files/" . $file['type'] . ".png";
            if (!file_exists($myfile)) {
                $file['type'] = "none";
            }
            // Determine if it is an image or text file or some other kind of file (required for lightbox)
            if (stristr($file['type'], "image")) {
                $file['imgfile'] = true;
            } elseif (stristr($file['type'], "text")) {
                $file['imgfile'] = false;
            } else {
                $file['imgfile'] = false;
            }

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

    /*
     * Find a file by its filename
     */
    function getFileByName($name){
        global $conn;
        $filesStmt = $conn->prepare("SELECT ID FROM files WHERE name = ?");
        $filesStmt->execute(array($name));

        $fileId = $filesStmt->fetch();
        return $this->getFile($fileId["ID"]);
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

        $file = (int)$file;
        $target = (int)$target;
        // Get the file
        $thefile = $this->getFile($file);
        // Get the target folder
        $thefolder = $this->getFolder($target);
        $abspath = $this->getAbsolutePathName($thefolder);
        if ($abspath == "/") {
            $abspath = "";
        }
        // Build file system paths
        // $targetstr = "files/" . CL_CONFIG . "/" . $thefile["project"] . "/" . $thefolder["name"] . "/" . $thefile["name"];
        $targetstr = "files/" . CL_CONFIG . "/" . $thefile["project"] . $abspath . "/" . $thefile["name"];
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
     * @param int $limit Limit
     * @param int $folder Folder
     * @return array $files Found files
     */
    function getProjectFiles($id, $limit = 5000, $folder = "", $offset = 0)
    {
        global $conn;

        $id = (int)$id;
        $limit = (int)$limit;
        $folder = (int)$folder;
        // If folder is given, return files from this folder, otherwise return files from root folder
        if ($folder > 0) {
            $fold = "files/" . CL_CONFIG . "/$id/$folder/";
            $sel = $conn->prepare("SELECT COUNT(*) FROM files WHERE project = ? AND folder = ? ORDER BY ID DESC");
            $sel->execute(array($id, $folder));
        } else {
            $sel = $conn->prepare("SELECT COUNT(*) FROM files WHERE project = ? AND folder = 0 ORDER BY ID DESC");
            $sel->execute(array($id));
        }
        $num = $sel->fetch();
        $num = $num[0];
        // Set items per page
        $files = array();

        if ($folder > 0) {
            $sel2 = $conn->query("SELECT ID FROM files WHERE project = $id AND folder = $folder ORDER BY  ID DESC LIMIT $limit OFFSET $offset");
        } else {
            $sel2 = $conn->query("SELECT ID FROM files WHERE project = $id AND folder = 0 ORDER BY  ID DESC LIMIT $limit OFFSET $offset");
        }
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

    function getJsonProjectFiles($id, $limit = 5000, $folder = 0, $offset = 0)
    {
        global $conn;

        $id = (int)$id;
        $limit = (int)$limit;
        $folder = (int)$folder;
        $files = array();

       $filesStmt = $conn->query("SELECT ID FROM files WHERE project = $id AND folder = $folder ORDER BY ID DESC LIMIT $limit OFFSET $offset");

        while ($file = $filesStmt->fetch()) {
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

    function countJsonProjectFiles($id, $folder = 0)
    {
        global $conn;

        $id = (int)$id;
        $folder = (int)$folder;

        $filesStmt = $conn->query("SELECT COUNT(*) FROM files WHERE project = $id AND folder = $folder");

        $fileCount = $filesStmt->fetch();
        $fileCount = $fileCount["COUNT(*)"];

        if (!empty($fileCount)) {
            return $fileCount;
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

        $id = (int)$id;

        $files = array();

        $sel2 = $conn->prepare("SELECT ID FROM files WHERE project = ? ORDER BY ID DESC");
        $sel2->execute(array($id));

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
        $value = (float)$sec + ((float)$usec * 100000);
        return $value;
    }

    /**
     * Add a file to the database
     *
     * @param string $name File name
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
    function add_file($name, $desc, $project, $milestone, $datei, $type, $title = " ", $folder = 0, $visstr = "")
    {
        global $conn;

        if (!$desc) {
            $desc = " ";
        }

        $project = (int)$project;
        $milestone = (int)$milestone;
        $folder = (int)$folder;
        $userid = $_SESSION["userid"];
        $now = time();
        $insStmt = $conn->prepare("INSERT INTO files (`name`, `desc`, `project`, `milestone`, `user`, `added`, `datei`, `type`, `title`, `folder`, `visible`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $ins = $insStmt->execute(array($name, $desc, $project, $milestone, $userid, $now, $datei, $type, $title, $folder, $visstr));
        if ($ins) {
            $insid = $conn->lastInsertId();
            return $insid;
        } else {
            return false;
        }
    }

    /**
     * Encrypts a file with AES
     *
     * @param string $filename Filename
     * @param string $key Encryption key
     * @return bool file was written
     */
    function encryptFile($filename, $key)
    {
        include_once(CL_ROOT . "/include/phpseclib/Crypt/AES.php");
        $cipher = new Crypt_AES(); // could use CRYPT_AES_MODE_CBC
        $cipher->setPassword($key);

        $plaintext = file_get_contents($filename);
        // echo $cipher->decrypt($cipher->encrypt($plaintext));
        return file_put_contents($filename, $cipher->encrypt($plaintext));
    }

    /**
     * Decrypts a file with AES
     *
     * @param string $filename Filename
     * @param string $key Encryption key
     * @return string cleartext
     */
    function decryptFile($filename, $key)
    {
        include_once(CL_ROOT . "/include/phpseclib/Crypt/AES.php");
        $cipher = new Crypt_AES(); // could use CRYPT_AES_MODE_CBC
        $cipher->setPassword($key);

        $ciphertext = file_get_contents($filename);
        // echo $cipher->decrypt($cipher->encrypt($plaintext));
        return $cipher->decrypt($ciphertext);
    }
}

?>
