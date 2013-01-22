<div class="headline navbar clearfix">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#" title="{#activity#}">{#activity#}</a>
			{if $userpermissions.admin.add}
			<ul class="nav pull-right">
				<li class="divider-vertical"></li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" title="{#export#}" href="#">
					<i class="icon-cog big"></i>
					<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a class="pdf" href="manageproject.php?action=projectlogpdf&amp;id={$project.ID}"><i class="icon-file"></i> {#pdfexport#}</a>
							
						</li>
						<li>
							<a class="excel" href="manageproject.php?action=projectlogxls&amp;id={$project.ID}"><i class="icon-th"></i> {#excelexport#}</a>
						</li>
					</ul>
				</li>
			</ul>
			{/if}
		</div>
	</div>
</div>
<div class="block" id="loghead" style="{$logstyle}">
	<table class="log table table-bordered table-hover">
		<thead>
			<tr>
				<th class="a"></th>
				<th class="bc">{#action#}</th>
				<th class="d">{#user#}</th>
			</tr>
		</thead>


		{section name='logitem' loop=$log}
		{*Color-Mix*}
		{if $smarty.section.logitem.index % 2 == 0}
		<tbody class="color-a" id="log_{$log[logitem].ID}">
			{else}
		<tbody class="color-b" id="log_{$log[logitem].ID}">
			{/if}
			<tr>
				<td class="symbols">
					{if $log[logitem].type == "tasklist"}
						<i class="icon-tasks"></i>
					{elseif $log[logitem].type == "user"}
						<i class="icon-user"></i>
					{elseif $log[logitem].type == "task"}
						<i class="icon-check"></i>
					{elseif $log[logitem].type == "projekt"}
						<i class="icon-briefcase"></i>
					{elseif $log[logitem].type == "milestone"}
						<i class="icon-flag"></i>
					{elseif $log[logitem].type == "message"}
						<i class="icon-envelope"></i>
					{elseif $log[logitem].type == "file"}
						<i class="icon-folder-close"></i>
					{elseif $log[logitem].type == "folder"}
						<i class="icon-folder-open"></i>
					{elseif $log[logitem].type == "track"}
						<i class="icon-time"></i>
					{/if}
				</td>
				<td>
					<strong>{$log[logitem].name|truncate:55:"...":true}</strong>
					<br />
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
						{$log[logitem].datum} </span>
				</td>
				<td><a href="manageuser.php?action=profile&amp;id={$log[logitem].user}">{$log[logitem].username|truncate:25:"...":true}</a></td>
			</tr>
		</tbody>
		{/section}
	</table>
	<div class="pagination">
		<ul>
			{paginate_prev text='Prev' textonly='1'}
			{paginate_middle link_prefix='<li>' link_suffix='</li>'}
			{paginate_next text='Next' textonly='1'}
		</ul>
	</div>
</div> {*block end*}