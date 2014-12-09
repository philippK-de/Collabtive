<div class="block_in_wrapper">
	
	<h2>{#filterreport#}</h2>

	<form class="main" method="get" action="manageuser.php" {literal} onsubmit="return validateCompleteForm(this);" {/literal} >
		<fieldset>
			
			<div class="row">
				<label for="start">{#start#}:</label>
				<input type="text" name="start" id="start" onfocus="dpck.close();" value="{$start}" />
			</div>
			
			<div class="datepick">
				<div id="datepicker_startfilter" class="picker" style="display:none;"></div>
			</div>
			
			<div class="row">
				<label for="end">{#end#}:</label>
				<input type="text" name="end" id="end" onfocus="dpck2.close();" value="{$end}" />
			</div>
			
			<div class="datepick">
				<div id="datepicker_endfilter" class="picker" style="display:none;"></div>
			</div>
			
			<div class="row">
				<label for="fproject">{#project#}</label>
				<select name="project" id="fproject">
					<option value="">{#chooseone#}</option>
					{section name=proj loop=$opros}
						<option value="{$opros[proj].ID}" {if $opros[proj].ID == $fproject} selected="selected" {/if} >
							{$opros[proj].name}
						</option>
					{/section}
				</select>
			</div>
			
			<input type="hidden" name="action" value="profile" />
			
			<input type="hidden" name="id" value="{$user.ID}" />
			
			<script type="text/javascript">
				theCal = new calendar({$theM},{$theY});
				theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
				theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
				theCal.relateTo = "start";
				theCal.keepEmpty = true;
				theCal.dateFormat = "{$settings.dateformat}";
				theCal.getDatepicker("datepicker_startfilter");
			</script>
			
			<script type="text/javascript">
				theCal2 = new calendar({$theM},{$theY});
				theCal2.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
				theCal2.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
				theCal2.relateTo = "end";
				theCal2.keepEmpty = true;
				theCal2.dateFormat = "{$settings.dateformat}";
				theCal2.getDatepicker("datepicker_endfilter");
			</script>
			
			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="this.blur();">{#filter#}</button>
				<button onclick= "javascript:blindtoggle('form_filter');toggleClass('filter_report','filter-active','filter');toggleClass('filter_butn','butn_link_active','butn_link');toggleClass('sm_report','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
			</div>
			
		</fieldset>
	</form>

</div> {*block_in_wrapper end*}
