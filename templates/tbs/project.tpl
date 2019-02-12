{include file="header.tpl" jsload="ajax" stage="project" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" projecttab="active"}
<div class="row-fluid">
	<div id="content-left" class="span9">
		<div class="projects">
			<div class="infowin_left" style="display:none;" id="systemmsg">
			{if $mode == "edited"}
				<span class="info_in_yellow"><img src="templates/standard/images/symbols/projects.png" alt=""/>{#projectwasedited#}</span>
			{elseif $mode == "timeadded"}
				<span class="info_in_green"><img src="templates/standard/images/symbols/timetracker.png" alt=""/>{#timetrackeradded#}</span>
			{/if}
			</div>
{literal}
<script type="text/javascript">
	systemMsg('systemmsg');
</script>
{/literal}
			<div class="navbar navbar-inverse">
				<div class="navbar-inner clerafix">
					<a class="brand" href="#">{$project.name|truncate:45:"...":true} <small>{#overview#}</small></a>
					<ul class="nav pull-right">
						<li class="divider-vertical"></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="icon-cog big"></i>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
							{if $userpermissions.projects.close}
			        			{if $project.status == 1}
								<li id="closetoggle"><a href="javascript:closeElement('closetoggle','manageproject.php?action=close&amp;id={$project.ID}');" title="{#close#}"><i class="icon-remove"></i> {#close#}</a></li>
								{else}
								<li id="closetoggle"><a class="closed" href="manageproject.php?action=open&amp;id={$project.ID}" title="{#open#}"><i class="icon-ok"></i> {#open#}</a></li>
				   	 			{/if}
							{/if}
							{if $userpermissions.projects.edit}
								<li ><a class="edit" href="javascript:void(0);"  id="edit_butn" onclick="blindtoggle('form_edit');toggleClass(this,'edit-active','edit');toggleClass('sm_project','smooth','nosmooth');toggleClass('sm_project_desc','smooth','nosmooth');" title="{#edit#}"><i class="icon-pencil"></i> {#edit#}</a></li>
							{/if}
							{if $project.desc}
								<li onclick="blindtoggle('descript');toggleClass('desctoggle','desc_active','desc');"><a class="desc" id="desctoggle" href="#" title="{#open#}"><i class="icon-info-sign"></i> {#description#}</a></li>
							{/if}
							</ul>
						</li>
					</ul>
				</div>
			</div>			

				<div class="status clearfix">
					{if $project.daysleft != "" || $project.daysleft == "0"}
						<span class="label label-{if $project.daysleft < 0}important{else}success{/if} huge">{$project.daysleft} {#daysleft#}</span>
					{/if}
					{if $userpermissions.projects.del}
						{if $project.budget}
							<span class="label label-info huge">{#budget#}: {$project.budget} &euro;</span>
						{/if}
					{/if}
					<div class="span3 pull-right">
					<div class="progress progress-striped">
						<div class="bar" id="completed" style="width:{$done}%;"></div>
					</div>
					</div>
				</div>

			{*Edit Task*}
			{if $userpermissions.projects.edit}
				<div id="form_edit" class="addmenue" style="display:none;clear:both;">
					{include file="editform.tpl" showhtml="no" }
				</div>
			{/if}

			<div class="nosmooth" id="sm_project_desc">
				<div id="descript" class="descript well" style="display:none;">
					{$project.desc}
				</div>
			</div>
		</div> {*Projects END*}
		
		<div class="tabbable tabs-left">
  			<ul class="nav nav-tabs">
   				<li class="active"><a href="#tab1" data-toggle="tab">{#activity#}</a></li> 				
  				<li><a href="#tab2" data-toggle="tab">{#calendar#}</a></li>
  				<li><a href="#tab3" data-toggle="tab">{#timetracker#}</a></li>
  			</ul>
  			<div class="tab-content">
  			<div class="tab-pane active" id="tab1">
			{*Activity Log*}
				<div class="neutral">
					{include file="log.tpl" }
				</div>
			{*Activity Log End*}
			</div>
  			<div class="tab-pane" id="tab2">
			{*Milestones*}
			<div class="miles">
				<div class="headline navbar clearfix">
					<div class="navbar-inner">
						<div class="container">
							<a href="#" class="brand" title="{#calendar#}">{#calendar#}</a>
						</div>
					</div>
				</div>
				<div class="block" id="milehead" style="{$tmilestyle}">
					<div id="progress"></div>
					<div id="thecal" class="bigcal"></div>
				</div> {*block End*}
			</div>	{*miles End*}
			</div>
			<div class="tab-pane" id="tab3">
			{*Timetracker*}
			{if $userpermissions.timetracker.add}
			<div class="timetrack">
				<div class="block" id="trackerhead" style="{$trackerstyle}">
					<div id="trackerform" class="addmenue">
						{include file="addtimetracker.tpl" }
					</div>
					<div class="tablemenue"></div>
				</div> {*block end*}
			</div> {*timetrack end*}
			{/if}
			{*Timetracker End*}
			</div>
			</div>
		</div>
{literal}
<script type="text/javascript">
	changeshow('manageproject.php?action=cal&id={/literal}{$project.ID}{literal}','thecal','progress');
</script>
{/literal}
	</div> 
{include file="sidebar-a.tpl" showcloud="1"}
</div>
{include file="footer.tpl"}