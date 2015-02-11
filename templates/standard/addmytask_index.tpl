<div class="block_in_wrapper">

	<h2>{#addtask#}</h2>

	<form novalidate id="addtaskform" class="main" method="post" action="managetask.php?action=add&amp;id=" {literal} onsubmit="return validateCompleteForm(this,'input_error'); {/literal} ">
		<fieldset>

			<div class="row">
				<label for="title">{#title#}:</label>
				<input type="text" class="text" name="title" id="title" realname="{#title#}" required="1" />
			</div>

			<div class="row">
				<label for="text">{#text#}:</label>
				<div class="editor">
					<textarea name="text" id="text" rows="3" cols="1"></textarea>
				</div>
			</div>

			<div class="row">
				<label for="end{$myprojects[project].ID}">{#end#}:</label>
				<input type="text" class="text" name="end" realname="{#due#}" id="end{$myprojects[project].ID}" required="1" />
			</div>

			<div class="datepick">
				<div id="datepicker_task{$myprojects[project].ID}" class="picker" style="display:none;"></div>
			</div>

			<script type="text/javascript">
			  	theCal = new calendar({$theM},{$theY});
				theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
				theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
				theCal.relateTo = "end{$myprojects[project].ID}";
				theCal.dateFormat = "{$settings.dateformat}";
				theCal.getDatepicker("datepicker_task{$myprojects[project].ID}");
			</script>

		    <div class="row">
		    	<label for="tasklist">{#project#}:</label>
				<select name="project" id="projectTask" required="1" exclude="-1" realname="{#project#}" onchange="change('manageproject.php?action=tasklists&amp;id='+this.value,'tasklist');$('addtaskform').action += this.value;">
				    <option value="-1" selected="selected">{#chooseone#}</option>
				    {section name=project loop=$myprojects}
				    	<option value="{$myprojects[project].ID}">{$myprojects[project].name}</option>
				    {/section}
			    </select>
		    </div>

		    <div class="row">
		    	<label for="tasklist">{#tasklist#}:</label>
			    <select name="tasklist" id="tasklist" required="1" exclude="-1" realname="{#tasklist#}">
			    	<option value="-1" selected="selected">{#chooseone#}</option>
			    </select>
		    </div>

		    <input type="hidden" value="{$userid}" name="assigned[]" />

			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="this.blur();">{#addbutton#}</button>
				<button type="reset" onclick="blindtoggle('form_addmytask');toggleClass('add_butn_mytasks','butn_link_active','butn_link');toggleClass('sm_desktoptasks','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
			</div>

		</fieldset>
	</form>

</div> {*block_in_wrapper end*}
