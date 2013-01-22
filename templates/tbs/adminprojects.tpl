{include file="header.tpl" jsload = "ajax"  jsload1 = "tinymce"}
{include file="tabsmenue-admin.tpl" projecttab = "active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="projects">

			<div class="infowin_left" style = "display:none;" id = "systemmsg">
				{if $mode == "assigned"}
					<span class="info_in_yellow"><img src="templates/standard/images/symbols/system-settings.png" alt=""/>{#settingsedited#}</span>
				{elseif $mode == "deassigned"}
					<span class="info_in_red"><img src="templates/standard/images/symbols/user-icon-male.png" alt=""/>{#userwasdeassigned#}</span>
				{elseif $mode == "added"}
					<span class="info_in_green"><img src="templates/standard/images/symbols/projects.png" alt=""/>{#projectwasadded#}</span>
				{/if}
			</div>

			{literal}
				<script type = "text/javascript">
					systemMsg('systemmsg');
				</script>
			{/literal}

			<h1>{#administration#}<span>/ {#projectadministration#}</span></h1>

			<div class="headline">
				<a href="javascript:void(0);" id="acc-projects_toggle" class="win_none" onclick = "toggleBlock('acc-projects');"></a>
					{if $userpermissions.projects.add}
						<div class="wintools">
							<a class="add" href="javascript:blindtoggle('form_addmyproject');" id="add_myprojects" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_myprojects','butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');"><span>{#addproject#}</span></a>
						</div>
					{/if}

					<h2>
						<img src="./templates/standard/images/symbols/projects.png" alt="" />{#openprojects#}
					</h2>
				</div>

				<div class="block" id="acc-projects"> {*Add Project*}
					<div id = "form_addmyproject" class="addmenue" style = "display:none;">
						{include file="addproject.tpl" myprojects="1"}
					</div>

					<div class="nosmooth" id="sm_myprojects">
				
						<table cellpadding="0" cellspacing="0" border="0">
							<thead>
								<tr>
									<th class="a"></th>
									<th class="b">{#project#}</th>
									<th class="c">{#done#}</th>
									<th class="days" style="text-align:right">{#daysleft#}&nbsp;&nbsp;</th>
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
									<tr {if $opros[opro].daysleft < 0 && $opros[opro].daysleft != ""} class="marker-late"{elseif $opros[opro].daysleft == "0"} class="marker-today"{/if}>
										<td>
											{if $userpermissions.projects.del}
												<a class="butn_check" href="javascript:closeElement('proj_{$opros[opro].ID}','manageproject.php?action=close&amp;id={$opros[opro].ID}');" title="{#close#}"></a>
											{/if}
										</td>
										<td>
											<div class="toggle-in">
												<span class="acc-toggle" onclick="javascript:accord_projects.activate($$('#acc-projects .accordion_toggle')[{$smarty.section.opro.index}]);toggleAccordeon('acc-projects',this);"></span>
												<a href="manageproject.php?action=showproject&amp;id={$opros[opro].ID}" title="{$opros[opro].name}">
													{$opros[opro].name|truncate:30:"...":true}
												</a>
											</div>
										</td>
										<td>
											<div class="statusbar_b">
												<div class="complete" id = "completed" style="width:{$opros[opro].done}%;"></div>
											</div>
											<span>{$opros[opro].done}%</span>
										</td>
										<td style="text-align:right">{$opros[opro].daysleft}&nbsp;&nbsp;</td>
										<td class="tools">
											{if $userpermissions.projects.edit}
												<a class="tool_edit" href="manageproject.php?action=editform&amp;id={$opros[opro].ID}" title="{#edit#}"></a>
											{/if}
											{if $userpermissions.projects.del}
												<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{$opros[opro].ID}\',\'manageproject.php?action=del&amp;id={$opros[opro].ID}\')');"  title="{#delete#}"></a>
											{/if}
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
																			<div class="moreinfo" id="info_{$opros[opro].ID}_{$opros[opro].members[member].ID}" style="display:none">
																				<img src = "thumb.php?pic=files/{$cl_config}/avatar/{$opros[opro].members[member].avatar}&amp;width=82" alt="" onclick="fadeToggle('info_{$opros[opro].ID}_{$opros[opro].members[member].ID}');" />
																				<span class="name"><a href="manageuser.php?action=profile&amp;id={$opros[opro].members[member].ID}">{$opros[opro].members[member].name|truncate:15:"...":true}</a></span>
																			</div>
																		{/if}
																	</div> {*itemwrapper end*}
																	
																</li>
															{/section}
														</ul>
													</div> {*inwrapper End*}
													
													<p class="tags-miles"> {*assign users*}
														<strong>{#adduser#}:</strong>
													</p>
													<div class = "inwrapper">
														<form  class = "main" method="post" action="manageproject.php?action=assign&amp;id={$opros[opro].ID}&redir=admin.php?action=projects&mode=useradded" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
															<fieldset>

																<div class="row">
																	<label for="addtheuser">{#user#}</label>
																	<select name = "user" id="addtheuser" >
																		<option value = "">{#chooseone#}</option>
																		{section name = usr loop=$users}
																		<option value = "{$users[usr].ID}">{$users[usr].name}</option>
																		{/section}
																	</select>
																</div>

																<div class="row-butn-bottom">
																	<label>&nbsp;</label>
																	<button type="submit" onfocus="this.blur();">{#addbutton#}</button>
																	<button onclick="blindtoggle('form_member');toggleClass('addmember','add-active','add');toggleClass('add_butn_member','butn_link_active','butn_link');toggleClass('sm_member','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
																</div>
															</fieldset>
														</form>
													</div>{*assign users end*}

												</div>
											</div>
										</td>
									</tr>
								</tbody>
							{/section}
						</table> {*Projects End*}

						{*Doneprojects*}
						<div id="doneblock" class="doneblock" style="display: none;">
							
							<table class="second-thead" cellpadding="0" cellspacing="0" border="0" onclick="blindtoggle('doneblock');toggleClass('donebutn','butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">
								<tr>
									<td class="a"></td>
									<td class="b"><span id="toggle-done" class="acc-toggle">{#closedprojects#}</span></td>
									<td class="c"></td>
									<td class="days"></td>
									<td class="tools"></td>
								</tr>
							</table>

					
							<div class="toggleblock">
							
								<table cellpadding="0" cellspacing="0" border="0" id="acc-oldprojects">
									{section name=clopro loop=$clopros}

										{*Color-Mix*}
										{if $smarty.section.clopro.index % 2 == 0}
										<tbody class="color-a" id="proj_{$clopros[clopro].ID}">
										{else}
										<tbody class="color-b" id="proj_{$clopros[clopro].ID}">
										{/if}
											<tr>
												<td class="a">{if $userpermissions.projects.add}<a class="butn_checked" href="manageproject.php?action=open&amp;id={$clopros[clopro].ID}" title="{#open#}"></a>{/if}</td>
												<td class="b">
													<div class="toggle-in">
													<span class="acc-toggle" onclick="javascript:accord_oldprojects.activate($$('#acc-oldprojects .accordion_toggle')[{$smarty.section.clopro.index}]);toggleAccordeon('acc-oldprojects',this);"></span>
														<a href="manageproject.php?action=showproject&amp;id={$clopros[clopro].ID}" title="{$clopros[clopro].name}">
															{$clopros[clopro].name|truncate:30:"...":true}
														</a>
													</div>
												</td>
												<td class="c">
													<div class="statusbar_b">
														<div class="complete" id = "completed" style="width:{$myprojects[project].done}%;"></div>
													</div>
													<span>{$myprojects[project].done}%</span>
												</td>
												<td class="days" style="text-align:right">{$clopros[clopro].daysleft}&nbsp;&nbsp;</td>
												<td class="tools">
													{if $userpermissions.projects.edit}
													<a class="tool_edit" href="manageproject.php?action=editform&amp;id={$clopros[clopro].ID}" title="{#edit#}"></a>
													{/if}
													{if $userpermissions.projects.del}
														<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{$clopros[clopro].ID}\',\'manageproject.php?action=del&amp;id={$clopros[clopro].ID}\')');"  title="{#delete#}"></a>
													{/if}
												</td>
											</tr>

											<tr class="acc">
												<td colspan="5">
													<div class="accordion_toggle"></div>
													<div class="accordion_content">
														<div class="acc-in">
															{$clopros[clopro].desc}

														</div>
													</div>
												</td>
											</tr>
										</tbody>
									{/section}
								</table>
								
							</div> {*toggleblock End*}
						</div> {*doneblock end*}
					</div> {*smooth end*}

					<div class="tablemenue">
						<div class="tablemenue-in">
							{if $userpermissions.projects.add}
								<a class="butn_link" href="javascript:blindtoggle('form_addmyproject');" id="add_butn_myprojects" onclick="toggleClass('add_myprojects','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');">{#addproject#}</a>
							{/if}
							<a class="butn_link" href="javascript:blindtoggle('doneblock');" id="donebutn" onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('toggle-done','acc-toggle','acc-toggle-active');">{#closedprojects#}</a>
						</div>
					</div>
				</div> {*block END*} {*Doneprojects End*}

			{literal}
				<script type = "text/javascript">
					var accord_projects = new accordion('acc-projects');
					var accord_oldprojects = new accordion('acc-oldprojects');
				</script>
			{/literal}

			<div class="content-spacer"></div>

		</div> {*Projects END*}
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}