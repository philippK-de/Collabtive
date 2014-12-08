{include file="header.tpl" jsload="ajax" jsload3="lightbox"}
{include file="tabsmenue-desk.tpl"}

<div id="content-left">
	<div id="content-left-in">
		<div class="neutral">

			<h1>{#search#}</h1>

			<div class="headline">
				<a href="javascript:void(0);" id="block_tags_toggle" class="win_block" onclick="toggleBlock('block_tags');"></a>

				<h2>
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/search.png" alt="" />{#results#} ({$num})
				</h2>
			</div>

			<div id="block_tags" class="blockwrapper">

				<div class="contenttitle">
					<div class="contenttitle_menue">
						{* space for tool below title icon *}
					</div>
					
					<div class="contenttitle_in">
						{* space for header info *}
					</div>
				</div>
				
				<div class="content_in_wrapper">
					<div class="content_in_wrapper_in">
						<div class="inwrapper">
						
							<ul>
								{section name=obj loop=$result}
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
														{if $result[obj].type == "file"}
															<a style="top:-33px;" href="managefile.php?action=downloadfile&amp;id={$result[obj].project}&amp;file={$result[obj].ID}" {if $result[obj].imgfile == 1} rel="lytebox[]" {elseif $result[obj].imgfile == 2} rel="lyteframe[text]" rev="width:650px;height:500px;" {/if}>
															<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/{$result[obj].icon}" alt="" />
														{else}
															<a href="{$result[obj].url}" title="{$result[obj].name}">
																<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/{$result[obj].icon}" alt="" />
															</a>
														{/if}
													</td>
													
													<td class="rightmen" valign="top"></td>
												</tr>
												
												<tr>
													<td colspan="3">
														<span class="name">
																{if $result[obj].type == "file"}
																	<a href="managefile.php?action=downloadfile&amp;id={$result[obj].project}&amp;file={$result[obj].ID}" {if $result[obj].imgfile == 1} rel="lytebox[]" {elseif $result[obj].imgfile == 2} rel="lyteframe[text]" rev="width:650px;height:500px;" {/if}>{$result[obj].name|truncate:13:"...":true}
																{elseif $result[obj].name != ""}
																	<a href="{$result[obj].url}" title="{$result[obj].name}">{$result[obj].name|truncate:13:"...":true}</a>
																{else}
																	<a href="{$result[obj].url}" title="{$result[obj].title}">{$result[obj].title|truncate:13:"...":true}</a>
																{/if}
															</a>
														</span>
													</td>
												<tr/>
												
											</table>

											{if $members[member].avatar != ""}
												<div class="moreinfo" id="info_{$members[member].ID}" style="display:none">
													<img src="thumb.php?pic=files/{$cl_config}/avatar/{$members[member].avatar}&amp;width=82" alt="" onclick="fadeToggle('info_{$members[member].ID}');" />
													<span class="name"><a href="manageuser.php?action=profile&amp;id={$members[member].ID}">{$members[member].name|truncate:15:"...":true}</a></span>
												</div>
											{/if}

										</div> {* itemwrapper end *}
									</li>
								{/section} {* loop folder end *}
							</ul>
							
						</div> {* inwrapper end *}
					</div> {* content_in_wrapper_in end *}
				</div> {* content_in_wrapper end *}

				<div class="staterow">
					<div class="staterowin">
						{* space for whatever *}
					</div>
				</div>

				<div class="tablemenue"></div>
			</div> {* block_tags end *}

			<div class="content-spacer"></div>

		</div> {*Neutral END*}
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}