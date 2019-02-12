				<div id="email">
				<div class="block_in_wrapper">

					<form class="main" method="post" action="admin.php?action=editmailsets" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
					<fieldset>

					<div class="row">
						<label for="mailstatus">{#status#}:</label>
						<select name="mailstatus" id="mailstatus" >
						{if $settings.mailnotify == 1}
						<option value = "1" selected>on</option>
						<option value = "0">off</option>
						{else}
						<option value = "1">on</option>
						<option value = "0" selected>off</option>
						{/if}
						</select>
					</div>

					<div class="row"><label for="mailfrommail">{#mailfrommail#}:</label><input type="text" class="text" name="mailfrommail" id="mailfrommail" value = "{$settings.mailfrom}" /></div>
					<div class="row"><label for="mailfromname">{#mailfromname#}:</label><input type="text" class="text" name="mailfromname" id="mailfromname" value = "{$settings.mailfromname}" /></div>

					<div class="row"><label for="mailmethod">{#mailmethod#}:</label><select name="mailmethod" id="mailmethod" >
					{if $settings.mailmethod == "mail"}
					<option value = "mail" selected>PHP mail()</option>
					<option value = "smtp">SMTP</option>
					{else}
					<option value = "mail">PHP mail()</option>
					<option value = "smtp" selected>SMTP</option>
					{/if}
					</select>
					</div>


					<div class="row"><label for="server">{#mailserver#}:</label><input type="text" class="text" name="server" id="server" value = "{$settings.mailhost}" /></div>
					<div class="row"><label for="mailuser">{#mailusername#}:</label><input type="text" class="text" name="mailuser" id="mailuser" value = "{$settings.mailuser}" autocomplete = "off" /></div>
					<div class="row"><label for="mailpass">{#mailpass#}:</label><input type="password" class="text" name="mailpass" id="mailpass" value = "{$settings.mailpass}" autocomplete = "off" /></div>


					<div class="row-butn-bottom">
						<label>&nbsp;</label>
						<button type="submit" onfocus="this.blur();">{#send#}</button>
					</div>


					</fieldset>
					</form>

				</div> {*block_in_wrapper end*}
				</div> {*admin end*}