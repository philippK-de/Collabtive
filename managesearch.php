<?php
require("init.php");

$action = getArrayVal($_GET, "action");
$project = getArrayVal($_GET, "project");
$query = getArrayVal($_GET, "query");
$query = urldecode($query);
$strlim = 19;
$such = (object) new search();

if ($action == "search")
{
    $result = $such->dosearch($query);

    if (!empty($result))
    {
        $finresult = $such->limitResult($result, $userid);
        $num = count($finresult);

        $template->assign("result", $finresult);
    }
    else
    {
        $num = 0;
    }
    $template->assign("title", $langfile["results"]);
    $template->assign("num", $num);
    $template->display("search.tpl");
} elseif ($action == "projectsearch")
{
    $result = $such->dosearch($query, $project);

    $num = count($result);
    $template->assign("num", $num);
    $template->assign("title", $langfile["results"]);
    $template->assign("result", $result);
    $template->display("search.tpl");
} elseif ($action == "ajaxsearch")
{
    $query = getArrayVal($_POST, "query");

    $result = $such->dosearch($query);
    if (!empty($result))
    {
        $finresult = $such->limitResult($result, $userid);
    }
    if (!empty($finresult))
    {
        echo "<ul>";
        foreach($finresult as $res)
        {
            if (!empty($res))
            {
                if ($res["type"] == "file")
                {
                    if (strlen($res["name"]) > $strlim)
                    {
                        $res["name"] = substr($res["name"], 0, $strlim);
                    }
                    echo "<li><img src = \"templates/$settings[template]/images/files/$res[ftype].png\">$res[name]</li>";
                } elseif ($res["type"] != "task" and $res["type"] != "message")
                {
                    if (strlen($res["name"]) > $strlim)
                    {
                        $res["name"] = substr($res["name"], 0, $strlim);
                    }
                    // style = \"list-style-image: url(templates/standard/img/symbols/$res[icon]);\"
                    echo "<li><img src = \"templates/$settings[template]/images/symbols/$res[icon]\">$res[name]</li>";
                }
                else
                {
                    if (strlen($res["title"]) > $strlim)
                    {
                        $res["title"] = substr($res["title"], 0, $strlim);
                    }
                    // style = \"list-style-image: url(templates/standard/img/symbols/$res[icon]);\"
                    echo "<li><img src = \"templates/$settings[template]/images/symbols/$res[icon]\" >$res[title]</li>";
                }
            }
        }
    }
    else
    {
        echo "<li></li>";
    }
    echo "</ul>";
    // echo "<ul><li>$query</li></ul>";
} elseif ($action == "ajaxsearch-p")
{
    $query = getArrayVal($_POST, "query");

    $result = $such->dosearch($query, $project);
    if (!empty($result))
    {
        echo "<ul>";
        foreach($result as $res)
        {
            if (!empty($res))
            {
                if ($res["type"] == "file")
                {
                    if (strlen($res["name"]) > $strlim)
                    {
                        $res["name"] = substr($res["name"], 0, $strlim);
                    }
                    echo "<li><img src = \"templates/$settings[template]/images/files/$res[ftype].png\">$res[name]</li>";
                } elseif ($res["type"] != "task" and $res["type"] != "message")
                {
                    if (strlen($res["name"]) > $strlim)
                    {
                        $res["name"] = substr($res["name"], 0, $strlim);
                    }
                    // style = \"list-style-image: url(templates/standard/img/symbols/$res[icon]);\"
                    echo "<li><img src = \"templates/$settings[template]/images/symbols/$res[icon]\">$res[name]</li>";
                }
                else
                {
                    if (strlen($res["title"]) > $strlim)
                    {
                        $res["title"] = substr($res["title"], 0, $strlim);
                    }
                    // style = \"list-style-image: url(templates/standard/img/symbols/$res[icon]);\"
                    echo "<li><img src = \"templates/$settings[template]/images/symbols/$res[icon]\" >$res[title]</li>";
                }
            }
        }
    }
    else
    {
        echo "<li></li>";
    }
    echo "</ul>";
} elseif ($action == "searchjson")
{
    $result = $such->dosearch($query);
    if (!empty($result))
    {
        $finresult = $such->limitResult($result, $userid);
    }

    if (!empty($finresult))
    {
        $json = "[\"$query\",[";
        foreach($finresult as $res)
        {
            if (isset($res["name"]))
            {
                $json .= "\"$res[name]\",";
            } elseif (isset($res["title"]))
            {
                $json .= "\"$res[title]\",";
            }
        }
        $json = substr($json, 0, strlen($json)-1);
        $json .= "]]";
        echo $json;
    }
} elseif ($action == "searchjson-project")
{
    $result = $such->dosearch($query, $project);

    if (!empty($result))
    {
        $json = "[\"$query\",[";
        foreach($result as $res)
        {
            if (isset($res["name"]))
            {
                $json .= "\"$res[name]\",";
            } elseif (isset($res["title"]))
            {
                $json .= "\"$res[title]\",";
            }
        }
        $json = substr($json, 0, strlen($json)-1);
        $json .= "]]";
        echo $json;
    }
}

?>