<div class="headline">
    <a href="javascript:void(0);" id="lateMilestones_toggle" class="win_block" onclick=""></a>

    <div class="wintools">
        <div class="progress display-none float-left" id="progresslateMilestones">
            <img src="templates/standard/theme/standard/images/symbols/loader-calendar.gif"/>
        </div>
    </div>
    <h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png" alt=""/>{#latestones#}</h2>
</div>

<div class="block blockaccordion_content overflow-hidden display-none" id="lateMilestonesHead">
    <div id="sm_miles_late" class="nosmooth">

        <table cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr>
                <th class="a"></th>
                <th class="b">{#milestone#}</th>
                <th class="c">{#user#}</th>
                <th class="d">{#due#}</th>
                <th class="days" class="text-align-right">{#daysleft#}&nbsp;&nbsp;</th>
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
    <!--late Miles-->
    <div class="toggleblock" id="lateMilestones" v-cloak>
        {literal}
        <table class="miles" id="accordion_miles_late" cellpadding="0" cellspacing="0" border="0">
            <tbody v-for="milestone in items" class="alternateColors"
                   v-bind:id="'miles_late_'+milestone.ID">
            <tr class="marker-late">
                <td class="a">
                    {/literal}
                    {if $userpermissions.milestones.close}
                    {literal}
                        <a class="butn_check" href="javascript:closeElement('miles_late_{{milestone.ID}}','managemilestone.php?action=close&amp;mid={{milestone.ID}}&amp;id={{milestone.project}}', lateProjectMilestonesView);" title="{/literal}{#close#}"></a>
                    {/if}
                </td>
                {literal}
                    <td class="b">
                        <div class="toggle-in">
                            <span class="acc-toggle"
                                  onclick="javascript:accord_miles_late.activate(css('#lateMilestones_content{{$index}}'));">
                                 <a href="javascript:void(0);" title="{{{milestone.name}}}">{{{milestone.name | truncate '30' }}}</a>
                            </span>

                        </div>
                    </td>
                    <td class="c">{{milestone.user}}</td>
                    <td class="d">{{milestone.fend}}</td>
                    <td class="days text-align-right">-{{milestone.dayslate}}&nbsp;&nbsp;</td>
                {/literal}
                <td class="tools">
                    {if $userpermissions.milestones.edit}
                    {literal}
                        <a class="tool_edit" href="managemilestone.php?action=editform&amp;mid={{milestone.ID}}&amp;id={{milestone.project}}"
                        title="{/literal}{#edit#}"></a>
                    {/if}
                    {if $userpermissions.milestones.del}
                    {literal}
                        <a class="tool_del"
                        href="javascript:confirmDelete('{/literal}{#confirmdel#}{literal}','miles_late_{{milestone.ID}}','managemilestone.php?action=del&amp;mid={{milestone.ID}}&amp;id={{milestone.project}}', lateProjectMilestonesView);"
                        title="{#delete#}"></a>
                    {/literal}
                    {/if}
                </td>
                {literal}
            </tr>
            <tr class="acc">
                <td></td>
                <td colspan="5">
                    <div class="accordion_content" data-slide="{{$index}}" id="lateMilestones_content{{milestone.ID}}">
                        <div class="acc-in">
                            <div class="message-in">
                                {{{milestone.desc}}}
                                <!--Tasklists-->
                                <template v-if="milestone.hasTasklist">
                                    <div class="content-spacer-b"></div>
                                    <h2>{/literal}{#tasklists#}{literal}</h2>

                                    <div class="dtree"
                                         :id="'milestoneTree_' + milestone.ID">

                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>

        </table>

    </div>
    {/literal}
    <!--toggleblock End-->
</div>
<div class="padding-bottom-two-px"></div>
<!-- block end -->