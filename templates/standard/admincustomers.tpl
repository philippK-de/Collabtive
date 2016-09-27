{include file="header.tpl" jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-admin.tpl" customertab="active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="projects" id="adminCustomers">
            <div class="infowin_left display-none"
                 id="customerSystemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/customers.png"
                 data-text-added="{#customerwasadded#}"
                 data-text-edited="{#customerwasedited#}"
                 data-text-deleted="{#customerwasdeleted#}"
                 >
            </div>

			<h1>{#administration#}<span>/ {#customeradministration#}</span></h1>

			<div class="headline">
				<a href="javascript:void(0);" id="acc-customers_toggle" class="win_none" onclick="toggleBlock('acc-customers');"></a>

				{if $userpermissions.admin.add|default}
				<div class="wintools">

                    <loader block="adminCustomers" loader="loader-project3.gif"></loader>

					<a class="add" href="javascript:blindtoggle('form_addcustomer');" id="add_customers" onclick="Effect.BlindUp('form_editcustomer');toggleClass(this,'add-active','add');toggleClass('add_butn_customers','butn_link_active','butn_link');toggleClass('sm_customers','smooth','nosmooth');">
						<span>{#addcustomer#}</span>
					</a>
				</div>
				{/if}

				<h2>
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/customers.png" alt="" />
					{#customerlist#}
                    <pagination view="adminCustomersView" :pages="pages" :current-page="currentPage"></pagination>
				</h2>
			</div>

			<div class="block" id="acc_customers"> {*Add Customer*}
				<div id="form_addcustomer" class="addmenue display-none">
					{include file="forms/addcustomer.tpl" customers="1"}
				</div>

				<div id="form_editcustomer" class="addmenue display-none">
				{include file = "forms/editcustomer.tpl" async="yes"}
				</div>

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

						{literal}
							<tbody v-for="customer in items" class="alternateColors" id="proj_{{*customer.ID}}">
								<tr>
									<td>&nbsp;</td>
									<td>
										<div class="toggle-in">
											<span id="acc_customers_toggle{{$index}}" class="acc-toggle" onclick="javascript:accord_customers.activate(css('#acc_customers_content{{$index}}'))"></span>
											<a href="#" title="{{*customer.company}}">
												{{*customer.company}}
											</a>
										</div>
									</td>
									<td>{{*customer.phone}}</td>
									<td>{{*customer.email}}</td>
									<td class="tools">
                                        {/literal}
										{if $userpermissions.admin.add}
										{literal}
                                        <a id="edit_butn{{*customer.ID}}" class="tool_edit" href="javascript:void(0);" onclick = "change('managecompany.php?action=editform&amp;id={{*customer.ID}}','form_editcustomer');toggleClass(this,'tool_edit_active','tool_edit');blindtoggle('form_editcustomer');" title="{/literal}{#edit#}"></a>
										{/if}

										{if $userpermissions.admin.add}
										{literal}
                                        <a class="tool_del" href="javascript:confirmDelete('{/literal}{#confirmdel#}{literal}','proj_{{*customer.ID}}','managecompany.php?action=del&amp;id={{*customer.ID}}', adminCustomersView);" title="{#delete#}"></a>
										{/literal}
                                        {/if}
									</td>
								</tr>
                                {literal}
								<tr class="acc">
									<td colspan="5">
										<div class="accordion_content" data-slide="{{$index}}" id="acc_customers_content{{$index}}">
											<div class="acc-in">

												<table id="admincustomers" cellpadding="0" cellspacing="0" border="0">
													<tr>
														<td>{/literal}{#contactperson#}{literal}:</td>
														<td>{{*customer.contact}}</td>
														<td rowspan="3" style="vertical-align:top;">{/literal}{#address#}:{literal}</td>
														<td>{{*customer.address}}</td>
													</tr>
													<tr>
														<td>{/literal}{#cellphone#}{literal}:</td>
														<td>{{*customer.mobile}}</td>
														<td>{{*customer.zip}} {{*customer.city}}</td>
													</tr>
													<tr>
														<td>{/literal}{#url#}{literal}:</td>
														<td><a href="{{*customer.url}}" target="_blank">{{*customer.url}}</a></td>
														<td>{{*customer.country}}</td>
													</tr>
												</table>

											</div>
										</div>
									</td>
								</tr>
							</tbody>

						{/literal}

					</table> {*Customers End*}

				</div> {*smooth end*}

				<div class="tablemenue">
					<div class="tablemenue-in">

						{if $userpermissions.admin.add|default}
							<a class="butn_link" href="javascript:blindtoggle('form_addcustomer');" id="add_butn_customers" onclick="Effect.BlindUp('form_editcustomer');toggleClass('add_customers','add-active','add');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_customers','smooth','nosmooth');">{#addcustomer#}</a>
						{/if}
					</div>
				</div>
			</div> {*block END*} {*Donecustomers End*}

			{literal}
                <script type="text/javascript" src="include/js/accordion.min.js"></script>
                <script type="text/javascript" src="include/js/views/adminCustomersView.min.js"></script>
			{/literal}

			<div class="content-spacer"></div>

		</div> {*Customers END*}
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}
