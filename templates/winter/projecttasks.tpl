{include file="header.tpl" jsload = "ajax"  jsload1 = "tinymce" }
{include file="tabsmenue-project.tpl" taskstab = "active"}

<div id="content-left">
<div id="content-left-in">
<div class="tasks">

	{*System Message*}
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
		{elseif $mode == "listadded"}
			<span class="info_in_green"><img src="templates/standard/images/symbols/tasklist.png" alt=""/>{#tasklistwasadded#}</span>
		{elseif $mode == "listclosed"}
			<span class="info_in_red"><img src="templates/standard/images/symbols/tasklist-done.png" alt=""/>{#tasklistwasclosed#}</span>
		{elseif $mode == "listdeleted"}
			<span class="info_in_red"><img src="templates/standard/images/symbols/tasklist.png" alt=""/>{#tasklistwasdeleted#}</span>
		{elseif $mode == "listopened"}
			<span class="info_in_green"><img src="templates/standard/images/symbols/tasklist.png" alt=""/>{#tasklistwasopened#}</span>
		{/if}

		{*for async display*}
		<span id = "added" style = "display:none;" class="info_in_green"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasadded#}</span>
		<span id = "edited" style = "display:none;" class="info_in_yellow"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasedited#}</span>
		<span id = "deleted" style = "display:none;" class="info_in_red"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasdeleted#}</span>
		<span id = "opened" style = "display:none;" class="info_in_green"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasopened#}</span>
		<span id = "closed" style = "display:none;" class="info_in_green"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasclosed#}</span>
		<span id = "assigned" style = "display:none;" class="info_in_yellow"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasassigned#}</span>
		<span id = "deassigned" style = "display:none;" class="info_in_yellow"><img src="templates/standard/images/symbols/task.png" alt=""/>{#taskwasdeassigned#}</span>
		<span id = "listadded" style = "display:none;" class="info_in_green"><img src="templates/standard/images/symbols/tasklist.png" alt=""/>{#tasklistwasadded#}</span>
        <span id = "listclosed" style = "display:none;" class="info_in_red"><img src="templates/standard/images/symbols/tasklist-done.png" alt=""/>{#tasklistwasclosed#}</span>
		<span id = "listdeleted" style = "display:none;" class="info_in_red"><img src="templates/standard/images/symbols/tasklist.png" alt=""/>{#tasklistwasdeleted#}</span>
		<span id = "listopened" style = "display:none;" class="info_in_green"><img src="templates/standard/images/symbols/tasklist.png" alt=""/>{#tasklistwasopened#}</span>
	</div>
	
	{literal}
		<script type = "text/javascript">
			systemMsg('systemmsg');
		</script>
	{/literal}{*/System Message*}

	<h1>{$projectname|truncate:45:"...":true}<span>/ {#tasklists#}</span></h1>

	{if $userpermissions.tasks.add}
		<div class="add-main">
			<a id="addtasklists" class="add" href="javascript:blindtoggle('addlist');" onclick="toggleClass(this,'add-active','add');"><span>{#addtasklist#}</span></a>
		</div>
	{/if}

	{if $userpermissions.tasks.add} {*Add Tasklist*}
		<div id = "addlist" class="addmenue" style="display:none;">
			{include file="addtasklist.tpl" }
		</div>
	{/if} {*Add Tasklist END*}

	{*Tasks*}
	{if $lists[0][0]}
		{section name=list loop=$lists}
			<div class="headline">
				<a href="javascript:void(0);" id="block-{$lists[list].ID}_toggle" class="win_block" onclick = "toggleBlock('block-{$lists[list].ID}');"></a>

				<div class="wintools">
					{if $userpermissions.tasks.close}
						<a class="close" href="managetasklist.php?action=close&amp;tlid={$lists[list].ID}&amp;id={$project.ID}"><span>{#close#}</span></a>
					{/if}
					{if $userpermissions.tasks.edit}
						<a class="edit" href="managetasklist.php?action=editform&amp;tlid={$lists[list].ID}&amp;id={$project.ID}"><span>{#edit#}</span></a>
					{/if}
					{if $userpermissions.tasks.del}
						<a class="del" href="javascript:confirmit('{#confirmdel#}','managetasklist.php?action=del&amp;tlid={$lists[list].ID}&amp;id={$project.ID}');"><span>{#delete#}</span></a>
					{/if}
					{if $userpermissions.tasks.add}
						<a class="add" href="javascript:blindtoggle('form_{$lists[list].ID}');" id="add_{$lists[list].ID}" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_{$lists[list].ID}','butn_link_active','butn_link');toggleClass('sm_{$lists[list].ID}','smooth','nosmooth');"><span>{#addtask#}</span></a>
					{/if}
				</div>

				<h2>
					<a href="managetasklist.php?action=showtasklist&amp;id={$project.ID}&amp;tlid={$lists[list].ID}" title="{#tasklist#} {$lists[list].name}"><img src="./templates/standard/images/symbols/tasklist.png" alt="" />{$lists[list].name|truncate:70:"...":true}</a>
				</h2>
			</div>

			<div id="block-{$lists[list].ID}" class="block">

				{*Add Task*}
				{if $userpermissions.tasks.add}
					<div id = "form_{$lists[list].ID}" class="addmenue" style = "display:none;">
						{include file="addtask.tpl" }
					</div>
				{/if}

				<div class="nosmooth" id="sm_{$lists[list].ID}">
					<table id="acc_{$lists[list].ID}" cellpadding="0" cellspacing="0" border="0">

						<thead>
							<tr>
								<th class="a"></th>
								<th class="b">{#tasks#}</th>
								<th class="c">{#user#}</th>
								<th class="days" style="text-align:right">{#daysleft#}&nbsp;&nbsp;</th>
								<th class="tools"></th>
							</tr>
						</thead>

						<tfoot>
							<tr>
								<td colspan="5"></td>
							</tr>
						</tfoot>

						{section name=task loop=$lists[list].tasks}
							{*Color-Mix*}
							{if $smarty.section.task.index % 2 == 0}
							<tbody class="color-a" id="task_{$lists[list].tasks[task].ID}">
							{else}
							<tbody class="color-b" id="task_{$lists[list].tasks[task].ID}">
							{/if}
								<tr {if $lists[list].tasks[task].daysleft < 0} class="marker-late"{elseif $lists[list].tasks[task].daysleft == 0} class="marker-today"{/if}>
									<td>
										{if $userpermissions.tasks.close}
											<a class="butn_check" href="javascript:closeElement('task_{$lists[list].tasks[task].ID}','managetask.php?action=close&amp;tid={$lists[list].tasks[task].ID}&amp;id={$project.ID}');" title="{#close#}"></a>
										{/if}
									</td>
									<td>
										<div class="toggle-in">
										<span class="acc-toggle" onclick="javascript:accord_{$lists[list].ID}.activate($$('#acc_{$lists[list].ID} .accordion_toggle')[{$smarty.section.task.index}]);toggleAccordeon('acc_{$lists[list].ID}',this);"></span>
											<a href="managetask.php?action=showtask&amp;tid={$lists[list].tasks[task].ID}&amp;id={$lists[list].tasks[task].project}" title="{$lists[list].tasks[task].title}">
												{if $lists[list].tasks[task].title != ""}
												{$lists[list].tasks[task].title|truncate:30:"...":true}
												{else}
												{$lists[list].tasks[task].text|truncate:30:"...":true}
												{/if}
											</a>
										</div>
									</td>
									<td>
										{section name=theusers loop=$lists[list].tasks[task].users}
											<a href="manageuser.php?action=profile&amp;id={$lists[list].tasks[task].users[theusers].ID}">{$lists[list].tasks[task].users[theusers].name|truncate:30:"...":true}</a> 
										{/section}
									</td>
									<td style="text-align:right">{$lists[list].tasks[task].daysleft}&nbsp;&nbsp;</td>
									<td class="tools">
										{if $userpermissions.tasks.edit}
										<a class="tool_edit" href="managetask.php?action=editform&amp;tid={$lists[list].tasks[task].ID}&amp;id={$project.ID}" title="{#edit#}"></a>{/if}
										{if $userpermissions.tasks.del}
										<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$lists[list].tasks[task].ID}\',\'managetask.php?action=del&amp;tid={$lists[list].tasks[task].ID}&amp;id={$project.ID}\')');"  title="{#delete#}"></a>
										{/if}
									</td>
								</tr>

								<tr class="acc">
									<td colspan="5">
										<div class="accordion_toggle"></div>
										<div class="accordion_content">
											<div class="acc-in">
												<div class="message-in">
													{$lists[list].tasks[task].text|nl2br}
												</div>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
							{literal}
								<script type = "text/javascript">
									var accord_{/literal}{$lists[list].ID}{literal} = new accordion('block-{/literal}{$lists[list].ID}{literal}');
								</script>
							{/literal}
						{/section}
					</table>

					{*Tasks donetasks*}
					<div id="doneblock_{$lists[list].ID}" class="doneblock" style="display: none;">
						<table class="second-thead" cellpadding="0" cellspacing="0" border="0" onclick="blindtoggle('doneblock_{$lists[list].ID}');toggleClass('donebutn_{$lists[list].ID}','butn_link_active','butn_link');toggleClass('toggle-done-{$lists[list].ID}','acc-toggle','acc-toggle-active');">

								<tr>
									<td class="a"></td>
									<td class="b"><span id="toggle-done-{$lists[list].ID}" class="acc-toggle">{#donetasks#}</span></td>
									<td class="c"></td>
									<td class="days"></td>
									<td class="tools"></td>
								</tr>

						</table>

						<div class="toggleblock">
							<table cellpadding="0" cellspacing="0" border="0" id = "done_{$lists[list].ID}">
								{section name=oldtask loop=$lists[list].oldtasks}

								{if $smarty.section.oldtask.index % 2 == 0}
								<tbody class="color-a" id="task_{$lists[list].oldtasks[oldtask].ID}">
								{else}
								<tbody class="color-b" id="task_{$lists[list].oldtasks[oldtask].ID}">
								{/if}


									<tr>
										<td class="a">{if $userpermissions.tasks.close}<a class="butn_checked" href="javascript:closeElement('task_{$lists[list].oldtasks[oldtask].ID}','managetask.php?action=open&amp;tid={$lists[list].oldtasks[oldtask].ID}&amp;id={$project.ID}');" title="{#open#}"></a>{/if}</td>
										<td class="b">
											<div class="toggle-in">
											<span class="acc-toggle" onclick="javascript:accord_done_{$lists[list].ID}.activate($$('#done_{$lists[list].ID} .accordion_toggle')[{$smarty.section.oldtask.index}]);toggleAccordeon('done_{$lists[list].ID}',this);"></span>
												<a href="managetask.php?action=showtask&amp;tid={$lists[list].oldtasks[oldtask].ID}&amp;id={$lists[list].oldtasks[oldtask].project}" title="{$lists[list].oldtasks[oldtask].title}">
													{if $lists[list].oldtasks[oldtask].title != ""}
													{$lists[list].oldtasks[oldtask].title|truncate:30:"...":true}
													{else}
													{$lists[list].oldtasks[oldtask].text|truncate:30:"...":true}
													{/if}
												</a>
											</div>
										</td>
										<td class="c"><a href="manageuser.php?action=profile&amp;id={$lists[list].oldtasks[oldtask].user_id}">{$lists[list].oldtasks[oldtask].user|truncate:23:"...":true}</a></td>
										<td class="days" style="text-align:right">{$lists[list].oldtasks[oldtask].daysleft}&nbsp;&nbsp;</td>
										<td class="tools">
											{if $userpermissions.tasks.edit}
												<a class="tool_edit" href="managetask.php?action=editform&amp;tid={$lists[list].oldtasks[oldtask].ID}&amp;id={$project.ID}" title="{#edit#}"></a>
											{/if}
											{if $userpermissions.tasks.del}
												<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$lists[list].oldtasks[oldtask].ID}\',\'managetask.php?action=del&amp;tid={$lists[list].oldtasks[oldtask].ID}&amp;id={$project.ID}\')');"  title="{#delete#}"></a>
											{/if}
										</td>
									</tr>

									<tr class="acc">
										<td colspan="5">
											<div class="accordion_toggle"></div>
											<div class="accordion_content">
												<div class="acc-in">
													<div class="message-in">
														{$lists[list].oldtasks[oldtask].text|nl2br}
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
								
								{literal}
									<script type = "text/javascript">
										var accord_done_{/literal}{$lists[list].ID}{literal} = new accordion('done_{/literal}{$lists[list].ID}{literal}');
									</script>
								{/literal}
							{/section} {*Tasks donetasks END*}

						</table>
					</div> {*toggleblock End*}
				</div> {*done_block End*}
			</div> {*smooth End*}

			<div class="tablemenue">
				<div class="tablemenue-in">
					{if $userpermissions.tasks.add}
						<a class="butn_link" href="javascript:blindtoggle('form_{$lists[list].ID}');" id="add_butn_{$lists[list].ID}" onclick="toggleClass('add_{$lists[list].ID}','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_{$lists[list].ID}','smooth','nosmooth');">{#addtask#}</a>
					{/if}
					<a class="butn_link" href="javascript:blindtoggle('doneblock_{$lists[list].ID}');" id="donebutn_{$lists[list].ID}" onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done-{$lists[list].ID}','acc-toggle','acc-toggle-active');">{#donetasks#}</a>
				</div>
			</div>
		</div> {*block END*}
		
		<div class="content-spacer"></div>
	{/section} {*Tasks End*}
{/if} {*if $lists[0][0]*}

{if $oldlists[0][0]} {*only show the block if there are closed tasklists*} {*Done Tasklists*}
	<div class="headline">
		<a href="javascript:void(0);" id="block-donelists_toggle" class="win_block" onclick = "toggleBlock('block-donelists');"></a>
		<h2>
			<img src="./templates/standard/images/symbols/tasklist-done.png" alt="" />{#donetasklists#}
		</h2>
	</div>

	<div id="block-donelists" class="block">
		<div class="dones">
			<table id="acc_donelists" cellpadding="0" cellspacing="0" border="0">

				<thead>
					<tr>
						<th class="a"></th>
						<th class="b">{#tasklist#}</th>
						<th class="c"></th>
						<th class="days"></th>
						<th class="tools"></th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="5"></td>
					</tr>
				</tfoot>

				{section name = oldlist loop=$oldlists}
					{*Color-Mix*}
					{if $smarty.section.oldlist.index % 2 == 0}
					<tbody class="color-a" id="task_{$oldlists[oldlist].ID}">
					{else}
					<tbody class="color-b" id="task_{$oldlists[oldlist].ID}">
					{/if}
						<tr {if $oldlists[oldlist].daysleft < 0} class="marker-late"{elseif $oldlists[oldlist].daysleft == 0} class="marker-today"{/if}>
							<td>{if $userpermissions.tasks.close}<a class="butn_check" href="managetasklist.php?action=open&amp;tlid={$oldlists[oldlist].ID}&amp;id={$project.ID}" title="{#open#}"></a>{/if}</td>
							<td>
								<div class="toggle-in">
								<span class="acc-toggle" onclick="javascript:accord_donelists.activate($$('#block-donelists .accordion_toggle')[{$smarty.section.oldlist.index}]);toggleAccordeon('acc_{$lists[list].ID}',this);"></span>
									<a href="managetasklist.php?action=showtasklist&amp;id={$project.ID}&amp;tlid={$oldlists[oldlist].ID}">
									{$oldlists[oldlist].name|truncate:30:"...":true}
									</a>
								</div>
							</td>
							<td></td>
							<td>{$oldlists[oldlist].daysleft}</td>
							<td class="tools">
								{if $userpermissions.tasks.del}
								<a class="tool_del" href="managetasklist.php?action=del&amp;tlid={$oldlists[oldlist].ID}&amp;id={$project.ID}"  title="{#delete#}"></a>
								{/if}
							</td>
						</tr>

						<tr class="acc">
							<td colspan="5">
								<div class="accordion_toggle"></div>
								<div class="accordion_content">
									<div class="acc-in">
										<div class="message-in">
											{$oldlists[oldlist].desc|nl2br}
										</div>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				{/section}
			</table>
			
			<div class="tablemenue"></div>
		</div> {*dones End*}
	</div> {*block End*}

	<div class="content-spacer"></div>
	
	{literal}
		<script type = "text/javascript">
			var accord_donelists = new accordion('block-donelists');
		</script>
	{/literal}
{/if} {*Done Tasklists End*}

{if !$lists[0][0] and !$oldlists[0][0]}
	<tbody class="color-a">
		<tr>
			<td></td>
			<td colspan="3" class="info">{#notasklists#}</td>
			<td class="tools"></td>
		</tr>
	</tbody>
{/if}

</div> {*Tasks END*}
</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}