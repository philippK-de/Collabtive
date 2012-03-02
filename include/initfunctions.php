<?php
// Autoload requires classes on new class()
function __autoload($class_name)
{
    $pfad = CL_ROOT . "/include/class." . $class_name . ".php";
    if (file_exists($pfad))
    {
        require_once($pfad);
    }
    else
    {

        die("<b>Fatal Error. Class $class_name could not be located.</b>");
    }
}

function chkproject($user, $project)
{
	$user = (int) $user;
	$project = (int) $project;
    $sel = @mysql_query("SELECT ID FROM projekte_assigned WHERE projekt = $project AND user = $user");
    $chk = @mysql_fetch_row($sel);
    $chk = $chk[0];

    if ($chk != "")
    {
        return true;
    }
    else
    {
        return false;
    }
}

function getAvailableLanguages()
{
    $dir = scandir("./language/");
    $languages = array();

    foreach($dir as $folder)
    {
        if ($folder != "." and $folder != "..")
        {
            array_push($languages, $folder);
        }
    }
    if (!empty($languages))
    {
        return $languages;
    }
    else
    {
        return false;
    }
}

function countLanguageStrings($locale)
{
    $langfile = file("./language/$locale/lng.conf");
    $cou1 = (int) 0;
    $cou2 = (int) 0;

    foreach($langfile as $lang)
    {
        if (strstr($lang, "="))
        {
            $cou1 = $cou1 + 1;
            $slang = explode("=", $lang);
            if (trim($slang[1]) != "")
            {
                $cou2 = $cou2 + 1;
            }
        }
    }

    if ($cou1 > 0 and $cou2 > 0)
    {
        $proz = $cou2 / $cou1 * 100;
        return floor($proz);
    }
    else
    {
        return 0;
    }
}

function readLangfile($locale)
{
    $langfile = file("./language/$locale/lng.conf");
    $langkeys = array();
    $langvalues = array();
    foreach($langfile as $lang)
    {
        if (strstr($lang, "="))
        {
            $slang = explode("=", $lang);
            array_push($langkeys, trim($slang[0]));
            array_push($langvalues, trim($slang[1]));
        }
    }
    $langfile = array_combine($langkeys, $langvalues);
    if (!empty($langfile))
    {
        return $langfile;
    }
    else
    {
        return false;
    }
}

function detectSSL()
{
    if (getArrayVal($_SERVER, "https") == "on")
    {
        return true;
    } elseif (getArrayVal($_SERVER, "https") == 1)
    {
        return true;
    } elseif (getArrayVal($_SERVER, "SERVER_PORT") == 443)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function getMyUrl()
{
    if (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])){
        $requri = $_SERVER['REQUEST_URI'];
    }
    else {
        // assume IIS
        $requri = $_SERVER['SCRIPT_NAME'];
        if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
            $requri .= '?' . $_SERVER['QUERY_STRING'];
        }
    }
    $host = $_SERVER['HTTP_HOST'];
    $pos1 = strrpos($requri, "/");
    $requri = substr($requri, 0, $pos1 + 1);
    if (detectSSL())
    {
        $host = "https://" . $host;
    }
    else
    {
        $host = "http://" . $host;
    }
    $url = $host . $requri;

    return $url;
}
function strip_only_tags($str, $tags, $stripContent=false) {
    $content = '';
    if(!is_array($tags)) {
        $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
        if(end($tags) == '') array_pop($tags);
    }
    foreach($tags as $tag) {
        if ($stripContent)
             $content = '(.+</'.$tag.'(>|\s[^>]*>)|)';
         $str = preg_replace('#</?'.$tag.'(>|\s[^>]*>)'.$content.'#is', '', $str);
    }
    return $str;
}
function getArrayVal(array $array, $name)
{
    if (array_key_exists($name, $array))
    {
        return strip_only_tags($array[$name], "script");
    }
    else
    {
        return false;
    }
}
function delete_directory($dirname)
{
    if (is_dir($dirname))
    {
        $dir_handle = opendir($dirname);
    }
    if (!$dir_handle)
    {
        return false;
    }
    while ($file = readdir($dir_handle))
    {
        if ($file != "." && $file != "..")
        {
            if (!is_dir($dirname . "/" . $file))
            {
                unlink($dirname . "/" . $file);
            }
            else
            {
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
    for($i = 0;$i < $num;$i++)
    {
        if (!empty($arr[$i]))
        {
            $earr = array_merge($earr, $arr[$i]);
        }
    }
    return $earr;
}

?>