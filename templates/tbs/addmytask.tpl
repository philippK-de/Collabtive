<div class="block_in_wrapper">
	<form novalidate class="main form-horizontal" method="post" action="managetask.php?action=add&amp;id={$myprojects[project].ID}" onsubmit="return validateCompleteForm(this,'input_error');">
		<fieldset>
			<legend>{#addtask#}</legend>
			<div class="control-group">
				<label class="control-label" for="title">{#title#}:</label>
				<div class="controls">
					<input type="text" class="text" name="title" id="title" realname="{#title#}" required="1" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="text">{#text#}:</label>
				<div class="controls editor">
					<textarea name="text" id="text" rows="3" cols="1" ></textarea>
				</div>			
			</div>
			<div class="control-group">
				<label class="control-label" for="end{$myprojects[project].ID}">{#end#}:</label>
				<div class="controls">
					<div class="input-append date" id="dp" data-date="{$settings.dateformat}" data-date-format="dd-mm-yyyy">
						<input size="16" type="text" value="{$smatry.now}" name="end" id="end{$myprojects[project].ID}" />
						<span class="add-on"><i class="icon-calendar"></i></span>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label for="tasklist" class="control-label">{#tasklist#}:</label>
				<div class="controls">
					<select name="tasklist" id="tasklist" required="1" exclude="-1" realname="{#tasklist#}">
						<option value="-1" selected="selected">{#chooseone#}</option>
						{section name=tasklist loop=$myprojects[project].lists}
						<option value="{$myprojects[project].lists[tasklist].ID}">{$myprojects[project].lists[tasklist].name}</option>
						{/section}
					</select>
				</div>
			</div>
			<input type="hidden" value="{$userid}" name="assigned" />
				<label>&nbsp;</label>
				<button type="submit" class="btn btn-primary" onfocus="this.blur();">
					{#addbutton#}
				</button>
				<button class="btn btn-danger" type="reset" onclick="blindtoggle('form_{$myprojects[project].ID}');" onfocus="this.blur();">
					{#cancel#}
				</button>
		</fieldset>
	</form>
</div>
{*block_in_wrapper end*}