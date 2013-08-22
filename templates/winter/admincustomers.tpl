{include file="header.tpl" jsload = "ajax"  jsload1 = "tinymce"}
{include file="tabsmenue-admin.tpl" customertab = "active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="projects">

			<div class="infowin_left" style = "display:none;" id = "systemmsg">
				{if $mode == "edited"}
					<span class="info_in_yellow"><img src="templates/standard/images/symbols/customers.png" alt=""/>{#customerwasedited#}</span>
				{elseif $mode == "deassigned"}
					<span class="info_in_red"><img src="templates/standard/images/symbols/user-icon-male.png" alt=""/>{#userwasdeassigned#}</span>
				{elseif $mode == "added"}
					<span class="info_in_green"><img src="templates/standard/images/symbols/customers.png" alt=""/>{#customerwasadded#}</span>
				{/if}
			</div>

			{literal}
				<script type = "text/javascript">
					systemMsg('systemmsg');
				</script>
			{/literal}

			<h1>{#administration#}<span>/ {#customeradministration#}</span></h1>

			<div class="headline">
				<a href="javascript:void(0);" id="acc-customers_toggle" class="win_none" onclick = "toggleBlock('acc-customers');"></a>
					{if $userpermissions.customers.add|default}
						<div class="wintools">
							<a class="add" href="javascript:blindtoggle('form_addcustomer');" id="add_customers" onclick="Effect.BlindUp('form_editcustomer');toggleClass(this,'add-active','add');toggleClass('add_butn_customers','butn_link_active','butn_link');toggleClass('sm_customers','smooth','nosmooth');"><span>{#addcustomer#}</span></a>
						</div>
					{/if}

					<h2>
						<img src="./templates/standard/images/symbols/customers.png" alt="" />{#customerlist#}
					</h2>
				</div>

				<div class="block" id="acc-customers"> {*Add Customer*}
					<div id = "form_addcustomer" class="addmenue" style = "display:none;">
						{include file="addcustomer.tpl" customers="1"}
					</div>
					<div id = "form_editcustomer" class="addmenue" style = "display:none;"></div>
					<div class="nosmooth" id="sm_customers">

						<table id="admincustomers" cellpadding="0" cellspacing="0" border="0">
							<thead>
								<tr>
									<th class="a"></th>
									<th class="b">{#customer#}</th>
									<th class="c">{#phone#}</th>
									<th class="d">{#email#}</th>
									<th class="tools"></th>
								</tr>
							</thead>

							<tfoot>
								<tr>
									<td colspan="5"></td>
								</tr>
							</tfoot>

							{section name=cust loop=$allcust}

								{*Color-Mix*}
								{if $smarty.section.cust.index % 2 == 0}
								<tbody class="color-a" id="proj_{$allcust[cust].ID}">
								{else}
								<tbody class="color-b" id="proj_{$allcust[cust].ID}">
								{/if}
									<tr>
										<td>
											&nbsp;
										</td>
										<td>
											<div class="toggle-in">
												<span class="acc-toggle" onclick="javascript:accord_customers.activate($$('#acc-customers .accordion_toggle')[{$smarty.section.cust.index}]);toggleAccordeon('acc-customers',this);"></span>
												<a href="#" onclick="javascript:accord_customers.activate($$('#acc-customers .accordion_toggle')[{$smarty.section.cust.index}]);toggleAccordeon('acc-customers',this);" title="{$allcust[cust].company}">
													{$allcust[cust].company|truncate:30:"...":true}
												</a>
											</div>
										</td>
										<td>{$allcust[cust].phone}</td>
										<td>{$allcust[cust].email}</td>
										<td class="tools">

												{if $userpermissions.customers.edit}
											<a id="edit_butn{$allcust[cust].ID}" class="tool_edit" href="javascript:void(0);" onclick = "change('managecustomer.php?action=editform&amp;id={$allcust[cust].ID}','form_editcustomer');Effect.BlindUp('form_addcustomer');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_editcustomer');" title="{#edit#}"></a>{/if}
											{if $userpermissions.customers.del}
												<a class="tool_del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'proj_{$allcust[cust].ID}\',\'managecustomer.php?action=del&amp;id={$allcust[cust].ID}\')');"  title="{#delete#}"></a>
											{/if}
										</td>
									</tr>

									<tr class="acc">
										<td colspan="5">
											<div class="accordion_toggle"></div>
											<div class="accordion_content">
												<div class="acc-in">
													<table id="admincustomers" cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td>{#contactperson#}:</td>
															<td>{$allcust[cust].contact}</td>
															<td rowspan="3" style="vertical-align:top;">{#address#}:</td>
															<td>{$allcust[cust].address}</td>
														</tr>
														<tr>
															<td>{#cellphone#}:</td>
															<td>{$allcust[cust].mobile}</td>
															<td>{$allcust[cust].zip} {$allcust[cust].city}</td>
														</tr>
														<tr>
															<td>{#url#}:</td>
															<td><a href="{$allcust[cust].url}" target="_blank">{$allcust[cust].url}</a></td>
															<td>{$allcust[cust].country}</td>
														</tr>
													</table>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							{/section}
						</table> {*Customers End*}
					</div> {*smooth end*}

					<div class="tablemenue">
						<div class="tablemenue-in">
							{if $userpermissions.customers.add|default}
								<a class="butn_link" href="javascript:blindtoggle('form_addcustomer');" id="add_butn_customers" onclick="Effect.BlindUp('form_editcustomer');toggleClass('add_customers','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_customers','smooth','nosmooth');">{#addcustomer#}</a>
							{/if}
						</div>
					</div>
				</div> {*block END*} {*Donecustomers End*}

			{literal}
				<script type = "text/javascript">
					var accord_customers = new accordion('acc-customers');
				</script>
			{/literal}

			<div class="content-spacer"></div>

		</div> {*Customers END*}
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}
