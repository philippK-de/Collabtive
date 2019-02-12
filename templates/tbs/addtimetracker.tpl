<form novalidate class="main form-horizontal" id="trackeradd" method="post" action="managetimetracker.php?action=add" {literal}onsubmit="return validateCompleteForm(this,'input_error');{/literal}">
	<fieldset>
		<legend>{#timetracker#}</legend>
		<input type="hidden" name="project" value="{$project.ID}" />

	 	<div class="control-group">
	  		<label class="control-label" for="ttday">{#day#}:</label>
	  		<div class="controls">
	  			<div class="input-append date" id="dp" data-date="{$smarty.now|date_format:'%d-%m-%Y'}" data-date-format="dd-mm-yyyy">
	  			<input type="text" name="ttday" value="{$smarty.now|date_format:'%d-%m-%Y'}" readonly />
	  			<span class="add-on"><i class="icon-th"></i></span>
	  			</div>
	  		</div>
		</div>
	  	<div class="control-group">
	  		<label class="control-label" for="started">{#started#}:</label>
	  		<div class="controls">
	  			<input type="text" class="text" id="started" name="started" required="1" regexp="^([01]?\d|2[0123]):[012345]\d$" realname="{#started#} (Format: hh:mm)" />
	  			<button class="btn" onclick="getnow('started');return false;" onfocus="this.blur();" title="{#inserttime#}">hh:mm</button>
	  		</div>
	  	</div>
	  	<div class="control-group">
	  		<label class="control-label" for="ended">{#ended#}:</label>
	  		<div class="controls">
	  			<input  type="text" class="text" id="ended" name="ended"  required="1" regexp="^([01]?\d|2[0123]):[012345]\d$" realname="{#ended#} (Format: hh:mm)" / >
				<button class="btn" onclick="getnow('ended');return false;" onfocus="this.blur();" title="{#inserttime#}">hh:mm</button>
			</div>
		</div>
	  	<div class="control-group">
	  		<label class="control-label" for="trackcomm">{#comment#}:</label>
	  		<div class="controls">
	  			<textarea name="comment" id="trackcomm"></textarea>
	  		</div>
	  	</div>
	  	<div class="control-group">
			<label class="control-label" for="ttask">{#task#}:</label>
			<div class="controls">
				<select name="ttask" id="ttask" >
		  			<option value="0" >{#chooseone#}</option>
		  			{section name = task loop=$ptasks}
		  			{if $ptasks[task].title != ""}
		  			<option value="{$ptasks[task].ID}">{$ptasks[task].title}</option>
		  			{else}
		  			<option value="{$ptasks[task].ID}">{$ptasks[task].text|truncate:30:"...":true}</option>
					{/if}
					{/section}
	  			</select>
	  		</div>
	  	</div>
		<div class="row-butn-bottom">
			<button type="submit" class="btn btn-primary" onfocus="this.blur();">{#addbutton#}</button>
		</div>
	</fieldset>
</form>
