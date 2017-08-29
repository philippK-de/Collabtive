{include file="header.tpl" stage = "project" treeView="treeView" jsload="ajax"  jsload1="tinymce"}
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

        </div>
        <!--Miles END-->
    </div>
    <!--content-left-in END-->
</div> <!--content-left END-->

{literal}
    <script type="text/javascript" src="include/js/accordion.js"></script>
    <script type="text/javascript" src="include/js/views/projectMilestones.js"></script>
<script type="text/javascript">
    /* Create views */

    /* Current Milestones */
    projectMilestones.url = projectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};
    var projectMilestonesView = createView(projectMilestones);
    //make late and upcoming milestones update with current milestones
    Vue.set(projectMilestonesView, "dependencies", [lateProjectMilestonesView, upcomingProjectMilestonesView]);
    projectMilestonesView.afterLoad(function () {
        //loop through the blocks and add the accordion toggle link
        var theBlocks = document.querySelectorAll("#projectMilestones > div[class~='headline'] > a");
        //loop through the blocks and add the accordion toggle link
        for (i = 0; i < theBlocks.length; i++) {
            var theAction = theBlocks[i].getAttribute("onclick");
            theAction += "activateAccordeon(" + i + ");";
            theBlocks[i].setAttribute("onclick", theAction);
            //console.log(theBlocks[i].getAttribute("onclick"));
        }
        //open first slide
        activateAccordeon(1);

        //render tasklist tree
        renderTasklistTree(projectMilestonesView);

        /* bind submit form handler to add milestone form */
        var addMilestoneForm = document.getElementById("addmilestoneform");

        formView = projectMilestonesView;
        formView.doUpdate = true;
        addMilestoneForm.addEventListener("submit", submitForm.bind(formView));
    });


    var accord_miles_new;
    projectMilestonesView.afterUpdate(function () {
        //render tasklist tree
        renderTasklistTree(projectMilestonesView);
        //create inner accordeons
        accord_miles_new = new accordion2('currentMilestones');
    });

    /* Late Milestone */
    lateProjectMilestones.url = lateProjectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};

    var accord_miles_late;
    var lateProjectMilestonesView = createView(lateProjectMilestones);
    lateProjectMilestonesView.afterUpdate(function(){
        //render tasklist tree
        renderTasklistTree(lateProjectMilestonesView);
        //create inner accordeons
        accord_miles_late = new accordion2('lateMilestones');
    });

    /* Upcoming milestones */
    upcomingProjectMilestones.url = upcomingProjectMilestones.url + "&id=" + {/literal}{$project.ID}{literal};

    var accord_miles_upcoming;
    var upcomingProjectMilestonesView = createView(upcomingProjectMilestones);
    upcomingProjectMilestonesView.afterUpdate(function(){
        //render tasklist tree
        renderTasklistTree(upcomingProjectMilestonesView);
        //create inner accordeons
        accord_miles_upcoming = new accordion2('upcomingMilestones');
    });


</script>
{/literal}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}