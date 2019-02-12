{include file="header.tpl" jsload="ajax" jsload1="tinymce" jsload3="lightbox" stage="index"}
{include file="tabsmenue-desk.tpl" desktab="active"}
<div class="row-fluid">
	<div id="content-left" class="span9">
		<div id="content-left-in">
			{* Display System messages *}
			<div class="infowin_left" style="display:none;" id="systemmsg">
				{if $mode == "projectadded"}
				<span class="info_in_green"><img src="templates/standard/images/symbols/projects.png" alt=""/>{#projectwasadded#}</span>
				{/if}

				{*for async display*}
				<span id="closed" style="display:none;" class="info_in_green"><img src="templates/standard/images/symbols/projects.png" alt=""/>{#projectwasclosed#}</span>
				<span id="deleted" style="display:none;" class="info_in_red"><img src="templates/standard/images/symbols/projects.png" alt=""/>{#projectwasdeleted#}</span>
			</div>

			{literal}
			<script type = "text/javascript">
				systemMsg('systemmsg');
			</script>
			{/literal}
			<div class="page-header">
				<h1>{#desktop#}</h1>
			</div>
			{include file="projects-desk.tpl"}
			{include file="tasks-desk.tpl"}
			{include file="milestones-desk.tpl"}
			{include file="messages-desk.tpl"}

			{literal}
			<script type = "text/javascript">
				try {
					var accord_projects = new accordion('projecthead');
				} catch(e) {
				}
				try {
					var accord_tasks = new accordion('taskhead');
				} catch(e) {
				}
				try {
					var accord_msgs = new accordion('activityhead');
				} catch(e) {
				}
				changeshow('manageajax.php?action=newcal', 'thecal', 'progress');
			</script>
			{/literal}

		</div>
		{*content-left-in END*}
	</div>
	{*content-left END*}
	{include file="sidebar-a.tpl"}
</div>
{include file="footer.tpl"}
