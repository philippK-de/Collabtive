<div class="block_in_wrapper">

    <form novalidate class="main" method="post" action="manageproject.php?action=assign&amp;id={$project.ID}" {literal} onsubmit="return validateCompleteForm(this);" {/literal} >
        <fieldset>

            <div class="row">
                <label for="addtheuser">{#user#}</label>
                <select name="user" id="addtheuser" required="1" exclude="-1" realname="{#user#}">
                    <option value="-1" selected="selected">{#chooseone#}</option>
                    {section name=usr loop=$users}
                        <option value="{$users[usr].ID}">{$users[usr].name}</option>
                    {/section}
                </select>
            </div>

            <div class="row-butn-bottom">
                <label>&nbsp;</label>
                <button type="submit" onfocus="this.blur();">{#addbutton#}</button>
                <button onclick="blindtoggle('form_member');toggleClass('addmember','add-active','add');toggleClass('add_butn_member','butn_link_active','butn_link');toggleClass('sm_member','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
            </div>

        </fieldset>
    </form>

</div> {*block_in_wrapper end*}