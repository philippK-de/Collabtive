{include file="header.tpl" jsload="ajax" jsload1="tinymce" jsload3="lightbox" stage="index"}
{include file="tabsmenue-desk.tpl" desktab="active"}

<div id="content-left">
    <div id="content-left-in">
        <!-- project text -->
        <div class="infowin_left display-none"
             id="projectSystemMessage"
             data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png"
             data-text-deleted="{#projectwasdeleted#}"
             data-text-edited="{#projectwasedited#}"
             data-text-added="{#projectwasadded#}"
             data-text-closed="{#projectwasclosed#}"
                >
        </div>
        <!-- task text -->
        <div class="infowin_left display-none"
             id="taskSystemMessage"
             data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png"
             data-text-deleted="{#taskwasdeleted#}"
             data-text-edited="{#taskwasedited#}"
             data-text-added="{#taskwasadded#}"
             data-text-closed="{#taskwasclosed#}"
                >
        </div>
        <!-- messages text -->
        <div class="infowin_left display-none"
             id="messageSystemMessage"
             data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png"
             data-text-deleted="{#messagewasdeleted#}"
             data-text-edited="{#messagewasedited#}"
             data-text-added="{#messagewasadded#}"
                >
        </div>

        {if $isUpdated}
            {include file="updateNotify.tpl"}
            <br/>
        {/if}

        <h1>{#desktop#}</h1>

        <div id="block_index" class="block">

            <!--testpluginTwo-->

            {* Projects *}
            {include file="desktopProjects.tpl"}
            {* Tasks *}
            {include file="desktopTasks.tpl"}
            {* Calendar *}
            {if $tasknum}
                {include file="calendar.tpl"}
            {/if}
            <!--ganttChart-->

            {* Messages *}
            {include file="desktopMessages.tpl"}

        </div> {* block index end*}
    </div> {* content-left-in END *}
</div> {* content-left END *}

<script type="text/javascript" src="include/js/accordion.js"></script>
<script type="text/javascript" src="include/js/modal.min.js"></script>
<script type="text/javascript" src="include/js/views/index.js"></script>
<script type="text/javascript">
        pagination.itemsPerPage = 15;
    {if $tasknum > 0}
        var tasksView = createView(tasks);
        //add this view to the dependencies of projectsView
        projectsViewDependencies.push(tasksView);

        var accord_tasks;
        tasksView.afterUpdate(function () {
            accord_tasks = new accordion2('desktoptasks');
        });
    {/if}
    {if $msgnum > 0}
        var msgsView = createView(messages);
        //add this view to the dependencies of projectsView
        projectsViewDependencies.push(msgsView);

        var accord_msgs;
        msgsView.afterUpdate(function () {
            accord_msgs = new accordion2('desktopmessages');
        });
    {/if}
    //setup dependenciens
    Vue.set(projectsView, "dependencies", projectsViewDependencies);
</script>
{include file="sidebar-a.tpl"}
{include file="footer.tpl"}
