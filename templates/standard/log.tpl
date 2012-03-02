<div class="headline">
	<a href="javascript:void(0);" id="loghead_toggle" class="win_block" onclick = "toggleBlock('loghead');"></a>

	{if $userpermissions.admin.add}
		<div class="wintools">
			<div class="export-main">
				<a class="export"><span>{#export#}</span></a>
				<div class="export-in"  style="width:46px;left: -46px;"> {*at one item*}
					<a class="pdf" href="manageproject.php?action=projectlogpdf&amp;id={$project.ID}"><span>{#pdfexport#}</span></a>
					<a class="excel" href="manageproject.php?action=projectlogxls&amp;id={$project.ID}"><span>{#excelexport#}</span></a>
				</div>
			</div>
		</div>
	{/if}

	<h2>
		<img src="./templates/standard/images/symbols/activity.png" alt="" />{#activity#}
	</h2>
</div>


<div class="block" id = "loghead" style = "{$logstyle}">
	<table class="log" cellpadding="0" cellspacing="0" border="0">

		<thead>
			<tr>
				<th class="a"></th>
				<th class="bc">{#action#}</th>
				<th class="d">{#user#}</th>
				<th class="tools"></th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td colspan="5"></td>
			</tr>
		</tfoot>

		{section name=logitem loop=$log}

			{*Color-Mix*}
			{if $smarty.section.logitem.index % 2 == 0}
			<tbody class="color-a" id="log_{$log[logitem].ID}">
			{else}
			<tbody class="color-b" id="log_{$log[logitem].ID}">
			{/if}
				<tr>
					<td style="padding:0" class="symbols">
						{if $log[logitem].type == "tasklist"}
							<img style="margin:0 0 0 3px;" src="./templates/standard/images/symbols/tasklist.png" alt="" />
						{elseif $log[logitem].type == "user"}
							<img style="margin:0 0 0 3px;" src="./templates/standard/images/symbols/userlist.png" alt="" />
						{elseif $log[logitem].type == "task"}
							<img style="margin:0 0 0 3px;" src="./templates/standard/images/symbols/task.png" alt="" />
						{elseif $log[logitem].type == "projekt"}
							<img style="margin:0 0 0 3px;" src="./templates/standard/images/symbols/projects.png" alt="" />
						{elseif $log[logitem].type == "milestone"}
							<img style="margin:0 0 0 3px;" src="./templates/standard/images/symbols/miles.png" alt="" />
						{elseif $log[logitem].type == "message"}
							<img style="margin:0 0 0 3px;" src="./templates/standard/images/symbols/msgs.png" alt="" />
						{elseif $log[logitem].type == "file"}
							<img style="margin:0 0 0 3px;" src = "./templates/standard/images/symbols/files.png" alt="" />
						{elseif $log[logitem].type == "folder"}
							<img style="margin:0 0 0 3px;" src = "./templates/standard/images/symbols/folder-root.png" alt="" />
						{elseif $log[logitem].type == "track"}
							<img style="margin:0 0 0 3px;" src = "./templates/standard/images/symbols/timetracker.png" alt="" />
						{/if}
					</td>
					<td>
						<div class="toggle-in">
							<strong>{$log[logitem].name|truncate:55:"...":true}</strong><br />
							<span class="info">{#was#}
								{if $log[logitem].action == 1}
									{#added#}
								{elseif $log[logitem].action == 2}
									{#edited#}
								{elseif $log[logitem].action == 3}
									{#deleted#}
								{elseif $log[logitem].action == 4}
									{#opened#}
								{elseif $log[logitem].action == 5}
									{#closed#}
								{elseif $log[logitem].action == 6}
									{#assigned#}
								{elseif $log[logitem].action == 7}
									{#deassigned#}
								{/if}
								{$log[logitem].datum}
							</span>
						</div>
					</td>
					<td>
						<a href = "manageuser.php?action=profile&amp;id={$log[logitem].user}">{$log[logitem].username|truncate:25:"...":true}</a>
					</td>
					<td class="tools"></td>
				</tr>
			</tbody>
		{/section}

		<tbody class="paging">
			<tr>
				<td></td>
				<td colspan="2">
					<div id="paging">
						{paginate_prev} {paginate_middle} {paginate_next}
					</div>
				</td>
				<td class="tools"></td>
			</tr>
		</tbody>
	</table>

	<div class="tablemenue"></div>
</div> {*block end*}

<div class="content-spacer"></div>