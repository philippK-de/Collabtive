<div class="block_in_wrapper">
    <h2>{#addrole#}</h2>
    <form id="addRoleForm" class="main" method="post" action="manageroles.php?action=addrole">
        <fieldset>

            <div class="row">
                <label for="name">{#name#}:</label>
                <input type="text" class="text" name="name" id="name" required="1" realname="{#name#}"/>
            </div>
            <div class="clear_both_b"></div>
            <div class="row">
                <label>{#permissions#}:</label>

                <div class="row"><label></label>
                    <label>{#projects#}</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_projects[add]"/>{#add#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_projects[edit]"/>{#edit#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_projects[del]"/>{#delete#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_projects[close]"/>{#close#}
                </div>

                <div class="row"><label></label>
                    <label>{#milestones#}</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_milestones[view]"/>{#view#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_milestones[add]"/>{#add#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_milestones[edit]"/>{#edit#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_milestones[del]"/>{#delete#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_milestones[close]"/>{#close#}
                </div>

                <div class="row"><label></label>
                    <label>{#tasks#}</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_tasks[view]"/>{#view#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_tasks[add]"/>{#add#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_tasks[edit]"/>{#edit#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_tasks[del]"/>{#delete#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_tasks[close]"/>{#close#}
                </div>

                <div class="row"><label></label>
                    <label>{#messages#}</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_messages[view]"/>{#view#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_messages[add]"/>{#add#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_messages[edit]"/>{#edit#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_messages[del]"/>{#delete#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_messages[close]"/>{#answer#}
                </div>

                <div class="row"><label></label>
                    <label>{#files#}</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_files[view]"/>{#view#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_files[add]"/>{#add#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_files[edit]"/>{#edit#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_files[del]"/>{#delete#}
                </div>

                <div class="row"><label></label>
                    <label>{#timetracker#}</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_timetracker[view]"/>{#view#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_timetracker[read]"/>{#permissionread#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_timetracker[add]"/>{#add#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_timetracker[edit]"/>{#edit#}
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_timetracker[del]"/>{#delete#}
                </div>

                <div class="row"><label></label>
                    <label>{#chat#}</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_chat[add]" {if $roles[role].chat.add} checked {/if} />{#chat#}
                </div>

                <div class="row"><label></label>
                    <label>{#admin#}</label>
                </div>
                <div class="row"><label></label>
                    <input type="checkbox" class="checkbox" value="0" name="permissions_admin[add]"/>{#administration#}
                </div>

            </div>

            <div class="clear_both_b"></div>

            <div class="row-butn-bottom">
                <label>&nbsp;</label>
                <button type="submit" onfocus="this.blur();">{#addbutton#}</button>

                <button type="reset"
                        onclick="blindtoggle('form_addmyroles');toggleClass('addrolelink','add-active','add');return false;">{#cancel#}</button>
            </div>

        </fieldset>
    </form>

</div> {*block_in_wrapper end*}
