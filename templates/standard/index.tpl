{include file="header.tpl" jsload="ajax" jsload1="tinymce" jsload3="lightbox" stage="index"}
{include file="tabsmenue-desk.tpl" desktab="active"}

<div id="content-left">
    <div id="content-left-in">

        <div class="infowin_left"
             id="projectSystemMessage"
             data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png"
             data-text-deleted = "{#projectwasdeleted#}"
             data-text-edited = "{#projectwasedited#}"
             data-text-added = "{#projectwasadded#}"
             data-text-closed = "{#projectwasclosed#}"
             style="display:none">
        </div>
        <div class="infowin_left"
             id="taskSystemMessage"
             data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png"
             data-text-deleted = "{#taskwasdeleted#}"
             data-text-edited = "{#taskwasedited#}"
             data-text-added = "{#taskwasadded#}"
             data-text-closed = "{#taskwasclosed#}"
             style="display:none">
        </div>
        <div class="infowin_left"
             id="messageSystemMessage"
             data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png"
             data-text-deleted = "{#messagewasdeleted#}"
             data-text-edited = "{#messagewasedited#}"
             data-text-added = "{#messagewasadded#}"
             style="display:none">
        </div>
        {if $isUpdated|default}
            {include file="updateNotify.tpl"}
            <br/>
        {/if}

        <h1>{#desktop#}</h1>

        <div id="block_index" class="block">
            {* Projects *}
            {include file="desktopProjects.tpl"}
            {* Tasks *}
            {include file="desktopTasks.tpl"}

            {* Milestones *}
            {if $tasknum}
                <div class="miles" style="padding-bottom:2px;">
                    <div class="headline">
                        <a href="javascript:void(0);" id="mileshead_toggle" class="win_none"
                           onclick=""></a>

                        <div class="wintools">
                            <div class="progress" id="progress" style="display:none;">
                                <img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/loader-cal.gif"/>
                            </div>
                        </div>

                        <h2>
                            <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png" alt=""/>{#calendar#}
                        </h2>
                    </div>

                    <div class="acc_toggle"></div>
                    <div class="block acc_content" id="mileshead" style="overflow:hidden;">
                        <div id="thecal" class="bigcal" style="height:270px;"></div>
                        <div class="content-spacer"></div>
                    </div> {* block END *}
                </div>
                {* miles END *}
                {* milestons END *}
            {/if}

          {include file="desktopMessages.tpl"}

        </div> {* block index end*}
        <div class="content-spacer"></div>
    </div> {* content-left-in END *}
</div> {* content-left END *}
<link rel="stylesheet" type = "text/css" href="templates/standard/theme/standard/css/blindTransition.css" />
<script type="text/javascript" src="include/js/accordion.js"></script>
<script type="text/javascript" src="include/js/views/index.min.js"></script>
{include file="sidebar-a.tpl"}
{include file="footer.tpl"}
