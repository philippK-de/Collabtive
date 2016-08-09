<div class="headline">
    <a href="javascript:void(0);" id="block_system_toggle" class="win_block" onclick="toggleBlock('block_plugins');"></a>

    <h2>
        <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/system-settings.png" alt=""/>
        <a href="http://collabtive.o-dyn.de/plugins.php">Plugins</a>
    </h2>
</div>
<div id="block_plugins" class="block">
    <div class="block_in_wrapper">
        <form class="main" method="post" action="admin.php?action=editPluginSettings" {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
            <fieldset>
                {section name=plugin loop=$plugins}
                    <div class="row">
                        <label for="{$plugins[plugin][0]}">{$plugins[plugin][0]}</label>
                        <input type="checkbox" class="checkbox" value="1" name="plugins[{$plugins[plugin][0]}]"
                               id="{$plugins[plugin][0]}"
                               realname="{#name#}"
                               {if $plugins[plugin][1] == true }checked{/if} />
                    </div>
                {/section}
                <div class="row-butn-bottom">
                    <label>&nbsp;</label>
                    <button type="submit" onfocus="this.blur();">{#send#}</button>
                </div>
            </fieldset>
        </form>
    </div> {*block_in_wrapper end*}
</div>