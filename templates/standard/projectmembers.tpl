{include file="header.tpl" jsload="ajax"}
{include file="tabsmenue-project.tpl" userstab="active"}

<div id="content-left">
<div id="content-left-in">
<div class="user">

    <!-- System messages -->
    <div class="infowin_left display-none"
         id="userSystemMessage"
         data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png"
         data-text-added="{#userwasadded#}"
         data-text-edited="{#userwasedited#}"
         data-text-deleted="{#userwasdeleted#}"
         data-text-assigned="{#userwasassigned#}"
         data-text-deassigned="{#userwasdeassigned#}"
         >
    </div>

<h1>{$projectname|truncate:45:"...":true}<span>/ {#members#}</span></h1>

			<div class="headline">
				<a href="javascript:void(0);" id="block_members_toggle" class="win_block" onclick = "toggleBlock('block_members');"></a>

				<div class="wintools">
					{if $userpermissions.projects.edit}
						<a class="add" href="javascript:blindtoggle('form_member');" id="addmember" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_member','butn_link_active','butn_link');toggleClass('sm_member','smooth','nosmooth');"><span>{#adduser#}</span></a>
					{/if}
				</div>

				<h2>
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png" alt="" />{#members#}
				</h2>

			</div>

			<div id="block_members" class="blockwrapper">
				{*Add User*}
				{if $userpermissions.projects.edit}
					<div id = "form_member" class="addmenue display-none">
						{include file="adduserproject.tpl" }
					</div>
				{/if}

				<div class="nosmooth" id="sm_member">
					<div class="contenttitle">
						<div class="contenttitle_menue">
							{*place for tool under ne title-icon*}
						</div>
						<div class="contenttitle_in">
							{*place for header-informations*}
						</div>
					</div>
					<div class="content_in_wrapper">
					<div class="content_in_wrapper_in">
						<div class="inwrapper">
							<ul>
							{section name=member loop=$members}
								<li>
									<div class="itemwrapper" id="iw_{$folders[fold].ID}">

											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td class="leftmen" valign="top">
														<div class="inmenue">
															{if $members[member].avatar != ""}
																<a class="more" href="javascript:fadeToggle('info_{$members[member].ID}');"></a>
															{/if}
														</div>
													</td>
													<td class="thumb">
														<a href="manageuser.php?action=profile&amp;id={$members[member].ID}" title="{$members[member].name}">
															{if $members[member].gender == "f"}
																<img src = "./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/user-icon-female.png" alt="" />
															{else}
																<img src = "./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/user-icon-male.png" alt="" />
															{/if}
														</a>
													</td>
													<td class="rightmen" valign="top">
														<div class="inmenue">
															{if $userpermissions.projects.edit}
																<a class="del" href="manageproject.php?action=deassignform&amp;id={$project.ID}&amp;user={$members[member].ID}" title="{#deassign#}"></a>
																<a class="edit" href="admin.php?action=editform&id={$members[member].ID}" title="{#edituser#}"></a>
															{/if}
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="3">
														<span class="name">
															<a href = "manageuser.php?action=profile&amp;id={$members[member].ID}" title="{$members[member].name}">
																{if $members[member].name != ""}
																	{$members[member].name|truncate:13:"...":true}
																{else}
																	{#user#}
																{/if}
															</a>
														</span>
													</td>
												<tr/>
											</table>

											{if $members[member].avatar != ""}
											<div class="moreinfo-wrapper">
												<div class="moreinfo display-none" id="info_{$members[member].ID}">
													<img src = "thumb.php?pic=files/{$cl_config}/avatar/{$members[member].avatar}&amp;width=82" alt="" onclick="fadeToggle('info_{$members[member].ID}');" />
													<span class="name"><a href="manageuser.php?action=profile&amp;id={$members[member].ID}">{$members[member].name|truncate:15:"...":true}</a></span>
												</div>
											</div>
											{/if}

									</div> {*itemwrapper End*}
								</li>
							{/section} {*lop folder End*}

							</ul>
						</div> {*inwrapper End*}



			</div> {*content_in_wrapper_in End*}

			</div> {*content_in_wrapper End*}

			<div class="staterow">
				<div class="staterowin">
					{*place for whatever*}
				</div>

				<div class="staterowin_right"> <span >{$langfile.page} </span></div>
			</div>


			</div> {*nosmooth End*}
			<div class="tablemenue">
					<div class="tablemenue-in">
						{if $userpermissions.projects.edit}
							<a class="butn_link" href="javascript:blindtoggle('form_member');" id="add_butn_member" onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('addmember','add-active','add');toggleClass('sm_member','smooth','nosmooth');">{#adduser#}</a>
						{/if}
					</div>
			</div>
			</div> {*block_files End*}


<div class="content-spacer"></div>


</div> {*User END*}
</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}