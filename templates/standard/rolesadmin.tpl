<div class="user" id="adminRoles">
    <div class="headline">

        <a href="javascript:void(0);" id="acc-roles_toggle" class="win_block" onclick="toggleBlock('acc-roles');"></a>


        <div class="wintools">
            <a id="addrolelink" class="add" href="javascript:blindtoggle('form_addmyroles');" id="add_myroles"
               onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_myroles','butn_link_active','butn_link');toggleClass('sm_myrolls','smooth','nosmooth');"><span>{#addrole#}</span></a>
        </div>

        <h2>
            <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png"
                 alt=""/>{#roles#}
        </h2>
    </div>

    <div class="block" id="acc_roles">

        <!-- Add Roles-->
        <div id="form_addmyroles" class="addmenue" style="display:none;">
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

            {literal}
            <tbody class="alternateColors" v-for="role in items" id="role_{{*role.ID}}">
            <tr>
                <td></td>
                <td>
                    <div class="toggle-in">
                        <span class="acc-toggle"
                              onclick="accord_roles.activate(document.querySelector('#acc_roles_content{{$index}}'));"></span>
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
                    <div class="accordion_content" data-slide="{{$index}}" id="acc_roles_content{{$index}}">
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
                                        <label>{#milestones#}</label>
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
                                        <label>{#tasks#}</label>
                                    </div>
                                    <div class="row">
                                        <label></label>
                                        <input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[view]"
                                                              v-model="role.tasks.view" />{#view#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[add]"
                                                              {if $roles[role].tasks.add}checked{/if} />{#add#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[edit]"
                                                              {if $roles[role].tasks.edit}checked{/if} />{#edit#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[del]"
                                                              {if $roles[role].tasks.del}checked{/if} />{#delete#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_tasks[close]"
                                                              {if $roles[role].tasks.close}checked{/if} />{#close#}
                                    </div>


                                    <!-- Permissions for messages, close = reply -->
                                    <div class="row">
                                        <label></label>
                                        <label>{#messages#}</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[view]"
                                                              {if $roles[role].messages.view}checked{/if} />{#view#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[add]"
                                                              {if $roles[role].messages.add}checked{/if} />{#add#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[edit]"
                                                              {if $roles[role].messages.edit}checked{/if} />{#edit#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[del]"
                                                              {if $roles[role].messages.del}checked{/if} />{#delete#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_messages[close]"
                                                              {if $roles[role].messages.close}checked{/if} />{#answer#}
                                    </div>

                                    <!-- Permissions for files -->
                                    <div class="row">
                                        <label></label>
                                        <label>{#files#}</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[view]"
                                                              {if $roles[role].files.view}checked{/if} />{#view#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[add]"
                                                              {if $roles[role].files.add}checked{/if} />{#add#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[edit]"
                                                              {if $roles[role].files.edit}checked{/if} />{#edit#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_files[del]"
                                                              {if $roles[role].files.del}checked{/if} />{#delete#}
                                    </div>

                                    <!-- Permissions for timetracker, read = read other's entries -->
                                    <div class="row">
                                        <label></label>
                                        <label>{#timetracker#}</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[view]"
                                                              {if $roles[role].timetracker.view}checked{/if} />{#view#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[read]"
                                                              {if $roles[role].timetracker.read}checked{/if}
                                        />{#permissionread#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[add]"
                                                              {if $roles[role].timetracker.add}checked{/if} />{#add#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[edit]"
                                                              {if $roles[role].timetracker.edit}checked{/if} />{#edit#}
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_timetracker[del]"
                                                              {if $roles[role].timetracker.del}checked{/if} />{#delete#}
                                    </div>

                                    <div class="row">
                                        <label></label>
                                        <label>{#chat#}</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_chat[add]"
                                                              {if $roles[role].chat.add}checked{/if} />{#chat#}
                                    </div>


                                    <div class="row">
                                        <label></label>
                                        <label>{#admin#}</label>
                                    </div>
                                    <div class="row">
                                        <label></label><input type="checkbox" class="checkbox" value="1"
                                                              name="permissions_admin[add]"
                                                              {if $roles[role].admin.add}checked{/if}
                                        />{#administration#}
                                    </div>

                                </div>

                                <div class="clear_both_b"></div>

                                <div class="row-butn-bottom">
                                    <label>&nbsp;</label>
                                    <button type="submit" onfocus="this.blur();">{/literal}{#save#}{literal}</button>
                                    <button onclick="javascript:accord_roles.activate(document.querySelector('#acc-roles .accordion_toggle')[{$smarty.section.role.index}]);">{#cancel#}
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