{include file="header.tpl" jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-admin.tpl" projecttab="active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="projects" id="adminProjects">

            <!-- project text -->
            <div class="infowin_left display-none"
                 id="projectSystemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png"
                 data-text-deleted="{#projectwasdeleted#}"
                 data-text-edited="{#projectwasedited#}"
                 data-text-added="{#projectwasadded#}"
                 data-text-closed="{#projectwasclosed#}"
                    >
            </div>
            <h1>{#administration#}<span>/ {#projectadministration#}</span></h1>

            <div class="headline">
                <a href="javascript:void(0);" id="acc-projects_toggle" class="win_none" onclick="toggleBlock('acc-projects');"></a>

                {if $userpermissions.projects.add}
                    <div class="wintools">
                        <loader block="adminProjects" loader="loader-project3.gif"></loader>
                        <a class="add" href="javascript:blindtoggle('form_addmyproject');" id="add_myprojects"
                           onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_myprojects','butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');">
                            <span>{#addproject#}</span>
                        </a>
                    </div>
                {/if}

                <h2>
                    <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt=""/>
                    {#openprojects#}
                    <pagination view="adminProjectsView" :pages="pages" :current-page="currentPage"></pagination>
                </h2>

            </div>
            <div class="block" id="acc_projects"> {*Add Project*}
                <div id="form_addmyproject" class="addmenue display-none">
                    {include file="forms/addproject.tpl"}
                </div>
                <div class="nosmooth" id="sm_myprojects">
                    <table id="adminprojects" cellpadding="0" cellspacing="0" border="0">

                        <thead>
                        <tr>
                            <th class="a"></th>
                            <th class="b">{#project#}</th>
                            <th class="c">{#done#}</th>
                            <th class="d text-align-right">{#daysleft#}&nbsp;&nbsp;</th>
                            <th class="tools"></th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        </tfoot>

                        {literal}
                        <tbody v-for="project in items.open" class="alternateColors" id="proj_{{project.ID}}">

                        <tr v-bind:class="{ 'marker-late': project.islate, 'marker-today': project.istoday }">
                            <td>
                                {/literal}
                                {if $userpermissions.projects.del}
                                {literal}
                                    <a class="butn_check"
                                    href="javascript:closeElement('proj_{{project.ID}}','manageproject.php?action=close&amp;id={{project.ID}}',adminProjectsView);"
                                    title="{/literal}{#close#}"></a>
                                {/if}
                            </td>
                            {literal}
                            <td>
                                <div class="toggle-in">
                                    <span id="acc_projects_toggle{{project.ID}}" class="acc-toggle"
                                          onclick="javascript:accord_projects.toggle(css('#acc_projects_content{{$index}}'));"></span>
                                    <a href="manageproject.php?action=showproject&amp;id={{*project.ID}}" title="{{*project.name}}">
                                        {{{*project.name | truncate '35'}}}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div class="statusbar_b">
                                    <div class="complete" id="completed" style="width:{{*project.done}}%;"></div>
                                </div>
                                <span>{{*project.done}}%</span>
                            </td>
                            <td class="text-align-right">{{*project.daysleft}}&nbsp;&nbsp;</td>
                            <td class="tools">

                                {/literal}
                                {if $userpermissions.projects.edit}
                                {literal}
                                    <a class="tool_edit" href="javascript:void(0);" onclick="change('manageproject.php?action=editform&amp;id={{*project.ID}}','form_addmyproject');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_addmyproject');" title="{/literal}{#edit#}"></a>
                                {/if}

                                {if $userpermissions.projects.del}
                                {literal}
                                    <a class="tool_del"
                                    href="javascript:confirmDelete('{/literal}{#confirmdel#}{literal}','proj_{{*project.ID}}','manageproject.php?action=del&amp;id={{*project.ID}}',adminProjectsView);" title="{/literal}{#delete#}"></a>
                                {/if}
                            </td>
                        </tr>
                        {literal}
                        <tr class="acc">
                            <td colspan="5">
                                <div class="accordion_content">
                                    <div class="acc-in">
                                        {{{*project.desc}}}
                                        <p class="tags-miles">
                                            {/literal}<strong>{#user#}:</strong>{literal}
                                        </p>

                                        <div class="inwrapper">
                                            <ul>
                                                <li v-for="member in project.members">
                                                    <div class="itemwrapper" id="iw_{{*project.ID}}_{{*member.ID}}">

                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tr>
                                                                <td class="leftmen" valign="top">
                                                                    <div class="inmenue">
                                                                        <a v-show="member.avatar != ''" class="more"
                                                                           href="javascript:fadeToggle('info_{{project.ID}}_{{*member.ID}}');"></a>
                                                                    </div>
                                                                </td>
                                                                <td class="thumb">
                                                                    <a href="manageuser.php?action=profile&amp;id={{project.members[member].ID}"
                                                                       title="{{project.members[member].name}">
                                                                        <img v-if="member.gender == 'f'"
                                                                             src="./templates/{$settings.template}/theme/{/literal}{$setting.theme}/{literal}images/symbols/user-icon-female.png"
                                                                             alt=""/>
                                                                        <img v-if="member.gender == 'm'"
                                                                             src="./templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/symbols/user-icon-male.png"
                                                                             alt=""/>
                                                                    </a>
                                                                </td>
                                                                <td class="rightmen" valign="top">
                                                                    <div class="inmenue">
                                                                        <a class="del"
                                                                           href="manageproject.php?action=deassign&amp;user={{*member.ID}}&amp;id={{*project.ID}}&amp;redir=admin.php?action=projects"
                                                                           title="{/literal}{#deassignuser#}{literal}"
                                                                           onclick="fadeToggle('iw_{{*project.ID}}_{{*member.ID}}');"></a>
                                                                        <a class="edit"
                                                                           href="admin.php?action=editform&amp;id={{project.members[member].ID}}"
                                                                           title="{/literal}{#edituser#}{literal}"></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <span class="name">
                                                                        <a href="manageuser.php?action=profile&amp;id={{*member.ID}}"
                                                                           title="{{*member.name}}">
                                                                            {{*member.name}}
                                                                        </a>
                                                                    </span>
                                                                </td>
                                                            <tr/>

                                                        </table>

                                                        <div v-show="member.avatar != ''" class="moreinfo display-none"
                                                             id="info_{{*project.ID}}_{{*member.ID}">
                                                            <img src="thumb.php?pic=files/{$cl_config}/avatar/{{project.members[member].avatar}}&amp;width=82"
                                                                 alt="" onclick="fadeToggle('info_{{project.ID}}_{{project.members[member].ID}}');"/>
																		<span class="name">
																			<a href="manageuser.php?action=profile&amp;id={{*member.ID}}">
                                                                                {{*member.name}}
                                                                            </a>
																		</span>
                                                        </div>
                                                    </div>
                                                    <!--itemwrapper end-->
                                                </li>
                                            </ul>
                                        </div>
                                        <!--inwrapper End-->
                                        {/literal}
                                        <p class="tags-miles"> <!--assign users-->
                                            <strong>{#adduser#}:</strong>
                                        </p>
                                        {literal}
                                        <div class="inwrapper">

                                            <form class="main" method="post"
                                                  action="manageproject.php?action=assign&amp;id={{*project.ID}}&redir=admin.php?action=projects&mode=useradded"
                                                  onsubmit="return validateCompleteForm(this);">
                                                <fieldset>

                                                    {/literal}
                                                    <div class="row">
                                                        <label for="addtheuser">{#user#}</label>
                                                        <select name="user" id="addtheuser">
                                                            <option value="">{#chooseone#}</option>
                                                            {section name=usr loop=$users}
                                                                <option value="{$users[usr].ID}">{$users[usr].name}</option>
                                                            {/section}
                                                        </select>
                                                    </div>

                                                    <div class="row-butn-bottom">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" onfocus="this.blur();">{#addbutton#}</button>
                                                    </div>

                                                </fieldset>
                                            </form>
                                        </div>
                                        <!--assign users end-->
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!--Projects End-->
                    {literal}
                    <!--Doneprojects-->
                    <div id="doneblock" class="doneblock display-none">

                        <table class="second-thead" cellpadding="0" cellspacing="0" border="0"
                               onclick="blindtoggle('doneblock');toggleClass('donebutn','butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">
                            <tr>
                                <td class="a"></td>
                                <td class="b"><span id="toggle-done">{/literal}{#closedprojects#}{literal}</span></td>
                                <td class="c"></td>
                                <td class="days"></td>
                                <td class="tools"></td>
                            </tr>
                        </table>

                        <div class="toggleblock">

                            <table cellpadding="0" cellspacing="0" border="0" id="acc-oldprojects">

                                <tbody v-for="closedProject in items.closed" class="alternateColors" id="proj_{{*closedProject.ID}}">
                                <tr>
                                    <td class="a">
                                        {/literal}
                                        {if $userpermissions.projects.add}
                                        {literal}
                                            <a class="butn_checked" href="manageproject.php?action=open&amp;id={{*closedProject.ID}}"
                                            title="{/literal}{#open#}"></a>
                                        {/if}
                                    </td>
                                    {literal}
                                    <td class="b">
                                        <div class="toggle-in">
                                            <a href="manageproject.php?action=showproject&amp;id={{*closedProject.ID}}"
                                               title="{{{*closedProject.name}}}">
                                                {{{*closedProject.name}}}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="c">

                                    </td>
                                    <td class="days text-align-right">{{*closedProject.daysleft}}&nbsp;&nbsp;</td>
                                    <td class="tools">
                                        {/literal}
                                        {if $userpermissions.projects.del}
                                        {literal}
                                            <a class="tool_del"
                                            href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{{*closedProject.ID}\',\'manageproject.php?action=del&amp;id={{*closedProject.ID}\')');"
                                            title="{/literal}{#delete#}"></a>
                                        {/if}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!--toggleblock End-->
                    </div>
                    <!--doneblock end-->
                </div>
                <!--smooth end-->
                {literal}
                <div class="tablemenue">
                    <div class="tablemenue-in">
                        {/literal}
                        {if $userpermissions.projects.add}
                        {literal}
                            <a class="butn_link" href="javascript:blindtoggle('form_addmyproject');" id="add_butn_myprojects"
                            onclick="toggleClass('add_myprojects','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');">
                        {/literal}{#addproject#}
                            </a>
                        {/if}
                        {literal}
                        <a class="butn_link" href="javascript:blindtoggle('doneblock');" id="donebutn"
                           onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">
                            {/literal}{#closedprojects#}{literal}
                        </a>
                    </div>
                </div>
            </div> {/literal}
            <!-- block END Doneprojects End-->

            <div class="content-spacer"></div>
            <!--projectTemplates-->
        </div>
        <!--Projects END-->


    </div>
    <!--content-left-in END-->
</div> <!--content-left END-->

<script type="text/javascript" src="include/js/accordion.min.js"></script>
<script type="text/javascript" src="include/js/views/adminProjectsView.min.js"></script>

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}