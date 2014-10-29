{include file="header.tpl" jsload="ajax" jsload1="tinymce" jsload3="lightbox" stage="index"}
{include file="tabsmenue-desk.tpl" desktab="active"}

<div id="content-left">
	<div id="content-left-in">

		<h1>{#desktop#}</h1>
<div id="block_index" class="block">
		{* Projects *}
			<div class="projects"  style = "padding-bottom:2px;">
				<div class="headline">
					<a href="javascript:void(0);" id="projecthead_toggle" class="win_block" onclick="changeElements('a.win_block','win_none');toggleBlock('projecthead');"></a>

					<h2><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />{#closedprojects#}</h2>
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

                                                        {section name=doneproject loop=$myoldprojects}
                                                                {*Color-Mix*}
								{if $smarty.section.doneproject.index % 2 == 0}
									<tbody class="color-a" id="proj_{$myoldprojects[doneproject].ID}" rel="{$myoldprojects[doneproject].ID},{$myoldprojects[doneproject].name},{$myoldprojects[doneproject].daysleft},0,0,{$myoldprojects[project].done}">
								{else}
									<tbody class="color-b" id="proj_{$myoldprojects[doneproject].ID}" rel="{$myoldprojects[doneproject].ID},{$myoldprojects[doneproject].name},{$myoldprojects[doneproject].daysleft},0,0,{$myoldprojects[project].done}">
								{/if}

									<tr>
										<td class="a">

											{if $userpermissions.projects.add}
												<a class="butn_checked" href="manageproject.php?action=open&amp;id={$myoldprojects[doneproject].ID}" title="{#open#}"></a>
											{/if}

										</td>
										<td class="b">
											<div class="toggle-in">
												<span id="desktopprojectstoggle{$myoldprojects[doneproject].ID}" class="acc-toggle" onclick="javascript:accord_projects.activate($$('#projecthead .accordion_toggle')[{($smarty.section.project.total + $smarty.section.doneproject.index)}]);toggleAccordeon('projecthead',this);"></span>
												<a href="manageproject.php?action=showproject&amp;id={$myoldprojects[doneproject].ID}" title="{$myoldprojects[doneproject].name}">
													{$myoldprojects[doneproject].name|truncate:33:"...":true}
												</a>
											</div>
										</td>
										<td class="c">
											<div class="statusbar_b">
												<div class="complete" id="completed" style="width:{$myoldprojects[doneproject].done}%;"></div>
											</div>
											<span>{$myoldprojects[doneproject].done}%</span>
										</td>
										<td class="d" style="text-align:right">{$myoldprojects[doneproject].daysleft}&nbsp;&nbsp;</td>
										<td class="tools">

											{if $userpermissions.projects.edit}
												<a class="tool_edit" href="javascript:void(0);" onclick="change('manageproject.php?action=editform&amp;id={$myoldprojects[doneproject].ID}','form_addmyproject');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_addmyproject');" title="{#edit#}"></a>
											{/if}

											{if $userpermissions.projects.del}
												<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{$myoldprojects[doneproject].ID}\',\'manageproject.php?action=del&amp;id={$myoldprojects[doneproject].ID}\')');"  title="{#delete#}"></a>
											{/if}

										</td>
									</tr>

									<tr class="acc">
										<td colspan="5">
											<div class="accordion_toggle"></div>
											<div class="accordion_content">
												<div class="acc-in">
													<div class="message-in">
														{$myoldprojects[doneproject].desc}
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
                                                        {/section}
                                                        </table>
                                                        </div>
                                                        </div>


						

					<div class="content-spacer"></div>
					</div> {* block END *}
			   </div> {* smooth END *}
			</div> {* projects END *}

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

{include file="sidebar-a.tpl"}

<div class="content-spacer"></div>
	</div> {* content-left-in END *}
</div> {* content-left END *}
{include file="footer.tpl"}
