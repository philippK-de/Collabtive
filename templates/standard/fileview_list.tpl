{config_load file='lng.conf' section="strings" scope="global"}

<table id="desktopprojects" cellpadding="3px" cellspacing="0" border="1" style="border-left:0px solid;border-collapse:collapse;width:100%">

	<thead>
		<tr>
			<th class="a" style="text-align:center;width:5%;border-right:0px;"></th>
			<th class="b" style="text-align:left;width:50%;border-left:0px;">{#name#}</th>
			<th class="c" style="text-align:left;width:20%">{#uploaded#}</th>
			<th class="d" style="text-align:left;width:10%;">{#filesize#}</th>
			<th class="tools" style="width:3%;"></th>
		</tr>
	</thead>

	{section name=folder loop=$folders}

		{*Color-Mix*}
		{if $smarty.section.folder.index % 2 == 0}
			<tbody class="color-a" id="thefold_{$folders[folder].ID}">
		{else}
			<tbody class="color-b" id="thefold_{$folders[folder].ID}">
		{/if}

			<tr>
				<td style="border-right:0px;"><img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/folder-sub.png" /></td>
				<td style="border-left:0px;">
					<a href="javascript:change('manageajax.php?action=fileview_list&id={$project.ID}&folder={$folders[folder].ID}','filescontent');">
						{if $folders[folder].name != ""}
							{$folders[folder].name|truncate:100:"...":true}
						{/if}
					</a>
				</td>
				<td>{$folders[folder].description|truncate:30:"...":true}</td>
				<td></td>
				<td class="tools">
					{if $userpermissions.files.del}
						<a class="tool_del" href="javascript:confirmfunction('{$langfile.confirmdel}','deleteElement(\'thefold_{$folders[folder].ID}\',\'managefile.php?action=delfolder&amp;id={$project.ID}&amp;folder={$folders[folder].ID}&ajax=1\')');" title="{#delete#}"></a>
					{/if}
				</td>
			</tr>

		</tbody>

	{/section}

	{section name=file loop=$files}

		{*Color-Mix*}
		{if $smarty.section.file.index % 2 == 0}
			<tbody class="color-a" id="fli_{$files[file].ID}">
		{else}
			<tbody class="color-b" id="fli_{$files[file].ID}">
		{/if}

			<tr>
				<td style="border-right:0px;">
					<a href="managefile.php?action=downloadfile&amp;id={$files[file].project}&amp;file={$files[file].ID}" {if $files[file].imgfile == 1} rel="lytebox[]" {elseif $files[file].imgfile == 2} rel = "lyteframe[text]" rev="width: 650px; height: 500px;" {/if} >
			 			{*if $files[file].imgfile == 1}
				 			<img src="thumb.php?pic={$files[file].datei}&amp;width=32" alt="{$files[file].name}" />
				 		{else}
				 			<img src="templates/{$settings.template}/theme/{$settings.theme}/images/files/{$files[file].type}.png" alt="{$files[file].name}" />
				 		{/if*}
				 		<img src="templates/{$settings.template}/theme/{$settings.theme}/images/files/{$files[file].type}.png" alt="{$files[file].name}" />
					 </a>
				</td>
				<td style="border-left:0px;">
					<a href="managefile.php?action=downloadfile&amp;id={$files[file].project}&amp;file={$files[file].ID}" {if $files[file].imgfile == 1} rel="lytebox[]" {elseif $files[file].imgfile == 2} rel = "lyteframe[text]" rev="width: 650px; height: 500px;" {/if} >
						{$files[file].name|truncate:75:"...":true}
					</a>
				</td>
				<td>
					{$files[file].addedstr} /
					<a href="manageuser.php?action=profile&id={$files[file].userdata.ID}">{$files[file].userdata.name}</a>
				</td>
				<td>{$files[file].size} kB</td>
	            <td class="tools">
				   	<a class="tool_del" href="javascript:confirmfunction('{$langfile.confirmdel}','deleteElement(\'fli_{$files[file].ID}\',\'managefile.php?action=delete&amp;id={$project.ID}&amp;file={$files[file].ID}\')');" title="{#delete#}"></a>
				</td>
			</tr>
		</tbody>

		{literal}
            <script type="text/javascript">
                new Draggable('{/literal}fli_{$files[file].ID}{literal}');
            </script>
        {/literal}

	{/section}

</table>

{section name=fold loop=$folders}
	{literal}
		<script type="text/javascript">
			Droppables.add('{/literal}thefold_{$folders[fold].ID}{literal}',{
				onDrop: function(element) {
					change('managefile.php?action=movefile&id={/literal}{$project.ID}{literal}&file='+element.id+'&target={/literal}{$folders[fold].ID}{literal}','jslog');
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
	$('dirUp').href = "javascript:change('manageajax.php?action=fileview_list&id={$project.ID}&folder={$folderid}','filescontent');"
</script>
