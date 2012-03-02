<?php
ob_start ("ob_gzhandler");
header("Content-type: text/css; charset: utf-8");
header("Cache-Control: must-revalidate");
$offset = 60 * 60 ;
$ExpStr = "Expires:" .
gmdate("D, d M Y H:i:s",
    time() + $offset) . "GMT";
header($ExpStr);

$colorMain = "#001e40";

$loginAlert = "#BE4C43";

$logoName = "#fff";
$logoSubname = "#6ab0c5";
$leftBlockBg = "#FFFFFF";

//$tabsInfo = "#6d7f93";
$tabsInfo = "#839BB5";
$mainMenueInfo = "#6d7f93"; // Info-Span in Mainmenue

$bigCalToday = "#eaf6e3"; //day in the Big Calendar

$green = "#009359";
$greenbg = "#e0f1db";
$red = "#be4c43";
$redbg = "#e7dcda";

$projectsMain = "#DCE7F5";
$projectsB = "#234B96"; // Borders in Forms
$projectsC = "#DCE7F5"; // Buttons Hover
$projectsBlockhead = "url(../images_frost/bg_th.png)";
$projectsTablehead = "url(../images_frost/tables-projects-thead.png)";
$projectsTableSecondhead = "url(../images_frost/tables-projects-sechead.png)"; // closed Things
$projectsColorA = "url(../images_frost/tables-projects-bg-a.png)"; // ColorMix Rows
$projectsColorB = "url(../images_frost/tables-projects-bg-b.png)"; // ColorMix Rows
$projectsColorC = "url(../images_frost/tables-projects-bg-c.png)"; // Files in Messages

$tasksMain = "#DCE7F5";
$tasksB = "#234B96"; // Borders in Forms
$tasksC = "#203d56"; // Buttons Hover
$tasksBlockhead = "url(../images_frost/bg_th.png)";
$tasksTablehead = "url(../images_frost/tables-tasks-thead.png)";
$tasksTableSecondhead = "url(../images_frost/tables-tasks-sechead.png)"; // closed Things
$tasksColorA = "url(../images_frost/tables-tasks-bg-a.png)"; // ColorMix Rows
$tasksColorB = "url(../images_frost/tables-tasks-bg-b.png)"; // ColorMix Rows
$tasksColorC = "url(../images_frost/tables-tasks-bg-c.png)"; // Files in Messages

$msgsMain = "#DCE7F5";
$msgsB = "#a3a19e"; // Borders in Forms
$msgsC = "#464440"; // Buttons Hover
$msgsBlockhead = "url(../images_frost/bg_th.png)";
$msgsTablehead = "url(../images_frost/tables-msgs-thead.png)";
$msgsTableSecondhead = "url(../images_frost/tables-msgs-sechead.png)"; // closed Things
$msgsColorA = "url(../images_frost/tables-msgs-bg-a.png)"; // ColorMix Rows
$msgsColorB = "url(../images_frost/tables-msgs-bg-b.png)"; // ColorMix Rows
$msgsColorC = "url(../images_frost/tables-msgs-bg-c.png)"; // Files in Messages

$userMain = "#576d5a";
$userB = "#bbcbbb"; // Borders in Forms
$userC = "#3d573f"; // Buttons Hover
$userBlockhead = "url(../images_frost/bg_th.png)";
$userTablehead = "url(../images_frost/tables-user-thead.png)";
$userTableSecondhead = "url(../images_frost/tables-user-sechead.png)"; // closed Things
$userColorA = "url(../images_frost/tables-user-bg-a.png)"; // ColorMix Rows
$userColorB = "url(../images_frost/tables-user-bg-b.png)"; // ColorMix Rows
$userColorC = "url(../images_frost/tables-user-bg-c.png)"; // Files in Messages

$milesMain = "#824e48";
$milesB = "#b49591"; // Borders in Forms
$milesC = "#62332e"; // Buttons Hover
$milesBlockhead = "url(../images_frost/bg_th.png)";
$milesTablehead = "url(../images_frost/tables-miles-thead.png)";
$milesTableSecondhead = "url(../images_frost/tables-miles-sechead.png)"; // closed Things
$milesColorA = "url(../images_frost/tables-miles-bg-a.png)"; // ColorMix Rows
$milesColorB = "url(../images_frost/tables-miles-bg-b.png)"; // ColorMix Rows
$milesColorC = "url(../images_frost/tables-miles-bg-c.png)"; // Files in Messages

$neutralMain = "#6f6d6e";
$neutralB = "#a9a7a8"; // Borders in Forms
$neutralC = "#4f4e4e"; // Buttons Hover
$neutralBlockhead = "url(../images_frost/bg_th.png)";
$neutralTablehead = "url(../images_frost/tables-neutral-thead.png)";
$neutralTableSecondhead = "url(../images_frost/tables-neutral-sechead.png)"; // closed Things
$neutralColorA = "url(../images_frost/tables-neutral-bg-a.png)"; // ColorMix Rows
$neutralColorB = "url(../images_frost/tables-neutral-bg-b.png)"; // ColorMix Rows
$neutralColorC = "url(../images_frost/tables-neutral-bg-c.png)"; // Files in Messages

$timetrackMain = "#8f5f45";
$timetrackB = "#bc9f8f"; // Borders in Forms
$timetrackC = "#6f412c"; // Buttons Hover
$timetrackBlockhead = "url(../images_frost/bg_th.png)";
$timetrackTablehead = "url(../images_frost/tables-timetracking-thead.png)";
$timetrackTableSecondhead = "url(../images_frost/tables-timetracking-sechead.png)"; // closed Things
$timetrackColorA = "url(../images_frost/tables-timetracking-bg-a.png)"; // ColorMix Rows
$timetrackColorB = "url(../images_frost/tables-timetracking-bg-b.png)"; // ColorMix Rows
$timetrackColorC = "url(../images_frost/tables-timetracking-bg-c.png)"; // Files in Messages

$filesMain = "#484f64";
$filesB = "#9195a2"; // Borders in Forms
$filesC = "#2e3345"; // Buttons Hover
$filesBlockhead = "url(../images_frost/bg_th.png)";
$filesTablehead = "url(../images_frost/tables-files-thead.png)";
$filesTableSecondhead = "url(../images_frost/tables-files-sechead.png)"; // closed Things
$filesColorA = "url(../images_frost/tables-files-bg-a.png)"; // ColorMix Rows
$filesColorB = "url(../images_frost/tables-files-bg-b.png)"; // ColorMix Rows
$filesColorC = "url(../images_frost/tables-files-bg-c.png)"; // Files in Messages


?>

/*
##  Visional Arts CSS Framework 1.1 static
##  Author & Copyright: Marcus Fröhner
##  URL: http://www.visional-arts.de
*/

@import url("style_form.css");

/* ## Basic-XHTML-Elements ################################ */

html, body {
	margin: 0;
	padding: 0;
	height: 100%;
	font-family: Arial, helvetica, sans-serif;
	font-size: 13px;
}

body {
	color:#082343;
	background:#F6F7F9 url(../images_frost/main-bg.jpg) repeat-x 0 0;
	background-attachment: fixed;
}

body a, body a:visited {
	text-decoration:none;
	outline: none;
}

body a:hover {
	text-decoration: none;
}

.hidden {
	visibility:hidden;
}

.visible {
	visibility:visible;
}

.clear_both {
	clear:both;
}

.clear_both_b {
	clear:both;
	height:15px;
}

img {
	border: none;
}

::selection {
	background: <?php echo $colorMain;
?> ;
	color: #fff;
}

::-moz-selection {
	background: <?php echo $colorMain;
?>;
	color: #fff;
}

ul {
	margin: 0;
	padding: 0;
	list-style-type: none;
}

td.message ul {
	padding: 0 0 0 5px;
	margin: 0 0 0 10px;
	list-style-type: disc;
}

.error_message {
	color:red;
}


/* ## Basic-XHTML-Elements ############### END ############## */


/* ## LOGIN-Elements ################################ */

.login {
	width: 460px;
	position: absolute;
	top: 10%;
	left: 50%;
	margin-left: -230px;
}

.login-in, .login-alert {
	width: 100%;
	background: #FEFEFE url(../images_frost/color-a.png) repeat-x; border: 1px solid #D2D6DF;
	text-align: center;
	padding: 20px 0 20px 0;
	/*
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	*/
}

