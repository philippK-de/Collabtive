<div class="block_in_wrapper">
	
	<h2>{#addmessage#}</h2>
	
	<form novalidate class="main" id="add_msgs" method="post" enctype="multipart/form-data" action="managemessage.php?action=add&amp;id={$myprojects[project].ID}" {literal} onsubmit="return validateCompleteForm(this,'input_error');" {/literal}>
		<fieldset>
			
			<div class="row">
				<label for="title">{#title#}:</label>
				<input type="text" name="title" id="title" required="1" realname="{#title#}" />
			</div>
			
			<div class="row">
				<label for="text">{#text#}:</label>
				<div class="editor">
					<textarea name="text" id="text{$myprojects[project].ID}" realname="{#text#}" rows="3" cols="1"></textarea>
				</div>
			</div>
			
			<div class="row">
				<label>{#files#}</label>
				<button class="inner" onclick="blindtoggle('files-add{$myprojects[project].ID}');toggleClass(this,'inner-active','inner');return false;" onfocus="this.blur()">{#addbutton#}</button>
				<button class="inner" onclick="blindtoggle('files-attach{$myprojects[project].ID}');toggleClass(this,'inner-active','inner');return false;" onfocus="this.blur()">{#attachbutton#}</button>
			</div>
			
			{*Attach*}
			<div id="files-attach{$myprojects[project].ID}" class="blinded" style="display:none;clear:both;">
				
			   	<div class="row">
					<label for="thefiles">{#attachfile#}</label>
					<select name="mode" id="thefiles">
						<option value="0">{#chooseone#}</option>
						{section name=file loop=$myprojects[project].files}
							<option value="{$myprojects[project].files[file].ID}">{$myprojects[project].files[file].name}</option>
						{/section}
					</select>
				</div>
				
			</div>
			
			{*Add*}
			<div id="files-add{$myprojects[project].ID}" class="blinded" style="display:none;">
				
				<div class="row">
					<label for="numfiles{$myprojects[project].ID}">{#count#}:</label>
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
				
				<div id="inputs">
					<div class="row">
						<label for="title">{#title#}:</label>
						<input type="text" name="userfile1-title" id="title" />
					</div>
					
					<div class="row">
						<label for="file">{#file#}:</label>
						<div class="fileinput">
							<input type="file" class="file" name="userfile1" id="file" realname="{#file#}" size="19" onchange="file_{$myprojects[project].ID}.value = this.value;" />
							<table class="faux" cellpadding="0" cellspacing="0" border="0" style="padding:0;margin:0;border:none;">
								<tr>
									<td>
										<input type="text" class="text-file" name="file-$myprojects[project].ID" id="file_{$myprojects[project].ID}">
									</td>
									<td class="choose">
										<button class="inner" onclick="return false;">{#chooseone#}</button>
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<input type="hidden" name="desc" id="desc" value="" />
				</div>
				
			</div>
			
			<div class="row">
				<label for="tags">{#tags#}:</label>
				<input type="text" name="tags" id="tags" realname="{#tags#}" />
			</div>
			
			<div class="row">
				<label for="milestone">{#milestone#}:</label>
				<select name="milestone" id="milestone" realname="{#milestone#}">
					<option value="0" selected="selected">{#chooseone#}</option>
					{section name=stone loop=$myprojects[project].milestones}
						<option value="{$myprojects[project].milestones[stone].ID}">{$myprojects[project].milestones[stone].name}</option>
					{/section}
				</select>
			</div>
			
			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="this.blur()">{#send#}</button>
				<button onclick="blindtoggle('addmsg{$myprojects[project].ID}');toggleClass('add_{$myprojects[project].ID}','add-active','add');toggleClass('add_butn_{$myprojects[project].ID}','butn_link_active','butn_link');toggleClass('sm_{$myprojects[project].ID}','smooth','nosmooth');return false;" onfocus="this.blur()">{#cancel#}</button>
			</div>
			
		</fieldset>
	</form>
	
</div> {*block_in_wrapper end*}
