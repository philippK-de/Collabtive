{include file="header.tpl" jsload = "ajax" }
{include file="tabsmenue-project.tpl" timetab = "active"}

<div id="content-left">
<div id="content-left-in">
<div class="timetrack" id="projectTimetracker" >

	<div class="infowin_left" style = "display:none;" id = "systemmsg">
		{if $mode == "added"}
			<span class="info_in_green"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/timetracker.png" alt=""/>{#timetracker#} {#was#} {#added#}</span>
		{elseif $mode == "edited"}
			<span class="info_in_yellow"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/timetracker.png" alt=""/>{#timetracker#} {#was#} {#edited#}</span>
		{elseif $mode == "deleted"}
			<span class="info_in_red"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/timetracker.png" alt=""/>{#timetracker#} {#was#} {#deleted#}</span>
		{/if}
	</div>

	{literal}
		<script type = "text/javascript">
			systemMsg('systemmsg');
		</script>
	{/literal}

	<h1>{$projectname|truncate:45:"...":true}<span>/ {#timetracker#}</span></h1>

	<div class="timetrack">
		<div class="headline">
			<a href="javascript:void(0);" id="acc-tracker_toggle" class="win_block" onclick = "toggleBlock('acc-tracker');"></a>

			<div class="wintools">
                <div class="progress" id="progressprojectTimetracker" style="display:none;width:20px;float:left">
                    <img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/loader-messages.gif"/>
                </div>
				<div class="export-main">
					<a class="export"><span>{#export#}</span></a>
					<div class="export-in"  style="width:69px;left: -69px;"> {*at one item*}
						<a class="html" target="_blank" href="managetimetracker.php?action=projecthtml&amp;id={$project.ID}{if $start != "" and $end != ""}&amp;start={$start}&amp;end={$end}{/if}{if $usr > 0}&amp;usr={$usr}{/if}{if $task > 0}&amp;task={$task}{/if}{if $fproject|default > 0}&amp;project={$fproject|default}{/if}"><span>{#htmlexport#}</span></a>
						<a class="pdf" href="managetimetracker.php?action=projectpdf&amp;id={$project.ID}{if $start != "" and $end != ""}&amp;start={$start}&amp;end={$end}{/if}{if $usr > 0}&amp;usr={$usr}{/if}{if $task > 0}&amp;task={$task}{/if}{if $fproject|default > 0}&amp;project={$fproject|default}{/if}"><span>{#pdfexport#}</span></a>
						<a class="excel" href="managetimetracker.php?action=projectxls&amp;id={$project.ID}{if $start != "" and $end != ""}&amp;start={$start}&amp;end={$end}{/if}{if $usr > 0}&amp;usr={$usr}{/if}{if $task > 0}&amp;task={$task}{/if}{if $fproject|default > 0}&amp;project={$fproject|default}{/if}"><span>{#excelexport#}</span></a>
					</div>
				</div>

				<div class="toolwrapper">
					<a class="filter" href="javascript:blindtoggle('filter');" id="filter_report" onclick="toggleClass(this,'filter-active','filter');toggleClass('filter_butn','butn_link_active','butn_link');toggleClass('sm_report','smooth','nosmooth');"><span>{#filterreport#}</span></a>
				</div>
			</div>

			<h2>
				<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/timetracker.png" alt="" />{#report#}
			</h2>
		</div>

		<div class="block" id="acc_tracker" v-cloak>

		<div id = "filter" class="addmenue" style = "display:none;"> {*Filter Report*}
			{include file="filtertracker.tpl" }
		</div> {*Filter End*}

		<div class="nosmooth" id="sm_report">

			<table cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<th class="a"></th>
						<th class="b">{#user#}</th>
						<th class="cf">{#day#}</th>
						<th class="cf">{#started#}</th>
						<th class="cf">{#ended#}</th>
						<th class="e" style="text-align:right">{#hours#}&nbsp;&nbsp;</th>
						<th class="tools"></th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="6"></td>
					</tr>
				</tfoot>
                    {literal}
					<tbody v-for="track in items" class="alternateColors" id="track_{{track.ID}}">
						<tr>
							<td></td>
							<td>
								<div class="toggle-in">
								<span class="acc-toggle" onclick="javascript:accord_tracker.activate(document.querySelector('#acc_tracker_content{{$index}}'));"></span>
									<a href = "manageuser.php?action=profile&amp;id={{track.user}}" title="{{track.pname}}">
										{{track.uname}}
									</a>
								</div>
							</td>
							<td>{{*track.daystring}}</td>
							<td>{{*track.startstring}}</td>
							<td>{{*track.endstring}}</td>
							<td style="text-align:right">{{track.hours}}&nbsp;&nbsp;</td>
							<td class="tools">
							    {/literal}
								{if $userpermissions.timetracker.edit}
                                {literal}
									<a class="tool_edit" href="managetimetracker.php?action=editform&amp;tid={track.ID}}&amp;id={{track.project}}"
                                       title="{/literal}{#edit#}"></a>
								{/if}
								{if $userpermissions.timetracker.del}
									<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'track_{$tracker[track].ID}\',\'managetimetracker.php?action=del&amp;tid={$tracker[track].ID}&amp;id={$project.ID}\')');"  title="{#delete#}"></a>
								{/if}
							</td>
						</tr>
                        {literal}
						<tr class="acc">
							<td colspan="7">
								<div class="accordion_content" data-slide="{{$index}}" id="acc_tracker_content{{$index}}">
									<div class="acc-in">
											<strong v-if="track.comment">{/literal}{#comment#}:{literal}</strong><br />{{{*track.comment}}}

                                            <p v-if="track.tasks" class="tags-miles">
											<strong>{#task#}:</strong><br />
											<a href = "managetask.php?action=showtask&amp;tid={{*track.task}}&amp;id={{*track.project}}">{{*track.tname}</a>
											</p>
										</div>
								</div>
							</td>
						</tr>
					</tbody>
		    {/literal}
				<tbody class="tableend">
					<tr>
						<td></td>
						<td colspan="4"><strong>{#totalhours#}:</strong></td>
						<td style="text-align:right"><strong>{$totaltime|default}</strong>&nbsp;&nbsp;</td>
						<td class="tools"></td>
					</tr>
				</tbody>
			</table>
		</div> {*smooth End*}

		<div class="tablemenue">
			<div class="tablemenue-in">
				<a class="butn_link" href="javascript:blindtoggle('filter');" id="filter_butn" onclick="toggleClass('filter_report','filter-active','filter');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_report','smooth','nosmooth');">{#filterreport#}</a>
			</div>
		</div>
	</div> {*block END*}

</div> {*timetrack END*}

<div class="content-spacer"></div>

{literal}
    <script type="text/javascript" src="include/js/accordion.min.js"></script>
    <script type="text/javascript" src="include/js/views/timetrackerProject.min.js"></script>
	<script type = "text/javascript">
        projectTimetracker.url = projectTimetracker.url + "&id=" + {/literal}{$project.ID}{literal};
        projectTimetrackerView = createView(projectTimetracker);
		var accord_tracker = new accordion2('acc_tracker');
	</script>
{/literal}

</div> {*Timetracking END*}
</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}