.login-alert {
	margin: 3px 0 0 0;
	padding: 5px 0 5px 0;
	background-image: url(../images_frost/login-alert.png);
	font-weight: bold;
	color: <?php echo $loginAlert;
?>;
}

.login .logo-name {
	width:100%;
	text-align: center;
}

.logo-name h1 {
	color: <?php echo $logoName;
?>;
	margin: 0;
	height: auto;
	line-height: normal;
}

.logo-name h2 {
	font-size: 15px;
	color: <?php echo $logoSubname;
?>;
	margin: 8px 0 18px 0;
}

/* ## LOGIN-Elements ############## END ############# */


#sitebody {
	display: block;
	min-width: 980px;
	min-height: 100%;
	margin: 0 auto;
}

#header-wrapper {
	width:100%;
	height: 70px;
	background: url(../images_frost/color-a.png) repeat 0 0;
}

#header {
	width: 980px;
	margin: 0 auto 0 auto;
}

.header-in {
	padding: 11px 0 0 0;
	height: 70px;
}

#header .left {
	float: left;
	width: 742px;
	height: 70px;
	margin-right: 18px;
	overflow: hidden;
}

#header .right {
	float: left;
	width: 202px;
	padding-left: 18px;
}

#header .logo {
	float: left;

}

#header .logo img {
	float: left;
}

#header .logo h1 span.title {
	color: #fff;
	font-size: 26px;
	font-weight: bold;
	position: relative;
	top: 20px;
	left: -5px;
	display:block;
	float:left;
}

#header .logo h1 span.subtitle {
color: #547AC3;
}

/* ## MAIN-MENUE ######################################## */

#mainmenue {
	padding: 8px 0 0 0;
	position: relative;
}

#mainmenue li {
	float: left;
	width: 32px;
	height: 32px;
	margin-right: 6px;
}

#mainmenue li a {
	display: block;
	float: left;
	width: 32px;
	height: 32px;
}

#mainmenue li.desktop a {
	background: url(../images_frost/main-desk.png) no-repeat 0 0;
}


#mainmenue li.profil-male a {
	background: url(../images_frost/main-prof-male.png) no-repeat 0 0;
}

#mainmenue li.profil-female a {
	background: url(../images_frost/main-prof-female.png) no-repeat 0 0;
}

#mainmenue li.admin a {
	background: url(../images_frost/main-settings.png) no-repeat 0 0;
}

#mainmenue li.logout a {
	background: url(../images_frost/main-logout.png) no-repeat 0 0;
}

#mainmenue li a span {
	display: none;
}

#mainmenue .submen {
	position: absolute;
	top: 34px;
	left: -6px;
	padding: 25px 0 0 0;
	float:left;
	z-index: 6;
	display: none;
}

#mainmenue .submen ul {
	width: auto;
	background: url(../images_frost/color-a.png) repeat 0 0;
	padding: 0 0 6px 6px;
	float: left;
	-moz-border-radius-bottomleft: 4px;
	-moz-border-radius-bottomright: 4px;
	-webkit-border-bottom-left-radius: 4px;
	-webkit-border-bottom-right-radius: 4px;
	border-bottom-left-radius: 4px;
	border-bottom-right-radius: 4px;
}

#mainmenue .submen li {
	margin: -6px 6px 0 0;
}

#mainmenue li:hover .submen {
	display: block;
}

#mainmenue .submen li.project-settings a {
	background: url(../images_frost/main-admin-a.png) no-repeat 0 0;
}

#mainmenue .submen li.user-settings a {
	background: url(../images_frost/main-admin-b.png) no-repeat 0 0;
}

#mainmenue .submen li.system-settings a {
	background: url(../images_frost/main-admin-c.png) no-repeat 0 0;
}

#mainmenue li:hover a, #mainmenue li a.active, #mainmenue .submen li:hover a, #mainmenue .submen li a.active {
	background-position: 0 -32px;
}

#mainmenue li a:hover span {
	display: block;
	width: 200px;
	position: absolute;
	top: 18px;
	left: -222px;
	color: <?php echo $mainMenueInfo;
?>;
	font-size: 12px;
	font-weight: bold;
	text-align: right;
	white-space: nowrap;
}

#mainmenue li .submen a:hover span {
	top: -16px;
	left: -216px;
}

#mainmenue li:hover a .submenarrow {
	display: block;
	position: relative;
	top: 32px;
	left: 0;
	width: 32px;
	height: 7px;
	padding: 0;
	background: url(../images_frost/main-submen.png) no-repeat center 0;
}

/* ## CONTENT ######################################## */

#contentwrapper {
	width: 980px;
	margin: 0 auto -26px auto; /* same as footer-height (for IE 7) */
}

#content-left {
	width: 742px;
	min-height: 100px;
	background: <?php echo $leftBlockBg;
?>;
	margin: 0 18px 10px 0;
	float: left;
}

#content-left-in {
	margin: 18px 18px 0 18px;
}

#content-left h1 {
	padding: 0 0 15px 2px;
	width: 100%;
	overflow: hidden;
}

#content-left h1 span {
	font-size: 15px;
}

#content-left h1.second {
	line-height: 34px;
	margin: -5px 0 10px 0;
	position: relative;
	left: -8px;
}

#content-left h1 img {
	float: left;
}

.content-spacer {
	clear: both;
	width: 100%;
	height: 27px;
}

.content-spacer-b {
	clear: both;
	width: 100%;
	height: 13px;
	margin: 13px 0 0 0;
	border-top: 1px dotted #000;
}

#content-right {
	width: 220px;
	min-height: 20px;
	background: url(../images_frost/color-a.png) repeat 0 0;
	margin: 0 0 0 0;
	padding: 0 0 23px 0;
	float: left;
	color: #6d7f93;
}

.content-right-in {
	width: 184px;
	margin: 23px 0 0 18px;
}

.content-right-in .cloud { /* Tag Cloud */
	width: 184px;
	overflow: hidden;
}


.content-right-in a {
	color: #6d7f93;
}

.content-right-in a:hover {
	color: #9daab7;
}

#content-right h2 {
	font-size: 14px;
	margin: 0;
	line-height: 12px;
}

#content-right h2 a.win-up, #content-right h2 a.win-down {
	display: block;
	width: 100%;
	height: 16px;
	background: url(../images_frost/win-up-side.png) no-repeat right 1px;
	margin: 0 0 5px 0;
}

 #content-right h2 a.win-down {
	background: url(../images_frost/win-down-side.png) no-repeat right 1px;
 }

#content-right h2 a.win-up:hover, #content-right h2 a.win-down:hover {
	background-position: right -15px;
}

/* ## CHAT ############################################# */

.chat {
	background: url(../images_frost/color-a.png) repeat 0 0;
	padding: 11px;
	color: #6d7f93;
	float: left;
	height: 100%;
}

.chat .row .text {
	float: left;
	width: 217px;
	height: 19px;
	padding: 4px 6px 0 6px;
	border:none;
	font-size: 12px;
	background: url(../images_frost/input-bg-a.png) repeat-x 0 0;
	color: #0a182f;
	font-weight: bold;
	margin-right: 3px;
}

.chat .chattext {
	height: 200px;
	width: 300px;
	padding: 5px;
	overflow: auto;
	margin: 0 0 10px 0;
	float: left;
	background: #fff;
	color: #0a182f;
}

.chat button:hover {
	background: #6d7f93;
	color: #0a182f;
}

.chat button[disabled]:hover {
	background: #384e67;
	color: #fff;
}

/* ## SEARCH MODAL ################################### */

.search-modal {
	width: 202px;
	min-height: 20px;
	background: url(../images_frost/color-a.png) repeat 0 0;
	margin: 0 0 0 0;
	padding: 12px 0px 23px 18px;
	float: left;
	color: #6d7f93;
	-moz-border-radius: 6px;
	-webkit-border-radius: 6px;
	border-radius: 6px;
}

/* ## WINTOOLS ######################################## */

.wintools {
	float: right;
	height: 26px;
	position: relative;
	top: -28px;
	margin: 0 37px -30px 0;
	z-index: 9;
}

.wintools a {
	background: url(../images_frost/win-tools.png) 0 0;
}

.wintools a.close {
	background-position: 0 0;
}

.wintools a.close:hover {
	background-position: 0 -31px;
}

.wintools a.edit {
	background-position: -23px 0;
}

.wintools a.edit:hover {
	background-position: -23px -31px;
}

.wintools a.del {
	background-position: -46px 0;
}

