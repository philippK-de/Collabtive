{include file="header.tpl" jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" filestab="active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="files">

			<div class="breadcrumb">
				<a href="managefile.php?action=showproject&amp;id={$project.ID}" ><img src="./templates/standard/images/symbols/projects.png" alt="" />
					{$projectname|truncate:25:"...":true}
				</a>
			</div>
			
			<h1 class="second">
				<img src="./templates/standard/images/symbols/files.png" alt="" />
				{$file.title|truncate:30:"...":true}
			</h1>
			
			<div class="block_in_wrapper">
				
				<h2>{#editfile#}</h2>
					
				<form novalidate class="main" method="post" action="managefile.php?action=edit&amp;id={$project.ID}&amp;file={$file.ID}" {literal}onsubmit="return validateCompleteForm(this);"{/literal} >
					<fieldset>
						
						<div class="row"><label for="title">{#title#}:</label><input type="text" value="{$file.title}" name="title" id="title" required="1" realname="{#title#}" /></div>
						<div class="row"><label for="desc">{#description#}:</label></div><div class="editor"><textarea name="desc" id="desc" rows="3" cols="1">{$file.desc}</textarea></div>
						<div class="row"><label for="tags">{#tags#}:</label><input type="text" value="{$file.tags}" name="tags" id="tags"  realname="{#tags#}" /></div>
						
						<div class="row">
						<label>&nbsp;</label>
						<div class="butn"><button type="submit">{#send#}</button></div>
						</div>
						
					</fieldset>
				</form>
				
				<div class="clear_both"></div>
				
			</div> {* block_in_wrapper END *}
			<div class="clear_both"></div>

		</div>
	</div>
</div> {* content_left END *}

{include file="footer.tpl"}
