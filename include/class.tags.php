<?php

class tags
{
    public $cloudlimit;
    /**
     * Constructor
     *
     * @access protected
     */
    function __construct()
    {
        $this->cloudlimit = 0;
    }

	/**
	* Formats the an input string to be stored as tags.
	* Possible format: word1,word2,word / word1, word2, word3 / word1 word2 word3
	* OR a mix of the preceding
	* 
	* Extracts the words from the string, makes the first character uppercase, and reassembles the tagstring
	*
	* @param string $ tags Tagstring to be formatted
	* @return string worktags Formatted tags
	*/
	public function formatInputTags($tags)
	{
		// Trim string
		$tags = trim($tags);

		// Compress string internal spaces:
		$count = 1;
		while($count) {
    		$tags = str_replace("  ", " ", $tags, $count);
		}
	
		// String liegt jetzt als "txt1, txt2, txt3" / "txt1,txt2,txt3" / "txt1 txt2 txt3" vor,
		// bei entsprechender Usereingabe auch als "txt1 txt2,txt3, txt4"

		$tags = str_replace(" ", "," , $tags);
	
		// String liegt jetzt als "txt1,,txt2,,txt3" /txt1,txt2,txt3" / "txt1,txt2,txt3" vor,
		// bei entsprechender Usereingabe auch als "txt1,txt2,txt3,,txt4

   		$tags = str_replace(",,", "," , $tags);

		// String liegt jetzt als "txt1,txt2,txt3" /txt1,txt2,txt3" / "txt1,txt2,txt3" vor,
		// bei entsprechender Usereingabe auch als "txt1,txt2,txt3,txt4"

        $tags = strtolower($tags);
        
        if (!empty($tags))
        {
            $tags = explode(",", $tags);
            $worktags = "";
            foreach($tags as $tag)
            {
                if ($tag != "" and $tag != ",")
                {
                    $tag = trim($tag);
                    $tag = ucfirst($tag);
                    $worktags .= $tag . ",";
                }
            }
            $worktags = substr($worktags, 0, strlen($worktags)-1);
        }
        else
        {
            $worktags = "";
        }

        if (!empty($worktags))
        {
            return $worktags;
        }
        else
        {
            return false;
        }
    }

    /**
     * Splits a tag string into an array
     *
     * @param tagstr $ Tagstring to be split
     * @return array tags Array with the t ags
     */
    public function splitTagStr($tagstr)
    {
        $tags = explode(",", $tagstr);

        if (!empty($tags))
        {
            return $tags;
        }
        else
        {
            return false;
        }
    }

