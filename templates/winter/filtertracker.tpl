<div class="block_in_wrapper">
<h2>{#filterreport#}</h2>

	<form class="main" method="post" action="managetimetracker.php?action=showproject&id={$project.ID}" {literal}onsubmit="return validateCompleteForm(this);"{/literal} >
	<fieldset>
		<div class="row"><label for="start">{#start#}:</label><input type="text" class="text" name="start" id="start"  realname="{#start#}" onfocus = "dpck.close();" value = "{$start}" /></div>

			<div class="datepick">
				<div id = "datepicker_startfilter" class="picker" style = "display:none;"></div>
			</div>

		<div class="row"><label for="end">{#end#}:</label><input type="text" class="text" name="end" id="end"  realname="{#end#}" onfocus = "dpck2.close();" value = "{$end}"/></div>

			<div class="datepick">
				<div id = "datepicker_endfilter" class="picker" style = "display:none;"></div>
			</div>

	{if $userpermissions.admin.add}

		<div class="row">
			<label for="usr">{#user#}:</label>
			<select name="usr" id="usr"  realname="{#user#}">
				<option value = "">{#chooseone#}</option>
				{section name = usi loop=$users}
				<option value = "{$users[usi].ID}" {if $users[usi].ID == $usr}selected="selected"{/if}>{$users[usi].name}</option>
				{/section}
			</select>
		</div>

	{else}
			<input type = "hidden" name="usr" id="usr" value = "{$usr}" />

	{/if}

		<div class = "row">
			<label for = "ttask">{#task#}:</label>
			<select name = "task[]" id = "ttask"  multiple style = "height:80px;" >
		  		<option value = "" >{#chooseone#}</option>
			  	{section name = task loop=$ptasks}
			  		{if $ptasks[task].title != ""}
			  		<option value = "{$ptasks[task].ID}" {if $ptasks[task].ID == $task}selected="selected"{/if}>{$ptasks[task].title}</option>
			  		{else}
			  		<option value = "{$ptasks[task].ID}" {if $ptasks[task].ID == $task}selected="selected"{/if}>{$ptasks[task].text|truncate:30:"...":true}</option>
					{/if}
				{/section}
		  	</select>
		</div>

		<input type = "hidden" name = "action" value ="showproject" />
		<input type = "hidden" name = "id" value="{$project.ID}" />

		<script type="text/javascript">
			theCal = new calendar({$theM},{$theY});
			theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
			theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
			theCal.relateTo = "start";
			theCal.keepEmpty = true;
			theCal.getDatepicker("datepicker_startfilter");
	        </script>
			<script type="text/javascript">
			theCal2 = new calendar({$theM},{$theY});
			theCal2.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
			theCal2.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
			theCal2.relateTo = "end";
			theCal2.keepEmpty = true;
			theCal2.getDatepicker("datepicker_endfilter");
        </script>


		<div class="row-butn-bottom">
			<label>&nbsp;</label>
			<button type="submit" onfocus="this.blur();">{#filter#}</button>
			<button onclick= "javascript:blindtoggle('filter');toggleClass('filter_report','filter-active','filter');toggleClass('filter_butn','butn_link_active','butn_link');toggleClass('sm_report','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
		</div>


	</fieldset>
	</form>

</div> {*block_in_wrapper end*}