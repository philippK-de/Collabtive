<ul class="nav nav-tabs">
	<li class="projects{if $projecttab == "active" } active{/if}"><a href="admin.php?action=projects"><span>{#projectadministration#}</span></a></li>
	<li class="user{if $usertab == "active" } active{/if}"><a href="admin.php?action=users"><span>{#useradministration#}</span></a></li>
	<li class="system-settings{if $settingstab == "active" } active{/if}"><a href="admin.php?action=system"><span>{#systemadministration#}</span></a></li>
</ul>