.wintools a.del:hover {
	background-position: -46px -31px;
}

.wintools a.filter {
	background-position: -69px 0;
}

.wintools a.filter:hover, .wintools a.filter-active {
	background-position: -69px -31px;
}


.wintools a.add, .wintools a.add-active {
	background-position: right 0;
	margin-left: 9px;
}

.wintools a.add:hover, .wintools a.add-active {
	background-position: right -31px;
}

/* ## EXPORT-MAIN ##################################### */

<?php include "export.css";
?>

/* ## IN-MENUES ######################################## */

.inwrapper {
	float: left;
	width: 100%;
	margin: 6px 0 6px 0;
	font-size: 12px;
}

.inwrapper li {
	float: left;
	width: 92px;
	margin: 0 1px 6px 0;
	padding: 4px 0 0 0;
	text-align: center;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	border-radius: 4px;
}

.inwrapper img {
	float: left;
}

.itemwrapper {
	/* in this are all the li */
}

.itemwrapper table {
	width: 100%;
}

.inwrapper span.name a:hover {
	text-decoration: underline;
}

.inwrapper li td.thumb a {
	display: block;
	width: 32px;
	max-height: 32px;
	overflow: hidden;
	padding: 0;
	margin: 0;
	border: none;
}

.inwrapper li td.thumb {
	width: 32px;
	height: 32px;
}

.inwrapper li td.thumb a img {
	float: none;
	margin: 0;
}

.inwrapper span.name {
	display: block;
	clear: both;
	width: 100%;
	margin: 5px 0 0 0;
	height: 18px;
	line-height: 18px;
	text-align: center;
	overflow: hidden;
}

.inwrapper li .rightmen, .inwrapper li  .leftmen {
	width: 30px;
}

.inmenue {
	height: 32px;
}

.inmenue a {
	display: none;
	width: 14px;
	height: 14px;
	background: url(../images_frost/inmenue-tools.png) no-repeat;
	margin: 0 0 3px 8px;
}

.itemwrapper:hover .inmenue a {
	display: block;
}

.inmenue a.more {
	background-position: 1px 1px;
}
.inmenue a.more:hover {
	background-position: 1px -13px;
}

.inmenue a.export {
	background-position: -13px 1px;
}
.inmenue a.export:hover {
	background-position: -13px -13px;
}

.inmenue a.edit {
	background-position: -27px 1px;
}
.inmenue a.edit:hover {
	background-position: -27px -13px;
}

.inmenue a.del {
	background-position: -41px 1px;
}
.inmenue a.del:hover {
	background-position: -41px -13px;
}

.moreinfo-wrapper {
	position: relative;
	clear: both;
}

.moreinfo {
	position: absolute;
	left: 0;
	bottom: 0px;
	padding: 5px 5px 0 5px;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	border-radius: 4px;
	width: 82px;
	z-index: 11;
}

.moreinfo img {
	float: left;
}

.moreinfo img:hover {
	cursor: pointer;
}

.user .moreinfo a, .projects .moreinfo a, .tasks .morinfo a {
	color: <?php echo $leftBlockBg;
?>;
}




/* ## CALENDAR ############## Sidebar / Datepicker ################# */

.cal {
	width: 184px;
}

.cal tr {
	height: 23px;
}

.cal td {
	padding: 0;
	background: url(../images_frost/color-d.png) repeat 0 0;
	text-align: center;
	font-size: 11px;
}

.cal tr.head, .cal tr.weekday {
	font-weight: bold;
}

.cal tr.head td {
	background: url(../images_frost/color-d.png) repeat 0 0;
}

.cal tr.weekday td {
	background: url(../images_frost/color-c.png) repeat 0 0;
}

.cal td.back, .cal td.next {
	/*background: url(../images_frost/autocomplete-bg-a.png) repeat-x 0 0;*/
}

.cal td.back a, .cal td.next a {
	display: block;
	width: 100%;
	height: 23px;
	background: url(../images_frost/back-side.png) no-repeat center 7px;
}

.cal td.next a {
	background-image: url(../images_frost/next-side.png);
}

.cal td.back a:hover, .cal td.next a:hover {
	background-position: center -16px;
}

.cal td.wrong {
	color: #3c556f;
	background: url(../images_frost/color-c.png) repeat 0 0;
}

.cal td.today {
	color: #52a454;
	background: url(../images_frost/color-c-green.png) repeat 0 0;
}

.cal td.red {
	color: #be4c43;
	background: url(../images_frost/color-c-red.png) repeat 0 0;
}

.cal td.cyan {
	color: #529ba4;
	background: url(../images_frost/color-c-cyan.png) repeat 0 0;
}

/* ## BIG CALENDAR ###################################### */

.block .bigcal table.thecal {
	border-collapse: separate;
	display: inline-table;
	table-layout: fixed;
}

.block .bigcal table.thecal thead {
	background: <?php echo $leftBlockBg;
?>;
	font-size: 14px;
}

.block .bigcal table.thecal th {
	height: 37px;
	line-height: 37px;
	border: none;
	padding: 0;
}

.block .bigcal table.thecal .dayhead th {
	height: 27px;
	line-height: 27px;
	padding: 0 6px 0 6px;
}

.block .bigcal tbody.content td {
	border: none;
	width: 100px;
	background: <?php echo $leftBlockBg;
?>;
	padding: 6px;
	line-height: normal;
}

.block .bigcal table.thecal td.today {
	background: <?php echo $bigCalToday ?>;
}
.block .bigcal table.thecal td.second {
}
.block .bigcal table.thecal td.othermonth {
}
.block .bigcal .calcontent {
}

.bigcal .scroll_left, .bigcal .scroll_right {
	display: block;
	width: 100%;
	height: 100%;
	background: url(../images_frost/scroll_left_miles.png) no-repeat center 15px;
}

.bigcal .scroll_right {
	background-image: url(../images_frost/scroll_right_miles.png);
}

.bigcal .scroll_left:hover, .bigcal .scroll_right:hover {
	background-position: center -20px
}

.calinmenue {
	position: absolute;
	height: 0px;
	z-index: 1666;
}

.calinmenue ul {
	position: relative;
	left: 0;
	top: 0;
	padding: 6px;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	border-radius: 4px;
}

.calinmenue ul li a {
	display: block;
	height: 22px;
	line-height: 22px;
	margin: 0 0 1px 0;
	padding: 0 8px 0 8px;
	white-space: nowrap;
	color: <?php echo $leftBlockBg;
?>;
	font-size: 11px;
	font-weight: bold;
}

.calinmenue ul li a:hover {
	text-decoration: none;
}

.closewin a {
	display: block;
	background: url(../images_frost/closewin.png) no-repeat right 1px;
}

.closewin a:hover {
	background-position: right -19px;
}

.calinmenue ul li.closewin a {
	height: 14px;
	line-height: normal;
	padding: 0 0 0 4px;
	margin: 0 0 4px 0;
}

/* ## DATEPICKER ######################################## */

.datepick {
	clear:both;
	position: absolute;
	z-index: 1000000000;
}

.datepick .picker {
	position: relative;
	left: 152px;
	top: -210px;
	padding: 6px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
}

.datepick .picker a {

}

.datepick .cal {
	background: #fff;
}

.block .datepick table, .datepick table {
	border-collapse: separate;
	border: none;
}

.block .datepick table td, .datepick table td {
	padding: 0 6px 0 6px;
	min-width: 12px;
	border: none;
	height: 23px;
	line-height: 23px;
}

.projects .datepick td.today, .tasks .datepick td.today, .miles .datepick td.today, .timetrack .datepick td.today, .neutral .datepick td.today {
	color: <?php echo $green;
?>;
	background: url(../images_frost/color-c-green.png) repeat 0 0;
}

.projects .datepick td.red, .tasks .datepick td.red, .miles .datepick td.red, .timetrack .datepick td.red, .neutral .datepick td.red {
	color: <?php echo $red;
?>;
	background: url(../images_frost/color-c-red.png) repeat 0 0;
}

.datepick td.normalday:hover, .datepick td.today:hover, .datepick td.red:hover {
	cursor: pointer;
	background: #fff;
}

