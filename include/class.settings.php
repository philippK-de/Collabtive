<?php
/*
 * The class 'settings' provides methods to deal with the global system settings
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name settings
 * @package Collabtive
 * @version 0.7.5
 * @link http://collabtive.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class settings {

    /*
     * Returns all global settings
     *
     * @return array $settings Global system settings
     */
    function getSettings()
    {
        global $conn;
        $selStmt = $conn->prepare("SELECT settingsKey,settingsValue FROM settings");
        $sel = $selStmt->execute(array());

        $settings = array();
        while ($selSettings = $selStmt->fetch()) {
            // Create a key/value array
            $settings[$selSettings["settingsKey"]] = $selSettings["settingsValue"];
        }
        if (!empty($settings)) {
            return $settings;
        } else {
            return false;
        }
    }

    /*
     * Edits the global system settings
     *
     * @param string $name System name
     * @param string $subtitle Subtitle is displayed under the system name
     * @param string $locale Standard locale
     * @param string $timezone Standard timezone
     * @param string $templ Template
     * @param string $rssuser Username for RSS Feed access
     * @param string $rsspass Password for RSS Feed access
     * @return bool
     */
    function editSettings($name, $subtitle, $locale, $timezone, $dateformat, $templ, $theme, $rssuser, $rsspass)
    {
        global $conn;
        // This is an artifact of refactoring to a key/value table for the settings
        // Create an arrray containing the settings fields as keys and new values from the user as values
        $theSettings = array("name" => $name, "subtitle" => $subtitle, "locale" => $locale, "timezone" => $timezone, "dateformat" => $dateformat, "template" => $templ, "theme" => $theme, "rssuser" => $rssuser, "rsspass" => $rsspass);
        // Now prepare a statement to edit one settings row
        $updStmt = $conn->prepare("UPDATE settings SET `settingsValue` = ? WHERE `settingsKey` = ?");
        // Loop through the array containing the key/value pairs, writing the database field to $setKey and the value to $setVal
        foreach($theSettings as $setKey => $setVal) {
            // Execute the prepared statement by binding the current settings field and values
            $upd = $updStmt->execute(array($setVal, $setKey));
        }

        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Edits the global mail notification settings
     *
     * @param int $onoff 1 = nofitications on, 0 = notifications off
     * @param string $mailfrom Sender
     * @param string $mailfromname Name of the sender
     * @param string $method Method (e.g. SMTP)
     * @param string $mailhost Host
     * @param string $mailuser User
	 * @param string $mailpass Password
     * @return bool
     */
    function editMailsettings($onoff, $mailfrom, $mailfromname, $method, $mailhost, $mailuser, $mailpass)
    {
        global $conn;
        // This is an artifact of refactoring to a key/value table for the settings
        $theSettings = array("mailnotify" => $onoff, "mailfrom" => $mailfrom, "mailfromname" => $mailfromname, "mailmethod" => $method, "mailhost" => $mailhost, "mailuser" => $mailuser, "mailpass" => $mailpass);
        $updStmt = $conn->prepare("UPDATE settings SET `settingsValue` = ? WHERE `settingsKey` = ?");

        foreach($theSettings as $setKey => $setVal) {
            $upd = $updStmt->execute(array($setVal, $setKey));
        }
        if ($upd) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Returns all available templates
     *
     * @return array $templates
     */
    function getTemplates()
    {
        $handle = opendir(CL_ROOT . "/templates");
        $templates = array();
        // Iterate through the templates directory and count each subdirectory within it as a template
        while (false !== ($file = readdir($handle))) {
            $type = filetype(CL_ROOT . "/templates/" . $file);

            if ($type == "dir" and $file != "." and $file != "..") {
                $template = $file;
                array_push($templates, $template);
            }
        }

        if (!empty($templates)) {
            return $templates;
        } else {
            return false;
        }
    }

    /*
	   * Returns all available themes for a given template
	   *
	   * @param string $template The template whose themes get fetched
	   *
	   * @return array $templates
	*/
    function getThemes($template)
    {
        $handle = opendir(CL_ROOT . "/templates/$template/theme");

        $themes = array();
        if($handle) {
            // Iterate through the templates directory and count each subdirectory within it as a template
            while (false !== ($file = readdir($handle))) {
                $type = filetype(CL_ROOT . "/templates/$template/theme/" . $file);

                if ($type == "dir" and $file != "." and $file != "..") {
                    $theme = $file;
                    array_push($themes, $theme);
                }
            }
        }
        if (!empty($themes)) {
            return $themes;
        } else {
            return false;
        }
    }
}