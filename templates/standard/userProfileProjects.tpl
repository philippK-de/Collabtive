{if $userpermissions.admin.add}{*Projects*}
    <div class="projects" id="userProjects">
        <div class="headline">
            <a href="javascript:void(0);" id="projecthead_toggle" class="win_block" onclick="toggleBlock('projecthead');"></a>
            <div class="wintools">
                <div class="progress" id="progressuserProjects" style="display:none;">
                    <img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/loader-project3.gif"/>
                </div>
            </div>
            <h2>
                <a href="myprojects.php" title="{#myprojects#}">
                    <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt=""/>{#projects#}</a>
                <pagination view="userProfileProjectsView" :pages="pages" :current-page="currentPage"></pagination>
            </h2>
        </div>

        <div class="block" id="projecthead" style="{$projectstyle|default}">

            <table cellpadding="0" cellspacing="0" border="0">
                <thead>
                <tr>
                    <th class="a"></th>
                    <th class="b">{#project#}</th>
                    <th class="c"></th>
                    <th class="d" style="text-align:right">{#daysleft#}&nbsp;&nbsp;</th>
                    <th class="tools"></th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <td colspan="5"></td>
                </tr>
                </tfoot>

                {literal}
                <tbody v-for="project in items" class="alternateColors" id="proj_{{*project.ID}">
                <tr v-bind:class="{ 'marker-late': project.islate, 'marker-today': project.istoday }">
                    <td>
                        {/literal}
                        {if $userpermissions.admin.add}
                        {literal}
                            <a class="butn_check" href="javascript:closeElement('proj_{{*project.ID}}','manageproject.php?action=close&amp;id={{*project.ID}}');" title="{/literal}{#close#}"></a>
                        {/if}
                    </td>
                    {literal}
                    <td>
                        <div class="toggle-in">
                            <span class="acc-toggle"
                                  onclick="javascript:accord_projects.activate(document.querySelectorAll('#projecthead .accordion_toggle')[{{$index}}]);toggleAccordeon('projecthead',this);"></span>
                            <a href="manageproject.php?action=showproject&amp;id={{*project.ID}}" title="{{*project.name}}">
                                {{*project.name}}
                            </a>
                        </div>
                    </td>
                    <td></td>
                    <td style="text-align:right">{{*project.daysleft}}&nbsp;&nbsp;</td>
                    <td class="tools">

                    </td>
                </tr>

                <tr class="acc">
                    <td colspan="5">
                        <div class="accordion_toggle"></div>
                        <div class="accordion_content">
                            <div class="acc-in">
                                {{*project.desc}}
                                <p class="tags-miles">
                                    {/literal}<strong>{#user#}:</strong>
                                </p>
                                {literal}
                                <div class="inwrapper">
                                    <ul>
                                        <li v-for="member in project.members">
                                            <div class="itemwrapper" id="iw_{{*project.ID}}_{{*member.ID}}">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="leftmen" valign="top">
                                                            <div class="inmenue">
                                                                <a v-if="member.avatar != ''" class="more"
                                                                   href="javascript:fadeToggle('info_{{*project.ID}_{{*project.members[member].ID}');"></a>
                                                            </div>
                                                        </td>
                                                        <td class="thumb">
                                                            <a href="manageuser.php?action=profile&amp;id={{*project.members[member].ID}"
                                                               title="{{*member.name}}">
                                                                <img src="./templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/symbols/user-icon-male.png" alt=""/>
                                                            </a>
                                                        </td>
                                                        <td class="rightmen" valign="top">
                                                            <div class="inmenue">
                                                                <a class="del"
                                                                   href="manageproject.php?action=deassign&amp;user={{*member.ID}}&amp;id={{*project.ID}}&amp;redir=admin.php?action=projects" title="{#deassignuser#}" onclick="fadeToggle('iw_{{*project.ID}}_{{*member.ID}}');"></a>
                                                                <a class="edit" href="admin.php?action=editform&amp;id={{*member.ID}}" title="{#edituser#}"></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <span class="name">
                                                                <a href="manageuser.php?action=profile&amp;id={{*project.members[member].ID}"
                                                                   title="{{*project.members[member].name}">
                                                                    {{*member.name}}
                                                                </a>
                                                            </span>
                                                        </td>
                                                    <tr/>
                                                </table>

                                                {if $opros[opro].members[member].avatar != ""}
                                                <div class="moreinfo-wrapper">
                                                    <div class="moreinfo" id="info_{{*project.ID}_{{*project.members[member].ID}"
                                                         style="display:none">
                                                        <img src="thumb.php?pic=files/{$cl_config}/avatar/{{*project.members[member].avatar}&amp;width=82"
                                                             alt="" onclick="fadeToggle('info_{{*project.ID}_{{*project.members[member].ID}');"/>
                                                        <span class="name"><a
                                                                    href="manageuser.php?action=profile&amp;id={{*project.members[member].ID}">{{*project.members[member].name|truncate:15:"...":true}</a></span>
                                                    </div>
                                                </div>
                                                {/if}
                                            </div>
                                            <!--itemwrapper end-->
                                        </li>
                                    </ul>
                                </div>
                                <!--inwrapper End-->
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
                {/literal}
            </table>

            <div class="tablemenue"></div>
        </div> <!--block END-->
    </div>
    {*projects END*}
    <div class="content-spacer"></div>
    {*Projects End*}
{literal}
    <script type="text/javascript" src="include/js/views/userProfileView.min.js"></script>
    <script type="text/javascript">
        userProfileProjects.url = userProfileProjects.url + "&id=" + {/literal}{$user.ID}{literal};
        var userProfileProjectsView = createView(userProfileProjects);

        var accord_projects = new accordion('projecthead');
        new Control.Modal('ausloeser', {
            opacity: 0.8,
            position: 'absolute',
            width: 480,
            height: 480,
            fade: true,
            containerClassName: 'pics',
            overlayClassName: 'useroverlay'
        });
    </script>
{/literal}
{/if} {*if admin end*}