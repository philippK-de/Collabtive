<div class="tabswrapper">
	<ul class="tabs">
		<li class="projects"><a {if $projecttab|default == "active" }class="active"{/if} href="admin.php?action=projects"><span>{#projectadministration#}</span></a></li>
		<li class="customers"><a {if $customertab|default == "active" }class="active"{/if} href="admin.php?action=customers"><span>{#customeradministration#}</span></a></li>
		<li class="user"><a {if $usertab|default == "active" }class="active"{/if} href="admin.php?action=users"><span>{#useradministration#}</span></a></li>
		<li class="system-settings"><a {if $settingstab|default == "active" }class="active"{/if} href="admin.php?action=system"><span>{#systemadministration#}</span></a></li>
	</ul>
</div>