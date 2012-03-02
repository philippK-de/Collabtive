<?php
define('CL_ROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR));

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
}
error_reporting(0);
$pic = getArrayVal($_GET,"pic");
$height = getArrayVal($_GET,"height");
$width = getArrayVal($_GET,"width");
include(CL_ROOT . "/include/class.hft_image.php");
$imagehw = GetImageSize($pic);
$imagewidth = $imagehw[0];
$imageheight = $imagehw[1];
$myThumb = new hft_image(CL_ROOT . "/" . $pic);
$myThumb->jpeg_quality = 80;

if (!isset($height))
{
	$ratio =  $imageheight / $imagewidth;
	$height = $width * $ratio;

	$height = round($height);
}

if (!isset($width))
{
	$ratio = $imagewidth / $imageheight;
    $width = $height * $ratio;
}

$myThumb->resize($width, $height, 0);

HEADER("Content-Type: image/jpeg");
$myThumb->output_resized("");

?>