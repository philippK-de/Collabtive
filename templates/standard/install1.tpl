{include file="header.tpl" title="$title" showheader="no" jsload="ajax"}
 			<div class="install" style="text-align:center;padding:5% 0;">
				<div style="text-align:left;width:500px;margin:0 auto;padding:25px 25px 15px 25px;background:white;border:1px solid;">
				<h1>{#installcollabtive#}</h1>
				<div style="border-bottom:1px dashed;padding:16px 0 16px 0;">

					<form class="main" method="get" action="install.php">
						<fieldset>

							<div class="row">
								<label for="language" style="width:210px;">{#installerlanguage#}</label>
								<select name="locale" id="language" onchange="document.forms[0].submit();">
									<option value="">{#chooseone#}</option>
                                    <option value="al">{#al#}</option>
                                    <option value="ar">{#ar#}</option>
                                    <option value="bg">{#bg#}</option>
                                    <option value="ca">{#ca#}</option>
                                    <option value="cs">{#cs#}</option>
                                    <option value="da">{#da#}</option>
                                    <option value="de">{#de#}</option>
                                    <option value="el">{#el#}</option>
                                    <option value="en">{#en#}</option>
                                    <option value="es">{#es#}</option>
                                    <option value="et">{#et#}</option>
                                    <option value="fa">{#fa#}</option>
                                    <option value="fi">{#fi#}</option>
                                    <option value="fr">{#fr#}</option>
                                    <option value="gl">{#gl#}</option>
                                    <option value="he">{#he#}</option>
                                    <option value="hr">{#hr#}</option>
                                    <option value="hu">{#hu#}</option>
                                    <option value="id">{#id#}</option>
                                    <option value="it">{#it#}</option>
                                    <option value="ja">{#ja#}</option>
                                    <option value="lt">{#lt#}</option>
                                    <option value="nb">{#nb#}</option>
                                    <option value="nl">{#nl#}</option>
                                    <option value="nn">{#nn#}</option>
                                    <option value="pl">{#pl#}</option>
                                    <option value="pt">{#pt#}</option>
                                    <option value="pt_br">{#pt_br#}</option>
                                    <option value="ro">{#ro#}</option>
                                    <option value="ru">{#ru#}</option>
                                    <option value="se">{#se#}</option>
                                    <option value="sk">{#sk#}</option>
                                    <option value="sl">{#sl#}</option>
                                    <option value="sr">{#sr#}</option>
                                    <option value="tr">{#tr#}</option>
                                    <option value="uk">{#uk#}</option>
                                    <option value="vi">{#vi#}</option>
                                    <option value="zh">{#zh#}</option>
                                    <option value="zh_tw">{#zh_tw#}</option>
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
							<td style="width:260px;"><strong>{#condition#}:</strong></td>
							<td><strong>{#status#}:</strong></td>
						</tr>
						<tr valign="top">
							<td>PHP 5.5+</td>
							{if $phpver >= 5.5}
								<td><span style="color:green;font-weight:bold;"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/butn-ok.png" alt="OK" /></span></td>
							{else}
								<td><span style="color:red;font-weight:bold;"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/butn-notok.png" alt="Not OK" /><br />(PHP {$phpver} - {#phpversion#})</span></td>
							{/if}
						</tr>
                        <tr valign="top">
                            <td>{#mb_string_enabled#}</td>
                            {if $is_mbstring_enabled}
                                <td><span style="color:green;font-weight:bold;"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/butn-ok.png" alt="OK" /></span></td>
                            {else}
                                <td><span style="color:red;font-weight:bold;"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/butn-notok.png" alt="Not OK" /><br />{#enable_mb_string#}</span></td>
                            {/if}
                        </tr>
						<tr valign="top">
							<td>'config.php' {#iswritable#}</td>
							{if $configfile == 1}
								<td><span style="color:green;font-weight:bold;"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/butn-ok.png" alt="OK" /></span></td>
							{else}
								<td><span style="color:red;font-weight:bold;"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/butn-notok.png" alt="Not OK" /><br />{#makefilewritable#}</span></td>
							{/if}
						</tr>
						<tr valign="top">
							<td>'./files' {#iswritable#}</td>
							{if $filesdir == 1}
							<td><span style="color:green;font-weight:bold;"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/butn-ok.png" alt="OK" /></span></td>
							{else}
							<td><span style="color:red;font-weight:bold;"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/butn-notok.png" alt="Not OK" /><br />{#makedirwritable#}</span></td>
							{/if}
						</tr>
						<tr valign="top">
							<td>'./templates_c' {#iswritable#}</td>
							{if $templatesdir == 1}
							<td><span style="color:green;font-weight:bold;"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/butn-ok.png" alt="OK" /></span></td>
							{else}
							<td><span style="color:red;font-weight:bold;"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/butn-notok.png" alt="Not OK" /><br />{#makedirwritable#}</span></td>
							{/if}
						</tr>
					</table>
				</div>

				{if $configfile == 1 and $phpver >= 5.5 and $templatesdir == 1 and $filesdir == 1 and $is_mbstring_enabled}
					<div style="padding:16px 0 12px 0;">

						<h2>2. {#db#}</h2>
						<form class="main" method="post" action="install.php?action=step2&locale={$locale}">
							<fieldset>
								<div class="row" style="padding-bottom:12px;"><i>Select your database driver</i></div>
								<label for="db_driver" style="width:210px;">Database Driver:</label>
								<select name="db_driver" id="db_driver">
								<option value="mysql" onclick="$('dbdata').blindDown();">MySQL</option>
								<option value="sqlite" onclick="$('dbdata').blindUp();">SQLite</option>
								</select>
							</fieldset>
						<fieldset id = "dbdata">
						<div style="border-bottom:1px dashed;height:16px;display:block;clear:both;margin-bottom:16px;"></div>
						<div class="row" style="padding-bottom:12px;"><i>{#insertdbaccessdata#}</i></div>
								<div class="row">
									<label for="db_host" style="width:210px;">{#dbhost#}:</label><input type="text" name="db_host" id="db_host" value="localhost" />
								</div>
								<div class="row">
									<label for="db_name" style="width:210px;">{#dbname#}:</label><input type="text" name="db_name" id="db_name" />
								</div>
								<div class="row">
									<label for="db_user" style="width:210px;">{#dbuser#}:</label><input type="text" name="db_user" id="db_user" />
								</div>
								<div class="row">
									<label for="db_pass" style="width:210px;">{#dbpass#}:</label><input type="password" name="db_pass" id="db_pass" />
								</div>
								</fieldset>
								<fieldset>
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
					<br />
					<span style="color: red;font-weight:bold;">{#correctfaults#}</span>
				{/if}

				<div class="content-spacer"></div>

			</div>
		</div> {* install END *}

	</body>
</html>
