<div id="desktopprojects" class="projects padding-bottom-two-px">
    <div class="headline">
        <a href="javascript:void(0);" id="projecthead_toggle" class="win_block" onclick=""></a>

        <div class="wintools">
            <loader block="desktopprojects" loader="loader-project3.gif"></loader>
        </div>

        <h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt=""/>{#myprojects#}
            {* Pagination *}
            <pagination view="projectsView" :pages="pages" :current-page="currentPage"></pagination>
        </h2>

    </div>
    <div class="block blockaccordion_content overflow-hidden display-none" id="projecthead">
        <div id="form_addmyproject" class="addmenue display-none">
            {include file="forms/addproject.tpl" myprojects="1"}
        </div>
        <div class="nosmooth" id="sm_deskprojects">
            <table cellpadding="0" cellspacing="0" border="0" id="desktopProjectsTable" v-cloak>
                <thead>
                <tr>
                    <th class="a"></th>
                    <th class="b" class="cursor-pointer">{#project#}</th>
                    <th class="c" class="cursor-pointer">{#done#}</th>
                    <th class="d" class="text-align-right">{#daysleft#}&nbsp;&nbsp;</th>
                    <th class="tools"></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="5"></td>
                </tr>
                </tfoot>

                {literal}
                <tbody v-for="item in items.open"
                       v-bind:id="'proj_'+item.ID" class="alternateColors">

                <tr v-bind:class="{ 'marker-late': item.islate, 'marker-today': item.istoday }">
                    <td>
                        {/literal}
                        {if $userpermissions.projects.close}
                        {literal}
                            <a class="butn_check"
                            v-bind:href="'javascript:closeElement(\'proj_'+item.ID+'\',\'manageproject.php?action=close&amp;id='+item.ID+'\',projectsView);'"
                            title="{/literal}{#close#}{literal}"></a>
                        {/literal}
                        {/if}
                        {literal}
                    </td>
                    <td>
                        <div class="toggle-in">
                                <span
                                        v-bind:id="'desktopprojects_toggle'+item.ID"
                                        class="acc-toggle"
                                        :onclick="'accord_projects.toggle(css(\'#desktopprojects_content'+$index+'\'));'"></span>
                            <a v-bind:href="'manageproject.php?action=showproject&amp;id=' + item.ID"
                               v-bind:title=item.name>
                                {{{item.name | truncate '35' }}}
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="statusbar_b">
                            <div class="complete" id="completed"
                                 v-bind:style="'width:' + item.done + '%'"></div>
                        </div>
                        <span>{{item.done}}%</span>
                    </td>
                    <td class="text-align-right">{{item.daysleft}}&nbsp;&nbsp;</td>
                    <td class="tools">
                        {/literal}
                        {if $userpermissions.projects.edit}
                        {literal}
                            <a class="tool_edit"
                               v-bind:href="'javascript:change(\'manageproject.php?action=editform&amp;id='+item.ID+'\',\'form_addmyproject\');blindtoggle(\'form_addmyproject\');'"
                               title="{#edit#}"></a>
                        {/literal}
                        {/if}
                        {if $userpermissions.projects.del}
                        {literal}
                            <a class="tool_del"
                            v-bind:href="'javascript:confirmDelete(\'{/literal}{#confirmdel#}{literal}\',\'proj_'+item.ID+'\',\'manageproject.php?action=del&amp;id='+item.ID+'\',projectsView);'"
                            title="{/literal}{#delete#}{literal}"></a>
                        {/literal}
                        {/if}
                        {literal}

                    </td>
                </tr>

                <tr class="acc">
                    <td colspan="5">
                        <div class="accordion_content">
                            <div class="acc-in">
                                <div class="message-in-fluid" v-html="item.desc">
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
                {/literal}
            </table>

            {*Doneprojects*}
            <div id="projectsDoneblock" class="projects display-none">
                <table class="second-thead" cellpadding="0" cellspacing="0" border="0"
                       onclick="blindtoggle('projectsDoneblock');toggleClass('donebutn','butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">
                    <tr>
                        <td class="a"></td>
                        <td class="b" class="cursor-pointer"><span id="toggle-done">{#closedprojects#}</span></td>
                        <td class="c" class="cursor-pointer"></td>
                        <td class="d" class="text-align-right"></td>
                        <td class="tools"></td>
                    </tr>
                </table>

                {literal}
                <div class="toggleblock">
                    <table cellpadding="0" cellspacing="0" border="0" id="acc-oldprojects">

                        <tbody v-for="item in items.closed" class="alternateColors"
                               v-bind:id="'proj_'+item.ID">
                        <tr>
                            <td class="a">
                                {/literal}
                                {if $userpermissions.projects.add}
                                    <a class="butn_checked"
                                       v-bind:href="'manageproject.php?action=open&amp;id='{literal}+ item.ID{/literal}"
                                       title="{#open#}"></a>
                                {/if}
                                {literal}
                            </td>
                            <td class="b">
                                {{item.name | truncate '40' }}
                            </td>
                            <td></td>
                            <td></td>
                            <td class="tools">
                                {/literal}
                                {if $userpermissions.projects.del}
                                {literal}
                                    <a class="tool_del"
                                    v-bind:href="'javascript:confirmDelete(\'{/literal}{#confirmdel#}{literal}\',\'proj_'+item.ID+'\',\'manageproject.php?action=del&amp;id='+item.ID+'\',projectsView);'"
                                    title="{/literal}{#delete#}{literal}"></a>
                                {/literal}
                                {/if}
                                {literal}
                            </td>
                        </tr>
                        </tbody>
                        {/literal}
                    </table>
                </div> {*toggleblock End*}
            </div> {*doneblock end*}
            {*Doneprojects End*}

            {* If no projects exist, open form to add a project *}
            {if $openProjectnum < 1 && $userpermissions.projects.add}
                <script type="text/javascript">
                    toggleClass('sm_deskprojects', 'smooth', 'nosmooth');
                    blindtoggle('form_addmyproject');
                </script>
            {/if}

            <div class="tablemenue">
                <div class="tablemenue-in">
                    {if $userpermissions.projects.add}
                        <a class="butn_link" href="javascript:blindtoggle('form_addmyproject');" id="add_butn_myprojects"
                           onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_deskprojects','smooth','nosmooth');">{#addproject#}</a>
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
<script type="text/javascript">
    {literal}
    theCal = new calendar({/literal}{$theM},{$theY}	);
    theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
    theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
    theCal.relateTo = "endP";
    theCal.dateFormat = "{$settings.dateformat}";
    theCal.getDatepicker("add_project");
</script>



