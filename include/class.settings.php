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
class settings
{
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
        $sel = $conn->query("SELECT * FROM settings LIMIT 1");
        $settings = array();
        $settings = $sel->fetch();

        if (!empty($settings))
        {
            return $settings;
        }
        else
        {
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
				
        $updStmt = $conn->prepare("UPDATE settings SET name = ?, subtitle = ?, `locale` = ?, `timezone` = ?, `dateformat` = ?, `template` = ?, rssuser = ?, rsspass = ?");
				$upd = $updStmt->execute(array($name, $subtitle, $locale, $timezone, $dateformat, $templ, $rssuser, $rsspass));

				if ($upd)
        {
            return true;
        }
        else
        {
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

		if($upd)
		{
			return true;
		}
		else
		{
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

        while (false !== ($file = readdir($handle)))
        {
            $type = filetype(CL_ROOT . "/templates/" . $file);

			if ($type == "dir" and $file != "." and $file != "..")
            {
                $template = $file;
                array_push($templates, $template);
            }
        }

        if (!empty($templates))
        {
            return $templates;
        }
        else
        {
            return false;
        }
    }
}
