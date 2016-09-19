<table id="filesviewList" class="border-left-none border-collapse" cellpadding="3px" cellspacing="0" style="width:100%;">
    <thead>
    <tr class="">
        <th class="a text-align-center border-right-none"></th>
        <th class="b text-align-lef border-left-none">{#name#}</th>
        <th class="c text-align-left">{#uploaded#}</th>
        <th class="d" class="text-align-right">{#filesize#}</th>
        <th class="tools" style="width:3%;"></th>
    </tr>
    </thead>

    {literal}
        <tbody v-for="folder in items.folders" class="color-a" id="thefold_{{*folder.ID}}">
        <tr>
            <td style="border-right:0;">
                <img src="templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/symbols/folder-sub.png"/>
            </td>
            <td style="border-left:0;">
                <a href="javascript:loadFolder(projectFilesView,{{folder.ID}});selectFolder({{folder.ID}});">
                    {{*folder.name | truncate '25'}}
                </a>
            </td>
            <td>{{*folder.description | truncate '30'}}</td>
            <td></td>
            <td class="tools">
                {/literal}
                {if $userpermissions.files.del}
                {literal}
                    <a  class="del"
                        href="javascript:confirmDelete('{/literal}{$langfile.confirmdel}{literal}','fdli_{{*folder.ID}}','managefile.php?action=delfolder&amp;id={{*folder.project}}&amp;folder={{*folder.ID}}&ajax=1',projectFilesView);"
                        title="{/literal}{#delete#}{literal}" onclick=""></a>
                {/literal}
                {/if}
                {literal}
            </td>
        </tr>

        </tbody>
    {/literal}

    {literal}
        <tbody v-for="file in items.files" id="fli_{{*file.ID}}" class="color-a">
        <tr>
            <td class="border-right-none">
                <template v-if="file.imgfile">
                    <a rel="lytebox[]" rev="width: 650px; height: 500px;"
                       href="managefile.php?action=downloadfile&amp;id={{*file.project}}&amp;file={{*file.ID}}"
                       data-fileid="{{*file.ID}}">
                        <img data-fileid="{{*file.ID}}"
                             src="templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/files/{{*file.type}}.png"
                             alt="{{*file.name}}"/>
                    </a>
                </template>
                <template v-else>
                    <a href="managefile.php?action=downloadfile&amp;id={{*file.project}}&amp;file={{*file.ID}}" data-fileid="{{*file.ID}}">
                        <img data-fileid="{{*file.ID}}"
                             src="templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/files/{{*file.type}}.png"
                             alt="{{*file.name}}"/>
                    </a>
                </template>
            </td>
            <td class="border-left-none">
                <template v-if="file.imgfile">
                    <a rel="lytebox[]" rev="width: 650px; height: 500px;"
                       href="managefile.php?action=downloadfile&amp;id={{*file.project}}&amp;file={{*file.ID}}"
                       data-fileid="{{*file.ID}}">
                        {{*file.name | truncate '75'}}
                    </a>
                </template>
                <template v-else>
                    <a href="managefile.php?action=downloadfile&amp;id={{*file.project}}&amp;file={{*file.ID}}"
                       data-fileid="{{*file.ID}}">
                        {{*file.name | truncate '75'}}
                    </a>
                </template>
            </td>
            <td>
                {{*file.addedstr}} /
                <a href="manageuser.php?action=profile&id={{*file.userdata.ID}}">{{*file.userdata.name}}</a>
            </td>
            <td class="text-align-right">{{*file.size}} kB</td>
            <td class="tools">
                {/literal}
                {if $userpermissions.files.del}
                {literal}
                    <a class="tool_del" href="javascript:confirmDelete('{/literal}{$langfile.confirmdel}{literal}','fli_{{*file.ID}}','managefile.php?action=delete&amp;id={{*file.project}}&amp;file={{*file.ID}}',projectFilesView);" title="{#delete#}"></a>
                {/literal}
                {/if}
                {literal}
            </td>
        </tr>
        </tbody>

    {/literal}

</table>
<div id="parentfolder" style="display:none;">{$folderid}</div>

<script type="text/javascript">
    {if $foldername}
    cssId('dirname').innerHTML = '{$foldername}';
    {else}
    cssId('dirname').innerHTML = '{$langfile.rootdir}';
    {/if}

    new LyteBox();
</script>
