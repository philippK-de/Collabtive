<?php
/*
 * The class 'settings' provides methods to deal with the global system settings
 *
 * @author Open Dynamics <info@o-dyn.de>
 * @name settings
 * @package Collabtive
 * @version 0.7.5
 * @link http://www.o-dyn.de
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License v3 or later
 */
class settings {
    public $mylog;

    /*
     * Constructor
     */
    function __construct()
    {
    }

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
    function editSettings($name, $subtitle, $locale, $timezone, $dateformat, $templ, $rssuser, $rsspass)
    {
		global $conn;

		//This is an artifact of refactoring to a key/value table for the settings
        $theSettings = array("name" => $name, "subtitle" => $subtitle, "locale" => $locale, "timezone" => $timezone, "dateformat" => $dateformat, "template" => $templ, "rssuser" => $rssuser, "rsspass" => $rsspass);

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
		$updStmt = $conn->prepare("UPDATE settings SET mailnotify = ? , mailfrom = ?, mailfromname = ?, mailmethod = ?, mailhost = ?, mailuser = ?, mailpass = ?");
		$upd = $updStmt->execute(array((int) $onoff, $mailfrom, $mailfromname, $method, $mailhost, $mailuser, $mailpass));

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
}
