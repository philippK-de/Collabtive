<div id="desktopprojects" class="projects" style="padding-bottom:2px;">
    <div class="headline">
        <a href="javascript:void(0);" id="projecthead_toggle" class="win_block" onclick=""></a>

        <div class="wintools">
          <!--  <loader block="desktopprojects" loader="loader-project3.gif"></loader>   -->
            <loader block="desktopprojects" loader="loader-project3.gif"></loader>
        </div>

        <h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt=""/>{#myprojects#}
            {* Pagination *}
            <pagination view="projectsView" :pages="pages" :current-page="currentPage"></pagination>
        </h2>

    </div>
    <div class="block blockaccordion_content" id="projecthead" style="overflow:hidden;">
        <div id="form_addmyproject" class="addmenue" style="display:none;">
            {include file="addproject.tpl" myprojects="1"}
        </div>
        <div class="nosmooth" id="sm_deskprojects">
            <table cellpadding="0" cellspacing="0" border="0" id="desktoProjectsTable" v-cloak>    {literal}

                {/literal}
                <thead>
                <tr>
                    <th class="a"></th>
                    <th class="b" style="cursor:pointer;">{#project#}</th>
                    <th class="c" style="cursor:pointer">{#done#}</th>
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
                <tbody v-for="item in items.open" id="proj_{{item.ID}}" class="alternateColors"
                       rel="{{item.ID}},{{item.name}},{{item.daysleft}},0,0,{{item.done}}">

                <tr v-bind:class="{ 'marker-late': item.islate, 'marker-today': item.istoday }">
                    <td>
                        {/literal}
                        {if $userpermissions.projects.close}
                        {literal}
                            <a class="butn_check" href="javascript:closeElement('proj_{{*item.ID}}','manageproject.php?action=close&amp;id={{*item.ID}}', projectsView);" title="{/literal}{#close#}{literal}"></a>
                        {/literal}
                        {/if}
                        {literal}
                    </td>
                    <td>
                        <div class="toggle-in">
                                <span id="desktopprojects_toggle{{ item.ID }}" class="acc-toggle"
                                      onclick="javascript:accord_projects.activate(document.querySelector('#projecthead_content{{$index}}'));"></span>
                            <a href="manageproject.php?action=showproject&amp;id={{*item.ID}}" title="{{*item.name}}">
                                {{*item.name}}
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="statusbar_b">
                            <div class="complete" id="completed" style="width:{{item.done}}%"></div>
                        </div>
                        <span>{{*item.done}}%</span>
                    </td>
                    <td style="text-align:right">{{*item.daysleft}}&nbsp;&nbsp;</td>
                    <td class="tools">
                        {/literal}
                        {if $userpermissions.projects.edit}
                        {literal}
                            <a class="tool_edit" href="javascript:void(0);"
                               onclick="change('manageproject.php?action=editform&amp;id={{ item.ID }}','form_addmyproject');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_addmyproject');"
                               title="{#edit#}"></a>
                        {/literal}
                        {/if}
                        {if $userpermissions.projects.del}
                        {literal}
                            <a class="tool_del"
                            href="javascript:confirmDelete('{/literal}{#confirmdel#}{literal}','proj_{{*item.ID}}','manageproject.php?action=del&amp;id={{*item.ID}}',projectsView);" title="{/literal}{#delete#}{literal}"></a>
                        {/literal}
                        {/if}
                        {literal}

                    </td>
                </tr>

                <tr class="acc">
                    <td colspan="5">
                        <div class="accordion_content" data-slide="{{$index}}" id="projecthead_content{{$index}}">
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


            {*Doneprojects*}
            <div id="projectsDoneblock" class="projects" style="display: none;">
                <table class="second-thead" cellpadding="0" cellspacing="0" border="0"
                       onclick="blindtoggle('projectsDoneblock');toggleClass('donebutn','butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">

                    <tr>
                        <td class="a"><span id="toggle-done">{#closedprojects#}</span></td>
                        <td class="b"></td>

                        <td class="tools"></td>
                    </tr>

                </table>

                {literal}
                <div class="toggleblock">
                    <table cellpadding="0" cellspacing="0" border="0" id="acc-oldprojects">

                        <tbody v-for="item in items.closed" class="alternateColors" id="proj_{{item.ID}}">
                        <tr>
                            <td class="a">
                                {/literal}
                                {if $userpermissions.projects.add}
                                <a class="butn_checked"
                                   href="manageproject.php?action=open&amp;id={literal}{{item.ID}}{/literal}"
                                   title="{#open#}"></a>
                                {/if}
                                {literal}
                            </td>
                            <td class="b">
                                {{item.name}}
                            </td>
                           <td class="tools">
                                {/literal}
                                {if $userpermissions.projects.del}
                                {literal}
                                    <a class="tool_del"
                                    href="javascript:confirmDelete('{/literal}{#confirmdel#}{literal}','proj_{{*item.ID}}','manageproject.php?action=del&amp;id={{*item.ID}}',projectsView);" title="{/literal}{#delete#}{literal}"></a>
                                {/literal}
                                {/if}
                                {literal}
                            </td>
                        </tr>
                        </tbody>
                        {/literal}


                    </table>
                </div> {*toggleblock End*}
            </div>
            {*doneblock end*}

            {*Doneprojects End*}
            <div class="tablemenue">
                <div class="tablemenue-in">
                    {if $userpermissions.projects.add}
                        <a class="butn_link" href="javascript:blindtoggle('form_addmyproject');" id="add_butn_myprojects"
                           onclick="toggleClass('add_myprojects','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_deskprojects','smooth','nosmooth');">{#addproject#}</a>
                    {/if}
                    {if $closedProjectnum > 0}
                        <a class="butn_link" href="javascript:blindtoggle('projectsDoneblock');" id="donebutn"
                           onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">{#closedprojects#}</a>
                    {/if}
                </div>
            </div>
            <div class="content-spacer"></div>
        </div> {* block END *}
    </div> {* smooth END *}
</div> {* projects END *}



