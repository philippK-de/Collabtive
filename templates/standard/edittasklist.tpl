{if $showhtml != "no"}

{include file="header.tpl" jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" taskstab="active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="tasks">
			
			<div class="breadcrumb">
				<a href="manageproject.php?action=showproject&amp;id={$project.ID}" title="{$projectname}">
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />
					{$projectname|truncate:25:"...":true}
				</a>
				<a href="managetask.php?action=showproject&amp;id={$project.ID}">
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt="" />
					{#tasklists#}
				</a>
				<a href="managetasklist.php?action=showtasklist&amp;id={$project.ID}&amp;tlid={$tasklist.ID}">
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt="" />
					{$tasklist.name|truncate:55:"...":true}
				</a>
			</div>
			
			<h1 class="second">
				<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png" alt="" />
				{$tasklist.name|truncate:30:"...":true}
			</h1>
			
{/if}
			
			<div class="block_in_wrapper">
				
				<h2>{#edittasklist#}</h2>
				
				<form novalidate class="main" method="post" action="managetasklist.php?action=edit&amp;id={$project.ID}&amp;tlid={$tasklist.ID}" {literal} onsubmit="return validateCompleteForm(this);" {/literal} >
					<fieldset>
						
						<div class="row">
							<label for="name">{#name#}:</label>
							<input type="text" value="{$tasklist.name}" name="name" id="name" required="1" realname="{#name#}" />
						</div>
						
						<div class="row">
							<label for="desc">{#description#}:</label>
							<div class="editor">
								<textarea name="desc" id="desc" rows="3" cols="1">{$tasklist.desc}</textarea>
							</div>
						</div>
						
						<div class="row">
							<label for="milestone">{#milestone#}:</label>
							<select name="milestone" id="milestone">
								<option value="0">{#chooseone#}</option>
								{section name=stone loop=$milestones}
									<option value="{$milestones[stone].ID}" {if $tasklist.milestone == $milestones[stone].ID} selected="selected" {/if} >
										{$milestones[stone].name}
									</option>
								{/section}
							</select>
						</div>
						
						<div class="row-butn-bottom">
							<label>&nbsp;</label>
							<button type="submit" onfocus="this.blur();">{#send#}</button>
							<button onclick="blindtoggle('form_edit');toggleClass('edit_butn','edit-active','edit');toggleClass('sm_tasklist','smooth','nosmooth');return false;" onfocus="this.blur();" {if $showhtml != "no"} style="display:none;"{/if}>{#cancel#}</button>
						</div>
						
					</fieldset>
				</form>
				
			</div> {* block_in_wrapper END *}
			
{if $showhtml != "no"}
			
			<div class="content-spacer"></div>
			
		</div> {* tasks END *}
	</div> {* content-left-in END *}
</div> {* content-left END *}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}

{/if}