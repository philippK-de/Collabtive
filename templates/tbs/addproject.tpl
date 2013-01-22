<div class="block_in_wrapper">
	<form novalidate class="main form-horizontal" method="post" action="admin.php?action=addpro" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
		<fieldset>
			<legend>{#addproject#}</legend>
			<div class="control-group">
				<label class="control-label" for="name">{#name#}:</label>
				<div class="controls">
					<input type="text" class="text" name="name" id="name" required="1" realname="{#name#}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="desc">{#description#}:</label>
				<div class="editor controls">
					<textarea name="desc" id="desc"  rows="3" cols="1" ></textarea>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="end">{#due#}:</label>
				<div class="controls">
					<div class="input-append date" id="dp" data-date="{$settings.dateformat}" data-date-format="dd-mm-yyyy">
						<input size="16" type="text" value="{$smatry.now}" name="end" />
						<span class="add-on"><i class="icon-calendar"></i></span>
					</div>
				</div>
				<div class="controls">
					<label class="checkbox">
						<input type="checkbox" class="checkbox" value="neverdue" name="neverdue" id="neverdue" onclick = "$('end').value='';$('end').disabled='disabled';"> {#neverdue#}
					</label>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="budget">{#budget#}:</label>
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on">&euro;</span>
						<input type="text" class="text" name="budget" id="budget" />
					</div>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">{#members#}:</label>
					<div class="controls well well-small">
					{section name=user loop=$users}
					<label for="{$users[user].ID}" class="checkbox">
						<input type="checkbox" class="checkbox" value="{$users[user].ID}" name="assignto[]" id="{$users[user].ID}"  {if $users[user].ID == $userid} checked="checked"{/if} /> {$users[user].name}
					</label>
					{/section}
				</div>
			</div>
			
			<input type="hidden" name="assignme" value="1" />
			<button type="submit" class="btn btn-primary" onfocus="this.blur();">{#addbutton#}</button>
			{if $myprojects == "1"}
			<button type="reset" class="btn btn-danger" onclick="blindtoggle('form_addmyproject');toggleClass('add_myprojects','add-active','add');toggleClass('add_butn_myprojects','butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');return false;" onfocus="this.blur();">
				{#cancel#}
			</button>
			{else}
			<button type="reset" class="btn btn-danger" onclick="blindtoggle('form_{$myprojects[project].ID}');return false;">{#cancel#}</button>
			{/if}
		</fieldset>
	</form>
</div>
{*block_in_wrapper end*}