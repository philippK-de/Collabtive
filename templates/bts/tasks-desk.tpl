{*Tasks*}
{if $tasknum > 0}
<div class="tasks">
	<div class="headline navbar navbar-inverse clearfix">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="mytasks.php" title="{#mytasks#}">{#mytasks#}</a>
					<ul class="nav pull-right">
						<li class="divider-vertical"></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="icon-cog big"></i>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="rss" href="managerss.php?action=rss-tasks&user={$userid}"><i class="icon-globe"></i> {#rssfeed#}</a>
								</li>
								<li>
									<a class="ical" href="managetask.php?action=ical"><i class="icon-calendar"></i> {#icalexport#}</a>
								</li>
								<li>
									<a href="javascript:void(0);" id="taskhead_toggle" class="{$taskbar}" onclick="toggleBlock('taskhead');"><i class="icon-resize-vertical"></i> {#toggle#}</a>
								</li>	
							</ul>
						</li>
					</ul>
			</div>
		</div>
	</div>
	<div class="block" id="taskhead" style = "{$taskstyle}">
		<table id="desktoptasks" class="table table-bordered table-hover">
			<thead>
				<tr>
					<th class="a"></th>
					<th class="b" style="cursor:pointer;" onclick="sortBlock('desktoptasks','');">{#task#}</th>
					<th class="c" style="cursor:pointer;" onclick="sortBlock('desktoptasks','project');">{#project#}</th>
					<th class="d" style="cursor:pointer;text-align:right" onclick="sortBlock('desktoptasks','daysleft');">{#daysleft#}</th>
					<th class="tools"></th>
				</tr>
			</thead>
			{section name=task loop=$tasks}
			{*Color-Mix*}
			
			{if $smarty.section.task.index % 2 == 0}
				<tbody class="color-a" id="task_{$tasks[task].ID}" rel="{$tasks[task].ID},{$tasks[task].title},{$tasks[task].daysleft},{$tasks[task].pname}">
			{else}
				<tbody class="color-b" id="task_{$tasks[task].ID}" rel="{$tasks[task].ID},{$tasks[task].title},{$tasks[task].daysleft},{$tasks[task].pname}">
			{/if}
				<tr id="{$tasks[task].ID}"{if $tasks[task].daysleft < 0} class="marker-late error"{elseif $tasks[task].daysleft == 0} class="marker-today warning"{/if}>
					<td>
					{if $userpermissions.tasks.close}
						<a class="butn_check" href="javascript:closeElement('task_{$tasks[task].ID}','managetask.php?action=close&amp;tid={$tasks[task].ID}&amp;id={$tasks[task].project}');" title="{#close#}">{#close#}</a>
					{/if}
					</td>
					<td>
						{*
						{if !empty($tasks[task].text)}
							<a href="#{$tasks[task].ID}" class="btn btn-mini btn-info pull-right pop" rel="popover" data-content="{$tasks[task].text|strip_tags|truncate:'100'}" data-original-title="Information"><i class="icon-info-sign icon-white"></i></a>
						{/if}
						*}
						<a href="managetask.php?action=showtask&amp;id={$tasks[task].project}&amp;tid={$tasks[task].ID}" title="{$tasks[task].title}">
						{if $tasks[task].title != ""}
							{$tasks[task].title|truncate:35:"...":true}
						{else}
							{$tasks[task].text|strip_tags|truncate:35:"...":true}
						{/if}						
						</a>
						{if !empty($tasks[task].text)}
						<a href="#open{$tasks[task].ID}" class="btn btn-mini btn-info pull-right" data-toggle="modal" title="Information"><i class="icon-info-sign icon-white"></i></a>
							<div class="modal hide fade" id="open{$tasks[task].ID}" tabindex="-1" role="dialog" aria-labelledby="open{$tasks[task].name}Label" aria-hidden="true">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3>{$tasks[task].title}</h3>
								</div>
								<div class="modal-body">
									{$tasks[task].text|strip_tags|truncate:'200'}
								</div>
							</div>
							{/if}						
						</div>
					</td>
					<td>
						<a href="managetask.php?action=showproject&amp;id={$tasks[task].project}">{$tasks[task].pname|truncate:30:"...":true}</a>
					</td>
					<td style="text-align:right">
						<span class="badge{if $tasks[task].daysleft < 0} badge-important{elseif $tasks[task].daysleft == 0} badge-warning{else} badge-success{/if}">{$tasks[task].daysleft}</span>
					</td>
					<td class="tools">
						{if $userpermissions.tasks.edit}
							<a class="tool_edit" href="managetask.php?action=editform&amp;tid={$tasks[task].ID}&amp;id={$tasks[task].project}" title="{#edit#}"><i class="icon-edit"></i></a>
						{/if}
						{if $userpermissions.tasks.del}
							<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$tasks[task].ID}\',\'managetask.php?action=del&amp;tid={$tasks[task].ID}&amp;id={$tasks[task].project}\')');"  title="{#delete#}"><i class="icon-trash"></i></a>
						{/if}
					</td>
				</tr>
				</tbody>
	{/section}
	</table>
	</div> {*block END*}
</div> {*tasks END*}
<div class="content-spacer"></div>
{/if}{*Tasks End*}