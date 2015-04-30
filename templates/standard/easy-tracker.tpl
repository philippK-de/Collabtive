<div id="content-right">


	{*Easy Tracker*}
	<div class="content-right-in">
			<h2><a id = "searchtoggle" class="win-up" href="javascript:blindtoggle('search');toggleClass('searchtoggle','win-up','win-down');">{#search#}</a></h2>

			<form id = "search" method = "get" action = "managesearch.php" {literal} onsubmit="return validateStandard(this,'input_error');"{/literal}>
			<fieldset>
				<div class = "row">
					<input type="text" class = "text" id="query" name="query" />
				</div>

				<div id="choices"></div>
				<input type = "hidden" name = "action" value = "search" />

				<div id="indicator1" style="display:none;"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/indicator_arrows.gif" alt="{#searching#}" /></div>

				<button type="submit" title="{#gosearch#}"></button>
			</fieldset>

			</form>
	</div>
	{*Easy Tracker End*}

</div>
