

				<table id="timeline1" class="timeline" cellpadding="0" cellspacing="1">


				<tr class="head">
				<td></td>
				{section name = day loop=$timestr}
				<td>{$timestr[day]}</td>
				{/section}
				<td></td>
				</tr>

				<tr class="content" valign="top">
					<td valign="middle" rowspan="1" style="width:12px;"><a class="scroll_left" href = "{literal}javascript:change('manageajax.php?action=timeline1prev&amp;start={/literal}{$start}{literal}&amp;end={/literal}{$end}{literal}','timeline');{/literal}"></a></td>

					{section name=tag loop=$timeline1}
					{if $smarty.section.tag.index % 2 == 0}
						{if $timeline1[tag].tagstr == $today}
						<td class="today">
						{else}
						<td class="second">
						{/if}

					{else}
					<td>
					{/if}
					<span style="white-space:nowrap;">{$timeline1[tag].tagstr}</span>
					{if $timeline1[tag].milestone.ID > 0}
					<p><a id = "link{$timeline1[tag].milestone.ID}" href="managemilestone.php?action=showmilestone&amp;msid={$timeline1[tag].milestone.ID}&amp;id={$timeline1[tag].milestone[1]}" >{$timeline1[tag].milestone.name|truncate:30:"...":true}</a><br/><span style="display:none;" class="tooltip" id = "tip{$timeline1[tag].milestone.ID}">{$timeline1[tag].milestone.desc|nl2br}</span></p></td>
				{literal}
				<script type = "text/javascript">
				new Tooltip('link{/literal}{$timeline1[tag].milestone.ID}{literal}','tip{/literal}{$timeline1[tag].milestone.ID}{literal}');
				</script>
				{/literal}
				{/if}
					{/section}

					<td valign="middle" rowspan="1" style="width:12px;"><a class="scroll_right" href = "{literal}javascript:change('manageajax.php?action=timeline1next&amp;start={/literal}{$start}{literal}&amp;end={/literal}{$end}{literal}','timeline');{/literal}"></a></td>

					</tr>


				</table>
				<div class="clear_both_b"></div>


