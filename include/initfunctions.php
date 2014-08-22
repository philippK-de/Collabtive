<?php
// Autoload requires classes on new class()
function cl_autoload($class_name)
{
    $pfad = CL_ROOT . "/include/class." . $class_name . ".php";
    if (file_exists($pfad)) {
        require_once($pfad);
    } else {
        return false;
    }
}
spl_autoload_register('cl_autoload');

function chkproject($user, $project)
{
    global $conn;
    $user = (int) $user;
    $project = (int) $project;
    $chk = @$conn->query("SELECT ID FROM projekte_assigned WHERE projekt = $project AND user = $user")->fetch();

    $chk = $chk[0];

    if ($chk != "") {
        return true;
    } else {
        return false;
    }
}

function getAvailableLanguages()
{
    $dir = scandir(CL_ROOT . "/language/");
    $languages = array();
    if (!empty($dir)) {
        foreach($dir as $folder) {
            if ($folder != "." and $folder != "..") {
                array_push($languages, $folder);
            }
        }
    }
    if (!empty($languages)) {
        return $languages;
    } else {
        return false;
    }
}

function countLanguageStrings($locale)
{
    if (file_exists(CL_ROOT . "/language/$locale/lng.conf")) {
        $langfile = file("./language/$locale/lng.conf");
        $cou1 = (int) 0;
        $cou2 = (int) 0;
    }

    if (!empty($langfile)) {
        foreach($langfile as $lang) {
            if (strstr($lang, "=")) {
                $cou1 = $cou1 + 1;
                $slang = explode("=", $lang);
                if (trim($slang[1]) != "") {
                    $cou2 = $cou2 + 1;
                }
            }
        }
    }

    if ($cou1 > 0 and $cou2 > 0) {
        $proz = $cou2 / $cou1 * 100;
        return floor($proz);
    } else {
        return 0;
    }
}

function readLangfile($locale)
{
    $langfile = file("./language/$locale/lng.conf");
    $langkeys = array();
    $langvalues = array();
    foreach($langfile as $lang) {
        if (strstr($lang, "=")) {
            $slang = explode("=", $lang);
            array_push($langkeys, trim($slang[0]));
            array_push($langvalues, trim($slang[1]));
        }
    }
    $langfile = array_combine($langkeys, $langvalues);
    if (!empty($langfile)) {
        return $langfile;
    } else {
        return false;
    }
}

function detectSSL()
{
    if (getArrayVal($_SERVER, "https") == "on") {
        return true;
    } elseif (getArrayVal($_SERVER, "https") == 1) {
        return true;
    } elseif (getArrayVal($_SERVER, "HTTPS") == 1) {
        return true;
    } elseif (getArrayVal($_SERVER, "SERVER_PORT") == 443) {
        return true;
    } else {
        return false;
    }
}

function getMyUrl()
{
    if (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
        $requri = $_SERVER['REQUEST_URI'];
    } else {
        // assume IIS
        $requri = $_SERVER['SCRIPT_NAME'];
        if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
            $requri .= '?' . $_SERVER['QUERY_STRING'];
        }
    }
    $host = $_SERVER['HTTP_HOST'];
    $pos1 = strrpos($requri, "/");
    $requri = substr($requri, 0, $pos1 + 1);
    if (detectSSL()) {
        $host = "https://" . $host;
    } else {
        $host = "http://" . $host;
    }
    $url = $host . $requri;

    return $url;
}

function getArrayVal(array $array, $name)
{
    if (array_key_exists($name, $array)) {
    	$config = HTMLPurifier_Config::createDefault();
    	if(file_exists(CL_ROOT . "/files/standard/ics"))
    	{
    		$config->set('Cache.SerializerPath', CL_ROOT . "/files/standard/ics");
    	}
    	else
    	{
    		$config->set('Cache.SerializerPath', NULL);
    	}
		$purifier = new HTMLPurifier($config);
        if (!is_array($array[$name])) {
            $clean = $purifier->purify($array[$name]);
        }else {
            $clean = $array[$name];
        }
        return $clean;
    } else {
        return false;
    }
}

function delete_directory($dirname)
{
    if (is_dir($dirname)) {
        $dir_handle = opendir($dirname);
    }
    if (!$dir_handle) {
        return false;
    } while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file)) {
                unlink($dirname . "/" . $file);
            } else {
                delete_directory($dirname . '/' . $file);
            }
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

function reduceArray(array $arr)
{
    $num = count($arr);
    $earr = array();
    for($i = 0;$i < $num;$i++) {
        if (!empty($arr[$i])) {
            $earr = array_merge($earr, $arr[$i]);
        }
    }
    return $earr;
}

function getUpdateNotify()
{
    return json_decode(@file_get_contents("http://collabtive.o-dyn.de/update/chk.php"));
}

function full_url()
{
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
    return $_SERVER['REQUEST_URI'];
}

?>
