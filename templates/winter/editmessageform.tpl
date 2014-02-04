{if $showhtml != "no"}
{include file="header.tpl" jsload = "ajax" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" msgstab = "active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="msgs">

			<div class="breadcrumb">
				<a href="managemessage.php?action=showproject&amp;id={$project.ID}" title="{$project.name}"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$message.pname}</a>
				<a href="managemessage.php?action=showmessage&amp;id={$project.ID}&mid={$message.ID}"><img src="./templates/standard/images/symbols/msgs.png" alt="" />{#message#}</a>
			</div>

			<h1 class="second"><img src="./templates/standard/images/symbols/msgs.png" alt="" />{$message.name}</h1>
{/if}
			<div class="block_in_wrapper">
				<h2>{#editmessage#}</h2>

				<form novalidate class="main" method="post" action="managemessage.php?action=edit&amp;id={$project.ID}" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
					<fieldset>
						<div class="row">
							<label for="title">{#title#}:</label><input type="text" value="{$message.title}" name="title" id="title" required="1" realname="{#title#}"/>
						</div>

						<div class = "row">
							<label for="text">{#text#}:</label>
							<div class="editor">
								<textarea name="text" id="msgtext" realname="{#text#}" rows="3" cols="1">{$message.text}</textarea>
							</div>
						</div>

<input type="hidden" value="{$message.tags}" name="tags" id="tags" realname="{#tags#}"/>
						<input type="hidden" name="mid" value="{$message.ID}" />

						<div class="row-butn-bottom">
							<label>&nbsp;</label>
							<button type="submit" onfocus="this.blur();">{#send#}</button>
							{if $showhtml == "no"}
								<button onclick="blindtoggle('form_edit');toggleClass('edit_butn','edit-active','edit');toggleClass('sm_replies_a','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
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