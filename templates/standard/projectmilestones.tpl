{include file="header.tpl" jsload="ajax"  jsload1="tinymce"}
{include file="tabsmenue-project.tpl" milestab = "active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="miles">
            <div class="infowin_left" style="display:none;" id="systemmsg">
                {if $mode == "added"}
                    <span class="info_in_green"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png"
                                                     alt=""/>{#milestonewasadded#}</span>
                {elseif $mode == "edited"}
                    <span class="info_in_yellow"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png"
                                                      alt=""/>{#milestonewasedited#}</span>
                {elseif $mode == "deleted"}
                    <span class="info_in_red"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png"
                                                   alt=""/>{#milestonewasdeleted#}</span>
                {elseif $mode == "opened"}
                    <span class="info_in_green"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png"
                                                     alt=""/>{#milestonewasopened#}</span>
                {elseif $mode == "closed"}
                    <span class="info_in_red"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png"
                                                   alt=""/>{#milestonewasclosed#}</span>
                {/if}

                <span id="deleted" class="info_in_red" style="display:none;"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png"
                            alt=""/>{#milestonewasdeleted#}</span>
                <span class="info_in_green" id="closed" style="display:none;"><img
                            src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png"
                            alt=""/>{#milestonewasclosed#}</span>

            </div>

            {literal}
                <script type="text/javascript">
                    systemMsg('systemmsg');
                </script>
            {/literal}

            <h1>{$projectname|truncate:45:"...":true}<span>/ {#milestones#}</span></h1>

            {include file="projectLateMilestones.tpl"}
            {include file="projectCurrentMilestones.tpl"}
            {include file = "projectUpcomingMilestones.tpl"}



        <!--block End-->

        {literal}
            <script type="text/javascript">
                try {
                    var accord_miles_late = new accordion('accordion_miles_late');
                }
                catch (e) {
                }

                try {
                    var accord_miles_new = new accordion('accordion_miles_new');
                }
                catch (e) {
                }

                try {
                    var accord_miles_done = new accordion('accordion_miles_done');
                }
                catch (e) {
                }
            </script>
        {/literal}

    </div>
    <!--Miles END-->
    <div class="content-spacer"></div>
</div>
<!--content-left-in END-->
</div> <!--content-left END-->

{literal}
    <script type="text/javascript" src="include/js/views/projectMilestones.min.js"></script>
<script type="text/javascript">
    projectMilestones.url = projectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};
    var projectMilestonesView = createView(projectMilestones);
    lateProjectMilestones.url = lateProjectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};
    var lateProjectMilestonesView = createView(lateProjectMilestones);
    upcomingProjectMilestones.url = upcomingProjectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};
    var upcomingProjectMilestonesView = createView(upcomingProjectMilestones);
</script>
{/literal}
{include file="sidebar-a.tpl"}
{include file="footer.tpl"}