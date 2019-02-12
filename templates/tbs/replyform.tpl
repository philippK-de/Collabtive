{if $showhtml != "no"}
{include file="header.tpl" jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" msgstab="active"}
<div class="row-fluid">
	<div id="content-left" class="span9">
		<div class="msgs">
			<ul class="breadcrumb">
				<li><a href="manageproject.php?action=showproject&amp;id={$project.ID}" title="{$project.name}">{$project.name|truncate:25:"...":true}</a></li>
				<li><a href="managetask.php?action=showproject&amp;id={$project.ID}">{#messages#}</a></li>
				<li><a href="managetasklist.php?action=showtasklist&amp;id={$project.ID}&amp;tlid={$tasklist.ID}">{$message.name|truncate:50:"...":true}</a></li>
			</ul>
			{*<h1 class="second"><img src="./templates/standard/images/symbols/msgs.png" alt="" />{$message.name}</h1>*}
{/if}
			<div class="block_in_wrapper">
				<h2>{#answer#}</h2>
				
					<form class="main form-horizontal" method="post"  enctype="multipart/form-data" action="managemessage.php?action=reply&amp;id={$project.ID}" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
						<fieldset>
							<legend>{#answer#}</legend>
								<div class="control-group">
									<label class="control-label" for="title">{#title#}:</label>
									<div class="controls">
										<input type="text" name="title" id="title" required="1" realname="{#title#}" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="text">{#text#}:</label>
									<div class="editor controls">
										<textarea name="text" id="text"  realname="{#text#}" rows="3" cols="1"></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">{#files#}:</label>
									<div class="controls">
										<button class="inner btn" onclick="blindtoggle('files-add');toggleClass(this,'inner-active btn disabled','inner btn');return false;" onfocus="this.blur()">{#addbutton#}</button>
										<button class="inner btn" onclick="blindtoggle('files-attach');toggleClass(this,'inner-active btn disabled','inner btn');return false;" onfocus="this.blur()">{#attachbutton#}</button>
									</div>
								</div>
								{*Attach*}
								<div id="files-attach" class="blinded" style="display:none;clear:both;">
								<div class="control-group">
									<label class="control-label" for="thefiles">{#attachfile#}:</label>
									<div class="controls">
									<select name="thefiles" id="thefiles">
										<option value="0">{#chooseone#}</option>
										{section name='file' loop=$files}
										<option value="{$files[file].ID}">{$files[file].name}</option>
										{/section}
										{section name='file' loop=$myprojects[project].files}
										<option value ="{$myprojects[project].files[file].ID}">{$myprojects[project].files[file].name}</option>
										{/section}
									</select>
									</div>
								</div>
								</div>					
								{*Add*}
								<div id="files-add" class="blinded" style = "display:none;">
									<div class="control-group">
										<label class="control-label" for="numfiles{$myprojects[project].ID}">{#count#}:</label>
										<div class="controls">
										<select name="numfiles" id="numfiles{$myprojects[project].ID}" onchange="make_inputs(this.value);">
											<option value="1" selected="selected">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>
										</div>
									</div>
									<div id="inputs">
										<div class="control-group">
											<label class="control-label" for="title">{#title#}:</label>
											<div class="controls">
												<input type="text" name="userfile1-title" id="title" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="file">{#file#}:</label>
											<div class="fileinput controls">
											<input type="file" class="file" name="userfile1" id="file"  realname="{#file#}" size="19" onchange="file_{$myprojects[project].ID}.value = this.value;" />
											<table class="faux" cellpadding="0" cellspacing="0" border="0" style="padding:0;margin:0;border:none;">
												<tr>
													<td><input type="text" class="text-file" name="file-{$myprojects[project].ID}" id="file_{$myprojects[project].ID}"></td>
													<td class="choose"><button class="inner" onclick="return false;">{#chooseone#}</button></td>
												</tr>
											</table>				
										</div>
									</div>
									<input type="hidden" name="desc" id="desc" value="" />			
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="tags">{#tags#}:</label>
									<div class="controls">
										<input type="text" name="tags" id="tags" realname="{#tags#}" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">{#notify#}:</label>
									<div class="controls">
									<select name="sendto[]" multiple style="height:100px;">
										<option value="" disabled style="color:black;font-weight:bold;">{#general#}</option>
										<option value="all" selected>{#all#}</option>
										<option value="none" >{#none#}</option>
										<option value="" disabled style="color:black;font-weight:bold;">{#members#}</option>
										{section name=member loop=$members}
										<option value="{$members[member].ID}" >{$members[member].name}</option>
										{/section}
									</select>
									</div>
								</div>
								<input type="hidden" name="desc" id="desc" value="" />
								<input type="hidden" value="{$message.ID}" name="mid" />
								
								<button type="submit" class="btn btn-primary" onfocus="this.blur()">{#send#}</button>
								{if $showhtml == "no"}
									{if $reply != "a"}
									<button class="btn btn-danger" onclick="blindtoggle('form_reply_b');toggleClass('add_replies','add-active','add');toggleClass('add_butn_replies','butn_link_active','butn_link');toggleClass('sm_replies','smooth','nosmooth');return false;" onfocus="this.blur()">{#cancel#}</button>
									{/if}
								{/if}
								{if $reply == "a"}
									<button class="btn btn-danger" onclick="blindtoggle('form_reply_a');toggleClass('add_reply_a','reply-active','reply');toggleClass('sm_replies_a','smooth','nosmooth');return false;" onfocus="this.blur()">{#cancel#}</button>
								{/if}
					</fieldset>
				</form>
			</div> {*block_in_wrapper end*}
{if $showhtml != "no"}
		</div> {*Msgs END*}
	</div>
	{include file="sidebar-a.tpl"}
</div>
{include file="footer.tpl"}
{/if}