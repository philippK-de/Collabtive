{include file="header.tpl" title="Login" showheader="no" jsload = "ajax"}

		<div class="login">
			<div class="login-in">
				<div class="logo-name">
					<h1><a href = "http://collabtive.o-dyn.de/" title = "{$settings.name} Open Source project management"><img src="./templates/standard/images/logo-a.png" alt="{$settings.name}"  /></a></h1>
					<h2>{$settings.subtitle}</h2>
				</div>

				<form id = "loginform" name = "loginform" method="post" action="manageuser.php?action=login" {literal} onsubmit="return validateCompleteForm(this,'input_error');"{/literal}>
					<fieldset>

					<div class="row">
						<label for="username" class="username"></label>
						<input type="text" class="text" name="username" id="username" required = "1" realname = "{#name#}" />
					</div>

					<div class="row">
						<label for="pass" class="pass"></label>
						<input type="password" class="text" name="pass" id="pass" realname = "{#password#}" />
					</div>


					<div class="row">
						<label for="stay" class="keep" onclick = "toggleClass(this,'keep','keep-active');"><span>{#stayloggedin#}</span></label>
						<input type = "checkbox" name = "staylogged" id="stay" value = "1" />
					</div>

					<div class="row">
						<button type="submit" class="loginbutn" title="{#loginbutton#}" onfocus="this.blur();"></button>
					</div>
					</fieldset>
				</form>
			</div>

			{if $loginerror == 1}
				<div class="login-alert timetrack">
					{#loginerror#}<br /><br />
					<form id = "blaform" name = "resetform" class = "main" method="post" action="manageuser.php?action=loginerror" >
						<div class="row" style="text-align:center;">
							<button  style = "float:none;margin:0 0 0 0;" onclick="$('blaform').submit();" onfocus="this.blur();">{#resetpassword#}</button>
						</div>
					</form>
					<div class="clear_both"></div>
				</div>
			{/if}
		</div>
	</body>
</html>
