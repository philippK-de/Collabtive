{if $showhtml != "no"}

{include file="header.tpl" jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" taskstab="active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="tasks">

			<div class="breadcrumb">
				<a href="manageproject.php?action=showproject&amp;id={$project.ID}" title="{$projectname}">
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />
					{$projectname|truncate:25:"...":true}
				</a>
				<a href="managetask.php?action=showproject&amp;id={$project.ID}">
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt="" />
					{#tasklists#}
				</a>
				<a href="managetasklist.php?action=showtasklist&id={$project.ID}&tlid={$task.liste}" title="{#tasklist#} / {$task.list}">
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt="" />
					{$task.list|truncate:25:"...":true}
				</a>
				<a href="managetask.php?action=showtask&amp;tid={$task.ID}&amp;id={$project.ID}" title="{#task#} / {$task.title}">
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt="" />
					{$task.title|truncate:50:"...":true}
				</a>
				<span>&nbsp;/...</span>
			</div>

			<h1 class="second">
				<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png" alt="" />
				{$task.title|truncate:30:"...":true}
			</h1>

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

				<h2>{$langfile.edittask}</h2>

				<form novalidate class="main" method="post" action="managetask.php?action=edit&amp;tid={$task.ID}&amp;id={$pid}" {literal} onsubmit="return validateCompleteForm(this);" {/literal} >
					<fieldset>

						<div class="row">
							<label for="title">{$langfile.title}:</label>
							<input type="text" class="text" value="{$task.title}" name="title" id="title" realname="{$langfile.title}" required="1" />
						</div>

						<div class="row">
							<label for="text">{$langfile.text}:</label>
							<div class="editor">
								<textarea name="text" id="text" rows="3" cols="0">{$task.text}</textarea>
							</div>
						</div>


						<div class="row">
							<label for="start">{$langfile.start}:</label>
							<input type="text" class="text" value="{$task.startstring}" name="start" id="start_task{$task.ID}" />
						</div>

						<div class="datepick">
							<div id="datepicker_task_start" class="picker" style="display:none;"></div>
						</div>

						<script type="text/javascript">
						  	theCalStart{$lists[list].ID} = new calendar({$theM},{$theY});
							theCalStart{$lists[list].ID}.dayNames = ["{$langfile.monday}","{$langfile.tuesday}","{$langfile.wednesday}","{$langfile.thursday}","{$langfile.friday}","{$langfile.saturday}","{$langfile.sunday}"];
							theCalStart{$lists[list].ID}.monthNames = ["{$langfile.january}","{$langfile.february}","{$langfile.march}","{$langfile.april}","{$langfile.may}","{$langfile.june}","{$langfile.july}","{$langfile.august}","{$langfile.september}","{$langfile.october}","{$langfile.november}","{$langfile.december}"];
							theCalStart{$lists[list].ID}.dateFormat = "{$settings.dateformat}";
							theCalStart{$lists[list].ID}.relateTo = "start_task{$task.ID}";
							theCalStart{$lists[list].ID}.getDatepicker("datepicker_task_start");
						</script>


						<div class="row">
							<label for="end">{$langfile.end}:</label>
							<input type="text" class="text" value="{$task.endstring}" name="end" id="end_task{$task.ID}" />
						</div>

						<div class="datepick">
							<div id="datepicker_task_end" class="picker" style="display:none;"></div>
						</div>

						<script type="text/javascript">
						  	theCalEnd{$lists[list].ID} = new calendar({$theM},{$theY});
							theCalEnd{$lists[list].ID}.dayNames = ["{$langfile.monday}","{$langfile.tuesday}","{$langfile.wednesday}","{$langfile.thursday}","{$langfile.friday}","{$langfile.saturday}","{$langfile.sunday}"];
							theCalEnd{$lists[list].ID}.monthNames = ["{$langfile.january}","{$langfile.february}","{$langfile.march}","{$langfile.april}","{$langfile.may}","{$langfile.june}","{$langfile.july}","{$langfile.august}","{$langfile.september}","{$langfile.october}","{$langfile.november}","{$langfile.december}"];
							theCalEnd{$lists[list].ID}.dateFormat = "{$settings.dateformat}";
							theCalEnd{$lists[list].ID}.relateTo = "end_task{$task.ID}";
							theCalEnd{$lists[list].ID}.getDatepicker("datepicker_task_end");
						</script>


						<div class="row">
							<label for="tasklist">{$langfile.tasklist}:</label>
							<select name="tasklist" class="select" id="tasklist" required="1" realname="{$langfile.tasklist}">
								{section name=tasklist loop=$tasklists}
									<option value="{$tasklists[tasklist].ID}" {if $task.listid == $tasklists[tasklist].ID} selected="selected" {/if} >
										{$tasklists[tasklist].name}
									</option>
								{/section}
							</select>
						</div>

		                <div class="row">
		                    <label for="assigned">{$langfile.assignto}:</label>
		                    <select name="assigned[]" multiple="multiple" style="height:80px;" id="assigned" required="1" exclude="-1" realname="{$langfile.assignto}">
		                        <option value="-1">{$langfile.chooseone}</option>
		                        {section name=member loop=$members}
		                          	<option value="{$members[member].ID}" {if in_array($members[member].ID, $task.users)} selected="selected" {/if} >
		                          		{$members[member].name}
		                          	</option>
		                        {/section}
		                    </select>
		                </div>

						<div class="row-butn-bottom">
							<label>&nbsp;</label>
							<button type="submit" onfocus="this.blur();">{$langfile.send}</button>
							<button type="reset" onclick="blindtoggle('form_edit');toggleClass('edit_butn','edit-active','edit');toggleClass('sm_task','smooth','nosmooth');return false;" onfocus="this.blur();" {if $showhtml != "no"}style="display:none;"{/if}>{$langfile.cancel}</button>
						</div>

					</fieldset>
				</form>

			</div> {* block_in_wrapper END *}

{if $showhtml != "no"}

			<div class="content-spacer"></div>

		</div> {* tasks END *}
	</div> {* content-left-in END *}
</div> {* content-left END *}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}

{/if}