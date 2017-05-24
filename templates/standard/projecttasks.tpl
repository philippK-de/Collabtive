{include file="header.tpl" jsload = "ajax"  jsload1 = "tinymce" }
{include file="tabsmenue-project.tpl" taskstab = "active"}
<div id="content-left">
    <div id="content-left-in">
        <div class="tasks">
            <div class="infowin_left display-none"
                 id="taskSystemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png"
                 data-text-deleted="{#taskwasdeleted#}"
                 data-text-edited="{#taskwasedited#}"
                 data-text-added="{#taskwasadded#}"
                 data-text-closed="{#taskwasclosed#}"
                 data-text-opened="{#taskwasopened#}"
                 data-text-assigned="{#taskwasassigned#}"
                 data-text-deassigned="{#taskwasdeassigned#}"
                 >
            </div>

            <h1>{$projectname|truncate:45:"...":true}<span>/ {#tasklists#}</span></h1>

            {*Add Tasklist*}
            {if $userpermissions.tasks.add}
                <div class="add-main" style="left:-60px;">
                  <form class="main" action="javascript:void(0);">
                        <fieldset>
                            <div class="row">
                                <button id="addtasklist" onclick = "blindtoggle('addlist');">{#addtasklist#}</button>
                            </div>
                        </fieldset>
                    </form>
                </div>

                <div id="addlist" class="addmenue display-none">
                    {include file="forms/addtasklist.tpl" }
                </div>
            {/if}
            {*Add Tasklist END*}

            <div id="blockTasks">
                {*Tasks*}
                {if $lists[0][0]}
                {section name=list loop=$lists}
                    <div class="headline accordion_toggle" style="margin-top:5px;">
                        <a href="javascript:void(0);" id="toggle-{$lists[list].ID}" class="win_block" onclick=""></a>

                        <div class="wintools" style="z-index:999;">
                            <div class="progress display-none float-left" id="progressblockTasks_content{$smarty.section.list.index}" style="width:22px;">
                                <img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/loader-tasks.gif"/>
                            </div>
                            {if $userpermissions.tasks.close}
                                <a class="close"
                                   href="managetasklist.php?action=close&amp;tlid={$lists[list].ID}&amp;id={$project.ID}"><span>{#close#}</span></a>
                            {/if}
                            {*if $userpermissions.tasks.edit}
                                <a class="edit"
                                   href="managetasklist.php?action=editform&amp;tlid={$lists[list].ID}&amp;id={$project.ID}"><span>{#edit#}</span></a>
                            {/if*}
                            {if $userpermissions.tasks.del}
                                <a class="del"
                                   href="javascript:confirmit('{#confirmdel#}','managetasklist.php?action=del&amp;tlid={$lists[list].ID}&amp;id={$project.ID}');"><span>{#delete#}</span></a>
                            {/if}
                        </div>

                        <h2>
                            <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt=""/>{$lists[list].name|truncate:70:"...":true}
                        </h2>
                    </div>
                    <div id="blockTasks_content{$smarty.section.list.index}"
                         data-tasklist="{$lists[list].ID}"
                         data-project="{$project.ID}"
                         class="block blockaccordion_content overflow-hidden display-none">

                        {*Add Task*}
                        {if $userpermissions.tasks.add}
                            <div id="form_{$lists[list].ID}" class="addmenue display-none">
                                {include file="forms/addtask.tpl" }
                            </div>
                        {/if}
                        <div class="nosmooth" id="sm_{$lists[list].ID}">
                            <table id="taskList_{$lists[list].ID}" class="taskList" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                <tr>
                                    <th class="a"></th>
                                    <th class="b">{#tasks#}</th>
                                    <th class="c">{#user#}</th>
                                    <th class="days" class="text-align-right">{#daysleft#}&nbsp;&nbsp;</th>
                                    <th class="tools"></th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <td colspan="5"></td>
                                </tr>
                                </tfoot>

                                {*Color-Mix*}
                                {literal}
                                <tbody v-for="task in items.open" class="alternateColors" id="task_{{*task.ID}}" v-cloak>

                                <tr v-bind:class="{ 'marker-late': task.islate, 'marker-today': task.istoday }">
                                    <td>
                                        {/literal}
                                        {if $userpermissions.tasks.close}
                                        {literal}
                                            <a class="butn_check closeElement" href="javascript:void(0);"
                                            data-task="{{*task.ID}}"
                                            data-viewindex="{/literal}{$smarty.section.list.index}"
                                            data-project="{$project.ID}{literal}"
                                            title="{/literal}{#close#}"></a>
                                        {/if}
                                    </td>
                                    {literal}
                                    <td>
                                        <div class="toggle-in">
                                            <span class="acc-toggle"
                                                  onclick="accordeons[{/literal}{$smarty.section.list.index}{literal}].activate(css('#taskList_{/literal}{$lists[list].ID}{literal}_content{{$index}}'));"></span>
                                            <a href="managetask.php?action=showtask&amp;tid={{*task.ID}}&amp;id={{*task.project}}"
                                               title="{{*task.title}}">
                                                {{{*task.title | truncate '30' }}}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span v-for="user in task.users">
                                            <a href="manageuser.php?action=profile&amp;id={{*user.ID}}">{{*user.name}}</a>&nbsp;
                                        </span>
                                    </td>
                                    <td class="text-align-right">
                                        {/literal}
                                        <!--autoTimetrackerToggle-->
                                        {literal}
                                        {{*task.daysleft}}&nbsp;&nbsp;</td>
                                    <td class="tools">
                                        {/literal}
                                        {if $userpermissions.tasks.edit}
                                        {literal}
                                            <a class="tool_edit" href="javascript:void(0);" onclick="change('managetask.php?action=editform&amp;tid={{*task.ID}}&amp;id={{*task.project}}','form_{{*task.liste}}');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_{{*task.liste}}');" title="{/literal}{#edit#}"></a>
                                        {/if}
                                        {if $userpermissions.tasks.del}
                                        {literal}
                                            <a class="tool_del deleteElement"
                                            data-task="{{*task.ID}}"
                                            data-viewindex="{/literal}{$smarty.section.list.index}"
                                            data-confirmtext="{#confirmdel#}"
                                            data-project="{$project.ID}{literal}"
                                            href="javascript:void(0);"  title="{/literal}{#delete#}"></a>
                                        {/if}
                                    </td>
                                </tr>

                                {literal}
                                <tr class="acc">
                                    <td colspan="5">
                                        <div class="accordion_content"
                                             id="taskList_{/literal}{$lists[list].ID}{literal}_content{{$index}}"
                                             data-slide="{{$index}}">
                                            <div class="acc-in">
                                                <div class="message-in-fluid">
                                                    {{{*task.text}}}
                                                    {/literal}
                                                    <!--taskComments-->
                                                    {literal}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                                {/literal}

                            </table>

                            {*Tasks donetasks*}
                            <div id="doneblock_{$lists[list].ID}" class="doneblock display-none" >
                                <table id="acc_donetasks_{$lists[list].ID}" class="second-thead" cellpadding="0" cellspacing="0" border="0"
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
                                        {literal}
                                        <tbody v-for="oldtask in items.closed" class="alternateColors" id="task_{{*oldtask.ID}}">

                                        <tr>
                                            <td class="a">
                                                {/literal}
                                                {if $userpermissions.tasks.close}
                                                {literal}
                                                    <a class="butn_checked openElement"
                                                    data-task="{{*oldtask.ID}}"
                                                    data-viewindex="{/literal}{$smarty.section.list.index}"
                                                    data-project="{$project.ID}{literal}"
                                                    title="{/literal}{#open#}"></a>
                                                {/if}
                                                {literal}
                                            </td>
                                            <td class="b">
                                                <div class="toggle-in">
                                                    <a href="managetask.php?action=showtask&amp;tid={{*oldtask.ID}}&amp;id={{*oldtask.project}}"
                                                       title="{{*oldtask.title}}">
                                                        {{{*oldtask.title | truncate '30' }}}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="c">
                                                <a href="manageuser.php?action=profile&amp;id={{*oldtask.user_id}}">{{*oldtask.user}}</a>
                                            </td>
                                            <td class="days text-align-right"></td>
                                            <td class="tools">
                                                {/literal}
                                                {if $userpermissions.tasks.del}
                                                {literal}
                                                    <a class="tool_del deleteElement"
                                                    data-task="{{*oldtask.ID}}"
                                                    data-viewindex="{/literal}{$smarty.section.list.index}"
                                                    data-confirmtext="{#confirmdel#}"
                                                    data-project="{$project.ID}{literal}"
                                                    href="javascript:void(0);" title="{/literal}{#delete#}"></a>
                                                {/if}
                                            </td>
                                        </tr>
                                        </tbody>
                                        {*Tasks donetasks END*}
                                    </table>
                                </div>
                                {*toggleblock End*}
                            </div>
                            {*done_block End*}
                        </div>
                        {*smooth End*}

                        <div class="tablemenue">
                            <div class="tablemenue-in">
                                {if $userpermissions.tasks.add}
                                    <a class="butn_link" href="javascript:blindtoggle('form_{$lists[list].ID}');" id="add_butn_{$lists[list].ID}"
                                       onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_{$lists[list].ID}','smooth','nosmooth');">{#addtask#}</a>
                                {/if}
                                <a class="butn_link" href="javascript:blindtoggle('doneblock_{$lists[list].ID}');" id="donebutn_{$lists[list].ID}"
                                   onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done-{$lists[list].ID}','acc-toggle','acc-toggle-active');">{#donetasks#}</a>
                            </div>
                        </div>
                    </div>
                    {*block END*}
                {/section}
                {/if}{*if $lists[0][0]*}
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

            <!-- content left end -->
            <div class="content-spacer"></div>
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
                            {section name=oldlist loop=$oldlists}
                                <tbody class="alternateColors" id="task_{$oldlists[oldlist].ID}">
                                    <tr>
                                        <td>
                                            {if $userpermissions.tasks.close}
                                                <a class="butn_checked"
                                                   href="managetasklist.php?action=open&amp;tlid={$oldlists[oldlist].ID}&amp;id={$project.ID}"
                                                   title="{#open#}">
                                                </a>
                                            {/if}
                                        </td>
                                        <td colspan="3">
                                            <a href="managetasklist.php?action=showtasklist&amp;id={$project.ID}&amp;tlid={$oldlists[oldlist].ID}">
                                                {$oldlists[oldlist].name|truncate:100:"...":true}
                                            </a>
                                        </td>
                                        <td class="tools">
                                            {if $userpermissions.tasks.del}
                                                <a class="tool_del"
                                                   href="javascript:confirmit('{#confirmdel#}','managetasklist.php?action=del&amp;tlid={$oldlists[oldlist].ID}&amp;id={$project.ID}');" title="{#delete#}"</a>
                                            {/if}
                                        </td>
                                    </tr>
                                </tbody>
                            {/section}
                        </table>

                        <div class="tablemenue"></div>
                    </div> {*dones End*}
                </div> {*block End*}

                <div class="content-spacer"></div>
            </div> {*Tasks END*}
        </div> {*content-left-in END*}
    </div>
    {*content-left END*}
 </div>
{/if} {*Done Tasklists End*}

<script type="text/javascript" src="include/js/accordion.js"></script>
<script type="text/javascript" src="include/js/views/projectTasks.min.js"></script>

{include file="footer.tpl"}