{include file="header.tpl" jsload="ajax"  jsload1="tinymce"}
{include file="tabsmenue-project.tpl" milestab = "active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="miles" >
            <div class="infowin_left"
                 id="milestoneSystemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png"
                 data-text-added="{#milestonewasadded#}"
                 data-text-edited="{#milestonewasedited#}"
                 data-text-deleted="{#milestonewasdeleted#}"
                 data-text-closed="{#milestonewasclosed#}"
                 data-text-opened="{#milestonewasopened#}"
                 style="display:none">
            </div>
            <h1>{$projectname|truncate:45:"...":true}<span>/ {#milestones#}</span></h1>

            <div id="projectMilestones">
            {include file="projectLateMilestones.tpl"}
            {include file="projectCurrentMilestones.tpl"}
            {include file = "projectUpcomingMilestones.tpl"}
            </div>

        <!--block End-->
    </div>
    <!--Miles END-->
    <div class="content-spacer"></div>
</div>
<!--content-left-in END-->
</div> <!--content-left END-->

{literal}
    <script type="text/javascript" src="include/js/accordion.js"></script>
    <script type="text/javascript" src="include/js/views/projectMilestones.min.js"></script>
<script type="text/javascript">
    lateProjectMilestones.url = lateProjectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};
    var lateProjectMilestonesView = createView(lateProjectMilestones);

    projectMilestones.url = projectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};
    var projectMilestonesView = createView(projectMilestones);

    upcomingProjectMilestones.url = upcomingProjectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};
    var upcomingProjectMilestonesView = createView(upcomingProjectMilestones);

    var accord_miles_late = new accordion2('lateMilestones');
    var accord_miles_new = new accordion2('currentMilestones');
    var accord_miles_upcoming = new accordion2('upcomingMilestones');
</script>
{/literal}
{include file="sidebar-a.tpl"}
{include file="footer.tpl"}