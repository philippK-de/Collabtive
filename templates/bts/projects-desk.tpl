{*Projects*}
{if $projectnum > 0}
<div class="projects">
	<div class="headline navbar navbar-inverse clearfix">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="myprojects.php" title="{#myprojects#}">{#myprojects#}</a>
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
									<a class="add" href="javascript:blindtoggle('form_addmyproject');" id="add_myprojects"><i class="icon-pencil"></i> {#addproject#}</a>
								</li>
								{/if}
								<li>
									<a href="javascript:void(0);" id="projecthead_toggle" class="{$actbar}" onclick="toggleBlock('projecthead');"><i class="icon-resize-vertical"></i> {#toggle#}</a>
								</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="block" id="projecthead" style="{$projectstyle}">
	{*Add Project*}
	<div id="form_addmyproject" class="addmenue" style="display:none;">
		{include file="addproject.tpl" myprojects="1"}
	</div>
	<table id="desktopprojects" class="table table-bordered table-hover">
		<thead>
			<tr>
				<th class="a"></th>
				<th class="b" style="cursor:pointer;" onclick = "sortBlock('desktopprojects','');">{#project#}</th>
				<th class="c" style="cursor:pointer" onclick = "sortBlock('desktopprojects','done');">{#done#}</th>
				<th class="d" style="text-align:right" onclick = "sortBlock('desktopprojects','daysleft');">{#daysleft#}&nbsp;&nbsp;</th>
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
	</div> {*block END*}
</div> {*projects END*}
{/if}{*Projects End*}