    /**
     * Gets all the content for a given tag in a given project
     *
     * @param string $ tag The wanted tag
     * @param int $ project The project
     * @return array content The content for the tag
     */
    public function getTagContent($tag, $project)
    {
        $files = $this->getFiles($tag, $project);
        $messages = $this->getMessages($tag, $project);
        $user = $this->getUser($tag);

        $content = array_merge($files, $messages, $user);
        if (!empty($content))
        {
            return $content;
        }
        else
        {
            return false;
        }
    }
    /**
     * Builds a tagcloud
     *
     * @param string $ tag The wanted tag
     * @param int $ project The project
     * @return array content The content for the tag
     */
    public function getTagcloud($project)
    {
        $project = (int) $project;

        $sel1 = mysql_query("SELECT tags FROM files WHERE tags != '' AND project = $project");
        $sel2 = mysql_query("SELECT tags FROM messages WHERE tags != '' AND project = $project");

        $tags1 = array();
        $worktags = "";

        while ($dat = mysql_fetch_row($sel1))
        {
            $tag = $dat[0];
            $tag = ucfirst($tag);
            if ($tag != "" and $tag != ",")
            {
                $worktags .= $tag . ",";
            }
        }
        while ($dat = mysql_fetch_row($sel2))
        {
            $tag = $dat[0];
            $tag = ucfirst($tag);
            if ($tag != "" and $tag != ",")
            {
                $worktags .= $tag . ",";
            }
        }

        $worktags = substr($worktags, 0, strlen($worktags)-1);

        $tags1 = explode(",", $worktags);
        $tagsnum = array_count_values($tags1);

        $tagsnum = array_filter($tagsnum, array($this, "limitcloud"));

        $thecloud = new tagcloud($tagsnum);
        $thecloud->itemsPerRow = 3;
        $thecloud->rows = ceil(count($tagsnum) / 3);
        $thecloud->padding = 3;
        $thecloud->maxFontSize = 16;
        $thecloud->minFontSize = 10;
        $thecloud->linkUrlPrefix = "managetags.php?action=gettag&id=$project&tag=";

        return $thecloud->getCloud();
    }
    // SELECT * FROM `files` WHERE tags REGEXP ',{0,1}Wort1,{0,1}'
    private function limitcloud($arr)
    {
        if ($arr > $this->cloudlimit)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    private function getFiles($query, $project = 0)
    {
        $query = mysql_real_escape_string($query);
        $project = (int) $project;

        if ($project > 0)
        {
            $sel = mysql_query("SELECT `ID`,`name`,`desc`,`type`,`datei`,`title`,`project`,`tags` FROM `files` WHERE `tags` LIKE '%$query%' HAVING project = $project");
        }
        else
        {
            $sel = mysql_query("SELECT `ID`,`name`,`desc`,`type`,`datei`,`title`,`project`,`tags` FROM `files` WHERE `tags` LIKE '%$query%'");
        }

        $files = array();
        while ($result = mysql_fetch_array($sel))
        {
            if (!empty($result))
            {
                $project = mysql_query("SELECT name FROM projekte WHERE ID = $result[project]");
                $project = mysql_fetch_row($project);
                $project = $project[0];

                $result["pname"] = $project;
                $result["ftype"] = str_replace("/", "-", $result["type"]);
                $set = new settings();
                $settings = $set->getSettings();
                $myfile = CL_ROOT . "/templates/" . $settings["template"] . "/images/symbols/files/" . $result["ftype"] . ".png";
                if (stristr($result["type"], "image"))
                {
                    $result["imgfile"] = 1;
                } elseif (stristr($result['type'], "text"))
                {
                    $result["imgfile"] = 2;
                }
                else
                {
                    $result["imgfile"] = 0;
                }

                if (!file_exists($myfile))
                {
                    $result["ftype"] = "none";
                }
                $result["title"] = stripslashes($result["title"]);
                $result["desc"] = stripslashes($result["desc"]);
                $result["tags"] = stripslashes($result["tags"]);
                $thetags = $this->splitTagStr($result["tags"]);
                $result["tagsarr"] = $thetags;
                $result["tagnum"] = count($result["tagsarr"]);
                $result["type"] = "file";
                $result[3] = "file";
                $result["icon"] = "files.png";
                array_push($files, $result);
            }
        }

        if (!empty($files))
        {
            return $files;
        }
        else
        {
            return array();
        }
    }

    private function getMessages($query, $project = 0)
    {
        $query = mysql_real_escape_string($query);
        $project = (int) $project;

        if ($project > 0)
        {
            $sel = mysql_query("SELECT `ID`,`title`,`text`,`posted`,`user`,`username`,`project`,`tags` FROM messages WHERE `tags` LIKE '%$query%'  HAVING project = $project ");
        }
        else
        {
            $sel = mysql_query("SELECT `ID`,`title`,`text`,`posted`,`user`,`username`,`project`,`tags` FROM messages WHERE `tags` LIKE '%$query%'");
        }

        $messages = array();
        while ($result = mysql_fetch_array($sel))
        {
            if (!empty($result))
            {
                $project = mysql_query("SELECT name FROM projekte WHERE ID = $result[project]");
                $project = mysql_fetch_row($project);
                $project = $project[0];

                $result["pname"] = $project;
                $result["type"] = "message";
                $result["icon"] = "msgs.png";

                $result["tagsarr"] = $this->splitTagStr($result["tags"]);
                $result["tagnum"] = count($result["tagsarr"]);

                $result["title"] = stripslashes($result["title"]);
                $result["text"] = stripslashes($result["text"]);
                $result["username"] = stripslashes($result["username"]);
                $posted = date("d.m.y - H:i", $result["posted"]);
                $result["endstring"] = $posted;
                $result["url"] = "managemessage.php?action=showmessage&amp;mid=$result[ID]&id=$result[project]";
                array_push($messages, $result);
            }
        }

        if (!empty($messages))
        {
            return $messages;
        }
        else
        {
            return array();
        }
    }

    private function getUser($query)
    {
        $query = mysql_real_escape_string($query);

        $sel = mysql_query("SELECT `ID`,`email`,`name`,`avatar`,`lastlogin`,`tags` FROM user WHERE tags LIKE '%$query%'");

        $user = array();
        while ($result = mysql_fetch_array($sel))
        {
            if (!empty($result))
            {
                $result["type"] = "user";
                $result["name"] = stripslashes($result["name"]);
                $result["url"] = "manageuser.php?action=profile&amp;id=$result[ID]";
                $result["type"] = "user";
                $result["tagsarr"] = $this->splitTagStr($result["tags"]);
                $result["tagnum"] = count($result["tagsarr"]);
                $result[3] = "user";
                $result["icon"] = "user.png";
                array_push($user, $result);
            }
        }

        if (!empty($user))
        {
            return $user;
        }
        else
        {
            return array();
        }
    }
}
// end class tags
class tagcloud extends tags
{
    public $rows;
    public $itemsPerRow;
    public $minFontSize;
    public $maxFontSize;
    public $padding;
    public $linkUrlPrefix;

