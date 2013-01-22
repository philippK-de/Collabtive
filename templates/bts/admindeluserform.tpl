{include file="header.tpl" jsload = "ajax"}

{include file="tabsmenue-admin.tpl" usertab = "active"}
<div id="content-left">
<div id="content-left-in">
<div class="user">

<h1>{#deleteuser#}<span>/ {$user.name}</span></h1>





					<div class="block_in_wrapper">
					<h2>{#deleteform#}</h2>
					<form class="main" method="post" action="admin.php?action=deleteuser&amp;id={$user.ID}" enctype="multipart/form-data" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
					<fieldset>
<input type = "hidden" name = "id" value = "{$user.ID}" />


                        {section name=proj loop=$projects}
                        <div class="row"><h3>{$projects[proj].name}</h3></div>
                        <div class = "row">
                            <label for = "{$projects[proj].ID}membs">{#assignto#}:</label>
							<select name = "uprojects[]" id = "{$projects[proj].ID}membs" >
							<option value = "{$projects[proj].ID}#0" selected>{#deletetasks#}</option>
                            {section name=member loop=$projects[proj].members}
                            {if $projects[proj].members[member].ID != $user.ID}
							<option value="{$projects[proj].ID}#{$projects[proj].members[member].ID}"  />{$projects[proj].members[member].name}</option>
                            {/if}
                            {/section}
                             </select>
							</div>
						<div class="content-spacer"></div>
                        {/section}

                        
                        <div class="row-butn-bottom">
                        	<label>&nbsp;</label>
	                        <button type="submit"onfocus="this.blur();">{#send#}</button>
	                        <button onclick="javascript:history.back();return false;" onfocus="this.blur();">{#cancel#}</button>
                        </div>
                        
					</fieldset>
					</form>

					<div class="clear_both"></div> {*required ... do not delete this row*}
					</div> {*block_in_wrapper end*}






<div class="content-spacer"></div>
</div> {*User END*}
</div> {*content-left-in END*}

</div> {*Content_left end*}



{include file="sidebar-a.tpl"}
{include file="footer.tpl"}