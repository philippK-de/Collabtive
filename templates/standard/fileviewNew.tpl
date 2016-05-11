<ul>
    {literal}
        <li v-for="folder in items.folders" id="fdli_{{*folder.ID}}">
            <div class="itemwrapper" id="iw_{{*folder.ID}}">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td class="leftmen" valign="top">
                        </td>
                        <td class="thumb">
                            <a href="javascript:change('manageajax.php?action=fileview&id={{*folder.project}}&folder={{*folder.ID}}','filescontent');
                            selectFolder({{folder.ID}});">
                                <img src="./templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/symbols/folder-sub.png"
                                alt="" />
                            </a>
                        </td>
                        <td class="rightmen" valign="top">
                            <div class="inmenue">
                                {/literal}
                                {if $userpermissions.files.del}
                                {literal}
                                    <a class="del" href="javascript:confirmDelete('{/literal}{$langfile.confirmdel}{literal}','fdli_{{*folder.ID}}','managefile.php?action=delfolder&amp;id={{*folder.project}}&amp;folder={{*folder.ID}}&ajax=1');" title="{#delete#}" onclick=""></a>
                                {/literal}
                                {/if}
                                {literal}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
							<span class="name">
								<a href="javascript:change('manageajax.php?action=fileview&id={{*folder.project}}&folder={{*folder.ID}}','filescontent');selectFolder(folder.ID);" title="">
                                        {{*folder.name | truncate '10'}}
                                </a>
							</span>
                        </td>
                    <tr/>

                </table>

            </div> <!--itemwrapper END -->
        </li>
    {/literal} {* loop folder END *}
    <div class="content-spacer"></div>

    {literal}
        <li v-for="file in items.files" id="fli_{{*file.ID}}">
            <div class="itemwrapper" id="iw_{{*file.ID}}">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td class="leftmen" valign="top">
                            <div class="inmenue"></div>
                        </td>
                        <td class="thumb">
                            <a href="managefile.php?action=downloadfile&amp;id={{*file.project}}&amp;file={{*file.ID}}" {if $files[file].imgfile == 1}  rel="lytebox[]" {elseif $files[file].imgfile == 2} rel = "lyteframe[text]" rev="width: 650px; height: 500px;" {/if} >
                                <img src="templates/{/literal}{$settings.template}/theme/{$settings.theme}/{literal}images/files/{{*file.type}}.png" alt="{{*file.name}}" />
                            </a>
                        </td>
                        <td class="rightmen" valign="top">
                            <div class="inmenue">
                                {/literal}
                                {if $userpermissions.files.del}
                                {literal}
                                    <a class="del" href="javascript:confirmDelete('{/literal}{$langfile.confirmdel}{literal}','fli_{{*file.ID}}','managefile.php?action=delete&amp;id={{*file.project}}&amp;file={{*file.ID}}');" title="{#delete#}"></a>
                                {/literal}
                                {/if}
                                {literal}

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
							<span class="name">
                                <a href="managefile.php?action=downloadfile&amp;id={{*file.project}}&amp;file={{*file.ID}}" {if $files[file].imgfile == 1}  rel="lytebox[]" {elseif $files[file].imgfile == 2} rel = "lyteframe[text]" rev="width: 650px; height: 500px;" {/if} >
                                        {{*file.name | truncate '10'}}

                                </a>
							</span>
                        </td>
                    <tr/>
                </table>

            </div> <!--itemwrapper End-->
        </li>

        <script type = "text/javascript">
            new Draggable('fli_{{*file.ID}}',{revert:true});
        </script>

    <!-- files in fldes END-->
    {/literal}
</ul>


{section name=fold loop=$folders}
{literal}
    <script type="text/javascript">
        try {
            Droppables.add('{/literal}fdli_{$folders[fold].ID}{literal}',{
                onDrop: function(element) {
                    change('managefile.php?action=movefile&id={/literal}{$project.ID}{literal}&file='+element.id+'&target={/literal}{$folders[fold].ID}{literal}','jslog');
                    element.hide();
                }
            });
        }
        catch(e){}
    </script>
{/literal}
{/section}

<div id="parentfolder" style="display:none;">{$folderid}</div>

<script type="text/javascript">
    {literal}
    parentFolder = $("parentfolder").innerHTML;
    Droppables.add('dropDirUp',{
        onDrop: function(element) {
            //alert('managefile.php?action=movefile&id={/literal}{$project.ID}{literal}&file='+element.id+'&target='+parentFolder);
            change('managefile.php?action=movefile&id={/literal}{$project.ID}{literal}&file='+element.id+'&target='+parentFolder,'jslog');
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
    $('dirUp').href = "javascript:change('manageajax.php?action=fileview&id={$project.ID}&folder={$folderid}','filescontent');selectFolder({$folderid});"
</script>