.projects .datepick td.next a {
	background-image: url(../images_frost/next-side-projects.png);
}
.projects .datepick td.back a {
	background-image: url(../images_frost/back-side-projects.png);
}
.tasks .datepick td.next a {
	background-image: url(../images_frost/next-side-tasks.png);
}
.tasks .datepick td.back a {
	background-image: url(../images_frost/back-side-tasks.png);
}
.timetrack .datepick td.next a {
	background-image: url(../images_frost/next-side-timetrack.png);
}
.timetrack .datepick td.back a {
	background-image: url(../images_frost/back-side-timetrack.png);
}
.user .datepick td.next a {
	background-image: url(../images_frost/next-side-user.png);
}
.user .datepick td.back a {
	background-image: url(../images_frost/back-side-user.png);
}
.miles .datepick td.next a {
	background-image: url(../images_frost/next-side-miles.png);
}
.miles .datepick td.back a {
	background-image: url(../images_frost/back-side-miles.png);
}
.files .datepick td.next a {
	background-image: url(../images_frost/next-side-files.png);
}
.files .datepick td.back a {
	background-image: url(../images_frost/back-side-files.png);
}
.neutral .datepick td.next a {
	background-image: url(../images_frost/next-side-neutral.png);
}
.neutral .datepick td.back a {
	background-image: url(../images_frost/back-side-neutral.png);
}

/* ## ONLINELISTE ######################################## */

#onlinelist ul {
	border-bottom: 1px solid #3c5570;
	float: left;
	position: relative;
}

#onlinelist li {
	width: 184px;
	clear: both;
	border-top: 1px solid #3c5570;
}

#onlinelist li a div {
	display: none;
}

#onlinelist li a div img {
	margin: 5px 5px 5px 5px;
	float: right;
}

#onlinelist a.user, #onlinelist a.chat, .chat-user {
	display: block;
	float: left;
	width: 167px;
	height: 23px;
	line-height: 23px;
	color: #6d7f93;
	padding-left: 1px;
}

#onlinelist a.user:hover, .chat-user {
	color: #9daab7;
	/*background: url(../images_frost/onlinelist-hover.png) repeat 0 0; */
}

#onlinelist a.chat, .chat-user {
	width: 16px;
	padding: 0;
	background: url(../images_frost/chat.png) no-repeat right -23px;
}

#onlinelist a.chat:hover {
	background-position: right -46px;
}

.chat-user {
	background-position: right 0;
}

#onlinelist li a:hover div {
	display:block;
	position: absolute;
	top: 0;
	left: -119px;
	width: 100px;
	z-index: 8;
	background: url(../images_frost/color-b.png) repeat 0 0;
}

/* ## HEADLINES ##################################### */

.headline, .headline_lone {
	width: 100%;
	height: 35px;
	margin: 0 0 0 0;
	position: relative;

}

.headline h2 {
	position: absolute;
	height: 100%;
	line-height: 35px;
	top: 0;
	left: 0;
}

.headline_lone {
	height: 100%;
	line-height: 35px;
}

.headline_lone h2 {
	height: 32px;
	line-height: 33px;
	margin: -15px 0 10px 2px;
}

.headline h2, .headline h2 a {
	color: <?php echo $leftBlockBg;
?>;
}

.headline img, .headline_lone img {
	float:left;
	margin: 0 7px 0 2px;
}

.headline_lone h2 img {
	margin: 0 0px 0 -4px;
}

h2 a:hover {
		text-decoration: none;
}

#content-left h2 a:hover {
		text-decoration: none;
		border-bottom: 1px dotted <?php echo $leftBlockBg;
?>;
}


/* ## Block-headline - Aufklappfunktion ## start ## */

a.win_block {
	display: block;
	width: 100%;
	height: 100%;
	background: url(../images_frost/win-up.png) no-repeat 678px 8px;
}

a.win_block:hover {
	background-position: 678px -23px;
}

a.win_none {
	display:block;
	width:100%;
	height: 100%;
	background: url(../images_frost/win-up.png) no-repeat 678px -54px;
}

a.win_none:hover {
	background-position: 678px -85px;
}

/* ## BUTTON ####################################### */

a.butn_link, a.butn_link_active {
	display: block;
	float: left;
	height: 23px;
	line-height: 23px;
	padding: 0px 8px 0 8px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	font-size: 11px;
	font-weight: bold;
	margin: 0 1px 0 0;
}

.tablemenue a.butn_link, .tablemenue a.butn_link_active {
	-moz-border-radius-topleft: 0px;
	-moz-border-radius-topright: 0px;
	-webkit-border-top-left-radius: 0px;
	-webkit-border-top-right-radius: 0px;
	border-top-left-radius: 0px;
	border-top-right-radius: 0px;
}

body a.butn_link, body a.butn_link_active {
	color: <?php echo $leftBlockBg;
?>;
}

/* ## AVATARE / MESSAGES ########################### */

.avatar, .avatar-profile {
	float: left;
	min-height: 1px;
}

.msgs .avatar {
	width: 92px;
}

.user .avatar-profile {
	width: 122px;
}

.user .avatar-profile img,.msgs .avatar img, .avatar img {
	float: left;
}

.message {
	float: left;
	overflow: hidden;
}
.message .block { /* Block for Userdetails in Profil*/
	margin: 0;
}

.msgs .message {
	width: 562px;
	margin: 0;
	padding: 0;
}

.user .message {
	width: 567px;
}

.message-in {
	width: 562px;
	overflow-x: auto;
}
.message-in ul
{
padding-left;
}
message-in li
{
list-style-type: disc;
padding: 0;
}

.message-in img {
	height: auto;
}
.message img {
	float: left;
	margin: 0 6px 6px 0;
	max-width: 100%;
	height: auto;
}

.message p {
	margin: 0 0 12px 0;
}

p.tags-miles {
	clear: both;
	border-top: 1px dotted #000;
	margin: 12px 0 0 0;
	padding: 12px 0 0 0;
}

.message ul.files {
	margin: 12px 0 0 0;
}

.message ul.files table {
	margin: 0 0 1px 0;
}

.message ul.files table img {
	margin: 0;
}

.message ul.files table td {
	height: 30px;
	line-height: 30px;
	padding: 0;
}

.message ul.files table td.filepic {
	width: 35px;
}

.message ul.files table td.filelink {
	width: 502px;
}

.message ul.files table td.tools {
	width: 23px;
}

.message .toggle-content {
	border-right: none;
	border-top: 1px solid <?php echo $leftBlockBg;
?>;
	border-bottom: 1px solid <?php echo $leftBlockBg;
?>;
	padding: 12px 0 12px 6px;

}

/* ## Blind Toggles ################################### */

.blinded {
	overflow: hidden;
	clear: both;
}

/* ## Breadcrumbs #################################### */

.breadcrumb {
	width: 100%;
	height: 22px;
	line-height: 22px;
	margin: 0px 0 15px 0;
	position: relative;
	left: -3px;
	overflow: hidden;
}

.breadcrumb span {
	display: block;
	float: left;
	opacity: 0.4;
	/*filter:alpha(opacity=40);*/
	-moz-opacity: 0.4;

}

.breadcrumb a {
	float: left;
	opacity: 0.4;
	/*filter:alpha(opacity=40);*/
	-moz-opacity: 0.4;
}

.breadcrumb a:hover {
	opacity: 1;
	/*filter:alpha(opacity=100);*/
	-moz-opacity: 1;
	text-decoration: underline;
}

.breadcrumb img {
	float: left;
	height: 22px;
}


/* ## TAB-MENUES ################################### */

.tabswrapper {
	height: 45px;
	width: 100%;
	padding: 15px 0 0 0;
}

ul.tabs {
	position: relative;
	float: left;
}

ul.tabs li {
	float: left;
	width: 57px;
	height: 45px;
	margin: 0 1px 0 0;
}

ul.tabs li a {
	display: block;
	width: 100%;
	height: 100%;
	background-position: 0 0;
	background-repeat: no-repeat;
}

ul.tabs li a:hover, ul.tabs li a.active {
	background-position: 0 -45px;
}

ul.tabs li a:hover span {
	position: absolute;
	right: -258px;
	top: 25px;
	display: block;
	width: 250px;
	color: <?php echo $tabsInfo;
?>;
	font-size: 12px;
	font-weight: bold;
}

ul.tabs li span {
	display: none;
}

ul.tabs li.desk a {
	background-image: url(../images_frost/symbols/tab-desk.png);
}

ul.tabs li.projects a {
	background-image: url(../images_frost/symbols/tab-projects.png);
}

ul.tabs li.tasks a {
	background-image: url(../images_frost/symbols/tab-tasklist.png);
}

