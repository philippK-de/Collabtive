{include file="header.tpl" jsload = "ajax" jsload3 = "lightbox" showheader="no"}

				<table id="timeline" class="timeline" cellpadding="0" cellspacing="1">
				<tr class="head">
				{section name = day loop=$timestr}
				<td>{$timestr[day]}</td>
				{/section}
				</tr>

				<tr class="content" valign="top">
					{section name=tag loop=$timeline1}
					<td {if $timeline1[tag].tagstr == $today}class="today"{else}class="{cycle values="second,"}"{/if}><span style="white-space:nowrap;">{$timeline1[tag].tagstr}</span>
					<p>{if $timeline1[tag].milestone.ID > 0}<a id = "link{$timeline1[tag].milestone.ID}" href="managemilestone.php?action=showmilestone&amp;msid={$timeline1[tag].milestone.ID}&amp;id={$project.ID}">{$timeline1[tag].milestone.name|truncate:30:"...":true}</a><br/><span style="display:none;" class="tooltip" id = "tip{$timeline1[tag].milestone.ID}">{$timeline1[tag].milestone.desc|nl2br}</span>
						{literal}
				<script type = "text/javascript">
				new Tooltip('link{/literal}{$timeline1[tag].milestone.ID}{literal}','tip{/literal}{$timeline1[tag].milestone.ID}{literal}');
				</script>
				{/literal}
					{/if}</p></td>

					{/section}
					</tr>
				<tr class="content" valign="top">
					{section name=day loop=$timeline2}
					<td {if $timeline2[day].tagstr == $today}class="today"{else}class="{cycle values=",second"}"{/if}><span style="white-space:nowrap;">{$timeline2[day].tagstr}</span>
				<p>{if $timeline2[day].milestone.ID > 0}<a id = "link{$timeline2[day].milestone.ID}" href="managemilestone.php?action=showmilestone&amp;msid={$timeline2[day].milestone.ID}&amp;id={$project.ID}">{$timeline2[day].milestone.name|truncate:30:"...":true}</a><br/><span style="display:none;" class="tooltip" id = "tip{$timeline2[day].milestone.ID}">{$timeline2[day].milestone.desc|nl2br}</span>
					{literal}
				<script type = "text/javascript">
				new Tooltip('link{/literal}{$timeline2[day].milestone.ID}{literal}','tip{/literal}{$timeline2[day].milestone.ID}{literal}');
				</script>
				{/literal}
				{/if}</p></td>

					{/section}
				</tr>
				<tr class="content" valign="top">
					{section name=day loop=$timeline3}
					<td {if $timeline3[day].tagstr == $today}class="today"{else}class="{cycle values=",second"}"{/if}><span style="white-space:nowrap;">{$timeline3[day].tagstr}</span>
							<p>{if $timeline3[day].milestone.ID > 0}<a id = "link{$timeline3[day].milestone.ID}" href="managemilestone.php?action=showmilestone&amp;msid={$timeline3[day].milestone.ID}&amp;id={$project.ID}">{$timeline3[day].milestone.name|truncate:30:"...":true}</a><br/><span style="display:none;" class="tooltip" id = "tip{$timeline3[day].milestone.ID}">{$timeline3[day].milestone.desc|nl2br}</span>
									{literal}
				<script type = "text/javascript">
				new Tooltip('link{/literal}{$timeline3[day].milestone.ID}{literal}','tip{/literal}{$timeline3[day].milestone.ID}{literal}');
				</script>
				{/literal}
							{/if}</p></td>

					{/section}
				</tr>
				<tr class="content" valign="top">
					{section name=day loop=$timeline4}
					<td {if $timeline4[day].tagstr == $today}class="today"{else}class="{cycle values=",second"}"{/if}><span style="white-space:nowrap;">{$timeline4[day].tagstr}</span>
							<p>{if $timeline4[day].milestone.ID > 0}<a id = "link{$timeline4[day].milestone.ID}" href="managemilestone.php?action=showmilestone&amp;msid={$timeline4[day].milestone.ID}&amp;id={$project.ID}">{$timeline4[day].milestone.name|truncate:30:"...":true}</a><br/><span style="display:none;" class="tooltip" id = "tip{$timeline4[day].milestone.ID}">{$timeline4[day].milestone.desc|nl2br}</span>
								{literal}
				<script type = "text/javascript">
				new Tooltip('link{/literal}{$timeline4[day].milestone.ID}{literal}','tip{/literal}{$timeline4[day].milestone.ID}{literal}');
				</script>
				{/literal}
							{/if}</p></td>

					{/section}
				</tr>
				</table>
				<div class="clear_both_b"></div>

