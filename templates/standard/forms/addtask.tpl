<div class="block_in_wrapper">
	<h2>{#addtask#}</h2>
	<form
          data-index="{$smarty.section.list.index}"
          data-tasklist="{$lists[list].ID}"
          data-project="{$project.ID}"
          name="addtaskform{$lists[list].ID}"
          id="addtaskform{$lists[list].ID}"
          class="main taskSubmitForm" method="post" action="managetask.php?action=add&amp;id={$project.ID}">
		<fieldset>

			<div class="row">
				<label for="title">{#title#}:</label>
				<input type="text" class="text" name="title" id="title" required />
			</div>

			<div class="row">
				<label for="text">{#text#}:</label>
				<div class="editor">
					<textarea name="text" id="text{$lists[list].ID}" rows="3" cols="1"></textarea>
				</div>
			</div>


			<div class="row">
				<label for="start_{$lists[list].ID}">{#start#}:</label>
				<input type="text" class="text" name="start" id="start_{$lists[list].ID}" required />
			</div>

			<div class="datepick">
				<div id="datepicker_start_task{$lists[list].ID}" class="picker display-none"></div>
			</div>

			<script type="text/javascript">
			  	theCalStart{$lists[list].ID} = new calendar({$theM},{$theY});
				theCalStart{$lists[list].ID}.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
				theCalStart{$lists[list].ID}.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
				theCalStart{$lists[list].ID}.relateTo = "start_{$lists[list].ID}";
				theCalStart{$lists[list].ID}.dateFormat = "{$settings.dateformat}";
				theCalStart{$lists[list].ID}.getDatepicker("datepicker_start_task{$lists[list].ID}");
			</script>


			<div class="row">
				<label for="end_{$lists[list].ID}">{#due#}:</label>
				<input type="text" class="text" name="end" id="end_{$lists[list].ID}" required />
			</div>

			<div class="datepick">
				<div id="datepicker_end_task{$lists[list].ID}" class="picker display-none"></div>
			</div>

			<script type="text/javascript">
			  	theCalEnd{$lists[list].ID} = new calendar({$theM},{$theY});
				theCalEnd{$lists[list].ID}.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
				theCalEnd{$lists[list].ID}.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
				theCalEnd{$lists[list].ID}.relateTo = "end_{$lists[list].ID}";
				theCalEnd{$lists[list].ID}.dateFormat = "{$settings.dateformat}";
				theCalEnd{$lists[list].ID}.getDatepicker("datepicker_end_task{$lists[list].ID}");
			</script>


			<div class="row">
				<label for="assigned">{#assignto#}:</label>
				<select name="assigned[]" multiple="multiple" style="height:80px;" id="assigned" required>
					<option value="">{#chooseone#}</option>
					{section name=user loop=$assignable_users}
						<option value="{$assignable_users[user].ID}" {if $assignable_users[user].ID == $userid} selected {/if} >{$assignable_users[user].name}</option>
					{/section}
				</select>
			</div>

			{if $lists[list].ID != ""}
			<input type="hidden" value="{$lists[list].ID }" name="tasklist" />
			{else}
			<input type="hidden" value="{$tasklist.ID }" name="tasklist" />
			{/if}

			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="tinyMCE.triggerSave();this.blur();">{#addbutton#}</button>
				<button type="reset" onclick="blindtoggle('form_{$lists[list].ID}');toggleClass('add_{$lists[list].ID}','add-active','add');toggleClass('add_butn_{$lists[list].ID}','butn_link_active','butn_link');toggleClass('sm_{$lists[list].ID}','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
			</div>

		</fieldset>
	</form>

</div> {*block_in_wrapper end*}
