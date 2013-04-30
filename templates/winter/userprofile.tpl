{include file="header.tpl" jsload = "ajax"}
{include file="tabsmenue-user.tpl" usertab = "active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="user">

			<h1>{#userprofile#}<span>/ {$user.name}</span></h1>

			<div class="export-main">
				<a class="export"><span>{#export#}</span></a>
				<div class="export-in"  style="width:32px;left: -32px;"> {*at vcard items*}
					{if $user.gender == "f"}
						<a class="vcardfemale" href="manageuser.php?action=vcard&amp;id={$user.ID}"><span>{#vcardexport#}</span></a>
					{else}
						<a class="vcardmale" href="manageuser.php?action=vcard&amp;id={$user.ID}"><span>{#vcardexport#}</span></a>
					{/if}
				</div>
			</div>

			<div class="userwrapper">

				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="avatarcell" valign="top">
							{if $user.avatar != ""}
								<a href="#avatarbig" id="ausloeser">
									<div class="avatar-profile"><img src = "thumb.php?pic=files/{$cl_config}/avatar/{$user.avatar}&amp;width=122;" alt="" /></div>
								</a>
							{else}
								{if $user.gender == "f"}
									<div class="avatar-profile"><img src = "thumb.php?pic=templates/standard/images/no-avatar-female.jpg&amp;width=122;" alt="" /></div>
								{else}
									<div class="avatar-profile">
										<img src = "thumb.php?pic=templates/standard/images/no-avatar-male.jpg&amp;width=122;" alt="" />
									</div>
								{/if}
							{/if}
							<div id="avatarbig" style="display:none;">
								<a href="javascript:Control.Modal.close();"><img src = "thumb.php?pic=files/{$cl_config}/avatar/{$user.avatar}&amp;width=480&amp;height=480;" alt="" /></a>
							</div>
						</td>
						<td>
							<div class="message">
								<div class="block">

									<table cellpadding="0" cellspacing="0" border="0">
										<colgroup>
											<col class="a" />
											<col class="b" />
										</colgroup>

										<thead><tr><th colspan="2"></th></tr></thead>
										<tfoot><tr><td colspan="2"></td></tr></tfoot>

										<tbody class="color-b">
											<tr>
												<td><strong>{#company#}:</strong></td>
												<td class="right">{if $user.company}{$user.company}{/if}</td>
											</tr>
										</tbody>

										<tbody class="color-a">
											<tr>
												<td><strong>{#email#}:</strong></td>
												<td class="right"><a href = "mailto:{$user.email}">{$user.email}</a></td>
											</tr>
										</tbody>

										<tbody class="color-b">
											<tr>
												<td><strong>{#url#}:</strong></td>
												<td class="right"><a href="{$user.url}" target="_blank">{$user.url}</a></td>
											</tr>
										</tbody>

										<tbody class="color-a">
											<tr>
												<td><strong>{#phone#}:</strong></td>
												<td class="right">{$user.tel1}</td>
											</tr>
										</tbody>

										<tbody class="color-b">
											<tr>
												<td><strong>{#cellphone#}:</strong></td>
												<td class="right">{$user.tel2}</td>
											</tr>
										</tbody>

										<tbody class="color-a">
											<tr>
												<td><strong>{#address#}:</strong></td>
												<td class="right">{$user.adress}</td>
											</tr>
										</tbody>

										<tbody class="color-b">
											<tr>
												<td><strong>{#zip#} / {#city#}:</strong></td>
												<td class="right">{$user.zip}{if $user.zip && $user.adress2} {/if}{$user.adress2} </td>
											</tr>
										</tbody>

										<tbody class="color-a">
											<tr>
												{if $user.state == ""}
													<td><strong>{#country#}:</strong></td>
													<td class="right">{$user.country}</td>
												{elseif $user.country == ""}
													<td><strong>{#state#}:</strong></td>
													<td class="right">{$user.state}</td>
												{else}
													<td><strong>{#country#} ({#state#}):</strong></td>
													<td class="right">{$user.country} ({$user.state})</td>
												{/if}
											</tr>
										</tbody>

										<tbody class="color-b">
											<tr>
												<td><strong>{#tags#}:</strong></td>
												<td class="right">
													{section name = tag loop=$user.tagsarr}
														<a href = "managetags.php?action=gettag&tag={$user.tagsarr[tag]}&amp;id=0">{$user.tagsarr[tag]}</a>
													{/section}
												</td>
											</tr>
										</tbody>
									</table>

								</div> {*Block End*}
							</div> {*Message End*}
						</td>
					</tr>
				</table>
			</div> {*UserWrapper End*}
		</div> {*User END*}
		<div class="content-spacer"></div>

		{if $userpermissions.admin.add}{if $opros}{*Projects*}
			<div class="projects">
				<div class="headline">
					<a href="javascript:void(0);" id="projecthead_toggle" class="win_block" onclick = "toggleBlock('projecthead');"></a>

					<h2>
						<a href="myprojects.php" title="{#myprojects#}"><img src="./templates/standard/images/symbols/projects.png" alt="" />{#projects#}</a>
					</h2>
				</div>

				<div class="block" id="projecthead" style = "{$projectstyle}">

					<table cellpadding="0" cellspacing="0" border="0">
						<thead>
							<tr>
								<th class="a"></th>
								<th class="b">{#project#}</th>
								<th class="c"></th>
								<th class="d" style="text-align:right">{#daysleft#}&nbsp;&nbsp;</th>
								<th class="tools"></th>
							</tr>
						</thead>

						<tfoot>
							<tr>
								<td colspan="5"></td>
							</tr>
						</tfoot>

							{section name=opro loop=$opros}
							{*Color-Mix*}
							{if $smarty.section.opro.index % 2 == 0}
							<tbody class="color-a" id="proj_{$opros[opro].ID}">
							{else}
							<tbody class="color-b" id="proj_{$opros[opro].ID}">
							{/if}
								<tr {if $opros[opro].daysleft < 0} class="marker-late"{elseif $opros[opro].daysleft == 0} class="marker-today"{/if}>
									<td>
										{if $adminstate|default > 4}
											<a class="butn_check" href="javascript:closeElement('proj_{$opros[opro].ID}','manageproject.php?action=close&amp;id={$opros[opro].ID}');" title="{#close#}"></a>
										{/if}
									</td>
									<td>
										<div class="toggle-in">
											<span class="acc-toggle" onclick="javascript:accord_projects.activate($$('#projecthead .accordion_toggle')[{$smarty.section.opro.index}]);toggleAccordeon('projecthead',this);"></span>
											<a href="manageproject.php?action=showproject&amp;id={$opros[opro].ID}" title="{$opros[opro].name}">
												{$opros[opro].name|truncate:30:"...":true}
											</a>
										</div>
									</td>
									<td></td>
									<td style="text-align:right">{$opros[opro].daysleft}&nbsp;&nbsp;</td>
									<td class="tools">
										<a class="tool_edit" href="manageproject.php?action=editform&amp;id={$opros[opro].ID}" title="{#edit#}" {if !$userpermissions.projects.edit}style="visibility:hidden;" {/if}></a>
										<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{$opros[opro].ID}\',\'manageproject.php?action=del&amp;id={$opros[opro].ID}\')');"  title="{#delete#}" {if !$userpermissions.projects.del}style="visibility:hidden;" {/if}></a>
									</td>
								</tr>

								<tr class="acc">
									<td colspan="5">
										<div class="accordion_toggle"></div>
										<div class="accordion_content">
											<div class="acc-in">
												{$opros[opro].desc}
												<p class="tags-miles">
													<strong>{#user#}:</strong>
												</p>

												<div class="inwrapper">
													<ul>
														{section name = member loop=$opros[opro].members}
															<li>
																<div class="itemwrapper" id="iw_{$opros[opro].ID}_{$opros[opro].members[member].ID}">

																	<table cellpadding="0" cellspacing="0" border="0">
																		<tr>
																			<td class="leftmen" valign="top">
																				<div class="inmenue">
																					{if $opros[opro].members[member].avatar != ""}
																						<a class="more" href="javascript:fadeToggle('info_{$opros[opro].ID}_{$opros[opro].members[member].ID}');"></a>
																					{/if}
																				</div>
																			</td>
																			<td class="thumb">
																				<a href="manageuser.php?action=profile&amp;id={$opros[opro].members[member].ID}" title="{$opros[opro].members[member].name}">
																					{if $opros[opro].members[member].gender == "f"}
																						<img src = "./templates/standard/images/symbols/user-icon-female.png" alt="" />
																					{else}
																						<img src = "./templates/standard/images/symbols/user-icon-male.png" alt="" />
																					{/if}
																				</a>
																			</td>
																			<td class="rightmen" valign="top">
																				<div class="inmenue">
																					<a class="del" href="manageproject.php?action=deassign&amp;user={$opros[opro].members[member].ID}&amp;id={$opros[opro].ID}&amp;redir=admin.php?action=projects" title="{#deassignuser#}" onclick="fadeToggle('iw_{$opros[opro].ID}_{$opros[opro].members[member].ID}');"></a>
																					<a class="edit" href="admin.php?action=editform&amp;id={$opros[opro].members[member].ID}" title="{#edituser#}"></a>
																				</div>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="3">
																				<span class="name"><a href="manageuser.php?action=profile&amp;id={$opros[opro].members[member].ID}" title="{$opros[opro].members[member].name}">{$opros[opro].members[member].name|truncate:15:"...":true}</a></span>
																			</td>
																		<tr/>
																	</table>

																	{if $opros[opro].members[member].avatar != ""}
																		<div class="moreinfo-wrapper">
																			<div class="moreinfo" id="info_{$opros[opro].ID}_{$opros[opro].members[member].ID}" style="display:none">
																				<img src = "thumb.php?pic=files/{$cl_config}/avatar/{$opros[opro].members[member].avatar}&amp;width=82" alt="" onclick="fadeToggle('info_{$opros[opro].ID}_{$opros[opro].members[member].ID}');" />
																				<span class="name"><a href="manageuser.php?action=profile&amp;id={$opros[opro].members[member].ID}">{$opros[opro].members[member].name|truncate:15:"...":true}</a></span>
																			</div>
																		</div>
																	{/if}
																</div> {*itemwrapper end*}

															</li>
														{/section}
													</ul>
												</div> {*inwrapper End*}

											</div>
										</div>
									</td>
								</tr>
							</tbody>
						{/section}
					</table>

					<div class="tablemenue"></div>
				</div> {*block END*}
			</div> {*projects END*}
			<div class="content-spacer"></div> {*Projects End*}


			{literal}
				<script type = "text/javascript">
					var accord_projects = new accordion('projecthead');
					new Control.Modal('ausloeser',{
					opacity: 0.8,
					position: 'absolute',
					width: 480,
					height: 480,
					fade:true,
					containerClassName: 'pics',
					overlayClassName: 'useroverlay'
					});
				</script>
			{/literal}
		{/if}{/if} {*if admin end*}

		{if $userpermissions.admin.add or $userid == $user.ID}{if $tracker} {*timetracker start*}
			<div class="timetrack">
				<div class="headline">
					<a href="javascript:void(0);" id="acc-tracker_toggle" class="win_block" onclick = "toggleBlock('acc-tracker');"></a>
					<div class="wintools">
						<div class="export-main">
							<a class="export"><span>{#export#}</span></a>
							<div class="export-in"  style="width:46px;left: -46px;"> {*at one item*}
								<a class="pdf" href="managetimetracker.php?action=userpdf&amp;id={$project.ID}{if $start != "" and $end != ""}&amp;start={$start}&amp;end={$end}{/if}{if $usr > 0}&amp;usr={$usr}{/if}{if $task > 0}&amp;task={$task}{/if}{if $fproject > 0}&amp;project={$fproject}{/if}"><span>{#pdfexport#}</span></a>
								<a class="excel" href="managetimetracker.php?action=userxls&amp;id={$project.ID}{if $start != "" and $end != ""}&amp;start={$start}&amp;end={$end}{/if}{if $usr > 0}&amp;usr={$usr}{/if}{if $task > 0}&amp;task={$task}{/if}{if $fproject > 0}&amp;project={$fproject}{/if}"><span>{#excelexport#}</span></a>
							</div>
						</div>

						<div class="toolwrapper">
							<a class="filter" href="javascript:blindtoggle('form_filter');" id="filter_report" onclick="toggleClass(this,'filter-active','filter');toggleClass('filter_butn','butn_link_active','butn_link');toggleClass('sm_report','smooth','nosmooth');"><span>{#filterreport#}</span></a>
						</div>
					</div>

					<h2>
						<img src="./templates/standard/images/symbols/timetracker.png" alt="" />{#report#}
					</h2>
				</div>

				<div class="block" id="acc-tracker"> {*Filter Report*}
					<div id = "form_filter" class="addmenue" style = "display:none;">
						{include file="filterreport.tpl" }
					</div>

					<div class="nosmooth" id="sm_report">

						<table cellpadding="0" cellspacing="0" border="0">
							<thead>
								<tr>
									<th class="a"></th>
									<th class="b">{#project#}</th>
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

							{section name = track loop=$tracker}

								{*Color-Mix*}
								{if $smarty.section.track.index % 2 == 0}
								<tbody class="color-a" id="track_{$tracker[track].ID}">
								{else}
								<tbody class="color-b" id="track_{$tracker[track].ID}">
								{/if}
									<tr>
										<td></td>
										<td>
											<div class="toggle-in">
											<span class="acc-toggle" onclick="javascript:accord_tracker.activate($$('#acc-tracker .accordion_toggle')[{$smarty.section.track.index}]);toggleAccordeon('acc-tracker',this);"></span>
												<a href = "managetimetracker.php?action=showproject&amp;id={$tracker[track].project}" title="{$tracker[track].pname}">
													{$tracker[track].pname|truncate:30:"...":true}
												</a>
											</div>
										</td>
										<td>{$tracker[track].daystring|truncate:12:"...":true}</td>
										<td>{$tracker[track].startstring|truncate:12:"...":true}</td>
										<td>{$tracker[track].endstring|truncate:12:"...":true}</td>
										<td style="text-align:right">{$tracker[track].hours|truncate:12:"...":true}&nbsp;&nbsp;</td>
										<td class="tools">
											{if $userpermissions.timetracker.edit}
												<a class="tool_edit" href="managetimetracker.php?action=editform&amp;tid={$tracker[track].ID}&amp;id={$tracker[track].project}" title="{#edit#}"></a>
											{/if}
											{if $userpermissions.timetracker.del}
												<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'track_{$tracker[track].ID}\',\'managetimetracker.php?action=del&amp;tid={$tracker[track].ID}&amp;id={$project.ID}\')');"  title="{#delete#}"></a>
											{/if}
										</td>
									</tr>

									<tr class="acc">
										<td colspan="7">
											<div class="accordion_toggle"></div>
											<div class="accordion_content">
												<div class="acc-in">
													{if $tracker[track].comment != ""}
														<strong>{#comment#}:</strong><br />{$tracker[track].comment}
													{/if}
													{if $tracker[track].task > 0}
														<p class="tags-miles">
															<strong>{#task#}:</strong><br />
															<a href = "managetask.php?action=showtask&amp;tid={$tracker[track].task}&amp;id={$tracker[track].project}">{$tracker[track].tname}</a>
														</p>
													{/if}
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							{/section}

							<tbody class="tableend">
								<tr>
									<td></td>
									<td colspan="4"><strong>{#totalhours#}:</strong></td>
									<td style="text-align:right"><strong>{$totaltime}</strong>&nbsp;&nbsp;</td>
									<td class="tools"></td>
								</tr>
							</tbody>

						<tbody class="color-a">
					<tr>
						<td colspan="7">
							<div id="paging" style = "float:right;" >
								{paginate_prev} {paginate_middle} {paginate_next}
							</div>
						</td>

					</tr>
				</tbody>
						</table>

					</div> {*smooth End*}

					<div class="tablemenue">
						<div class="tablemenue-in">
							<a class="butn_link" href="javascript:blindtoggle('form_filter');" id="filter_butn" onclick="toggleClass('filter_report','filter-active','filter');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_report','smooth','nosmooth');">{#filterreport#}</a>
						</div>
					</div>
				</div> {*block END*}
			</div> {*timetrack END*}
		{/if}{/if}
		<div class="content-spacer"></div>

		{literal}
			<script type = "text/javascript">
				var accord_tracker = new accordion('acc-tracker');
			</script>
		{/literal}

	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}