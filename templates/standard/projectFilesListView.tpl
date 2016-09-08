<table id="desktopprojects" cellpadding="3px" cellspacing="0" border="1" style="border-left:0px solid;border-collapse:collapse;width:100%">
    <thead>
    <tr>
        <th class="a text-align-center" style="width:5%;border-right:0px;"></th>
        <th class="b text-align-left" style="width:50%;border-left:0px;">{#name#}</th>
        <th class="c text-align-left" style="width:20%">{#uploaded#}</th>
        <th class="d" style="text-align:left;width:10%;">{#filesize#}</th>
        <th class="tools" style="width:3%;"></th>
    </tr>
    </thead>

    {literal}
        <tbody v-for="folder in items.folders" class="color-a" id="thefold_{{*folder.ID}}">
        <tr>
            <td style="border-right:0px;"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/folder-sub.png"/></td>
            <td style="border-left:0px;">
                <a href="javascript:change('manageajax.php?action=fileview_list&id={$project.ID}&folder={$folders[folder].ID}','filescontent');">
                    {{*folder.name | truncate '25'}}
                </a>
            </td>
            <td>{{*folder.description | truncate '30'}}</td>
            <td></td>
            <td class="tools">
                {/literal}
                {if $userpermissions.files.del}
                {literal}
                    <a class="del" href="javascript:confirmDelete('{/literal}{$langfile.confirmdel}{literal}','fdli_{{*folder.ID}}','managefile.php?action=delfolder&amp;id={{*folder.project}}&amp;folder={{*folder.ID}}&ajax=1',projectFilesView);" title="{#delete#}" onclick=""></a>
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
            <td style="border-right:0px;">
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
            <td style="border-left:0px;">
                <template v-if="file.imgfile">
                    <a rel="lytebox[]" rev="width: 650px; height: 500px;"
                       href="managefile.php?action=downloadfile&amp;id={{*file].project}}&amp;file={{*file.ID}}"
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
            <td>{{*file.size}} kB</td>
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

{section name=fold loop=$folders}
{literal}
    <script type="text/javascript">
        Droppables.add('{/literal}thefold_{$folders[fold].ID}{literal}', {
            onDrop: function (element) {
                change('managefile.php?action=movefile&id={/literal}{$project.ID}{literal}&file=' + element.id + '&target={/literal}{$folders[fold].ID}{literal}', 'jslog');
                element.hide();
            }
        });
    </script>
{/literal}
{/section}

<div id="parentfolder" style="display:none;">{$folderid}</div>

<script type="text/javascript">
    {literal}
    parentFolder = $("parentfolder").innerHTML;
    Droppables.add('dropDirUp', {
        onDrop: function (element) {
            //alert('managefile.php?action=movefile&id={/literal}{$project.ID}{literal}&file='+element.id+'&target='+parentFolder);
            change('managefile.php?action=movefile&id={/literal}{$project.ID}{literal}&file=' + element.id + '&target=' + parentFolder, 'jslog');
            element.hide();
        }
    });
    {/literal}

    {if $foldername}
    $('dirname').innerHTML = '{$foldername}';
    {else}
    $('dirname').innerHTML = '{$langfile.rootdir}';
    {/if}

    $('filenum').innerHTML = '{$filenum}';
    new LyteBox();
    $('dirUp').href = "javascript:change('manageajax.php?action=fileview_list&id={$project.ID}&folder={$folderid}','filescontent');"
</script>
