{include file="header.tpl" jsload = "ajax"  jsload1 = "tinymce" }
{include file="tabsmenue-project.tpl" taskstab = "active"}

<div id="content-left">
<div id="content-left-in">
<div class="tasks">

	<div class="infowin_left" style = "display:none;" id = "systemmsg">
		{if $mode == "edited"}
			<span class="info_in_yellow"><img src="templates/standard/img/symbols/tasklist.png" alt=""/>{#tasklistwasedited#}</span>
		{/if}
	</div>
	
	{literal}
		<script type = "text/javascript">
			apperar = new Effect.Appear('systemmsg', { duration: 2.0 })
		</script>
	{/literal}

	<div class="breadcrumb">
		<a href="manageproject.php?action=showproject&amp;id={$project.ID}"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$projectname|truncate:40:"...":true}</a>
		<a href="managetask.php?action=showproject&amp;id={$project.ID}"><img src="./templates/standard/images/symbols/tasklist.png" alt="" />{#tasklists#}</a>
	</div>

	<h1 class="second"><img src="./templates/standard/images/symbols/tasklist.png" alt="" />{$tasklist.name|truncate:40:"...":true}</h1>

	<div class="statuswrapper">
		<ul>
			{if $userpermissions.tasks.close}
				{if $tasklist.status == 1}
					<li class="link" id="closetoggle"><a class="close" href="managetasklist.php?action=close&amp;tlid={$tasklist.ID}&amp;id={$project.ID}" title="{#close#}"></a></li>
				{else}
					<li class="link" id="closetoggle"><a class="closed" href="managetasklist.php?action=open&amp;tlid={$tasklist.ID}&amp;id={$project.ID}" title="{#open#}"></a></li>
				{/if}
			{/if}
			{if $userpermissions.tasks.edit}
				<li class="link"><a class="edit" href="javascript:void(0);"  id="edit_butn" onclick="blindtoggle('form_edit');toggleClass(this,'edit-active','edit');toggleClass('sm_tasklist','smooth','nosmooth');" title="{#edit#}"></a></li>
			{/if}
			{if $userpermissions.tasks.del}
				<li class="link"><a class="del" href="javascript:void(0);" onclick="confirmit('{#confirmdel#}','managetasklist.php?action=del&amp;tlid={$tasklist.ID}&amp;id={$project.ID}');" title="{#delete#}"></a></li>
			{/if}
			{if $tasklist.desc}
				<li class="link" onclick="blindtoggle('descript');toggleClass('desctoggle','desc_active','desc');"><a class="desc" id="desctoggle" href="#" title="{#open#}">{#description#}</a></li>
			{/if}
			<li><a>{#start#}: {$tasklist.startstring}</a></li>
		</ul>
	</div>
	
	{*Edit Task*}
	{if $userpermissions.tasks.edit}
		<div id = "form_edit" class="addmenue" style = "display:none;clear:both;">
			<div class="content-spacer"></div>
			{include file="edittasklist.tpl" showhtml="no" }
		</div>
	{/if}

	<div class="content-spacer"></div>

	<div class="nosmooth" id="sm_tasklist">
		<div id="descript" class="descript" style="display:none;">
			<h2>{$tasklist.name}</h2>
			{$tasklist.desc}
			<div class="content-spacer"></div>
		</div>

		<div class="headline">
			<a href="javascript:void(0);" id="block-{$myprojects[project].ID}_toggle" class="win_block" onclick = "toggleBlock('block-{$myprojects[project].ID}');"></a>

			<div class="wintools">
				{if $userpermissions.tasks.add}
				<a class="add" href="javascript:blindtoggle('form_addtask');" id="add_{$myprojects[project].ID}" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_{$myprojects[project].ID}','butn_link_active','butn_link');toggleClass('sm_{$myprojects[project].ID}','smooth','nosmooth');"><span>{#addtask#}</span></a>
				{/if}
			</div>

			<h2>
				<img src="./templates/standard/images/symbols/tasklist.png" alt="" />{#newtasks#}</a>
			</h2>
		</div>

		<div id="acc1" class="block">

		{*Add Task*}
		{if $userpermissions.tasks.add}
			<div id = "form_addtask" class="addmenue" style = "display:none;">
				{include file="addtask.tpl" }
			</div>
		{/if}

		<div class="nosmooth" id="sm_{$myprojects[project].ID}">
			<table id="acc_1" cellpadding="0" cellspacing="0" border="0">

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

				{if $tasklist.tasknum > 0}
					{section name=task loop=$tasks}
						{*Color-Mix*}
						{if $smarty.section.task.index % 2 == 0}
						<tbody class="color-a" id="task_{$tasks[task].ID}">
						{else}
						<tbody class="color-b" id="task_{$tasks[task].ID}">
						{/if}
							<tr {if $tasks[task].daysleft < 0} class="marker-late"{elseif $tasks[task].daysleft == 0} class="marker-today"{/if}>
								<td>{if $userpermissions.tasks.close}<a class="butn_check" href="javascript:closeElement('task_{$tasks[task].ID}','managetask.php?action=close&amp;tid={$tasks[task].ID}&amp;id={$project.ID}');" title="{#close#}"></a>{/if}</td>
								<td>
									<div class="toggle-in">
									<span class="acc-toggle" onclick="javascript:accord_1.activate($$('#acc1 .accordion_toggle')[{$smarty.section.task.index}]);toggleAccordeon('acc_1',this);"></span>
										<a href="managetask.php?action=showtask&amp;tid={$tasks[task].ID}&amp;id={$tasks[task].project}" title="{$tasks[task].title}">
											{if $tasks[task].title != ""}
											{$tasks[task].title|truncate:30:"...":true}
											{else}
											{$tasks[task].text|truncate:30:"...":true}
											{/if}
										</a>
									</div>
								</td>
								<td><a href="manageuser.php?action=profile&amp;tlid={$tasks[task].user_id}&amp;id={$project.ID}">{$tasks[task].user|truncate:23:"...":true}</a></td>
								<td style="text-align:right">{$tasks[task].daysleft}&nbsp;&nbsp;</td>
								<td class="tools">
									{if $userpermissions.tasks.edit}
									<a class="tool_edit" href="managetask.php?action=editform&amp;tid={$tasks[task].ID}&amp;id={$project.ID}" title="{#edit#}"></a>{/if}
									{if $userpermissions.tasks.del}
									<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$tasks[task].ID}\',\'managetask.php?action=del&amp;tid={$tasks[task].ID}&amp;id={$project.ID}\')');"  title="{#delete#}"></a>
									{/if}
								</td>
							</tr>

							<tr class="acc">
								<td colspan="5">
									<div class="accordion_toggle"></div>
									<div class="accordion_content">
										<div class="acc-in">
											<div class="message-in">
												{$tasks[task].text|nl2br}
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
			{if $tasklist.donetasknum > 0}
				<div id="done_2" class="doneblock">
					
					<table class="second-thead" cellpadding="0" cellspacing="0" border="0" onclick="blindtoggle('doneblock_{$project.ID}');toggleClass('donebutn_{$project.ID}','butn_link_active','butn_link');toggleClass('toggle-done-{$project.ID}','acc-toggle','acc-toggle-active');">
						<tr>
							<td class="a"></td>
							<td class="b"><span id="toggle-done-{$project.ID}" class="acc-toggle-active">{#donetasks#}</span></td>
							<td class="c"></td>
							<td class="days"></td>
							<td class="tools"></td>
						</tr>
					</table>

					<div class="toggleblock">
					
						<table cellpadding="0" cellspacing="0" border="0" id = "done_{$project.ID}">
							{section name=donetask loop=$donetasks}
								{if $smarty.section.donetask.index % 2 == 0}
								<tbody class="color-a" id="task_{$donetasks[donetask].ID}">
								{else}
								<tbody class="color-b" id="task_{$donetasks[donetask].ID}">
								{/if}
									<tr>
										<td class="a">{if $userpermissions.tasks.close}<a class="butn_checked" href="javascript:closeElement('task_{$donetasks[donetask].ID}','managetask.php?action=open&amp;tid={$donetasks[donetask].ID}&amp;id={$project.ID}');" title="{#open#}"></a>{/if}</td>
										<td class="b">
											<div class="toggle-in">
											<span class="acc-toggle" onclick="javascript:done_2.activate($$('#done_2 .accordion_toggle')[{$smarty.section.donetask.index}]);toggleAccordeon('done_{$project.ID}',this);"></span>
												<a href="managetask.php?action=showtask&amp;tid={$donetasks[donetask].ID}&amp;id={$donetasks[donetask].project}" title="{$donetasks[donetask].title}">
													{if $donetasks[donetask].title != ""}
													{$donetasks[donetask].title|truncate:30:"...":true}
													{else}
													{$donetasks[donetask].text|truncate:30:"...":true}
													{/if}
												</a>
											</div>
										</td>
										<td class="c"><a href="manageuser.php?action=profile&amp;tlid={$donetasks[donetask].user_id}&amp;id={$project.ID}">{$donetasks[donetask].user|truncate:23:"...":true}</a></td>
										<td class="days" style="text-align:right">{$donetasks[donetask].daysleft}&nbsp;&nbsp;</td>
										<td class="tools">
											{if $userpermissions.tasks.edit}
											<a class="tool_edit" href="managetask.php?action=editform&amp;tid={$donetasks[donetask].ID}&amp;id={$project.ID}" title="{#edit#}"></a>{/if}
											{if $userpermissions.tasks.del}
											<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$donetasks[donetask].ID}\',\'managetask.php?action=del&amp;tid={$donetasks[donetask].ID}&amp;id={$project.ID}\')');"  title="{#delete#}"></a>
											{/if}
										</td>
									</tr>

									<tr class="acc">
										<td colspan="5">
											<div class="accordion_toggle"></div>
											<div class="accordion_content">
												<div class="acc-in">
													<div class="message-in">
														{$donetasks[donetask].text|nl2br}
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							{/section} {*Tasks donetasks END*}
						</table>
						
					</div> {*toggleblock End*}
				</div> {*done_block End*}
			{/if} {*If if $tasklist.donetasknum > 0*}

		</div> {*smooth End*}

		{*Add Task*}
		<div class="tablemenue">
			<div class="tablemenue-in">
				{if $userpermissions.tasks.add}
					<a class="butn_link" href="javascript:blindtoggle('form_addtask');" id="add_butn_{$project.ID}" onclick="toggleClass('add_{$project.ID}','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_{$project.ID}','smooth','nosmooth');">{#addtask#}</a>
				{/if}
				{if $tasklist.donetasknum > 0}
					<a class="butn_link_active" href="javascript:blindtoggle('doneblock_{$project.ID}');" id="donebutn_{$project.ID}" onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done-{$project.ID}','acc-toggle','acc-toggle-active');">{#donetasks#}</a>
				{/if}
			</div>
		</div> {*Add Task End*}

	</div> {*nosmooth End*}
</div> {*block END*}

<div class="content-spacer"></div>

{literal}
	<script type = "text/javascript">
		var accord_1 = new accordion('acc_1');
		var done_2 = new accordion('done_2');
	</script>
{/literal}

</div> {*Tasks END*}
</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}