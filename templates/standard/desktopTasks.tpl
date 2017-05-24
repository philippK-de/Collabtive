{if $tasknum > 0}
    <div class="tasks padding-bottom-two-px" id="desktoptasks">
        <div class="headline">
            <a href="javascript:void(0);" id="taskhead_toggle" class="win_none" onclick=""></a>

            <div class="wintools">
                <loader block="desktoptasks" loader="loader-tasks.gif"></loader>
            </div>
            <h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt="" />{#mytasks#}</h2>
        </div>
        <div class="block blockaccordion_content overflow-hidden display-none" id="taskhead">
            <div id="form_addmytask" class="addmenue display-none">
                {include file="forms/addmytask_index.tpl" }
            </div>
            <div class="nosmooth" id="sm_desktoptasks">
                <table  cellpadding="0" cellspacing="0" border="0" v-cloak>
                    <thead>
                    <tr>
                        <th class="a"></th>
                        <th class="b" class="cursor-pointer">{#task#}</th>
                        <th class="c" class="cursor-pointer">{#project#}</th>
                        <th class="d" class="cursor-pointer">{#daysleft#}&nbsp;&nbsp;</th>
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
                    <tbody v-for="item in items" class="alternateColors" v-bind:id="'task_' + item.ID">
                        <tr v-bind:class="{ 'marker-late': item.islate, 'marker-today': item.istoday }" >
                            <td>
                                {/literal}{if $userpermissions.tasks.close}{literal}
                                    <a class="butn_check"
                                    v-bind:href="'javascript:closeElement(\'task_'+item.ID+'\',\'managetask.php?action=close&amp;tid='+item.ID+'&amp;id='+item.project+'\', tasksView);'"
                                    title="{/literal}{#close#}"></a>
                                {/if}{literal}

                            </td>
                            <td>
                                <div class="toggle-in">
                                    <span v-bind:id="'desktoptasks_toggle' + item.ID"
                                          class="acc-toggle"
                                          :onclick="'accord_tasks.toggle(document.querySelector(\'#desktoptasks_content'+$index+'\'));'"></span>
                                    <a v-bind:href="'managetask.php?action=showtask&amp;id=' + item.project + '&amp;tid=' + item.ID"
                                       v-bind:title=item.title>
                                        {{{item.title | truncate '30' }}}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <a v-bind:href="'managetask.php?action=showproject&amp;id=' +item.project">{{item.pname | truncate '30' }}</a>
                            </td>
                            <td class="text-align-right">{{item.daysleft}}&nbsp;&nbsp;</td>
                            <td class="tools">

                                {/literal}{if $userpermissions.tasks.edit} {literal}
                                    <a class="tool_edit" href="javascript:void(0);"
                                       v-bind:href="'javascript:change(\'managetask.php?action=editform&amp;tid='+item.ID+'&amp;id='+item.project+'\',\'form_addmytask\');blindtoggle(\'form_addmytask\');'"
                                       title="Edit"></a>
                                {/literal}{/if}{literal}

                                {/literal}{if $userpermissions.tasks.del}{literal}
                                    <a class="tool_del"
                                    v-bind:href="'javascript:confirmDelete(\'{/literal}{#confirmdel#}{literal}\',\'task_'+item.ID+'\',\'managetask.php?action=del&amp;tid='+item.ID+'&amp;id='+item.project+'\',tasksView);'"  title="{/literal}{#delete#}"></a>

                                {/if}{literal}

                            </td>
                        </tr>

                        <tr class="acc">
                            <td colspan="5">
                                <div class="accordion_content" >
                                    <div class="acc-in">
                                        <div class="message-in-fluid" >
                                            {{{item.text}}}
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>

                </table>
                {/literal}

                <div class="tablemenue">
                    <div class="tablemenue-in">
                        {if $userpermissions.tasks.add}

                            <a class="butn_link" href="javascript:void(0);"
                               id="add_butn_mytasks"
                               onclick="blindtoggle('form_addmytask');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_desktoptasks','smooth','nosmooth');">{#addtask#}</a>
                        {/if}
                    </div>
                </div>
                <div class="content-spacer"></div>
            </div> {*block END*}
        </div> {* Smooth end *}
    </div> {*tasks END*}
    <script type="text/javascript">
        theCalStart = new calendar({$theM},{$theY});
        theCalStart.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
        theCalStart.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
        theCalStart.relateTo = "start";
        theCalStart.dateFormat = "{$settings.dateformat}";
        theCalStart.getDatepicker("datepicker_start_task");
        theCal = new calendar({$theM},{$theY});
        theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
        theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
        theCal.relateTo = "end{$myprojects[project].ID}";
        theCal.dateFormat = "{$settings.dateformat}";
        theCal.getDatepicker("datepicker_task");
    </script>
{/if} {* Tasks END *}
