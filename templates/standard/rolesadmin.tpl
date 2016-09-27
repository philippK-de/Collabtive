<div class="user" id="adminRoles">
    <div class="headline">
        <a href="javascript:void(0);" id="acc-roles_toggle" class="win_block" onclick="toggleBlock('acc-roles');"></a>
        <div class="wintools"></div>
        <h2>
            <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png"
                 alt=""/>{#roles#}
        </h2>
    </div>

    <div class="block" id="acc_roles">

        <!-- Add Roles-->
        <div id="form_addmyroles" class="addmenue display-none">
            {include file="forms/addroles.tpl" myroles="1"}
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

            {literal}
            <tbody class="alternateColors" v-for="role in items" id="role_{{*role.ID}}">
            <tr>
                <td></td>
                <td>
                    <div class="toggle-in">
                        <span class="acc-toggle"
                              onclick="accord_roles.toggle(css('#acc_roles_content{{$index}}'));"></span>
                        <a href="#">
                            {{*role.name}}
                        </a>
                    </div>
                </td>
                <td></td>
                <td class="tools">
                    <!--	<a class="tool_edit" href="manageproject.php?action=editform&amp;id={{*role..ID}" title="{#edit#}" ></a>-->
                    <a class="tool_del"
                       href="javascript:confirmDelete('{/literal}{#confirmdel#}{literal}','role_{{*role.ID}}','manageroles.php?action=delrole&amp;id={{*role.ID}}',adminRolesView);"
                       title="{#delete#}"></a>
                </td>
            </tr>
            <tr class="acc">
                <td></td>
                <td colspan="4">
                    <div class="accordion_content">
                        <form class="main" method="post" action="manageroles.php?action=editrole&id={{*role.ID}}"
                              onsubmit="return validateCompleteForm(this);">
                            <fieldset>
                                <div class="clear_both_b"></div>
                                <div class="row">
                                    <label for="rolename">{/literal}{#name#}{literal}:</label>
                                    <input type="text" name="rolename" id="rolename" v-model="role.name"/>
                                </div>

                                <div class="row"><label>{/literal}{#permissions#}{literal}:</label>

                                    <!-- Permissions for projects -->
                                    <div class="row">
                                        <label></label>
                                        <label>{/literal}{#projects#}{literal}</label>
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                               name="permissions_projects[add]"
                                               v-model="role.projects.add"/>{/literal}{#add#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                               name="permissions_projects[edit]"
                                               v-model="role.projects.edit"/>{/literal}{#edit#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                               name="permissions_projects[del]"
                                               v-model="role.projects.del"/>{/literal}{#delete#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                               name="permissions_projects[close]"
                                               v-model="role.projects.close"/>{/literal}{#close#}{literal}
                                    </div>

                                    <!-- Permissions for milestones -->
                                    <div class="row">
                                        <label></label>
                                        <label>{/literal}{#milestones#}{literal}</label>
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                               name="permissions_milestones[view]"
                                               v-model="role.milestones.view"/>{/literal}{#view#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_milestones[add]"
                                                              v-model="role.milestones.add"/>{/literal}{#add#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_milestones[edit]"
                                                              v-model="role.milestones.edit"/>{/literal}{#edit#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_milestones[del]"
                                                              v-model="role.milestones.del"/>{/literal}{#delete#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_milestones[close]"
                                                              v-model="role.milestones.close"/>{/literal}{#close#}{literal}
                                    </div>

                                    <!-- Permissions for tasks -->
                                    <div class="row">
                                        <label></label>
                                        <label>{/literal}{#tasks#}{literal}</label>
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[view]"
                                                              v-model="role.tasks.view" />{/literal}{#view#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[add]"
                                                              v-model="role.tasks.add" />{/literal}{#add#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[edit]"
                                                              v-model="role.tasks.edit" />{/literal}{#edit#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[del]"
                                                              v-model="role.tasks.del" />{/literal}{#delete#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[close]"
                                                              v-model="role.tasks.close" />{/literal}{#close#}{literal}
                                    </div>


                                    <!-- Permissions for messages, close = reply -->
                                    <div class="row">
                                        <label></label>
                                        <label>{/literal}{#messages#}{literal}</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[view]"
                                                              v-model="role.messages.view" />{/literal}{#view#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[add]"
                                                              v-model="role.messages.add" />{/literal}{#add#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[edit]"
                                                              v-model="role.messages.edit" />{/literal}{#edit#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[del]"
                                                              v-model="role.messages.del" />{/literal}{#delete#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[close]"
                                                              v-model="role.messages.close" />{/literal}{#answer#}{literal}
                                    </div>

                                    <!-- Permissions for files -->
                                    <div class="row">
                                        <label></label>
                                        <label>{/literal}{#files#}{literal}</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[view]"
                                                              v-model="role.files.view" />{/literal}{#view#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[add]"
                                                              v-model="role.files.add" />{/literal}{#add#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[edit]"
                                                              v-model="role.files.edit" />{/literal}{#edit#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[del]"
                                                              v-model="role.files.del" />{/literal}{#delete#}{literal}
                                    </div>

                                    <!-- Permissions for timetracker, read = read other's entries -->
                                    <div class="row">
                                        <label></label>
                                        <label>{/literal}{#timetracker#}{literal}</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[view]"
                                                              v-model="role.timetracker.view" />{/literal}{#view#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[read]"
                                                              v-model="role.timetracker.read"
                                        />{/literal}{#permissionread#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[add]"
                                                              v-model="role.timetracker.add" />{/literal}{#add#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[edit]"
                                                              v-model="role.timetracker.edit" />{/literal}{#edit#}{literal}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[del]"
                                                              v-model="role.timetracker.del" />{/literal}{#delete#}{literal}
                                    </div>

                                    <div class="row">
                                        <label></label>
                                        <label>{/literal}{#chat#}{literal}</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_chat[add]"
                                                              v-model="role.chat.add" />{/literal}{#chat#}{literal}
                                    </div>
                                   <div class="row">
                                        <label></label>
                                        <label>{/literal}{#admin#}{literal}</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_admin[add]"
                                                              v-model="role.admin.add"
                                        />{/literal}{#administration#}{literal}
                                    </div>

                                </div>

                                <div class="clear_both_b"></div>

                                <div class="row-butn-bottom">
                                    <label>&nbsp;</label>
                                    <button type="submit" onfocus="this.blur();">{/literal}{#save#}{literal}</button>
                                    <button onclick="javascript:accord_roles.activate(css('#acc-roles .accordion_toggle')[{$smarty.section.role.index}]);">{/literal}{#cancel#}{literal}
                                    </button>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                </td>
            </tr>
            </tbody>
            {/literal}

        </table>

        <!-- add role menu -->
        <div class="tablemenue">
            <div class="tablemenue-in">

                {if $userpermissions.admin.add}
                    <a class="butn_link" href="javascript:blindtoggle('form_addmyroles');" id="add_butn_myprojects"
                       onclick="toggleClass('addrolelink','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');">
                        {#addrole#}
                    </a>
                {/if}

            </div>
        </div>
    </div>
    <!-- admin roles end -->