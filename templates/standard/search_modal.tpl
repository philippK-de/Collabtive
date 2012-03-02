<a href = "#logme" id = "logit" ></a>
<div id = "logme">

<div class="search-modal">
	<h2>{#search#}</h2>
			
			<form id = "search" method = "get" action = "managesearch.php" {literal} onsubmit="return validateStandard(this,'input_error');"{/literal}>
			<fieldset>
				<div class = "row">
					<input type="text" class = "text" id="query_modal" name="query" />
				</div>
			
				<div id="choices_modal"></div>
				<input type = "hidden" name = "action" value = "search" />
				
				<div id="indicator2" style="display:none;"><img src="templates/standard/images/symbols/indicator_arrows.gif" alt="{#searching#}" /></div>
				
				<button type="submit" onfocus="this.blur()" title="{#gosearch#}"></button>
			</fieldset>

			</form>
</div>
</div>



{literal}
<script type = "text/javascript">
  new Ajax.Autocompleter('query_modal', 'choices_modal', 'managesearch.php?action=ajaxsearch', {paramName:'query',minChars: 2,indicator: 'indicator2'});
			
	mo = new Control.Modal('logit',{
        opacity: 0.8,
        position: 'absolute',
        width: 300,
        height: 150,
        fade:true
	});
	</script>
{/literal}