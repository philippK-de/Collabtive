<div class="span3 bs-sidebar">
	<div id="content-right" class="well">
		{*Search*}
		<div class="content-right-in clearfix">
			<h4><a id="searchtoggle" class="win-up" href="javascript:blindtoggle('search');toggleClass('searchtoggle','win-up','win-down');">{#search#}</a></h4>

			<form class="form-search" id="search" method="get" action="managesearch.php" {literal} onsubmit="return validateStandard(this,'input_error');"{/literal}>
				<div class="input-append">
					<input type="text" class="search-query" id="query" name="query" data-provide="typeahead" />
					<input type="hidden" name="action" value="search" />
					<button type="submit" title="{#gosearch#}" class="btn" data-loading-text="{#searching#}">{#gosearch#}</button>
				</div>
			</form>
		</div>
		{*Search End*}

		{*Quickfinder*}
		{if $openProjects[0].ID > 0}
		<div class="content-right-in clearfix">
			<h4><a id="quickfindertoggle" class="win-up" href="javascript:blindtoggle('quickfinder');toggleClass('quickfindertoggle','win-up','win-down');">{#myprojects#}</a></h4>
			<div id="quickfinder">
				<form>
					<select class="span12" onchange="window.location='manageproject.php?action=showproject&id='+this.value;">
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
		<div class="content-right-in clearfix">
			<h4><a id="onlinelisttoggle" class="win-up" href="javascript:blindtoggle('onlinelist');toggleClass('onlinelisttoggle','win-up','win-down');">{#usersonline#}</a></h4>
			<div id="onlinelist">
				{$cloud}
			</div>
		</div>

		{literal}
		<script type="text/javascript">
		jQuery("#query").typeahead({
			source: function (query, process) {
				return $.get('managesearch.php?action=searchjson', { query: query }, function (data) {
					return process(data);
				});
			}
		});
		/*
			new Ajax.Autocompleter('query', 'choices', 'managesearch.php?action=ajaxsearch', {
				paramName : 'query',
				minChars : 2,
				indicator : 'indicator1'
			});
		*/	
			var on = new Ajax.PeriodicalUpdater("onlinelist", "manageuser.php?action=onlinelist", {
				method : 'get',
				evalScripts : true,
				frequency : 45,
				decay : 1.5
			});

		</script>
		{/literal}
	</div>
</div>