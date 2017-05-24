{include file="header.tpl" jsload="ajax"}
{include file="tabsmenue-project.tpl" userstab="active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="user" id="projectMembers" v-cloak>

            <!-- System messages -->
            <div class="infowin_left display-none"
                 id="userSystemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png"
                 data-text-added="{#userwasadded#}"
                 data-text-edited="{#userwasedited#}"
                 data-text-deleted="{#userwasdeleted#}"
                 data-text-assigned="{#userwasassigned#}"
                 data-text-deassigned="{#userwasdeassigned#}"
                    >
            </div>

            <h1>{$projectname|truncate:45:"...":true}<span>/ {#members#}</span></h1>

            <div class="headline">
                <a href="javascript:void(0);" id="block_members_toggle" class="win_block" onclick="toggleBlock('block_members');"></a>

                <div class="wintools">
                    <loader block="projectMembers" loader="loader-users.gif"></loader>
                    {if $userpermissions.projects.edit}
                        <a class="add" href="javascript:blindtoggle('form_member');" id="addmember"
                           onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_member','butn_link_active','butn_link');toggleClass('sm_member','smooth','nosmooth');"><span>{#adduser#}</span></a>
                    {/if}
                </div>

                <h2>
                    <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png" alt=""/>{#members#}
                </h2>
            </div>
            <div id="block_members" class="blockwrapper">
                {*Add User*}
                {if $userpermissions.projects.edit}
                    <div id="form_member" class="addmenue display-none">
                        {include file="forms/assignUserToProject.tpl" }
                    </div>
                {/if}

                <div class="nosmooth" id="sm_member">
                    <div class="contenttitle">
                        <div class="contenttitle_menue">
                            {*place for tool under ne title-icon*}
                        </div>
                        <div class="contenttitle_in">
                            {*place for header-informations*}
                        </div>
                    </div>

                    <div class="content_in_wrapper">
                        <div class="content_in_wrapper_in">
                            <div class="inwrapper">
                                <ul>
                                    {literal}
                                    <li v-for="member in items">
                                        <div class="itemwrapper"
                                             v-bind:id="'iw_'+member.ID">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td class="leftmen" valign="top">
                                                        <div class="inmenue">
                                                            <a v-if="member.avatar != ''"
                                                               class="more"
                                                               href="javascript:fadeToggle('info_{{*member.ID}}');"></a>
                                                        </div>
                                                    </td>
                                                    <td class="thumb">
                                                        <a v-bind:href="'manageuser.php?action=profile&amp;id='+member.ID"
                                                           :title="member.name">
                                                            <img src="./templates/standard/theme/standard/images/symbols/user-icon-male.png"
                                                                 alt=""/>
                                                        </a>
                                                    </td>
                                                    <td class="rightmen" valign="top">
                                                        <div class="inmenue">
                                                            {/literal}
                                                            {if $userpermissions.projects.edit}
                                                            {literal}
                                                                <a class="del"
                                                                v-bind:href="'manageproject.php?action=deassignform&amp;id={/literal}{$project.ID}{literal}&amp;user='+member.ID"
                                                                title="{#deassign#}"></a>
                                                                <a class="edit"
                                                                   v-bind:href="'admin.php?action=editform&id='+member.ID"
                                                                   title="{#edituser#}"></a>
                                                            {/literal}{/if}
                                                            {literal}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
														<span class="name">
															<a v-bind:href="'manageuser.php?action=profile&amp;id='+member.ID"
                                                               :title="member.name">
                                                                {{member.name | truncate '13' }}
                                                            </a>
														</span>
                                                    </td>
                                                <tr/>
                                            </table>

                                            <div class="moreinfo-wrapper" v-if="member.avatar != ''">
                                                <div class="moreinfo display-none" id="info_{$members[member].ID}">
                                                    <img :src="'thumb.php?pic=files/standard/avatar/' + member.avatar + '&amp;width=82'"
                                                         alt=""
                                                         onclick="fadeToggle('info_{{*member.ID}}');"/>
                                                            <span class="name">
                                                                <a v-bind:href="'manageuser.php?action=profile&amp;id='+member.ID">
                                                                    {{member.name | truncate '15'}}</a></span>
                                                </div>
                                            </div>

                                        </div>
                                        {/literal}
                                        {*itemwrapper End*}
                                    </li>
                                </ul>
                            </div> {*inwrapper End*}
                        </div> {*content_in_wrapper_in End*}
                    </div> {*content_in_wrapper End*}

                    <div class="staterow">
                        <div class="staterowin">
                            {*place for whatever*}
                        </div>

                        <div class="staterowin_right"><span>{$langfile.page} </span></div>
                    </div>

                </div> {*nosmooth End*}
                <div class="tablemenue">
                    <div class="tablemenue-in">
                        {if $userpermissions.projects.edit}
                            <a class="butn_link" href="javascript:blindtoggle('form_member');" id="add_butn_member"
                               onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('addmember','add-active','add');toggleClass('sm_member','smooth','nosmooth');">{#adduser#}</a>
                        {/if}
                    </div>
                </div>
            </div> {*block_files End*}

            <div class="content-spacer"></div>
        </div> {*User END*}
    </div> {*content-left-in END*}
</div> {*content-left END*}
<script type="text/javascript" src="include/js/views/projectMembersView.min.js"></script>
<script type="text/javascript">
    projectMembers.url = projectMembers.url + "&id=" + {$project.ID};
    projectMembersView = createView(projectMembers);

    projectMembersView.afterLoad(function () {

    });
</script>
{include file="sidebar-a.tpl"}
{include file="footer.tpl"}