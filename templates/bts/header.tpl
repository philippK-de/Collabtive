{strip}
{config_load file=lng.conf section="strings" scope="global" }
{/strip}<!DOCTYPE html>
<html lang="{$locale}">
	<head>
		<meta charset='utf-8' />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!--[if lt IE 9]>
		<script src='//html5shim.googlecode.com/svn/trunk/html5.js'></script>
		<![endif]-->
		<link rel='stylesheet' type='text/css' href='templates/idt/css/bootstrap.min.css' />
		<link rel='stylesheet' type='text/css' href='templates/idt/css/bootstrap-responsive.min.css' />
		<link rel='stylesheet' type='text/css' href='templates/idt/css/datepicker.css' />
		<link rel='stylesheet' type='text/css' href='templates/idt/css/theme.css' />
		<script src='http://code.jquery.com/jquery-latest.js'></script>
		<script src='templates/idt/js/bootstrap.min.js'></script>
		<script src='templates/idt/js/bootstrap-datepicker.js'></script>
		<script src='templates/idt/js/app.js'></script>
		<title>{$title} @ {$settings.name}</title>
		<link rel='shortcut icon' href='templates/standard/images/favicon.ico' type='image/x-icon' />
	{if $stage != "project" and $loggedin}
		<link rel='search' type='application/opensearchdescription+xml' title='{$settings.name} {#search#}' href='manageajax.php?action=addfx-all' />
	{elseif $stage == "project" and $loggedin}
		<link rel='search' type='application/opensearchdescription+xml' title='{$project.name} {#search#}' href='manageajax.php?action=addfx-project&amp;project={$project.ID}' />
	{/if}
	{if $loggedin}
		<link rel='alternate' type='application/rss+xml' title='{#mymessages#}' href='managerss.php?action=mymsgs-rss&amp;user={$userid}' />
		<link rel='alternate' type='application/rss+xml' title='{#mytasks#}' href='managerss.php?action=rss-tasks&amp;user={$userid}' />
	{/if}

	{if $jsload == "ajax"}
	{literal}
		<script>
			//endcolor for close element flashing
			closeEndcolor = '#377814';
			//endcolor for delete element flashing
			deleteEndcolor = '#c62424';
		</script>
		<script src='include/js/prototype.php' ></script>
		<script src='include/js/ajax.php' ></script>
		<script src='include/js/jsval.php'></script>
		<script src='include/js/chat.js'></script>
		<script>
			function _jsVal_Language() {
				this.err_enter = "{/literal}{#wrongfield#}{literal}";
				this.err_form = "{/literal}{#wrongfields#}{literal}";
				this.err_select = "{/literal}{#wrongselect#}{literal}";
			}
		</script>
		<script src='include/js/mycalendar.js'></script>
	{/literal}
	{/if}
		
	{if $jsload2 == "chat"}
	{literal}
		<script>
			window.onunload = quitchat;
		</script>
	{/literal}
	{/if}

	{if $jsload3 == "lightbox"}
		<!-- add something else here -->
	{/if}
	{if $jsload1 == "tinymce"}
	{literal}
		<script type="text/javascript" src="include/js/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript">
			//	theme_advanced_statusbar_location : "bottom",
			tinyMCE.init({
				mode: "textareas",
				theme: "advanced",
				skin: "o2k7",
				skin_variant: "silver",
				language: "{/literal}{$locale}{literal}",
				plugins: 'autoresize',
				width: '100%',
				height: 400,
				autoresize_min_height: 400,
				autoresize_max_height: 800,
				plugins: "inlinepopups,style,advimage,advlink,xhtmlxtras,safari,template",
				theme_advanced_buttons1: "bold,italic,underline,|,formatselect,fontsizeselect,forecolor,|,bullist,numlist,|,link,unlink,image,|undo,redo",
				theme_advanced_buttons2: "",
				theme_advanced_buttons3: "",
				theme_advanced_toolbar_location: "top",
				theme_advanced_toolbar_align: "left",
				theme_advanced_path: false,
				extended_valid_elements: "a[name|href|target|title],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|name],font[face|size|color|style],span[class|align|style]",
				theme_advanced_statusbar_location: "bottom",
				theme_advanced_resizing: true,
				theme_advanced_resizing_use_cookie: false,
				theme_advanced_resizing_min_width: 400,
				theme_advanced_resizing_max_width: 800,
				theme_advanced_resize_horizontal: false,
				force_br_newlines: true,
				cleanup: true,
				cleanup_on_startup: true,
				force_p_newlines: false,
				convert_newlines_to_brs: false,
				forced_root_block: false,
				external_image_list_url: 'manageajax.php?action=jsonfiles&id={/literal}{$project.ID}{literal}'

			});
		</script>
	{/literal}
	{/if}
	</head>
	<body data-spy="scroll" data-target=".bs-sidebar">
	{if $showheader != "no"}
		{include file="header_main.tpl"}
	{/if}
