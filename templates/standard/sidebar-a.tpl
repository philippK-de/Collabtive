<div id="content-right" class="overflow-hidden" data-opened="false">
    <!-- the overlay to be displayed when the sidebar is hidden -->
    <div id="sidebar-overlay" class="text-align-center">
       <img src="templates/standard/theme/standard/images/logo-b.png" onclick="toggleSidebar()" alt="" style="cursor: ew-resize" />
    </div>
    <div id="sidebar-content" class="overflow-hidden display-none">
        <!--testplugin-->
        {if $showConferenceSidebarControls == 1}
            <!--conferenceSidebarControls-->
        {/if}
        <div class="content-right-in overflow-hidden">
            <div>
                <h2>
                    <a id="searchtoggle" class="win-up">{#search#}</a>
                </h2>
                <form id="search" method="get" action="managesearch.php"  onsubmit="return validateStandard(this,'input_error');">
                    <fieldset>
                        <div class="row">
                            <input type="text" class="text" id="query" name="query"/>
                        </div>
                        <div id="choices"></div>
                        <input type="hidden" name="action" value="search"/>

                        <div id="indicator1" class="display-none">
                            <img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/indicator_arrows.gif" alt=""/>
                        </div>
                        <button type="submit"></button>
                    </fieldset>
                </form>
            </div>
        </div>

        {*Quickfinder*}
        {if $openProjects[0].ID > 0}
            <div class="content-right-in overflow-hidden">
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
            <div class="content-spacer"></div>
        {/if}
    </div>
</div>
<script type="text/javascript" src="include/js/components/searchWidgetComponent.js"></script>
<script type="text/javascript" src="include/js/views/sidebar.js"></script>
