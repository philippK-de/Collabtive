<div class="block_in_wrapper">

    <h2>{#addcustomer#}</h2>

    <form novalidate class="main" method="post" action="admin.php?action=addcust" {literal}onsubmit="return validateCompleteForm(this);" {/literal}>
        <fieldset>
            <div class="row">
                <label for="company">{#company#}:</label>
                <input type="text" class="text" name="company" id="company" required="1" realname="{#company#}" />
            </div>

            <div class="row">
                <label for="contact">{#contactperson#}:</label>
                <input type="text" class="text" name="contact" id="contact" required="1" realname="{#contactperson#}" />
            </div>

            <div class="row">
                <label for="email">{#email#}:</label>
                <input type="text" class="text" name="email" id="email" required="1" realname="{#email#}" />
            </div>

            <div class="row">
                <label for="tel1">{#phone#}:</label>
                <input type="text" class="text" name="tel1" id="tel1" required="1" realname="{#phone#}" />
            </div>

            <div class="row">
                <label for="tel2">{#cellphone#}:</label>
                <input type="text" class="text" name="tel2" id="tel2" realname="{#cellphone#}" />
            </div>

            <div class="row">
                <label for="web">{#url#}:</label>
                <input type="text" class="text" name="web" id="web" realname="{#url#}" />
            </div>

            <div class="clear_both_b"></div>

            <div class="row">
                <label for="address">{#address#}:</label>
                <input type="text" class="text" name="address" id="address" realname="{#address#}" />
            </div>

            <div class="row">
                <label for="zip">{#zip#}:</label>
                <input type="text" class="text" name="zip" id="zip" realname="{#zip#}" />
            </div>

            <div class="row">
                <label for="city">{#city#}:</label>
                <input type="text" class="text" name="city" id="city" realname="{#city#}" />
            </div>

            <div class="row">
                <label for="country">{#country#}:</label>
                <input type="text" class="text" name="country" id="country" realname="{#country#}" />
            </div>

            <div class="row">
                <label for="state">{#state#}:</label>
                <input type="text" class="text" name="state" id="state" realname="{#state#}" />
            </div>

            <div class="clear_both_b"></div>

            <div class="row">
                <label for="desc">{#description#}:</label>
                <div class="editor">
                    <textarea name="desc" id="desc" rows="3" cols="1"></textarea>
                </div>
            </div>

            <input type="hidden" name="assignme" value="1" />

            <div class="clear_both_b"></div>

            <div class="row-butn-bottom">
                <label>&nbsp;</label>
                <button type="submit" onfocus="this.blur();">{#addbutton#}</button>
                <button type="reset" onclick="blindtoggle('form_addcustomer');toggleClass('add_customers','add-active','add');toggleClass('add_butn_customers','butn_link_active','butn_link');toggleClass('sm_customers','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
            </div>


        </fieldset>
    </form>

</div>{*block_in_wrapper end*}
