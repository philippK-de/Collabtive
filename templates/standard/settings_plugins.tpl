<div class="headline">
    <a href="javascript:void(0);" id="block_system_toggle" class="win_block" onclick="toggleBlock('block_plugins');"></a>

    <h2>
        <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/system-settings.png" alt=""/>
        <a href="http://collabtive.o-dyn.de/plugins.php">Plugins</a>
    </h2>
</div>
<div id="block_plugins" class="block">
    <div class="block_in_wrapper">
        <form class="main" method="post" action="admin.php?action=editsets" enctype="multipart/form-data"
              {literal}onsubmit="return validateCompleteForm(this);"{/literal}>
            <fieldset>
                {section name=plugin loop=$plugins}
                    <div class="row">
                        <label for="{$plugins[plugin]}">{$plugins[plugin]}</label>
                        <input type="checkbox" class="checkbox" value="{$plugins[plugin]}" name="name" id="name" required="1" realname="{#name#}"
                               checked/>
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