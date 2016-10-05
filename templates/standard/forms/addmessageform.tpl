<div class="block_in_wrapper">

	<h2>{#addmessage#}</h2>

	<form name="addmessageform" id="addmessageform" class="main" method="post" enctype="multipart/form-data" action="managemessage.php?action=add&amp;id={$project.ID}" >
		<fieldset>
            <div class="row">
                <label>Recipient:</label>
                <select name="privateRecipient">
                    <option value="" disabled style="color:black;font-weight:bold;">{#general#}</option>
                    <option value="0" selected>{#all#}</option>
                    <option value="" disabled style="color:black;font-weight:bold;">{#members#}</option>
                    {section name=member loop=$members}
                        <option value="{$members[member].ID}">{$members[member].name}</option>
                    {/section}
                </select>
            </div>
			<div class="row">
				<label for="title">{#title#}:</label>
				<input type="text" class="text" name="title" id="title" required />
			</div>

			<div class="row">
				<label for="text">{#text#}:</label>
				<div class="editor">
					<textarea name="text" id="text" rows="3" cols="1"></textarea>
				</div>
			</div>

			{*Attach*}
			<div class="row">
				<label for="thefiles">{#attachfile#}:</label>
				<select name="thefiles" id="thefiles">
					<option value="0">{#chooseone#}</option>
					{section name=file loop=$files}
						<option value="{$files[file].ID}">{$files[file].name}</option>
					{/section}
					{section name=file loop=$myprojects[project].files}
						<option value="{$myprojects[project].files[file].ID}">{$myprojects[project].files[file].name}</option>
					{/section}
				</select>
			</div>

			<div class="row">
				<label for="milestone">{#milestone#}:</label>
				<select name="milestone" id="milestone">
					<option value="0" selected="selected">{#chooseone#}</option>
					{section name=stone loop=$milestones}
						<option value="{$milestones[stone].ID}">{$milestones[stone].name}</option>
					{/section}
				</select>
			</div>

           {if $settings.mailnotify == 1}
			<div class="row">
				<label>{#notify#}:</label>
				<select name="sendto[]" multiple style="height:100px;">
					<option value="" disabled style="color:black;font-weight:bold;">{#general#}</option>
					<option value="all" selected>{#all#}</option>
					<option value="none" >{#none#}</option>
					<option value="" disabled style="color:black;font-weight:bold;">{#members#}</option>
					{section name=member loop=$members}
						<option value="{$members[member].ID}">{$members[member].name}</option>
					{/section}
				</select>
			</div>
            {/if}

			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="this.blur()">{#send#}</button>
				<button onclick="blindtoggle('addmsg');toggleClass('add','add-active','add');toggleClass('add_butn','butn_link_active','butn_link');toggleClass('sm_msgs','smooth','nosmooth');return false;" onfocus="this.blur()">{#cancel#}</button>
			</div>

		</fieldset>
	</form>

</div> {*block_in_wrapper end*}
