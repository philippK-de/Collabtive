{if $showhtml != "no"}

{include file="header.tpl" jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" projecttab="active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="projects">

			<div class="breadcrumb">
				<a href="manageproject.php?action=showproject&amp;id={$project.ID}" title="{$project.name}"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />{$project.name|truncate:50:"...":true}</a>
				<span>&nbsp;/...</span>
			</div>

			<h1 class="second">
				<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />
				{$project.name}
			</h1>

{/if}

{if $async|default == "yes"}

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

				<h2>{$langfile.editproject}</h2>

				<form novalidate class="main" method="post" action="manageproject.php?action=edit&amp;id={$project.ID}" {literal}onsubmit="return validateCompleteForm(this,'input_error');"{/literal}>
					<fieldset>

						<div class="row">
							<label for="name">{$langfile.name}:</label>
							<input type="text" class="text" name="name" id="name" required="1" realname="{$langfile.name}" value="{$project.name}" />
						</div>

						<div class="row">
							<label for="desc">{$langfile.description}:</label>
							<div class="editor">
								<textarea name="desc" id="desc" rows="3" cols="1">{$project.desc}</textarea>
							</div>
						</div>

						<div class="row">
							<label for="budget">{$langfile.budget}:</label>
							<input type="text" class="text" name="budget" id="budget" realname="{$langfile.budget}" value="{$project.budget}" />
						</div>

						<div class="row">
							<label for="end">{$langfile.due}:</label>
							<input type="text" class="text" value="{$project.endstring}" name="end" id="end" {if $project.end == 0} disabled="disabled" {/if} realname="{$langfile.due}" />
						</div>
						<div class="row">
							<label for="neverdue"></label>
							<input type="checkbox" class="checkbox" value="neverdue" name="neverdue" id="neverdue" {if $project.end == 0} checked = "checked" {/if} onclick="$('end').value='';$('end').disabled=!$('end').disabled;">
							<label>{$langfile.neverdue}</label>
						</div>
                        <div class="row">
                            <label for="changeallduedates"></label>
                            <input type="checkbox" class="checkbox" name="changeallduedates" id="changeallduedates" />
                            <label>{$langfile.changeallduedates}</label>
                        </div>

						<div class="datepick">
							<div id="datepicker_project" class="picker display-none"></div>
						</div>

						<script type="text/javascript">
							theCal{$lists[list].ID} = new calendar({$theM},{$theY});
							theCal{$lists[list].ID}.dayNames = ["{$langfile.monday}","{$langfile.tuesday}","{$langfile.wednesday}","{$langfile.thursday}","{$langfile.friday}","{$langfile.saturday}","{$langfile.sunday}"];
							theCal{$lists[list].ID}.monthNames = ["{$langfile.january}","{$langfile.february}","{$langfile.march}","{$langfile.april}","{$langfile.may}","{$langfile.june}","{$langfile.july}","{$langfile.august}","{$langfile.september}","{$langfile.october}","{$langfile.november}","{$langfile.december}"];
							theCal{$lists[list].ID}.dateFormat = "{$settings.dateformat}";
							theCal{$lists[list].ID}.relateTo = "end";
							theCal{$lists[list].ID}.getDatepicker("datepicker_project");
						</script>

						<div class="row-butn-bottom">
							<label>&nbsp;</label>
							<button type="submit" onfocus="this.blur();">{$langfile.send}</button>
							<button type="button" onclick="{if $projectov == "no"}blindtoggle('form_edit'); toggleClass('edit_butn','edit-active','edit');toggleClass('sm_project','smooth','nosmooth');toggleClass('sm_project_desc','smooth','nosmooth');{else};blindtoggle('form_addmyproject');{/if} return false;" onfocus="this.blur();" {if $showhtml != "no"} style="display:none;"{/if}>{$langfile.cancel}</button>
						</div>

					</fieldset>
				</form>

			</div> {*block_in_wrapper end*}

{if $showhtml != "no"}

			<div class="content-spacer"></div>

		</div> {*Projects END*}
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}

{/if}