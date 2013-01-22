<ul class="nav nav-tabs">
	{if $user.gender == "f"}
	{if $userid == $user.ID}
	<li class="user-female {if $usertab == "active" } active{/if}">
		<a href="manageuser.php?action=profile&amp;id={$userid}"><span>{#myaccount#}</span></a>
	</li>
	{else}
	<li class="user-female{if $usertab == "active" } active{/if}">
		<a href=""></a>
	</li>
	{/if}
	{if $userpermissions.admin.add and $userid != $user.ID}
	<li class="edit-male{if $usertab == "active" } active{/if}">
		<a href="admin.php?action=editform&amp;id={$user.ID}"><span>{#edit#}</span></a>
	</li>
	{elseif $userid == $user.ID}
	<li class="edit-male {if $edittab == "active" } active{/if}">
		<a href="manageuser.php?action=editform&amp;id={$user.ID}"><span>{#edit#}</span></a>
	</li>
	{/if}

	{else}
	{if $userid == $user.ID}
	<li class="user-male{if $usertab == "active" } active{/if}">
		<a href="manageuser.php?action=profile&amp;id={$userid}"><span>{#myaccount#}</span></a>
	</li>
	{else}
	<li class="user-male{if $usertab == "active" } active{/if}">
		<a href=""></a>
	</li>
	{/if}
	{if $userpermissions.admin.add and $userid != $user.ID}
	<li class="edit-male{if $edittab == "active" } active{/if}">
		<a href="admin.php?action=editform&amp;id={$user.ID}"><span>{#edit#}</span></a>
	</li>
	{elseif $userid == $user.ID}
	<li class="edit-male{if $edittab == "active" } active{/if}">
		<a href="manageuser.php?action=editform&amp;id={$user.ID}"><span>{#edit#}</span></a>
	</li>
	{/if}
	{/if}
</ul>