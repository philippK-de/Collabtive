{include file="header.tpl" jsload = "ajax"  jsload1 = "tinymce" }
{include file="tabsmenue-project.tpl" taskstab = "active"}

<div id="content-left">
<div id="content-left-in">
<div class="tasks" >
    <div class="infowin_left"
         id="systemMessage"
         data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png"
         data-text-deleted = "{#taskwasdeleted#}"
         data-text-edited = "{#taskwasedited#}"
         data-text-added = "{#taskwasadded#}"
         data-text-closed = "{#taskwasclosed#}"
         data-text-opened = "{#taskwasopened#}"
         data-text-assigned = "{#taskwasassigned#}"
         data-text-deassigned = "{#taskwasdeassigned#}"
         style="display:none">
    </div>

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

    <div id="blockTasks">
    {*Tasks*}
	{if $lists[0][0]}
		{section name=list loop=$lists}
			<div class="headline accordion_toggle" style="margin-top:5px;">
				<a href="javascript:void(0);" id="toggle-{$lists[list].ID}" class="win_block" onclick = "changeElements('a.win_block','win_none');toggleBlock('contentblock-{$lists[list].ID}');" style="z-index:1"></a>

				<div class="wintools" style="z-index:999;">
                    <div class="progress" id="progressacc_{$lists[list].ID}" style="display:none;width:22px;float:left">
                        <img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/loader-tasks.gif"/>
                    </div>
					{if $userpermissions.tasks.close}
						<a class="close" href="managetasklist.php?action=close&amp;tlid={$lists[list].ID}&amp;id={$project.ID}"><span>{#close#}</span></a>
					{/if}
					{if $userpermissions.tasks.edit}
						<a class="edit" href="managetasklist.php?action=editform&amp;tlid={$lists[list].ID}&amp;id={$project.ID}"><span>{#edit#}</span></a>
					{/if}
					{if $userpermissions.tasks.del}
						<a class="del" href="javascript:confirmit('{#confirmdel#}','managetasklist.php?action=del&amp;tlid={$lists[list].ID}&amp;id={$project.ID}');"><span>{#delete#}</span></a>
					{/if}
				</div>

				<h2>
					<a href="managetasklist.php?action=showtasklist&amp;id={$project.ID}&amp;tlid={$lists[list].ID}" title="{#tasklist#} {$lists[list].name}"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt="" />{$lists[list].name|truncate:70:"...":true}</a>
				</h2>
			</div>

			<div id="contentblock-{$lists[list].ID}" class="block accordion_content" style="overflow:hidden;">

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

							{*Color-Mix*}
                            {literal}
							<tbody v-for="task in items" class="alternateColors" id="task_{{task.ID}}" v-cloak>

								<tr v-bind:class="{ 'marker-late': item.islate, 'marker-today': item.istoday }">
									<td>
                                        {/literal}
										{if $userpermissions.tasks.close}
                                            {literal}
											<a class="butn_check" href="javascript:closeElement('task_{{task.ID}}','managetask.php?action=close&amp;tid={{*task.ID}}&amp;id={{*task.project}}');" title="{/literal}{#close#}"></a>
										{/if}
									</td>
                                    {literal}
									<td>
										<div class="toggle-in">
										<span class="acc-toggle" onclick="javascript:accord_{{task.liste}}.activate($$('#acc_{{task.liste}} .accordion_toggle')[{{$index}}]);toggleAccordeon('acc_{{task.liste}}',this);"></span>
											<a href="managetask.php?action=showtask&amp;tid={{task.ID}}&amp;id={{task].project}}" title="{{task.title}}">
												{{task.title}}
											</a>
										</div>
									</td>
									<td>
										<a v-for="user in task.users" href="manageuser.php?action=profile&amp;id={{user.ID}}">{{user.name}}</a>
									</td>
									<td style="text-align:right">{{task.daysleft}}&nbsp;&nbsp;</td>
									<td class="tools">
                                        {/literal}
										{if $userpermissions.tasks.edit}
                                        {literal}
											<a class="tool_edit" href="javascript:void(0);" onclick="change('managetask.php?action=editform&amp;tid={{task.ID}}&amp;id={{task.project}}','form_{{task.liste}}');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_{{task.liste}}');" title="{/literal}{#edit#}"></a>
										{/if}
										{if $userpermissions.tasks.del}
                                            {literal}
											<a class="tool_del" href="javascript:confirmDelete('{/literal}{#confirmdel#}{literal}','task_{{task.ID}}','managetask.php?action=del&amp;tid={{task.ID}}&amp;id={{task.project}}');"  title="{/literal}{#delete#}"></a>
										{/if}
									</td>
								</tr>

                                {literal}
								<tr class="acc">
									<td colspan="5">
										<div class="accordion_toggle"></div>
										<div class="accordion_content">
											<div class="acc-in">
												<div class="message-in">
													{{{task.text}}}
												</div>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
								<script type = "text/javascript">
									var accord_{/literal}{$lists[list].ID}{literal} = new accordion('contentblock-{/literal}{$lists[list].ID}{literal}',{
                                        bindOnclickHandler: false
                                    });
								</script>
							{/literal}

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
												<a class="tool_edit" href="javascript:void(0);" onclick="change('managetask.php?action=editform&amp;tid={$lists[list].oldtasks[oldtask].ID}&amp;id={$project.ID}','form_{$lists[list].ID}');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_{$lists[list].ID}');" title="{#edit#}"></a>
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
        {literal}

            <script type="text/javascript">
                var projectTasks = {
                    el: "acc_{/literal}{$lists[list].ID}{literal}",
                    itemType: "task",
                    url: "managetask.php?action=projectTasks&id={/literal}{$lists[list].ID}{literal}",
                    dependencies: []
                };
                var projectTasksView_{/literal}{$lists[list].ID}{literal} = createView(projectTasks);
            </script>
        {/literal}
      {/section}
    </div>
    {/if} {*if $lists[0][0]*}

{if !$lists[0][0] and !$oldlists[0][0]}

	<tbody class="color-a">
		<tr>
			<td></td>
			<td colspan="3" class="info">{#notasklists#}</td>
			<td class="tools"></td>
		</tr>
	</tbody>
</div><!-- content left end -->
{/if}
    <div class="content-spacer"></div>
</div> {*Tasks END*}
</div> {*content-left-in END*}
</div> {*content-left END*}
{* current tasklists end*}
{*right sidebar*}
{include file="sidebar-a.tpl"}
{if $oldlists[0][0]} {*only show the block if there are closed tasklists*} {*Done Tasklists*}
<div class="content-spacer"></div>
{*closed tasklists*}
<div id="content-left">
<div id="content-left-in">
<div class="tasks">
	<h1>{$projectname|truncate:45:"...":true}<span>/ {#donetasklists#}</span></h1>

	<div class="headline">
		<a href="javascript:void(0);" id="block-donelists_toggle" class="win_block" onclick = "toggleBlock('block-donelists');"></a>
		<h2>
			<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist-done.png" alt="" />
		</h2>
	</div>


{* Closed tasklists *}
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



	{literal}
		<script type = "text/javascript">
			var accord_donelists = new accordion('block-donelists');
		</script>
	{/literal}

</div> {*Tasks END*}
</div> {*content-left-in END*}
</div> {*content-left END*}

{/if} {*Done Tasklists End*}




<script type="text/javascript" src="include/js/views/projectTasks.min.js"></script>

{include file="footer.tpl"}