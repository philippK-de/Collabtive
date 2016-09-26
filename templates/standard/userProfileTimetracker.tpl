<!-- container for the userTimetrackerAccordeon accordeon -->
<div class="timetrack" id="userTimetracker">
    <div class="headline">
        <!-- toggle for the blockaccordeon-->
        <a href="javascript:void(0);" id="userTimetracker_toggle" class="win_none" onclick = ""></a>
        <div class="wintools">
            <div class="export-main">
                <a class="export"><span>{#export#}</span></a>
                <div class="export-in"  style="width:46px;left: -46px;"> {*at one item*}
                    <a class="pdf" href="managetimetracker.php?action=userpdf&amp;id={$project.ID}{if $start != "" and $end != ""}&amp;start={$start}&amp;end={$end}{/if}{if $usr > 0}&amp;usr={$usr}{/if}{if $task > 0}&amp;task={$task}{/if}{if $fproject > 0}&amp;project={$fproject}{/if}"><span>{#pdfexport#}</span></a>
                    <a class="excel" href="managetimetracker.php?action=userxls&amp;id={$project.ID}{if $start != "" and $end != ""}&amp;start={$start}&amp;end={$end}{/if}{if $usr > 0}&amp;usr={$usr}{/if}{if $task > 0}&amp;task={$task}{/if}{if $fproject > 0}&amp;project={$fproject}{/if}"><span>{#excelexport#}</span></a>
                </div>
            </div>

            <div class="toolwrapper">
                <a class="filter" href="javascript:blindtoggle('form_filter');" id="filter_report" onclick="toggleClass(this,'filter-active','filter');toggleClass('filter_butn','butn_link_active','butn_link');toggleClass('sm_report','smooth','nosmooth');"><span>{#filterreport#}</span></a>
            </div>
        </div>

        <h2>
            <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/timetracker.png" alt="" />{#report#}
        </h2>
    </div>

    <!-- contentSlide for the blockAccordeon -->
    <div class="block blockaccordion_content overflow-hidden display-none" > {*Filter Report*}
        <div id = "form_filter" class="addmenue display-none">
            {include file="filterreport.tpl" }
        </div>

        <div class="nosmooth" id="sm_report">
            <table cellpadding="0" cellspacing="0" border="0" id="userTimetrackerAccordeon">
                <thead>
                <tr>
                    <th class="a"></th>
                    <th class="b">{#project#}</th>
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

                    <tbody v-for="tracker in items" class="alternateColors" id="track_{{*tracker.ID}}">
                    <tr>
                        <td></td>
                        <td>
                            <div class="toggle-in">
                                <span class="acc-toggle" onclick="javascript:accord_tracker.toggle(css('#userTimetrackerAccordeon_content{{$index}}'))"></span>
                                <a href = "managetimetracker.php?action=showproject&amp;id={{*tracker.project}}" title="{{*tracker.pname}}">
                                    {{{*tracker.pname | truncate '30'}}}
                                </a>
                            </div>
                        </td>
                        <td>{{*tracker.daystring | truncate '12'}}</td>
                        <td>{{*tracker.startstring | truncate '12'}}</td>
                        <td>{{*tracker.endstring | truncate '12'}}</td>
                        <td class="text-align-right">{{*tracker.hours | truncate '12' }}&nbsp;&nbsp;</td>
                        <td class="tools">
                            {/literal}
                            {if $userpermissions.timetracker.edit}
                            {literal}
                                <a class="tool_edit"
                                href="managetimetracker.php?action=editform&amp;tid={$tracker[track].ID}&amp;id={$tracker[track].project}"
                                title="{/literal}{#edit#}"></a>
                            {/if}
                            {if $userpermissions.timetracker.del}
                            {literal}
                                <a class="tool_del"
                                   href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'track_{$tracker[track].ID}\',\'managetimetracker.php?action=del&amp;tid={$tracker[track].ID}&amp;id={$project.ID}\')');"
                                   title={/literal}"{#delete#}"></a>
                            {/if}
                        </td>
                    </tr>
                    {literal}
                    <tr class="acc">
                        <td colspan="7">
                            <div class="accordion_content">
                                <div class="acc-in">
                                    <template v-if="tracker.comment != ''">
                                        <strong>{/literal}{#comment#}{literal}:</strong><br />{{*tracker.comment}}
                                    </template>
                                    <template v-if="tracker.task > 0">
                                        <p class="tags-miles">
                                            <strong>{/literal}{#task#}:{literal}</strong><br />
                                            <a href = "managetask.php?action=showtask&amp;tid={$tracker[track].task}&amp;id={{*tracker.project}}">{{*tracker.tname}}</a>
                                        </p>
                                    </template>
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
                    <td class="text-align-right"><strong>{$totaltime}</strong>&nbsp;&nbsp;</td>
                    <td class="tools"></td>
                </tr>
                </tbody>
            </table>

        </div> {*smooth End*}

        <div class="tablemenue">
            <div class="tablemenue-in">
                <a class="butn_link" href="javascript:blindtoggle('form_filter');" id="filter_butn" onclick="toggleClass('filter_report','filter-active','filter');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_report','smooth','nosmooth');">{#filterreport#}</a>
            </div>
        </div>
    </div> {*block END*}
</div> {*timetrack END*}
<div class="padding-bottom-two-px"></div>