<?php
require("init.php");
/*
if (!isset($_SESSION["userid"]))
{
    $template->assign("loginerror", 0);
    $mode = getArrayVal($_GET, "mode");
    $template->assign("mode", $mode);
    $template->display("login.tpl");
    die();
}
*/

$milestone = new milestone();

$action = getArrayVal($_GET, "action");
$mid = getArrayVal($_GET, "mid");

$mode = getArrayVal($_GET, "mode");
$template->assign("mode", $mode);

$id = getArrayVal($_GET, "id");
$start = getArrayVal($_GET, "start");
$end = getArrayVal($_GET, "end");
$project = array('ID' => $id);
$template->assign("project", $project);

$pro = new project();
if (!$id)
{
    $id = 0;
}
$template->assign("id", $id);
if ($action == "timeline1next")
{
    $start = $start + 7;
    $end = $end + 7;
    $today = date("d");

    $timestr = $pro->getTimestr();
    $timeline1 = $pro->getTimeline($id, $start, $end);

    $timestring = array();
    foreach($timestr as $times)
    {
        $times = $langfile[$times];
        array_push($timestring, $times);
    }

    $template->assign("timeline1", $timeline1);

    $template->assign("start", $start);
    $template->assign("end", $end);
    $template->assign("timestr", $timestring);
    $template->assign("today", $today);

    $template->display("mileview1.tpl");
} elseif ($action == "timeline1prev")
{
    $start = $start - 7;
    $end = $end - 7;

    $today = date("d");

    $timestr = $pro->getTimestr();
    $timeline1 = $pro->getTimeline($id, $start, $end);

    $timestring = array();
    foreach($timestr as $times)
    {
        $times = $langfile[$times];
        array_push($timestring, $times);
    }

    $template->assign("timeline1", $timeline1);

    $template->assign("start", $start);
    $template->assign("end", $end);
    $template->assign("timestr", $timestring);
    $template->assign("today", $today);

    $template->display("mileview1.tpl");
} elseif ($action == "timeline2next")
{
    $start = $start + 7;
    $end = $end + 7;
    $today = date("d");

    $timestr = $pro->getTimestr();

    $timeline1 = $pro->getTimeline($id, $start, $end);

    $start = $start + 7;
    $end = $end + 7;
    $timeline2 = $pro->getTimeline($id, $start, $end);
    $timestring = array();
    foreach($timestr as $times)
    {
        $times = $langfile[$times];
        array_push($timestring, $times);
    }

    $template->assign("timeline1", $timeline1);
    $template->assign("timeline2", $timeline2);

    $template->assign("start", $start);
    $template->assign("end", $end);
    $template->assign("timestr", $timestring);
    $template->assign("today", $today);

    $template->display("mileview2.tpl");
} elseif ($action == "timeline2prev")
{
    $start = $start - 7;
    $end = $end - 7;
    $today = date("d");

    $timestr = $pro->getTimestr();
    $timeline2 = $pro->getTimeline($id, $start, $end);

    $start = $start - 7;
    $end = $end - 7;

    $timeline1 = $pro->getTimeline($id, $start, $end);

    $timestring = array();
    foreach($timestr as $times)
    {
        $times = $langfile[$times];
        array_push($timestring, $times);
    }

    $template->assign("timeline1", $timeline1);
    $template->assign("timeline2", $timeline2);

    $template->assign("start", $start);
    $template->assign("end", $end);
    $template->assign("timestr", $timestring);
    $template->assign("today", $today);

    $template->display("mileview2.tpl");
} elseif ($action == "makeinputs")
{
    $num = getArrayVal($_GET, "num");
    $file = $langfile["file"] . ":";
    $title = $langfile["title"] . ":";
    $tags = $langfile["tags"] . ":";

    for($i = 1;$i <= $num;$i++)
    {
        echo " <div class=\"row\"><label for = \"title$i\">$title </label><input type = \"text\" name = \"userfile$i-title\" id=\"title$i\" /></div>
        <div class=\"row\"><label for = \"tags$i\">$tags </label><input type = \"text\" name = \"userfile$i-tags\" id=\"tags$i\" /></div>
			<div class=\"row\"><label for = \"userfile$i\">$file </label><input type=\"file\" id = \"userfile$i\" name=\"userfile$i\" /><div style=\"clear:left\"></div>";
    }
} elseif ($action == "addfx-all")
{
    $templ = $url . "managesearch.php?action=search&amp;query={searchTerms}";
    $templ2 = $url . "managesearch.php?action=searchjson&amp;query={searchTerms}";
    $fav = $url . "templates/standard/images/favicon.ico";
    $strsearch = $langfile["search"];
    $sysname = $settings["name"];
    echo "
<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\">
<ShortName>$sysname $strsearch</ShortName>
<Description>Search all Collabtive</Description>
<Tags></Tags>
<Image height=\"16\" width=\"16\" type=\"image/x-icon\">$fav</Image>
<Url type=\"text/html\" method=\"get\"  template=\"$templ\"/>
<Url type=\"application/x-suggestions+json\" method=\"get\"  template=\"$templ2\"/>
<InputEncoding>UTF-8</InputEncoding>
<OutputEncoding>UTF-8</OutputEncoding>

<AdultContent>false</AdultContent>
</OpenSearchDescription>";
} elseif ($action == "addfx-project")
{
    $templ = $url . "managesearch.php?action=projectsearch&amp;project=$project&amp;query={searchTerms}";
    $templ2 = $url . "managesearch.php?action=searchjson-project&amp;project=$project&amp;query={searchTerms}";
    $fav = $url . "templates/standard/images/favicon.ico";
    $project = $_GET['project'];
    $strsearch = $langfile["search"];
    $pro = new project();
    $pname = $pro->getProject($project);
    $pname = $pname["name"];
    echo "
<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\">
<ShortName>$pname $strsearch</ShortName>
<Description>Search project $pname</Description>
<Tags></Tags>
<Image height=\"16\" width=\"16\" type=\"image/x-icon\">$fav</Image>
<Url type=\"text/html\" method=\"GET\"  template=\"$templ\"/>
<Url type=\"application/x-suggestions+json\" method=\"get\"  template=\"$templ2\"/>
<InputEncoding>UTF-8</InputEncoding>
<OutputEncoding>UTF-8</OutputEncoding>

<AdultContent>false</AdultContent>
</OpenSearchDescription>";
} elseif ($action == "jsonfiles")
{
    if (!chkproject($userid, $id))
    {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $myfile = new datei();
    $ordner = $myfile->getProjectFiles($id);
    if (!empty($ordner))
    {
        $json = "var tinyMCEImageList = new Array(\n";
        foreach($ordner as $file)
        {
            if ($file["imgfile"] == 1)
            {
                $json .= "[\"$file[datei]\", \"$file[datei]\"],\n";
            }
        }
        $json = substr($json, 0, strlen($json)-2);
        $json .= ");";
    }
    else
    {
        $json = "";
    }
    echo $json;
} elseif ($action == "fileview")
{

    if (!chkproject($userid, $id))
    {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $POST_MAX_SIZE = ini_get('post_max_size');
    $POST_MAX_SIZE = $POST_MAX_SIZE . "B";
    $folder = getArrayVal($_GET, "folder");

    $myfile = new datei();
    $ordner = $myfile->getProjectFiles($id, 1000000, $folder);
    $finfiles = array();
    if (!empty($ordner))
    {
        foreach($ordner as $file)
        {

            if ($file["visible"])
            {
                $filevis = unserialize($file["visible"]);

                if (is_array($filevis))
                {
                    //array_push($filevis, 18);
                    if (in_array($userpermissions["ID"], $filevis))
                    {
                        array_push($finfiles, $file);
                    }
                }
                else
                {
                    array_push($finfiles, $file);
                }
            }
            else
            {
                array_push($finfiles, $file);
            }
        }
    }
    $filenum = count($finfiles);
    if (empty($finfiles))
    {
        $filenum = 0;
    }

    $filenum = count($finfiles);
    if (empty($finfiles))
    {
        $filenum = 0;
    }

    if ($folder == 0)
    {
        $folders = $myfile->getProjectFolders($id);
		$foldername = "";
		$thefolder = array("parent"=>0);
    }
    else
    {
        $folders = $myfile->getProjectFolders($id, $folder);
        $thefolder = $myfile->getFolder($folder);
        $foldername = $thefolder["abspath"];
    }

    $finfolders = array();
    if (!empty($folders))
    {
        foreach($folders as $folder)
        {
            if ($folder["visible"])
            {
                $foldvis = unserialize($folder["visible"]);

                if (is_array($foldvis))
                {
                    if (in_array($userpermissions["ID"], $foldvis))
                    {
                        array_push($finfolders, $folder);
                    }
                }
                else
                {
                    array_push($finfolders, $folder);
                }
            }
            else
            {
                array_push($finfolders, $folder);
            }
        }

        $template->assign("folders", $finfolders);
    }
    $template->assign("filenum", $filenum);
    $template->assign("foldername", $foldername);
    if(!$thefolder["parent"])
    {
		$thefolder["parent"] = 0;
	}

    $template->assign("folderid", $thefolder["parent"]);
    $template->assign("langfile", $langfile);
    $template->assign("theAction", "fileview");
    SmartyPaginate::assign($template);
    $template->assign("files", $finfiles);
    $template->assign("postmax", $POST_MAX_SIZE);
    $template->display("fileview.tpl");
}
elseif ($action == "fileview_list")
{
    if (!chkproject($userid, $id))
    {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $POST_MAX_SIZE = ini_get('post_max_size');
    $POST_MAX_SIZE = $POST_MAX_SIZE . "B";
    $folder = getArrayVal($_GET, "folder");

    $myfile = new datei();
    $ordner = $myfile->getProjectFiles($id, 1000000, $folder);
    $finfiles = array();
    if (!empty($ordner))
    {
        foreach($ordner as $file)
        {

            if ($file["visible"])
            {
                $filevis = unserialize($file["visible"]);

                if (is_array($filevis))
                {
                    //array_push($filevis, 18);
                    if (in_array($userpermissions["ID"], $filevis))
                    {
                        array_push($finfiles, $file);
                    }
                }
                else
                {
                    array_push($finfiles, $file);
                }
            }
            else
            {
                array_push($finfiles, $file);
            }
        }
    }
    $filenum = count($finfiles);
    if (empty($finfiles))
    {
        $filenum = 0;
    }

    $filenum = count($finfiles);
    if (empty($finfiles))
    {
        $filenum = 0;
    }

    if ($folder == 0)
    {
        $folders = $myfile->getProjectFolders($id);
    }
    else
    {
        $folders = $myfile->getProjectFolders($id, $folder);
        $thefolder = $myfile->getFolder($folder);
        $foldername = $thefolder["abspath"];
    }

    $finfolders = array();
    if (!empty($folders))
    {
        foreach($folders as $folder)
        {
            if ($folder["visible"])
            {
                $foldvis = unserialize($folder["visible"]);

                if (is_array($foldvis))
                {
                    if (in_array($userpermissions["ID"], $foldvis))
                    {
                        array_push($finfolders, $folder);
                    }
                }
                else
                {
                    array_push($finfolders, $folder);
                }
            }
            else
            {
                array_push($finfolders, $folder);
            }
        }

        $template->assign("folders", $finfolders);
    }
    $template->assign("filenum", $filenum);
    $template->assign("foldername", $foldername);
    $template->assign("folderid", $thefolder["parent"]);
    $template->assign("langfile", $langfile);
    $template->assign("theAction", "fileview_list");
    SmartyPaginate::assign($template);
    $template->assign("files", $finfiles);
    $template->assign("postmax", $POST_MAX_SIZE);
    $template->display("fileview_list.tpl");
}

elseif ($action == "folderview")
{
    if (!chkproject($userid, $id))
    {
        $errtxt = $langfile["notyourproject"];
        $noperm = $langfile["accessdenied"];
        $template->assign("errortext", "$errtxt<br>$noperm");
        $template->display("error.tpl");
        die();
    }

    $myfile = new datei();
    $ordner = $myfile->getProjectFolders($id);
    $myproject = new project();

    $template->assign("langfile", $langfile);
    $template->assign("ordner", $ordner);
    $template->display("folderview.tpl");
} elseif ($action == "newcal")
{
    $thisd = date("j");
    $thism = date("n");
    $thisy = date("Y");

    $m = getArrayVal($_GET, "m");
    $y = getArrayVal($_GET, "y");
    if (!$m)
    {
        $m = $thism;
    }
    if (!$y)
    {
        $y = $thisy;
    }

    $nm = $m + 1;
    $pm = $m -1;
    if ($nm > 12)
    {
        $nm = 1;
        $ny = $y + 1;
    }
    else
    {
        $ny = $y;
    }
    if ($pm < 1)
    {
        $pm = 12;
        $py = $y-1;
    }
    else
    {
        $py = $y;
    }

    $today = date("d");

    $calobj = new calendar();
    $cal = $calobj->getCal($m, $y);
    $weeks = $cal->calendar;
    // print_r($weeks);
    $mstring = strtolower(date('F', mktime(0, 0, 0, $m, 1, $y)));
    $mstring = $langfile[$mstring];
    $template->assign("mstring", $mstring);

    $template->assign("m", $m);
    $template->assign("y", $y);
    $template->assign("thism", $thism);
    $template->assign("thisd", $thisd);
    $template->assign("thisy", $thisy);
    $template->assign("nm", $nm);
    $template->assign("pm", $pm);
    $template->assign("ny", $ny);
    $template->assign("py", $py);
    $template->assign("weeks", $weeks);
    $template->display("calbody.tpl");
}

