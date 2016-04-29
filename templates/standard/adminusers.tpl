{include file="header.tpl" jsload="ajax"}
{include file="tabsmenue-admin.tpl" usertab="active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="user">
		
			<div class="infowin_left" style="display:none;" id="systemmsg">
				
				{if $mode == "deleted"}
				<span class="info_in_red">
					<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png" alt="" />
					{#userwasdeleted#}
				</span>
				{elseif $mode == "added"}
				<span class="info_in_green">
					<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png" alt="" />
					{#userwasadded#}
				</span>
				{elseif $mode == "roleadded"}
				<span class="info_in_green">
					<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png" alt="" />
					{#roleadded#}
				</span>
				{elseif $mode == "roleedited"}
				<span class="info_in_yellow">
					<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png" alt="" />
					{#roleedited#}
				</span>
				{elseif $mode == "edited"}
					<span class="info_in_yellow">
					<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png" alt="" />
					{#userwasedited#}
				</span>
		        {elseif $mode == "de-assigned"}
				<span class="info_in_yellow">
					<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png" alt="" />
					{#permissionswereedited#}
				</span>
				{/if}
				
			</div>
			
			{literal}
				<script type="text/javascript">
					systemMsg('systemmsg');
				 </script>
			{/literal}
		
			<h1>{#administration#}<span>/ {#useradministration#}</span></h1>
		
			<div class="headline">
				<a href="javascript:void(0);" id="block_files_toggle" class="win_block" onclick="toggleBlock('block_files');"></a>
				
				<div class="wintools">
					{if $userpermissions.admin.add}
					<a class="add" href="javascript:blindtoggle('form_member');" id="addmember" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_member','butn_link_active','butn_link');toggleClass('sm_member','smooth','nosmooth');">
						<span>
							{#adduser#}
						</span>
					</a>
					{/if}
				</div>
				
				<h2>
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/userlist.png" alt="" />
					{#useradministration#}
				</h2>
			</div>
			
			<div id="block_files" class="blockwrapper">
			
				{*Add User*}
				{if $userpermissions.admin.add}
				<div id="form_member" class="addmenue" style="display:none;">
					{include file="adduserform.tpl"}
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
					
					<div id="adminUsers" class="content_in_wrapper">
						<div class="content_in_wrapper_in">
							<div class="inwrapper">
								  {literal}
								<ul>
									<li>
											<div v-for="user in items" class="itemwrapper" id="iw_{{*user.ID}}">
												
												<table  cellpadding="0" cellspacing="0" border="0">
												
													<tr>
														<td class="leftmen" valign="top">
															<div class="inmenue">
																<a v-if="*user.avatar != ''" class="more" href="javascript:fadeToggle('info_{{*user.ID}}');"></a>
															</div>
														</td>
														<td class="thumb">
															<a href="manageuser.php?action=profile&amp;id={{*user.ID}}" title="{{*user.name}}">

																<img v-if="user.gender == 'f'" src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/user-icon-female.png" alt="" />
																<img v-else src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/user-icon-male.png" alt="" />
															</a>
														</td>
														<td class="rightmen" valign="top">
															<div class="inmenue">
																<a class="edit" href="admin.php?action=editform&amp;id={{*user.ID}}" title="{#edit#}"></a>
																<a class="del" href="javascript:confirmit('{#confirmdel#}','admin.php?action=deleteuserform&amp;id={{*user.ID}}');" title="{#delete#}"></a>
															</div>
														</td>
													</tr>
													<tr>
														<td colspan="3">
															<span class="name">
																<a href="manageuser.php?action=profile&amp;id={{*user.ID}}" title="{{*user.name}}">
																	{{*user.name}}
																</a>
															</span>
														</td>
													</tr>
													
												</table>
	
												<template v-if="{{user.avatar != ''}}">
                                                    <div class="moreinfo-wrapper">
                                                        <div class="moreinfo" id="info_{{*user.ID}}" style="display:none">
                                                            <img src="thumb.php?pic=files/{$cl_config}/avatar/{{*user.avatar}}&amp;width=82" alt="" onclick="fadeToggle('info_{{*user.ID}');" />
                                                            <span class="name">
                                                                <a href="manageuser.php?action=profile&amp;id={{*user.ID}}">
                                                                    {{*user.name}}
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
												</template>
												
											</div> {*itemwrapper End*}
										</li>
									{/literal} {*lop folder End*}
	
								</ul>
								
							</div> {*inwrapper End*}
						</div> {*content_in_wrapper_in End*}
					</div> {*content_in_wrapper End*}
					
					<div class="staterow">
						<div class="staterowin">
							{*place for whatever*}
						</div>
						<div class="staterowin_right">
							<span>
								{$langfile.page|default}
							</span>
						</div>
					</div>
						
				</div> {*nosmooth End*}
					
				<div class="tablemenue">
					<div class="tablemenue-in">
						
						{if $userpermissions.admin.add}
						<a class="butn_link" href="javascript:blindtoggle('form_member');" id="add_butn_member" onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('addmember','add-active','add');toggleClass('sm_member','smooth','nosmooth');">
							{#adduser#}
						</a>
						{/if}
						
					</div>
				</div>
					
			</div> {*block_files End*}
			<div class="content-spacer"></div>
			
			{* Roles *}
			{include file = "rolesadmin.tpl"}
			{* Roles End *}

			<div class="tablemenue">
				<div class="tablemenue-in">
					
					{if $userpermissions.admin.add}
					<a class="butn_link" href="javascript:blindtoggle('form_addmyroles');" id="add_butn_myprojects" onclick="toggleClass('addrolelink','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');">
						{#addrole#}
					</a>
					{/if}
				
				</div>
			</div>
		
		</div> {*block END*}
		
		{literal}
            <script type="text/javascript" src="include/js/accordion.min.js"></script>
            <script type="text/javascript" src="include/js/adminUsersView.min.js"></script>
			<script type="text/javascript">
				var accord_roles = new accordion2('acc_roles');
			</script>
		{/literal}
		
		<div class="content-spacer"></div>

		</div> {*User END*}
	</div> {*content-left-in END*}
</div> {*Content_left end*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}