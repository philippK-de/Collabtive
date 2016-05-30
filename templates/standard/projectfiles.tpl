{include file="header.tpl" jsload = "ajax" jsload3 = "lightbox" }
{include file="tabsmenue-project.tpl" filestab = "active"}

<div id="content-left">
    <div id="content-left-in">
        <div class="files" id="projectFiles">
            <!-- project text -->
            <div class="infowin_left"
                 id="fileSystemMessage"
                 data-icon="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/files.png"
                 data-text-added="{#filewasadded#}"
                 data-text-deleted="{#filewasdeleted#}"
                 style="display:none">
            </div>

            <div class="infowin_left" style="display:none;" id="systemmsg">
                {if $mode == "folderadded"}
                    <span class="info_in_green"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/folder-root.png"
                                                     alt=""/>{#folderwasadded#}</span>
                {elseif $mode == "folderedited"}
                    <span class="info_in_yellow"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/folder-root.png"
                                                      alt=""/>{#folderwasedited#}</span>
                {elseif $mode == "folderdel"}
                    <span class="info_in_red"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/folder-root.png"
                                                   alt=""/>{#folderwasdeleted#}</span>
                {/if}
            </div>

            <h1>{$projectname|truncate:45:"...":true}<span>/ {#files#}</span></h1>

            <div class="headline">
                <a href="javascript:void(0);" id="block_files_toggle" class="win_block" onclick="toggleBlock('block_files');"></a>

                <div class="wintools">
                    <div class="addmen">
                        <loader block="projectFiles" loader="loader-files.gif"></loader>
                        <div class="export-main">
                            <a class="export"><span>{#addbutton#}</span></a>

                            <div class="export-in" style="width:54px;left: -54px;"> {*at two items*}
                                {if $userpermissions.files.add}
                                    <a class="addfile" href="javascript:blindtoggle('form_file');" id="addfile"
                                       onclick="toggleClass(this,'addfile-active','addfile');toggleClass('add_file_butn','butn_link_active','butn_link');toggleClass('sm_files','smooth','nosmooth');"><span>{#addfile#}</span></a>
                                    <a class="addfolder" href="javascript:blindtoggle('form_folder');" id="addfolder"
                                       onclick="toggleClass(this,'addfolder-active','addfolder');toggleClass('add_folder_butn','butn_link_active','butn_link');toggleClass('sm_files','smooth','nosmooth');"><span>{#addfolder#}</span></a>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>

                <h2>
                    <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/folder-root.png" alt=""/><span
                            id="dirname">{literal}{{items.currentFolder.abspath}}{/literal}</span>
                    <pagination view="projectFilesView" :pages="pages" :current-page="currentPage"></pagination>
                </h2>
            </div>

            <div id="block_files" class="blockwrapper">
                {*Add Folder*}
                {if $userpermissions.files.add}
                    <div id="form_folder" class="addmenue" style="display:none;">
                        {include file="addfolder.tpl" }
                    </div>
                {/if}

                {*Add File*}
                {if $userpermissions.files.add}
                    <div id="form_file" class="addmenue" style="display:none;">
                        <div id="newupload" style="display:block">{include file="addfileform_new.tpl"}</div>
                    </div>
                {/if}
                <div class="nosmooth" id="sm_files">
                    <div class="contenttitle" id="dropDirUp">
                        <div class="contenttitle_menue">
                            {literal}
                            <a id="dirUp" class="dir_up_butn"
                               href="javascript:loadFolder(projectFilesView,{{items.currentFolder.parent}});selectFolder({{items.currentFolder.parent}})"
                               title="{/literal}{#parent#}"></a>
                        </div>
                        <div class="contenttitle_in" style="width:500px;">
                        </div>
                        <div style="float:right;margin-right:3px;">
                            <form id="typechose">
                                <select id="fileviewtype" onchange="changeFileview(this.value);">
                                    <option value="fileview" selected>{#gridview#}</option>
                                    <option value="fileview_list">{#listview#}</option>
                                </select>
                            </form>
                        </div>

                    </div>
                    <div class="content_in_wrapper">
                        <div class="content_in_wrapper_in">
                            {*change to fileview_list.tpl for list style view*}
                            <div id="filescontent" class="inwrapper">
                                {include file = "fileviewNew.tpl"}
                            </div>
                        </div> {*content_in_wrapper_in End*}
                    </div> {*content_in_wrapper End*}

                </div> {*nosmooth End*}

                <div class="tablemenue">
                    <div class="tablemenue-in">
                        {if $userpermissions.files.add}
                            <a class="butn_link" href="javascript:blindtoggle('form_file');" id="add_file_butn"
                               onclick="toggleClass('addfile','addfile-active','addfile');toggleClass(this,'butn_link_active','butn_link');toggleClass('sm_files','smooth','nosmooth');">{#addfile#}</a>
                            <a class="butn_link" href="javascript:blindtoggle('form_folder');" id="add_folder_butn"
                               onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('addfolder','addfolder-active','addfolder');toggleClass('sm_files','smooth','nosmooth');">{#addfolder#}</a>
                        {/if}
                    </div>
                </div>
            </div> {*block_files End*}

            <div class="content-spacer"></div>
        </div> {*Files END*}
    </div> {*content-left-in END*}
</div> {*content-left END*}
{literal}
    <script type="text/javascript" src="include/js/5up.min.js"></script>
    <script type="text/javascript" src="include/js/views/projectFilesView.min.js"></script>
<script type="text/javascript">
    projectFiles.url = projectFiles.url + "&id=" + {/literal}{$project.ID}{literal};
    pagination.itemsPerPage = 21;

    var projectFilesView = createView(projectFiles);

</script>

{/literal}
{include file="sidebar-a.tpl"}
{include file="footer.tpl"}