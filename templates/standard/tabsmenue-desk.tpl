<div class="tabswrapper">

<ul class="tabs">
		<li class="desk"><a {if $desktab == "active" }class="active"{/if} href="index.php"><span>{#desktop#}</span></a></li>
		<li class="projects"><a {if $projectstab == "active" }class="active"{/if} href="myprojects.php"><span>{#myprojects#}</span></a></li>		
		<li class="tasks"><a {if $taskstab == "active" }class="active"{/if} href="mytasks.php"><span>{#mytasks#}</span></a></li>	
		<li class="msgs"><a {if $msgstab == "active" }class="active"{/if} href="managemessage.php?action=mymsgs"><span>{#mymessages#}</span></a></li>				
	</ul>
</div>