{include file="header.tpl" jsload="ajax" jsload1="tinymce" jsload3="lightbox" stage="index"}
{include file="tabsmenue-desk.tpl" desktab="active"}

<div id="content-left">
	<div id="content-left-in">

		{* Display system messages *}
		<div class="infowin_left" style="display:none;" id="systemmsg">
			{if $mode == "projectadded"}
				<span class="info_in_green">
					<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />{#projectwasadded#}
				</span>
			{/if}

			{* For async display *}
			<span id="closed" style="display:none;" class="info_in_green">
				<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />{#projectwasclosed#}
			</span>
			<span id="deleted" style="display:none;" class="info_in_red">
				<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />{#projectwasdeleted#}
			</span>
		</div>

		{literal}
			<script type="text/javascript">
				systemMsg('systemmsg');
			</script>
		{/literal}

		{if $isUpdated|default}
			{include file="updateNotify.tpl"}
			<br />
		{/if}

		<h1>{#desktop#}</h1>
<div id="block_index" class="block">
		{* Projects *}
		{if $projectnum > 0}

			<div class="projects"  style = "padding-bottom:2px;">
				<div class="headline">
					<a href="javascript:void(0);" id="projecthead_toggle" class="win_block" onclick="changeElements('a.win_block','win_none');toggleBlock('projecthead');"></a>

					<h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />{#myprojects#}</h2>
				</div>
				<div class = "acc_toggle"></div>
				<div class="block acc_content" id="projecthead" style = "overflow:hidden;" >{* Add project *}
					<div id="form_addmyproject" class="addmenue" style="display:none;">
						{include file="addproject.tpl" myprojects="1"}
					</div>

					<div class="nosmooth" id="sm_deskprojects">
						<table id="desktopprojects" cellpadding="0" cellspacing="0" border="0">

							<thead>
								<tr>
									<th class="a"></th>
									<th class="b" style="cursor:pointer;" onclick="sortBlock('desktopprojects','');">{#project#}</th>
									<th class="c" style="cursor:pointer" onclick="sortBlock('desktopprojects','done');">{#done#}</th>
									<th class="d" style="text-align:right" onclick="sortBlock('desktopprojects','daysleft');">{#daysleft#}&nbsp;&nbsp;</th>
									<th class="tools"></th>
								</tr>
							</thead>

							<tfoot>
								<tr>
									<td colspan="5"></td>
								</tr>
							</tfoot>

							{section name=project loop=$myprojects}

								{*Color-Mix*}
								{if $smarty.section.project.index % 2 == 0}
									<tbody class="color-a" id="proj_{$myprojects[project].ID}" rel="{$myprojects[project].ID},{$myprojects[project].name},{$myprojects[project].daysleft},0,0,{$myprojects[project].done}">
								{else}
									<tbody class="color-b" id="proj_{$myprojects[project].ID}" rel="{$myprojects[project].ID},{$myprojects[project].name},{$myprojects[project].daysleft},0,0,{$myprojects[project].done}">
								{/if}

									<tr {if $myprojects[project].daysleft < 0 && $myprojects[project].daysleft != ""} class="marker-late" {elseif $myprojects[project].daysleft == "0"} class="marker-today" {/if} >
										<td>

											{if $userpermissions.projects.close}
												<a class="butn_check" href="javascript:closeElement('proj_{$myprojects[project].ID}','manageproject.php?action=close&amp;id={$myprojects[project].ID}');" title="{#close#}"></a>
											{/if}

										</td>
										<td>
											<div class="toggle-in">
												<span id="desktopprojectstoggle{$myprojects[project].ID}" class="acc-toggle" onclick="javascript:accord_projects.activate($$('#projecthead .accordion_toggle')[{$smarty.section.project.index}]);toggleAccordeon('projecthead',this);"></span>
												<a href="manageproject.php?action=showproject&amp;id={$myprojects[project].ID}" title="{$myprojects[project].name}">
													{$myprojects[project].name|truncate:33:"...":true}
												</a>
											</div>
										</td>
										<td>
											<div class="statusbar_b">
												<div class="complete" id="completed" style="width:{$myprojects[project].done}%;"></div>
											</div>
											<span>{$myprojects[project].done}%</span>
										</td>
										<td style="text-align:right">{$myprojects[project].daysleft}&nbsp;&nbsp;</td>
										<td class="tools">

											{if $userpermissions.projects.edit}
												<a class="tool_edit" href="javascript:void(0);" onclick="change('manageproject.php?action=editform&amp;id={$myprojects[project].ID}','form_addmyproject');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_addmyproject');" title="{#edit#}"></a>
											{/if}

											{if $userpermissions.projects.del}
												<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{$myprojects[project].ID}\',\'manageproject.php?action=del&amp;id={$myprojects[project].ID}\')');"  title="{#delete#}"></a>
											{/if}

										</td>
									</tr>

									<tr class="acc">
										<td colspan="5">
											<div class="accordion_toggle"></div>
											<div class="accordion_content">
												<div class="acc-in">
													<div class="message-in">
														{$myprojects[project].desc}
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							{/section}

						</table>

						<div class="tablemenue">
							<div class="tablemenue-in">
								{if $userpermissions.projects.add}
									<a class="butn_link" href="javascript:blindtoggle('form_addmyproject');" id="add_butn_myprojects" onclick="toggleClass('add_myprojects','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_deskprojects','smooth','nosmooth');">{#addproject#}</a>
								{/if}
							</div>
						</div>
					<div class="content-spacer"></div>
					</div> {* block END *}
			   </div> {* smooth END *}
			</div> {* projects END *}



		{/if} {* Projects END *}

		{* Tasks *}
		{if $tasknum > 0}

			<div class="tasks" style = "padding-bottom:2px;">
				<div class="headline">
				<a href="javascript:void(0);" id="taskhead_toggle" class="win_none" onclick="changeElements('a.win_block','win_none');toggleBlock('taskhead');"></a>

					<div class="wintools">
						<div class="export-main">
							<a class="export"><span>{#export#}</span></a>
							<div class="export-in" style="width:69px;left: -69px;"> {* at two items *}
								<a class="rss" href="managerss.php?action=rss-tasks&user={$userid}"><span>{#rssfeed#}</span></a>
								<a class="ical" href="managetask.php?action=ical"><span>{#icalexport#}</span></a>
								<a class="pdf" href="mytasks.php?action=pdf"><span>{#pdfexport#}</span></a>
							</div>
						</div>
					</div>

					<h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt="" />{#mytasks#}</h2>
				</div>

				<div class = "acc_toggle"></div>
				<div class="block acc_content" id="taskhead" style = "overflow:hidden;" >
					<div id="form_addmytask" class="addmenue" style="display:none;">
						{include file="addmytask_index.tpl" }
					</div>

					<div class="nosmooth" id="sm_desktoptasks">

						<table id="desktoptasks" cellpadding="0" cellspacing="0" border="0">

							<thead>
								<tr>
									<th class="a"></th>
									<th class="b" style="cursor:pointer;" onclick="sortBlock('desktoptasks','');">{#task#}</th>
									<th class="c" style="cursor:pointer;" onclick="sortBlock('desktoptasks','project');">{#project#}</th>
									<th class="d" style="cursor:pointer;text-align:right" onclick="sortBlock('desktoptasks','daysleft');">{#daysleft#}&nbsp;&nbsp;</th>
									<th class="tools"></th>
								</tr>
							</thead>

							<tfoot>
								<tr>
									<td colspan="5"></td>
								</tr>
							</tfoot>

							{section name=task loop=$tasks}

								{*Color-Mix*}
								{if $smarty.section.task.index % 2 == 0}
									<tbody class="color-a" id="task_{$tasks[task].ID}" rel="{$tasks[task].ID},{$tasks[task].title},{$tasks[task].daysleft},{$tasks[task].pname}">
								{else}
									<tbody class="color-b" id="task_{$tasks[task].ID}" rel="{$tasks[task].ID},{$tasks[task].title},{$tasks[task].daysleft},{$tasks[task].pname}">
								{/if}

									<tr {if $tasks[task].daysleft < 0} class="marker-late" {elseif $tasks[task].daysleft == 0} class="marker-today" {/if} >
										<td>
											{if $userpermissions.tasks.close}
												<a class="butn_check" href="javascript:closeElement('task_{$tasks[task].ID}','managetask.php?action=close&amp;tid={$tasks[task].ID}&amp;id={$tasks[task].project}');" title="{#close#}"></a>
											{/if}

										</td>
										<td>
											<div class="toggle-in">
												<span id="desktoptaskstoggle{$tasks[task].ID}" class="acc-toggle" onclick="javascript:accord_tasks.activate($$('#taskhead .accordion_toggle')[{$smarty.section.task.index}]);toggleAccordeon('taskhead',this);"></span>
												<a href="managetask.php?action=showtask&amp;id={$tasks[task].project}&amp;tid={$tasks[task].ID}" title="{$tasks[task].title}">
													{if $tasks[task].title != ""}
														{$tasks[task].title|truncate:33:"...":true}
													{else}
														{$tasks[task].text|truncate:33:"...":true}
													{/if}
												</a>
											</div>
										</td>
										<td>
											<a href="managetask.php?action=showproject&amp;id={$tasks[task].project}">{$tasks[task].pname|truncate:30:"...":true}</a>
										</td>
										<td style="text-align:right">{$tasks[task].daysleft}&nbsp;&nbsp;</td>
										<td class="tools">

											{if $userpermissions.tasks.edit}
												<a class="tool_edit" href="javascript:void(0);" onclick="change('managetask.php?action=editform&amp;tid={$tasks[task].ID}&amp;id={$tasks[task].project}','form_addmytask');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_addmytask');" title="{#edit#}"></a>
											{/if}

											{if $userpermissions.tasks.del}
												<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'task_{$tasks[task].ID}\',\'managetask.php?action=del&amp;tid={$tasks[task].ID}&amp;id={$tasks[task].project}\')');"  title="{#delete#}"></a>
											{/if}

										</td>
									</tr>

									<tr class="acc">
										<td colspan="5">
											<div class="accordion_toggle"></div>
											<div class="accordion_content">
												<div class="acc-in">
													<div class="message-in">
														{$tasks[task].text|nl2br}
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							{/section}

						</table>


						<div class="tablemenue">
							<div class="tablemenue-in">
								{if $userpermissions.tasks.add}
									<a class="butn_link" href="javascript:void(0);" id="add_butn_mytasks" onclick="blindtoggle('form_addmytask');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_desktoptasks','smooth','nosmooth');">{#addtask#}</a>
								{/if}
							</div>
						</div>
					<div class="content-spacer"></div>
					</div> {*block END*}
				</div> {* Smooth end *}
			</div> {*tasks END*}


		{/if} {* Tasks END *}

		{* Milestones *}
		{if $tasknum}
			<div class="miles" style = "padding-bottom:2px;">
				<div class="headline">
					<a href="javascript:void(0);" id="mileshead_toggle" class="win_none" onclick="changeElements('a.win_block','win_none');toggleBlock('mileshead');"></a>

					<div class="wintools">
						<div class="progress" id="progress" style="display:none;">
							<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/loader-cal.gif" />
						</div>
					</div>

					<h2>
						<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png" alt="" />{#calendar#}
					</h2>
				</div>

				<div class = "acc_toggle"></div>
				<div class="block acc_content" id="mileshead" style = "overflow:hidden;" >
					<div id="thecal" class="bigcal" style = "height:270px;"></div>
				<div class="content-spacer"></div>
				</div> {* block END *}
			</div> {* miles END *}
		 {* milestons END *}
		{/if}

		{* Messages *}
		{if $msgnum > 0}
			<div class="msgs" style = "padding-bottom:2px;">
				<div class="headline">
					<a href="javascript:void(0);" id="activityhead_toggle" class="win_none" onclick="changeElements('a.win_block','win_none');toggleBlock('activityhead');accordIndex.activate($$('#block_index .acc_toggle')[3]);"></a>

					<div class="wintools">
						<div class="export-main">
							<a class="export"><span>{#export#}</span></a>
							<div class="export-in" style="width:46px;left: -46px;"> {* at one item *}
								<a class="rss" href="managerss.php?action=mymsgs-rss&amp;user={$userid}"><span>{#rssfeed#}</span></a>
								<a class="pdf" href="managemessage.php?action=mymsgs-pdf&amp;id={$userid}"><span>{#pdfexport#}</span></a>
							</div>
						</div>
					</div>

					<h2>
						<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt="" />{#mymessages#}
					</h2>
				</div>
				<div class = "acc_toggle"></div>
				<div class="block acc_content" id="activityhead" style = "overflow:hidden;" >
				<div id = "addmsg" class="addmenue" style = "display:none;">
				</div>
					<table id="desktopmessages" cellpadding="0" cellspacing="0" border="0">

						<thead>
							<tr>
								<th class="a"></th>
								<th class="b" >{#message#}</th>
								<th class="ce">{#project#}</th>
								<th class="de">{#by#}</th>
								<th class="e">{#on#}</th>
								<th class="tools"></th>
							</tr>
						</thead>

						<tfoot>
							<tr>
								<td colspan="6"></td>
							</tr>
						</tfoot>

						{section name=message loop=$messages}

							{*Color-Mix*}
							{if $smarty.section.message.index % 2 == 0}
								<tbody class="color-a" id="messages_{$messages[message].ID}" rel="{$messages[message].ID},{$messages[message].title},{$messages[message].posted},0,0,0">
							{else}
								<tbody class="color-b" id="messages_{$messages[message].ID}" rel="{$messages[message].ID},{$messages[message].title},{$messages[message].posted},0,0,0">
							{/if}

								<tr>
									<td>
										{if $userpermissions.messages.close}
											<a class="butn_reply" href="managemessage.php?action=replyform&amp;mid={$messages[message].ID}&amp;id={$messages[message].project}" title="{#answer#}"></a>
										{/if}
									</td>
									<td>
										<div class="toggle-in">
											<span class="acc-toggle" onclick="javascript:accord_msgs.activate($$('#activityhead .accordion_toggle')[{$smarty.section.message.index}]);toggleAccordeon('activityhead',this);"></span>
											<a href="managemessage.php?action=showmessage&amp;mid={$messages[message].ID}&amp;id={$messages[message].project}" title="{$messages[message].title}">{$messages[message].title|truncate:35:"...":true}</a>
										</div>
									</td>
									<td>
										<a href="managemessage.php?action=showproject&amp;id={$messages[message].project}">{$messages[message].pname|truncate:20:"...":true}</a>
									</td>
									<td>
										<a href="manageuser.php?action=profile&amp;id={$messages[message].user}">{$messages[message].username|truncate:20:"...":true}</a>
									</td>
									<td>{$messages[message].postdate}</td>
									<td class="tools">
										{if $userpermissions.messages.edit}
											<a class="tool_edit" href="javascript:void(0);" onclick="change('managemessage.php?action=editform&amp;mid={$messages[message].ID}&amp;id={$messages[message].project}','addmsg');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('addmsg');" title="{#edit#}"></a>
										{/if}
										{if $userpermissions.messages.del}
											<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'messages_{$messages[message].ID}\',\'managemessage.php?action=del&amp;mid={$messages[message].ID}&amp;id={$messages[message].project}\')');"  title="{#delete#}"></a>
										{/if}
									</td>
								</tr>

								<tr class="acc">
									<td colspan="6">
										<div class="accordion_toggle"></div>
										<div class="accordion_content">
											<div class="acc-in">

												{if $messages[message].avatar != ""}
													<div class="avatar"><img src="thumb.php?width=80&amp;height=80&amp;pic=files/{$cl_config}/avatar/{$messages[message].avatar}" alt="" /></div>
												{else}
													{if $messages[message].gender == "f"}
														<div class="avatar"><img src="thumb.php?width=80&amp;height=80&amp;pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-female.jpg" alt="" /></div>
													{else}
														<div class="avatar"><img src="thumb.php?width=80&amp;height=80&amp;pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-male.jpg" alt="" /></div>
													{/if}
												{/if}

												<div class="message">
													<div class="message-in">
														{$messages[message].text|nl2br}
													</div>

													{* MILESTONE and TAGS *}
													{if $messages[message].tagnum > 1 or $messages[message].milestones[0] != ""}
														<div class="content-spacer-b"></div>

														{* MESSAGES-MILESTONES *}
														{if $messages[message].milestones[0] != ""}
															<p>
																<strong>{#milestone#}:</strong>
																<a href="managemilestone.php?action=showmilestone&amp;msid={$messages[message].milestones.ID}&amp;id={$messages[message].project}">{$messages[message].milestones.name}</a>
															</p>
														{/if}

														{* MESSAGES-TAGS *}
														{if $messages[message].tagnum > 1}
															<p>
																<strong>{#tags#}:</strong>
																{section name=tag loop=$messages[message].tagsarr}
																	<a href="managetags.php?action=gettag&tag={$messages[message].tagsarr[tag]}&amp;id={$messages[message].project}">{$messages[message].tagsarr[tag]}</a>,
																{/section}
															</p>
														{/if}
													{/if}

													{* MESSAGES-FILES *}
													{if $messages[message].files[0][0] > 0}
														<p class="tags-miles">
															<strong>{#files#}:</strong>
														</p>

														<div class="inwrapper">
															<ul>

																{section name=file loop=$messages[message].files}
																	<li>
																		<div class="itemwrapper" id="iw_{$messages[message].files[file].ID}">

																			<table cellpadding="0" cellspacing="0" border="0">
																				<tr>
																					<td class="leftmen" valign="top">
																						<div class="inmenue"></div>
																					</td>
																					<td class="thumb">
																						<a href="{$messages[message].files[file].datei}" {if $messages[message].files[file].imgfile == 1} rel="lytebox[]" {elseif $messages[message].files[file].imgfile == 2} rel = "lyteframe[text]" {/if} title="{$messages[message].files[file].name}">
																							{if $messages[message].files[file].imgfile == 1}
																								<img src="thumb.php?pic={$messages[message].files[file].datei}&amp;width=32" alt="{$ordner[file].name}" />
																							{else}
																								<img src="templates/{$settings.template}/theme/{$settings.theme}/images/files/{$messages[message].files[file].type}.png" alt="{$messages[message].files[file].name}" />
																							{/if}
																						</a>
																					</td>
																					<td class="rightmen" valign="top">
																						<div class="inmenue">
																							<a class="del" href="managefile.php?action=delete&amp;id={$myprojects[project].ID}&amp;file={$messages[message].files[file].ID}" title="{#delete#}" onclick="fadeToggle('iw_{$messages[message].files[file].ID}');"></a>
																						</div>
																					</td>
																				</tr>
																				<tr>
																					<td colspan="3"><span class="name">
																						<a href="{$messages[message].files[file].datei}" {if $messages[message].files[file].imgfile == 1} rel="lytebox[]" {elseif $messages[message].files[file].imgfile == 2} rel = "lyteframe[text]" {/if} title="{$messages[message].files[file].name}">{$messages[message].files[file].name|truncate:15:"...":true}</a></span>
																					</td>
																				<tr/>
																			</table>

																		</div> {*itemwrapper End*}
																	</li>
																{/section}

															</ul>
														</div> {*inwrapper End*}
														<div style="clear:both"></div>
													{/if}

												</div> {*div messages end*}

											</div>
										</div>
									</td>
								</tr>
							</tbody>
						{/section}
					</table>

					<div class="tablemenue"></div>

				</div> {* block END *}
			</div> {* messages END *}

		{/if}

		{literal}
			<script type="text/javascript">
				//initialize accordeons
				try{
					var accord_projects = new accordion('projecthead');
				}
				catch(e)
				{}
				try{
					var accord_tasks = new accordion('taskhead');
				}
				catch(e)
				{}
				try{
					var accord_msgs = new accordion('activityhead');
				}
				catch(e)
				{}
				//load calendar
				changeshow('manageajax.php?action=newcal','thecal','progress');

				//create blocks accordeon
				var accordIndex = new accordion('block_index', {
			    classNames : {
			        toggle : 'acc_toggle',
			        toggleActive : 'acctoggle_active',
			        content : 'acc_content'
			    }
			});

				/**
				 *
				 * @access public
				 * @return void
				 **/
				function activateAccordeon(theAccord){
					accordIndex.activate($$('#block_index .acc_toggle')[theAccord]);
					changeElements("#"+blockIds[theAccord]+" > a.win_block","win_none");
					setCookie("activeSlideIndex",theAccord);
				}
				var theBlocks = $$("#block_index > div .headline > a");
				//console.log(theBlocks);

				//loop through the blocks and add the accordion toggle link
				openSlide = 0;
				blockIds = [];
				for(i=0;i<theBlocks.length;i++)
				{
					var theId = theBlocks[i].getAttribute("id");

					//theId = theId.split("_");
					//theId = theId[0];
					blockIds.push(theId);

					theCook = readCookie("activeSlideIndex");
					//console.log(theCook);
					if(theCook > 0)
					{
						openSlide = theCook;
					}

					var theAction = theBlocks[i].getAttribute("onclick");
					theAction += "activateAccordeon("+i+");";
					theBlocks[i].setAttribute("onclick",theAction);
					//console.log(theBlocks[i].getAttribute("onclick"));
				}


				//accordIndex.activate($$('#block_index .acc_toggle')[0]);
				//activateAccordeon(openSlide);
				activateAccordeon(0);



			</script>
		{/literal}
</div> {* block index end*}
<div class="content-spacer"></div>
	</div> {* content-left-in END *}
</div> {* content-left END *}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}
