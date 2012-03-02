{include file="header.tpl" jsload = "ajax"}

{include file="tabsmenue-project.tpl" userstab = "active"}
<div id="content-left">
<div id="content-left-in">
<div class="user">

<h1>{#deassignuser#}<span>/ {$user.name}</span></h1>



					<div class="block_in_wrapper">
                    <h2>{#deleteform#}<h2>
					<form class="main" method="post" action="manageproject.php?action=deassign&amp;user={$user.ID}&amp;id={$project.ID}&amp;redir={$redir}" enctype="multipart/form-data" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
					<fieldset>

                        <div class="row"><h3>{$project.name}</h3></div>
                            <div class = "row">
                            <label for = "assignto">{#assignto#}:</label>
    				        <select name = "assignto" id = "assignto" >
					        <option value = "0" selected>{#deletetasks#}</option>
                            {section name=member loop=$members}
                            {if $members[member].ID != $user.ID}
                            <option value="{$members[member].ID}"  />{$members[member].name}</option>
                            {/if}
                            {/section}
                            </select>
                            </div>

                        <div class="row-butn-bottom">
                        	<label>&nbsp;</label>
	                        <button type="submit"onfocus="this.blur();">{#send#}</button>
	                       	<button onclick="javascript:history.back();return false;" onfocus="this.blur();">{#cancel#}</button>	                        
                        </div>
                        
					</fieldset>
					</form>


					</div> {*block_in_wrapper end*}






<div class="content-spacer"></div>
</div> {*User END*}
</div> {*content-left-in END*}

</div> {*Content_left end*}



{include file="sidebar-a.tpl"}
{include file="footer.tpl"}