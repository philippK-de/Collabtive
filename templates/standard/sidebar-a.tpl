<div id="content-right">
    <div id="sidebar-overlay" class="display-none" style="text-align:center">
       <img src="templates/standard/theme/standard/images/logo-b.png" onclick="openSidebar()" alt="" />
    </div>
    <div id="sidebar-content">
        {*Search*}
        <div class="content-right-in">
            <a href="javascript:closeSidebar();">close</a>
            <h2><a id="searchtoggle" class="win-up"
                   href="javascript:blindtoggle('search');toggleClass('searchtoggle','win-up','win-down');">{#search#}</a></h2>

            <form id="search" method="get" action="managesearch.php" {literal} onsubmit="return validateStandard(this,'input_error');"{/literal}>
                <fieldset>
                    <div class="row">
                        <input type="text" class="text" id="query" name="query"/>
                    </div>

                    <div id="choices"></div>
                    <input type="hidden" name="action" value="search"/>

                    <div id="indicator1" style="display:none;">
                        <img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/indicator_arrows.gif" alt="{#searching#}"/>
                    </div>

                    <button type="submit" title="{#gosearch#}"></button>
                </fieldset>
            </form>
        </div>

        {*Quickfinder*}
        {if $openProjects[0].ID > 0}
            <div class="content-right-in">
                <h2><a id="quickfindertoggle" class="win-up"
                       href="javascript:blindtoggle('quickfinder');toggleClass('quickfindertoggle','win-up','win-down');">{#myprojects#}</a></h2>

                <div id="quickfinder">
                    <form>
                        <select style="background-color:#CCC;width:100%;"
                                onchange="window.location='manageproject.php?action=showproject&id='+this.value;">
                            <option>{#chooseone#}</option>
                            {section name=drop loop=$openProjects}
                                <option value="{$openProjects[drop].ID}">{$openProjects[drop].name|truncate:40:"...":true}</option>
                            {/section}
                        </select>
                    </form>
                </div>
            </div>
        {/if}

        {*Onlinelist*}
        <div class="content-right-in">
            <h2><a id="onlinelisttoggle" class="win-up"
                   href="javascript:blindtoggle('onlinelist');toggleClass('onlinelisttoggle','win-up','win-down');">{#usersonline#}</a></h2>

            <div id="onlinelist"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="include/js/views/sidebar.js"></script>