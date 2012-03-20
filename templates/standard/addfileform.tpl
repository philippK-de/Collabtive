<div class="block_in_wrapper">

	<h2>{#addfile#}</h2>
	{#maxsize#}: {$postmax}<br/><br/>
	<form novalidate class="main" action="managefile.php?action=upload&amp;id={$project.ID}" method="post" enctype="multipart/form-data" {literal} onsubmit="return validateCompleteForm(this,'input_error');"{/literal}>
	<fieldset>
		<div class="row">
			<label for = "numfiles">{#count#}:</label>
			<select name = "numfiles" id = "numfiles" onchange = "make_inputs(this.value);">
				<option value = "1" selected="selected">1</option>
				<option value = "2">2</option>
				<option value = "3">3</option>
				<option value = "4">4</option>
				<option value = "5">5</option>
				<option value = "6">6</option>
				<option value = "7">7</option>
				<option value = "8">8</option>
				<option value = "9">9</option>
				<option value = "10">10</option>
			</select>
		</div>

		<div class = "row">
			<label for = "upfolder">{#folder#}:</label>
			<select name = "upfolder" id = "upfolder">
			<option value = "">{#rootdir#}</option>
			{section name=fold loop=$allfolders}
			<option value = "{$allfolders[fold].ID}">{$allfolders[fold].abspath}</option>
			{/section}
			</select>
		</div>

		<div id = "inputs">
			<div class="row"><label for = "title">{#title#}:</label><input type = "text" name = "userfile1-title" id="title" /></div>
				<div class="row"><label for="file">{#file#}:</label>
					<div class="fileinput" >
						<input size = "40" type="file" class = "file"  name="userfile1" id="filer"  realname="{#file#}" size="19" onchange = "file.value = this.value;" multiple />
						<table class = "faux" cellpadding="0" cellspacing="0" border="0" style="padding:0;margin:0;border:none;">
							<tr>
								<td><input type="text" class="text-file" name="userfile1" id="file" required="1" realname="{#file#}"></td>
								<td class="choose"><button class="inner" onclick="return false;">{#chooseone#}</button></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
					<label for = "tags">{#tags#}:</label><input type = "text" name = "userfile1-tags" id="tags" />
				</div>
			</div>

			<div class="row">
				<label for = "desc">{#description#}:</label><textarea name="desc" id="desc" rows="3" cols="1"></textarea>
			</div>

			<div class = "row">
				<label>{#visibility#}:</label>
				<select name = "visible[]" multiple style = "height:80px;">
					<option value = "" selected>{#all#}</option>
					{section name=role loop=$roles}
						<option value = "{$roles[role].ID}" >{$roles[role].name}</option>
					{/section}
				</select>
			</div>

			<div class = "row">
				<label>{#notify#}:</label>
				<select name = "sendto[]" multiple style = "height:100px;">
					<option value = "" disabled style = "color:black;font-weight:bold;">{#general#}</option>
					<option value = "all" selected>{#all#}</option>
					<option value = "none" >{#none#}</option>
					<option value = "" disabled style = "color:black;font-weight:bold;">{#members#}</option>
					{section name=member loop=$members}
						<option value = "{$members[member].ID}" >{$members[member].name}</option>
					{/section}
				</select>
			</div>



	<div class="row-butn-bottom">
		<label>&nbsp;</label>
		<img id = "fakeprogress" src = "templates/standard/images/symbols/ajax-loader.gif" alt = "fakeprogress" style = "display:none;" />

		<div id = "filesubmit" >
			<button type="submit" onclick = "$('filesubmit').hide();$('fakeprogress').show();" onfocus="this.blur();">{#addbutton#}</button>
			<button type="reset" onclick="blindtoggle('form_file');toggleClass('addfile','addfile-active','addfile');toggleClass('add_file_butn','butn_link_active','butn_link');toggleClass('sm_files','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
		</div>
	</div>


	</fieldset>
	</form>

</div> {*block_in_wrapper end*}