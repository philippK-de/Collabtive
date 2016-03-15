{include file="header.tpl" jsload = "ajax"  jsload1 = "tinymce" }
{include file="tabsmenue-project.tpl" taskstab = "active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="tasks" id="projectTasks">

            {*System Message*}
            <div class="infowin_left" style="display:none;" id="systemmsg">
                {if $mode == "added"}
                    <span class="info_in_green"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png"
                                                     alt=""/>{#taskwasadded#}</span>
                {elseif $mode == "edited"}
                    <span class="info_in_yellow"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png"
                                                      alt=""/>{#taskwasedited#}</span>
                {elseif $mode == "deleted"}
                    <span class="info_in_red"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png"
                                                   alt=""/>{#taskwasdeleted#}</span>
                {elseif $mode == "opened"}
                    <span class="info_in_green"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png"
                                                     alt=""/>{#taskwasopened#}</span>
                {elseif $mode == "closed"}
                    <span class="info_in_red"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png"
                                                   alt=""/>{#taskwasclosed#}</span>
                {elseif $mode == "assigned"}
                    <span class="info_in_yellow"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png"
                                                      alt=""/>{#taskwasassigned#}</span>
                {elseif $mode == "deassigned"}
                    <span class="info_in_yellow"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png"
                                                      alt=""/>{#taskwasdeassigned#}</span>
                {elseif $mode == "error"}
                    <span class="info_in_red"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png"
                                                   alt=""/>{#error#}</span>
                {elseif $mode == "listadded"}
                    <span class="info_in_green"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png"
                                                     alt=""/>{#tasklistwasadded#}</span>
                {elseif $mode == "listclosed"}
                    <span class="info_in_red"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist-done.png"
                                                   alt=""/>{#tasklistwasclosed#}</span>
                {elseif $mode == "listdeleted"}
                    <span class="info_in_red"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png"
                                                   alt=""/>{#tasklistwasdeleted#}</span>
                {elseif $mode == "listopened"}
                    <span class="info_in_green"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png"
                                                     alt=""/>{#tasklistwasopened#}</span>
                {/if}

                {*for async display*}
                <span id="added" style="display:none;" class="info_in_green"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png" alt=""/>{#taskwasadded#}</span>
                <span id="edited" style="display:none;" class="info_in_yellow"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png" alt=""/>{#taskwasedited#}</span>
                <span id="deleted" style="display:none;" class="info_in_red"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png" alt=""/>{#taskwasdeleted#}</span>
                <span id="opened" style="display:none;" class="info_in_green"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png" alt=""/>{#taskwasopened#}</span>
                <span id="closed" style="display:none;" class="info_in_green"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png" alt=""/>{#taskwasclosed#}</span>
                <span id="assigned" style="display:none;" class="info_in_yellow"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png" alt=""/>{#taskwasassigned#}</span>
                <span id="deassigned" style="display:none;" class="info_in_yellow"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png" alt=""/>{#taskwasdeassigned#}</span>
                <span id="listadded" style="display:none;" class="info_in_green"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png"
                            alt=""/>{#tasklistwasadded#}</span>
                <span id="listclosed" style="display:none;" class="info_in_red"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist-done.png"
                            alt=""/>{#tasklistwasclosed#}</span>
                <span id="listdeleted" style="display:none;" class="info_in_red"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png"
                            alt=""/>{#tasklistwasdeleted#}</span>
                <span id="listopened" style="display:none;" class="info_in_green"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png"
                            alt=""/>{#tasklistwasopened#}</span>
            </div>

            {literal}
                <script type="text/javascript">
                    systemMsg('systemmsg');
                </script>
            {/literal}{*/System Message*}

            <h1>{$projectname|truncate:45:"...":true}<span>/ {#tasklists#}</span></h1>

            {if $userpermissions.tasks.add}
                <div class="add-main">
                    <a id="addtasklists" class="add" href="javascript:blindtoggle('addlist');"
                       onclick="toggleClass(this,'add-active','add');"><span>{#addtasklist#}</span></a>
                </div>
            {/if}

            {if $userpermissions.tasks.add} {*Add Tasklist*}
                <div id="addlist" class="addmenue" style="display:none;">
                    {include file="addtasklist.tpl" }
                </div>
            {/if} {*Add Tasklist END*}

            {*Tasks*}

            {*section name=list loop=$lists*}
            {literal}
            <div v-for="list in items">
                <div class="headline">
                    <a href="javascript:void(0);" id="block-{{*list.ID}}_toggle" class="win_block" onclick="toggleBlock('block-{{list.ID}}');"></a>

                    <div class="wintools">
                        {/literal}
                        {if $userpermissions.tasks.close}
                        {literal}
                            <a class="close" href="managetasklist.php?action=close&amp;tlid={{*list.ID}}&amp;id={{*list.project}}"><span>{/literal}{#close#}</span>
                            </a>
                        {/if}
                        {if $userpermissions.tasks.edit}
                        {literal}
                            <a class="edit" href="managetasklist.php?action=editform&amp;tlid={{*list.ID}}&amp;id={{*list.project}}"><span>{/literal}{#edit#}</span>
                            </a>
                        {/if}
                        {if $userpermissions.tasks.del}
                        {literal}
                            <a class="del" href="javascript:confirmit('{/literal}{#confirmdel#}{literal}','managetasklist.php?action=del&amp;tlid={{list.ID}}&amp;id={{list.project}}');">
                            <span>{/literal}{#delete#}</span></a>
                        {/if}
                        {if $userpermissions.tasks.add}
                        {literal}
                            <a class="add" href="javascript:blindtoggle('form_{{*list.ID}}');" id="add_{{*list.ID}}" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_{{*list.ID}}','butn_link_active','butn_link');toggleClass('sm_{{*list.ID}}','smooth','nosmooth');"><span>{/literal}{#addtask#}</span>
                            </a>
                        {/if}
                        {literal}
                    </div>

                    <h2>
                        <a href="managetasklist.php?action=showtasklist&amp;id={{*list.project}}&amp;tlid={{*list.ID}}" title="{{*list.name}}">
                            <img src="./templates/{/literal}{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt=""/>
                            {literal}
                            {{*list.name}}
                        </a>
                    </h2>
                </div>

                <div id="block-{{*list.ID}}" class="block">
                    {/literal}
                    {if $userpermissions.tasks.add}
                    {literal}
                        <div id="form_{{*list.ID}}" class="addmenue" style="display:none;">
                    {/literal}{include file="addtask.tpl" }
                        </div>
                    {/if}
                    {literal}
                    <div class="nosmooth" id="sm_{{*list.ID}}">
                        <table id="acc_{{list.ID}}" cellpadding="0" cellspacing="0" border="0">
                            {/literal}
                            <thead>
                            <tr>
                                <th class="a"></th>
                                <th class="b">{#tasks#}</th>
                                <th class="c">{#user#}</th>
                                <th class="days" style="text-align:right">{#daysleft#}&nbsp;&nbsp;</th>
                                <th class="tools"></th>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <td colspan="5"></td>
                            </tr>
                            </tfoot>
                            {literal}
                            <tbody v-for="task in list.tasks" class="alternateColors" id="task_{{*task.ID}}">
                            <tr v-bind:class="{ 'marker-late': task.islate, 'marker-today': task.istoday }">
                                <td>
                                    {/literal}
                                    {if $userpermissions.tasks.close}
                                    {literal}
                                        <a class="butn_check" href="javascript:closeElement('task_{{*task.ID}}','managetask.php?action=close&amp;tid={{*task.ID}}&ampid={{*task.project}}');" title="{/literal}{#close#}"></a>
                                    {/if}
                                </td>
                                {literal}
                                <td>
                                    <div class="toggle-in">
                                        <span class="acc-toggle"
                                              onclick="javascript:accord_{{list.ID}}.activate($$('#acc_{{*list.ID}} .accordion_toggle')[{$index}])toggleAccordeon('acc_{{*list.ID}}',this);"></span>
                                        <a href="managetask.php?action=showtask&amp;tid={{*task.ID}}&amp;id={{*task.project}}"
                                           title="{{*task.title}}">
                                            {{{*task.title}}}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <a v-for="user in task.users" href="manageuser.php?action=profile&amp;id={{user.ID}}">{{*user.name}}</a>
                                </td>
                                <td style="text-align:right">{{*task.daysleft}}&nbsp;&nbsp;</td>
                                <td class="tools">
                                    {/literal}
                                    {if $userpermissions.tasks.edit}
                                    {literal}
                                        <a class="tool_edit" href="javascript:void(0);" onclick="change('managetask.php?action=editform&amp;' +
											 'tid={{task.ID}}&amp;id={{task.project}}','form_{{list.ID}}');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_{{list.ID}}');" title="{/literal}{#edit#}"></a>
                                    {/if}
                                    {if $userpermissions.tasks.del}
                                    {literal}
                                        <a class="tool_del"
                                           href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$lists[list].tasks[task].ID}\',\'managetask.php?action=del&amp;tid={$lists[list].tasks[task].ID}&amp;id={$project.ID}\')');"
                                           title="{#delete#}"></a>
                                    {/literal}
                                    {/if}
                                    {literal}
                                </td>
                            </tr>

                            <tr class="acc">
                                <td colspan="5">
                                    <div class="accordion_toggle"></div>
                                    <div class="accordion_content">
                                        <div class="acc-in">
                                            <div class="message-in">
                                                {{{*task.text}}}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>

                            <script type="text/javascript">
                                var accord_{/literal}{$lists[list].ID}{literal} = new accordion('block-{/literal}{$lists[list].ID}{literal}');
                            </script>


                        </table>
                        {/literal}

                        {*Tasks donetasks*}
                        <div id="doneblock_{$lists[list].ID}" class="doneblock" style="display: none;">
                            <table class="second-thead" cellpadding="0" cellspacing="0" border="0"
                                   onclick="blindtoggle('doneblock_{$lists[list].ID}');toggleClass('donebutn_{$lists[list].ID}','butn_link_active','butn_link');toggleClass('toggle-done-{$lists[list].ID}','acc-toggle','acc-toggle-active');">

                                <tr>
                                    <td class="a"></td>
                                    <td class="b"><span id="toggle-done-{$lists[list].ID}" class="acc-toggle">{#donetasks#}</span></td>
                                    <td class="c"></td>
                                    <td class="days"></td>
                                    <td class="tools"></td>
                                </tr>

                            </table>

                            <div class="toggleblock">
                                <table cellpadding="0" cellspacing="0" border="0" id="done_{$lists[list].ID}">
                                    {section name=oldtask loop=$lists[list].oldtasks}

                                    {if $smarty.section.oldtask.index % 2 == 0}
                                        <tbody class="color-a" id="task_{$lists[list].oldtasks[oldtask].ID}">
                                        {else}
                                        <tbody class="color-b" id="task_{$lists[list].oldtasks[oldtask].ID}">
                                        {/if}


                                        <tr>
                                            <td class="a">{if $userpermissions.tasks.close}<a class="butn_checked"
                                                                                              href="javascript:closeElement('task_{$lists[list].oldtasks[oldtask].ID}','managetask.php?action=open&amp;tid={$lists[list].oldtasks[oldtask].ID}&amp;id={$project.ID}');"
                                                                                              title="{#open#}"></a>{/if}</td>
                                            <td class="b">
                                                <div class="toggle-in">
                                                    <span class="acc-toggle"
                                                          onclick="javascript:accord_done_{$lists[list].ID}.activate($$('#done_{$lists[list].ID} .accordion_toggle')[{$smarty.section.oldtask.index}]);toggleAccordeon('done_{$lists[list].ID}',this);"></span>
                                                    <a href="managetask.php?action=showtask&amp;tid={$lists[list].oldtasks[oldtask].ID}&amp;id={$lists[list].oldtasks[oldtask].project}"
                                                       title="{$lists[list].oldtasks[oldtask].title}">
                                                        {if $lists[list].oldtasks[oldtask].title != ""}
                                                            {$lists[list].oldtasks[oldtask].title|truncate:30:"...":true}
                                                        {else}
                                                            {$lists[list].oldtasks[oldtask].text|truncate:30:"...":true}
                                                        {/if}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="c"><a
                                                        href="manageuser.php?action=profile&amp;id={$lists[list].oldtasks[oldtask].user_id}">{$lists[list].oldtasks[oldtask].user|truncate:23:"...":true}</a>
                                            </td>
                                            <td class="days" style="text-align:right">{$lists[list].oldtasks[oldtask].daysleft}&nbsp;&nbsp;</td>
                                            <td class="tools">
                                                {if $userpermissions.tasks.edit}
                                                    <a class="tool_edit" href="javascript:void(0);"
                                                       onclick="change('managetask.php?action=editform&amp;tid={$lists[list].oldtasks[oldtask].ID}&amp;id={$project.ID}','form_{$lists[list].ID}');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_{$lists[list].ID}');"
                                                       title="{#edit#}"></a>
                                                {/if}
                                                {if $userpermissions.tasks.del}
                                                    <a class="tool_del"
                                                       href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$lists[list].oldtasks[oldtask].ID}\',\'managetask.php?action=del&amp;tid={$lists[list].oldtasks[oldtask].ID}&amp;id={$project.ID}\')');"
                                                       title="{#delete#}"></a>
                                                {/if}
                                            </td>
                                        </tr>

                                        <tr class="acc">
                                            <td colspan="5">
                                                <div class="accordion_toggle"></div>
                                                <div class="accordion_content">
                                                    <div class="acc-in">
                                                        <div class="message-in">
                                                            {$lists[list].oldtasks[oldtask].text|nl2br}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    {literal}
                                        <script type="text/javascript">
                                            var accord_done_{/literal}{$lists[list].ID}{literal} = new accordion('done_{/literal}{$lists[list].ID}{literal}');
                                        </script>
                                    {/literal}
                                    {/section} {*Tasks donetasks END*}

                                </table>
                            </div> {*toggleblock End*}
                        </div> {*done_block End*}
                    </div> {*smooth End*}

                    <div class="tablemenue">
                        <div class="tablemenue-in">
                            {if $userpermissions.tasks.add}
                            {literal}
                                <a class="butn_link" href="javascript:blindtoggle('form_{{list.ID}}');" id="add_butn_{{list.ID}}" onclick="toggleClass('add_{{list.ID}}','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_{{list.ID}}','smooth','nosmooth');">{/literal}{#addtask#}</a>
                            {/if}
                            {literal}
                            <a class="butn_link" href="javascript:blindtoggle('doneblock_{{list.ID}}');" id="donebutn_{{list.ID}}"
                               onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done-{{list.ID}}','acc-toggle','acc-toggle-active');">{/literal}{#donetasks#}</a>
                        </div>
                    </div>
                </div> {*block END*}

                <div class="content-spacer"></div>
                {*/section*} {*Tasks End*}
            </div>
            {if !$lists[0][0] and !$oldlists[0][0]}
                <tbody class="color-a">
                <tr>
                    <td></td>
                    <td colspan="3" class="info">{#notasklists#}</td>
                    <td class="tools"></td>
                </tr>
                </tbody>
            {/if}

        </div> {*Tasks END*}
    </div> {*content-left-in END*}
</div> {*content-left END*}
{* current tasklists end*}
{*right sidebar*}
{include file="sidebar-a.tpl"}

{if $oldlists[0][0]} {*only show the block if there are closed tasklists*} {*Done Tasklists*}
    <div class="content-spacer"></div>
    {*closed tasklists*}
    <div id="content-left">
        <div id="content-left-in">
            <div class="tasks">
                <h1>{$projectname|truncate:45:"...":true}<span>/ {#donetasklists#}</span></h1>

                <div class="headline">
                    <a href="javascript:void(0);" id="block-donelists_toggle" class="win_block" onclick="toggleBlock('block-donelists');"></a>

                    <h2>
                        <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist-done.png" alt=""/>
                    </h2>
                </div>


                {* Closed tasklists *}
                <div id="block-donelists" class="block">
                    <div class="dones">
                        <table id="acc_donelists" cellpadding="0" cellspacing="0" border="0">

                            <thead>
                            <tr>
                                <th class="a"></th>
                                <th class="b">{#tasklist#}</th>
                                <th class="c"></th>
                                <th class="days"></th>
                                <th class="tools"></th>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <td colspan="5"></td>
                            </tr>
                            </tfoot>

                            {section name = oldlist loop=$oldlists}
                                {*Color-Mix*}
                                {if $smarty.section.oldlist.index % 2 == 0}
                                    <tbody class="color-a" id="task_{$oldlists[oldlist].ID}">
                                    {else}
                                    <tbody class="color-b" id="task_{$oldlists[oldlist].ID}">
                                {/if}
                                <tr {if $oldlists[oldlist].daysleft < 0} class="marker-late"{elseif $oldlists[oldlist].daysleft == 0} class="marker-today"{/if}>
                                    <td>{if $userpermissions.tasks.close}<a class="butn_check"
                                                                            href="managetasklist.php?action=open&amp;tlid={$oldlists[oldlist].ID}&amp;id={$project.ID}"
                                                                            title="{#open#}"></a>{/if}</td>
                                    <td>
                                        <div class="toggle-in">
                                            <span class="acc-toggle"
                                                  onclick="javascript:accord_donelists.activate($$('#block-donelists .accordion_toggle')[{$smarty.section.oldlist.index}]);toggleAccordeon('acc_{$lists[list].ID}',this);"></span>
                                            <a href="managetasklist.php?action=showtasklist&amp;id={$project.ID}&amp;tlid={$oldlists[oldlist].ID}">
                                                {$oldlists[oldlist].name|truncate:30:"...":true}
                                            </a>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td>{$oldlists[oldlist].daysleft}</td>
                                    <td class="tools">
                                        {if $userpermissions.tasks.del}
                                            <a class="tool_del"
                                               href="managetasklist.php?action=del&amp;tlid={$oldlists[oldlist].ID}&amp;id={$project.ID}"
                                               title="{#delete#}"></a>
                                        {/if}
                                    </td>
                                </tr>
                                <tr class="acc">
                                    <td colspan="5">
                                        <div class="accordion_toggle"></div>
                                        <div class="accordion_content">
                                            <div class="acc-in">
                                                <div class="message-in">
                                                    {$oldlists[oldlist].desc|nl2br}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            {/section}
                        </table>

                        <div class="tablemenue"></div>
                    </div> {*dones End*}
                </div> {*block End*}

                <div class="content-spacer"></div>

                {literal}
                    <script type="text/javascript" src="include/js/views/projectTasks.min.js"></script>
                    <script type="text/javascript">
                        var accord_donelists = new accordion('block-donelists');
                    </script>
                {/literal}

            </div> {*Tasks END*}
        </div> {*content-left-in END*}
    </div>
    {*content-left END*}

{/if} {*Done Tasklists End*}
{literal}
    <script type="text/javascript" src="include/js/views/projectTasks.min.js"></script>
<script type="text/javascript">
    projectTasks.url = projectTasks.url + "&id=" +
    {/literal}{$project.ID}{literal}
    var projectTasksView = createView(projectTasks);
</script>
{/literal}


{include file="footer.tpl"}