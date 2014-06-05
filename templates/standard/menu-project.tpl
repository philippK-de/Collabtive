<div class="headline">
	<h2>
		<a href="javascript:void(0);" id="status_toggle" class="{$statbar}" onclick="toggleBlock('status');">
			<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />
			<span>{#project#}</span>
		</a>
	</h2>
</div>

<div id="status" style="{$statstyle}">

	<div class="table_head">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td class="a"></td>
				<td class="b">{#description#}</td>
				<td class="days">{#daysleft#}</td>
				<td class="d">{#status#}</td>
				<td class="e"></td>
			</tr>
		</table>
	</div>

	<div class="table_body">
		<div id="accordion_status">
		
			<ul>
				<li class="bg_a">			
					<div class="marker_all">
						<div id="status_focus" class="focus_off">

							<table cellpadding="0" cellspacing="0">
							
								<tr>
									<td class="a">
										{if $userpermissions.projects.close}
											{if $project.status == 1}
												<a class="butn_check" href="manageproject.php?action=close&amp;id={$project.ID}" title="{#close#}"></a>
											{else}
												<a class="butn_checked" href="manageproject.php?action=open&amp;id={$project.ID}" title="{#open#}"></a>
											{/if}
										{/if}
									</td>
									<td class="b"></td>
									<td class="days">{$project.daysleft}</td>
									<td class="d">
										{if $project.status == 1}
											<span class="status_active">{#active#}</span>
										{else}
											<span class="status_inactive">{#inactive#}</span>
										{/if}
									</td>
									<td class="tools">
					                    {if $userpermissions.projects.edit}
					                   		<a class="tool_edit" style="margin-left:21px;" href="manageproject.php?action=editform&amp;id={$project.ID}" title="{#edit#}"></a>
					                    {/if}
					                </td>			
								</tr>
				            </table>
				
							<div class="accordion_toggle">
								<a class="open" href="#" onclick="switchClass('status_focus','focus_on','focus_off');">
									{if $project.name != ""}
										{$project.name|truncate:30:"...":true}
									{else}
										{$project.desc|truncate:30:"...":true}
									{/if}
								</a>
							</div> {* accordion_toggle END *}
							
							<div class="accordion_content">
							
								<table class="description" cellpadding="0" cellspacing="0">
									<tr valign="top">
										<td class="a"></td>
										<td class="descript">
											<div style="width:588px;overflow:auto;">
												{$project.desc}
												
												{if $userpermissions.projects.add}
													<strong>{#report#}:</strong>
													<br /><a href="manageproject.php?action=pdfreport&amp;id={$project.ID}" title="{#report#}"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/files/application-pdf.png"></a><br />
												{/if}
												
											</div>
										</td>
									</tr>
								</table>
								
							</div> {* accordion_Content END *}
						</div> {* focus END *}
					</div> {* marker END *}
				</li>
			</ul>
			
		</div> {* accordion END *}
	</div> {* table_Body END *}
</div>{* status END *}

{literal}
	<script type="text/javascript">
		var accord_status = new accordion('accordion_status');
	</script>
{/literal}