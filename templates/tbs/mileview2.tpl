{include file="header.tpl" jsload = "ajax" jsload3 = "lightbox" showheader="no"}
				<table id="timeline1" class="timeline" cellpadding="0" cellspacing="1">

				<tr class="head">
				<td></td>
				{section name = day loop=$timestr}
				<td>{$timestr[day]}</td>
				{/section}
				<td></td>
				</tr>
				<tr class="content" valign="top">

					<td valign="middle" rowspan="2" style="width:12px;"><a class="scroll_left" href = "{literal}javascript:change('manageajax.php?action=timeline2prev&start={/literal}{$start}{literal}&end={/literal}{$end}{literal}&amp;id={/literal}{$id}{literal}','milehead');{/literal}"></a></td>


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
				<p>	{if $timeline1[tag].milestone.ID > 0}
					<a id = "link{$timeline1[tag].milestone.ID}" href="managemilestone.php?action=showmilestone&amp;msid={$timeline1[tag].milestone.ID}&amp;id={$timeline1[tag].milestone[1]}" >{$timeline1[tag].milestone.name|truncate:30:"...":true}</a><br/><span style="display:none;" class="tooltip" id = "tip{$timeline1[tag].milestone.ID}">{$timeline1[tag].milestone.desc|nl2br}</span>
				{literal}
				<script type = "text/javascript">
				new Tooltip('link{/literal}{$timeline1[tag].milestone.ID}{literal}','tip{/literal}{$timeline1[tag].milestone.ID}{literal}');
				</script>
				{/literal}
				{/if}</p></td>
					{/section}


					<td valign="middle" rowspan="2" style="width:12px;"><a class="scroll_right" href = "{literal}javascript:change('manageajax.php?action=timeline2next&start={/literal}{$start}{literal}&end={/literal}{$end}{literal}&amp;id={/literal}{$id}{literal}','milehead');{/literal}"></a></td>

					</tr>
				<tr class="content" valign="top">
					{section name=day loop=$timeline2}
					{if $smarty.section.day.index % 2 == 0}
						{if $timeline2[day].tagstr == $today}
						<td class="today">
						{else}
						<td class="second">
						{/if}
					{else}
					<td>
					{/if}
					<span style="white-space:nowrap;">{$timeline2[day].tagstr}</span>
							{if $timeline2[day].milestone.ID > 0}	<p>
					<a id = "link{$timeline2[day].milestone.ID}" href="managemilestone.php?action=showmilestone&amp;msid={$timeline2[day].milestone.ID}&amp;id={$timeline2[day].milestone[1]}" >{$timeline2[day].milestone.name|truncate:30:"...":true}</a><br/><span style="width:200px;margin: 5px; background-color: black;display:none;" id = "tip{$timeline2[day].milestone.ID}">{$timeline2[day].milestone.desc|nl2br}</span></p>
				{literal}
				<script type = "text/javascript">
				new Tooltip('link{/literal}{$timeline2[day].milestone.ID}{literal}','tip{/literal}{$timeline2[day].milestone.ID}{literal}');
				</script>
				{/literal}
				{/if}</td>
					{/section}
				</tr>

				</table>
				<div class="clear_both_b"></div> {*required ... do not delete this row*}


</div> {*block_in_wrapper end*}
</div>