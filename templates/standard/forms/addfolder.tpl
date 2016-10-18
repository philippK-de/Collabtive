<div class="block_in_wrapper">
	
	<h2>{#addfolder#}</h2>
	
	<form class="main" action="managefile.php?action=addfolder&amp;id={$project.ID}" method="post" {literal}onsubmit="return validateCompleteForm(this,'input_error');"{/literal}>
		<fieldset>
			
			<div class="row">
				<label for="folderparent">{#parent#}:</label>
				<select name="folderparent" id="folderparent">
					<option value="0">{#rootdir#}</option>
					{section name=fold loop=$folders}
						<option value="{$folders[fold].ID}">{$folders[fold].abspath}</option>
					{/section}
				</select>
			</div>
			
			<div class="row">
				<label for="foldertitle">{#title#}:</label>
				<input type="text" class="text" name="foldertitle" id="foldertitle" required />
			</div>
			
			<input type="hidden" name="visible[]" value="" />
			
			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="this.blur();">{#addbutton#}</button>
				<button onclick="blindtoggle('form_folder');toggleClass('addfolder','addfolder-active','addfolder');toggleClass('add_folder_butn','butn_link_active','butn_link');toggleClass('sm_files','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
			</div>
			
		</fieldset>
	</form>
	
</div> {*block_in_wrapper end*}
