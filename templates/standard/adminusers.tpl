{include file="header.tpl" jsload="ajax"}
{include file="tabsmenue-admin.tpl" usertab="active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="user" id="adminUsers">

            <!-- user text -->
            <div class="infowin_left display-none"
                 id="userSystemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png"
                 data-text-added="{#userwasadded#}"
                 data-text-edited="{#userwasedited#}"
                 data-text-deleted="{#userwasdeleted#}"
                 data-text-assigned="{#userwasassigned#}"
                 data-text-deassigned="{#permissionswereedited#}"
                 >
            </div>

            <!-- role text -->
            <div class="infowin_left display-none"
                 id="roleSystemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png"
                 data-text-edited="{#roleedited#}"
                 data-text-added="{#roleadded#}"
                 data-text-deleted="{#roleadded#}"
                 >
            </div>

            <h1>{#administration#}<span>/ {#useradministration#}</span></h1>

            <div class="headline">
                <a href="javascript:void(0);" id="block_files_toggle" class="win_block"
                   onclick="toggleBlock('block_files');"></a>

                <div class="wintools">
                    <loader block="adminUsers" loader="loader-users.gif"></loader>

                    {if $userpermissions.admin.add}
                        <a class="add" href="javascript:blindtoggle('form_member');" id="addmember"
                           onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_member','butn_link_active','butn_link');toggleClass('sm_member','smooth','nosmooth');">
						<span>
                            {#adduser#}
						</span>
                        </a>
                    {/if}
                </div>

                <h2>
                    <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png"
                         alt=""/>
                    {#useradministration#}
                    <pagination view="adminUsersView" :pages="pages" :current-page="currentPage"></pagination>
                </h2>
            </div>
            <div id="block_files" class="blockwrapper">
                <!--Add User-->
                {if $userpermissions.admin.add}
                    <div id="form_member" class="addmenue display-none">
                        {include file="forms/adduserform.tpl"}
                    </div>
                {/if}
                <div class="nosmooth" id="sm_member">
                    <div class="content_in_wrapper">
                        <div class="content_in_wrapper_in">
                            <div class="inwrapper">
                                {literal}
                                <ul>
                                    <li v-for="user in items">
                                        <div class="itemwrapper" id="iw_{{*user.ID}}">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td class="leftmen" valign="top">
                                                        <div class="inmenue">
                                                            <a v-if="*user.avatar != ''" class="more"
                                                               href="javascript:fadeToggle('info_{{*user.ID}}');"></a>
                                                        </div>
                                                    </td>
                                                    <td class="thumb">
                                                        <a href="manageuser.php?action=profile&amp;id={{*user.ID}}"
                                                           title="{{*user.name}}">

                                                            <img v-if="user.gender == 'f'" {/literal}
                                                                 src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/user-icon-female.png" {literal}
                                                                 alt=""/>
                                                            <img v-else {/literal}
                                                                 src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/user-icon-male.png" {literal}
                                                                 alt=""/>
                                                        </a>
                                                    </td>
                                                    <td class="rightmen" valign="top">
                                                        <div class="inmenue">
                                                            <a class="edit"
                                                               href="admin.php?action=editform&amp;id={{*user.ID}}"
                                                               title="{#edit#}"></a>
                                                            <a v-if="user.ID != {/literal}{$userid}{literal}"
                                                               class="del"
                                                               href="javascript:confirmit('{/literal}{#confirmdel#}{literal}','admin.php?action=deleteuserform&amp;id={{*user.ID}}');"
                                                               title="{/literal}{#delete#}{literal}"></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
															<span class="name">
																<a href="manageuser.php?action=profile&amp;id={{*user.ID}}"
                                                                   title="{{*user.name}}">
                                                                    {{*user.name}}
                                                                </a>
															</span>
                                                    </td>
                                                </tr>
                                            </table>

                                            <template v-if="{{user.avatar != ''}}">
                                                <div class="moreinfo-wrapper">
                                                    <div class="moreinfo display-none" id="info_{{*user.ID}}">
                                                        <img src="thumb.php?pic=files/{/literal}{$cl_config}{literal}/avatar/{{*user.avatar}}&amp;width=82"
                                                             alt="" onclick="fadeToggle('info_{{*user.ID}');"/>
                                                            <span class="name">
                                                                <a href="manageuser.php?action=profile&amp;id={{*user.ID}}">
                                                                    {{*user.name}}
                                                                </a>
                                                            </span>
                                                    </div>
                                                </div>
                                            </template>

                                        </div>
                                        <!--itemwrapper End-->
                                    </li>
                                    {/literal} <!--loop folder End-->
                                </ul>
                            </div>
                            <!--inwrapper End-->
                        </div>
                        <!--content_in_wrapper_in End-->
                    </div>
                    <!--content_in_wrapper End-->

                    <div class="staterow">
                        <div class="staterowin">
                            <!--place for whatever-->
                        </div>
                        <div class="staterowin_right">
							<span>
								{$langfile.page|default}
							</span>
                        </div>
                    </div>

                </div>
                <!--nosmooth End-->
                <div class="tablemenue">
                    <div class="tablemenue-in">

                        {if $userpermissions.admin.add}
                            <a class="butn_link" href="javascript:blindtoggle('form_member');" id="add_butn_member"
                               onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('addmember','add-active','add');toggleClass('sm_member','smooth','nosmooth');">
                                {#adduser#}
                            </a>
                        {/if}

                    </div>
                </div><!-- admin users end -->
            </div><!--content left end-->
        </div><!-- content left in end-->
        <!--block END-->
        <div class="content-spacer"></div>
        <!-- Roles -->
        {include file = "rolesadmin.tpl"}
        <!-- Roles End -->
        <div class="content-spacer"></div>
    </div>
    <!--userAdmin END-->
</div> <!--content-left-in END-->
</div> <!--Content_left end-->


{literal}
    <script type="text/javascript" src="include/js/accordion.min.js"></script>
    <script type="text/javascript" src="include/js/views/adminUsersView.min.js"></script>

{/literal}
{include file="sidebar-a.tpl"}
{include file="footer.tpl"}