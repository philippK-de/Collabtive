

	{literal}
		<script type="text/javascript">
			//	theme_advanced_statusbar_location : "bottom",
			tinyMCE.init({
				mode : "textareas",
				theme : "advanced",
				language: "{/literal}{$locale}{literal}",
				width: "400px",
				height: "250px",
				plugins : "inlinepopups,style,advimage,advlink,xhtmlxtras,safari,template",
				theme_advanced_buttons1 : "bold,italic,underline,|,fontsizeselect,forecolor,|,bullist,numlist,|,link,unlink,image",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_path : false,
				extended_valid_elements : "a[name|href|target|title],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|name],font[face|size|color|style],span[class|align|style]",
			    theme_advanced_statusbar_location: "bottom",
			    theme_advanced_resizing : true,
				theme_advanced_resizing_use_cookie : false,
				theme_advanced_resizing_min_width : "400px",
				theme_advanced_resizing_max_width : "600px",
				theme_advanced_resize_horizontal : false,
				force_br_newlines : true,
				cleanup: true,
				cleanup_on_startup: true,
				force_p_newlines : false,
				convert_newlines_to_brs : false,
				forced_root_block : false,
				external_image_list_url: 'manageajax.php?action=jsonfiles&id={/literal}{$customer.ID}{literal}'
			});
		</script>
	{/literal}


<div class="block_in_wrapper">

	<h2>{$langfile.editcustomer}</h2>

	<form novalidate class="main" method="post" action="managecompany.php?action=edit&amp;id={$customer.ID}" {literal}onsubmit="return validateCompleteForm(this,'input_error');"{/literal}>
		<fieldset>

			<div class="row"><label for="company">{$langfile.company}:</label><input type="text" class="text" name="company" id="company" required="1" realname="{$langfile.company}" value="{$customer.company}" /></div>
			<div class="row"><label for="contact">{$langfile.contactperson}:</label><input type="text" class="text" name="contact" id="contact" required="1" realname="{$langfile.contactperson}" value="{$customer.contact}" /></div>
			<div class="row"><label for="email">{$langfile.email}:</label><input type="text" class="text" name="email" id="email" required="1" realname="{$langfile.email}" value="{$customer.email}" /></div>
			<div class="row"><label for="tel1">{$langfile.phone}:</label><input type="text" class="text" name="tel1" id="tel1" required="1" realname="{$langfile.phone}" value="{$customer.phone}" /></div>
			<div class="row"><label for="tel2">{$langfile.cellphone}:</label><input type="text" class="text" name="tel2" id="tel2" realname="{$langfile.cellphone}" value="{$customer.mobile}" /></div>
			<div class="row"><label for="web">{$langfile.url}:</label><input type="text" class="text" name="web" id="web" realname="{$langfile.url}" value="{$customer.url}" /></div>

			<div class="clear_both_b"></div>

			<div class="row"><label for="address">{$langfile.address}:</label><input type="text" class="text" name="address" id="address" realname="{$langfile.address}" value="{$customer.address}" /></div>
			<div class="row"><label for="zip">{$langfile.zip}:</label><input type="text" class="text" name="zip" id="zip" realname="{$langfile.zip}" value="{$customer.zip}" /></div>
			<div class="row"><label for="city">{$langfile.city}:</label><input type="text" class="text" name="city" id="city" realname="{$langfile.city}" value="{$customer.city}" /></div>
			<div class="row"><label for="country">{$langfile.country}:</label><input type="text" class="text" name="country" id="country" realname="{$langfile.country}" value="{$customer.country}" /></div>
			<div class="row"><label for="state">{$langfile.state}:</label><input type="text" class="text" name="state" id="state" realname="{$langfile.state}" value="{$customer.state}" /></div>

			<div class="clear_both_b"></div>

			<div class="row"><label for="desc">{$langfile.description}:</label><div class="editor"><textarea name="desc" id="desc" rows="3" cols="1">{$customer.desc}</textarea></div></div>

			<div class="row-butn-bottom">
				<label>&nbsp;</label>
				<button type="submit" onfocus="this.blur();">{$langfile.send}</button>
				<button type="button" onclick="blindtoggle('form_editcustomer');toggleClass('edit_butn{$customer.ID}','tool_edit_active','tool_edit');" onfocus="this.blur();">{$langfile.cancel}</button>
			</div>

		</fieldset>
	</form>

</div> {* block_in_wrapper END *}
