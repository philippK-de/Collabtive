<div class="block_in_wrapper">

	<h2>{#addtask#}</h2>

	<form id="addtaskform" class="main" method="post" action="managetask.php?action=add&amp;redir=yes&amp;id=">
		<fieldset>

			<div class="row">
				<label for="title">{#title#}:</label>
				<input type="text" class="text" name="title" id="title" required="1" />
			</div>

			<div class="row">
				<label for="text">{#text#}:</label>
				<div class="editor">
					<textarea name="text" id="text" rows="3" cols="1"></textarea>
				</div>
			</div>

            <div class="row">
                <label for="start">{#start#}:</label>
                <input type="text" class="text" name="start" realname="{#start#}" id="start" />
            </div>

            <div class="datepick">
                <div id="datepicker_start_task" class="picker" style="display:none;"></div>
            </div>


			<div class="row">
				<label for="end">{#end#}:</label>
				<input type="text" class="text" name="end" realname="{#due#}" id="end{$myprojects[project].ID}" required="true" />
			</div>

			<div class="datepick">
				<div id="datepicker_task" class="picker" style="display:none;"></div>
			</div>

		    <div class="row">
		    	<label for="tasklist">{#project#}:</label>
				<select name="project" id="projectTask" required onchange="change('manageproject.php?action=tasklists&amp;id='+this.value,'tasklist');document.getElementById('addtaskform').action += this.value;">
				    <option value="" selected="selected">{#chooseone#}</option>
				    {section name=project loop=$openProjects}
				    	<option value="{$openProjects[project].ID}">{$openProjects[project].name}</option>
				    {/section}
			    </select>
		    </div>

		    <div class="row">
		    	<label for="tasklist">{#tasklist#}:</label>
			    <select name="tasklist" id="tasklist" required>
			    	<option value="" selected="selected">{#chooseone#}</option>
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