    private $keywordsArray;
    private $minTagValue;
    private $maxTagValue;
    private $fontRatio;
    private $fontOffset;

    function __construct ($keys, $rows = 3, $rowitems = 3, $minfont = 10, $maxfont = 20)
    {
        // init default values
        $this->keywordsArray = $keys;

        $this->Rows = $rows;
        $this->itemsPerRow = $rowitems;
        $this->minFontSize = $minfont;
        $this->maxFontSize = $maxfont;
        if (!empty($this->keywordsArray))
        {
            $this->minTagValue = min($this->keywordsArray);
            $this->maxTagValue = max($this->keywordsArray);
        }
    }

    function getCloud()
    {
        if (isset($this->maxTagValue) and isset($this->minTagValue) and $this->minTagValue > 0 and $this)
        {
            $fsize = $this->maxFontSize - $this->minFontSize;
            $tval = $this->maxTagValue - $this->minTagValue;
            if ($fsize > 0 and $tval > 0)
            {
                $this->fontRatio = $fsize / $tval;
            }
            else
            {
			$this->fontRatio = 1;
			}
            $this->fontOffset = $this->maxFontSize - ($this->fontRatio * $this->maxTagValue);
        }
        $htmlCode = "";
        $AbsoluteIndex = 0;

        reset($this->keywordsArray);

        for ($NumofRows = 1;$NumofRows <= $this->rows;$NumofRows++)
        {
            for ($itemsPerRow = 1;$itemsPerRow <= $this->itemsPerRow;$itemsPerRow++)
            {
                $AbsoluteIndex++;
                $currentKey = key($this->keywordsArray);
                if (!empty($currentKey))
                {
                    $currentValue = $this->keywordsArray[$currentKey];
                }
                $TagSize = floor(($this->fontRatio * $currentValue) + $this->fontOffset);

                $htmlCode .= "<a href=\"" . $this->linkUrlPrefix . $currentKey . "\"style=\"font-size:" . $TagSize . "pt;padding:" . $this->padding . "px;\">" . $currentKey . "</a>";

                next($this->keywordsArray);
            }

            $htmlCode .= "<br />";
        }

        return $htmlCode;
    }
}

?>