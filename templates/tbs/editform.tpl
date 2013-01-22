{if $showhtml != "no"}
{include file="header.tpl"  jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" projecttab="active"}
<div class="row-fluid">
	<div id="content-left" class="span9">
		<div class="projects">
			<div class="breadcrumb">
				<a href="manageproject.php?action=showproject&amp;id={$project.ID}" title="{$project.name}"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$project.name|truncate:50:"...":true}</a>
			</div>
			<h1 class="second"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$project.name}</h1>
{/if}

			<div class="block_in_wrapper">
				<form novalidate class="main form-horizontal" method="post" action="manageproject.php?action=edit&amp;id={$project.ID}" {literal}onsubmit="return validateCompleteForm(this,'input_error');"{/literal}>
				<fieldset>
					<legend>{#editproject#}</legend>
					<div class="control-group">
						<label class="control-label" for="name">{#name#}:</label>
						<div class="controls">
							<input type="text" class="text" name="name" id="name" required="1" realname="{#name#}" value = "{$project.name}" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="desc">{#description#}:</label>
						<div class="controls">
							<div class="editor"><textarea name="desc" id="desc"  rows="3" cols="1">{$project.desc}</textarea></div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="budget">{#budget#}:</label>
						<div class="controls">
							<input type="text" class="text" name="budget" id="budget"  realname="{#budget#}" value = "{$project.budget}" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="end">{#due#}:</label>
						<div class="controls">
							<input type="text" class="text" value="{$project.endstring}" name="end"  id="end"  {if $project.end == 0}disabled = "disabled"{/if} realname="{#due#}" />
						</div>
						<label class="control-label" for="neverdue">{#neverdue#}</label>
						<div class="controls">
							<input type = "checkbox" value="neverdue" name="neverdue" id="neverdue" {if $project.end == 0}checked="checked" onclick="$('end').enable();"{else}onclick = "$('end').value='';$('end').disabled='disabled';"{/if}>
						</div>
					</div>
					<div class="datepick control-group">
						<div id="datepicker_project" class="picker" style="display:none;"></div>
					</div>
					<div class="row-butn-bottom">
						<button type="submit" class="btn btn-primary" onfocus="this.blur();">{#send#}</button>
						<button class="btn btn-danger" type="reset" onclick="blindtoggle('form_edit');toggleClass('edit_butn','edit-active','edit');toggleClass('sm_project','smooth','nosmooth');toggleClass('sm_project_desc','smooth','nosmooth');return false;" onfocus="this.blur();" {if $showhtml != "no"} style="display:none;"{/if}>{#cancel#}</button>
					</div>
				</fieldset>
				</form>
<script type="text/javascript">
	theCal{$lists[list].ID} = new calendar({$theM},{$theY});
	theCal{$lists[list].ID}.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
	theCal{$lists[list].ID}.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
	theCal{$lists[list].ID}.relateTo = "end";
	theCal{$lists[list].ID}.getDatepicker("datepicker_project");
</script>
			</div> {*block_in_wrapper end*}
{if $showhtml != "no"}
		</div> {*projects END*}
	</div>
	{include file="sidebar-a.tpl"}
</div>
{include file="footer.tpl"}
{/if}