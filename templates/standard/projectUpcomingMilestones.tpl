<div class="headline">
    <a href="javascript:void(0);" id="upcomingMilestones_toggle" class="win_block" onclick=""></a>

    <div class="wintools">
        <div class="progress float-left display-none" id="progressupcomingMilestones">
            <img src="templates/standard/theme/standard/images/symbols/loader-calendar.gif"/>
        </div>
    </div>
    <h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png" alt=""/>{#upcomingmilestones#}</h2>
</div>
<div class="block blockaccordion_content overflow-hidden display-none" id="upcomingMilestonesHead">
    <div id="sm_miles_upcoming" class="nosmooth">

        <table cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr>
                <th class="a"></th>
                <th class="b">{#milestone#}</th>
                <th class="c">{#due#}</th>
                <th class="days text-align-right">{#daysleft#}&nbsp;&nbsp;</th>
                <th class="tools"></th>
            </tr>
            </thead>

            <tfoot>
            <tr>
                <td colspan="5"></td>
            </tr>
            </tfoot>
        </table>
    </div>
    {literal}
    <!--Upcoming miles-->

    <div id="upcomingMilestones" class="toggleblock" v-cloak>
        <table id="accordion_miles_upcoming" cellpadding="0" cellspacing="0" border="0" class="clear_both">
            <tbody v-for="milestone in items" class="alternateColors" id="miles_upcoming_{{milestone.ID}}">
            <tr>
                <td class="a">
                    {/literal}
                    {if $userpermissions.milestones.close}
                    {literal}
                        <a class="butn_check" href="javascript:closeElement('miles_upcoming_{{milestone.ID}}','managemilestone.php?action=close&amp;mid={{milestone.ID}}&amp;id={{milestone.project}}');" title="{/literal}{#close#}"></a>
                    {/if}
                </td>
                {literal}
                    <td class="b">
                        <div class="toggle-in">
                            <span class="acc-toggle"
                                  onclick="javascript:accord_miles_upcoming.activate(css('#upcomingMilestones_content{{$index}}'));">
                                <a href="javascript:void(0);" title="{{milestone.name}}">{{{milestone.name | truncate '30' }}}</a>
                            </span>

                        </div>
                    </td>
                    <td class="c">{{milestone.startstring}} - {{milestone.endstring}}</td>
                    <td class="days text-align-right">{{milestone.dayslate}}&nbsp;&nbsp;</td>
                {/literal}
                <td class="tools">
                    {if $userpermissions.milestones.edit}
                    {literal}
                        <a class="tool_edit" href="managemilestone.php?action=editform&amp;mid={{milestone.ID}}&amp;id={{milestone.project}}" title="{/literal}{#edit#}"></a>
                    {/if}
                    {if $userpermissions.milestones.del}
                    {literal}
                        <a class="tool_del"
                        href="javascript:confirmDelete('{/literal}{#confirmdel#}{literal}','miles_{{milestone.ID}}','managemilestone.php?action=del&amp;mid={{milestone.ID}}&amp;id={{milestone.project}}', projectMilestonesView);"
                        title="{/literal}{#delete#}"></a>
                    {/if}
                </td>
            </tr>
            {literal}
            <tr class="acc">
                <td colspan="5">
                    <div class="accordion_content" data-slide="{{$index}}" id="upcomingMilestones_content{{$index}}">
                        <div class="acc-in">
                            <div class="message-in">
                                {{{milestone.desc}}}

                                <!--Tasklists-->
                                <template v-if="milestone.tasklists.length > 0">
                                    <div class="content-spacer-b"></div>
                                    <h2>{/literal}{#tasklists#}{literal}</h2>

                                    <div class="inwrapper">
                                        <ul class="list-style-none">
                                            <li class="list-style-none" v-for="tasklist in milestone.tasklists">
                                                <div class="itemwrapper">
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="leftmen" valign="top">
                                                            </td>
                                                            <td class="thumb">
                                                                <a href="managetasklist.php?action=showtasklist&amp;tlid={{tasklist.ID}}&amp;id={{tasklist.project}}"
                                                                   title="{{{tasklist.name}}}">
                                                                    <img src="./templates/standard/theme/standard/images/symbols/tasklist.png"
                                                                         alt=""/>
                                                                </a>
                                                            </td>
                                                            <td class="rightmen" valign="top">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                            <span class="name">
                                                                <a href="managetasklist.php?action=showtasklist&amp;tlid={{tasklist.ID}}&amp;id={{tasklist.project}}"
                                                                   title="{{{tasklist.name}}}">
                                                                    {{{tasklist.name | truncate '10' }}}
                                                                </a>
                                                            </span>
                                                            </td>
                                                        <tr/>
                                                    </table>

                                                </div>
                                            </li><!--loop Tasklists End-->

                                        </ul>
                                    </div>
                                    <!--inwrapper End-->
                                </template>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- toggleblock End -->
    <!--Upcoming miles end -->
    {/literal}
</div>
<!--smooth End-->
<div class="content-spacer"></div>
