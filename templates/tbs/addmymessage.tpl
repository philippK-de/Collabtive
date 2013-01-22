<div class="block_in_wrapper">
	<form novalidate class="main form-horizontal" id="add_msgs" method="post" enctype="multipart/form-data" action="managemessage.php?action=add&amp;id={$myprojects[project].ID}" {literal} onsubmit="return validateCompleteForm(this,'input_error');"{/literal} >
		<fieldset>
			<legend>{#addmessage#}</legend>
			<div class="control-group">
				<label class="control-label" for="title">{#title#}:</label>
				<div class="controls">
					<input type="text" name="title" id="title" required="1" realname="{#title#}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="text">{#text#}:</label>
				<div class="editor controls">
					<textarea name="text" id="text{$myprojects[project].ID}"  realname="{#text#}" rows="3" cols="1"></textarea>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">{#files#}</label>
				<div class="controls">
					<button class="inner btn" onclick="blindtoggle('files-add{$myprojects[project].ID}');toggleClass(this,'inner-active btn disabled','inner btn');return false;" onfocus="this.blur()">
						{#addbutton#}
					</button>
					<button class="inner btn" onclick="blindtoggle('files-attach{$myprojects[project].ID}');toggleClass(this,'inner-active btn disabled','inner btn');return false;" onfocus="this.blur()">
						{#attachbutton#}
					</button>
				</div>
			</div>

			{*Attach*}
			<div id="files-attach{$myprojects[project].ID}" class="blinded" style="display:none;clear:both;">
				<div class="control-group">
					<label class="control-label" for="thefiles">{#attachfile#}</label>
					<div class="controls">
						<select name="mode" id="thefiles">
							<option value="0">{#chooseone#}</option>
							{section name=file loop=$myprojects[project].files}
							<option value="{$myprojects[project].files[file].ID}">{$myprojects[project].files[file].name}</option>
							{/section}
						</select>
					</div>
				</div>
			</div>

			{*Add*}
			<div id="files-add{$myprojects[project].ID}" class="blinded" style="display:none;">
				<div class="control-group">
					<label class="control-label" for="numfiles{$myprojects[project].ID}">{#count#}:</label>
					<div class="controls">
						<select name="numfiles" id="numfiles{$myprojects[project].ID}" onchange="make_inputs(this.value);">
							<option value="1" selected="selected">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
						</select>
					</div>
				</div>
				<div id="inputs">
					<div class="control-group">
						<label class="control-label" for="title">{#title#}:</label>
						<div class="controls">
							<input type="text" name="userfile1-title" id="title" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="file">{#file#}:</label>
						<div class="fileinput controls">
							<input type="file" class="file" name="userfile1" id="file"  realname="{#file#}" size="19" onchange="file_{$myprojects[project].ID}.value = this.value;" />
							<table class="faux table">
								<tr>
									<td>
										<input type="text" class="text-file" name="file-$myprojects[project].ID" id="file_{$myprojects[project].ID}">
									</td>
									<td class="choose">
										<button class="inner btn" onclick="return false;">
											{#chooseone#}
										</button>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<input type="hidden" name="desc" id="desc" value="" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="tags">{#tags#}:</label>
				<div class="controls">
					<input type="text" name="tags" id="tags"  realname="{#tags#}" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="milestone">{#milestone#}:</label>
				<div class="controls">
					<select name="milestone" id="milestone"  realname="{#milestone#}">
						<option value="0" selected="selected">{#chooseone#}</option>
						{section name=stone loop=$myprojects[project].milestones}
						<option value="{$myprojects[project].milestones[stone].ID}">{$myprojects[project].milestones[stone].name}</option>
						{/section}
					</select>
				</div>
			</div>
				<button type="submit" class="btn btn-primary" onfocus="this.blur()">
					{#send#}
				</button>
				<button class="btn btn-danger" type="reset">
					{#cancel#}
				</button>
		</fieldset>
	</form>
</div>
{*block_in_wrapper end*}