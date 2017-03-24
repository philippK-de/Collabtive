<div class="block_in_wrapper">
	<h2>{#addproject#}</h2>
	<form id="addprojectform" class="main" method="post" action="admin.php?action=addpro">
		<fieldset>
            <!--projectTemplatesSelect-->
			<div class="row">
				<label for="name">{#name#}:</label>
				<input type="text" class="text" name="name" id="name" required="1" />
			</div>

			<div class="row">
				<label for="desc">{#description#}:</label>
				<div class="editor">
					<textarea name="desc" id="desc" rows="3" cols="1"></textarea>
				</div>
			</div>

		    <div class="clear_both_b"></div>

			<div class="row">
				<label for="end">{#due#}:</label>
				<input type="text" class="text" name="end" id="endP" />
			</div>

			<div class="row">
				<label for="neverdue"></label>
				<input type="checkbox" class="checkbox" value="neverdue" name="neverdue" id="neverdue" onclick="document.getElementById('endP').value='';
				document.getElementById('endP').disabled=!document.getElementById('endP').disabled;">
				<label>{#neverdue#}</label>
			</div>

			<div class="datepick">
				<div id="add_project" class="picker display-none"></div>
			</div>

			<div class="row">
				<label for="budget">{#budget#}:</label>
				<input type="text" class="text" name="budget" id="budget" value="0" />
			</div>

			<div class = "row">
				<label>{#customer#}:</label>
				<select name="company" id="company">
					<option value="-1">{#chooseone#}</option>
					{section name=customer loop=$customers}
						<option value = "{$customers[customer].ID}">{$customers[customer].company}</option>
					{/section}
				</select>
			</div>

            <div class="row">
                <label for="assignto">{#members#}:</label>
                <select name="assignto[]" multiple="multiple" style="height:80px;" id="assignto" required>
                    <option value="" disabled>{#chooseone#}</option>
                    {section name=user loop=$users}
                        <option value="{$users[user].ID}" {if $users[user].ID == $userid} selected {/if} >{$users[user].name}</option>
                    {/section}
                </select>
            </div>

			<input type="hidden" name="assignme" value="1" />
		    <div class="clear_both_b"></div>

			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="this.blur();" onclick="tinyMCE.triggerSave();">{#addbutton#}</button>

				<button type="reset" onclick="blindtoggle('form_{$myprojects[project].ID}');return false;">{#cancel#}</button>

			</div>

		</fieldset>
	</form>
    {if $myprojects != 1}
    <script type="text/javascript">
        {literal}
        theCal = new calendar({/literal}{$theM},{$theY}	);
        theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
        theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
        theCal.relateTo = "endP";
        theCal.dateFormat = "{$settings.dateformat}";
        theCal.getDatepicker("add_project");
    </script>
    {/if}
</div> {*block_in_wrapper end*}
