<ul id="filesList" v-cloak>
    {* Folders *}
    {literal}
    <li v-for="folder in items.folders" id="fdli_{{*folder.ID}}">
        <div class="itemwrapper" id="iw_{{*folder.ID}}"
             ondrop="handleDrop(event);"
             ondragover="handleDragOver(event);"
             ondragenter="handleDragEnter(event);"
             ondragleave="handleDragLeave(event);" data-folderid="{{*folder.ID}}">

            <table cellpadding="0" cellspacing="0" border="0" data-folderid="{{*folder.ID}}">
                <tr>
                    <td class="leftmen" valign="top" data-folderid="{{*folder.ID}}">
                    </td>
                    <td class="thumb">
                        <a href="javascript:loadFolder(projectFilesView,{{folder.ID}});selectFolder({{folder.ID}});">
                            <img src="./templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/symbols/folder-sub.png"
                                 alt="" data-folderid="{{*folder.ID}}" / >
                        </a>
                    </td>
                    <td class="rightmen" valign="top">
                        <div class="inmenue">
                            {/literal}
                            {if $userpermissions.files.del}
                            {literal}
                                <a class="del" href="javascript:confirmDelete('{/literal}{$langfile.confirmdel}{literal}','fdli_{{*folder.ID}}','managefile.php?action=delfolder&amp;id={{*folder.project}}&amp;folder={{*folder.ID}}&ajax=1',projectFilesView);" title="{#delete#}" onclick=""></a>
                            {/literal}
                            {/if}
                            {literal}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
							<span class="name">
								<a href="javascript:loadFolder(projectFilesView,{{folder.ID}});selectFolder({{folder.ID}});" title="">
                                    {{*folder.name | truncate '10'}}
                                </a>
							</span>
                    </td>
                <tr/>
            </table>
        </div>
        <!--itemwrapper END -->
    </li>
    {/literal} {* loop folder END *}
    <div class="content-spacer"></div>

    {* Files *}
    {literal}
    <li v-for="file in items.files" id="fli_{{*file.ID}}" draggable="true" ondragstart="handleDragStart(event);" data-fileid="{{*file.ID}}">
        <div class="itemwrapper" id="iw_{{*file.ID}}" class="singleFile">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="leftmen" valign="top">
                        <div class="inmenue"></div>
                    </td>
                    <td class="thumb">
                        <template v-if="file.imgfile">
                            <a rel="lytebox[]" rev="width: 650px; height: 500px;"
                               href="managefile.php?action=downloadfile&amp;id={{*file.project}}&amp;file={{*file.ID}}"
                               data-fileid="{{*file.ID}}">
                                <img data-fileid="{{*file.ID}}"
                                     class="fileicon"
                                     v-bind:src="'templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/files/' +file.type + '.png'"
                                     alt="{{*file.name}}"/>
                            </a>
                        </template>
                        <template v-else>
                            <a href="managefile.php?action=downloadfile&amp;id={{*file.project}}&amp;file={{*file.ID}}" data-fileid="{{*file.ID}}">
                                <img data-fileid="{{*file.ID}}"
                                     class="fileicon"
                                     v-bind:src="'templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/files/' +file.type + '.png'"
                                     alt="{{*file.name}}"/>
                            </a>
                        </template>
                    </td>
                    <td class="rightmen" valign="top">
                        <div class="inmenue">
                            {/literal}
                            {if $userpermissions.files.del}
                            {literal}
                                <a class="del" href="javascript:confirmDelete('{/literal}{$langfile.confirmdel}{literal}','fli_{{*file.ID}}','managefile.php?action=delete&amp;id={{*file.project}}&amp;file={{*file.ID}}',projectFilesView);" title="{#delete#}"></a>
                            {/literal}
                            {/if}
                            {literal}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
						<span class="name">
                               <a href="managefile.php?action=downloadfile&amp;id={{*file.project}}&amp;file={{*file.ID}}" data-fileid="{{file.ID}}">
                                   {{*file.name | truncate '10'}}
                               </a>
						</span>
                    </td>
                <tr/>
            </table>
        </div>
        <!--itemwrapper End-->
    </li>
    <!-- files in fldes END-->
    {/literal}
</ul>


<div id="parentfolder" class="display-none">{$folderid}</div>
