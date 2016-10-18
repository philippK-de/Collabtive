<div class="block_in_wrapper">
	
	<h2>{#addfile#}</h2>
	{#maxsize#}: {$postmax}<br /><br />
	
	<form class="main" action="#" method="post" enctype="multipart/form-data">
		<fieldset>
			
			<div class="row">
				<label for="upfolder">{#folder#}:</label>
				<select name="upfolder" id="upfolder">
					<option value="">{#rootdir#}</option>
					{section name=fold loop=$folders}
						<option value="{$folders[fold].ID}">{$folders[fold].abspath}</option>
					{/section}
				</select>
			</div>
			
			<div id="inputs">
				
				<div class="row">
					<label for="file">{#file#}:</label>
					<div class="fileinput">
						<input size="1" type="file" class="file" name="userfile1" id="filer" realname="{#file#}" onchange="uploader.fileInfo();"
                               style="cursor:pointer;" multiple required />
						<table class="faux" cellpadding="0" cellspacing="0" border="0" style="padding:0;margin:0;border:none;">
							<tr>
								<td class="choose" style="padding:0px;">
									<button class="inner" onclick="return false;" style="float:left;cursor:pointer;">{#chooseone#}</button>
								</td>
							</tr>
						</table>
					</div>
				</div>
				
				<div class="row">
					<label>&nbsp;</label>
					<div id="fileInfo1"></div>
				</div>
			</div>

            {if $settings.mailnotify == 1}
			<div class="row">
				<label>{#notify#}:</label>
				<select id="sendto" name="sendto[]" multiple style="height:100px;">
					<option value="" disabled style="color:black;font-weight:bold;">{#general#}</option>
					<option value="all" selected>{#all#}</option>
					<option value="none" >{#none#}</option>
					<option value="" disabled style="color:black;font-weight:bold;">{#members#}</option>
					{section name=member loop=$members}
						<option value="{$members[member].ID}">{$members[member].name}</option>
					{/section}
				</select>
			</div>
            {/if}
			
			<div class="row" id="statusrow" style="display:none;">
				<label>&nbsp;</label><br />
				<div class="statusbar" id="fakeprogress" style="width:314px;margin-left:140px;">
					<div id="uploadCompleted" class="complete" style="width:0%;"></div>
				</div>
			</div>
			
			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<div id="filesubmit">
					<button onclick="uploader.upload();return false;" onfocus="this.blur();">{#addbutton#}</button>
				</div>
			</div>
			
		</fieldset>
	</form>
	
	{literal}
		<script type="text/javascript">
            window.onload = function(){
                uploader = new html5up("filer", "fileInfo1", "uploadCompleted", "managefile.php?action=uploadAsync&id={/literal}{$project.ID}{literal}");

            }

		</script>
	{/literal}
	
</div> {*block_in_wrapper end*}
