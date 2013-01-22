{include file="header.tpl" jsload="ajax"  jsload1="tinymce"}
{include file="tabsmenue-desk.tpl" taskstab="active"}
<div class="row-fluid">
	<div id="content-left" class="span9">
		<div class="tasks">
			<div class="infowin_left" style="display:none;" id="systemmsg">
			{if $mode == "added"}
				<span class="info_in_green"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasadded#}</span>
			{elseif $mode == "edited"}
				<span class="info_in_yellow"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasedited#}</span>
			{elseif $mode == "deleted"}
				<span class="info_in_red"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasdeleted#}</span>
			{elseif $mode == "opened"}
				<span class="info_in_green"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasopened#}</span>
			{elseif $mode == "closed"}
				<span class="info_in_red"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasclosed#}</span>
			{elseif $mode == "assigned"}
				<span class="info_in_yellow"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasassigned#}</span>
			{elseif $mode == "deassigned"}
				<span class="info_in_yellow"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasdeassigned#}</span>
			{elseif $mode == "listclosed"}
				<span class="info_in_red"><img src="templates/standard/images/symbols/tasklist.png" alt=""/>{#tasklistwasclosed#}</span>
			{elseif $mode == "listdeleted"}
				<span class="info_in_red"><img src="templates/standard/images/symbols/tasklist.png" alt=""/>{#tasklistwasdeleted#}</span>
			{/if}
			</div>
{literal}
<script type= "text/javascript">
	apperar = new Effect.Appear('systemmsg', { duration: 2.0 })
</script>
{/literal}
		<div class="dropdown export pull-right">
			<a class="dropdown-toggle btn btn-mini" data-toggle="dropdown" href="#">{#export#} <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				<li>
					<a class="ical" href="managetask.php?action=ical"><i class="icon-calendar"></i> {#icalexport#}</a>
				</li>
				<li>
					<a class="rss" href="managerss.php?action=rss-tasks&user={$userid}"><i class="icon-globe"></i> {#rssfeed#}</a>
				</li>
			</ul>
		</div>
		<h1>{#mytasks#}</h1>
		<div class="clear"></div>
		{section name=project loop=$myprojects}
		{if $myprojects[project].tasknum}
		<div class="headline navbar navbar-inverse clearfix">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="managetask.php?action=showproject&amp;id={$myprojects[project].ID}" title="{$myprojects[project].name} / {#mytasks#}">{$myprojects[project].name|truncate:80:"...":true}</a>
					<ul class="nav pull-right">
						<li class="divider-vertical"></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="icon-cog big"></i>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								{if $userpermissions.tasks.add}
								<li>
									<a class="add" href="javascript:blindtoggle('form_{$myprojects[project].ID}');" id="add_{$myprojects[project].ID}" onclick="toggleClass(this,'add-active','add');"><i class="icon-pencil"></i> {#addtask#}</a>
								</li>
								{/if}
								<li>
									<a href="javascript:blindtoggle('doneblock_{$myprojects[project].ID}');" id="donebutn_{$myprojects[project].ID}"><i class="icon-check"></i> {#donetasks#}</a>
								</li>
								<li>
									<a href="javascript:void(0);" id="block-{$myprojects[project].ID}_toggle" class="win_block" onclick="toggleBlock('block-{$myprojects[project].ID}');"><i class="icon-resize-vertical"></i> {#toggle#}</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>		
		<div id="block-{$myprojects[project].ID}" class="block">
		{*Add Task*}
		{if $userpermissions.tasks.add > 0}
		<div id="form_{$myprojects[project].ID}" class="addmenue" style="display:none;">
			{include file="addmytask.tpl" }
		</div>
		{/if}
			{if $myprojects[project].tasknum > 0}
			<table id="acc_{$myprojects[project].ID}" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="a"></th>
						<th class="b"><a href="managetask.php?action=showproject&amp;id={$myprojects[project].ID}">{#tasks#}</a></th>
						<th class="c">{#tasklist#}</th>
						<th class="d" style="text-align:right">{#daysleft#}&nbsp;&nbsp;</th>
						<th class="tools"></th>
					</tr>
				</thead>
				{section name=task loop=$myprojects[project].tasks}
				{*Color-Mix*}
				{if $smarty.section.task.index % 2 == 0}
				<tbody class="color-a" id="task_{$myprojects[project].tasks[task].ID}">
				{else}
				<tbody class="color-b" id="task_{$myprojects[project].tasks[task].ID}">
				{/if}
						<tr id="{$myprojects[project].tasks[task].ID}"{if $myprojects[project].tasks[task].daysleft < 0 && $myprojects[project].tasks[task].daysleft != ''} class="marker-late error"{elseif $myprojects[project].tasks[task].daysleft == 0} class="marker-today warning"{/if}>
							<td>{if $userpermissions.tasks.close}<a class="butn_check" href="javascript:closeElement('task_{$myprojects[project].tasks[task].ID}','managetask.php?action=close&amp;tid={$myprojects[project].tasks[task].ID}&amp;id={$myprojects[project].ID}');" title="{#close#}">Close</a>{/if}</td>
							<td>
								<a href="managetask.php?action=showtask&amp;tid={$myprojects[project].tasks[task].ID}&amp;id={$myprojects[project].tasks[task].project}" title="{$myprojects[project].tasks[task].title}">
								{if $myprojects[project].tasks[task].title != ""}
									{$myprojects[project].tasks[task].title|truncate:30:"...":true}
								{else}
									{$myprojects[project].tasks[task].text|strip_tags|truncate:30:"...":true}
								{/if}
								</a>
								{if !empty($myprojects[project].tasks[task].text)}
								<a href="#open{$myprojects[project].tasks[task].ID}" class="btn btn-mini btn-info pull-right" data-toggle="modal" title="Information"><i class="icon-info-sign icon-white"></i></a>
									<div class="modal hide fade" id="open{$myprojects[project].tasks[task].ID}" tabindex="-1" role="dialog" aria-labelledby="open{$myprojects[project].tasks[task].title}Label" aria-hidden="true">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h3>{$myprojects[project].tasks[task].title}</h3>
										</div>
										<div class="modal-body">
											{$myprojects[project].tasks[task].text|nl2br|strip_tags|truncate:'200'}
										</div>
									</div>
								{/if}
							</td>
							<td><a href="managetasklist.php?action=showtasklist&amp;&amp;tlid={$myprojects[project].tasks[task].liste}&amp;id={$myprojects[project].ID}">{$myprojects[project].tasks[task].list|truncate:23:"...":true}</a></td>
							<td style="text-align:right">
								<span class="badge{if $myprojects[project].tasks[task].daysleft < 0} badge-important{elseif $myprojects[project].tasks[task].daysleft == 0} badge-warning{else} badge-success{/if}">{$myprojects[project].tasks[task].daysleft}</span>
							<td class="tools">
								{if $userpermissions.tasks.edit}
									<a class="tool_edit" href="managetask.php?action=editform&amp;tid={$myprojects[project].tasks[task].ID}&amp;id={$myprojects[project].ID}" title="{#edit#}"><i class="icon-edit"></i></a>
								{/if}
								{if $userpermissions.tasks.del}
									<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$myprojects[project].tasks[task].ID}\',\'managetask.php?action=del&amp;tid={$myprojects[project].tasks[task].ID}&amp;id={$myprojects[project].ID}\')');"  title="{#delete#}"><i class="icon-trash"></i></a>
								{/if}
							</td>
						</tr>
					</tbody>				
					{/section}
				</table>
				{else}
				<div class="alert alert-block alert-success">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{#notasks#}</strong>
				</div>
				{/if}
				{*Tasks donetasks*}
				<div id="doneblock_{$myprojects[project].ID}" class="doneblock" style="display: none;">
					{if $myprojects[project].oldtasks > 0}
					<div class="navbar clearfix">
						<div class="navbar-inner">
							<div class="container">
								<a class="brand" href="managetask.php?action=showproject&amp;id={$myprojects[project].ID}" title="{$myprojects[project].name} / {#mytasks#}">{#donetasks#}</a>
							</div>
						</div>
					</div>
					
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th class="a"></th>
								<th class="b"><a href="managetask.php?action=showproject&amp;id={$myprojects[project].ID}">{#tasks#}</a></th>
								<th class="c">{#tasklist#}</th>
								<th class="d" style="text-align:right">{#daysleft#}&nbsp;&nbsp;</th>
								<th class="tools"></th>
							</tr>
						</thead>
						{section name=oldtask loop=$myprojects[project].oldtasks}
						{if $smarty.section.oldtask.index % 2 == 0}
						<tbody class="color-a" id="task_{$myprojects[project].oldtasks[oldtask].ID}">
						{else}
						<tbody class="color-b" id="task_{$myprojects[project].oldtasks[oldtask].ID}">
						{/if}
							<tr>
								<td class="a">{if $userpermissions.tasks.close}<a class="butn_checked" href="javascript:closeElement('task_{$myprojects[project].oldtasks[oldtask].ID}','managetask.php?action=open&amp;tid={$myprojects[project].oldtasks[oldtask].ID}&amp;id={$myprojects[project].ID}');" title="{#open#}"></a>{/if}</td>
								<td class="b">
									<span class="acc-toggle" onclick="javascript:done_{$myprojects[project].ID}.activate($$('#done_{$myprojects[project].ID} .accordion_toggle')[{$smarty.section.oldtask.index}]);toggleAccordeon('done_{$myprojects[project].ID}',this);"></span>
										<a href="managetask.php?action=showtask&amp;tid={$myprojects[project].oldtasks[oldtask].ID}&amp;id={$myprojects[project].oldtasks[oldtask].project}" title="{$myprojects[project].oldtasks[oldtask].title}">
											{if $myprojects[project].oldtasks[oldtask].title != ""}
											{$myprojects[project].oldtasks[oldtask].title|truncate:30:"...":true}
											{else}
											{$myprojects[project].oldtasks[oldtask].text|truncate:30:"...":true}
											{/if}
										</a>
										{*$myprojects[project].oldtasks[oldtask].text|nl2br*}
								</td>
								<td class="c">
									<a href="managetasklist.php?action=showtasklist&amp;&amp;tlid={$myprojects[project].oldtasks[oldtask].liste}&amp;id={$myprojects[project].ID}">{$myprojects[project].oldtasks[oldtask].list|truncate:23:"...":true}</a>
								</td>
								<td class="d" style="text-align:right">
									{$myprojects[project].oldtasks[oldtask].daysleft}&nbsp;&nbsp;
								</td>
								<td class="tools">
									{if $userpermissions.tasks.edit}
										<a class="tool_edit" href="managetask.php?action=editform&amp;tid={$myprojects[project].oldtasks[oldtask].ID}&amp;id={$myprojects[project].ID}" title="{#edit#}"><i class="icon-edit"></i></a>
									{/if}
									{if $userpermissions.tasks.del}
										<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$myprojects[project].oldtasks[oldtask].ID}\',\'managetask.php?action=del&amp;tid={$myprojects[project].oldtasks[oldtask].ID}&amp;id={$myprojects[project].ID}\')');"  title="{#delete#}"><i class="icon-trash"></i></a>
									{/if}
								</td>
							</tr>
						</tbody>
						{/section}
					</table>
					{else}
					<div class="alert alert-block alert-success">
						<button type="button" class="close" data-dismiss="alert">×</button>
						<strong>{#notasks#}</strong>
					</div>
					{/if}
					</div>
			</div>
			{/if}
		{/section}
		{if $tasknum lt 1}
		<div class="alert alert-block alert-success">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<strong>{#notasks#}</strong>
		</div>
		{/if}
		</div> {*tasks END*}
	</div>
	{include file="sidebar-a.tpl"}
</div>
{include file="footer.tpl"}