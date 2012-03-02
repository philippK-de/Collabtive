{include file="header.tpl"  jsload = "ajax" jsload1 = "tinymce" jsload3 = "lightbox"}
{include file="tabsmenue-project.tpl" msgstab = "active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="msgs">

			<div class="infowin_left" style = "display:none;" id = "systemmsg">
				{if $mode == "added"}
					<span class="info_in_green"><img src="templates/standard/images/symbols/msg.png" alt=""/>{#messagewasadded#}</span>
				{elseif $mode == "edited"}
					<span class="info_in_yellow"><img src="templates/standard/images/symbols/msg.png" alt=""/>{#messagewasedited#}</span>
				{elseif $mode == "deleted"}
					<span class="info_in_red"><img src="templates/standard/images/symbols/msg.png" alt=""/>{#messagewasdeleted#}</span>
				{elseif $mode == "replied"}
					<span class="info_in_green"><img src="templates/standard/images/symbols/msgs.png" alt=""/>{#replywasadded#}</span>
				{/if}
			</div>
			
			{literal}
				<script type = "text/javascript">
					apperar = new Effect.Appear('systemmsg', { duration: 2.0 })
					makeTimer("new Effect.Fade('systemmsg', { duration: 2.0 })",7000);
				</script>
			{/literal}

			<div class="breadcrumb">
				<a href="manageproject.php?action=showproject&amp;id={$project.ID}"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$projectname|truncate:40:"...":true}</a>
				<a href="managemessage.php?action=showproject&amp;id={$project.ID}"><img src="./templates/standard/images/symbols/msgs.png" alt="" />{#messages#}</a>
			</div>

			<h1 class="second"><img src="./templates/standard/images/symbols/msgs.png" alt="" />{$message.title|truncate:40:"...":true}</h1>

			<div class="statuswrapper">
				<ul>
					{if $userpermissions.messages.close}
						<li class="link"><a class="reply" id="add_reply_a" href="javascript:void(0);" onclick="blindtoggle('form_reply_a');toggleClass(this,'reply-active','reply');toggleClass('sm_replies_a','smooth','nosmooth');" title="{#reply#}"></a></li>
					{/if}
					{if $userpermissions.messages.edit}
						<li class="link"><a class="edit" href="javascript:void(0);"  id="edit_butn" onclick="blindtoggle('form_edit');toggleClass(this,'edit-active','edit');toggleClass('sm_replies_a','smooth','nosmooth');" title="{#edit#}"></a></li>
					{/if}
					{if $message.replies}
						<li><a>{#replies#}: {$message.replies}</a></li>
					{/if}
				</ul>
			</div>

			{*Add Reply*}
			{if $userpermissions.messages.close}
				<div id = "form_reply_a" class="addmenue" style = "display:none;">
					<div class="content-spacer"></div>
					{include file="replyform.tpl" showhtml="no" reply="a"}
				</div>
			{/if}

			{*Edit Message*}
			{if $userpermissions.messages.edit}
				<div id = "form_edit" class="addmenue" style = "display:none;">
					<div class="content-spacer"></div>
					{include file="editmessageform.tpl" showhtml="no" }
				</div>
			{/if}

			<div class="content-spacer"></div>

			<div id="sm_replies_a" class="nosmooth">
				<div id="message" class="descript">
					{if $message.avatar != ""}
						<div class="avatar"><img src = "thumb.php?width=80&amp;height=80&amp;pic=files/{$cl_config}/avatar/{$message.avatar}" alt="" /></div>
					{else}
						{if $message.gender == "f"}
							<div class="avatar"><img src = "thumb.php?width=80&amp;height=80&amp;pic=templates/standard/images/no-avatar-female.jpg" alt="" /></div>
						{else}
							<div class="avatar"><img src = "thumb.php?width=80&amp;height=80&amp;pic=templates/standard/images/no-avatar-male.jpg" alt="" /></div>
						{/if}
					{/if}

					<div class="message">
						<div class="message-in">
							<h2>{$message.endstring}&nbsp;/&nbsp;{$message.username|truncate:20:"...":true}</h2>
							{$message.text}
						</div>

						{if $message.tagsarr[0] != "" or $message.milestones[0] != ""}
							<div class="content-spacer-b"></div>
						
							{*Milestones*}
							{if $message.milestones[0] != ""}
								<p>
									<strong>{#milestone#}: </strong>
									<a href = "managemilestone.php?action=showmilestone&amp;msid={$message.milestones.ID}&amp;id={$project.ID}">{$message.milestones.name}</a>
								</p>
							{/if}

							{*Tags*}
							{if $message.tagsarr[0] != ""}
								<p>
									<strong>{#tags#}: </strong>
									{section name = tag loop=$message.tagsarr}
										<a href = "managetags.php?action=gettag&tag={$message.tagsarr[tag]}&amp;id={$project.ID}">{$message.tagsarr[tag]}</a>
									{/section}
								</p>
							{/if}
						{/if}

						{*Files*}
						{if $message.files[0][0] > 0}
							<div class="content-spacer-b"></div>
							<strong>{#files#}:</strong>

							<div class="inwrapper">
								<ul>
									{section name = file loop=$message.files}
										<li id="fli_{$message.files[file].ID}">
											<div class="itemwrapper" id="iw_{$message.files[file].ID}">

												<table cellpadding="0" cellspacing="0" border="0">
													<tr>
														<td class="leftmen" valign="top">
															<div class="inmenue"></div>
														</td>
														<td class="thumb">
															<a href = "{$message.files[file].datei}"{if $message.files[file].imgfile == 1} rel="lytebox[img{$message.files[file].ID}]" {elseif $message.files[file].imgfile == 2} rel = "lyteframe[text{$message.files[file].ID}]"{/if} title="{$message.files[file].name}">
																{if $message.files[file].imgfile == 1}
																	<img src = "thumb.php?pic={$message.files[file].datei}&amp;width=32" alt="{$message.files[file].name}" />
																{else}
																	<img src = "templates/standard/images/files/{$message.files[file].type}.png" alt="{$message.files[file].name}" />
																{/if}
															</a>
														</td>
														<td class="rightmen" valign="top">
															<div class="inmenue">
																{if $userpermissions.files.del}
																	<a class="del" href="javascript:void(0);" onclick = "javascript:confirmfunction('{$langfile.confirmdel}','deleteElement(\'fli_{$message.files[file].ID}\',\'managefile.php?action=delete&id={$message.project}&file={$message.files[file].ID}\')');" title="{#delete#}"></a>
																{/if}
															</div>
														</td>
													</tr>
													<tr>
														<td colspan="3">
															<span class="name">
																<a href = "{$message.files[file].datei}"{if $message.files[file].imgfile == 1} rel="lytebox[img{$message.files[file].ID}]" {elseif $message.files[file].imgfile == 2} rel = "lyteframe[text{$message.files[file].ID}]"{/if} title="{$message.files[file].name}">
																	{if $message.files[file].title != ""}
																	{$message.files[file].title|truncate:13:"...":true}
																	{else}
																	{$message.files[file].name|truncate:13:"...":true}
																	{/if}
																</a>
															</span>
														</td>
													<tr/>
												</table>

											</div> {*itemwrapper End*}
										</li>
									{/section} {*files End*}
								</ul>
							</div> {*inwrapper End*}
						{/if}
					</div> {*message End*}
				</div> {*nosmooth End*}
				<div class="content-spacer"></div>
			</div> {*descript End*}

			{* ANSWERS *}
			{if $replies > 0}
				<div class="headline">
					<a href="javascript:void(0);" id="block-answers_toggle" class="win_block" onclick = "toggleBlock('block-answers');"></a>
					<div class="wintools">
						{if $userpermissions.messages.close}
							<a class="add" href="javascript:blindtoggle('form_reply_b');" id="add_replies" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_replies','butn_link_active','butn_link');toggleClass('sm_replies','smooth','nosmooth');"><span>{#answer#}</span></a>
						{/if}
					</div>
					<h2><img src="./templates/standard/images/symbols/msgs.png" alt="" />{#replies#}</a></h2>
				</div>

				<div id="block-answers" class="block">
					{*Add Reply*}
					{if $userpermissions.messages.close}
						<div id = "form_reply_b" class="addmenue" style = "display:none;">
							{include file="replyform.tpl" showhtml="no"}
						</div>
					{/if}

					<div class="nosmooth" id="sm_replies">
						<table id="acc_replies" cellpadding="0" cellspacing="0" border="0">

							<thead>
								<tr>
									<th class="a"></th>
									<th class="b">{#message#}</th>
									<th class="c">{#on#}</th>
									<th class="d">{#by#}</th>
									<th class="tools"></th>
								</tr>
							</thead>

							<tfoot>
								<tr>
									<td colspan="5"></td>
								</tr>
							</tfoot>

							{section name=reply loop=$replies}

								{*Color-Mix*}
								{if $smarty.section.reply.index % 2 == 0}
								<tbody class="color-a" id="reply_{$replies[reply].ID}">
								{else}
								<tbody class="color-b" id="reply_{$replies[reply].ID}">
								{/if}
									<tr>
										<td></td>
										<td>
											<div class="toggle-in">
												<span class="acc-toggle" onclick="javascript:accord_answer.activate($$('#acc_replies .accordion_toggle')[{$smarty.section.reply.index}]);toggleAccordeon('acc_replies',this);">{$replies[reply].title|truncate:20:"...":true}</span>
											</div>
										</td>
										<td>{$replies[reply].postdate}</td>
										<td>
											<a href="manageuser.php?action=profile&amp;id={$replies[reply].user}">{$replies[reply].username|truncate:20:"...":true}</a>
										</td>
										<td class="tools">
											{if $userpermissions.messages.edit}
											<a class="tool_edit" href="managemessage.php?action=editform&amp;mid={$replies[reply].ID}&amp;id={$message.project}" title="{#edit#}"></a>
											{/if}
											{if $userpermissions.messages.del}
											<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'reply_{$replies[reply].ID}\',\'managemessage.php?action=del&amp;mid={$replies[reply].ID}&amp;id={$message.project}\')');"  title="{#delete#}"></a>
											{/if}
										</td>
									</tr>

									<tr class="acc">
										<td colspan="5">
											<div class="accordion_toggle"></div>
											<div class="accordion_content">
												<div class="acc-in">

													{if $replies[reply].avatar != ""}
														<div class="avatar"><img src = "thumb.php?width=80&amp;height=80&amp;pic=files/{$cl_config}/avatar/{$replies[reply].avatar}" alt="" /></div>
													{else}
														{if $replies[reply].gender == "f"}
															<div class="avatar"><img src = "thumb.php?width=80&amp;height=80&amp;pic=templates/standard/images/no-avatar-female.jpg" alt="" /></div>
														{else}
															<div class="avatar"><img src = "thumb.php?width=80&amp;height=80&amp;pic=templates/standard/images/no-avatar-male.jpg" alt="" /></div>
														{/if}
													{/if}
													
													<div class="message">
														<div class="message-in">
															{$replies[reply].text|nl2br}
														</div>

														{*MESSAGETAGS*}
														{if $replies[reply].tagsarr[0] != ""}
															<p class="tags-miles">
																<strong>{#tags#}: </strong>
																{section name = tag loop=$replies[reply].tagsarr}
																	<a href = "managetags.php?action=gettag&tag={$replies[reply].tagsarr[tag]}&amp;id={$project.ID}">{$replies[reply].tagsarr[tag]}</a>
																{/section}
															</p>
														{/if}

													{*MESSAGEFILES*}
													{if $replies[reply].files[0][0] > 0}
														<div class="content-spacer-b"></div>
														<strong>{#files#}:</strong>
														<div class="inwrapper">
															<ul>
																{section name = file loop=$replies[reply].files}
																	<li id="fli_{$replies[reply].files[file].ID}">
																		<div class="itemwrapper">

																			<table cellpadding="0" cellspacing="0" border="0">
																				<tr>
																					<td class="leftmen" valign="top">
																						<div class="inmenue"></div>
																					</td>
																					<td class="thumb">
																						<a href = "{$replies[reply].files[file].datei}"{if $replies[reply].files[file].imgfile == 1} rel="lytebox[img{$replies[reply].files[file].ID}]" {elseif $replies[reply].files[file].imgfile == 2} rel = "lyteframe[text{$replies[reply].files[file].ID}]"{/if} title="{$replies[reply].files[file].name}">
																							{if $replies[reply].files[file].imgfile == 1}
																							<img src = "thumb.php?pic={$replies[reply].files[file].datei}&amp;width=32" alt="" />
																							{else}
																							<img src = "templates/standard/images/files/{$replies[reply].files[file].type}.png" alt="" />
																							{/if}
																						</a>
																					</td>
																					<td class="rightmen" valign="top">
																						<div class="inmenue">
																							{if $userpermissions.messages.del}
																								<a class="del" href="javascript:void(0);" onclick = "javascript:confirmfunction('{$langfile.confirmdel}','deleteElement(\'fli_{$replies[reply].files[file].ID}\',\'managefile.php?action=delete&id={$myprojects[project].ID}&file={$replies[reply].files[file].ID}\')');"></a>
																							{/if}
																						</div>
																					</td>
																				</tr>
																				<tr>
																					<td colspan="3">
																						<span class="name"><a href = "{$replies[reply].files[file].datei}"{if $replies[reply].files[file].imgfile == 1} rel="lytebox[img{$replies[reply].files[file].ID}]" {elseif $replies[reply].files[file].imgfile == 2} rel = "lyteframe[text{$replies[reply].files[file].ID}]"{/if} title="{$replies[reply].files[file].name}">{$replies[reply].files[file].name|truncate:15:"...":true}</a></span>
																					</td>
																				<tr/>
																			</table>

																		</div> {*itemwrapper End*}
																	</li>
																{/section}
															</ul>
														</div> {*inwrapper End*}
													{/if}
												</div>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						{/section}
					</table>
				</div> {*nosmooth End*}

				<div class="tablemenue">
					<div class="tablemenue-in">
						<a class="butn_link" href="javascript:blindtoggle('form_reply_b');"  id="add_butn_replies" onclick="toggleClass('add_replies','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_replies','smooth','nosmooth');">{#answer#}</a>
					</div>
				</div>

				</div> {*block End*}

				{literal}
					<script type = "text/javascript">
						var accord_answer = new accordion('acc_replies');
					</script>
				{/literal}

				<div class="content-spacer"></div>
			{/if} {*if REPLY End*}

		</div> {*MSGs END*}
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}