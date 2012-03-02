<div class="tabswrapper">
	<ul class="tabs">
		<li class="projects"><a {if $projecttab == "active" }class="active"{/if} href="admin.php?action=projects"><span>{#projectadministration#}</span></a></li>
		<li class="user"><a {if $usertab == "active" }class="active"{/if} href="admin.php?action=users"><span>{#useradministration#}</span></a></li>
		<li class="system-settings"><a {if $settingstab == "active" }class="active"{/if} href="admin.php?action=system"><span>{#systemadministration#}</span></a></li>
	</ul>
</div>