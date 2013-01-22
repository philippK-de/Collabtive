{if $showhtml != "no"}
{include file="header.tpl" jsload = "ajax" jsload1 = "tinymce" }

{include file="tabsmenue-project.tpl" taskstab = "active"}
<div id="content-left">
<div id="content-left-in">
<div class="tasks">

<div class="breadcrumb">
<a href="manageproject.php?action=showproject&amp;id={$project.ID}" title="{$projectname}"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$projectname|truncate:25:"...":true}</a>
<a href="managetask.php?action=showproject&amp;id={$project.ID}"><img src="./templates/standard/images/symbols/tasklist.png" alt="" />{#tasklists#}</a>
<a href="managetasklist.php?action=showtasklist&id={$project.ID}&tlid={$task.liste}" title="{#tasklist#} / {$task.list}"><img src="./templates/standard/images/symbols/tasklist.png" alt="" />{$task.list|truncate:25:"...":true}</a>
<a href="managetask.php?action=showtask&amp;tid={$task.ID}&amp;id={$project.ID}" title="{#task#} / {$task.title}"><img src="./templates/standard/images/symbols/tasklist.png" alt="" />{$task.title|truncate:50:"...":true}</a>
<span>&nbsp;/...</span>
</div>

<h1 class="second"><img src="./templates/standard/images/symbols/task.png" alt="" />{$task.title|truncate:30:"...":true}</h1>


{/if}
				<div class="block_in_wrapper">


				<h2>{#edittask#}</h2>


					<form novalidate class="main" method="post" action="managetask.php?action=edit&amp;tid={$task.ID}&amp;id={$pid}" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
					<fieldset>

					<div class="row"><label for="title">{#title#}:</label><input type="text" class="text" value="{$task.title}" name="title" id="title" realname="{#title#}" required="1" /></div>
					<div class="row"><label for="text">{#text#}:</label><div class="editor"><textarea name="text" id="text"   rows="3" cols="0" >{$task.text}</textarea></div></div>
					<div class="row"><label for="end">{#end#}:</label><input type="text" class="text" value="{$task.endstring}" name="end"  id="end"  /></div>

					<div class="datepick">
						<div id = "datepicker_task" class="picker" style = "display:none;"></div>
					</div>

					<script type="text/javascript">
					  	theCal{$lists[list].ID} = new calendar({$theM},{$theY});
						theCal{$lists[list].ID}.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
						theCal{$lists[list].ID}.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
						theCal{$lists[list].ID}.relateTo = "end";
						theCal{$lists[list].ID}.dateFormat = "{$settings.dateformat}";
						theCal{$lists[list].ID}.getDatepicker("datepicker_task");
					</script>

					<div class="row"><label for="tasklist">{#tasklist#}:</label>
						<select name="tasklist" class="select" id="tasklist" required="1" realname="{#tasklist#}">
						{section name=tasklist loop=$tasklists}
						<option value="{$tasklists[tasklist].ID}" {if $task.listid == $tasklists[tasklist].ID}selected = "selected"{/if}>{$tasklists[tasklist].name}</option>
						{/section}</select>
					</div>

                                        <div class="row">
                                                <label for="assigned" >{#assignto#}:</label>
                                                <select name = "assigned[]" multiple="multiple" style = "height:80px;" id="assigned" required = "1" exclude = "-1" realname = "{#assignto#}">
                                                        <option value="-1">{#chooseone#}</option>
                                                        {section name=member loop=$members}
                                                                <option value="{$members[member].ID}" {if in_array($members[member].ID, $task.users)}selected = "selected"{/if}>{$members[member].name}</option>
                                                        {/section}
                                                </select>
                                        </div>

					<div class="row-butn-bottom">
						<label>&nbsp;</label>
						<button type="submit" onfocus="this.blur();">{#send#}</button>
						<button onclick="blindtoggle('form_edit');toggleClass('edit_butn','edit-active','edit');toggleClass('sm_task','smooth','nosmooth');return false;" onfocus="this.blur();" {if $showhtml != "no"} style="display:none;"{/if}>{#cancel#}</button>
					</div>


					</fieldset>
					</form>

			</div> {*block_in_wrapper end*}


{if $showhtml != "no"}
<div class="content-spacer"></div>
</div> {*Tasks END*}
</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}
{/if}