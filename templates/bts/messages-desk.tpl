{*Messages*}
{if $msgnum > 0}
<div class="msgs">
	<div class="headline navbar navbar-inverse clearfix">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="managemessage.php?action=mymsgs" title="{#mymessages#}">{#mymessages#}</a>
					<ul class="nav pull-right">
						<li class="divider-vertical"></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="icon-cog big"></i>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="rss" href="managerss.php?action=mymsgs-rss&amp;user={$userid}"><i class="icon-globe"></i> {#rssfeed#}</a>
								</li>
								<li>
									<a class="pdf" href="managemessage.php?action=mymsgs-pdf&amp;id={$userid}"><i class="icon-file"></i>{#pdfexport#}</a>
								</li>
								<li>
									<a href="javascript:void(0);" id="activityhead_toggle" class="{$actbar}" onclick="toggleBlock('activityhead');"><i class="icon-resize-vertical"></i> {#toggle#}</a>
								</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="block" id="activityhead" style="{$actstyle}">
		<table id="desktopmessages" class="table table-bordered table-stripped table-hover">
			<thead>
				<tr>
					<th class="a"></th>
					<th class="b">{#message#}</th>
					<th class="ce">{#project#}</th>
					<th class="de">{#by#}</th>
					<th class="e">{#on#}</th>
					<th class="tools"></th>
				</tr>
			</thead>
			{section name=message loop=$messages}
			{*Color-Mix*}
			{if $smarty.section.message.index % 2 == 0}
			<tbody class="color-a" id="messages_{$messages[message].ID}">
			{else}
			<tbody class="color-b" id="messages_{$messages[message].ID}">
			{/if}
				<tr>
					<td>
					{if $userpermissions.messages.close}
						<a class="butn_reply" href="managemessage.php?action=replyform&amp;mid={$messages[message].ID}&amp;id={$messages[message].project}" title="{#answer#}">{#answer#}</a>
					{/if}
					</td>
					<td>
						<a href="managemessage.php?action=showmessage&amp;mid={$messages[message].ID}&amp;id={$messages[message].project}" title="{$messages[message].title}">{$messages[message].title|truncate:35:"...":true}</a>
						<a href="#messages{$messages[message].ID}" class="pull-right btn btn-mini btn-info" data-toggle="modal">
							<i class="icon-envelope icon-white"></i>
						</a>
						<div class="modal hide fade" id="messages{$messages[message].ID}" tabindex="-1" role="dialog" aria-labelledby="messages{$messages[message].ID}Label" aria-hidden="true">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h3>{$messages[message].title} <small>{$messages[message].postdate}</small></h3>
							</div>
							<div class="modal-body">
							{* message content *}
							{if $messages[message].avatar != ""}
							<div class="avatar">
								<img src="thumb.php?width=80&amp;height=80&amp;pic=files/{$cl_config}/avatar/{$messages[message].avatar}" alt="{$messages[message].username}" />
							</div>
							{else}
								{if $messages[message].gender == "f"}
								<div class="avatar">
									<img src = "thumb.php?width=80&amp;height=80&amp;pic=templates/standard/images/no-avatar-female.jpg" alt="{$messages[message].username}" />
								</div>
								{else}
								<div class="avatar">
									<img src = "thumb.php?width=80&amp;height=80&amp;pic=templates/standard/images/no-avatar-male.jpg" alt="{$messages[message].username}" />
								</div>
								{/if}
							{/if}

							{$messages[message].text|nl2br}
							{*MILESTONE and TAGS*}
							
							{if $messages[message].tagnum > 1 or $messages[message].milestones[0] != ""}
								{*MESSAGES-MILESTONES*}
								{if $messages[message].milestones[0] != ""}
								<p>
									<strong>{#milestone#}:</strong>
									<a href="managemilestone.php?action=showmilestone&amp;msid={$messages[message].milestones.ID}&amp;id={$messages[message].project}">{$messages[message].milestones.name}</a>
								</p>
								{/if}

								{*MESSAGES-TAGS*}
								{if $messages[message].tagnum > 1}
								<p>
									<strong>{#tags#}:</strong>
									{section name = tag loop=$messages[message].tagsarr}
									<a href="managetags.php?action=gettag&tag={$messages[message].tagsarr[tag]}&amp;id={$messages[message].project}">{$messages[message].tagsarr[tag]}</a>,
									{/section}
								</p>
								{/if}
							{/if}

							{*MESSAGES-FILES*}
							{if $messages[message].files[0][0] > 0}
							<p class="tags-miles">
								<strong>{#files#}:</strong>
							</p>
							<div class="inwrapper clearfix">
								<ul>
									{section name = file loop=$messages[message].files}
									<li>
										<div class="itemwrapper" id="iw_{$messages[message].files[file].ID}">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td class="leftmen" valign="top"><div class="inmenue"></div></td>
													<td class="thumb">
														<a href = "{$messages[message].files[file].datei}" {if $messages[message].files[file].imgfile == 1} rel="lytebox[]" {elseif $messages[message].files[file].imgfile == 2} rel="lyteframe[text]" {/if} title="{$messages[message].files[file].name}"> 
														{if $messages[message].files[file].imgfile == 1}
															<img src="thumb.php?pic={$messages[message].files[file].datei}&amp;width=32" alt="{$ordner[file].name}" />
														{else}
															<img src="templates/standard/images/files/{$messages[message].files[file].type}.png" alt="{$messages[message].files[file].name}" />
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
													<td colspan="3">
														<a href="{$messages[message].files[file].datei}" {if $messages[message].files[file].imgfile == 1} rel="lytebox[]" {elseif $messages[message].files[file].imgfile == 2} rel="lyteframe[text]" {/if} title="{$messages[message].files[file].name}">
															{$messages[message].files[file].name|truncate:15:"...":true}
														</a>
													</td>
												</tr>
											</table>
										</div>
										{*itemwrapper End*}
									</li>
									{/section}
								</ul>
							</div>
							{*inwrapper End*}
							<div style="clear:both"></div>
							{/if}
							</div>
						</div>
					</td>
					<td>
						<a href="managemessage.php?action=showproject&amp;id={$messages[message].project}">{$messages[message].pname|truncate:20:"...":true}</a>
					</td>
					<td>
						<a href="manageuser.php?action=profile&amp;id={$messages[message].user}">{$messages[message].username|truncate:20:"...":true}</a>
					</td>
					<td>
						{$messages[message].postdate}
					</td>
					<td class="tools">
						{if $userpermissions.messages.edit}
						<a class="tool_edit" href="managemessage.php?action=editform&amp;mid={$messages[message].ID}&amp;id={$messages[message].project}&amp;redir=index.php" title="{#edit#}"><i class="icon-edit"></i></a>
						{/if}
						{if $userpermissions.messages.del}
						<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'messages_{$messages[message].ID}\',\'managemessage.php?action=del&amp;mid={$messages[message].ID}&amp;id={$messages[message].project}\')');"  title="{#delete#}"><i class="icon-trash"></i></a>
						{/if}
					</td>
				</tr>
			</tbody>
			{/section}
		</table>
	</div>
	{*block END*}
</div>
{*messages END*}
{/if}