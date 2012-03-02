{include file="header.tpl" jsload = "ajax" jsload1 = "tinymce"}


{include file="tabsmenue-desk.tpl" projectstab = "active"}

<div id="content-left">
<div id="content-left-in">
<div class="projects">

	{if $newproject == 1}
	{#projectwasadded#}
	{/if}
	{if $editproject == 1}
	{#projectwasedited#}
	{/if}
	{if $delproject == 1}
	{#projectwasdeleted#}
	{/if}
	{if $openproject == 1}
	{#projectwasopened#}
	{/if}
	{if $closeproject == 1}
	{#projectwasclosed#}
	{/if}
	{if $assignproject == 1}
	{#projectwasassigned#}
	{/if}
	{if $deassignproject == 1}
	{#projectwasdeassigned#}
	{/if}

<h1>{#myprojects#}</h1>


{*Projects*}


			<div class="headline">
				<a href="javascript:void(0);" id="acc-projects_toggle" class="win_none" onclick = "toggleBlock('acc-projects');"></a>

					{if $userpermissions.projects.add}
					<div class="wintools">
						<a class="add" href="javascript:blindtoggle('form_addmyproject');" id="add_myprojects" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_myprojects','butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');"><span>{#addproject#}</span></a>
					</div>
					{/if}

				<h2>
					<img src="./templates/standard/images/symbols/projects.png" alt="" />{#openprojects#}
				</h2>
			</div>

			<div class="block" id="acc-projects">

			{*Add Project*}
				<div id = "form_addmyproject" class="addmenue" style = "display:none;">
				{include file="addproject.tpl" myprojects="1"}
				</div>

			<div class="nosmooth" id="sm_myprojects">
				<table cellpadding="0" cellspacing="0" border="0">

					<thead>
						<tr>
							<th class="a"></th>
							<th class="b">{#project#}</th>
							<th class="c">{#done#}</th>
							<th class="d" style="text-align:right">{#daysleft#}&nbsp;&nbsp;</th>
							<th class="tools"></th>
						</tr>
					</thead>

					<tfoot>
						<tr>
							<td colspan="5"></td>
						</tr>
					</tfoot>

					{section name=project loop=$myprojects}

					{*Color-Mix*}
					{if $smarty.section.project.index % 2 == 0}
					<tbody class="color-a" id="proj_{$myprojects[project].ID}">
					{else}
					<tbody class="color-b" id="proj_{$myprojects[project].ID}">
					{/if}
						<tr {if $myprojects[project].daysleft < 0} class="marker-late"{elseif $myprojects[project].daysleft == 0} class="marker-today"{/if}>
							<td>{if $userpermissions.projects.add}<a class="butn_check" href="javascript:closeElement('proj_{$myprojects[project].ID}','manageproject.php?action=close&amp;id={$myprojects[project].ID}');" title="{#close#}"></a>{/if}</td>
							<td>
								<div class="toggle-in">
								<span class="acc-toggle" onclick="javascript:accord_projects.activate($$('#acc-projects .accordion_toggle')[{$smarty.section.project.index}]);toggleAccordeon('acc-projects',this);"></span>
									<a href="manageproject.php?action=showproject&amp;id={$myprojects[project].ID}" title="{$myprojects[project].name}">
										{if $myprojects[project].name != ""}
										{$myprojects[project].name|truncate:30:"...":true}
										{else}
										{$myprojects[project].desc|truncate:30:"...":true}
										{/if}
									</a>
								</div>
							</td>
							<td><div class="statusbar_b"><div class="complete" id = "completed" style="width:{$myprojects[project].done}%;"></div></div><span>{$myprojects[project].done}%</span></td>
							<td style="text-align:right">{$myprojects[project].daysleft}&nbsp;&nbsp;</td>
							<td class="tools">
							     {if $userpermissions.projects.edit}
								<a class="tool_edit" href="manageproject.php?action=editform&amp;id={$myprojects[project].ID}" title="{#edit#}"></a>
								{/if}
								{if $userpermissions.projects.del}
								<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{$myprojects[project].ID}\',\'manageproject.php?action=del&amp;id={$myprojects[project].ID}\')');"  title="{#delete#}"></a>
								{/if}
							</td>
						</tr>

						<tr class="acc">
							<td colspan="5">
								<div class="accordion_toggle"></div>
								<div class="accordion_content">
									<div class="acc-in">
										<div class="message-in">
											{$myprojects[project].desc}
										</div>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
					{/section}


				</table>

{*Projects End*}



{*Doneprojects*}
				<div id="doneblock" class="doneblock" style="display: none;">
				<table class="second-thead" cellpadding="0" cellspacing="0" border="0" onclick="blindtoggle('doneblock');toggleClass('donebutn','butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">

						<tr>
							<td class="a"></td>
							<td class="b"><span id="toggle-done" class="acc-toggle">{#closedprojects#}</span></td>
							<td class="c"></td>
							<td class="d"></td>
							<td class="tools"></td>
						</tr>

				</table>


				<div class="toggleblock">
					<table cellpadding="0" cellspacing="0" border="0" id="acc-oldprojects">
					{section name=clopro loop=$oldprojects}

					{*Color-Mix*}
					{if $smarty.section.clopro.index % 2 == 0}
					<tbody class="color-a" id="proj_{$oldprojects[clopro].ID}">
					{else}
					<tbody class="color-b" id="proj_{$oldprojects[clopro].ID}">
					{/if}
						<tr>
							<td class="a">{if $userpermissions.projects.add}<a class="butn_checked" href="manageproject.php?action=open&amp;id={$oldprojects[clopro].ID}" title="{#open#}"></a>{/if}</td>
							<td class="b">
								<div class="toggle-in">
								<span class="acc-toggle" onclick="javascript:accord_oldprojects.activate($$('#acc-oldprojects .accordion_toggle')[{$smarty.section.clopro.index}]);toggleAccordeon('acc-oldprojects',this);"></span>
									<a href="manageproject.php?action=showproject&amp;id={$oldprojects[clopro].ID}" title="{$oldprojects[clopro].name}">
										{if $oldprojects[clopro].name != ""}
										{$oldprojects[clopro].name|truncate:30:"...":true}
										{else}
										{$oldprojects[clopro].desc|truncate:30:"...":true}
										{/if}
									</a>
								</div>
							</td>
							<td class="c"></td>
							<td class="d" style="text-align:right">{$oldprojects[clopro].daysleft}&nbsp;&nbsp;</td>
							<td class="tools">
								{if $userpermissions.projects.edit}
								<a class="tool_edit" href="manageproject.php?action=editform&amp;id={$oldprojects[clopro].ID}" title="{#edit#}"></a>
								{/if}
								{if $userpermissions.projects.del}
								<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{$oldprojects[clopro].ID}\',\'manageproject.php?action=del&amp;id={$oldprojects[clopro].ID}\')');"  title="{#delete#}" ></a>
								{/if}
							</td>
						</tr>

						<tr class="acc">
							<td colspan="5">
								<div class="accordion_toggle"></div>
								<div class="accordion_content">
									<div class="acc-in">
										{$oldprojects[clopro].desc}
									</div>
								</div>
							</td>
						</tr>
					</tbody>
					{/section}


				</table>
				</div> {*toggleblock End*}
				</div> {*doneblock end*}

			</div> {*smooth end*}

				<div class="tablemenue">
					<div class="tablemenue-in">
						{if $userpermissions.projects.add}
						<a class="butn_link" href="javascript:blindtoggle('form_addmyproject');" id="add_butn_myprojects" onclick="toggleClass('add_myprojects','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');">{#addproject#}</a>
						{/if}

						<a class="butn_link" href="javascript:blindtoggle('doneblock');" id="donebutn" onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">{#closedprojects#}</a>

					</div>
				</div>

		</div> {*block END*}


{*Doneprojects End*}



{literal}
	<script type = "text/javascript">
		var accord_projects = new accordion('acc-projects');
		var accord_oldprojects = new accordion('acc-oldprojects');
	</script>
{/literal}


<div class="content-spacer"></div>
</div> {*projects END*}
</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}