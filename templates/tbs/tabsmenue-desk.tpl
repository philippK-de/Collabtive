<ul class="nav nav-tabs">
		<li class="desk{if $desktab == "active" } active{/if}"><a href="index.php"><span>{#desktop#}</span></a></li>
		<li class="projects{if $projectstab == "active" } active{/if}"><a href="myprojects.php"><span>{#myprojects#}</span></a></li>		
		<li class="tasks{if $taskstab == "active" } active{/if}"><a href="mytasks.php"><span>{#mytasks#}</span></a></li>	
		<li class="msgs{if $msgstab == "active" } active{/if}"><a href="managemessage.php?action=mymsgs"><span>{#mymessages#}</span></a></li>
</ul>
