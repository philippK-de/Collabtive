<?php
require("./init.php");
$tagobj = new tags();
$pro = new project();

$action = getArrayVal($_GET, "action");
$mid = getArrayVal($_GET, "mid");
$tag = getArrayVal($_GET, "tag");
$tag = urldecode($tag);
$id = getArrayVal($_GET, "id");

$mode = getArrayVal($_GET, "mode");
$template->assign("mode", $mode);

$start = getArrayVal($_GET, "start");
$end = getArrayVal($_GET, "end");

if ($action == "gettag")
{
    $content = $tagobj->getTagContent($tag, $id);

    $num = count($content);

    $thetag = strip_tags($tag);

    $template->assign("thetag", $thetag);
    $template->assign("num", $num);
    $template->assign("title", $thetag);
    $template->assign("result", $content);
    $template->display("tag.tpl");
}
