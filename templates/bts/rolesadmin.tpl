	<div class="headline">

				<a href="javascript:void(0);" id="acc-roles_toggle" class="win_block" onclick = "toggleBlock('acc-roles');"></a>


					<div class="wintools">
						<a id = "addrolelink" class="add" href="javascript:blindtoggle('form_addmyroles');" id="add_myroles" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_myroles','butn_link_active','butn_link');toggleClass('sm_myrolls','smooth','nosmooth');"><span>{#addrole#}</span></a>
					</div>

				<h2>
					<img src="./templates/standard/images/symbols/userlist.png" alt="" />{#roles#}
				</h2>
			</div>

			<div class="block" id="acc-roles">

			{*Add Roles*}
				<div id = "form_addmyroles" class="addmenue" style = "display:none;">
				{include file="addroles.tpl" myroles="1"}
				</div>

				<table cellpadding="0" cellspacing="0" border="0">

					<thead>
						<tr>
							<th class="a"></th>
							<th class="b">{#name#}</th>
							<th class="c"></th>
							<th class="tools"></th>
						</tr>
					</thead>

					<tfoot>
						<tr>
							<td colspan="5"></td>
						</tr>
					</tfoot>

					{section name=role loop=$roles}

					{*Color-Mix*}
					{if $smarty.section.role.index % 2 == 0}
					<tbody class="color-a" id="role_{$roles[role].ID}">
					{else}
					<tbody class="color-b" id="role_{$roles[role].ID}">
					{/if}
						<tr>
							<td></td>
							<td>
								<div class="toggle-in">
								<span class="acc-toggle" onclick="accord_roles.activate($$('#acc-roles .accordion_toggle')[{$smarty.section.role.index}]);toggleAccordeon('acc-roles',this);"></span>
									<a href="javascript:accord_roles.activate($$('#acc-roles .accordion_toggle')[{$smarty.section.role.index}]);toggleAccordeon('acc-roles',this);">
										{$roles[role].name|truncate:30:"...":true}
									</a>
								</div>
							</td>
							<td></td>
							<td class="tools">
							<!--	<a class="tool_edit" href="manageproject.php?action=editform&amp;id={$roles[role].ID}" title="{#edit#}" ></a>-->
								<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'role_{$roles[role].ID}\',\'manageroles.php?action=delrole&amp;id={$roles[role].ID}\')');"  title="{#delete#}"></a>
							</td>
						</tr>

					<tr class="acc">
					<td></td>
							<td colspan="4">
								<div class="accordion_toggle"></div>
								<div class="accordion_content">
                                    	<form class="main" method="post" action="manageroles.php?action=editrole&id={$roles[role].ID}" {literal}onsubmit="return validateCompleteForm(this);"{/literal} >
	<fieldset>
		<div class="clear_both_b"></div>
		<div class = "row">
        <label for = "rolename">{#name#}:</label>
        <input type = "text" name = "rolename" id = "rolename" value = "{$roles[role].name}" />
        </div>

        <div class = "row"><label>{#permissions#}:</label>

            {* Permissions for projects *}
            <div class = "row">
            <label></label>
            <label>{#projects#}</label>
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_projects[add]" {if $roles[role].projects.add}checked{/if}  />{#add#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_projects[edit]" {if $roles[role].projects.edit}checked{/if}  />{#edit#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_projects[del]" {if $roles[role].projects.del}checked{/if}  />{#delete#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_projects[close]" {if $roles[role].projects.close}checked{/if}  />{#close#}
            </div>

            {* Permissions for milestones *}
            <div class = "row">
            <label></label>
            <label>{#milestones#}</label>
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_milestones[add]" {if $roles[role].milestones.add}checked{/if}  />{#add#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_milestones[edit]" {if $roles[role].milestones.edit}checked{/if}  />{#edit#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_milestones[del]" {if $roles[role].milestones.del}checked{/if}  />{#delete#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_milestones[close]" {if $roles[role].milestones.close}checked{/if}  />{#close#}
            </div>

            {* Permissions for tasks *}
            <div class = "row">
            <label></label>
            <label>{#tasks#}</label>
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_tasks[add]" {if $roles[role].tasks.add}checked{/if}  />{#add#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_tasks[edit]" {if $roles[role].tasks.edit}checked{/if}  />{#edit#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_tasks[del]" {if $roles[role].tasks.del}checked{/if}  />{#delete#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_tasks[close]" {if $roles[role].tasks.close}checked{/if}  />{#close#}
            </div>

            {* Permissions for messages, close = reply *}
            <div class = "row">
            <label></label>
            <label>{#messages#}</label>
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_messages[add]" {if $roles[role].messages.add}checked{/if}  />{#add#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_messages[edit]" {if $roles[role].messages.edit}checked{/if}  />{#edit#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_messages[del]" {if $roles[role].messages.del}checked{/if}  />{#delete#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_messages[close]" {if $roles[role].messages.close}checked{/if}  />{#answer#}
            </div>

            {* Permissions for files *}
            <div class = "row">
            <label></label>
            <label>{#files#}</label>
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_files[add]" {if $roles[role].files.add}checked{/if}  />{#add#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_files[edit]" {if $roles[role].files.edit}checked{/if}  />{#edit#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_files[del]" {if $roles[role].files.del}checked{/if}   />{#delete#}
            </div>

            {* Permissions for timetracker, read = read other's entries *}
            <div class = "row">
            <label></label>
            <label>{#timetracker#}</label>
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_timetracker[read]" {if $roles[role].timetracker.read}checked{/if}  />{#permissionread#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_timetracker[add]" {if $roles[role].timetracker.add}checked{/if}  />{#add#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_timetracker[edit]" {if $roles[role].timetracker.edit}checked{/if}  />{#edit#}
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_timetracker[del]" {if $roles[role].timetracker.del}checked{/if}  />{#delete#}
            </div>

            <div class = "row">
            <label></label>
            <label>{#chat#}</label>
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_chat[add]" {if $roles[role].chat.add}checked{/if}  />{#chat#}
            </div>


            <div class = "row">
            <label></label>
            <label>{#admin#}</label>
            </div>
            <div class = "row">
            <label></label><input type = "checkbox" class = "checkbox" value = "1" name = "permissions_admin[add]" {if $roles[role].admin.add}checked{/if}  />{#administration#}
            </div>

        </div>

	    <div class="clear_both_b"></div>

		<div class="row-butn-bottom">
    	<label>&nbsp;</label>
		<button type="submit" onfocus="this.blur();">{#edit#}</button>
		<button onclick="javascript:accord_roles.activate($$('#acc-roles .accordion_toggle')[{$smarty.section.role.index}]);toggleAccordeon('acc-roles',this);return false;">{#cancel#}</button>
		</div>

	</fieldset>
	</form>
    </div>

							</td>
						</tr>
					</tbody>
					{/section}

				</table>