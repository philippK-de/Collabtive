<div class="headline">
    <a href="javascript:void(0);" id="milehead_toggle" class="win_block" onclick="toggleBlock('milehead');"></a>

    <h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png" alt=""/>Upcoming {#milestones#}</h2>
    <loader block="currentMilestones" loader="loader-calendar.gif"></loader>
</div>

<div class="block" id="upcomingMilestonesHead">


    <div class="nosmooth">

        <table cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr>
                <th class="a"></th>
                <th class="b">{#milestone#}</th>
                <th class="c">{#due#}</th>
                <th class="days" style="text-align:right">{#daysleft#}&nbsp;&nbsp;</th>
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

        <table id="accordion_miles_new" cellpadding="0" cellspacing="0" border="0" style="clear:both;">
            <tbody v-for="milestone in items" class="alternateColors" id="miles_upcoming_{{milestone.ID}}">
            <tr>
                <td class="a">
                    {/literal}
                    {if $userpermissions.milestones.close}
                    {literal}
                        <a class="butn_check" href="javascript:closeElement('miles_upcoming_{{*milestone.ID}}','managemilestone.php?action=close&amp;mid={{*milestone.ID}}&amp;id={{*milestone.project}}');" title="{/literal}{#close#}"></a>
                    {/if}
                </td>
                {literal}
                    <td class="b">
                        <div class="toggle-in">
                                            <span class="acc-toggle"
                                                  onclick="javascript:accord_miles_new.activate($$('#accordion_miles_new .accordion_toggle')[{{$index}}]);toggleAccordeon('done_{{milestone.project}}',this);"></span>
                            <a href="managemilestone.php?action=showmilestone&amp;msid={{*milestone.ID}}&amp;id={{*milestone.project}}"
                               title="{{*milestone.name}}">{{*milestone.name | truncate '30' }}</a>
                        </div>
                    </td>
                    <td class="c">{{*milestone.startstring}} - {{*milestone.endstring}}</td>
                    <td class="days" style="text-align:right">{{*milestone.dayslate}}&nbsp;&nbsp;</td>
                {/literal}
                <td class="tools">
                    {if $userpermissions.milestones.edit}
                    {literal}
                        <a class="tool_edit" href="managemilestone.php?action=editform&amp;mid={{*milestone.ID}}&amp;id={{*milestone.project}}" title="{/literal}{#edit#}"></a>
                    {/if}
                    {if $userpermissions.milestones.del}
                        <a class="tool_del"
                           href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'miles_{$upcomingStones[ustone].ID}\',\'managemilestone.php?action=del&amp;mid={$upcomingStones[ustone].ID}&amp;id={$project.ID}\')');"
                           title="{#delete#}"></a>
                    {/if}
                </td>
            </tr>
            {literal}
            <tr class="acc">
                <td colspan="5">
                    <div class="accordion_toggle"></div>
                    <div class="accordion_content">
                        <div class="acc-in">
                            <div class="message-in">
                                {{{milestone.desc}}}

                                <!--Tasklists-->
                                {if $upcomingStones[ustone].tasklists[0][0]}
                                <div class="content-spacer-b"></div>
                                <h2>{#tasklists#}</h2>

                                <div class="inwrapper">
                                    <ul style="list-style-type:none;">
                                        {section name=task loop=$upcomingStones[ustone].tasklists}
                                        <li>
                                            <div class="itemwrapper">

                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="leftmen" valign="top">
                                                            <div class="inmenue">
                                                                <!-- <a class="more" href="javascript:fadeToggle('info_{$members[member].ID}');"></a>	-->
                                                            </div>
                                                        </td>
                                                        <td class="thumb">
                                                            <a href="managetasklist.php?action=showtasklist&amp;tlid={$upcomingStones[ustone].tasklists[task].ID}&amp;id={$project.ID}"
                                                               title="{$upcomingStones[ustone].tasklists[task].name}">
                                                                <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png"
                                                                     style="width: 32px; height: auto;" alt=""/>
                                                            </a>
                                                        </td>
                                                        <td class="rightmen" valign="top">
                                                            <div class="inmenue">
                                                                <!--
                                                                    <a class="del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'files_focus{$ordner[file].ID}\',\'managefile.php?action=delete&amp;id={$project.ID}&amp;file={$folders[fold].ID}\')');" title="{#delete#}" onclick="fadeToggle('iw_{$folders[fold].ID}');"></a>
                                                                    <a class="edit" href="#" title="{#editfile#}"></a>
                                                                -->
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
																							<span class="name">
																								<a href="managetasklist.php?action=showtasklist&amp;tlid={$upcomingStones[ustone].tasklists[task].ID}&amp;id={$project.ID}"
                                                                                                   title="{$upcomingStones[ustone].tasklists[task].name}">
                                                                                                    {if $upcomingStones[ustone].tasklists[task].name
                                                                                                    != ""}
                                                                                                    {$upcomingStones[ustone].tasklists[task].name|truncate:13:"...":true}
                                                                                                    {else}
                                                                                                    {#tasklist#}
                                                                                                    {/if}
                                                                                                </a>
																							</span>
                                                        </td>
                                                    <tr/>
                                                </table>

                                            </div>
                                            {*itemwrapper End*}
                                        </li>
                                        {/section} <!--loop Tasklists End-->

                                    </ul>
                                </div>
                                <!--inwrapper End-->

                                {/if}
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

</div>
<!--smooth End-->
<div class="content-spacer"></div>