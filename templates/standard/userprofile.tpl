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
									<div class="avatar-profile"><img src = "thumb.php?pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-female.jpg&amp;width=122;" alt="" /></div>
								{else}
									<div class="avatar-profile">
										<img src = "thumb.php?pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-male.jpg&amp;width=122;" alt="" />
									</div>
								{/if}
							{/if}
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
												<td class="right">{$user.url}</td>
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
									</table>

								</div> {*Block End*}
							</div> {*Message End*}
						</td>
					</tr>
				</table>
			</div> {*UserWrapper End*}
		</div> {*User END*}
		<div class="content-spacer"></div>

		{include file="userProfileProjects.tpl"}



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
						<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/timetracker.png" alt="" />{#report#}
					</h2>
				</div>

				<div class="block" id="acc-tracker"> {*Filter Report*}
					<div id = "form_filter" class="addmenue display-none">
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
									<th class="e text-align-right">{#hours#}&nbsp;&nbsp;</th>
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
										<td class="text-align-right">{$tracker[track].hours|truncate:12:"...":true}&nbsp;&nbsp;</td>
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
									<td class="text-align-right"><strong>{$totaltime}</strong>&nbsp;&nbsp;</td>
									<td class="tools"></td>
								</tr>
							</tbody>

						<tbody class="color-a">
					<tr>
						<td colspan="7">
							<div id="paging float-right">
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



	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}