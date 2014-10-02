<ul>
	{section name=fold loop=$folders}
		<li id="fdli_{$folders[fold].ID}">
			<div class="itemwrapper" id="iw_{$folders[fold].ID}">

				<table cellpadding="0" cellspacing="0" border="0">

					<tr>
						<td class="leftmen" valign="top">
							<div class="inmenue">
								<a class="export" href="managefile.php?action=folderexport&file={$folders[fold].ID}&id={$project.ID}" title="{#export#}"></a>
							</div>
						</td>
						<td class="thumb">
							<a href="javascript:change('manageajax.php?action=fileview&id={$project.ID}&folder={$folders[fold].ID}','filescontent');selectFolder({$folders[fold].ID});">
								<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/folder-sub.png" alt="" />
							</a>
						</td>
						<td class="rightmen" valign="top">
							<div class="inmenue">
								{if $userpermissions.files.del}
									<a class="del" href="javascript:confirmfunction('{$langfile.confirmdel}','deleteElement(\'fdli_{$folders[fold].ID}\',\'managefile.php?action=delfolder&amp;id={$project.ID}&amp;folder={$folders[fold].ID}&ajax=1\')');" title="{#delete#}" onclick=""></a>
								{/if}
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<span class="name">
								<a href="javascript:change('manageajax.php?action=fileview&id={$project.ID}&folder={$folders[fold].ID}','filescontent');selectFolder({$folders[fold].ID});" title="{$myprojects[project].messages[message].files[file].name}">
									{if $folders[fold].name != ""}
										{$folders[fold].name|truncate:14:"...":true}
									{else}
										{#folder#}
									{/if}
								</a>
							</span>
						</td>
					<tr/>

				</table>

			</div> {* itemwrapper END *}
		</li>
	{/section} {* loop folder END *}

	{section name=file loop=$files}
		<li id="fli_{$files[file].ID}">
			<div class="itemwrapper" id="iw_{$files[file].ID}">

				<table cellpadding="0" cellspacing="0" border="0">

					<tr>
						<td class="leftmen" valign="top">
							<div class="inmenue"></div>
						</td>
						<td class="thumb">
						{*href="{$files[file].datei}"*}
							<a href="managefile.php?action=downloadfile&amp;id={$project.ID}&amp;file={$files[file].ID}" {if $files[file].imgfile == 1}  rel="lytebox[]" {elseif $files[file].imgfile == 2} rel = "lyteframe[text]" rev="width: 650px; height: 500px;" {/if} >
								{*if $files[file].imgfile == 1}
									<img src="thumb.php?pic={$files[file].datei}&amp;width=32" alt="{$files[file].name}" />

								{else}
									<img src="templates/{$settings.template}/theme/{$settings.theme}/images/files/{$files[file].type}.png" alt="{$files[file].name}" />
								{/if*}
								<img src="templates/{$settings.template}/theme/{$settings.theme}/images/files/{$files[file].type}.png" alt="{$files[file].name}" />
							</a>
						</td>
						<td class="rightmen" valign="top">
							<div class="inmenue">
								{if $userpermissions.files.del}
									<a class="del" href="javascript:confirmfunction('{$langfile.confirmdel}','deleteElement(\'fli_{$files[file].ID}\',\'managefile.php?action=delete&amp;id={$project.ID}&amp;file={$files[file].ID}\')');" title="{#delete#}"></a>
								{/if}

								{if $userpermissions.files.edit}
									<a class="edit" href="managefile.php?action=editform&amp;id={$project.ID}&amp;file={$files[file].ID}" title="{#editfile#}"></a>
								{/if}
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<span class="name">
							{*	<a href="{$files[file].datei}" {if $files[file].imgfile == 1} rel="lytebox[img{$files[file].ID}]" {elseif $files[file].imgfile == 2} rel="lyteframe[text{$files[file].ID}]" {/if} title="{$files[file].name}" onmousedown="change('managefile.php?action=makeseen&file={$files[file].ID}&id={$project.ID}','jslog');">*}
								<a href="managefile.php?action=downloadfile&amp;id={$project.ID}&amp;file={$files[file].ID}" {if $files[file].imgfile == 1}  rel="lytebox[]" {elseif $files[file].imgfile == 2} rel = "lyteframe[text]" rev="width: 650px; height: 500px;" {/if} >
									{if $files[file].title != ""}
										{$files[file].title|truncate:14:"...":true}
									{else}
										{$files[file].name|truncate:14:"...":true}
									{/if}
								</a>
							</span>
						</td>
					<tr/>
				</table>

			</div> {*itemwrapper End*}
		</li>

		{literal}
			<script type = "text/javascript">
				new Draggable('{/literal}fli_{$files[file].ID}{literal}',{revert:true});
			</script>
		{/literal}

	{/section} {* files in fldes END *}
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