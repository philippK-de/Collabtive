<table  id="timeline1" cellpadding="0" cellspacing="1" border="0" class="thecal table table-bordered calendar">
	{*calender head bereich*}
	<thead class="calhead">
		<tr>
			<ul class="pager">
				<li class="previous">
					<a class="scroll_left btn" href="javascript:changeshow('manageajax.php?action=newcal&y={$py}&m={$pm}','thecal','progress');">&larr; Previous</a>
				</li>
				<li>
					{$mstring} {$y}
				</li>
				<li class="next">
					<a class="scroll_right btn" href="javascript:changeshow('manageajax.php?action=newcal&y={$ny}&m={$nm}','thecal','progress');">Next &rarr;</a>
				</li>
			</ul>
		</tr>
		{*tagesnamen ausgeben*}
		<tr class="dayhead">
			<th>{$langfile.monday}</th>
			<th>{$langfile.tuesday}</th>
			<th>{$langfile.wednesday}</th>
			<th>{$langfile.thursday}</th>
			<th>{$langfile.friday}</th>
			<th>{$langfile.saturday}</th>
			<th>{$langfile.sunday}</th>
		</tr>
	</thead>
	<tbody class="content">
		{section name = week loop=$weeks}
		<tr>
			{*tage der aktuellen woche durchlaufen*}
			{section name=day loop=$weeks[week]}
			{if $weeks[week][day].currmonth == 1}
			{*wenn es heute is, hervorheben*}
			{if $thism == $m and $thisy == $y and $thisd == $weeks[week][day].val}
				<td class="today" id="{$weeks[week][day].val}"><a href="javascript:void(0)" onclick="fadeToggle('t{$weeks[week][day].val}');">{$weeks[week][day].val}</a>
			{else}
				<td class="second" id="{$weeks[week][day].val}"><a href="javascript:void(0)" onclick="fadeToggle('t{$weeks[week][day].val}');">{$weeks[week][day].val}</a>
			{/if}
			{else}
				<td class="othermonth muted" id="{$weeks[week][day].val}">{$weeks[week][day].val}
			{/if}

			{*only output tasks/milestones if the day belongs to the current month*}
			{if $weeks[week][day].currmonth == 1}
			<div class="calcontent">
				{*Milestones des tages*}
				{if $weeks[week][day].milesnum > 0}
				<a href="#miles{$weeks[week][day].val}" data-toggle="modal">
					<i class="icon-flag"></i>
				</a>
				<div class="modal hide fade" id="miles{$weeks[week][day].val}" tabindex="-1" role="dialog" aria-labelledby="miles{$weeks[week][day].val}Label" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3>{$langfile.milestones} <small>{$weeks[week][day].val}.{$m}.{$y}</small></h3>
					</div>
					<div class="modal-body">
						<div class="miles">
							<div class="block">
								<table class="table table-condensed modal-table table-striped">
									<thead>
										<th></th>
										<th>{$langfile.project}: {$langfile.milestone}</th>
										<th class="tools">{$langfile.daysleft}</th>
									</thead>
									<tbody>
									{section name=stone loop=$weeks[week][day].milestones}
										<tr {if $weeks[week][day].milestones[stone].daysleft < 0} class="marker-late"{elseif $weeks[week][day].milestones[stone].daysleft == 0} class="marker-today"{/if}>
											<td class="icon"><i class="icon-flag"></i></td>
											<td>
												<a href="managemilestone.php?action=showmilestone&amp;msid={$weeks[week][day].milestones[stone].ID}&amp;id={$weeks[week][day].milestones[stone].project}" title="{$weeks[week][day].milestones[stone].title}">
												{$weeks[week][day].milestones[stone].pname}:
												{$weeks[week][day].milestones[stone].name|truncate:30:"...":true}
												</a>
											</td>
											<td class="tools">
												<span class="badge{if $weeks[week][day].milestones[stone].daysleft < 0} badge-important{elseif $weeks[week][day].milestones[stone].daysleft == 0} badge-warning{else} badge-success{/if}">{$weeks[week][day].milestones[stone].daysleft}</span>
											</td>
										</tr>
									{/section}
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				{/if}
				
				{*Tasks*}
				{if $weeks[week][day].tasksnum > 0}
				<a href="#tasks{$weeks[week][day].val}" data-toggle="modal">
					<i class="icon-tasks"></i>
				</a>
				<div id="tasks{$weeks[week][day].val}" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="tasks{$weeks[week][day].val}Label" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3>{$langfile.tasklist} <small>{$weeks[week][day].val}.{$m}.{$y}</small></h3>
					</div>
					<div class="modal-body">
						<div class="tasks">
							<div class="block">
								<table class="table table-condensed modal-table table-striped">
									<thead>
										<th></th>
										<th>{$langfile.project}: {$langfile.task}</th>
										<th class="tools">{$langfile.daysleft}</th>
									</thead>
									<tbody>
									{section name = task loop=$weeks[week][day].tasks}
										<tr class="{if $weeks[week][day].tasks[task].daysleft < 0}marker-late{elseif $weeks[week][day].tasks[task].daysleft == 0}marker-today{else}marker-none{/if}">
											<td class="icon"><i class="icon-tasks"></i></td>
											<td>
												<a href="managetask.php?action=showtask&amp;tid={$weeks[week][day].tasks[task].ID}&amp;id={$weeks[week][day].tasks[task].project}" title="{$weeks[week][day].tasks[task].title}">
													{$weeks[week][day].tasks[task].pname}: {$weeks[week][day].tasks[task].title|truncate:30:"...":true}
												</a>
											</td>
											<td class="tools" style="text-align: right;">
												<span class="badge{if $weeks[week][day].tasks[task].daysleft < 0} badge-important{elseif $weeks[week][day].tasks[task].daysleft == 0} badge-warning{else} badge-success{/if}">{$weeks[week][day].tasks[task].daysleft}</span>
											</td>
										</tr>
									{/section}
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				{/if} {*Tasks End*}
			</div> {*calcontent End*}
			{/if}
			</td>
		{*ende des tages*}
		{/section}
		</tr>

		{*ende der woche*}
		{/section}
	</tbody>
</table>
{*loading indicator off*}
{literal}
<script type = "text/javascript">stopWait('progress');</script>
{/literal}