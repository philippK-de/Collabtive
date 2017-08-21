{include file="header.tpl" jsload="ajax"}
{include file="tabsmenue-user.tpl" edittab="active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="user">
			<h1>{#edituser#}<span>/ {$user.name}</span></h1>
			<div class="userwrapper">
				<form novalidate class="main" method="post" action="manageuser.php?action=edit" enctype="multipart/form-data" {literal} onsubmit="return validateCompleteForm(this,'input_error');" {/literal} >
					<fieldset>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="avatarcell" valign="top">
							
									{if $user.avatar != ""}
										<a href="#avatarbig" id="ausloeser">
											<div class="avatar-profile">
												<img src="thumb.php?pic=files/{$cl_config}/avatar/{$user.avatar}&amp;width=122;" alt="" />
											</div>
										</a>
									{else}
										{if $user.gender == "f"}
											<div class="avatar-profile">
												<img src="thumb.php?pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-female.jpg&amp;width=122;" alt="" />
											</div>
										{else}
											<div class="avatar-profile">
												<img src="thumb.php?pic=templates/{$settings.template}/theme/{$settings.theme}/images/no-avatar-male.jpg&amp;width=122;" alt="" />
											</div>
										{/if}
									{/if}
									
									<div id="avatarbig" style="display:none;">
										<a href="javascript:Control.Modal.close();">
											<img src="thumb.php?pic=files/{$cl_config}/avatar/{$user.avatar}&amp;width=480&amp;height=480;" alt="" />
										</a>
									</div>
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
												
												<tbody class="color-a">
													<tr>
														<td><label for="name">{#user#}:</label></td>
														<td class="right"><input type="text" class="text" value="{$user.name}" name="name" id="name" required="1" realname="{#name#}" tabindex="1" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-b">
													<tr>
														<td><label for="avatar">{#avatar#}:</label></td>
														<td class="right">
															<div class="fileinput" >
																<input type="file" class="file" name="userfile" id="avatar" realname="{#file#}" size="19" onchange="file_avatar.value = this.value;" tabindex="2" />
																<table class="faux" cellpadding="0" cellspacing="0" border="0">
																	<tr>
																		<td><input type="text" class="text-file" name="file-avatar" id="file_avatar"></td>
																		<td class="choose"><button class="inner" onclick="return false;">{#chooseone#}</button></td>
																	</tr>
																</table>
															</div>
														</td>
													</tr>
												</tbody>
												
												<tbody class="color-a">
													<tr>
														<td></td>
														<td class="right"></td>
													</tr>
												</tbody>
												
												<tbody class="color-b">
													<tr>
														<td><label for="company">{#company#}:</label></td>
														<td class="right">
															<input type="text" name="company" id="company" value="{$user.company}" />
														</td>
													</tr>
												</tbody>
												
												<tbody class="color-a">
													<tr>
														<td><label for="email">{#email#}:</label></td>
														<td class="right"><input type="text" class="text" value="{$user.email}" name="email" id="email" {literal} regexp="EMAIL" {/literal} required="1" realname="{#email#}" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-b">
													<tr>
														<td><label for="web">{#url#}:</label></td>
														<td class="right"><input type="text" class="text" name="web" id="web" realname="{#url#}" value="{$user.url}" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-a">
													<tr>
														<td><label for="tel1">{#phone#}:</label></td>
														<td class="right"><input type="text" class="text" value="{$user.tel1}" name="tel1" id="tel1" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-b">
													<tr>
														<td><label for="tel2">{#cellphone#}:</label></td>
														<td class="right"><input type="text" class="text" value="{$user.tel2}" name="tel2" id="tel2" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-a">
													<tr>
														<td></td>
														<td class="right"></td>
													</tr>
												</tbody>
												
												<tbody class="color-b">
													<tr>
														<td><label for="address1">{#address#}:</label></td>
														<td class="right"><input type="text" value="{$user.adress}" name="address1" id="address1" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-a">
													<tr>
														<td><label for="zip">{#zip#}:</label></td>
														<td class="right"><input type="text" name="zip" id="zip" realname="{#zip#}" value="{$user.zip}" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-b">
													<tr>
														<td><label for="address2">{#city#}:</label></td>
														<td class="right"><input type="text" class="text" value="{$user.adress2}" name="address2" id="address2" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-a">
													<tr>
														<td><label for="country">{#country#}:</label></td>
														<td class="right"><input type="text" class="text" value="{$user.country}" name="country" id="country" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-b">
													<tr>
														<td><label for="state">{#state#}:</label></td>
														<td class="right"><input type="text" class="text" value="{$user.state}" name="state" id="state" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-a">
													<tr>
														<td></td>
														<td class="right"></td>
													</tr>
												</tbody>
												
												<tbody class="color-b">
													<tr>
														<td><label for="gender">{#gender#}:</label></td>
														<td class="right">
															<select name="gender" id="gender" realname="{#gender#}" />
																{if $user.gender == ""}
																	<option value="" selected>{#chooseone#}</option>
																{/if}
																<option {if $user.gender == "m"} selected="selected" {/if} value="m">{#male#}</option>
																<option {if $user.gender == "f"} selected="selected" {/if} value="f">{#female#}</option>
															</select>
														</td>
													</tr>
												</tbody>
												
												<tbody class="color-a">
													<tr>
														<td><label for="locale">{#locale#}:</label></td>
														<td class="right">
															<select name="locale" id="locale">
																<option value="" {if $user.locale == ""} selected="selected" {/if} >{#systemdefault#}</option>
																{section name=lang loop=$languages_fin}
																	<option value="{$languages_fin[lang].val}" {if $languages_fin[lang].val == $user.locale} selected="selected" {/if} >{$languages_fin[lang].str}</option>
																{/section}
															</select>
														</td>
													</tr>
												</tbody>
												
												<input type="hidden" name="admin" value="{$user.admin|default}" />
												
												<tbody class="color-b">
													<tr>
														<td><label for="oldpass">{#oldpass#}:</label></td>
														<td class="right"><input type="password" class="text" name="oldpass" id="oldpass" autocomplete="off" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-a">
													<tr>
														<td><label for="newpass">{#newpass#}:</label></td>
														<td class="right"><input type="password" name="newpass" id="newpass" autocomplete="off" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-b">
													<tr>
														<td><label for="repeatpass">{#repeatpass#}:</label></td>
														<td class="right"><input type="password" name="repeatpass" id="repeatpass" autocomplete="off" /></td>
													</tr>
												</tbody>
												
												<tbody class="color-a">
													<tr>
														<td></td>
														<td class="right">
															<button type="submit" onfocus="this.blur()">{#send#}</button>
														</td>
													</tr>
												</tbody>
												
											</table>
											
										</div> {* block END *}
									</div> {* message END *}
									
								</td>
							</tr>
							
						</table>
						
					</fieldset>
				</form>
				
				{literal}
					<script type="text/javascript">
						new Control.Modal('ausloeser',{
						opacity: 0.8,
						position: 'absolute',
						width: 480,
						height: 480,
						fade:true,
						containerClassName: 'pics',
						overlayClassName: 'useroverlay'
						});
					</script>
				{/literal}
				
			</div> {*UserWrapper End*}
			
			<div class="content-spacer"></div>
			
		</div> {* user END *}
	</div> {* content-left-in END *}
</div> {* content-left END *}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}