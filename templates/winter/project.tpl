{include file="header.tpl" jsload = "ajax" stage = "project" jsload1 = "tinymce"}

{include file="tabsmenue-project.tpl" projecttab = "active"}
<div id="content-left">
<div id="content-left-in">
<div class="projects">


	<div class="infowin_left" style = "display:none;" id = "systemmsg">
		{if $mode == "edited"}
		<span class="info_in_yellow"><img src="templates/standard/images/symbols/projects.png" alt=""/>{#projectwasedited#}</span>
		{elseif $mode == "timeadded"}
		<span class="info_in_green"><img src="templates/standard/images/symbols/timetracker.png" alt=""/>{#timetrackeradded#}</span>
		{/if}
	</div>
	{literal}
	<script type = "text/javascript">
		systemMsg('systemmsg');
	 </script>
	{/literal}

<h1>{$project.name|truncate:45:"...":true}<span>/ {#overview#}</span></h1>

	<div class="statuswrapper">
			<ul>
				{if $userpermissions.projects.close}
			        {if $project.status == 1}
					    <li class="link" id = "closetoggle"><a class="close" href="javascript:closeElement('closetoggle','manageproject.php?action=close&amp;id={$project.ID}');" title="{#close#}"></a></li>
					{else}
					<li class="link" id = "closetoggle"><a class="closed" href="manageproject.php?action=open&amp;id={$project.ID}" title="{#open#}"></a></li>
				    {/if}
				{/if}
				{if $userpermissions.projects.edit}
				<li class="link"><a class="edit" href="javascript:void(0);"  id="edit_butn" onclick="blindtoggle('form_edit');toggleClass(this,'edit-active','edit');toggleClass('sm_project','smooth','nosmooth');toggleClass('sm_project_desc','smooth','nosmooth');" title="{#edit#}"></a></li>
				{/if}
				{if $project.desc}
				<li class="link" onclick="blindtoggle('descript');toggleClass('desctoggle','desc_active','desc');"><a class="desc" id="desctoggle" href="#" title="{#open#}">{#description#}</a></li>
				{/if}
				{if $userpermissions.projects.del}
				{if $project.budget}
				<li><a>{#budget#}: {$project.budget}</a></li>
				{/if}{/if}
				
				{if $project.daysleft != "" || $project.daysleft == "0"}
					<li {if $project.daysleft < 0}class="red"{elseif $project.daysleft == "0"}class="green"{/if}><a>{$project.daysleft} {#daysleft#}</a></li>
				{/if}
			</ul>

			<div class="status">
				{$done}%
				<div class="statusbar"><div class="complete" id = "completed" style="width:0%;"></div></div>
			</div>
	</div>

			{*Edit Task*}
			{if $userpermissions.projects.edit}
				<div id = "form_edit" class="addmenue" style = "display:none;clear:both;">
					<div class="content-spacer"></div>
					{include file="editform.tpl" showhtml="no" }
				</div>
			{/if}

<div class="nosmooth" id="sm_project_desc">
		<div id="descript" class="descript" style="display:none;">
		<div class="content-spacer"></div>
			{$project.desc}
		</div>
</div>

</div> {*Projects END*}
<div class="content-spacer"></div>

<div class="nosmooth" id="sm_project">


{*Milestones*}
<div class="miles" >
			<div class="headline">
				<a href="javascript:void(0);" id="milehead_toggle" class="win_block" onclick = "toggleBlock('milehead');"></a>

				<div class="wintools">
					<!-- <div class="export-main">
						<a class="export"><span>{#export#}</span></a>
						<div class="export-in"  style="width:23px;left: -23px;"> {*at one item*}
							<a class="ical" href="managetask.php?action=ical"><span>{#icalexport#}</span></a>
						</div>
					</div>-->
					<div class = "progress" id = "progress" style = "display:none;">
						<img src = "templates/standard/images/symbols/loader-cal.gif" />
					</div>
				</div>


				<h2>
					<img src="./templates/standard/images/symbols/miles.png" alt="" />{#calendar#}
				</h2>

			</div>


			<div class="block" id="milehead" style = "{$tmilestyle}">
				<div id = "thecal" class="bigcal"></div>
			</div> {*block End*}
</div>	{*miles End*}
<div class="content-spacer"></div>
{*Milestons END*}




{*Timetracker*}
{if $userpermissions.timetracker.add}
<div class="timetrack">
	<div class="headline">
		<a href="javascript:void(0);" id="trackerhead_toggle" class="win_block" onclick = "toggleBlock('trackerhead');"></a>

		<!-- Export-block
		<div class="wintools">
			<div class="export-main">
				<a class="export"><span>{#export#}</span></a>
				<div class="export-in"  style="width:23px;left: -23px;"> {*at one item*}
					<a class="ical" href="managetask.php?action=ical"><span>{#icalexport#}</span></a>
				</div>
			</div>
		</div>
		-->

		<h2>
			<a href="managetimetracker.php?action=showproject&amp;id={$project.ID}" title="{#timetracker#}"><img src="./templates/standard/images/symbols/timetracker.png" alt="" />{#timetracker#}</a>
		</h2>
	</div>

	<div class="block" id="trackerhead" style = "{$trackerstyle}">
		<div id = "trackerform" class="addmenue">
			{include file="addtimetracker.tpl" }
		</div>
		<div class="tablemenue"></div>
	</div> {*block end*}
</div> {*timetrack end*}

<div class="content-spacer"></div>
{/if}
{*Timetracker End*}


{*Activity Log*}
<div class="neutral">
	{include file="log.tpl" }
</div>
{*Activity Log End*}


</div> {*nosmooth End*}


{literal}
	<script type = "text/javascript">
	changeshow('manageproject.php?action=cal&id={/literal}{$project.ID}{literal}','thecal','progress');
	</script>
{/literal}

</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl" showcloud="1"}

{literal}
	<script type = "text/javascript">
		Event.observe(window,"load",function()
		{
			new Effect.Morph('completed', {
				style: 'width:{/literal}{$done}{literal}%',
				duration: 4.0
			});
		});
	</script>
{/literal}

{include file="footer.tpl"}