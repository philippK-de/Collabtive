<script type="text/javascript" src="include/js/timetracker_widget.js"></script>
<div class="block_in_wrapper">

	<form novalidate class="main" id="trackeradd" method="post" action="managetimetracker.php?action=add" {literal} onsubmit="return validateCompleteForm(this,'input_error'); {/literal} ">
		<fieldset>

			<input type="hidden" name="project" value="{$project.ID}" />

		 	<div class="row">
		  		<label for="ttday">{#startday#}:</label>
		  		<input type="text" class="text" style="width:80px;margin:0 6px 0 0;" id="ttday" name="ttday" realname="{#date#}" />
			</div>

			<div class="datepick">
				<div id="datepicker_addtt" class="picker" style="display:none;"></div>
			</div>

			<script type="text/javascript">
				theCal = new calendar({$theM},{$theY});
				theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
				theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
				theCal.relateTo = "ttday";
				theCal.keepEmpty = false;
				theCal.dateFormat = "{$settings.dateformat}";
				theCal.getDatepicker("datepicker_addtt");
			</script>

		  	<div class="row">
		  		<label for="started">{#started#}:</label>
		  		<input type="text" class="text" style="width:80px;margin:0 6px 0 0;" id="started" name="started" onkeyup=" populateHours();" required="1" regexp="^([01]?\d|2[0123]):[012345]\d$" realname="{#started#} ({#timeformat#}: hh:mm)" value="08:00" />

				<label for = "ended">Endtime:</label>
		  	<input type="text" class="text" style="width:80px;margin:0 6px 0 0;" id="ended" name="ended" onkeyup = " populateHours();" required="1" regexp="^([01]?\d|2[0123]):[012345]\d$" realname="{#started#} ({#timeformat#}: hh:mm)" value="09:00" />

				<label for = "workhours" >Hours:</label>
		  		<input type = "number" id = "workhours" name = "workhours" value = "1" min = "1" max = "10" step = "1" onkeyup = "populateEndtime();" onchange = "populateEndtime();" style="width:40px;"/>


		  		<label for = "repeatTT">Repeat:</label>
				<input type = "range" id = "repeatTT" name = "repeatTT" value = "0" min = "0" max = "7" style = "width:60px;margin:0;border:0px;" onchange="$('repeatShow').value=this.value;" oninput="$('repeatShow').value=this.value;" />
				<span id  = "lala" style="float:left;"><input type = "text" id = "repeatShow" disabled value = "0" style = "text-align:center;width:15px;background-color:white;"></span>
			</div>

			<input type="hidden" name="project" value="{$project.ID}" />
<!--
		 	<div class="row">
		  		<label for="ttendday">{#endday#}:</label>
		  		<input type="text" class="text" style="width:80px;margin:0 6px 0 0;" id="ttendday" name="ttendday" realname="{#date#}" />
			</div>

			<div class="datepick">
				<div id="datepicker_addttend" class="picker" style="display:none;"></div>
			</div>

			<script type="text/javascript">
				theCal2 = new calendar({$theM},{$theY});
				theCal2.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
				theCal2.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
				theCal2.relateTo = "ttendday";
				theCal2.keepEmpty = false;
				theCal2.dateFormat = "{$settings.dateformat}";
				theCal2.getDatepicker("datepicker_addttend");
			</script>

		  	<div class="row">
		  		<label for="ended">{#ended#}:</label>
				<input type="text" class="text" style="width:80px;margin:0 6px 0 0;" id="ended" name="ended" required="1" regexp="^([01]?\d|2[0123]):[012345]\d$" realname="{#ended#} ({#timeformat#}: hh:mm)" />
				<button onclick="getnow('ended');return false;" onfocus="this.blur();" title="{#inserttime#}">hh:mm</button>
			</div>
-->
		  	<div class="row">
		  		<label for="trackcomm">{#comment#}:</label>
		  		<textarea name="comment" id="trackcomm"></textarea>
		  	</div>

		  	<div class="clear_both_b"></div>

		  	<div class="row">
				<label for="ttask">{#task#}:</label>
				<select name="ttask" id="ttask">
				  	<option value="0">{#chooseone#}</option>
				  	{section name=task loop=$ptasks}

				  		{if $ptasks[task].title != ""}
				  		<option value="{$ptasks[task].ID}">{$ptasks[task].title}</option>
				  		{else}
				  		<option value="{$ptasks[task].ID}">{$ptasks[task].text|truncate:30:"...":true}</option>
						{/if}

					{/section}
			  	</select>
		  	</div>

			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="this.blur();">{#addbutton#}</button>
			</div>

		</fieldset>
	</form>

</div> {*block_in_wrapper end*}
