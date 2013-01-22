{include file="header.tpl" jsload = "ajax" jsload3 = "lightbox" showheader="no"}
				<div id = "milehead" style = "">
				<div class="table_head">
				<table cellpadding="0" cellspacing="0">
				<tr>
				<td class="a"></td>
				<td class="b">{#milestone#}</td>
				<td class="c">{#due#}</td>
				<td class="days">{#daysleft#}</td>
				<td class="e"></td>
				</tr>
				</table>
				</div>

				<div class="table_body">
				<div id = "accordion_miles" >
				<ul>
					{section name=stone loop=$milestones}

					{*Color-Mix*}
					{if $smarty.section.stone.index % 2 == 0}
					<li class="bg_a">
					{else}
					<li class="bg_b">
					{/if}

					<div class ="marker_all">

						<div id = "miles_{$milestones[stone].ID}" class="focus_off">

						<table class="line" cellpadding="0" cellspacing="0">
						<tr>
						<td class="a"><a class="butn_check" href="managemilestone.php?action=close&amp;mid={$milestones[stone].ID}&amp;id={$project.ID}" title="{#close#}"></a></td>
						<td class="b"></td>
						<td class="c">{$milestones[stone].fend}</td>
						<td class="days">{$milestones[stone].daysleft}</td>
						<td class="tools"><a class="tool_edit" href="managemilestone.php?action=editform&amp;mid={$milestones[stone].ID}&amp;id={$project.ID}"></a><a class="tool_del" href="managemilestone.php?action=del&amp;mid={$milestones[stone].ID}&amp;id={$project.ID}" title="{#delete#}"></a></td>
						</tr></table>

						<div class="link_in_toggle"><a href="managemilestone.php?action=showmilestone&amp;msid={$milestones[stone].ID}&amp;id={$project.ID}">{$milestones[stone].name|truncate:30:"...":true}</a></div>

						<div class="accordion_toggle">
						<a class="open" href="javascript:void(0);" onclick = "switchClass('miles_{$milestones[stone].ID}','focus_on','focus_off');"></a>
						</div> {*Accordeon_Toggle End*}

						<div class="accordion_content">
						<table class="description" cellpadding="0" cellspacing="0">
						<tr valign="top">
						<td class="a"></td>
						<td class="descript">
							{$milestones[stone].desc}

							{section name=task loop=$milestones[stone].tasks}
							{if  $smarty.section.task.first == TRUE}
							<br/><br/><b>{#tasklists#}:</b>
							{/if}
							<a href  = "managetasklist.php?action=showtasklist&amp;tlid={$milestones[stone].tasks[task].ID}&amp;id={$project.ID}">{$milestones[stone].tasks[task].name}</a>
							{if  $smarty.section.task.last == FALSE},{/if}
							{/section}

							{section name=msg loop=$milestones[stone].messages}
							{if  $smarty.section.msg.first == TRUE}
							<br/><b>{#messages#}:</b>
							{/if}
							<a href  = "managemessage.php?action=showmessage&amp;mid={$milestones[stone].messages[msg].ID}&amp;id={$project.ID}">{$milestones[stone].messages[msg].title}</a>
							{if  $smarty.section.msg.last == FALSE},{/if}
							{/section}

						</td>
						</tr>
						</table>
						</div> {*Accordion_Content End*}
						</div> {*Focus End*}
						</div> {*Marker End*}

					</li>
					{/section}
				</ul>
				</div> {*Accordion End*}
				</div> {*Table_Body End*}



				<div class="clear_both"></div> {*required ... do not delete this row*}

			</div>{*milehead end*}


			{literal}
<script type = "text/javascript">try{
	var accord_miles = new accordion('accordion_miles');
	}
	catch(e){}
</script>
{/literal}

