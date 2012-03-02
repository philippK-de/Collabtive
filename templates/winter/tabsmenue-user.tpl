<div class="tabswrapper">
	<ul class="tabs">
		{if $user.gender == "f"}
			{if $userid == $user.ID}
				<li class="user-female"><a {if $usertab == "active" }class="active"{/if} href="manageuser.php?action=profile&amp;id={$userid}"><span>{#myaccount#}</span></a></li>
			{else}
				<li class="user-female"><a {if $usertab == "active" }class="active"{/if} href=""></a></li>
			{/if}
			{if $userpermissions.admin.add and $userid != $user.ID}
				<li class="edit-male"><a {if $edittab == "active" }class="active"{/if} href="admin.php?action=editform&amp;id={$user.ID}"><span>{#edit#}</span></a></li>
			{elseif $userid == $user.ID}
				<li class="edit-male"><a {if $edittab == "active" }class="active"{/if} href="manageuser.php?action=editform&amp;id={$user.ID}"><span>{#edit#}</span></a></li>
			{/if}

		{else}
			{if $userid == $user.ID}
				<li class="user-male"><a {if $usertab == "active" }class="active"{/if} href="manageuser.php?action=profile&amp;id={$userid}"><span>{#myaccount#}</span></a></li>
			{else}
				<li class="user-male"><a {if $usertab == "active" }class="active"{/if} href=""></a></li>
			{/if}
			{if $userpermissions.admin.add and $userid != $user.ID}
				<li class="edit-male"><a {if $edittab == "active" }class="active"{/if} href="admin.php?action=editform&amp;id={$user.ID}"><span>{#edit#}</span></a></li>
			{elseif $userid == $user.ID}
				<li class="edit-male"><a {if $edittab == "active" }class="active"{/if} href="manageuser.php?action=editform&amp;id={$user.ID}"><span>{#edit#}</span></a></li>
			{/if}
		{/if}
	</ul>
</div>