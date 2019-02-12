{include file="header.tpl"  jsload="ajax"  jsload1="tinymce" jsload3="lightbox"}
{include file="tabsmenue-desk.tpl" msgstab="active"}
<div class="row-fluid">
	<div id="content-left" class="span9">
		<div class="msgs">
			<div class="infowin_left" style="display:none;" id="systemmsg">
				{if $mode == "added"}
					<span class="info_in_green"><img src="templates/standard/images/symbols/msgs.png" alt=""/>{#messagewasadded#}</span>
				{elseif $mode == "edited"}
					<span class="info_in_yellow"><img src="templates/standard/images/symbols/msgs.png" alt=""/>{#messagewasedited#}</span>
				{elseif $mode == "deleted"}
					<span class="info_in_red"><img src="templates/standard/images/symbols/msgs.png" alt=""/>{#messagewasdeleted#}</span>
				{elseif $mode == "replied"}
					<span class="info_in_green"><img src="templates/standard/images/symbols/msgs.png" alt=""/>{#replywasadded#}</span>
				{/if}
			</div>
{literal}
<script type="text/javascript">
	apperar = new Effect.Appear('systemmsg', { duration: 2.0 })
</script>
{/literal}
		<div class="dropdown export pull-right">
			<a class="dropdown-toggle btn btn-mini" data-toggle="dropdown" href="#">{#export#} <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				<li>
					<a class="pdf" href="managemessage.php?action=mymsgs-pdf&amp;id={$userid}"><i class="icon-file"></i> {#pdfexport#}</a>
				</li>
				<li>
					<a class="rss" href="managerss.php?action=mymsgs-rss&amp;user={$userid}"><i class="icon-globe"></i> {#rssfeed#}</a>
				</li>
			</ul>
		</div>
		<h1>{#mymessages#}</h1>
		<div class="clear"></div>
		{section name=project loop=$myprojects}
		{if $myprojects[project].messages > 0}
		<div class="headline navbar navbar-inverse clearfix">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" ref="managemessage.php?action=showproject&amp;id={$myprojects[project].ID}" title="{$myprojects[project].name} / {#mymessages#}">{$myprojects[project].name|truncate:80:"...":true}</a>
					<ul class="nav pull-right">
						<li class="divider-vertical"></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="icon-cog big"></i>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								{if $userpermissions.tasks.add}
								<li>
									<a class="add" href="javascript:blindtoggle('addmsg{$myprojects[project].ID}');" id="add_{$myprojects[project].ID}" onclick="toggleClass(this,'add-active','add');"><i class="icon-pencil"></i> {#addmessage#}</a>
								</li>
								{/if}
								<li>
									<a href="javascript:void(0);" id="block-{$myprojects[project].ID}_toggle" class="win_block" onclick="toggleBlock('block-{$myprojects[project].ID}');"><i class="icon-resize-vertical"></i> {#toggle#}</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>	
		<div id="block-{$myprojects[project].ID}" class="block">
		{*Add Message*}
		<div id="addmsg{$myprojects[project].ID}" class="addmenue" style="display:none;">
			{include file="addmymessage.tpl" }
		</div>
		<table id="acc_{$myprojects[project].ID}" class="table table-bordered table-hover">
			<thead>
				<tr>
					<th class="a"></th>
					<th class="b">{#message#}</th>
					<th class="ce" style="text-align:right">{#replies#}&nbsp;&nbsp;</th>
					<th class="de">{#by#}</th>
					<th class="e">{#on#}</th>
					<th class="tools"></th>
				</tr>
			</thead>
			{section name=message loop=$myprojects[project].messages}
			{*Color-Mix*}
			{if $smarty.section.message.index % 2 == 0}
			<tbody class="color-a" id="msgs_{$myprojects[project].messages[message].ID}">
			{else}
			<tbody class="color-b" id="msgs_{$myprojects[project].messages[message].ID}">
			{/if}
				<tr>
					<td>
					{if $userpermissions.messages.add}<a class="butn_reply" href="managemessage.php?action=replyform&amp;mid={$myprojects[project].messages[message].ID}&amp;id={$myprojects[project].ID}" title="{#answer#}">{#answer#}</a>{/if}
					</td>
					<td>
						<a href="managemessage.php?action=showmessage&amp;mid={$myprojects[project].messages[message].ID}&amp;id={$myprojects[project].ID}" title="{$myprojects[project].messages[message].title}">{$myprojects[project].messages[message].title|truncate:35:"...":true}</a>
							<a href="#open{$myprojects[project].messages[message].ID}" class="btn btn-mini btn-info pull-right" data-toggle="modal" title="Information"><i class="icon-info-sign icon-white"></i></a>
							<div class="modal hide fade" id="open{$myprojects[project].messages[message].ID}" tabindex="-1" role="dialog" aria-labelledby="open{$myprojects[project].messages[message].title}}Label" aria-hidden="true">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3>{$myprojects[project].messages[message].title}</h3>
								</div>
								<div class="modal-body">
									{$myprojects[project].messages[message].text|nl2br|strip_tags|truncate:'400'}
								</div>
							</div>
					</td>
					<td style="text-align:right">
					{if $myprojects[project].messages[message].replies > 0}
						<a href="managemessage.php?action=showmessage&amp;mid={$myprojects[project].messages[message].ID}&amp;id={$myprojects[project].ID}#replies">{$myprojects[project].messages[message].replies}</a>
					{else}
						{$myprojects[project].messages[message].replies}
					{/if}
					</td>
					<td>
						<a href="manageuser.php?action=profile&amp;id={$myprojects[project].messages[message].user}">{$myprojects[project].messages[message].username|truncate:20:"...":true}</a>
					</td>
					<td>
						{$myprojects[project].messages[message].postdate}
					</td>
					<td class="tools">
					{if $userpermissions.messages.edit}
						<a class="tool_edit" href="managemessage.php?action=editform&amp;mid={$myprojects[project].messages[message].ID}&amp;id={$myprojects[project].ID}" title="{#edit#}"><i class="icon-edit"></i></a>
					{/if}
					{if $userpermissions.messages.del}
						<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'msgs_{$myprojects[project].messages[message].ID}\',\'managemessage.php?action=del&amp;mid={$myprojects[project].messages[message].ID}&amp;id={$myprojects[project].ID}\')');" title="{#delete#}"><i class="icon-trash"></i></a>
					{/if}
					</td>
				</tr>
<!--
							<tr class="acc">
								<td colspan="6">
									<div class="accordion_toggle"></div>
									<div class="accordion_content">
										<div class="acc-in">

											{if $myprojects[project].messages[message].avatar != ""}
											<div class="avatar"><img src = "thumb.php?width=80&amp;height=80&amp;pic=files/{$cl_config}/avatar/{$myprojects[project].messages[message].avatar}" alt="" /></div>
											{else}
											{if $myprojects[project].messages[message].gender == "f"}
											<div class="avatar"><img src = "thumb.php?width=80&amp;height=80&amp;pic=templates/standard/images/no-avatar-female.jpg" alt="" /></div>
											{else}
											<div class="avatar"><img src = "thumb.php?width=80&amp;height=80&amp;pic=templates/standard/images/no-avatar-male.jpg" alt="" /></div>
											{/if}
											{/if}

											<div class="message">
												<div class="message-in">
													{$myprojects[project].messages[message].text}
												</div>


												{*MILESTONE and TAGS*}
												{if $myprojects[project].messages[message].tagnum > 1 or $myprojects[project].messages[message].milestones[0] != ""}
												<div class="content-spacer-b"></div>

												{*MESSAGES-MILESTONES*}
												{if $myprojects[project].messages[message].milestones[0] != ""}
												<p>
													<strong>{#milestone#}:</strong>
													<a href = "managemilestone.php?action=showmilestone&amp;msid={$myprojects[project].messages[message].milestones.ID}&amp;id={$myprojects[project].ID}">{$myprojects[project].messages[message].milestones.name}</a>
												</p>
												{/if}

												{*MESSAGES-TAGS*}
												{if $myprojects[project].messages[message].tagnum > 1}
												<p>
													<strong>{#tags#}:</strong>
													{section name = tag loop=$myprojects[project].messages[message].tagsarr}
														<a href = "managetags.php?action=gettag&tag={$myprojects[project].messages[message].tagsarr[tag]}&amp;id={$myprojects[project].messages[message].project}">{$myprojects[project].messages[message].tagsarr[tag]}</a>,
													{/section}
												</p>
												{/if}
												{/if}


												{*MESSAGES-FILES*}
												{if $myprojects[project].messages[message].files[0][0] > 0}
												<p class="tags-miles">
													<strong>{#files#}:</strong>
												</p>

												<div class="inwrapper">
													<ul>
														{section name = file loop=$myprojects[project].messages[message].files}
														<li id="fli_{$myprojects[project].messages[message].files[file].ID}">
															<div class="itemwrapper">
																<table cellpadding="0" cellspacing="0" border="0">
																	<tr>
																		<td class="leftmen" valign="top">
																			<div class="inmenue"></div>
																		</td>
																		<td class="thumb">
																			<a href = "{$myprojects[project].messages[message].files[file].datei}"{if $myprojects[project].messages[message].files[file].imgfile == 1} rel="lytebox[img{$myprojects[project].messages[message].ID}]" {elseif $myprojects[project].messages[message].files[file].imgfile == 2} rel = "lyteframe[text{$myprojects[project].messages[message].ID}]"{/if} title="{$myprojects[project].messages[message].files[file].name}">
																			{if $myprojects[project].messages[message].files[file].imgfile == 1}
																			<img src = "thumb.php?pic={$myprojects[project].messages[message].files[file].datei}&amp;width=32" alt="" />
																			{else}
																			<img src = "templates/standard/images/files/{$myprojects[project].messages[message].files[file].type}.png" alt="" />
																			{/if}</a>
																		</td>
																		<td class="rightmen" valign="top">
																			<div class="inmenue">
																				{if $userpermissions.files.del}
																					<a class="del" href="javascript:confirmfunction('{$langfile.confirmdel}','deleteElement(\'fli_{$myprojects[project].messages[message].files[file].ID}\',\'managefile.php?action=delete&id={$myprojects[project].ID}&file={$myprojects[project].messages[message].files[file].ID}\')');" title="{#delete#}"></a>
																				{/if}
																			</div>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="3">
																			<span class="name">
																				<a href = "{$myprojects[project].messages[message].files[file].datei}"{if $myprojects[project].messages[message].files[file].imgfile == 1} rel="lytebox[img{$myprojects[project].messages[message].ID}]" {elseif $myprojects[project].messages[message].files[file].imgfile == 2} rel = "lyteframe[text{$myprojects[project].messages[message].ID}]"{/if} title="{$myprojects[project].messages[message].files[file].name}">{$myprojects[project].messages[message].files[file].name|truncate:15:"...":true}</a>
																			</span>
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
											</div> {*MESSAGES END*}

										</div>
									</div>
								</td>
							</tr>
-->

				</tbody>
			{/section}
			</table>
			</div> {*block END*}
		{/if}
		{/section}
		</div> {*Msgs END*}
	</div>
	{include file="sidebar-a.tpl"}
</div>
{include file="footer.tpl"}