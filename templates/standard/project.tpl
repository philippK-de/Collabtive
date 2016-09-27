{include file="header.tpl" jsload="ajax" stage="project" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" projecttab="active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="projects">

            <div class="infowin_left display-none"
                 id="systemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png"
                 data-text-deleted="{#projectwasdeleted#}"
                 data-text-edited="{#projectwasedited#}"
                 data-text-added="{#projectwasadded#}"
                 data-text-closed="{#projectwasclosed#}"
                    >
            </div>

            <h1>{$project.name|truncate:45:"...":true}<span>/ {#overview#}</span></h1>

            <div class="statuswrapper">
                <ul>
                    {if $userpermissions.projects.close}
                        {if $project.status == 1}
                            <li class="link" id="closetoggle">
                                <a class="close"
                                   href="javascript:closeElement('closetoggle','manageproject.php?action=close&amp;id={$project.ID}');"
                                   title="{#close#}"></a>
                            </li>
                        {else}
                            <li class="link" id="closetoggle">
                                <a class="closed"
                                   href="manageproject.php?action=open&amp;id={$project.ID}"
                                   title="{#open#}"></a>
                            </li>
                        {/if}
                    {/if}
                    {if $userpermissions.projects.edit}
                        <li class="link">
                            <a class="edit" href="javascript:void(0);" id="edit_butn"
                               onclick="blindtoggle('form_edit');toggleClass(this,'edit-active','edit');toggleClass('sm_project','smooth','nosmooth');toggleClass('sm_project_desc','smooth','nosmooth');"
                               title="{#edit#}"></a>
                        </li>
                    {/if}
                    {if $project.desc}
                        <li class="link" onclick="blindtoggle('descript');toggleClass('desctoggle','desc_active','desc');">
                            <a class="desc" id="desctoggle"
                               href="#"
                               title="{#open#}">{#description#}</a>
                        </li>
                    {/if}
                    {if $userpermissions.projects.del}{if $project.budget}
                        <li>
                            <a>{#budget#}: {$project.budget}</a>
                        </li>
                    {/if}{/if}
                    {if $project.customer.company != ""}
                        <li class="link" onclick="blindtoggle('customer');toggleClass('custtogle','desc_active','desc');">
                            <a class="desc" id="custtogle">{#customer#}: {$project.customer.company}</a>
                        </li>
                    {/if}
                    {if $project.daysleft != "" || $project.daysleft == "0"}
                        <li {if $project.daysleft < 0}class="red" {elseif $project.daysleft == "0"}class="green"{/if}>
                            <a>{$project.daysleft} {#daysleft#}</a></li>
                    {/if}
                </ul>

                <div class="status">
                    {$done}%
                    <div class="statusbar">
                        <div class="complete" id="completed" style="width:{$done}%;"></div>
                    </div>
                </div>
            </div>

            {*Edit Task*}
            {if $userpermissions.projects.edit}
                <div id="form_edit" class="addmenue display-none clear_both">
                    <div class="content-spacer"></div>
                    {include file="editform.tpl" showhtml="no" }
                </div>
            {/if}

            <div class="nosmooth" id="sm_project_customer">
                <div id="customer" class="descript display-none">
                    <div class="content-spacer"></div>
                    <h2>{$project.customer.company}</h2>
                    <b>{#contactperson#}:</b> {$project.customer.contact}
                    <br/>
                    <b>{#email#}:</b> <a href="mailto:{$project.customer.email}">{$project.customer.email}</a>
                    <br/>
                    <b>{#phone#} / {#cellphone#}:</b> {$project.customer.phone} / {$project.customer.mobile}
                    <br/>
                    <b>{#url#}:</b> <a href="{$project.customer.url}" target="_blank">{$project.customer.url}</a>
                    <br/><br/>
                    <b>{#address#}:</b><br/>
                    {$project.customer.address}
                    <br/>{$project.customer.zip} {$project.customer.city}
                    <br/>{$project.customer.country}<br/>
                </div>
            </div>

            <div class="nosmooth" id="sm_project_desc">
                <div id="descript" class="descript display-none">
                    <div class="content-spacer"></div>
                    {$project.desc}
                </div>
            </div>
        </div> {*Projects END*}

        <div class="content-spacer"></div>
        <div class="nosmooth" id="sm_project">
            <div id="block_dashboard" class="block">

                {*Miles tree*}
                {if $tree[0][0] > 0}
                    <div class="projects dtree padding-bottom-two-px">
                        <div class="headline accordion_toggle">
                            <a href="javascript:void(0);" id="treehead_toggle" class="win_block" onclick=""></a>

                            <h2>
                                <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png"
                                     alt=""/>{#projecttree#}
                            </h2>
                        </div>

                        <div class="block accordion_content overflow-hidden display-none" id="treehead">
                            <div class="block_in_wrapper" style="padding-top:0px;">
                                <script type="text/javascript">
                                    var projectTree = new dTree('projectTree');
                                    projectTree.config.useCookies = true;
                                    projectTree.config.useSelection = false;
                                    projectTree.add(0, -1, '');
                                    // Milestones
                                    {section name=titem loop=$tree}
                                    projectTree.add("m" +{$tree[titem].ID}, 0, "{$tree[titem].name}", "managemilestone.php?action=showmilestone&msid={$tree[titem].ID}&id={$project.ID}", "", "", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png", "", {$tree[titem].daysleft});

                                    // Task lists
                                    {section name=tlist loop=$tree[titem].tasklists}
                                    projectTree.add("tl" +{$tree[titem].tasklists[tlist].ID}, "m" +{$tree[titem].tasklists[tlist].milestone}, "{$tree[titem].tasklists[tlist].name}", "managetasklist.php?action=showtasklist&id={$project.ID}&tlid={$tree[titem].tasklists[tlist].ID}", "", "", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png");

                                    // Tasks from lists
                                    {section name=ttask loop=$tree[titem].tasklists[tlist].tasks}
                                    projectTree.add("ta" +{$tree[titem].tasklists[tlist].tasks[ttask].ID}, "tl" +{$tree[titem].tasklists[tlist].tasks[ttask].liste}, "{$tree[titem].tasklists[tlist].tasks[ttask].title}", "managetask.php?action=showtask&tid={$tree[titem].tasklists[tlist].tasks[ttask].ID}&id={$project.ID}", "", "", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png", "", {$tree[titem].tasklists[tlist].tasks[ttask].daysleft});
                                    {/section}

                                    // End task lists
                                    {/section}
                                    // Messages
                                    {section name=tmsg loop=$tree[titem].messages}
                                    {if $tree[titem].messages[tmsg].milestone > 0}
                                    projectTree.add("msg" +{$tree[titem].messages[tmsg].ID}, "m" +{$tree[titem].messages[tmsg].milestone}, "{$tree[titem].messages[tmsg].title}", "managemessage.php?action=showmessage&id={$project.ID}&mid={$tree[titem].messages[tmsg].ID}", "", "", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png");
                                    {/if}
                                    {/section}
                                    // End Messages
                                    {/section}
                                    // End milestones
                                    document.write(projectTree);
                                </script>

                                <br/>

                                <form id="treecontrol" action="#">
                                    <fieldset>
                                        <div class="row-butn-bottom">
                                            <button type="reset" id="openall" onclick="projectTree.openAll();">{#openall#}</button>
                                            <button type="reset" id="closeall" onclick="projectTree.closeAll();">{#closeall#}</button>
                                        </div>
                                    </fieldset>
                                </form>

                            </div> {*block end*}
                        </div> {*block in wrapper end*}
                    </div>
                {/if}
                {*Tree end*}
                {*Calendar*}
                {include file="calendar.tpl" context="project"}

                {*Timetracker*}
                {if $userpermissions.timetracker.add}
                    <div class="timetrack padding-bottom-two-px">
                        <div class="headline accordion_toggle">
                            <a href="javascript:void(0);" id="trackerhead_toggle" class="win_none" onclick=""></a>

                            <h2>
                                <a href="managetimetracker.php?action=showproject&amp;id={$project.ID}" title="{#timetracker#}"><img
                                            src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/timetracker.png"
                                            alt=""/>{#timetracker#}</a>
                            </h2>
                        </div>

                        <div class="block accordion_content overflow-hidden display-none" id="trackerhead">
                            <div id="trackerform" class="addmenue">
                                {include file="forms/addtimetracker.tpl" }
                            </div>
                            <div class="tablemenue"></div>
                        </div> {*block end*}
                    </div>
                    {*timetrack end*}

                    <!--<div class="content-spacer"></div>-->
                {/if}
                {*Timetracker End*}

                <div class="neutral padding-bottom-two-px">
                    {include file="log.tpl" }
                </div>
            </div> {*nosmooth End*}
            {*block dashboard end*}
        </div>

        {literal}
        <script type="text/javascript" src="include/js/accordion.min.js"></script>
        <script type="text/javascript" src="include/js/modal.min.js"></script>
        <script type="text/javascript" src="include/js/views/project.min.js"></script>
        <script type="text/javascript">
            //changeshow('manageproject.php?action=cal&id={/literal}{$project.ID}{literal}','thecal','progress');
            projectCalendar.url = projectCalendar.url + "&id=" + {/literal}{$project.ID}{literal};

            var calendarView = createView(projectCalendar);
            calendarView.afterLoad(function () {
                var theBlocks = document.querySelectorAll("#block_dashboard > div .headline > a");

                //loop through the blocks and add the accordion toggle link
                var openSlide = 0;
                for (i = 0; i < theBlocks.length; i++) {
                    var theAction = theBlocks[i].getAttribute("onclick");
                    theAction += "activateAccordeon(" + i + ");";
                    theBlocks[i].setAttribute("onclick", theAction);
                }
                activateAccordeon(0);
            });
        </script>
        {/literal}


    </div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl" showcloud="1"}



{include file="footer.tpl"}
