{include file="header.tpl" jsload="ajax" stage="project" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" projecttab="active"}

<div id="content-left">
<div id="content-left-in">
<div class="projects">


	<div class="infowin_left" style = "display:none;" id = "systemmsg">
		{if $mode == "edited"}
		<span class="info_in_yellow"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt=""/>{#projectwasedited#}</span>
		{elseif $mode == "timeadded"}
		<span class="info_in_green"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/timetracker.png" alt=""/>{#timetrackeradded#}</span>
		{/if}
	</div>
	{literal}
	<script type = "text/javascript">
		systemMsg('systemmsg');
	 </script>
	{/literal}

<h1>{$project.name|truncate:45:"...":true}<span>/ {#overview#}</span></h1>

	<div class="statuswrapper">
			<ul>
				{if $userpermissions.projects.close}
		        {if $project.status == 1}
				    <li class="link" id = "closetoggle"><a class="close" href="javascript:closeElement('closetoggle','manageproject.php?action=close&amp;id={$project.ID}');" title="{#close#}"></a></li>
				{else}
					<li class="link" id = "closetoggle"><a class="closed" href="manageproject.php?action=open&amp;id={$project.ID}" title="{#open#}"></a></li>
			    {/if}
				{/if}
				{if $userpermissions.projects.edit}
					<li class="link"><a class="edit" href="javascript:void(0);"  id="edit_butn" onclick="blindtoggle('form_edit');toggleClass(this,'edit-active','edit');toggleClass('sm_project','smooth','nosmooth');toggleClass('sm_project_desc','smooth','nosmooth');" title="{#edit#}"></a></li>
				{/if}
				{if $project.desc}
					<li class="link" onclick="blindtoggle('descript');toggleClass('desctoggle','desc_active','desc');"><a class="desc" id="desctoggle" href="#" title="{#open#}">{#description#}</a></li>
				{/if}
				{if $userpermissions.projects.del}{if $project.budget}
					<li><a>{#budget#}: {$project.budget}</a></li>
				{/if}{/if}
				{if $project.customer.company != ""}
					<li class="link" onclick="blindtoggle('customer');toggleClass('custtogle','desc_active','desc');"><a class="desc" id="custtogle">{#customer#}: {$project.customer.company}</a></li>
				{/if}
				{if $project.daysleft != "" || $project.daysleft == "0"}
					<li {if $project.daysleft < 0}class="red"{elseif $project.daysleft == "0"}class="green"{/if}><a>{$project.daysleft} {#daysleft#}</a></li>
				{/if}
			</ul>

			<div class="status">
				{$done}%
				<div class="statusbar"><div class="complete" id="completed" style="width:0%;"></div></div>
			</div>
	</div>

		{*Edit Task*}
		{if $userpermissions.projects.edit}
			<div id = "form_edit" class="addmenue" style = "display:none;clear:both;">
				<div class="content-spacer"></div>
				{include file="editform.tpl" showhtml="no" }
			</div>
		{/if}

		<div class="nosmooth" id="sm_project_customer">
			<div id="customer" class="descript" style="display:none;">
				<div class="content-spacer"></div>
				
				<h2>{$project.customer.company}</h2>
				
				<b>{#contactperson#}:</b> {$project.customer.contact}
				<br />
				<b>{#email#}:</b> <a href = "mailto:{$project.customer.email}">{$project.customer.email}</a>
				<br />
				<b>{#phone#} / {#cellphone#}:</b> {$project.customer.phone} / {$project.customer.mobile}
				<br />
				<b>{#url#}:</b> <a href = "{$project.customer.url}" target="_blank">{$project.customer.url}</a>
				<br /><br />
				<b>{#address#}:</b><br />
				{$project.customer.address}
				<br />{$project.customer.zip} {$project.customer.city}
				<br />{$project.customer.country}<br />
			</div>
		</div>

		<div class="nosmooth" id="sm_project_desc">
			<div id="descript" class="descript" style="display:none;">
				<div class="content-spacer"></div>
				{$project.desc}
			</div>
		</div>
	</div> {*Projects END*}

	<div class="content-spacer"></div>
	<div class="nosmooth" id="sm_project">

<div id="block_dashboard" class="block">

{*Miles tree*}
<div class="projects dtree" style="padding-bottom:2px;" >
	<div class="headline accordion_toggle">
		<a href="javascript:void(0);" id="treehead_toggle" class="win_block" onclick="changeElements('a.win_block','win_none');toggleBlock('treehead');"></a>
		<h2>
			<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />{#projecttree#}
		</h2>
	</div>

	<div class="block accordion_content" id="treehead" style="overflow:hidden;">
		<div class="block_in_wrapper" style="padding-top:0px;">
	
			<script type="text/javascript">
		
				d{$project.ID} = new dTree('d{$project.ID}');
				d{$project.ID}.config.useCookies = true;
				d{$project.ID}.config.useSelection = false;
				d{$project.ID}.add(0,-1,'');
		
				// Milestones
				{section name=titem loop=$tree}
					d{$project.ID}.add("m"+{$tree[titem].ID}, 0, "{$tree[titem].name}", "managemilestone.php?action=showmilestone&msid={$tree[titem].ID}&id={$project.ID}", "", "", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png", "", {$tree[titem].daysleft});
		
					// Task lists
					{section name=tlist loop=$tree[titem].tasklists}
						d{$project.ID}.add("tl"+{$tree[titem].tasklists[tlist].ID}, "m"+{$tree[titem].tasklists[tlist].milestone}, "{$tree[titem].tasklists[tlist].name}", "managetasklist.php?action=showtasklist&id={$project.ID}&tlid={$tree[titem].tasklists[tlist].ID}", "", "", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/tasklist.png");
		
						// Tasks from lists
						{section name=ttask loop=$tree[titem].tasklists[tlist].tasks}
							d{$project.ID}.add("ta"+{$tree[titem].tasklists[tlist].tasks[ttask].ID}, "tl"+{$tree[titem].tasklists[tlist].tasks[ttask].liste}, "{$tree[titem].tasklists[tlist].tasks[ttask].title}", "managetask.php?action=showtask&tid={$tree[titem].tasklists[tlist].tasks[ttask].ID}&id={$project.ID}", "", "", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/task.png", "",{$tree[titem].tasklists[tlist].tasks[ttask].daysleft});
						{/section}
		
					// End task lists
					{/section}
		
					// Messages
					{section name=tmsg loop=$tree[titem].messages}
						{if $tree[titem].messages[tmsg].milestone > 0}
							d{$project.ID}.add("msg"+{$tree[titem].messages[tmsg].ID}, "m"+{$tree[titem].messages[tmsg].milestone}, "{$tree[titem].messages[tmsg].title}", "managemessage.php?action=showmessage&id={$project.ID}&mid={$tree[titem].messages[tmsg].ID}", "", "", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png", "templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png");
						{/if}
		
					{/section}
					// End Messages
				{/section}
				// End milestones
		
				document.write(d{$project.ID});
		
			</script>
		
			<br />
			<form id="treecontrol" action="#">
				<fieldset>
					<div class="row-butn-bottom">
						<button type="reset" id="openall" onclick="d{$project.ID}.openAll();">{#openall#}</button>
						<button type="reset" id="closeall" onclick="d{$project.ID}.closeAll();">{#closeall#}</button>
					</div>
				</fieldset>
			</form>
			
		</div> {*block end*}
	</div> {*block in wrapper end*}
</div>
{*Tree end*}

{*Milestones*}
<div class="miles" style="padding-bottom:2px;">
			<div class="headline accordion_toggle" >
				<a href="javascript:void(0);" id="milehead_toggle" class="win_none" onclick="changeElements('a.win_block','win_none');toggleBlock('milehead');"></a>

				<div class="wintools">
					<!-- <div class="export-main">
						<a class="export"><span>{#export#}</span></a>
						<div class="export-in"  style="width:23px;left: -23px;"> {*at one item*}
							<a class="ical" href="managetask.php?action=ical"><span>{#icalexport#}</span></a>
						</div>
					</div>-->
					<div class="progress" id="progress" style="display:none;">
						<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/loader-cal.gif" />
					</div>
				</div>


				<h2>
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png" alt="" />{#calendar#}
				</h2>

			</div>


			<div class="block accordion_content" id="milehead" style = "overflow:hidden;">
				<div id = "thecal" class="bigcal" style = "min-height:270px;"><p style="position:relative;left:50%;">Loading ...</div>
			</div> {*block End*}
</div>	{*miles End*}
<!--<div class="content-spacer"></div>-->
{*Milestons END*}


{*Timetracker*}
{if $userpermissions.timetracker.add}
<div class="timetrack" style = "padding-bottom:2px;">
	<div class="headline accordion_toggle">
		<a href="javascript:void(0);" id="trackerhead_toggle" class="win_none" onclick = "changeElements('a.win_block','win_none');toggleBlock('trackerhead');"></a>

		<h2>
			<a href="managetimetracker.php?action=showproject&amp;id={$project.ID}" title="{#timetracker#}"><img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/timetracker.png" alt="" />{#timetracker#}</a>
		</h2>
	</div>

	<div class="block accordion_content" id="trackerhead" style = "overflow:hidden;">
		<div id = "trackerform" class="addmenue">
			{include file="addtimetracker.tpl" }
		</div>
		<div class="tablemenue"></div>
	</div> {*block end*}
</div> {*timetrack end*}

<!--<div class="content-spacer"></div>-->
{/if}
{*Timetracker End*}


{*Activity Log*}
<div class="neutral" style = "padding-bottom:2px;">
	{include file="log.tpl" }
</div>
{*Activity Log End*}


</div> {*nosmooth End*}

{*block dashboard end*}
</div>

{literal}
	<script type = "text/javascript">
	changeshow('manageproject.php?action=cal&id={/literal}{$project.ID}{literal}','thecal','progress');
	</script>
{/literal}

</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl" showcloud="1"}

{literal}
	<script type = "text/javascript">
		Event.observe(window,"load",function()
		{
			new Effect.Morph('completed', {
				style: 'width:{/literal}{$done}{literal}%',
				duration: 4.0
			});
		});
		var accord_dashboard = new accordion('block_dashboard');

				function activateAccordeon(theAccord){

					accord_dashboard.activate($$('#block_dashboard .accordion_toggle')[theAccord]);
					setCookie("activeSlideProject",theAccord);
				}
				var theBlocks = $$("#block_dashboard > div .headline > a");
				console.log(theBlocks);

				//loop through the blocks and add the accordion toggle link
				openSlide = 0;
				for(i=0;i<theBlocks.length;i++)
				{
					theCook = readCookie("activeSlideProject");
					console.log(theCook);
					if(theCook > 0)
					{
						openSlide = theCook;
					}

					var theAction = theBlocks[i].getAttribute("onclick");
					theAction += "activateAccordeon("+i+");";
					theBlocks[i].setAttribute("onclick",theAction);
					//console.log(theBlocks[i].getAttribute("onclick"));
				}


				//accordIndex.activate($$('#block_index .acc_toggle')[0]);
				activateAccordeon(0);

	</script>
{/literal}

{include file="footer.tpl"}