ul.tabs li.msgs a {
	background-image: url(../images_frost/symbols/tab-msgs.png);
}

ul.tabs li.user-male a {
	background-image: url(../images_frost/symbols/tab-userprofil-male.png);
}

ul.tabs li.edit-male a {
	background-image: url(../images_frost/symbols/tab-settings.png);
}

ul.tabs li.user-female a {
	background-image: url(../images_frost/symbols/tab-userprofil-female.png);
}

ul.tabs li.edit-female a {
	background-image: url(../images_frost/symbols/tab-settings.png);
}

ul.tabs li.edit a {
	background-image: url(../images_frost/symbols/tab-edit.png);
}

ul.tabs li.miles a {
	background-image: url(../images_frost/symbols/tab-miles.png);
}

ul.tabs li.files a {
	background-image: url(../images_frost/symbols/tab-files.png);
}

ul.tabs li.user a {
	background-image: url(../images_frost/symbols/tab-userlist.png);
}

ul.tabs li.timetrack a {
	background-image: url(../images_frost/symbols/tab-timetracking.png);
}

ul.tabs li.system-settings a {
	background-image: url(../images_frost/symbols/tab-system-settings.png);
}

/* ## MODALs ######################################## */

#modal_container {
    overflow: auto;
    color:  <?php echo $colorMain;
?>;
    background: none;
    text-align: left;
}

#modal_container.tasksmodal, #modal_container.milesmodal {
	background: <?php echo $tasksC;
?>;
	color: <?php echo $tasksMain;
?>;
	min-height: 150px;
	width: 500px;
	padding: 6px 12px 12px 12px;
	-moz-border-radius: 6px;
	-webkit-border-radius: 6px;
	border-radius: 6px;
	margin: -50px 0 0 -126px;
}

#modal_container.milesmodal {
	background: <?php echo $milesC;
?>;
	color: <?php echo $milesMain;
?>;
}

#modal_container.pics {
	background: none;
	margin: -50px 0 0 -126px;
}

#modal_container.pics img {
	margin: 0 0 -3px 0;
	border: none;
	padding: 0;
}

#modal_overlay {
    background-color: <?php echo $colorMain;
?>;

}

#modal_overlay.useroverlay {
    background: <?php echo $userB;
?>;
}

#modal_overlay.tasksoverlay {
    background: <?php echo $tasksB;
?>;
}

#modal_overlay.milesoverlay {
    background: <?php echo $milesB;
?>;
}

#modal_container .inmodal {
	background: <?php echo $leftBlockBg;
?>;
	-moz-border-radius: 1px;
	-webkit-border-radius: 1px;
	border-radius: 1px;
	padding: 8px;
	min-height: 100px;
	max-height: 450px;
	overflow: auto;
}

#modal_container .inmodal h2 span {
	font-size: 12px;
}


.acc_modal {
	width: 100%;
}

.acc_modal .m_a {
	width: 24px;
}

.acc_modal .m_b {

}

.acc_modal .m_c {
	width: 150px;
}

.acc_modal .icon {
	padding: 0;
}

.acc_modal .icon img {
	width: 24px;
	height: auto;
	float: left;
}

.acc_modal .content_in {
	padding: 6px 6px 6px 31px;
	border-top: 1px solid <?php echo $leftBlockBg;
?>;
}

#modal_container .inmodal img {
	max-width: 430px;
	height: auto;
}

.modaltitle {
	display: block;
	height: 38px;
	line-height: 38px;
	color: <?php echo $leftBlockBg;
?>;
	font-size: 14px;
	font-weight: bold;
	position: relative;
}

.modaltitle img {
	float: left;
}

.modaltitle a.winclose {
	display: block;
	width: 19px;
	height: 19px;
	background: url(../images_frost/closemodalwin.png) no-repeat 0 0;
	float: right;
	position: absolute;
	right: 0;
	top: 8px;
}

.modaltitle a.winclose:hover {
	background-position: 0 -37px;
}

/* ## STATUS ############ Statusbar Project ############# */

.statuswrapper {
	width: 100%;
}

.statuswrapper ul {
	float: left;
	list-style-type: none;
	margin: 0;
	padding: 0;
}

.statuswrapper li {
	margin: 0 2px 0 0;
	border: 1px solid #000;
	height: 23px;
	line-height: 23px;
	float: left;
}

.statuswrapper li.link:hover {
	cursor: pointer;
}

.statuswrapper a {
	display: block;
	height: 100%;
	padding: 0 6px 0 6px;
	float: left;
}

.statuswrapper a.close, .statuswrapper a.closed {
	width: 19px;
	background: url(../images_frost/butn-check.png) no-repeat center 4px;
}

.statuswrapper a.reply, .statuswrapper a.reply-active {
	width: 19px;
	background: url(../images_frost/butn-reply.png) no-repeat center 4px;
}

.statuswrapper a.edit, .statuswrapper a.edit-active  {
	width: 19px;
	background: url(../images_frost/butn-edit.png) no-repeat center 4px;
}

.statuswrapper a.del {
	width: 19px;
	background: url(../images_frost/butn-del.png) no-repeat center 4px;
}

.statuswrapper a:hover, .statuswrapper a.closed, .statuswrapper a.edit-active, .statuswrapper a.reply-active {
	background-position: center -22px;
}

.statuswrapper a.closed:hover {
	background-position: center 4px;
}

.statuswrapper a.desc, .statuswrapper a.desc_active {
	padding: 0 12px 0 6px;
	margin: 0 6px 0 0;
	background: url(../images_frost/acc-open.png) no-repeat right 9px;
}

.statuswrapper a.desc:hover {
	background-position: right -16px;
}

.statuswrapper a.desc_active {
	background-position: right -41px;
}

.statuswrapper a.desc_active:hover {
	background-position: right -66px;
}

.status {
	width: 130px;
	height:	56px;
	position: relative;
	right: 0;
	top: -47px;
	margin: 0 0 -56px 0;
	float: right;
	text-align: center;
	font-size: 40px;
	font-weight: bold;
}

.statusbar, .statusbar_b {
	width: 128px;
	height:	12px;
	background: url(../images_frost/statusbar_incomplete_b.jpg) repeat-x 0 0;
	border: 1px solid #000;
	margin: 6px 0 0 0;
}

.statusbar_b {
	float:left;
	height: 8px;
	background: url(../images_frost/statusbar_incomplete_b.jpg) repeat-x 0 -1px;
	margin: 9px 12px 0 0;
}

.statusbar .complete, .statusbar_b .complete {
	height:	12px;
	background: url(../images_frost/statusbar_complete_b.jpg) repeat-x 0 0;
}

.statusbar_b .complete {
	height: 8px;
	background: url(../images_frost/statusbar_complete_b.jpg) repeat-x 0 -1px;
}

/* ## CONTENT ########### without table ################ */

.contenttitle {
	width: 100%;
	height: 37px;
	line-height: 37px;
	font-size: 12px;
}

.contenttitle_menue {
	float: left;
	width: 33px;
	height: 25px;
	padding: 12px 7px 0 6px;
}

.contenttitle_in {
	float: left;
	width: 652px;
	height: 37px;
	font-weight: bold;
	overflow: hidden;
}

.contenttitle_in a:hover {
	text-decoration: underline;
}

.content_in_wrapper {
	float: left;
	width: 100%;
	padding: 0 0 6px 0;
	margin: 1px 0 1px 0;
}

.content_in_wrapper_in {
	padding: 0 14px 0 14px;
}

.staterow {
	width: 100%;
	height: 23px;
	line-height: 23px;
	clear: both;
	font-size: 12px;

}

.staterowin {
	width: 352px;
	height: 23px;
	overflow: hidden;
	margin: 0 0 0 48px;


}
.staterowin_right {
	width:50px;
	height: 23px;
	overflow: hidden;
	margin: -22px 0 0 650px;
	float:left;



}

/* ## FILES ################ Specials ################## */

a.dir_up_butn {
	display: block;
	width: 28px;
	height: 12px;
	background: url(../images_frost/root-arrow.png) no-repeat 2px 0;
}

a.dir_up_butn:hover {
	background-position: 2px -31px;
}

/* ## HEADLINES ############# Specials ############### */

#content-left-in h1 a:hover {
	border-bottom: 1px dotted #000;
}

/* ## DESCRIPTION ############# Specials ############### */

.descript {
	clear: both;
	width: 100%;
	overflow: hidden;
}

