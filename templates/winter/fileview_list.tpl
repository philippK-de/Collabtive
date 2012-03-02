

						<table id = "desktopprojects" cellpadding="0" cellspacing="0" border="0">

					<thead>
						<tr>
							<th class="a"></th>
							<th class="b" style = "text-align:left;">Name</th>
							<th class="c" style = "text-align:left;">Le / par</th>
							<th class="d" style = "text-align:left;">{#filesize#}</th>

							<th class="tools"></th>
						</tr>
					</thead>

					<tfoot>
						<tr>
							<td colspan="5"></td>
						</tr>
					</tfoot>

					{section name=folder loop=$folders}

					{*Color-Mix*}
					{if $smarty.section.folder.index % 2 == 0}
					<tbody class="color-a" id="thefold_{$folders[folder].ID}">
					{else}
					<tbody class="color-b" id="thefold_{$folders[folder].ID}">
					{/if}
						<tr >
							<td><img src = "templates/standard/images/symbols/folder-sub.png" /></td>
							<td>
<a href = "javascript:change('manageajax.php?action=fileview_list&id={$project.ID}&folder={$folders[folder].ID}','filescontent');">
										{if $folders[folder].name != ""}
										{$folders[folder].name|truncate:50:"...":true}
										{/if}
										</a>

							</td>
							<td>{$folders[folder].description|truncate:30:"...":true}</td>
							<td></td>
							<td class="tools">

							{if $userpermissions.files.del}
															<a class="tool_del" href="javascript:confirmfunction('{$langfile.confirmdel}','deleteElement(\'thefold_{$folders[folder].ID}\',\'managefile.php?action=delfolder&amp;id={$project.ID}&amp;folder={$folders[folder].ID}&ajax=1\')');" onclick="fadeToggle('thefold_{$folders[folder].ID}');" title="{#delete#}" ></a>
															<!-- <a class="edit" href="#" title="{#editfile#}"></a>-->
								{/if}
							</td>
						</tr>

					</tbody>

					{/section}

					{section name=file loop=$files}
                    <div id = "fli_{$files[file].ID}">
					{*Color-Mix*}
					{if $smarty.section.file.index % 2 == 0}
					<tbody class="color-a" id="fli_{$files[file].ID}">
					{else}
					<tbody class="color-b" id="fli_{$files[file].ID}">
					{/if}
						<tr >
							<td><a href = "{$files[file].datei}" {if $files[file].imgfile == 1} rel="lytebox[]" {elseif $files[file].imgfile == 2} rel = "lyteframe[text]" rev="width: 650px; height: 500px;"{/if}>
													 		{if $files[file].imgfile == 1}
													 		<img src = "thumb.php?pic={$files[file].datei}&amp;width=32" alt="{$files[file].name}" />
													 		{else}
													 		<img src = "templates/standard/images/files/{$files[file].type}.png" alt="{$files[file].name}" />
													 		{/if}
													 	</a></td>
							<td>
                                        <a href = "{$files[file].datei}" {if $files[file].imgfile == 1} rel="lytebox[]" {elseif $files[file].imgfile == 2} rel = "lyteframe[text]" rev="width: 650px; height: 500px;"{/if}>

										{$files[file].name}

                                        </a>

							</td>
							<td>{$files[file].addedstr} / <a href = "manageuser.php?action=profile&id={$files[file].userdata.ID}">{$files[file].userdata.name}</a></td>
							<td>{$files[file].size} KB</td>

                            <td class="tools">
								<a class="tool_edit" href="managefile.php?action=editform&amp;id={$project.ID}&amp;file={$files[file].ID}" title="{#editfile#}"></a>
							   	<a class="tool_del" href="javascript:confirmfunction('{$langfile.confirmdel}','deleteElement(\'fli_{$files[file].ID}\',\'managefile.php?action=delete&amp;id={$project.ID}&amp;file={$files[file].ID}\')');" title="{#delete#}"></a>

							</td>
						</tr>
					</tbody>
					</div>
					{literal}
                                    <script type = "text/javascript">
                                        new Draggable('{/literal}fli_{$files[file].ID}{literal}');
                                    </script>
                                {/literal}
					{/section}

				</table>

												{section name=fold loop=$folders}
						   {literal}
								 <script type = "text/javascript">
                                     Droppables.add('{/literal}thefold_{$folders[fold].ID}{literal}',{
                                     onDrop: function(element) {
                                     change('managefile.php?action=movefile&id={/literal}{$project.ID}{literal}&file='+element.id+'&target={/literal}{$folders[fold].ID}{literal}','jslog');
                                     element.hide();
                                     }

                                   });
                                </script>
                            {/literal}
        {/section}
							<script type = "text/javascript">
								 {if $foldername}
								$('dirname').innerHTML = '{$foldername}';
                            {else}
                            $('dirname').innerHTML = '{$langfile.rootdir}';
                            {/if}

								$('filenum').innerHTML = '{$filenum}';
								new LyteBox();


							</script>