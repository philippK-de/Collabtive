<script type="text/javascript" src="include/js/timetracker_widget.js"></script>
<div class="block_in_wrapper">

	<form class="main" id="trackeradd" method="post" action="managetimetracker.php?action=add" {literal} onsubmit="return validateCompleteForm(this,'input_error'); {/literal} ">
		<fieldset>

			<input type="hidden" name="project" value="{$project.ID}" />

		 	<div class="row">
		  		<label for="ttday">{#startday#}:</label>
		  		<input type="text" class="text" style="width:80px;margin:0 6px 0 0;" id="ttday" name="ttday" required />
			</div>

			<div class="datepick">
				<div id="datepicker_addtt" class="picker display-none"></div>
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
		  		<input type="text" class="text" style="width:80px;margin:0 6px 0 0;" id="started" name="started" onkeyup=" populateHours();" required  pattern="^([01]?\d|2[0123]):[012345]\d$" value="08:00" />
			</div>
			<div class="row">
				<label for = "ended">{#ended#}:</label>
		  		<input type="text" class="text" style="width:80px;margin:0 6px 0 0;" id="ended" name="ended" onkeyup = " populateHours();" required pattern="^([01]?\d|2[0123]):[012345]\d$" value="09:00" />
			</div>

<!-- Requires rework to enable submitting times <1 hour
			<div class = "row">
				<label for = "workhours" >Hours:</label>
		  		<input type = "number" id = "workhours" name = "workhours" value = "1" min = "1" max = "10" step = "1" onkeyup = "populateEndtime();" onchange = "populateEndtime();" style="width:40px;"/>
			</div>
-->

			<input type="hidden" name="project" value="{$project.ID}" />

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
