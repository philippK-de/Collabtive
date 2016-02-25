<div class="projects" style="padding-bottom:2px;">
    <div class="headline">
        <a href="javascript:void(0);" id="projecthead_toggle" class="win_block"
           onclick="changeElements('a.win_block','win_none');toggleBlock('projecthead');"></a>

        <div class="wintools">
            <div class="progress" id="progressdesktopprojects" style="display:none;">
                <img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/loader-cal.gif"/>
            </div>
        </div>

        <h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt=""/>{#myprojects#}</h2>
    </div>
    <div class="acc_toggle"></div>
    <div class="block acc_content" id="projecthead" style="overflow:hidden;">{* Add project *}
        <div id="form_addmyproject" class="addmenue" style="display:none;">
            {include file="addproject.tpl" myprojects="1"}
        </div>
        <div class="nosmooth" id="sm_deskprojects">
            <table id="desktopprojects" cellpadding="0" cellspacing="0" border="0" v-cloak>    {literal}

                {/literal}
                <thead>
                <tr>
                    <th class="a"></th>
                    <th class="b" style="cursor:pointer;" onclick="sortBlock('desktopprojects','');">{#project#}</th>
                    <th class="c" style="cursor:pointer" onclick="sortBlock('desktopprojects','done');">{#done#}</th>
                    <th class="d" style="text-align:right" onclick="sortBlock('desktopprojects','daysleft');">{#daysleft#}&nbsp;&nbsp;</th>
                    <th class="tools"></th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <td colspan="5"></td>
                </tr>
                </tfoot>

                {literal}
                    <tbody v-for="item in items" id="proj_{{ item.ID }}" class="alternateColors" rel="{{*item.ID}},{{*item.name }},{{*item.daysleft }},0,0,{{ item.done }}" >
                    <tr  v-bind:class="{ 'marker-late': item.islate, 'marker-today': item.istoday }">
                        <td>
                            <a class="butn_check"
                               href="javascript:closeElement('proj_{{*item.ID}}','manageproject.php?action=close&amp;id={{*item.ID}}');"
                               title="{#close#}"></a>
                        </td>
                        <td>
                            <div class="toggle-in">
                                <span id="desktopprojectstoggle{{ item.ID }}" class="acc-toggle"
                                      onclick="javascript:accord_projects.activate($$('#projecthead .accordion_toggle')[{{$index}}]);toggleAccordeon('projecthead',this);"></span>
                                <a href="manageproject.php?action=showproject&amp;id={{*item.ID}}" title="{{*item.name}}">
                                    {{*item.name}}
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="statusbar_b">
                                <div class="complete" id="completed" style="width:{{item.done}}"></div>
                            </div>
                            <span>{{*item.done}}%</span>
                        </td>
                        <td style="text-align:right">{{*item.daysleft}}&nbsp;&nbsp;</td>
                        <td class="tools">


                            <a class="tool_edit" href="javascript:void(0);"
                               onclick="change('manageproject.php?action=editform&amp;id={{ item.ID }}','form_addmyproject');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_addmyproject');"
                               title="{#edit#}"></a>


                            <a class="tool_del" href="javascript:confirmDelete('{/literal}{#confirmdel#}{literal}','proj_{{*item.ID}}','manageproject.php?action=del&amp;id={{*item.ID}}',projectsView);"  title="{/literal}{#delete#}{literal}"></a>


                        </td>
                    </tr>

                    <tr class="acc">
                        <td colspan="5">
                            <div class="accordion_toggle"></div>
                            <div class="accordion_content">
                                <div class="acc-in">
                                    <div class="message-in">
                                        {{{*item.desc}}}
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                {/literal}
            </table>
            {if $closedProjectnum > 0}
                {*Doneprojects*}
                <div id="doneblock" class="projects" style="display: none;">
                    <table class="second-thead" cellpadding="0" cellspacing="0" border="0"
                           onclick="blindtoggle('doneblock');toggleClass('donebutn','butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">

                        <tr>
                            <td class="a"></td>
                            <td class="b"><span id="toggle-done" class="acc-toggle">{#closedprojects#}</span></td>
                            <td class="c"></td>
                            <td class="d"></td>
                            <td class="tools"></td>
                        </tr>

                    </table>


                    <div class="toggleblock">
                        <table cellpadding="0" cellspacing="0" border="0" id="acc-oldprojects">
                            {section name=clopro loop=$oldprojects}

                                {*Color-Mix*}
                                {if $smarty.section.clopro.index % 2 == 0}
                                    <tbody class="color-a" id="proj_{$oldprojects[clopro].ID}">
                                    {else}
                                    <tbody class="color-b" id="proj_{$oldprojects[clopro].ID}">
                                {/if}
                                <tr>
                                    <td class="a">{if $userpermissions.projects.add}<a class="butn_checked"
                                                                                       href="manageproject.php?action=open&amp;id={$oldprojects[clopro].ID}"
                                                                                       title="{#open#}"></a>{/if}</td>
                                    <td class="b">
                                        <div class="toggle-in">
                                            <span class="acc-toggle"
                                                  onclick="javascript:accord_oldprojects.activate($$('#acc-oldprojects .accordion_toggle')[{$smarty.section.clopro.index}]);toggleAccordeon('acc-oldprojects',this);"></span>
                                            <a href="manageproject.php?action=showproject&amp;id={$oldprojects[clopro].ID}"
                                               title="{$oldprojects[clopro].name}">
                                                {if $oldprojects[clopro].name != ""}
                                                    {$oldprojects[clopro].name|truncate:30:"...":true}
                                                {else}
                                                    {$oldprojects[clopro].desc|truncate:30:"...":true}
                                                {/if}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="c"></td>
                                    <td class="d" style="text-align:right">{$oldprojects[clopro].daysleft}&nbsp;&nbsp;</td>
                                    <td class="tools">
                                        {if $userpermissions.projects.edit}
                                            <a class="tool_edit" href="manageproject.php?action=editform&amp;id={$oldprojects[clopro].ID}"
                                               title="{#edit#}"></a>
                                        {/if}
                                        {if $userpermissions.projects.del}
                                            <a class="tool_del"
                                               href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{$oldprojects[clopro].ID}\',\'manageproject.php?action=del&amp;id={$oldprojects[clopro].ID}\')');"
                                               title="{#delete#}"></a>
                                        {/if}
                                    </td>
                                </tr>
                                <tr class="acc">
                                    <td colspan="5">
                                        <div class="accordion_toggle"></div>
                                        <div class="accordion_content">
                                            <div class="acc-in">
                                                {$oldprojects[clopro].desc}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            {/section}


                        </table>
                    </div> {*toggleblock End*}
                </div>
                {*doneblock end*}
            {/if}
            {*Doneprojects End*}
            <div class="tablemenue">
                <div class="tablemenue-in">
                    {if $userpermissions.projects.add}
                        <a class="butn_link" href="javascript:blindtoggle('form_addmyproject');" id="add_butn_myprojects"
                           onclick="toggleClass('add_myprojects','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_deskprojects','smooth','nosmooth');">{#addproject#}</a>
                    {/if}
                    {if $closedProjectnum > 0}
                        <a class="butn_link" href="javascript:blindtoggle('doneblock');" id="donebutn"
                           onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">{#closedprojects#}</a>
                    {/if}
                </div>
            </div>
        </div> {* block END *}
    </div> {* smooth END *}
</div> {* projects END *}


