{if $showhtml != "no"}
{include file="header.tpl"  jsload = "ajax" jsload1 = "tinymce"}



{include file="tabsmenue-project.tpl" projecttab = "active"}
<div id="content-left">
<div id="content-left-in">
<div class="projects">

<div class="breadcrumb">
<a href="manageproject.php?action=showproject&amp;id={$project.ID}" title="{$project.name}"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$project.name|truncate:50:"...":true}</a>
<span>&nbsp;/...</span>
</div>

<h1 class="second"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$project.name}</h1>

{/if}

<div class="block_in_wrapper">

	<h2>{#editproject#}</h2>

	<form novalidate class="main" method="post" action="manageproject.php?action=edit&amp;id={$project.ID}" {literal}onsubmit="return validateCompleteForm(this,'input_error');"{/literal}>
	<fieldset>

	<div class="row"><label for="name">{#name#}:</label><input type="text" class="text" name="name" id="name" required="1" realname="{#name#}" value = "{$project.name}" /></div>
	<div class="row"><label for="desc">{#description#}:</label><div class="editor"><textarea name="desc" id="desc"  rows="3" cols="1">{$project.desc}</textarea></div></div>
	<div class="row"><label for="budget">{#budget#}:</label><input type="text" class="text" name="budget" id="budget"  realname="{#budget#}" value = "{$project.budget}" /></div>

	<div class="row">
		<label for="end">{#due#}:</label><input type="text" class="text" value="{$project.endstring}" name="end"  id="end"  {if $project.end == 0}disabled = "disabled"{/if} realname="{#due#}" />
		<br /><br />
		<label for="neverdue"></label><input type = "checkbox" class = "checkbox" value = "neverdue" name = "neverdue" id = "neverdue" {if $project.end == 0}checked = "checked" onclick = "$('end').enable();"{else}onclick = "$('end').value='';$('end').disabled='disabled';"{/if}><label for = "neverdue">{#neverdue#}</label>
	</div>


	<div class="datepick">
		<div id = "datepicker_project" class="picker" style = "display:none;"></div>
	</div>

	<script type="text/javascript">
		theCal{$lists[list].ID} = new calendar({$theM},{$theY});
		theCal{$lists[list].ID}.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
		theCal{$lists[list].ID}.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
		theCal{$lists[list].ID}.relateTo = "end";
		theCal{$lists[list].ID}.getDatepicker("datepicker_project");
	</script>


	<div class="row-butn-bottom">
		<label>&nbsp;</label>
		<button type="submit" onfocus="this.blur();">{#send#}</button>
		<button onclick="blindtoggle('form_edit');toggleClass('edit_butn','edit-active','edit');toggleClass('sm_project','smooth','nosmooth');toggleClass('sm_project_desc','smooth','nosmooth');return false;" onfocus="this.blur();" {if $showhtml != "no"} style="display:none;"{/if}>{#cancel#}</button>
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