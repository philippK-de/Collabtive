<div id="desktopCalendar" class="miles" style="padding-bottom:2px;">
    <div class="headline">
        <a href="javascript:void(0);" id="mileshead_toggle" class="win_none" onclick=""></a>

        <div class="wintools">
            <loader block="desktopCalendar" loader="loader-calendar.gif"></loader>
        </div>

        <h2>
            <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png" alt=""/>{#calendar#}

        </h2>
    </div>

    {if $context == "project"}
    <div class="block accordion_content" id="mileshead" style="overflow:hidden;">
    {else}
    <div class="block blockaccordion_content" id="mileshead" style="overflow:hidden;">
    {/if}
        <div class="bigcal">
            <table cellpadding="0" cellspacing="1" border="0" class="thecal">
                {literal}
                <!--Calender head area -->
                <thead class="calhead">
                <tr>
                    <th>
                        <a class="scroll_left" href="javascript:updateCalendar(calendarView,'{{items.previousMonth}}','{{items.previousYear}}');"></a>
                    </th>
                    <th colspan="5" align="center">
                        <!--Localized month & year -->
                        {{items.monthName}} {{items.currentYear}}
                    </th>
                    <th>
                        <a class="scroll_right" href="javascript:updateCalendar(calendarView,'{{items.nextMonth}}','{{items.nextYear}}');"></a>
                    </th>
                </tr>

                {/literal}
                <!--Localized days -->
                <tr class="dayhead">
                    <th>{$langfile.monday}</th>
                    <th>{$langfile.tuesday}</th>
                    <th>{$langfile.wednesday}</th>
                    <th>{$langfile.thursday}</th>
                    <th>{$langfile.friday}</th>
                    <th>{$langfile.saturday}</th>
                    <th>{$langfile.sunday}</th>
                </tr>
                </thead>

                <tbody class="content">
                {literal}
                <tr v-for="week in items.weeks" valign="top">
                    <!--Iterate days of current week -->
                    <td v-for="day in week"
                        v-bind:class="{
                           'today': day.currmonth == 1 && items.currentMonth == items.selectedMonth && items.currentYear == items.selectedYear && items.currentDay == day.val,
                           'second': items.currentDay != day.val,
                           'othermonth': day.currmonth != 1
                           }"
                        id="{{*day.val}}">
                        {{day.val}}
                        <!--Only output tasks/milestones if the day belongs to the current month -->
                        <div v-if="day.currmonth == 1" class="calcontent">

                            <!--Milestones -->
                            <template v-if="day.milesnum > 0">
                                <a href="#miles{{*day.val}}" id="mileslink{{*day.val}}">
                                    <img src="templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/symbols/miles.png" alt=""/>
                                </a>

                                <div id="miles{{*day.val}}" style="display:none;">
                                    <div class="modaltitle">
                                        <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png" alt=""/>
                                        {/literal}{$langfile.milestones}{literal}
                                        {{*day.val}}.{{*items.currentMonth}}.{{items.currentYear}}
                                        <a class="winclose" href="javascript:Control.Modal.close();"></a>
                                    </div>

                                    <div class="inmodal">
                                        <div class="miles">
                                            <div class="block">
                                                <table class="acc_modal" id="acc_m" cellpadding="0" cellspacing="0" border="0">
                                                    <colgroup>
                                                        <col class="m_a"/>
                                                        <col class="m_b"/>
                                                        <col class="m_c"/>
                                                    </colgroup>

                                                    {/literal}
                                                    <thead>
                                                    <th></th>
                                                    <th>{$langfile.project}: {$langfile.milestone}</th>
                                                    <th class="tools">{$langfile.daysleft}</th>
                                                    </thead>
                                                    {literal}

                                                    <tbody v-for="milestone in day.milestones" class="alternateColors" id="mile_m_{{*milestone.ID}}">
                                                    {else}
                                                    <tbody class="color-b" id="mile_m_{{*milestone.ID}}">
                                                    {/if}
                                                    <tr>
                                                        {/literal}
                                                        <td class="icon">
                                                            <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png"
                                                                 alt=""/>
                                                        </td>
                                                        {literal}
                                                        <td>
                                                            <div class="toggle-in">
                                                                <span class="acc-toggle"></span>
                                                                <a href="managemilestone.php?action=showmilestone&amp;msid={{*milestone.ID}}&amp;id={{*milestone.project}}"
                                                                   title="{{*milestone.title}}">
                                                                    {{*milestone.pname}}:
                                                                    {{*milestone.name}}
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td class="tools">
                                                            {{*milestone.daysleft}}
                                                        </td>
                                                    </tr>
                                                    <tr class="acc">
                                                        <td colspan="3">
                                                            <div class="maccordion_content" >
                                                                <div class="content_in">
                                                                    {{*milestone.desc}}
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script type="text/javascript">
                                    new Control.Modal('mileslink{{day.val}}', {
                                        opacity: 0.8,
                                        position: 'absolute',
                                        width: 550,
                                        fade: true,
                                        containerClassName: 'milesmodal',
                                        overlayClassName: 'milesoverlay'
                                    });
                                </script>


                            </template>
                            <!--Milestones End -->
                            <!--Tasks -->
                            <a v-if="day.tasksnum > 0" href="#tasks{{*day.val}}" id="tasklink{{*day.val}}">
                                <img src="templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/symbols/task.png" alt=""/>
                            </a>

                            <div id="tasks{{*day.val}}" style="display:none;">
                                <div class="modaltitle">
                                    <img src="./templates/{/literal}{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt=""/>
                                    {$langfile.tasklist}
                                    {literal}
                                    {{*day.val}}.{{*items.currentMonth}}.{{items.currentYear}}
                                    <a class="winclose" href="javascript:Control.Modal.close();"></a>
                                </div>

                                <div class="inmodal">
                                    <div class="tasks">
                                        <div class="block">
                                            <table class="acc_modal" id="acc_mb_{{*day.val}}" cellpadding="0" cellspacing="0" border="0">
                                                <colgroup>
                                                    <col class="m_a"/>
                                                    <col class="m_b"/>
                                                    <col class="m_c"/>
                                                </colgroup>
                                                {/literal}
                                                <thead>
                                                <th></th>
                                                <th>{$langfile.project}: {$langfile.task}</th>
                                                <th class="tools">{$langfile.daysleft}</th>
                                                </thead>
                                                {literal}
                                                <tbody v-for="task in day.tasks" class="alternateColors" id="task_m_{{*task.ID}}">
                                                <tr>
                                                    <td class="icon">
                                                        <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png"
                                                             alt=""/>
                                                    </td>
                                                    <td>
                                                        <div class="toggle-in">

                                                            <a href="managetask.php?action=showtask&amp;tid={{*task.ID}}&amp;id={{*task.project}}"
                                                               title="{{*task.title}}">
                                                                {{*task.pname}}:
                                                                {{*task.title}}
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td class="tools">{{*task.daysleft}}</td>
                                                </tr>
                                                <tr class="acc">
                                                    <td colspan="3">
                                                        <div class="maccordion_content">
                                                            <div class="content_in">
                                                                {{*task.text}}
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script type="text/javascript">
                                new Control.Modal('tasklink{{day.val}}', {
                                    opacity: 0.8,
                                    position: 'absolute',
                                    width: 550,
                                    fade: true,
                                    containerClassName: 'tasksmodal',
                                    overlayClassName: 'tasksoverlay'
                                });
                            </script>
                        </div>
                    </td>
                </tr>
                {/literal} <!--Week End -->
                </tbody>
            </table>
        </div><!-- bigcal end -->
    </div><!-- block END -->
</div> <!-- miles END -->