{include file="header.tpl" jsload = "ajax"}

{include file="tabsmenue-project.tpl" milestab = "active"}
<div id="content-left">
<div id="content-left-in">
<div class="miles">


<div class="breadcrumb">
<a href="manageproject.php?action=showproject&amp;id={$project.ID}"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$projectname|truncate:40:"...":true}</a>
<a href="managemilestone.php?action=showproject&amp;id={$project.ID}"><img src="./templates/standard/images/symbols/miles.png" alt="" />{#milestones#}</a>

</div>


<h1 class="second"><img src="./templates/standard/images/symbols/miles.png" alt="" />{$milestone.name}</h1>


	<div class="statuswrapper">
			<ul>
				{if $userpermissions.milestones.close}
					{if $milestone.status == 1}
						<li class="link"><a class="close" href="managemilestone.php?action=close&amp;mid={$milestone.ID}&amp;id={$project.ID}" title="{#close#}"></a></li>
					{/if}
				{/if}
					{if $userpermissions.milestones.edit}
				<li class="link"><a class="edit" href="javascript:void(0);"  id="edit_butn" onclick="blindtoggle('form_edit');toggleClass(this,'edit-active','edit');toggleClass('sm_mile','smooth','nosmooth');" title="{#edit#}"></a></li>
				{/if}
				{if $userpermissions.milestones.del}
				<li class="link"><a class="del" href="javascript:void(0);" onclick = "confirmit('{#confirmdel#}','managemilestone.php?action=del&amp;mid={$milestone.ID}&amp;id={$project.ID}');" title="{#delete#}"></a></li>
				{/if}

				<!--
				<li class="link" onclick="blindtoggle('descript');toggleClass('desctoggle','desc_active','desc');"><a class="desc_active" id="desctoggle" href="javascript:void(0);" title="{#open#}">{#description#}</a></li>
				-->

				<li><a>{#start#}: {$milestone.startstring}</a></li>

				<li><a>{#end#}: {$milestone.endstring}</a></li>
			</ul>
	</div>

			{*Edit Milestone*}
			{if $userpermissions.milestones.edit}
				<div id = "form_edit" class="addmenue" style = "display:none;clear:both;">
					<div class="content-spacer"></div>
					{include file="editmilestone.tpl" showhtml="no"}
				</div>
			{/if}


<div class="content-spacer"></div>

	<div class="nosmooth" id="sm_mile">
		{*Description*}
		<div id="descript" class="descript">
			<h2>{#description#}</h2>
			{$milestone.desc}
		</div>


		{*Tasklists*}
	{if $milestone.tasks[0][0]}
		<div class="content-spacer-b"></div>
				<h2>{#tasklists#}</h2>

						<div class="inwrapper">
							<ul>
							{section name=task loop=$milestone.tasks}
								<li>
									<div class="itemwrapper">

											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td class="leftmen" valign="top">
														<div class="inmenue">
																<!-- <a class="more" href="javascript:fadeToggle('info_{$members[member].ID}');"></a>	-->
														</div>
													</td>
													<td class="thumb">
														<a href="managetasklist.php?action=showtasklist&amp;tlid={$milestone.tasks[task].ID}&amp;id={$project.ID}" title="{$milestone.tasks[task].name}">
																<img src = "./templates/standard/images/symbols/tasklist.png" style="width: 32px; height: auto;" alt="" />
														</a>
													</td>
													<td class="rightmen" valign="top">
														<div class="inmenue">
														<!--
															<a class="del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'files_focus{$ordner[file].ID}\',\'managefile.php?action=delete&amp;id={$project.ID}&amp;file={$folders[fold].ID}\')');" title="{#delete#}" onclick="fadeToggle('iw_{$folders[fold].ID}');"></a>
															<a class="edit" href="#" title="{#editfile#}"></a>
														-->
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="3">
														<span class="name">
															<a href = "managetasklist.php?action=showtasklist&amp;tlid={$milestone.tasks[task].ID}&amp;id={$project.ID}" title="{$milestone.tasks[task].name}">
																{if $milestone.tasks[task].name != ""}
																	{$milestone.tasks[task].name|truncate:13:"...":true}
																{else}
																	{#tasklist#}
																{/if}
															</a>
														</span>
													</td>
												<tr/>
											</table>

									</div> {*itemwrapper End*}
								</li>
							{/section} {*loop Tasklists End*}

							</ul>
						</div> {*inwrapper End*}

				{/if}


		{*Messages*}
		{if $milestone.messages[0][0]}
		<div class="content-spacer-b"></div>
				<h2>{#messages#}</h2>

						<div class="inwrapper">
							<ul>
							{section name=msg loop=$milestone.messages}
								<li>
									<div class="itemwrapper">

											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td class="leftmen" valign="top">
														<div class="inmenue">
																<!-- <a class="more" href="javascript:fadeToggle('info_{$members[member].ID}');"></a>	-->
														</div>
													</td>
													<td class="thumb">
														<a href="managemessage.php?action=showmessage&amp;mid={$milestone.messages[msg].ID}&amp;id={$project.ID}" title="{$milestone.messages[msg].title}">
																<img src = "./templates/standard/images/symbols/msgs.png" style="width: 32px; height: auto;" alt="" />
														</a>
													</td>
													<td class="rightmen" valign="top">
														<div class="inmenue">
														<!--
															<a class="del" href="javascript:confirmfunction('{#confirmdel#}','deleteElement(\'files_focus{$ordner[file].ID}\',\'managefile.php?action=delete&amp;id={$project.ID}&amp;file={$folders[fold].ID}\')');" title="{#delete#}" onclick="fadeToggle('iw_{$folders[fold].ID}');"></a>
															<a class="edit" href="#" title="{#editfile#}"></a>
														-->
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="3">
														<span class="name">
															<a href = "managemessage.php?action=showmessage&amp;mid={$milestone.messages[msg].ID}&amp;id={$project.ID}" title="{$milestone.messages[msg].title}">
																{if $milestone.messages[msg].title != ""}
																	{$milestone.messages[msg].title|truncate:13:"...":true}
																{else}
																	{#message#}
																{/if}
															</a>
														</span>
													</td>
												<tr/>
											</table>

									</div> {*itemwrapper End*}
								</li>
							{/section} {*loop Messages End*}

							</ul>
						</div> {*inwrapper End*}
		{/if}


	</div> {*nosmooth End*}


<div class="content-spacer"></div>


</div> {*Miles END*}
</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}