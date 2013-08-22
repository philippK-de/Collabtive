<div id="content-right">


	{*Search*}
	<div class="content-right-in">
			<h2><a id = "searchtoggle" class="win-up" href="javascript:blindtoggle('search');toggleClass('searchtoggle','win-up','win-down');">{#search#}</a></h2>

			<form id = "search" method = "get" action = "managesearch.php" {literal} onsubmit="return validateStandard(this,'input_error');"{/literal}>
			<fieldset>
				<div class = "row">
					<input type="text" class = "text" id="query" name="query" />
				</div>

				<div id="choices"></div>
				<input type = "hidden" name = "action" value = "search" />

				<div id="indicator1" style="display:none;"><img src="templates/standard/images/symbols/indicator_arrows.gif" alt="{#searching#}" /></div>

				<button type="submit" title="{#gosearch#}"></button>
			</fieldset>

			</form>
	</div>
	{*Search End*}


	{*Calendar*}
	{* theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
	<div class="content-right-in">
		<h2><a id="mycaltoggle" class="win-up" href="javascript:blindtoggle('mycal_mini');toggleClass('mycaltoggle','win-up','win-down');">{#calendar#}</a></h2>
		<div id = "mycal_mini"></div>
		<script type = "text/javascript">
		theCal = new calendar({$theM},{$theY});
		theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
		theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
		theCal.getCal('mycal_mini');
		</script>
	</div>
	Calendar End*}


	{*Tag Cloud*}
	{if $showcloud|default == "1"}
		{if $cloud|default != ""}
		<div class="content-right-in">
			<h2><a id="tagcloudtoggle" class="win-up" href="javascript:blindtoggle('tagcloud');toggleClass('tagcloudtoggle','win-up','win-down');">{#tags#}</a></h2>
			<div id = "tagcloud" class="cloud">
				{$cloud}
			</div>
		</div>
		{/if}
	{/if}
	{*Tag Cloud End*}

	{*Quickfinder*}
	{if $openProjects[0].ID > 0}
		<div class="content-right-in">
			<h2><a id="quickfindertoggle" class="win-up" href="javascript:blindtoggle('quickfinder');toggleClass('quickfindertoggle','win-up','win-down');">{#myprojects#}</a></h2>
			<div id = "quickfinder">
				<form>
					<select style="background-color:#CCC;width:100%;" onchange="window.location='manageproject.php?action=showproject&id='+this.value;">
						<option>{#chooseone#}</option>
						{section name=drop loop=$openProjects}
							<option value="{$openProjects[drop].ID}">{$openProjects[drop].name|truncate:40:"...":true}</option>
						{/section}
					</select>
				</form>
			</div>
		</div>
	{/if}

	{*Onlinelist*}
	<div class="content-right-in">
			<h2><a id="onlinelisttoggle" class="win-up" href="javascript:blindtoggle('onlinelist');toggleClass('onlinelisttoggle','win-up','win-down');">{#usersonline#}</a></h2>

			<div id="onlinelist">
				{$cloud|default}
			</div>
	</div>


		{literal}
			  <script type = "text/javascript">
			  new Ajax.Autocompleter('query', 'choices', 'managesearch.php?action=ajaxsearch', {paramName:'query',minChars: 2,indicator: 'indicator1'});
				 var on = new Ajax.PeriodicalUpdater("onlinelist","manageuser.php?action=onlinelist",{method:'get',evalScripts:true,frequency:35,decay:1.5});


			</script>
		{/literal}

</div>