
	{*Easy Tracker*}

	<div class="content-right-in">
			<h2><a id = "trackertoggle" class="win-up" href="javascript:blindtoggle('tracker');toggleClass('trackertoggle','win-up','win-down');">{#easytracker#}</a></h3>
			<div id="tracker">
			{if $opentrack}
				{if $project.ID}
					{if $task.ID}
						<a class="button" href="managetimetracker.php?action=finisheasytracking&id={$project.ID}&tid={$task.ID}">{#stoptracking#}</a>
					{else}
						<a class="button" href="managetimetracker.php?action=finisheasytracking&id={$project.ID}">{#stoptracking#}</a>
					{/if}
				{/if}
				<a class="button" href="javascript:cancelEasyTracking()">{#canceltracking#}</a>
			{else}
				<a class="button" href="javascript:startEasyTracking()">{#starttracking#}</a>
			{/if}
			</div>
	</div>
	{*Easy Tracker End*}

