<?php

interface collabtiveBase
{
}

/* Autoload requires classes on new class() */
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

/**
 * Check if a user is assigned to a project
 *
 * @param int $user ID of the user
 * @param int $project ID of the project
 *
 * @return bool
 */
function chkproject($user, $project)
{
    global $conn;
    $user = (int)$user;
    $project = (int)$project;
    $chk = @$conn->query("SELECT ID FROM projekte_assigned WHERE projekt = $project AND user = $user")->fetch();
    $chk = $chk[0];

    if ($chk != "") {
        return true;
    } else {
        return false;
    }
}

/**
 * Read all available languages into an array
 *
 * @param string $locale the name of the locale (en, de, etc)
 *
 * @return array $languages List of available languages
 */
function getAvailableLanguages()
{
    $dir = scandir(CL_ROOT . "/language/");
    $languages = array();
    if (!empty($dir)) {
        foreach ($dir as $folder) {
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

/**
 * Count how complete a specified locale is , compared to the english one
 *
 * @param string $locale the name of the locale (en, de, etc)
 *
 * @return int $proz Percentage of completeness
 */
function countLanguageStrings($locale)
{
    if (file_exists(CL_ROOT . "/language/$locale/lng.conf")) {
        $langfile = file("./language/$locale/lng.conf");
        $cou1 = (int)0;
        $cou2 = (int)0;
    }

    if (!empty($langfile)) {
        foreach ($langfile as $lang) {
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

/**
 * Read the language file for a specified locale to an associative array
 *
 * @param string $locale the name of the locale (en, de, etc)
 *
 * @return array $langfile An associative array with the language file strings
 */
function readLangfile($locale)
{
    // open the file
    $langfile = file(CL_ROOT . "/language/$locale/lng.conf");
    $langkeys = array();
    $langvalues = array();
    // loop through the lines
    foreach ($langfile as $lang) {
        // if a line contains = it is not a comment
        if (strstr($lang, "=")) {
            // make an array of the string
            $slang = explode("=", $lang);
            // write both the key and the value of the string to an array
            array_push($langkeys, trim($slang[0]));
            array_push($langvalues, trim($slang[1]));
        }
    }
    // combine the two arrays, where the string key act as the array keys
    $langfile = array_combine($langkeys, $langvalues);
    if (!empty($langfile)) {
        return $langfile;
    } else {
        return false;
    }
}

/**
 * Detect if Collabtive runs on HTTP or HTTPS
 */
function detectSSL()
{
    if (getArrayVal($_SERVER, "https") == "on") {
        return true;
    } elseif (getArrayVal($_SERVER, "https") == 1) {
        return true;
    } elseif (getArrayVal($_SERVER, "HTTPS") == 'on') {
        return true;
    } elseif (getArrayVal($_SERVER, "HTTPS") == 1) {
        return true;
    } elseif (getArrayVal($_SERVER, "SERVER_PORT") == 443) {
        return true;
    } else {
        return false;
    }
}

/**
 * Get the URL Collabtive is running on
 */
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

/**
 * Get a specific value from an array.
 * Used to fetch user input from POST and GET
 * This sanitizes user input with HTMLPurifier
 *
 * @param array $array The array
 * @param string $name The key we want
 *
 * @return string a sanitized version of the array key
 */
function getArrayVal(array $array, $name)
{
    if (array_key_exists($name, $array)) {
        //use global HTMLPurifier object created in init.php
        global $purifier;
        if (!is_array($array[$name])) {
            $clean = $purifier->purify($array[$name]);
        } else {
            $clean = $array[$name];
        }
        return $clean;
    } else {
        return false;
    }
}

function purify($dirty)
{
    global $purifier;
    return $purifier->purify($dirty);
}

function cleanArray(array $theArray)
{
    $outArray = array();
    foreach ($theArray as $anArrayKey => $anArrayVal) {
        $outArray[$anArrayKey] = getArrayVal($theArray, $anArrayKey);
    }
    return $outArray;
}

function delete_directory($dirname)
{
    if (is_dir($dirname)) {
        $dir_handle = opendir($dirname);
    }
    if (!isset($dir_handle)) {
        return false;
    }
    while ($file = readdir($dir_handle)) {
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

/**
 * Reduce an array by one dimension
 * @param array $arr array to be flattened
 *
 * @return array $earr Flat array
 */
function reduceArray(array $arr)
{
    $num = count($arr);
    $earr = array();
    for ($i = 0; $i < $num; $i++) {
        if (!empty($arr[$i])) {
            $earr = array_merge($earr, $arr[$i]);
        }
    }
    return $earr;
}

/**
 * Check if an update is available
 * return string JSON document describing update info
 */
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

function clearTemplateCache()
{
    $handle = opendir(CL_ROOT . "/templates_c");
    while (false !== ($file = readdir($handle))) {
        if ($file != "." and $file != "..") {
            unlink(CL_ROOT . "/templates_c/" . $file);
        }
    }
}

/*
 * Function to fix deprecated vue syntax
 * Converts id="foo_{{bar}}" to v-bind:id="'foo'+bar"
 */
function filterVueInterpolation($source, Smarty_Internal_Template $localTemplateObj)
{
    $attributeInterpolationMatches = [];
    //find stuff in the form id="something_{{something}}"
    $attributeInterpolationPattern = "(\w+=)\"(\w+){{(.+)}}\"";
    $newAttributeValue = "";
    if (preg_match("/$attributeInterpolationPattern/", $source, $attributeInterpolationMatches)) {
        //convert notation to v-bind:something="'something' + something"
        $newAttributeValue = preg_replace("/$attributeInterpolationPattern/", "v-bind:$1\"'$2'+$3\"", $attributeInterpolationMatches[0]);
        $newAttributeValue = preg_replace("/\*/", "", $newAttributeValue);
    }
    //if a new value was constructed match it against the search pattern
    if ($newAttributeValue) {
        $source = preg_replace("/$attributeInterpolationPattern/", $newAttributeValue, $source);
    }
    return $source;
}