.msgs .descript .avatar {
	width: 98px;
}

.msgs .descript .message {
	width: 608px;
	overflow: hidden;
}

.msgs .descript .message img {
	max-width: 608px;
	height: auto;
}

.descript a:hover {
	text-decoration: underline;
}

/* ## TABLES ######################################## */

.blockwrapper { /* in projectfiles */
	margin: 1px 0 0 0;
}

.block {
	margin: 1px 0 0 0;
}

.block a:hover {
	text-decoration: underline;
}

.block a.butn_link:hover, .block a.butn_link_active:hover, .block .inwrapper a:hover {
	text-decoration: none;
}

.block table {
	width: 100%;
	text-align: left;
	border-collapse: collapse;
	/*display: inline-table;*/
}

.block table tfoot td {
	display: none;
}

.block table td {
	height: 27px;
	line-height: 27px;
	border-right: 1px solid <?php echo $leftBlockBg;
?>;
	padding: 0 0 0 6px;
}

.block table.log td {
	height: auto;
	line-height: normal;
	padding: 6px 0 6px 6px;
}

.block table tbody.paging td {
	height: 27px;
	line-height: 27px;
	padding: 0 0 0 6px;
}

.block table td.finished, .block table td.finished a {

}

.block table td.symbols img {
	float: left;
}

.block table thead {
	background-repeat: repeat-x;
}

.message .block table thead {
	display: none;
}

.block table thead th {
	height: 37px;
	line-height: 37px;
	padding: 0 0 0 6px;
	border-right: 1px solid <?php echo $leftBlockBg;
?>;
}

.block table.second-thead td {
	height: 27px;
	line-height: 27px;
	font-weight: bold;
	border-color: transparent;
	border-top: 1px solid <?php echo $leftBlockBg;
?>;
}

.block table.second-thead:hover {
	cursor: pointer;
}

.toggleblock {
	border-top: 1px solid <?php echo $leftBlockBg;
?>;
}

.doneblock .toggleblock td, .block .dones td {
	text-decoration: line-through;
	opacity: 0.6;
	/* filter:alpha(opacity=60); */
	-moz-opacity: 0.6;
}

.doneblock .toggleblocks td a, .block .dones td a {
	text-decoration: line-through;
}

.doneblock table tr.acc td, .block .dones td.info, .block .dones td.info a {
	text-decoration: none;
}

.block table td.tools, .block table th.tools, .message .block table td.right {
	border-right: none;
	padding: 0 0 0 9px;
}

.message .block table td.right {
	padding: 0 0 0 6px;
}

.block table tr.acc td {
	height: 0px;
}

.block table tr.acc td .accordion_toggle {
	display: none;
}

.block table tr.acc td {
	padding: 0;
	border: none;
	overflow: hidden;
}

.block table tr.acc td .accordion_content {
	overflow: hidden;
	display: none;
}

.block table tr.acc td .accordion_content .acc-in {

	border-top: 1px solid <?php echo $leftBlockBg;
?>;
	padding: 12px 9px 18px 45px;
	line-height: normal;
	overflow: hidden;
}

.smooth {
	opacity:.6;
	/*filter:alpha(opacity=60);*/
	-moz-opacity:.6;
}

.tablemenue {
	clear: both;
	height: auto;
	border-top: 1px solid #000;
	margin: 1px 0 0 0;
}

.tablemenue-in {
	height: 21px;
	padding: 0 0 0 38px;
}

.block .addmenue, .blockwrapper .addmenue, .addmenue {
	margin: 0 0 1px 0;
	overflow: hidden;
	clear: both;
	width: 100%;
}

.block_in_wrapper {
	padding: 10px 0 10px 44px;
}



/* ## TABLES - COLS ########### ges. 706px ############# */

th.a, td.a {
	width: 31px;
}

th.a img, td.a img {
	float: left;
}

th.b, td.b {
	width: 216px;
}

th.ba, td.ba {
	width: 216px;
}

th.bb, td.bb {
	width: 92px;
}

th.c, td.c {
	width: 190px;
}

th.d, td.d {
	width: 190px;
}

th.cd, td.cd {

}

th.ce, td.ce {
	width: 121px;
}

th.de, td.de {
	width: 121px;
}

th.cf, td.cf {
	width: 91px;
}

th.e, td.e {

}

th.tools, td.tools {
	width: 42px;
}

.user .message col.a {
	width: 180px;
}

.user .message col.b {
	width: 372px;
}

/* ## TABLES ################ Block - Colors ############# */

.projects .headline, .projects a.butn_link, .projects button, .projects a.butn_link_active:hover, .projects .inmenue a span, .projects .moreinfo {
	background: <?php echo $projectsBlockhead;
?>;
}

.projects thead {
	background: <?php echo $projectsTablehead;
?>;
}

.projects .second-thead, .projects .second-thead:hover td, .projects .block_in_wrapper, .projects .inwrapper li:hover, .projects .tableend, .projects .statuswrapper li.link:hover {
	background: <?php echo $projectsTableSecondhead;
?>;
}

.projects .color-a, .projects .statuswrapper li, .projects .datepick table td, .projects .datepick tr.head td {
	background: <?php echo $projectsColorA;
?>;
}

.projects .color-b, .projects .datepick td.wrong, .projects .datepick tr.weekday td {
	background: <?php echo $projectsColorB;
?>;
}

.projects .block, .projects a, .projects h1, .projects .block .tablemenue, .projects .block .addmenue, .projects p.tags-miles {
	border-color: <?php echo $projectsMain;
?>;
	color: <?php echo $projectsMain;
?>;
}

.projects, .projects .block td.finished, .projects .block td.finished a, .projects .datepick .cal, .projects .block_in_wrapper h2 {
	color: <?php echo $projectsMain;
?>;
}

.projects h1 span, .projects h1 span a, .projects .status, .projects .statusbar, .projects .statusbar_b {
	color: <?php echo $projectsB;
?>;
	border-color: <?php echo $projectsB;
?>;
}

.projects .datepick td.wrong {
	color: <?php echo $projectsB;
?>;
}

.projects h1 span a {
	border-color: <?php echo $projectsMain;
?>;
}

.projects form, .projects form input, .projects form select, .projects form textarea, .projects form .row .editor, .projects .statuswrapper li {
	border-color: <?php echo $projectsB;
?>;
	color: <?php echo $projectsMain;
?>;
}

.projects a.butn_link:hover, .projects button:hover, .projects form .fileinput:hover button, .projects a.butn_link_active, .projects .datepick .picker {
	background: <?php echo $projectsC;
?>;
}

/* TASKS COLORS #####################################*/

.tasks .headline, .tasks a.butn_link, .tasks button, .tasks a.butn_link_active:hover, .tasks .inmenue a span, .tasks .moreinfo {
	background: <?php echo $tasksBlockhead;
?>;
}

.tasks thead {
	background: <?php echo $tasksTablehead;
?>;
}

.tasks .second-thead, .tasks .second-thead:hover td, .tasks .block_in_wrapper, .tasks .statuswrapper li.link:hover {
	background: <?php echo $tasksTableSecondhead;
?>;
}

.tasks .color-a, .tasks .datepick table td, .tasks .datepick tr.head td, .tasks .statuswrapper li {
	background: <?php echo $tasksColorA;
?>;
}

.tasks .color-b, .tasks .datepick td.wrong, .tasks .datepick tr.weekday td {
	background: <?php echo $tasksColorB;
?>;
}

.tasks, .tasks a, .tasks h1, .tasks .headline_lone h2 , .tasks .block .tablemenue, .tasks .block .addmenue, .tasks p.tags-miles {
	border-color: <?php echo $tasksMain;
?>;
	color: <?php echo $tasksMain;
?>;
}

.tasks .block td.finished, .tasks .block td.finished a, .tasks .datepick .cal, .tasks .block_in_wrapper h2 {
	color: <?php echo $tasksMain;
?>;
}

.tasks h1 span, .tasks h1 span a, .tasks .datepick td.wrong {
	color: <?php echo $tasksB;
?>;
}

.task h1 span a {
	border-color: <?php echo $tasksMain;
?>;
}

.tasks form, .tasks form input, .tasks form select, .tasks form textarea, .tasks form .row .editor, .tasks .statuswrapper li {
	border-color: <?php echo $tasksB;
?>;
	color: <?php echo $tasksMain;
?>;
}

