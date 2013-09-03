{config_load file='lng.conf' section = "strings" scope="global" }
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title>{$title} @ {$settings.name}</title>
<link rel="shortcut icon" href="templates/standard/images/favicon.ico" type="image/x-icon" />
{if $stage|default != "project" and $loggedin|default}
<link rel="search" type="application/opensearchdescription+xml" title="{$settings.name} {#search#}" href="manageajax.php?action=addfx-all" />
{elseif $stage|default == "project" and $loggedin}
<link rel="search" type="application/opensearchdescription+xml" title="{$project.name} {#search#}" href="manageajax.php?action=addfx-project&amp;project={$project.ID}" />
{/if}
{if $loggedin}
<link rel="alternate" type="application/rss+xml" title="{#mymessages#}" href="managerss.php?action=mymsgs-rss&amp;user={$userid}" />
<link rel="alternate" type="application/rss+xml" title="{#mytasks#}" href="managerss.php?action=rss-tasks&amp;user={$userid}" />
{/if}
{if $jsload|default == "ajax"}
{literal}

<script type = "text/javascript">
//endcolor for close element flashing
closeEndcolor = '#377814';
//endcolor for delete element flashing
deleteEndcolor = '#c62424';
</script>
<script type = "text/javascript" src = "include/js/prototype.php" ></script>
<script type = "text/javascript" src = "include/js/ajax.php" ></script>
<script type = "text/javascript" src="include/js/jsval.php"></script>
<script type="text/javascript" src="include/js/chat.js"></script>
     <script type = "text/javascript">
        function _jsVal_Language() {
            this.err_enter = "{/literal}{#wrongfield#}{literal}";
            this.err_form = "{/literal}{#wrongfields#}{literal}";
            this.err_select = "{/literal}{#wrongselect#}{literal}";
        }
</script>

<script type="text/javascript" src="include/js/mycalendar.js"></script>
{/literal}
{/if}
{if $jsload2|default == "chat"}
{literal}
<script type="text/javascript">
window.onunload = quitchat;

</script>
{/literal}
{/if}

{if $jsload3|default == "lightbox"}
<link rel="stylesheet" href="templates/standard/css/lytebox.css" type="text/css"  />
<script type="text/javascript" src="include/js/lytebox.php"></script>
{/if}
<link rel="stylesheet" type="text/css" href="templates/standard/css/style_main.css"/>


{if $jsload1|default == "tinymce"}
{literal}
<script type="text/javascript" src="include/js/tiny_mce/tiny_mce.js"></script>

<script type="text/javascript">
//	theme_advanced_statusbar_location : "bottom",
tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	language: "{/literal}{$locale}{literal}",
	width: "400px",
	height: "250px",
	plugins : "inlinepopups,style,advimage,advlink,xhtmlxtras,safari,template",
	theme_advanced_buttons1 : "bold,italic,underline,|,fontsizeselect,forecolor,|,bullist,numlist,|,link,unlink,image",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path : false,
	extended_valid_elements : "a[name|href|target|title],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|name],font[face|size|color|style],span[class|align|style]",
    theme_advanced_statusbar_location: "bottom",
    theme_advanced_resizing : true,
	theme_advanced_resizing_use_cookie : false,
	theme_advanced_resizing_min_width : "400px",
	theme_advanced_resizing_max_width : "600px",
	theme_advanced_resize_horizontal : false,
	force_br_newlines : true,
	cleanup: true,
	cleanup_on_startup: true,
	force_p_newlines : false,
	convert_newlines_to_brs : false,
	forced_root_block : false,
	external_image_list_url: 'manageajax.php?action=jsonfiles&id={/literal}{$project.ID|default}{literal}'

});

</script>
{/literal}
{/if}
</head>
<body >

<!--<div id = "jslog" style = "color:red;position:absolute;top:60%;right:5%;width:300px;border:1px solid;background-color:grey;"></div>-->

{if $showheader|default != "no"}
	{include file="header_main.tpl"}
{/if}
