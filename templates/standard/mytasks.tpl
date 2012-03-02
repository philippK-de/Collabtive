{include file="header.tpl" jsload = "ajax"  jsload1 = "tinymce"}
{include file="tabsmenue-desk.tpl" taskstab = "active"}

<div id="content-left">
<div id="content-left-in">
<div class="tasks">

	<div class="infowin_left" style = "display:none;" id = "systemmsg">
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
	<script type = "text/javascript">
	apperar = new Effect.Appear('systemmsg', { duration: 2.0 })
	 </script>
	{/literal}


	<h1>{#mytasks#}</h1>

	<div class="export-main">
		<a class="export"><span>{#export#}</span></a>
		<div class="export-in"  style="width:46px;left: -46px;"> {*at two items*}
			<a class="ical" href="managetask.php?action=ical"><span>{#icalexport#}</span></a>
			<a class="rss" href="managerss.php?action=rss-tasks&user={$userid}"><span>{#rssfeed#}</span></a>
		</div>
	</div>

	{section name=project loop=$myprojects}
	{if $myprojects[project].tasknum}
			<div class="headline">
				<a href="javascript:void(0);" id="block-{$myprojects[project].ID}_toggle" class="win_block" onclick = "toggleBlock('block-{$myprojects[project].ID}');"></a>

				<div class="wintools">
					{if $userpermissions.tasks.add}
					<a class="add" href="javascript:blindtoggle('form_{$myprojects[project].ID}');" id="add_{$myprojects[project].ID}" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_{$myprojects[project].ID}','butn_link_active','butn_link');toggleClass('sm_{$myprojects[project].ID}','smooth','nosmooth');"><span>{#addtask#}</span></a>
					{/if}
				</div>

				<h2>
					<a href="managetask.php?action=showproject&amp;id={$myprojects[project].ID}" title="{$myprojects[project].name} / {#mytasks#}"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$myprojects[project].name|truncate:80:"...":true}</a>
				</h2>
			</div>

			<div id="block-{$myprojects[project].ID}" class="block">

			{*Add Task*}
			{if $userpermissions.tasks.add > 0}
				<div id = "form_{$myprojects[project].ID}" class="addmenue" style = "display:none;">
					{include file="addmytask.tpl" }
				</div>
			{/if}

			<div class="nosmooth" id="sm_{$myprojects[project].ID}">
				<table id="acc_{$myprojects[project].ID}" cellpadding="0" cellspacing="0" border="0">

					<thead>
						<tr>
							<th class="a"></th>
							<th class="b"><a href = "managetask.php?action=showproject&amp;id={$myprojects[project].ID}">{#tasks#}</a></th>
							<th class="c">{#tasklist#}</th>
							<th class="d" style="text-align:right">{#daysleft#}&nbsp;&nbsp;</th>
							<th class="tools"></th>
						</tr>
					</thead>

					<tfoot>
						<tr>
							<td colspan="5"></td>
						</tr>
					</tfoot>

					{if $myprojects[project].tasknum > 0}

					{section name=task loop=$myprojects[project].tasks}

					{*Color-Mix*}
					{if $smarty.section.task.index % 2 == 0}
					<tbody class="color-a" id="task_{$myprojects[project].tasks[task].ID}">
					{else}
					<tbody class="color-b" id="task_{$myprojects[project].tasks[task].ID}">
					{/if}
						<tr {if $myprojects[project].tasks[task].daysleft < 0} class="marker-late"{elseif $myprojects[project].tasks[task].daysleft == 0} class="marker-today"{/if}>
							<td>{if $userpermissions.tasks.close}<a class="butn_check" href="javascript:closeElement('task_{$myprojects[project].tasks[task].ID}','managetask.php?action=close&amp;tid={$myprojects[project].tasks[task].ID}&amp;id={$myprojects[project].ID}');" title="{#close#}"></a>{/if}</td>
							<td>
								<div class="toggle-in">
								<span class="acc-toggle" onclick="javascript:accord_{$myprojects[project].ID}.activate($$('#acc_{$myprojects[project].ID} .accordion_toggle')[{$smarty.section.task.index}]);toggleAccordeon('acc_{$myprojects[project].ID}',this);"></span>
									<a href="managetask.php?action=showtask&amp;tid={$myprojects[project].tasks[task].ID}&amp;id={$myprojects[project].tasks[task].project}" title="{$myprojects[project].tasks[task].title}">
										{if $myprojects[project].tasks[task].title != ""}
										{$myprojects[project].tasks[task].title|truncate:30:"...":true}
										{else}
										{$myprojects[project].tasks[task].text|truncate:30:"...":true}
										{/if}
									</a>
								</div>
							</td>
							<td><a href="managetasklist.php?action=showtasklist&amp;&amp;tlid={$myprojects[project].tasks[task].liste}&amp;id={$myprojects[project].ID}">{$myprojects[project].tasks[task].list|truncate:23:"...":true}</a></td>
							<td style="text-align:right">{$myprojects[project].tasks[task].daysleft}&nbsp;&nbsp;</td>
							<td class="tools">
								{if $userpermissions.tasks.edit}
								<a class="tool_edit" href="managetask.php?action=editform&amp;tid={$myprojects[project].tasks[task].ID}&amp;id={$myprojects[project].ID}" title="{#edit#}"></a>
								{/if}
								{if $userpermissions.tasks.del}
								<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$myprojects[project].tasks[task].ID}\',\'managetask.php?action=del&amp;tid={$myprojects[project].tasks[task].ID}&amp;id={$myprojects[project].ID}\')');"  title="{#delete#}"></a>
								{/if}
							</td>
						</tr>

						<tr class="acc">
							<td colspan="5">
								<div class="accordion_toggle"></div>
								<div class="accordion_content">
									<div class="acc-in">
										<div class="message-in">
											{$myprojects[project].tasks[task].text|nl2br}
										</div>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
					{/section}



					{else}
					<tbody class="color-a">
						<tr>
						<td></td>
						<td>{#notasks#}</td>
						<td></td>
						<td></td>
						<td class="tools"></td>
						</tr>
					</tbody>
					{/if}

				</table>


				{*Tasks donetasks*}
				<div id="doneblock_{$myprojects[project].ID}" class="doneblock" style="display: none;">
				<table class="second-thead" cellpadding="0" cellspacing="0" border="0" onclick="blindtoggle('doneblock_{$myprojects[project].ID}');toggleClass('donebutn_{$myprojects[project].ID}','butn_link_active','butn_link');toggleClass('toggle-done-{$myprojects[project].ID}','acc-toggle','acc-toggle-active');">

						<tr>
							<td class="a"></td>
							<td class="b"><span id="toggle-done-{$myprojects[project].ID}" class="acc-toggle">{#donetasks#}</span></td>
							<td class="c"></td>
							<td class="d"></td>
							<td class="tools"></td>
						</tr>

				</table>

				<div class="toggleblock">
					<table cellpadding="0" cellspacing="0" border="0" id = "done_{$myprojects[project].ID}">
						{section name=oldtask loop=$myprojects[project].oldtasks}

						{if $smarty.section.oldtask.index % 2 == 0}
						<tbody class="color-a" id="task_{$myprojects[project].oldtasks[oldtask].ID}">
						{else}
						<tbody class="color-b" id="task_{$myprojects[project].oldtasks[oldtask].ID}">
						{/if}


							<tr>
								<td class="a">{if $userpermissions.tasks.close}<a class="butn_checked" href="javascript:closeElement('task_{$myprojects[project].oldtasks[oldtask].ID}','managetask.php?action=open&amp;tid={$myprojects[project].oldtasks[oldtask].ID}&amp;id={$myprojects[project].ID}');" title="{#open#}"></a>{/if}</td>
								<td class="b">
									<div class="toggle-in">
									<span class="acc-toggle" onclick="javascript:done_{$myprojects[project].ID}.activate($$('#done_{$myprojects[project].ID} .accordion_toggle')[{$smarty.section.oldtask.index}]);toggleAccordeon('done_{$myprojects[project].ID}',this);"></span>
										<a href="managetask.php?action=showtask&amp;tid={$myprojects[project].oldtasks[oldtask].ID}&amp;id={$myprojects[project].oldtasks[oldtask].project}" title="{$myprojects[project].oldtasks[oldtask].title}">
											{if $myprojects[project].oldtasks[oldtask].title != ""}
											{$myprojects[project].oldtasks[oldtask].title|truncate:30:"...":true}
											{else}
											{$myprojects[project].oldtasks[oldtask].text|truncate:30:"...":true}
											{/if}
										</a>
									</div>
								</td>
								<td class="c"><a href="managetasklist.php?action=showtasklist&amp;&amp;tlid={$myprojects[project].oldtasks[oldtask].liste}&amp;id={$myprojects[project].ID}">{$myprojects[project].oldtasks[oldtask].list|truncate:23:"...":true}</a></td>
								<td class="d" style="text-align:right">{$myprojects[project].oldtasks[oldtask].daysleft}&nbsp;&nbsp;</td>
								<td class="tools">
									{if $userpermissions.tasks.edit}
									<a class="tool_edit" href="managetask.php?action=editform&amp;tid={$myprojects[project].oldtasks[oldtask].ID}&amp;id={$myprojects[project].ID}" title="{#edit#}"></a>
									{/if}
									{if $userpermissions.tasks.del}
									<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$myprojects[project].oldtasks[oldtask].ID}\',\'managetask.php?action=del&amp;tid={$myprojects[project].oldtasks[oldtask].ID}&amp;id={$myprojects[project].ID}\')');"  title="{#delete#}"></a>
									{/if}
								</td>
							</tr>

							<tr class="acc">
								<td colspan="5">
									<div class="accordion_toggle"></div>
									<div class="accordion_content">
										<div class="acc-in">
											<div class="message-in">
												{$myprojects[project].oldtasks[oldtask].text|nl2br}
											</div>
										</div>
									</div>
								</td>
							</tr>
						</tbody>

						{/section}

						{*Tasks donetasks END*}

					</table>
				</div> {*toggleblock End*}
				</div> {*done_block End*}

		</div> {*smooth End*}


				{*Add Task*}
				<div class="tablemenue">
					<div class="tablemenue-in">
						{if $userpermissions.tasks.add}
						<a class="butn_link" href="javascript:blindtoggle('form_{$myprojects[project].ID}');" id="add_butn_{$myprojects[project].ID}" onclick="toggleClass('add_{$myprojects[project].ID}','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_{$myprojects[project].ID}','smooth','nosmooth');">{#addtask#}</a>
						{/if}
						<a class="butn_link" href="javascript:blindtoggle('doneblock_{$myprojects[project].ID}');" id="donebutn_{$myprojects[project].ID}" onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done-{$myprojects[project].ID}','acc-toggle','acc-toggle-active');">{#donetasks#}</a>
					</div>
				</div>
				{*Add Task End*}


		</div> {*block END*}


	<div class="content-spacer"></div>

		{literal}
		<script type = "text/javascript">
		var accord_{/literal}{$myprojects[project].ID}{literal} = new accordion('acc_{/literal}{$myprojects[project].ID}{literal}');
		var done_{/literal}{$myprojects[project].ID}{literal} = new accordion('done_{/literal}{$myprojects[project].ID}{literal}');
		</script>
		{/literal}

			{/if}
		{/section}

			{if $tasknum < 1}
			<b>{#notasks#}</b>
			<div class="content-spacer"></div>
			{/if}



</div> {*tasks END*}
</div> {*content-left-in END*}
</div> {*content-left END*}


{include file="sidebar-a.tpl"}
{include file="footer.tpl"}