.tasks a.butn_link:hover, .tasks button:hover, .tasks form .fileinput:hover button, .tasks a.butn_link_active, .tasks .datepick .picker {
	background: <?php echo $tasksC;
?>;
}


/* MESSAGES COLORS #################################*/

.msgs .headline, .msgs a.butn_link, .msgs button, .msgs a.butn_link_active:hover, .msgs .inmenue a span, .msgs .moreinfo, .msgs .inmenue a span, .msgs .moreinfo {
	background: <?php echo $msgsBlockhead;
?>;
}

.msgs thead {
	background: <?php echo $msgsTablehead;
?>;
}

.msgs .second-thead, .msgs .second-thead:hover td, .msgs .block_in_wrapper, .msgs .inwrapper li:hover, .msgs .statuswrapper li.link:hover {
	background: <?php echo $msgsTableSecondhead;
?>;
}

.msgs .color-a, .msgs .statuswrapper li {
	background: <?php echo $msgsColorA;
?>;
}

.msgs .color-b {
	background: <?php echo $msgsColorB;
?>;
}

.msgs .color-a ul.files table, .msgs .color-b ul.files table {
	background: <?php echo $msgsColorC;
?>;
}

.msgs, .msgs .block, .msgs a, .msgs h1, .msgs .block .tablemenue, .msgs .block .addmenue, .msgs p.tags-miles {
	border-color: <?php echo $msgsMain;
?>;
	color: <?php echo $msgsMain;
?>;
}

.msgs h1 span, .msgs h1 span a, .msgs .block_in_wrapper h2 {
	color: <?php echo $msgsB;
?>;
}

.msgs h1 span a {
	border-color: <?php echo $msgsMain;
?>;
}

.msgs form, .msgs form input, .msgs form select, .msgs form textarea, .msgs form .row .editor, .msgs .statuswrapper li {
	border-color: <?php echo $msgsB;
?>;
	color: <?php echo $msgsMain;
?>;
}

.msgs a.butn_link:hover, .msgs button:hover, .msgs button.inner-active, .msgs form .fileinput:hover button, .msgs a.butn_link_active {
	background: <?php echo $msgsC;
?>;
}

/* USER COLORS #################################*/

.user .headline, .user a.butn_link, .user button, .user a.butn_link_active:hover, .user .inmenue a span, .user .moreinfo {
	background: <?php echo $userBlockhead;
?>;
}

.user thead, .user .contenttitle {
	background: <?php echo $userTablehead;
?>;
}

.user .second-thead, .user .second-thead:hover td, .user .block_in_wrapper, .user .inwrapper li:hover, .user .tableend {
	background: <?php echo $userTableSecondhead;
?>;
}

.user .color-a, .user .staterow {
	background: <?php echo $userColorA;
?>;
}

.user .color-b, .user .content_in_wrapper {
	background: <?php echo $userColorB;
?>;
}

.user .color-a ul.files table, .user .color-b ul.files table {
	background: <?php echo $userColorC;
?>;
}

.user .block, .user .blockwrapper, .user a, .user h1, .user .tablemenue, .user .addmenue, .user p.tags-miles, .user .userwrapper, .user .block_in_wrapper h2 {
	border-color: <?php echo $userMain;
?>;
	color: <?php echo $userMain;
?>;
}

.user h1 span, .user h1 span a, .user .inmenue a span {
	color: <?php echo $userB;
?>;
}

.user h1 span a {
	border-color: <?php echo $userMain;
?>;
}

.user form, .user form input, .user form select, .user form textarea, .user form .row .editor {
	border-color: <?php echo $userB;
?>;
	color: <?php echo $userMain;
?>;
}

.user a.butn_link:hover, .user button:hover, .user form .fileinput:hover button, .user a.butn_link_active {
	background: <?php echo $userC;
?>;
}

/* USER PROFILE */

.user .export-main {
	background-image: url(../images_frost/export-vcard.png);
}

.userwrapper {
	float: left;
	border-top: 1px solid;
	border-bottom: 1px solid;
	padding: 1px 0 1px 0;
}

.userwrapper td.avatarcell {
	background: <?php echo $userColorA;
?>;
	border-right: 1px solid <?php echo $leftBlockBg ?>;
	padding: 8px 8px 0 8px;
}

/* NEUTRAL COLORS #################################*/

.neutral .headline, .neutral a.butn_link, .neutral button, .neutral a.butn_link_active:hover {
	background: <?php echo $neutralBlockhead;
?>;
}

.neutral thead, .neutral .contenttitle {
	background: <?php echo $neutralTablehead;
?>;
}

.neutral .second-thead, .neutral .second-thead:hover td, .neutral .block_in_wrapper, .neutral .inwrapper li:hover, .neutral .tableend, .neutral .paging {
	background: <?php echo $neutralTableSecondhead;
?>;
}

.neutral .color-a, .neutral .staterow {
	background: <?php echo $neutralColorA;
?>;
}

.neutral .color-b, .neutral .content_in_wrapper {
	background: <?php echo $neutralColorB;
?>;
}

.neutral .color-a ul.files table, .neutral .color-b ul.files table {
	background: <?php echo $neutralColorC;
?>;
}

.neutral .block, .neutral a, .neutral h1, .neutral .block .tablemenue, .neutral .block .addmenue, .neutral p.tags-miles, .neutral .block_in_wrapper h2 {
	border-color: <?php echo $neutralMain;
?>;
	color: <?php echo $neutralMain;
?>;
}

.neutral h1 span, .neutral h1 span a {
	color: <?php echo $neutralB;
?>;
}

.neutral h1 span a {
	border-color: <?php echo $neutralMain;
?>;
}

.neutral form, .neutral form input, .neutral form select, .neutral form textarea, .neutral form .row .editor {
	border-color: <?php echo $neutralB;
?>;
	color: <?php echo $neutralMain;
?>;
}

.neutral a.butn_link:hover, .neutral button:hover, .neutral form .fileinput:hover button, .neutral a.butn_link_active, .neutral .datepick .picker {
	background: <?php echo $neutralC;
?>;
}

/* TIMETRACKING COLORS #################################*/

.timetrack .headline, .timetrack a.butn_link, .timetrack button, .timetrack a.butn_link_active:hover {
	background: <?php echo $timetrackBlockhead;
?>;
}

.timetrack thead {
	background: <?php echo $timetrackTablehead;
?>;
}

.timetrack .second-thead, .timetrack .second-thead:hover td, .timetrack .block_in_wrapper, .timetrack .inwrapper li:hover, .timetrack .tableend {
	background: <?php echo $timetrackTableSecondhead;
?>;
}

.timetrack .color-a, .timetrack .datepick table td, .timetrack .datepick tr.head td {
	background: <?php echo $timetrackColorA;
?>;
}

.timetrack .color-b, .timetrack .datepick td.wrong, .timetrack .datepick tr.weekday td {
	background: <?php echo $timetrackColorB;
?>;
}

.timetrack .color-a ul.files table, .timetrack .color-b ul.files table {
	background: <?php echo $timetrackColorC;
?>;
}

.timetrack .block, .timetrack a, .timetrack h1, .timetrack .block .tablemenue, .timetrack .block .addmenue, .timetrack p.tags-miles {
	border-color: <?php echo $timetrackMain;
?>;
	color: <?php echo $timetrackMain;
?>;
}

.timetrack .datepick .cal, .timetrack .block_in_wrapper h2 {
	color: <?php echo $timetrackMain;
?>;
}

.timetrack h1 span, .timerack h1 span a, .timetrack .datepick td.wrong {
	color: <?php echo $timetrackB;
?>;
}

.timetrack h1 span a {
	border-color: <?php echo $timetrackMain;
?>;
}

.timetrack form, .timetrack form input, .timetrack form select, .timetrack form textarea, .timetrack form .row .editor {
	border-color: <?php echo $timetrackB;
?>;
	color: <?php echo $timetrackMain;
?>;
}

.timetrack a.butn_link:hover, .timetrack button:hover, .timetrack form .fileinput:hover button, .timetrack a.butn_link_active, .timetrack .datepick .picker {
	background: <?php echo $timetrackC;
?>;
}

/* MILESTONES COLORS #################################*/

.miles .headline, .miles a.butn_link, .miles button, .miles a.butn_link_active:hover, .miles .calinmenue ul {
	background: <?php echo $milesBlockhead;
?>;
}

