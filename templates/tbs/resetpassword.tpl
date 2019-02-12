{include file="header.tpl" title="Reset Password" showheader="no" jsload = "ajax"}

<div class="login">
	<div class="login-in">
		<div class="logo-name">
			<h1><img src="./templates/standard/images/logo-b.png" alt="{$settings.name}" /></h1>
			<h2>{$settings.subtitle}</h2>
		</div>

		<form id="resetpasswordform" name="resetpasswordform" method="post" action="manageuser.php?action=resetpassword" {literal} onsubmit="return validateCompleteForm(this,'input_error');"{/literal}>
			<fieldset style="text-align: center;">

			<div class="login-alert timetrack" style="width:220px;">

				<h2>{#resetpasswordforgot#}</h2>
				<span>
					{#resetpasswordemail#}<br /><br />
				</span>

				<div class="row">
					<input type="text" class="text" name="email" id="email" required = "1" realname = "{#email#}" style="width: 190px; float: none;" />
				</div>
				
				<div class="row" style="text-align:center;">
					<button type="submit" style="float:none;margin:0 0 0 0;" onfocus="this.blur();">{#resetpasswordgenerate#}</button>
				</div>
				<div class="clear_both"></div>
			</div>
			
			</fieldset>
		</form>
	</div>

	{if $success == 1}
		<div class="login-alert"><span style="color:#5DF560;">{#resetpasswordsent#}</span></div>
	{/if}
	{if $loginerror == 1}
		<div class="login-alert">{#resetpasswordfail#}</div>
	{/if}
</div>
