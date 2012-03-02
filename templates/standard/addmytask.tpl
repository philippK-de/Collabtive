<div class="block_in_wrapper">


	<h2>{#addtask#}</h2>
	<form novalidate class="main" method="post" action="managetask.php?action=add&amp;id={$myprojects[project].ID}"  {literal}onsubmit="return validateCompleteForm(this,'input_error');{/literal}">
	<fieldset>

	<div class="row"><label for="title">{#title#}:</label><input type="text" class="text" name="title" id="title" realname = "{#title#}" required = "1"  /></div>
	<div class="row"><label for="text">{#text#}:</label><div class="editor"><textarea name="text" id="text" rows="3" cols="1" ></textarea></div></div>
	<div class="row"><label for="end{$myprojects[project].ID}">{#end#}:</label><input type="text" class="text" name="end" realname="{#due#}"  id="end{$myprojects[project].ID}" required = "1" /></div>

	<div class="datepick">
		<div id = "datepicker_task{$myprojects[project].ID}" class="picker" style = "display:none;"></div>
	</div>

	<script type="text/javascript">
	  	theCal = new calendar({$theM},{$theY});
		theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
		theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
		theCal.relateTo = "end{$myprojects[project].ID}";
		theCal.getDatepicker("datepicker_task{$myprojects[project].ID}");
	</script>

    <div class="row"><label for="tasklist">{#tasklist#}:</label>
	    <select name="tasklist" id="tasklist" required = "1" exclude = "-1" realname = "{#tasklist#}">
	    <option value="-1" selected="selected">{#chooseone#}</option>
	    {section name=tasklist loop=$myprojects[project].lists}
	    <option value="{$myprojects[project].lists[tasklist].ID}">{$myprojects[project].lists[tasklist].name}</option>
	    {/section}
	    </select>
    </div>

    <input type="hidden" value="{$userid}" name="assigned" />

	<div class="row-butn-bottom">
		<label>&nbsp;</label>
		<button type="submit" onfocus="this.blur();">{#addbutton#}</button>
		<button onclick="blindtoggle('form_{$myprojects[project].ID}');toggleClass('add_{$myprojects[project].ID}','add-active','add');toggleClass('add_butn_{$myprojects[project].ID}','butn_link_active','butn_link');toggleClass('sm_{$myprojects[project].ID}','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
	</div>

	</fieldset>
	</form>

</div> {*block_in_wrapper end*}