{include file="header.tpl" jsload="ajax" jsload1="tinymce" jsload3="lightbox"}
{include file="tabsmenue-project.tpl" msgstab="active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="msgs">

			<div class="breadcrumb">
				<a href="manageproject.php?action=showproject&amp;id={$project.ID}">
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />
					{$projectname|truncate:40:"...":true}
				</a>
				<a href="managemessage.php?action=showproject&amp;id={$project.ID}">
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt="" />
					{#messages#}
				</a>
			</div>

			<h1 class="second">
				<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt="" />
				{$message.title|truncate:40:"...":true}
			</h1>

			<div class="statuswrapper">
				<ul>
					{if $userpermissions.messages.close}
						<li class="link"><a class="reply" id="add_reply_a" href="javascript:void(0);" onclick="blindtoggle('form_reply_a');toggleClass(this,'reply-active','reply');toggleClass('sm_replies_a','smooth','nosmooth');" title="{#reply#}"></a></li>
					{/if}
					{if $userpermissions.messages.edit}
						<li class="link"><a class="edit" href="javascript:void(0);" id="edit_butn" onclick="blindtoggle('form_edit');toggleClass(this,'edit-active','edit');toggleClass('sm_replies_a','smooth','nosmooth');" title="{#edit#}"></a></li>
					{/if}
					{if $message.replies}
						<li><a>{#replies#}: {$message.replies}</a></li>
					{/if}
				</ul>
			</div>

			{* Add Reply *}
			{if $userpermissions.messages.close}
				<div id="form_reply_a" class="addmenue" style="display:none;">
					<div class="content-spacer"></div>
					{include file="replyform.tpl" showhtml="no" reply="a"}
				</div>
			{/if}

			{* Edit Message *}
			{if $userpermissions.messages.edit}
				<div id="form_edit" class="addmenue" style="display:none;">
					<div class="content-spacer"></div>
					{include file="editmessageform.tpl" showhtml="no"}
				</div>
			{/if}

			<div class="content-spacer"></div>

			<div id="sm_replies_a" class="nosmooth">
				<div id="message" class="descript">
					{if $message.avatar != ""}
						<div class="avatar">
							<img src="thumb.php?width=80&amp;height=80&amp;pic=files/{$cl_config}/avatar/{$message.avatar}" alt="" />
						</div>
					{else}
						{if $message.gender == "f"}
							<div class="avatar">
								<img src="thumb.php?width=80&amp;height=80&amp;pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-female.jpg" alt="" />
							</div>
						{else}
							<div class="avatar">
								<img src="thumb.php?width=80&amp;height=80&amp;pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-male.jpg" alt="" />
							</div>
						{/if}
					{/if}

					<div class="message">
						<div class="message-in">
							<h2>{$message.endstring}&nbsp;/&nbsp;{$message.username|truncate:20:"...":true}</h2>
							{$message.text}
						</div>

						{if $message.tagsarr[0] != "" or $message.milestones[0] != ""}
							<div class="content-spacer-b"></div>

							{* Milestones *}
							{if $message.milestones[0] != ""}
								<p>
									<strong>{#milestone#}: </strong>
									<a href="managemilestone.php?action=showmilestone&amp;msid={$message.milestones.ID}&amp;id={$project.ID}">{$message.milestones.name}</a>
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
	<!--														<a href = "{$message.files[file].datei}"{if $message.files[file].imgfile == 1} rel="lytebox[img{$message.files[file].ID}]" {elseif $message.files[file].imgfile == 2} rel = "lyteframe[text{$message.files[file].ID}]"{/if} title="{$message.files[file].name}">-->
															<a href = "managefile.php?action=downloadfile&amp;id={$message.files[file].project}&amp;file={$message.files[file].ID}"{if $message.files[file].imgfile == 1} rel="lytebox[img{$message.ID}]"{/if} title="{$message.files[file].name}">
																{if $message.files[file].imgfile == 1}
																	<img src = "thumb.php?pic={$message.files[file].datei}&amp;width=32" alt="{$message.files[file].name}" />
																{else}
																	<img src = "templates/{$settings.template}/theme/{$settings.theme}/images/files/{$message.files[file].type}.png" alt="{$message.files[file].name}" />
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
															<!--	<a href = "{$message.files[file].datei}"{if $message.files[file].imgfile == 1} rel="lytebox[img{$message.files[file].ID}]" {elseif $message.files[file].imgfile == 2} rel = "lyteframe[text{$message.files[file].ID}]"{/if} title="{$message.files[file].name}">-->
														<a href = "managefile.php?action=downloadfile&amp;id={$message.files[file].project}&amp;file={$message.files[file].ID}"{if $message.files[file].imgfile == 1} rel="lytebox[img{$message.ID}]"{/if} title="{$message.files[file].name}">

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

        {include file="messageReplies.tpl"}
		</div> {*MSGs END*}
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}