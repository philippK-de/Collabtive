<ul class="nav nav-tabs">
	<li class="projects{if $projecttab == "active" } active{/if}"><a href="manageproject.php?action=showproject&amp;id={$project.ID}"><span>{#project#}</span></a></li>
	<li class="miles {if $milestab == "active" } active{/if}"><a href="managemilestone.php?action=showproject&amp;id={$project.ID}"><span>{#milestones#}</span></a></li>
	<li class="tasks{if $taskstab == "active" } active{/if}"><a href="managetask.php?action=showproject&amp;id={$project.ID}"><span>{#tasklists#}</span></a></li>
	<li class="msgs{if $msgstab == "active" } active{/if}"><a href="managemessage.php?action=showproject&amp;id={$project.ID}"><span>{#messages#}</span></a></li>
	<li class="files{if $filestab == "active" } active{/if}"><a href="managefile.php?action=showproject&amp;id={$project.ID}"><span>{#files#}</span></a></li>
	<li class="user{if $userstab == "active" } active{/if}"><a href="manageuser.php?action=showproject&amp;id={$project.ID}"><span>{#user#}</span></a></li>
	<li class="timetrack {if $timetab == "active" } active{/if}"><a href="managetimetracker.php?action=showproject&amp;id={$project.ID}"><span>{#timetracker#}</span></a></li>
</ul>