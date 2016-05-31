<div id="desktopCalendar" class="miles" style="padding-bottom:2px;" v-cloak>
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
                            <a class="scroll_left"
                               href="javascript:updateCalendar(calendarView,'{{items.previousMonth}}','{{items.previousYear}}');"></a>
                        </th>
                        <th colspan="5" align="center">
                            <!--Localized month & year -->
                            {{items.monthName}} {{items.selectedYear}}
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
                            {{*day.val}}
                            <!--Only output tasks/milestones if the day belongs to the current month -->
                            <div v-if="day.currmonth == 1" class="calcontent">
                                <!--Milestones -->
                                <template v-if="day.milesnum > 0">
                                    <a href="javascript:openModal('miles_modal{{*day.val}}');">
                                        <img src="templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/symbols/miles.png"
                                             alt=""/>
                                    </a>

                                    <div id="miles_modal{{*day.val}}" class="milesmodal" style="display:none">
                                        <div class="modaltitle">
                                            <img src="./templates/{/literal}{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png"
                                                 alt=""/>
                                            {$langfile.milestones}{literal}
                                            {{*day.val}}.{{*items.currentMonth}}.{{*items.currentYear}}
                                            <a class="winclose" href="javascript:closeModal('miles_modal{{*day.val}}');"></a>
                                        </div>

                                        <div class="miles">
                                            <div class="block">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    {/literal}
                                                    <thead>
                                                    <th>{$langfile.project}</th>
                                                    <th>{$langfile.milestone}</th>
                                                    <th class="tools">{$langfile.daysleft}</th>
                                                    </thead>
                                                    {literal}

                                                    <tbody v-for="milestone in day.milestones" class="alternateColors">
                                                    <tr>
                                                        <td>{{*milestone.pname}}</td>
                                                        <td>
                                                            <a href="managemilestone.php?action=showmilestone&amp;msid={{*milestone.ID}}&amp;id={{*milestone.project}}"
                                                               title="{{*milestone.title}}">{{*milestone.name}}</a>
                                                        </td>
                                                        <td class="tools">
                                                            {{*milestone.daysleft}}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- modal end -->
                                    <!-- modalcontainer end -->
                                </template>
                                <!--Milestones End -->
                                <!--Tasks -->
                                <template v-if="day.tasksnum > 0">
                                    <a href="javascript:openModal('tasks_modal{{*day.val}}');">
                                        <img src="templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/symbols/task.png"
                                             alt=""/>
                                    </a>

                                    <div id="tasks_modal{{*day.val}}" class="tasksmodal" style="display:none;">
                                        <div class="modaltitle">
                                            <img src="./templates/{/literal}{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png"
                                                 alt=""/>
                                            {$langfile.tasklist}
                                            {literal}
                                            {{*day.val}}.{{*items.currentMonth}}.{{items.currentYear}}
                                            <a class="winclose" href="javascript:closeModal('tasks_modal{{*day.val}}');"></a>
                                        </div>
                                        <div class="inmodal">
                                            <div class="tasks">
                                                <div class="block">
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        {/literal}
                                                        <thead>
                                                        <th>{$langfile.project}</th>
                                                        <th>{$langfile.task}</th>
                                                        <th class="tools">{$langfile.daysleft}</th>
                                                        </thead>
                                                        {literal}
                                                        <tbody v-for="task in day.tasks" class="alternateColors">
                                                        <tr>
                                                            <td>{{*task.pname | truncate '15' }}</td>
                                                            <td>
                                                                <a href="managetask.php?action=showtask&amp;tid={{*task.ID}}&amp;id={{*task.project}}"
                                                                   title="{{*task.title}}">
                                                                    {{*task.title | truncate '15'}}
                                                                </a>

                                                            </td>
                                                            <td class="tools">{{*task.daysleft}}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <!-- tasks end -->
                            </div>
                        </td>
                    </tr>
                    {/literal} <!--Week End -->
                    </tbody>
                </table>
            </div>
            <!-- bigcal end -->
        </div>
        <!-- block END -->
    </div>
    <!-- miles END -->

