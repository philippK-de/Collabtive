{if $showhtml != "no"}
{include file="header.tpl" jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" msgstab = "active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="msgs">
			<div class="breadcrumb">
				<a href="manageproject.php?action=showproject&amp;id={$project.ID}" title="{$project.name}"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$project.name|truncate:25:"...":true}</a>
				<a href="managetask.php?action=showproject&amp;id={$project.ID}"><img src="./templates/standard/images/symbols/msgs.png" alt="" />{#messages#}</a>
				<a href="managetasklist.php?action=showtasklist&amp;id={$project.ID}&amp;tlid={$tasklist.ID}"><img src="./templates/standard/images/symbols/msgs.png" alt="" />{$message.name|truncate:50:"...":true}</a><span>&nbsp;/...</span>
			</div>

			<h1 class="second"><img src="./templates/standard/images/symbols/msgs.png" alt="" />{$message.name}</h1>
{/if}

			<div class="block_in_wrapper">
				<h2>{#answer#}</h2>
				
				<form class="main" method="post"  enctype="multipart/form-data" action="managemessage.php?action=reply&amp;id={$project.ID}" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
					<fieldset>
						
						<div class="row">
							<label for="title">{#title#}:</label>
							<input type="text" name="title" id="title" required="1" realname="{#title#}" />
						</div>
						<div class="row">
							<label for="text">{#text#}:</label>
							<div class="editor"><textarea name="text" id="text"  realname="{#text#}" rows="3" cols="1"></textarea></div>
						</div>
						
						<div class="row">
							<label>{#files#}:</label>
							<button class="inner" onclick="blindtoggle('files-add');toggleClass(this,'inner-active','inner');return false;" onfocus="this.blur()">{#addbutton#}</button>
							<button class="inner" onclick="blindtoggle('files-attach');toggleClass(this,'inner-active','inner');return false;" onfocus="this.blur()">{#attachbutton#}</button>
						</div>

						{*Attach*}
						<div id = "files-attach" class="blinded" style = "display:none;clear:both;">
							<div class="row">
								<label for = "thefiles">{#attachfile#}:</label>
								<select name = "thefiles" id = "thefiles">
									<option value = "0">{#chooseone#}</option>
									{section name = file loop=$files}
									<option value = "{$files[file].ID}">{$files[file].name}</option>
									{/section}
									{section name = file loop=$myprojects[project].files}
									<option value = "{$myprojects[project].files[file].ID}">{$myprojects[project].files[file].name}</option>
									{/section}
								</select>
							</div>
						</div>
						
						
						{*Add*}
						<div id = "files-add" class="blinded" style = "display:none;">
				
							<div class="row">
								<label for = "numfiles{$myprojects[project].ID}">{#count#}:</label>
								<select name = "numfiles" id = "numfiles{$myprojects[project].ID}" onchange = "make_inputs(this.value);">
								<option value = "1" selected="selected">1</option>
								<option value = "2">2</option>
								<option value = "3">3</option>
								<option value = "4">4</option>
								<option value = "5">5</option>
								<option value = "6">6</option>
								<option value = "7">7</option>
								<option value = "8">8</option>
								<option value = "9">9</option>
								<option value = "10">10</option>
								</select>
							</div>
						
							<div id = "inputs">
								<div class="row">
									<label for = "title">{#title#}:</label>
									<input type = "text" name = "userfile1-title" id="title" />
								</div>
								<div class="row">
									<label for="file">{#file#}:</label>
									<div class="fileinput" >
										<input type="file" class="file" name="userfile1" id="file"  realname="{#file#}" size="19" onchange = "file_{$myprojects[project].ID}.value = this.value;" />
										<table class = "faux" cellpadding="0" cellspacing="0" border="0" style="padding:0;margin:0;border:none;">
											<tr>
												<td><input type="text" class="text-file" name = "file-{$myprojects[project].ID}" id="file_{$myprojects[project].ID}"></td>
												<td class="choose"><button class="inner" onclick="return false;">{#chooseone#}</button></td>
											</tr>
										</table>				
									</div>
								</div>
								<input type = "hidden" name="desc" id="desc" value = "" />			
							</div>
						</div>
						
						<div class="row">
							<label for="tags">{#tags#}:</label>
							<input type="text" name="tags" id="tags" realname="{#tags#}" />
						</div>
							
						<div class = "row">
							<label>{#notify#}:</label>
							<select name = "sendto[]" multiple style = "height:100px;">
								<option value = "" disabled style = "color:black;font-weight:bold;">{#general#}</option>
								<option value = "all" selected>{#all#}</option>
								<option value = "none" >{#none#}</option>
								<option value = "" disabled style = "color:black;font-weight:bold;">{#members#}</option>
								{section name=member loop=$members}
									<option value = "{$members[member].ID}" >{$members[member].name}</option>
								{/section}
							</select>
						</div>

						<input type = "hidden" name="desc" id="desc" value = "" />
						<input type="hidden" value="{$message.ID}" name="mid" />

						<div class="row-butn-bottom">
							<label>&nbsp;</label>
							<button type="submit" onfocus="this.blur()">{#send#}</button>
							{if $showhtml == "no"}
								{if $reply != "a"}
									<button onclick="blindtoggle('form_reply_b');toggleClass('add_replies','add-active','add');toggleClass('add_butn_replies','butn_link_active','butn_link');toggleClass('sm_replies','smooth','nosmooth');return false;" onfocus="this.blur()">{#cancel#}</button>
								{/if}
							{/if}
							{if $reply == "a"}
								<button onclick="blindtoggle('form_reply_a');toggleClass('add_reply_a','reply-active','reply');toggleClass('sm_replies_a','smooth','nosmooth');return false;" onfocus="this.blur()">{#cancel#}</button>
							{/if}
						</div>

					</fieldset>
				</form>
			</div> {*block_in_wrapper end*}

{if $showhtml != "no"}
			<div class="content-spacer"></div>
		</div> {*Msgs END*}
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}
{/if}