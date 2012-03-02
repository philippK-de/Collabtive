<?php

Class xls
{
    private $fp = null;
    private $state = "CLOSED";
    private $newRow = false;

    /*
		* @Params : $file  : file name of excel file to be created.
		* @Return : On Success Valid File Pointer to file
		* 			On Failure return false
		*/

    function __construct($file = "")
    {
        return $this->open($file);
    }

    function open($file)
    {
        if ($this->state != "CLOSED")
        {
            return false;
        }

        if (!empty($file))
        {
            $this->fp = @fopen($file, "w+");
        }
        else
        {
            return false;
        }
        if ($this->fp == false)
        {
            return false;
        }
        $this->state = "OPENED";
        fwrite($this->fp, $this->GetHeader());
        return $this->fp;
    }

    function close()
    {
        if ($this->state != "OPENED")
        {
            return false;
        }
        if ($this->newRow)
        {
            fwrite($this->fp, "</tr>");
            $this->newRow = false;
        }

        fwrite($this->fp, $this->GetFooter());
        fclose($this->fp);
        $this->state = "CLOSED";
        return ;
    }

    function GetHeader()
    {
        $lastsav = date("e");
        $header = <<<EOH
				<html xmlns:o="urn:schemas-microsoft-com:office:office"
				xmlns:x="urn:schemas-microsoft-com:office:excel"
				xmlns="http://www.w3.org/TR/REC-html40">

				<head>
				<meta http-equiv=Content-Type content="text/html; charset=utf-8">
				<meta name=ProgId content=Excel.Sheet>
				<!--[if gte mso 9]><xml>
				 <o:DocumentProperties>
				  <o:LastAuthor>Collabtive</o:LastAuthor>
				  <o:LastSaved>$lastsav </o:LastSaved>
				  <o:Version>1</o:Version>
				 </o:DocumentProperties>
				 <o:OfficeDocumentSettings>
				  <o:DownloadComponents/>
				 </o:OfficeDocumentSettings>
				</xml><![endif]-->
				<style>
				<!--table
					{mso-displayed-decimal-separator:"\.";
					mso-displayed-thousand-separator:"\,";}
				@page
					{margin:1.0in .75in 1.0in .75in;
					mso-header-margin:.5in;
					mso-footer-margin:.5in;}
				tr
					{mso-height-source:auto;}
				col
					{mso-width-source:auto;}
				br
					{mso-data-placement:same-cell;}
				.style0
					{mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					white-space:nowrap;
					mso-rotate:0;
					mso-background-source:auto;
					mso-pattern:auto;
					color:windowtext;
					font-size:11.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					border:none;
					mso-protection:locked visible;
					mso-style-name:Normal;
					mso-style-id:0;}
					.style1
					{mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					white-space:nowrap;
					mso-rotate:0;
					mso-background-source:auto;
					mso-pattern:auto;
					color:windowtext;
					font-size:12.0pt;
					font-weight:600;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					border:none;
					mso-protection:locked visible;
					mso-style-name:Normal;
					mso-style-id:0;}
				td
					{mso-style-parent:style0;
					padding-top:1px;
					padding-right:1px;
					padding-left:1px;
					mso-ignore:padding;
					color:windowtext;
					font-size:11.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					border:none;
					mso-background-source:auto;
					mso-pattern:auto;
					mso-protection:locked visible;
					white-space:nowrap;
					mso-rotate:0;}
				.xl24
					{mso-style-parent:style0;
					white-space:normal;}
						.xl25
					{mso-style-parent:style1;
					white-space:normal;}
				-->
				</style>
				<!--[if gte mso 9]><xml>
				 <x:ExcelWorkbook>
				  <x:ExcelWorksheets>
				   <x:ExcelWorksheet>
					<x:Name>srirmam</x:Name>
					<x:WorksheetOptions>
					 <x:Selected/>
					 <x:ProtectContents>False</x:ProtectContents>
					 <x:ProtectObjects>False</x:ProtectObjects>
					 <x:ProtectScenarios>False</x:ProtectScenarios>
					</x:WorksheetOptions>
				   </x:ExcelWorksheet>
				  </x:ExcelWorksheets>
				  <x:WindowHeight>10005</x:WindowHeight>
				  <x:WindowWidth>10005</x:WindowWidth>
				  <x:WindowTopX>120</x:WindowTopX>
				  <x:WindowTopY>135</x:WindowTopY>
				  <x:ProtectStructure>False</x:ProtectStructure>
				  <x:ProtectWindows>False</x:ProtectWindows>
				 </x:ExcelWorkbook>
				</xml><![endif]-->
				</head>

				<body link=blue vlink=purple>
				<table x:str border=0 cellpadding=0 cellspacing=0 style="border-collapse: collapse;table-layout:fixed;">
EOH;
        return $header;
    }

    function GetFooter()
    {
        return "</table></body></html>";
    }

    function writeLine(array $line_arr, $width = 64)
    {
        if ($this->state != "OPENED")
        {
            return false;
        }

        fwrite($this->fp, "<tr>");
        foreach($line_arr as $col)
        {
            fwrite($this->fp, "<td class=xl24 width=$width >$col</td>");
        }
        fwrite($this->fp, "</tr>");
    }

    function writeBoldLine(array $line_arr, $width = 64)
    {
        if ($this->state != "OPENED")
        {
            return false;
        }

        fwrite($this->fp, "<tr>");
        foreach($line_arr as $col)
        {
            fwrite($this->fp, "<td class = x125 width=$width ><b>$col</b></td>");
        }
        fwrite($this->fp, "</tr>");
    }

    function writeHeadLine(array $line_arr, $width = 64)
    {
        if ($this->state != "OPENED")
        {
            return false;
        }

        fwrite($this->fp, "<tr>");
        foreach($line_arr as $col)
        {
            fwrite($this->fp, "<td class = x125 width=$width ><h3>$col</h3></td>");
        }
        fwrite($this->fp, "</tr>");
    }

    function writeRow()
    {
        if ($this->state != "OPENED")
        {
            return false;
        }
        if ($this->newRow == false)
        {
            fwrite($this->fp, "<tr>");
        }
        else
        {
            fwrite($this->fp, "</tr><tr>");
            $this->newRow = true;
        }
    }

    function writeColspan($value, $colspan = 0)
    {
        if ($this->state != "OPENED")
        {
            return false;
        }
        fwrite($this->fp, "<td colspan=$colspan class=xl24 width=64 >$value</td>");
    }

    function writeCol($value)
    {
        if ($this->state != "OPENED")
        {
            return false;
        }
        fwrite($this->fp, "<td class=xl24 width=64 >$value</td>");
    }
}

?>