.miles thead, .miles .calhead th {
	background: <?php echo $milesTablehead;
?>;
}

.miles .second-thead, .miles .block_in_wrapper, .miles .inwrapper li:hover, .miles .tableend, .miles .thecal, .miles .second-thead:hover td, .miles .statuswrapper li.link:hover {
	background: <?php echo $milesTableSecondhead;
?>;
}

.miles .color-a, .miles table.thecal .dayhead th, .miles .calinmenue ul li.link a, .miles .datepick table td, .miles .datepick tr.head td, .miles .statuswrapper li {
	background: <?php echo $milesColorA;
?>;
}

.miles .color-b, .miles .calinmenue ul li.link a:hover, .miles .datepick td.wrong, .miles .datepick tr.weekday td, .miles .content_in_wrapper {
	background: <?php echo $milesColorB;
?>;
}

.miles .color-a ul.files table, .miles .color-b ul.files table {
	background: <?php echo $milesColorC;
?>;
}

.miles, .miles a, .miles h1, .miles .block .tablemenue, .miles .block .addmenue, .miles p.tags-miles, .bigcal tbody.content td {
	border-color: <?php echo $milesMain;
?>;
	color: <?php echo $milesMain;
?>;
}

.miles .block td.finished, .miles .block td.finished a, .miles .datepick .cal, .miles .block_in_wrapper h2 {
	color: <?php echo $milesMain;
?>;
}

.miles h1 span, .miles h1 span a, .miles .block td.othermonth a, .miles .block td.othermonth, .miles .datepick td.wrong {
	color: <?php echo $milesB;
?>;
}

.miles h1 span a, .miles .content-spacer-b {
	border-color: <?php echo $milesMain;
?>;
}

.miles form, .miles form input, .miles form select, .miles form textarea, .miles form .row .editor, .miles .statuswrapper li {
	border-color: <?php echo $milesB;
?>;
	color: <?php echo $milesMain;
?>;
}

.miles a.butn_link:hover, .miles button:hover, .miles form .fileinput:hover button, .miles a.butn_link_active, .miles .datepick .picker {
	background: <?php echo $milesC;
?>;
}

/* FILES COLORS #####################################*/

.files .headline, .files a.butn_link, .files button, .files a.butn_link_active:hover, .files .inmenue a span, .files .moreinfo {
	background: <?php echo $filesBlockhead;
?>;
}

.files thead, .files .contenttitle {
	background: <?php echo $filesTablehead;
?>;
}

.files .second-thead, .files .second-thead:hover td, .files .block_in_wrapper, .files .inwrapper li:hover {
	background: <?php echo $filesTableSecondhead;
?>;
}

.files .color-a, .files .datepick table td, .files .datepick tr.head td, .files .staterow {
	background: <?php echo $filesColorA;
?>;
}

.files .color-b, .files .datepick td.wrong, .files .datepick tr.weekday td, .files .content_in_wrapper {
	background: <?php echo $filesColorB;
?>;
}

.files .block, .files .blockwrapper, .files a, .files h1, .files .tablemenue, .files .addmenue, .files p.tags-miles {
	border-color: <?php echo $filesMain;
?>;
	color: <?php echo $filesMain;
?>;
}

.files .block td.finished, .files .block td.finished a, .files .datepick .cal, .files .block_in_wrapper h2 {
	color: <?php echo $filesMain;
?>;
}

.files h1 span, .files h1 span a, .files .datepick td.wrong {
	color: <?php echo $filesB;
?>;
}

.files h1 span a {
	border-color: <?php echo $filesMain;
?>;
}

.files form, .files form input, .files form select, .files form textarea, .files form .row .editor {
	border-color: <?php echo $filesB;
?>;
	color: <?php echo $filesMain;
?>;
}

.files a.butn_link:hover, .files button:hover, .files form .fileinput:hover button, .files a.butn_link_active, .files .datepick .picker {
	background: <?php echo $filesC;
?>;
}

/* ## ACCORDION ################ Toggle ############### */

span.acc-toggle, span.acc-toggle-active, .second-thead span.acc-toggle, .second-thead span.acc-toggle-active {
	display: block;
	width: 97%;
	height: 100%;
	background: url(../images_frost/acc-open.png) no-repeat right 10px;
	cursor: pointer;

}

.second-thead span.acc-toggle, .second-thead span.acc-toggle-active { /* for darker backgrounds */
	background-image: url(../images_frost/acc-open-b.png);
}

span.acc-toggle:hover, .second-thead:hover span.acc-toggle {
	background-position: right -15px;
}

span.acc-toggle-active, .second-thead span.acc-toggle-active {
	background-position: right -40px;
}

span.acc-toggle-active:hover, .second-thead:hover span.acc-toggle-active  {
	background-position: right -65px;
}

.toggle-in {
	position: relative;
	width: 100%;
	height: 100%;
}

.toggle-in a, .toggle-in.acc-toggle-active a {
	display: block;
	height: 27px;
	position: absolute;
	top: 0;
	left: 0;
	z-index: 1;
}



/* ## ACCORDION ################ Tools ############### */

a.butn_check, a.butn_checked, a.butn_reply {
	display: block;
	width: 100%;
	height: 100%;
	background: url(../images_frost/butn-check.png) no-repeat 4px 6px;
}

a.butn_check:hover, a.butn_checked, a.butn_reply:hover {
	background-position: 4px -20px;
}

a.butn_checked:hover {
	background-position: 4px 6px;
}

a.butn_reply {
	background-image: url(../images_frost/butn-reply.png);
}

a.tool_edit, a.tool_del {
	display: block;
	width: 14px;
	height: 23px;
	float: left;
	margin-right: 4px;
	background: url(../images_frost/butn-edit.png) no-repeat 0 4px;
}

a.tool_del {
	background-image: url(../images_frost/butn-del.png);
	margin: 0;
}

a.tool_edit:hover, a.tool_del:hover {
	background-position: 0 -22px;
}

/* ## ACCORDION ############### Marker ############## */

.marker-late, .marker-late a {
	color: <?php echo $red;
?>;
}

.marker-today, .marker-today a {
	color: <?php echo $green;
?>;
}

.green, .green a {
	color: <?php echo $green;
?>;
	border-color: <?php echo $green;
?>;
	background-color:<?php echo $greenbg;
?>;
}

.red, .red a {
	color: <?php echo $red;
?>;
	border-color: <?php echo $red;
?>;
	background-color:<?php echo $redbg;
?>;
}

/* ## Headlines ## start ## */

h1 {
	font-size: 21px;
	margin:0 0 0px 0px;
	height: 25px;
	line-height: 21px;
}

#content-left h1 {
	width: 669px;
	overflow: hidden;
}

h1.head {
	font-size: 24pt;
	margin: 0 0 0px 0;
	color: white;
	height: 35px;
}

h1 span {
	font-size:11pt;
	margin-left:6px;
}

h2 {
	font-size:11pt;
	margin:0 0 10px 0;
}

h2.head {
	font-size:11pt;
	margin:0 0 10px 0;
	color:white;
	font-weight:normal;
}

/* ## Infos ## start ## */

span.info {
	font-size: 12px;
}

.infowin_left {
	position: relative;
	top: -72px;
	right: 0;
	height: 40px;
	margin: 0 0 -40px 0;
	font-size: 12px;
	font-weight: bold;
	float: right;
}
.infowin_left img {
   	float:left;
	position: relative;
	top: -8px;
	margin: 0 0 -12px 0;
}
.info_in_red, .info_in_green, .info_in_yellow {
	padding: 10px 10px 8px 4px;
	border: 1px solid #fff;
	float: right;
	color: #fff;
	background: url(../images_frost/infowin_red.png) repeat;
	line-height: 22px;
	-moz-border-radius: 6px;
	-webkit-border-radius: 6px;
	border-radius: 6px;
}
.info_in_green {
	background: url(../images_frost/infowin_green.png) repeat;
}
.info_in_yellow {
	background: url(../images_frost/infowin_yellow.png) repeat;
}

/* ## Footer ## start ## */

#footer-wrapper {
	clear: both;
	width: 100%;
	min-width: 980px;
	height: 26px;
	font-size: 9pt;
	margin-top: -26px;
}

.footer {
	width: 980px;
	margin: 0 auto 0 auto;
}

.footer-in {
	padding: 7px 0 0 2px;
	color: #6ab0c5;
}

.footer a {
	color: #6ab0c5;
}



/* ## Footer ## end ## */