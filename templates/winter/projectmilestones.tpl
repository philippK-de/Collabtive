{include file="header.tpl" jsload="ajax"  jsload1="tinymce"}
{include file="tabsmenue-project.tpl" milestab = "active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="miles">
			<div class="infowin_left" style = "display:none;" id = "systemmsg">
				{if $mode == "added"}
					<span class="info_in_green"><img src="templates/standard/images/symbols/miles.png" alt=""/>{#milestonewasadded#}</span>
				{elseif $mode == "edited"}
					<span class="info_in_yellow"><img src="templates/standard/images/symbols/miles.png" alt=""/>{#milestonewasedited#}</span>
				{elseif $mode == "deleted"}
					<span class="info_in_red"><img src="templates/standard/images/symbols/miles.png" alt=""/>{#milestonewasdeleted#}</span>
				{elseif $mode == "opened"}
					<span class="info_in_green"><img src="templates/standard/images/symbols/miles.png" alt=""/>{#milestonewasopened#}</span>
				{elseif $mode == "closed"}
					<span class="info_in_red"><img src="templates/standard/images/symbols/miles.png" alt=""/>{#milestonewasclosed#}</span>
				{/if}
			</div>

			{literal}
				<script type = "text/javascript">
					systemMsg('systemmsg');
				</script>
			{/literal}

			<h1>{$projectname|truncate:45:"...":true}<span>/ {#milestones#}</span></h1>

			{*Milestones*}
			<div class="headline">
				<a href="javascript:void(0);" id="milehead_toggle" class="win_block" onclick = "toggleBlock('milehead');"></a>

				<div class="wintools">
					{if $userpermissions.milestones.add}
						<a class="add" href="javascript:blindtoggle('addstone');" id="add" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn','butn_link_active','butn_link');toggleClass('sm_miles','smooth','nosmooth');"><span>{#addmilestone#}</span></a>
					{/if}
				</div>

				<h2><img src="./templates/standard/images/symbols/miles.png" alt="" />{#milestones#}</h2>
			</div>

			<div class="block" id="milehead">
			
				{*Add Milestone*}
				{if $userpermissions.milestones.add}
					<div id = "addstone" class="addmenue" style = "display:none;">
					{include file="addmilestone.tpl" }
					</div>
				{/if}

				<div class="nosmooth" id="sm_miles">

					<table cellpadding="0" cellspacing="0" border="0">
						<thead>
							<tr>
								<th class="a"></th>
								<th class="b">{#milestone#}</th>
								<th class="c">{#due#}</th>
								<th class="days" style="text-align:right">{#daysleft#}&nbsp;&nbsp;</th>
								<th class="tools"></th>
							</tr>
						</thead>

						<tfoot>
							<tr>
								<td colspan="5"></td>
							</tr>
						</tfoot>
					</table>

					{*new Miles*}
					<div id="togglenew" class="toggleblock">
						
						<table id="accordion_miles_new" cellpadding="0" cellspacing="0" border="0" style="clear:both;">
							{section name=stone loop=$milestones}
								{if $smarty.section.stone.index % 2 == 0}
								<tbody class="color-a" id="miles_{$milestones[stone].ID}">
								{else}
								<tbody class="color-b" id="miles_{$milestones[stone].ID}">
								{/if}
									<tr{if $milestones[stone].daysleft == 0} class="marker-today"{/if}>
										<td class="a">{if $userpermissions.milestones.close}<a class="butn_check" href="managemilestone.php?action=close&amp;mid={$milestones[stone].ID}&amp;id={$project.ID}" title="{#close#}"></a>{/if}</td>
										<td class="b">
											<div class="toggle-in">
												<span class="acc-toggle" onclick="javascript:accord_miles_new.activate($$('#accordion_miles_new .accordion_toggle')[{$smarty.section.stone.index}]);toggleAccordeon('done_{$myprojects[project].ID}',this);"></span>
												<a href="managemilestone.php?action=showmilestone&amp;msid={$milestones[stone].ID}&amp;id={$project.ID}" title="{$milestones[stone].name}">{$milestones[stone].name|truncate:30:"...":true}</a>
											</div>
										</td>
										<td class="c">{$milestones[stone].fend}</td>
										<td class="days" style="text-align:right">{$milestones[stone].dayslate}&nbsp;&nbsp;</td>
										<td class="tools">
											{if $userpermissions.milestones.edit}
												<a class="tool_edit" href="managemilestone.php?action=editform&amp;mid={$milestones[stone].ID}&amp;id={$project.ID}" title="{#edit#}"></a>
											{/if}
											{if $userpermissions.milestones.del}
												<a class="tool_del" href="javascript:confirmit('{#confirmdel#}','managemilestone.php?action=del&amp;mid={$milestones[stone].ID}&amp;id={$project.ID}');" title="{#delete#}"></a>
											{/if}
										</td>
									</tr>

									<tr class="acc">
										<td colspan="5">
											<div class="accordion_toggle"></div>
											<div class="accordion_content">
												<div class="acc-in">
													<div class="message-in">
														{$milestones[stone].desc}
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							{/section}
						</table>
					</div> {*toggleblock End*}{*new Miles End*}

					{*late Miles*}
					{if $countlate > 0}
					
						<table class="second-thead" cellpadding="0" cellspacing="0" border="0" onclick="blindtoggle('togglelate');toggleClass('togglemileslate','acc-toggle','acc-toggle-active');">
							<tr>
								<td class="a"></td>
								<td class="b"><span id="togglemileslate" class="acc-toggle-active">{#latestones#}</span></td>
								<td class="c"></td>
								<td class="days"></td>
								<td class="tools"></td>
							</tr>
						</table>

						<div id="togglelate" class="toggleblock">
							
							<table id="accordion_miles_late" cellpadding="0" cellspacing="0" border="0">
								{section name=latestone loop=$latemilestones}
									{if $smarty.section.latestone.index % 2 == 0}
									<tbody class="color-a" id="miles_late_{$latemilestones[latestone].ID}">
									{else}
									<tbody class="color-b" id="miles_late_{$latemilestones[latestone].ID}">
									{/if}

										<tr class="marker-late">
											<td class="a">
												{if $userpermissions.milestones.close}
													<a class="butn_check" href="managemilestone.php?action=close&amp;mid={$latemilestones[latestone].ID}&amp;id={$project.ID}" title="{#close#}"></a>
												{/if}
											</td>
											<td class="b">
												<div class="toggle-in">
													<span class="acc-toggle" onclick="javascript:accord_miles_late.activate($$('#accordion_miles_late .accordion_toggle')[{$smarty.section.latestone.index}]);toggleAccordeon('done_{$myprojects[project].ID}',this);"></span>
													<a href="managemilestone.php?action=showmilestone&amp;msid={$latemilestones[latestone].ID}&amp;id={$project.ID}" title="{$latemilestones[latestone].name}">{$latemilestones[latestone].name|truncate:30:"...":true}</a>
												</div>
											</td>
											<td class="c">{$latemilestones[latestone].fend}</td>
											<td class="days" style="text-align:right">-{$latemilestones[latestone].dayslate}&nbsp;&nbsp;</td>
											<td class="tools">
												{if $userpermissions.milestones.edit}
													<a class="tool_edit" href="managemilestone.php?action=editform&amp;mid={$latemilestones[latestone].ID}&amp;id={$project.ID}" title="{#edit#}"></a>
												{/if}
												{if $userpermissions.milestones.del}
													<a class="tool_del" href="javascript:confirmit('{#confirmdel#}','managemilestone.php?action=del&amp;mid={$latemilestones[latestone].ID}&amp;id={$project.ID}');" title="{#delete#}"></a>
												{/if}
											</td>
										</tr>
										<tr class="acc">
											<td colspan="5">
												<div class="accordion_toggle"></div>
												<div class="accordion_content">
													<div class="acc-in">
														<div class="message-in">
															{$latemilestones[latestone].desc}
														</div>
													</div>
												</div>
											</td>
										</tr>
									</tbody>
								{/section}
							</table>
						
						</div> {*toggleblock End*}
					{/if} {*late Miles End*}
				
					{*finished Miles*}
					<div id="doneblock" class="doneblock" style="display: none;">
						
						<table class="second-thead" cellpadding="0" cellspacing="0" border="0" onclick="blindtoggle('doneblock');toggleClass('donebutn','butn_link_active','butn_link');toggleClass('togglemilesdone','acc-toggle','acc-toggle-active');">
							<tr>
								<td class="a"></td>
								<td class="b"><span id="togglemilesdone" class="acc-toggle">{#donemilestones#}</span></td>
								<td class="c"></td>
								<td class="days"></td>
								<td class="tools"></td>
							</tr>
						</table>

						<div class="toggleblock">
						
							<table id="accordion_miles_done" cellpadding="0" cellspacing="0" border="0">
								{section name=stone loop=$donemilestones}
									{if $smarty.section.stone.index % 2 == 0}
									<tbody class="color-a" id="miles_{$donemilestones[stone].ID}">
									{else}
									<tbody class="color-b" id="miles_{$donemilestones[stone].ID}">
									{/if}
										{if $smarty.now gt $donemilestones[stone].end}
										<tr class="marker-late">
										{else}
										<tr>
										{/if}
											<td class="a">
												{if $userpermissions.milestones.close}
													<a class="butn_checked" href="managemilestone.php?action=open&amp;mid={$donemilestones[stone].ID}&amp;id={$project.ID}" title="{#open#}"></a>
												{/if}
											</td>
											<td class="b">
												<div class="toggle-in">
													<span class="acc-toggle" onclick="javascript:accord_miles_done.activate($$('#accordion_miles_done .accordion_toggle')[{$smarty.section.stone.index}]);toggleAccordeon('done_{$myprojects[project].ID}',this);"></span>
													<a href="managemilestone.php?action=showmilestone&amp;msid={$donemilestones[stone].ID}&amp;id={$project.ID}" title="{$donemilestones[stone].name}">{$donemilestones[stone].name|truncate:30:"...":true}</a>
												</div>
											</td>
											<td class="c">{$donemilestones[stone].fend}</td>
											{if $smarty.now gt $donemilestones[stone].end}
												<td class="days" style="text-align:right">-{$donemilestones[stone].dayslate}&nbsp;&nbsp;</td>
											{else}
												<td class="days" style="text-align:right">{$donemilestones[stone].dayslate}&nbsp;&nbsp;</td>
											{/if}
											<td class="tools">
												{if $userpermissions.milestones.edit}
													<a class="tool_edit" href="managemilestone.php?action=editform&amp;mid={$donemilestones[stone].ID}&amp;id={$project.ID}"  title="{#edit#}"></a>
													{/if}
													{if $userpermissions.milestones.del}
													<a class="tool_del" href="javascript:confirmit('{#confirmdel#}','managemilestone.php?action=del&amp;mid={$donemilestones[stone].ID}&amp;id={$project.ID}');"  title="{#delete#}"></a>
												{/if}
											</td>
										</tr>

										<tr class="acc">
											<td colspan="5">
												<div class="accordion_toggle"></div>
												<div class="accordion_content">
													<div class="acc-in">
														<div class="message-in">
															{$donemilestones[stone].desc}
														</div>
													</div>
												</div>
											</td>
										</tr>
									</tbody>
								{/section}
							</table>
						</div> {*toggleblock End*} {*finished Miles End*}
					</div> {*done_block End*}
				</div> {*smooth End*}

				<div class="tablemenue">
					<div class="tablemenue-in">
						{if $userpermissions.milestones.add > 0}
							<a class="butn_link" href="javascript:blindtoggle('addstone');" id="add_butn" onclick="toggleClass('add','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_miles','smooth','nosmooth');">{#addmilestone#}</a>
						{/if}
						<a class="butn_link" href="javascript:blindtoggle('doneblock');" id="donebutn" onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('togglemilesdone','acc-toggle','acc-toggle-active');">{#donemilestones#}</a>
					</div>
				</div>
			</div> {*block End*}

			{literal}
				<script type = "text/javascript">
					try{
					var accord_miles_late = new accordion('accordion_miles_late');
					}
					catch(e)
					{}

					try{
					var accord_miles_new = new accordion('accordion_miles_new');
					}
					catch(e)
					{}

					try{
					var accord_miles_done = new accordion('accordion_miles_done');
					}
					catch(e){}
				</script>
			{/literal}

		</div> {*Miles END*}
		<div class="content-spacer"></div>
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}