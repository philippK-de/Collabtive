{*Milestones*}
<div class="headline" id="currentMilestonesBlock">
    <a href="javascript:void(0);" id="currentMilestonesHead_toggle" class="win_block" onclick=""></a>

    <div class="wintools">
        <div class="progress" id="progresscurrentMilestones" style="float:left;display:none;">
            <img src="templates/standard/theme/standard/images/symbols/loader-calendar.gif"/>
        </div>
        {if $userpermissions.milestones.add}
            <a class="add" href="javascript:blindtoggle('addstone');" id="add"
               onclick="toggleClass(this,'add-active','add');toggleClass('add_butn','butn_link_active','butn_link');toggleClass('sm_miles','smooth','nosmooth');"><span>{#addmilestone#}</span></a>
        {/if}
    </div>

    <h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png" alt=""/>Current {#milestones#}</h2>


</div>

<div class="block blockaccordion_content" id="currentMilestonesHead" style="overlow:hidden">

    {*Add Milestone*}
    {if $userpermissions.milestones.add}
        <div id="addstone" class="addmenue" style="display:none;">
            {include file="addmilestone.tpl" }
        </div>
    {/if}

    <div class="nosmooth" id="sm_miles">

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

        {literal}
        <!--new Miles-->
        <div id="currentMilestones" class="toggleblock" v-cloak>

            <table id="accordion_miles_new" cellpadding="0" cellspacing="0" border="0" style="clear:both;">
                <tbody v-for="milestone in items.open" class="alternateColors" id="miles_{{milestone.ID}}">
                <tr>
                    <td class="a">
                        {/literal}
                        {if $userpermissions.milestones.close}
                        {literal}
                            <a class="butn_check" href="javascript:closeElement('miles_{{*milestone.ID}}','managemilestone.php?action=close&amp;mid={{*milestone.ID}}&amp;id={{*milestone.project}}', projectMilestonesView);" title="{/literal}{#close#}"></a>
                        {/if}
                    </td>
                    {literal}
                        <td class="b">
                            <div class="toggle-in">
                                            <span class="acc-toggle"
                                                  onclick="javascript:accord_miles_new.activate(document.querySelector('#currentMilestones_content{{$index}}'));">

                                            </span>
                                <a href="managemilestone.php?action=showmilestone&amp;msid={{milestone.ID}}&amp;id={{*milestone.project}}"
                                   title="{{*milestone.name}}">{{*milestone.name | truncate '30'}}</a>
                            </div>
                        </td>
                        <td class="c">{{*milestone.fend}}</td>
                        <td class="days" style="text-align:right">{{*milestone.dayslate}}&nbsp;&nbsp;</td>
                    {/literal}
                    <td class="tools">
                        {if $userpermissions.milestones.edit}
                        {literal}
                            <a class="tool_edit" href="managemilestone.php?action=editform&amp;mid={{milestone.ID}}&amp;id={{*milestone.project}}" title="{/literal}{#edit#}"></a>
                        {/if}
                        {if $userpermissions.milestones.del}
                        {literal}
                            <a class="tool_del"
                            href="javascript:confirmDelete('{/literal}{#confirmdel#}{literal}','miles_{{*milestone.ID}}','managemilestone.php?action=del&amp;mid={{*milestone.ID}}&amp;id={{*milestone.project}}', projectMilestonesView);"
                            title="{/literal}{#delete#}"></a>
                        {/if}
                    </td>
                </tr>
                {literal}
                <tr class="acc">
                    <td colspan="5">
                        <div class="accordion_content" data-slide="{{$index}}" id="currentMilestones_content{{$index}}">
                            <div class="acc-in">
                                <div class="message-in">
                                    {{{*milestone.desc}}}

                                    <!--Tasklists-->
                                    <div v-if="milestone.hasTasklist" class="content-spacer-b"></div>
                                    <h2 v-if="milestone.hasTasklist">{/literal}{#tasklists#}{literal}</h2>

                                    <div v-if="milestone.hasTasklist" class="inwrapper">
                                        <ul style="list-style-type:none;">
                                            <li v-for="tasklist in milestone.tasklists">
                                                <div class="itemwrapper">

                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="leftmen" valign="top">
                                                            </td>
                                                            <td class="thumb">
                                                                <a href="managetasklist.php?action=showtasklist&amp;tlid={{*tasklist.ID}}&amp;id={{*tasklist.project}}"
                                                                   title="{{*tasklist.name}}">
                                                                    <img src="./templates/standard/theme/standard/images/symbols/tasklist.png"
                                                                         style="width: 32px; height: auto;" alt=""/>
                                                                </a>
                                                            </td>
                                                            <td class="rightmen" valign="top">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                <span class="name">
                                                                    <a href="managetasklist.php?action=showtasklist&amp;tlid={{*tasklist.ID}}&amp;id={{*tasklist.project}}"
                                                                       title="{{*tasklist.name}}">
                                                                        {{*tasklist.name}}
                                                                    </a>
                                                                </span>
                                                            </td>
                                                        <tr/>
                                                    </table>

                                                </div>
                                                <!--itemwrapper End-->
                                            </li>

                                        </ul>
                                    </div>
                                    <!--inwrapper End-->
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>

            </table>

            <!--toggleblock End  new Miles End-->
            {/literal}
            {*finished Miles*}
            <div id="doneblock" class="doneblock" style="display: none;">

                <table class="second-thead" cellpadding="0" cellspacing="0" border="0"
                       onclick="blindtoggle('doneblock');toggleClass('donebutn','butn_link_active','butn_link');toggleClass('togglemilesdone','acc-toggle','acc-toggle-active');">
                    <tr>
                        <td class="a"></td>
                        <td class="b"><span id="togglemilesdone" class="acc-toggle">{#donemilestones#}</span></td>
                        <td class="c"></td>
                        <td class="tools"></td>
                    </tr>
                </table>

                <div class="toggleblock">

                    <table id="accordion_miles_done" cellpadding="0" cellspacing="0" border="0">
                        {literal}
                        <tbody v-for="oldmilestone in items.closed" class="alternateColors" id="miles_{{*milestone.ID}}">
                        <tr>
                            <td class="a">
                                {/literal}
                                {if $userpermissions.milestones.close}
                                {literal}
                                    <a class="butn_checked"
                                    href="managemilestone.php?action=open&amp;mid={{*oldmilestone.ID}}&amp;id={{*oldmilestone.project}}"
                                    title="{/literal}{#open#}"></a>
                                {/if}
                            </td>
                            {literal}
                            <td class="b">
                                <div class="toggle-in">
                                    <a href="managemilestone.php?action=showmilestone&amp;msid={{*oldmilestone.ID}}&amp;id={{*oldmilestone.project}}"
                                       title="{{*oldmilestone.name}}">{{*oldmilestone.name | truncate '30'}}</a>
                                </div>
                            </td>
                            <td class="c">{{*oldmilestone.fend}}</td>
                            <td class="tools">
                                {/literal}
                                {if $userpermissions.milestones.del}
                                {literal}
                                    <a class="tool_del"
                                    href="javascript:confirmDelete({/literal}'{#confirmdel#}'{literal},'miles_{{*oldmilestone.ID}}','managemilestone.php?action=del&amp;mid={{*oldmilestone.ID}}&amp;id={{*oldmilestone.project}}');"
                                    title="{/literal}{#delete#}"></a>
                                {/if}
                            </td>
                        </tr>
                        {literal}
                        </tbody>
                        {/literal}
                    </table>
                </div>
                <!-- toggleblock End finished Miles End-->
            </div>
            <!--done_block End-->

            <div class="tablemenue">
                <div class="tablemenue-in">
                    {if $userpermissions.milestones.add}
                        <a class="butn_link" href="javascript:blindtoggle('addstone');" id="add_butn"
                           onclick="toggleClass('add','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_miles','smooth','nosmooth');">{#addmilestone#}</a>
                    {/if}
                    <a class="butn_link" href="javascript:blindtoggle('doneblock');" id="donebutn"
                       onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('togglemilesdone','acc-toggle','acc-toggle-active');">{#donemilestones#}</a>
                </div>
            </div>
        </div>
    </div>
</div>