{include file="header.tpl" title="install" showheader="no"}

			<div class="install" style="text-align:center;padding:5% 0;">
				<div style="text-align:left;width:500px;margin:0 auto;padding:25px 25px 15px 25px;background:white;border:1px solid;">

				<h1>{#installcollabtive#}</h1>

				<div style="border-bottom:1px dashed;padding:16px 0 16px 0;">
					<form class="main" method = "get" action = "install.php">
						<fieldset>
							<div class="row"><label for = "language" style="width:210px;">{#installerlanguage#}</label>
								<select name = "locale" id = "language" onchange = "document.forms[0].submit();">
									<option value = "">{#chooseone#}</option>
									<option value = "ar">{#ar#}</option>
									<option value = "da">{#da#}</option>
									<option value = "de">{#de#}</option>
									<option value = "en">{#en#}</option>
									<option value = "es">{#es#}</option>
									<option value = "fi">{#fi#}</option>
									<option value = "fr">{#fr#}</option>
									<option value = "it">{#it#}</option>
									<option value = "nl">{#nl#}</option>
									<option value = "pl">{#pl#}</option>
									<option value = "se">{#se#}</option>
									<option value = "sr">{#sr#}</option>
								</select>
							</div>
						</fieldset>
					</form>
				</div>
				
				<div style="border-bottom:1px dashed;padding:16px 0 20px 0;">
					<h2>1. {#installerconditions#}</h2>
					
					<div class="row" style="padding-bottom:12px;">
						<i>{#installerchecksconditions#}</i>
					</div>
					
					<table cellpadding="0" cellspacing="0" style="font-style:italic;line-height: 23px">
						<tr>
							<td style="width:213px;"><strong>{#name#}:</strong></td>
							<td><strong>{#status#}:</strong></td>
						</tr>
						<tr valign="top">
							<td>PHP 5</td>
							{if $phpver >= 5.1}
							<td><span style = "color:green;font-weight:bold;">OK</span></td>
							{else}
							<td><span style = "color:red;font-weight:bold;">Not OK <br />(PHP {$phpver} - {#phpversion#})</span></td>
							{/if}
						</tr>
						<tr valign="top">
							<td>config.php {#iswritable#}</td>
							{if $configfile >= 666}
							<td><span style = "color:green;font-weight:bold;">OK </span></td>
							{else}
							<td><span style = "color:red;font-weight:bold;">Not OK <br />({#makefilewritable#})</span></td>
							{/if}
						</tr>
						<tr valign="top">
							<td>files {#iswritable#}</td>
							{if $filesdir == 1}
							<td><span style = "color:green;font-weight:bold;">OK </span></td>
							{else}
							<td><span style = "color:red;font-weight:bold;">Not OK <br />({#makedirwritable#})</span></td>
							{/if}
						</tr>
						<tr valign="top">
							<td>templates_c {#iswritable#}</td>
							{if $templatesdir == 1}
							<td><span style = "color:green;font-weight:bold;">OK </span></td>
							{else}
							<td><span style = "color:red;font-weight:bold;">Not OK <br />({#makedirwritable#})</span></td>
							{/if}
						</tr>
						<tr valign="top">
							<td>{#mb_string_enabled#}</td>
							{if $is_mbstring_enabled}
							<td><span style = "color:green;font-weight:bold;">OK </span></td>
							{else}
							<td><span style = "color:red;font-weight:bold;">Not OK <br />({#enable_mb_string#})</span></td>
							{/if}
						</tr>
					</table>
				</div>

				{if $configfile >= 666 and $phpver >= 5.1 and $templatesdir == 1 and $filesdir == 1 and $is_mbstring_enabled}
					<div style="padding:16px 0 12px 0;">

						<h2>2. {#db#}</h2>
						<div class="row" style="padding-bottom:12px;"><i>{#insertdbaccessdata#}</i></div>

						<form class="main" method = "post" action = "install.php?action=step2&locale={$locale}">
							<fieldset>
								<div class="row">
									<label for = "db_host" style="width:210px;">{#dbhost#}:</label><input type = "text" name = "db_host" id = "db_host" value = "localhost"/>
								</div>
								<div class="row">
									<label for = "db_name" style="width:210px;">{#dbname#}:</label><input type = "text" name = "db_name" id = "db_name" />
								</div>
								<div class="row">
									<label for = "db_user" style="width:210px;">{#dbuser#}:</label><input type = "text" name = "db_user" id = "db_user" />
								</div>
								<div class="row">
									<label for = "db_pass" style="width:210px;">{#dbpass#}:</label><input type = "password" name = "db_pass" id = "db_pass" />
								</div>
								<div style="border-bottom:1px dashed;height:16px;display:block;clear:both;margin-bottom:16px;"></div>
								<div class="row" style="padding-bottom:12px;">
									<i>{#clickcontinue#}</i>
								</div>
								<div class="row-butn-bottom">
									<label style="width:210px;">&nbsp;</label>
									<button type="submit" onfocus="this.blur();">{#continue#}</button>
								</div>
							</fieldset>
						</form>
					</div>
				{else}
					<br /><span style = "color: red;font-weight:bold;margin-left: 213px;">{#correctfaults#}</span>
				{/if}
				
				<div class="content-spacer"></div>
			
			</div>
		</div> {*Install end*}
	</body>
</html>