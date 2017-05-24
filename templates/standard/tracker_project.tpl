{include file="header.tpl" jsload = "ajax" }
{include file="tabsmenue-project.tpl" timetab = "active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="timetrack" id="projectTimetracker">

            <div class="infowin_left display-none"
                 id="timetrackerSystemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/timetracker.png"
                 data-text-added="{#timetracker#} {#was#} {#added#}"
                 data-text-edited="{#timetracker#} {#was#} {#edited#}"
                 data-text-deleted="{#timetracker#} {#was#} {#deleted#}">
            </div>

            <h1>{$projectname|truncate:45:"...":true}<span>/ {#timetracker#}</span></h1>
            <div class="timetrack">
                <div class="headline">
                    <a href="javascript:void(0);" id="acc-tracker_toggle" class="win_block" onclick="toggleBlock('acc-tracker');"></a>

                    <div class="wintools">
                        <loader block="projectTimetracker" loader="loader-timetracker.gif"></loader>

                        <div class="export-main">
                            <a class="export"><span>{#export#}</span></a>

                            <div class="export-in" style="width:46px;left: -46px;"> {*at one item*}
                                <a class="pdf"
                                   href="javascript:getTimetrackerReport({$project.ID}, 'pdf');"><span>{#pdfexport#}</span></a>
                                <a class="excel"
                                   href="javascript:getTimetrackerReport({$project.ID}, 'xls');"><span>{#excelexport#}</span></a>
                            </div>
                        </div>

                        <div class="toolwrapper">
                            <a class="filter" href="javascript:blindtoggle('filter');" id="filter_report"
                               onclick="toggleClass(this,'filter-active','filter');toggleClass('filter_butn','butn_link_active','butn_link');"><span>{#filterreport#}</span></a>
                        </div>
                    </div>

                    <h2>
                        <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/timetracker.png" alt=""/>{#report#}
                        <pagination view="projectTimetrackerView" :pages="pages" :current-page="currentPage"></pagination>
                    </h2>
                </div>

                <div class="block" id="accordeonProjectTimetracker" v-cloak>

                    <div id="filter" class="addmenue display-none"> {*Filter Report*}
                        {include file="filtertracker.tpl" }
                    </div> {*Filter End*}

                    <div class="nosmooth" id="sm_report">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <thead>
                            <tr>
                                <th class="a"></th>
                                <th class="b">{#user#}</th>
                                <th class="cf">{#day#}</th>
                                <th class="cf">{#started#}</th>
                                <th class="cf">{#ended#}</th>
                                <th class="e text-align-right">{#hours#}&nbsp;&nbsp;</th>
                                <th class="tools"></th>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <td colspan="6"></td>
                            </tr>
                            </tfoot>
                            {literal}
                            <tbody v-for="track in items" class="alternateColors" id="track_{{track.ID}}">
                            <tr>
                                <td></td>
                                <td>
                                    <div class="toggle-in">
                                        <span class="acc-toggle"
                                              onclick="javascript:accord_tracker.toggle(css('#accordeonProjectTimetracker_content{{$index}}'));"></span>
                                        <a href="manageuser.php?action=profile&amp;id={{*track.user}}" title="{{*track.pname}}">
                                            {{*track.uname | truncate '30' }}
                                        </a>
                                    </div>
                                </td>
                                <td>{{*track.daystring}}</td>
                                <td>{{*track.startstring}}</td>
                                <td>{{*track.endstring}}</td>
                                <td class="text-align-right">{{*track.hours}}&nbsp;&nbsp;</td>
                                <td class="tools">
                                    {/literal}
                                    {if $userpermissions.timetracker.edit}
                                    {literal}
                                        <a class="tool_edit" href="managetimetracker.php?action=editform&amp;tid={{*track.ID}}&amp;id={{*track.project}}"
                                        title="{/literal}{#edit#}"></a>
                                    {/if}
                                    {if $userpermissions.timetracker.del}
                                        <a class="tool_del"
                                           href="javascript:confirmDelete('{#confirmdel#}','{literal}track_{{*track.ID}}','managetimetracker.php?action=del&amp;tid={{*track.ID}}&amp;id={{*track.project}}{/literal}',projectTimetrackerView);"
                                           title="{#delete#}"></a>
                                    {/if}
                                </td>
                            </tr>
                            {literal}
                            <tr class="acc">
                                <td colspan="7">
                                    <div class="accordion_content">
                                        <div class="acc-in">
                                            <strong v-if="track.comment">{/literal}{#comment#}:{literal}</strong><br/>{{{*track.comment}}}

                                            <p v-if="track.hasTask" class="tags-miles">
                                                <strong>{/literal}{#task#}{literal}:</strong><br/>
                                                <a href="managetask.php?action=showtask&amp;tid={{*track.task.ID}}&amp;id={{*track.project}}">{{{*track.tname}}}</a>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            {/literal}
                            <tbody class="tableend">
                            <tr>
                                <td></td>
                                <td colspan="4"><strong>{#totalhours#}:</strong></td>
                                <td class="text-align-right"><strong>{$totaltime|default}</strong>&nbsp;&nbsp;</td>
                                <td class="tools"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div> {*smooth End*}

                    <div class="tablemenue">
                        <div class="tablemenue-in">
                            <a class="butn_link" href="javascript:blindtoggle('filter');" id="filter_butn"
                               onclick="toggleClass('filter_report','filter-active','filter');toggleClass(this,'butn_link_active','butn_link');">{#filterreport#}</a>
                        </div>
                    </div>
                </div> {*block END*}

            </div> {*timetrack END*}

            <div class="content-spacer"></div>

            {literal}
            <script type="text/javascript" src="include/js/accordion.min.js"></script>
            <script type="text/javascript" src="include/js/views/timetrackerProject.min.js"></script>
            <script type="text/javascript">
                projectTimetracker.url = projectTimetracker.url + "&id=" + {/literal}{$project.ID}{literal};

                pagination.itemsPerPage = 25;
                projectTimetrackerView = createView(projectTimetracker);
                //get the form to be submitted
                var filterTimetrackerForm = document.getElementById("filterTimetrackerForm");
                filterTimetrackerForm.addEventListener("submit",filterTimetrackerView.bind(projectTimetrackerView));

                var accord_tracker;
                projectTimetrackerView.afterUpdate(function(){
                    accord_tracker = new accordion2("accordeonProjectTimetracker");
                });
            </script>
            {/literal}

        </div> {*Timetracking END*}
    </div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}