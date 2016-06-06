{if $showhtml != "no"}
{include file="header.tpl" jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" msgstab="active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="msgs">
			<div class="breadcrumb">
				<a href="manageproject.php?action=showproject&amp;id={$project.ID}" title="{$project.name}"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />{$project.name|truncate:25:"...":true}</a>
				<a href="managetask.php?action=showproject&amp;id={$project.ID}"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt="" />{#messages#}</a>
				<a href="managetasklist.php?action=showtasklist&amp;id={$project.ID}&amp;tlid={$tasklist.ID}"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt="" />{$message.name|truncate:50:"...":true}</a><span>&nbsp;/...</span>
			</div>

			<h1 class="second"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt="" />{$message.name}</h1>
{/if}
	{if $async == "yes"}
				{literal}
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
							external_image_list_url: 'manageajax.php?action=jsonfiles&id={/literal}{$project.ID}{literal}'
						});
					</script>
				{/literal}
			{/if}
			<div class="block_in_wrapper">
				<h2>{#answer#}</h2>

				<form class="main" method="post" action="managemessage.php?action=reply&amp;id={$project.ID}" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
					<fieldset>

						<div class="row">
							<label for="title">{$langfile.title}:</label>
							<input type="text" name="title" id="title" required="1" realname="{#title#}" value="Re: {$message.title}" />
						</div>
						<div class="row">
							<label for="text">{$langfile.text}:</label>
							<div class="editor">
								<textarea name="text" id="text" realname="{#text#}" rows="3" cols="1"></textarea>
							</div>
						</div>

						<div class="row">
						{*Attach*}
						<div id="files-attach" class="blinded">
							<div class="row">
								<label for="thefiles">{$langfile.attachfile}:</label>
								<select name="thefiles" id="thefiles">
									<option value="0">{$langfile.chooseone}</option>
									{section name=file loop=$files}
										<option value="{$files[file].ID}">{$files[file].name}</option>
									{/section}
									{section name=file loop=$myprojects[project].files}
										<option value="{$myprojects[project].files[file].ID}">{$myprojects[project].files[file].name}</option>
									{/section}
								</select>
							</div>
						</div>

						</div>


						<div class="row">
							<label>{$langfile.notify}:</label>
							<select name="sendto[]" multiple style="height:100px;">
								<option value="" disabled style="color:black;font-weight:bold;">{$langfile.general}</option>
								<option value="all" selected>{$langfile.all}</option>
								<option value="none">{$langfile.none}</option>
								<option value="" disabled style="color:black;font-weight:bold;">{$langfile.members}</option>
								{section name=member loop=$members}
									<option value="{$members[member].ID}">{$members[member].name}</option>
								{/section}
							</select>
						</div>

						<input type="hidden" name="desc" id="desc" value="" />
						<input type="hidden" value="{$message.ID}" name="mid" />

						<div class="row-butn-bottom">
							<label>&nbsp;</label>
							<button type="submit" onfocus="this.blur()" >{$langfile.send}</button>
							{if $showhtml == "no"}
								{if $reply != "a"}
									<button type = "reset" onclick="blindtoggle('addmsg');toggleClass('add_replies','add-active','add');toggleClass('butn_reply','butn_reply_active','butn_reply');toggleClass('sm_replies','smooth','nosmooth');return false;" onfocus="this.blur()">{$langfile.cancel}</button>
								{/if}
							{/if}
							{if $reply == "a"}
								<button type = "reset" onclick="blindtoggle('form_reply_a');toggleClass('add_reply_a','reply-active','reply');toggleClass('sm_replies_a','smooth','nosmooth');return false;" onfocus="this.blur()">{$langfile.cancel}</button>
							{/if}
						</div>

					</fieldset>
				</form>
			</div> {*block_in_wrapper end*}

{if $showhtml != "no"}
			<div class="content-spacer"></div>
		</div> {*Msgs END*}
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}
{/if}