{*Keyboard shortcuts on the project level*}
{literal}
<script type = "text/javascript">
shortcut.add("Alt+O", function() {
	window.location.href = 'manageproject.php?action=showproject&id={/literal}{$project.ID}{literal}';
});
shortcut.add("Alt+S", function() {
	window.location.href = 'managemilestone.php?action=showproject&id={/literal}{$project.ID}{literal}';
});
shortcut.add("Alt+T", function() {
	window.location.href = 'managetask.php?action=showproject&id={/literal}{$project.ID}{literal}';
});
shortcut.add("Alt+F", function() {
	window.location.href = 'managefile.php?action=showproject&id={/literal}{$project.ID}{literal}';
});
shortcut.add("Alt+U", function() {
	window.location.href = 'manageuser.php?action=showproject&id={/literal}{$project.ID}{literal}';
});
shortcut.add("Alt+M", function() {
	window.location.href = 'managemessage.php?action=showproject&id={/literal}{$project.ID}{literal}';
});
</script>
{/literal}
<div class="tabswrapper">
	<ul class="tabs">
		<li class="projects"><a {if $projecttab == "active" }class="active"{/if} href="manageproject.php?action=showproject&amp;id={$project.ID}"><span>{#project#}</span></a></li>

		<li class="miles"><a {if $milestab == "active" }class="active"{/if} href="managemilestone.php?action=showproject&amp;id={$project.ID}"><span>{#milestones#}</span></a></li>

		<li class="tasks"><a {if $taskstab == "active" }class="active"{/if} href="managetask.php?action=showproject&amp;id={$project.ID}"><span>{#tasklists#}</span></a></li>
		<li class="msgs"><a {if $msgstab == "active" }class="active"{/if} href="managemessage.php?action=showproject&amp;id={$project.ID}"><span>{#messages#}</span></a></li>
		<li class="files"><a {if $filestab == "active" }class="active"{/if} href="managefile.php?action=showproject&amp;id={$project.ID}"><span>{#files#}</span></a></li>
		<li class="user"><a {if $userstab == "active" }class="active"{/if} href="manageuser.php?action=showproject&amp;id={$project.ID}"><span>{#user#}</span></a></li>
		<li class="timetrack"><a {if $timetab == "active" }class="active"{/if} href="managetimetracker.php?action=showproject&amp;id={$project.ID}"><span>{#timetracker#}</span></a></li>
	</ul>
</div>