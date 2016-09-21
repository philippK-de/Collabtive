
<div class="block_in_wrapper">

	<form class="main" method="post" action="admin.php?action=editsets" enctype="multipart/form-data" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
		<fieldset>

			<div class="row">
				<label for = "name">{#name#}:</label>
				<input type = "text" class="text" value = "{$settings.name}" name = "name" id="name" required = "1" realname = "{#name#}" />
			</div>

			<div class="row">
				<label for = "subtitle">{#subtitle#}:</label>
				<input type="text" class="text" value="{$settings.subtitle}" name="subtitle" id="subtitle" />
			</div>

			<div class="row">
				<label for = "locale">{#locale#}:</label>
				<select name = "locale" id="locale" required="1" realname = "{#locale#}">
					{section name = lang loop=$languages_fin}
						<option value = "{$languages_fin[lang].val}" {if $languages_fin[lang].val == $settings.locale}selected="selected"{/if}>{$languages_fin[lang].str}</option>
					{/section}
				</select>
			</div>

			<div class="row">
				<label for="timezone">{#timezone#}:</label>
				<select name="timezone" id="timezone" required="1" realname="{#timezone#}">
					{section name=timezone loop=$timezones}
						<option value="{$timezones[timezone]}" {if $timezones[timezone] == $settings.timezone}selected="selected"{/if}>{$timezones[timezone]}</option>
					{/section}
				</select>
			</div>

			<div class="row">
				<label for="dateformat">{#rssuser#}:</label>
				<input type = "text" name = "rssuser" id = "rssuser" value = "{$settings.rssuser}" autocomplete = "off"/>
			</div>

			<div class="row">
				<label for="dateformat">{#rsspass#}:</label>
				<input type = "password" name = "rsspass" id = "rsspass" value = "{$settings.rsspass}" autocomplete = "off" />
			</div>

			<div class="row">
				<label for="dateformat">{#dateformat#}:</label>
				<select name = "dateformat" id = "dateformat">
				<option value = "{$settings.dateformat}" selected>{$settings.dateformat}</option>
				<option value = "d.m.Y">day.month.Year</option>
				<option value = "m/d/Y">month/day/Year</option>
				</select>
			</div>

			<div class="row">
				<label for = "template">{#template#}:</label>
				<select name="template" id="template" required = "1" realname = "{#template#}" >
					{section name=tem loop=$templates}
                        {if $templates[tem] != "mobile"}
						    <option value="{$templates[tem]}" {if $settings.template == $templates[tem]}selected="selected"{/if}>{$templates[tem]}</option>
                        {/if}
					{/section}
				</select>
			</div>
			<div class="row">
				<label for = "template">Theme:</label>
				<select name="theme" id="theme" required = "1" realname = "{#template#}" >
					{section name=theme loop=$themes}
						<option value="{$themes[theme]}" {if $settings.theme == $themes[theme]}selected="selected"{/if}>{$themes[theme]}</option>
					{/section}
				</select>
			</div>


			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="this.blur();">{#send#}</button>
			</div>

		</fieldset>
	</form>

</div> {*block_in_wrapper end*}