{include file="header.tpl" jsload = "ajax" }

{include file="tabsmenue-admin.tpl" settingstab = "active"}
<div id="content-left">
<div id="content-left-in">
<div class="neutral">
	<div class="infowin_left" style = "display:none;" id = "systemmsg">
		{if $mode == "edited"}
		<span class="info_in_yellow"><img src="templates/standard/images/symbols/system-settings.png" alt=""/>{#settingsedited#}</span>
        {elseif $mode == "imported"}
        <span class="info_in_green"><img src="templates/standard/images/symbols/basecamp.png" alt=""/>{#importsuccess#}</span>

		{/if}

		</div>

	{literal}
	<script type = "text/javascript">
	systemMsg('systemmsg');
	 </script>
	{/literal}
<h1>{#administration#}<span>/ {#systemadministration#}</span></h1>

			<div class="headline">
				<a href="javascript:void(0);" id="block_system_toggle" class="win_block" onclick = "toggleBlock('block_system');"></a>
				<h2>
					<img src="./templates/standard/images/symbols/system-settings.png" alt="" />{#systemadministration#}</a>
				</h2>
			</div>

			<div id="block_system" class="block">
				{include file="settings_system.tpl" }


				<div class="tablemenue"></div>
			</div> {*Block End*}

<div class="content-spacer"></div>

			<div class="headline">
				<a href="javascript:void(0);" id="block_import_toggle" class="win_block" onclick = "toggleBlock('block_import');"></a>
				<h2>
					<img src="./templates/standard/images/symbols/basecamp.png" alt="" />{#import#}</a>
				</h2>
			</div>

			<div id="block_import" class="block">
				<div class="block_in_wrapper">
					<form novalidate class="main" method="post" action="manageimport.php?action=basecamp" enctype="multipart/form-data" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
					<fieldset>

					<div class="row">
						<label for="file">{#file#}:</label>
						<div class="fileinput" >
								<input type="file" class="file" name="importfile" id="importfile"  realname="{#file#}" size="19" onchange = "file.value = this.value;" />
								<table class = "faux" cellpadding="0" cellspacing="0" border="0" style="padding:0;margin:0;border:none;">
									<tr>
									<td><input type="text" class="text-file" name = "userfile1" id="file" required="1" realname="{#file#}"></td>
									<td class="choose"><button class="inner" onclick="return false;">{#chooseone#}</button></td>
									</tr>
								</table>
						</div>
					</div>

					<div class="row-butn-bottom">
						<label>&nbsp;</label>
						<button type="submit" onfocus="this.blur();">{#send#}</button>
					</div>


					</fieldset>
					</form>
				</div>

				<div class="tablemenue"></div>
			</div> {*Block End*}

<div class="content-spacer"></div>

			<div class="headline">
				<a href="javascript:void(0);" id="block_email_toggle" class="win_block" onclick = "toggleBlock('block_email');"></a>
				<h2>
					<img src="./templates/standard/images/symbols/msgs.png" alt="" />{#email#}</a>
				</h2>
			</div>

			<div id="block_email" class="block">
				{include file="settings_email.tpl" }


				<div class="tablemenue"></div>
			</div> {*Block End*}

<div class="content-spacer"></div>

</div> {*Neutral END*}
</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}