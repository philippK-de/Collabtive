{include file="header.tpl" jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-desk.tpl" projectstab="active"}
<div class="row-fluid">
	<div id="content-left" class="span9">
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
			<div class="headline navbar navbar-inverse clearfix">
				<div class="navbar-inner">
					<div class="container">
						<a class="brand" href="myprojects.php" title="{#openprojects#}">{#openprojects#}</a>
						<ul class="nav pull-right">
							<li class="divider-vertical"></li>
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">
									<i class="icon-cog big"></i>
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									{if $userpermissions.projects.add}
									<li>
										<a class="add" href="javascript:blindtoggle('form_addmyproject');" id="add_myprojects" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_myprojects','butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');"><i class="icon-pencil"></i> {#addproject#}</a>
									</li>
									{/if}
									<li>
										<a href="javascript:void(0);" id="acc-oldprojects_toggle" class="win_none" onclick="toggleBlock('acc-oldprojects');"><i class="icon-check"></i> {#closedprojects#}</a>
									</li>
									<li>
										<a href="javascript:void(0);" id="acc-projects_toggle" class="win_none" onclick="toggleBlock('acc-projects');"><i class="icon-resize-vertical"></i> {#toggle#}</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="block" id="acc-projects">
			{*Add Project*}
			<div id="form_addmyproject" class="addmenue" style="display:none;">
				{include file="addproject.tpl" myprojects="1"}
			</div>
			<div class="nosmooth" id="sm_myprojects">
				<table id="desktopprojects" class="table table-bordered table-stripped table-hover">
					<thead>
						<tr>
							<th class="a"></th>
							<th class="b">{#project#}</th>
							<th class="c">{#done#}</th>
							<th class="d">{#daysleft#}&nbsp;&nbsp;</th>
							<th class="tools"></th>
						</tr>
					</thead>
					{section name=project loop=$myprojects}
					{*Color-Mix*}	
					{if $smarty.section.project.index % 2 == 0}
					<tbody class="color-a" id="proj_{$myprojects[project].ID}" rel="{$myprojects[project].ID},{$myprojects[project].name},{$myprojects[project].daysleft},0,0,{$myprojects[project].done}">
					{else}
					<tbody class="color-b" id="proj_{$myprojects[project].ID}" rel="{$myprojects[project].ID},{$myprojects[project].name},{$myprojects[project].daysleft},0,0,{$myprojects[project].done}">
					{/if}
						<tr id="{$myprojects[project].ID}"{if $myprojects[project].daysleft < 0 && $myprojects[project].daysleft != ''} class="marker-late error"{elseif $myprojects[project].daysleft == 0} class="marker-today warning"{/if}> 
							<td>
							{if $userpermissions.projects.close}
								<a class="butn_check" href="javascript:closeElement('proj_{$myprojects[project].ID}','manageproject.php?action=close&amp;id={$myprojects[project].ID}');" title="{#close#}">{#close#}</a>
							{/if}
							</td>
							<td>
								<a href="manageproject.php?action=showproject&amp;id={$myprojects[project].ID}" title="{$myprojects[project].name}">{$myprojects[project].name|strip_tags|truncate:35:"...":true}</a>
								{if !empty($myprojects[project].desc)}
								<a href="#open{$myprojects[project].ID}" class="btn btn-mini btn-info pull-right" data-toggle="modal" title="Information"><i class="icon-info-sign icon-white"></i></a>
									<div class="modal hide fade" id="open{$myprojects[project].ID}" tabindex="-1" role="dialog" aria-labelledby="open{$myprojects[project].name}Label" aria-hidden="true">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h3>{$myprojects[project].name}</h3>
										</div>
										<div class="modal-body">
											{$myprojects[project].desc|strip_tags|truncate:'200'}
										</div>
									</div>
								{/if}
							</td>
							<td>
								<a href="#" rel="tooltip" data-placement="top" class="tip" title="{$myprojects[project].done}%">
								<div class="progress{if $myprojects[project].done > 50} progress-success{elseif $myprojects[project].done < 50 && $myprojects[project].done > 20} progress-warning{elseif $myprojects[project].done < 20} progress-danger{/if} progress-striped active" title="{$myprojects[project].done}%" style="margin: 0;">
									<div class="bar" style="width: {$myprojects[project].done}%;"></div>
								</div>
								</a>
							</td>
							<td style="text-align:right">
								<span class="badge{if $myprojects[project].daysleft < 0} badge-important{elseif $myprojects[project].daysleft == 0} badge-warning{else} badge-success{/if}">{$myprojects[project].daysleft}</span>
							</td>	
							<td class="tools">
							{if $userpermissions.projects.edit}
								<a class="tool_edit" href="manageproject.php?action=editform&amp;id={$myprojects[project].ID}" title="{#edit#}" ><i class="icon-edit"></i></a>
							{/if}
							{if $userpermissions.projects.del}
								<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{$myprojects[project].ID}\',\'manageproject.php?action=del&amp;id={$myprojects[project].ID}\')');"  title="{#delete#}"><i class="icon-trash"></i></a>
							{/if}
							</td>
						</tr>
					</tbody>
					{/section}
				</table>
			{*Projects End*}
			</div> {*block END*}
			{*Doneprojects*}
			<div id="acc-oldprojects" class="doneblock" style="display: none;">
				<div class="doneblock navbar clearfix">
					<div class="navbar-inner">
						<div class="container">
							<a class="brand" href="myprojects.php" title="{#myprojects#}">{#closedprojects#}</a>
						</div>
					</div>
				</div>				
				<table class="second-thead table table-bordered table-striped table-hover" onclick="blindtoggle('doneblock');toggleClass('donebutn','butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">
					<thead>
						<tr>
							<th class="a"></th>
							<th class="b">{#project#}</th>
							<th class="d">{#daysleft#}&nbsp;&nbsp;</th>
							<th class="tools"></th>
						</tr>
					</thead>
					{section name=clopro loop=$oldprojects}
					{if $smarty.section.clopro.index % 2 == 0}
					<tbody class="color-a" id="proj_{$oldprojects[clopro].ID}">
					{else}
					<tbody class="color-b" id="proj_{$oldprojects[clopro].ID}">
					{/if}
						<tr>
							<td class="a">{if $userpermissions.projects.add}<a class="butn_checked" href="manageproject.php?action=open&amp;id={$oldprojects[clopro].ID}" title="{#open#}">{#open#}</a>{/if}</td>
							<td class="b">
									<a href="manageproject.php?action=showproject&amp;id={$oldprojects[clopro].ID}" title="{$oldprojects[clopro].name}">
										{if $oldprojects[clopro].name != ""}
										{$oldprojects[clopro].name|strip_tags|truncate:30:"...":true}
										{else}
										{$oldprojects[clopro].desc|strip_tags|truncate:30:"...":true}
										{/if}
									</a>
									{if !empty($oldprojects[clopro].desc)}									
									<a href="#old{$oldprojects[clopro].ID}" class="btn btn-mini btn-info pull-right" data-toggle="modal" title="Information"><i class="icon-info-sign icon-white"></i></a>
									<div class="modal hide fade" id="old{$oldprojects[clopro].ID}" tabindex="-1" role="dialog" aria-labelledby="old{$oldprojects[clopro].name}Label" aria-hidden="true">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h3>{$oldprojects[clopro].name}</h3>
										</div>
										<div class="modal-body">
											{$oldprojects[clopro].desc|strip_tags|truncate:200:"...":true}
										</div>
									</div>
									{/if}
							</td>
							<td class="d" style="text-align:right">
								<span class="badge{if $oldprojects[clopro].daysleft < 0} badge-important{elseif $oldprojects[clopro].daysleft == 0} badge-warning{else} badge-success{/if}">{$oldprojects[clopro].daysleft}</span>
							</td>
							<td class="tools">
							{if $userpermissions.projects.edit}
								<a class="tool_edit" href="manageproject.php?action=editform&amp;id={$oldprojects[clopro].ID}" title="{#edit#}"><i class="icon-edit"></i></a>
							{/if}
							{if $userpermissions.projects.del}
								<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{$oldprojects[clopro].ID}\',\'manageproject.php?action=del&amp;id={$oldprojects[clopro].ID}\')');" title="{#delete#}" ><i class="icon-trash"></i></a>
							{/if}
							</td>
						</tr>
					</tbody>
					{/section}
				</table>
				</div> {*doneblock end*}
			</div> {*smooth end*}
{literal}
	<script type="text/javascript">
		var accord_projects = new accordion('acc-projects');
		var accord_oldprojects = new accordion('acc-oldprojects');
	</script>
{/literal}
		</div> {*projects END*}
	</div>
	{include file="sidebar-a.tpl"}
</div>
{include file="footer.tpl"}