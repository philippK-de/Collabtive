{include file="header.tpl" jsload="ajax"  jsload1="tinymce"}
{include file="tabsmenue-project.tpl" milestab = "active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="miles">
            <div class="infowin_left display-none"
                 id="milestoneSystemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png"
                 data-text-added="{#milestonewasadded#}"
                 data-text-edited="{#milestonewasedited#}"
                 data-text-deleted="{#milestonewasdeleted#}"
                 data-text-closed="{#milestonewasclosed#}"
                 data-text-opened="{#milestonewasopened#}"
                 >
            </div>
            <h1>{$projectname|truncate:45:"...":true}<span>/ {#milestones#}</span></h1>

            <div id="projectMilestones">
                {include file="projectLateMilestones.tpl"}
                {include file="projectCurrentMilestones.tpl"}
                {include file = "projectUpcomingMilestones.tpl"}
            </div>

        </div> <!--Miles END-->
    </div> <!--content-left-in END-->
</div> <!--content-left END-->

{literal}
    <script type="text/javascript" src="include/js/accordion.js"></script>
    <script type="text/javascript" src="include/js/views/projectMilestones.min.js"></script>
<script type="text/javascript">
    lateProjectMilestones.url = lateProjectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};
    var lateProjectMilestonesView = createView(lateProjectMilestones);

    upcomingProjectMilestones.url = upcomingProjectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};
    var upcomingProjectMilestonesView = createView(upcomingProjectMilestones);


    projectMilestones.url = projectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};
    var projectMilestonesView = createView(projectMilestones);
    //make late and upcoming milestones update with current milestones
    Vue.set(projectMilestonesView, "dependencies", [lateProjectMilestonesView, upcomingProjectMilestonesView]);


    var accord_miles_late;
    var accord_miles_new;
    var accord_miles_upcoming;
    projectMilestonesView.afterLoad(function () {

        // /loop through the blocks and add the accordion toggle link
        var theBlocks = document.querySelectorAll("#projectMilestones > div[class~='headline'] > a");

        //loop through the blocks and add the accordion toggle link
        for (i = 0; i < theBlocks.length; i++) {
            var theAction = theBlocks[i].getAttribute("onclick");
            theAction += "activateAccordeon(" + i + ");";
            theBlocks[i].setAttribute("onclick", theAction);
            //console.log(theBlocks[i].getAttribute("onclick"));
        }
        activateAccordeon(1);

        addMilestoneForm = document.getElementById("addmilestoneform");
        formView = projectMilestonesView;
        formView.doUpdate = true;
        addMilestoneForm.addEventListener("submit", submitForm.bind(formView));
    });

    projectMilestonesView.afterUpdate(function () {
        accord_miles_late = new accordion2('lateMilestones');
        accord_miles_new = new accordion2('currentMilestones');
        accord_miles_upcoming = new accordion2('upcomingMilestones');
    });

</script>
{/literal}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}