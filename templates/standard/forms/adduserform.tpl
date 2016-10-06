<div class="block_in_wrapper">
	<form novalidate class="main" method="post" action="admin.php?action=adduser" {literal} onsubmit="return validateCompleteForm(this);" {/literal} >
		<fieldset>

			<div class="row">
				<label for="name">{#name#}:</label>
				<input type="text" name="name" id="name" required="1" realname="{#name#}" />
			</div>

            <div class="row">
                <label for="pass">{#password#}:</label>
                <input type="password" name="pass" id="pass" required="1" realname="{#password#}" />
            </div>

            <div class="row">
                <label>{#role#}:</label>
                <select name="role" realname="{#role#}" required="1" exclude="-1" id="roleselect">
                    <option value="-1" selected="selected">{#chooseone#}</option>
                    {section name=role loop=$roles}
                        <option value="{$roles[role].ID}" id="role{$roles[role].ID}">{$roles[role].name}</option>
                    {/section}
                </select>
            </div>

            <div class="row">
                <label for="email">{#email#}:</label>
                <input type="text" name="email" id="email" realname="{#email#}" />
            </div>

			<div class="row">
				<label for="company">{#company#}:</label>
				<input type="text" name="company" id="company" realname="{#company#}" />
			</div>

			<div class="row">
				<label id="rate">{#rate#}:</label>
				<input type="text" name="rate" id="rate" />
			</div>

			{if $projects} {*only show this block if there are any projects to choose*}
			<div class="clear_both_b"></div>

            <div class="row">
                <label for="assignto">{#projects#}:</label>
                <select name="assignto[]" multiple="multiple" style="height:80px;" id="assignto">
                    <option value="" disabled>{#chooseone#}</option>
                    {section name=project loop=$projects}
                        <option value="{$projects[project].ID}">{$projects[project].name}</option>
                    {/section}
                </select>
            </div>
			{/if}

            <div class="clear_both_b"></div>

			<div class="row">
				<label>&nbsp;</label>
				<div class="butn">
					<button type="submit">{#addbutton#}</button>
				</div>
				<a href = "javascript:blindtoggle('form_member');" class="butn_link"><span>{#cancel#}</span></a>
			</div>

		</fieldset>
	</form>

</div> {*block_in_wrapper end*}
