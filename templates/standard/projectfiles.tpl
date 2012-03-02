{include file="header.tpl" jsload = "ajax" jsload3 = "lightbox" }
{include file="tabsmenue-project.tpl" filestab = "active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="files">

			<div class="infowin_left">
				<span id = "deleted" style = "display:none;" class="info_in_red"><img src="templates/standard/images/symbols/files.png" alt=""/>{#filewasdeleted#}</span>
			</div>

			<div class="infowin_left" style = "display:none;" id = "systemmsg">
				{if $mode == "added"}
				<span class="info_in_green"><img src="templates/standard/images/symbols/files.png" alt=""/>{#filewasadded#}</span>
				{elseif $mode == "edited"}
				<span class="info_in_yellow"><img src="templates/standard/images/symbols/files.png" alt=""/>{#filewasedited#}</span>
				{elseif $mode == "folderadded"}
				<span class="info_in_green"><img src="templates/standard/images/symbols/folder-root.png" alt=""/>{#folderwasadded#}</span>
				{elseif $mode == "folderedited"}
				<span class="info_in_yellow"><img src="templates/standard/images/symbols/folder-root.png" alt=""/>{#folderwasedited#}</span>
				{elseif $mode == "folderdel"}
				<span class="info_in_red"><img src="templates/standard/images/symbols/folder-root.png" alt=""/>{#folderwasdeleted#}</span>
				{/if}
			</div>
			
			{literal}
				<script type = "text/javascript">
					systemMsg('systemmsg');
				</script>
			{/literal}

			<h1>{$projectname|truncate:45:"...":true}<span>/ {#files#}</span></h1>

			<div class="headline">
				<a href="javascript:void(0);" id="block_files_toggle" class="win_block" onclick = "toggleBlock('block_files');"></a>

				<div class="wintools">
					<div class="addmen">
						<div class="export-main">
							<a class="export"><span>{#addbutton#}</span></a>
							<div class="export-in"  style="width:54px;left: -54px;"> {*at two items*}
								{if $userpermissions.files.add}
								<a class="addfile" href="javascript:blindtoggle('form_file');" id="addfile" onclick="toggleClass(this,'addfile-active','addfile');toggleClass('add_file_butn','butn_link_active','butn_link');toggleClass('sm_files','smooth','nosmooth');"><span>{#addfile#}</span></a>
								<a class="addfolder" href="javascript:blindtoggle('form_folder');" id="addfolder" onclick="toggleClass(this,'addfolder-active','addfolder');toggleClass('add_folder_butn','butn_link_active','butn_link');toggleClass('sm_files','smooth','nosmooth');"><span>{#addfolder#}</span></a>	{/if}
							</div>
						</div>
					</div>
				</div>

				<h2>
					<img src="./templates/standard/images/symbols/folder-root.png" alt="" /><span id = "dirname">{#rootdir#}</span>
				</h2>
			</div>

			<div id="block_files" class="blockwrapper">
				{*Add Folder*}
				{if $userpermissions.files.add}
					<div id = "form_folder" class="addmenue" style = "display:none;">
						{include file="addfolder.tpl" }
					</div>
				{/if}

				{*Add File*}
				{if $userpermissions.files.add}
					<div id = "form_file" class="addmenue" style = "display:none;">
						{include file="addfileform.tpl" }
					</div>
				{/if}

				<div class="nosmooth" id="sm_files">
					<div class="contenttitle">
						<div class="contenttitle_menue">
							<a id = "dirUp" class="dir_up_butn" href="javascript:change('manageajax.php?action=fileview&id={$project.ID}&folder=0','filescontent');" title="{#parent#}"></a>
						</div>
						<div class="contenttitle_in">
							<a href="manageajax.php?action=fileview&id={$project.ID}&folder={$folders[fold].ID}"></a>
						</div>
					</div>
					<div class="content_in_wrapper">
						<div class="content_in_wrapper_in">

							{*change to fileview_list.tpl for list style view*}
							<div id = "filescontent" class="inwrapper" >
								{include file = "fileview.tpl"}
							</div>
						</div> {*content_in_wrapper_in End*}
					</div> {*content_in_wrapper End*}
					<div class="staterow">
						<div class="staterowin">
							<span id = "filenum">{$filenum}</span> {#files#}
						</div>
						<div class="staterowin_right"><span >{$langfile.page} {paginate_prev} {paginate_middle} {paginate_next}</span></div>
					</div>
				</div> {*nosmooth End*}
			
				<div class="tablemenue">
					<div class="tablemenue-in">
						{if $userpermissions.files.add}
						<a class="butn_link" href="javascript:blindtoggle('form_file');" id="add_file_butn" onclick="toggleClass('addfile','addfile-active','addfile');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_files','smooth','nosmooth');">{#addfile#}</a>
						<a class="butn_link" href="javascript:blindtoggle('form_folder');" id="add_folder_butn" onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('addfolder','addfolder-active','addfolder');toggleClass('sm_files','smooth','nosmooth');">{#addfolder#}</a>
						{/if}
					</div>
				</div>
			</div> {*block_files End*}

			<div class="content-spacer"></div>
		</div> {*Files END*}
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}