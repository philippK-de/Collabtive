{include file="header.tpl" jsload="ajax" jsload1="tinymce" jsload3="lightbox" stage="index"}
{include file="tabsmenue-desk.tpl" desktab="active"}

<div id="content-left">
    <div id="content-left-in">

        {* Display system messages *}
        <div class="infowin_left" style="display:none;" id="systemmsg">
            {if $mode == "projectadded"}
                <span class="info_in_green">
					<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt=""/>{#projectwasadded#}
				</span>
            {/if}

            {* For async display *}
            <span id="closed" style="display:none;" class="info_in_green">
				<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt=""/>{#projectwasclosed#}
			</span>
			<span id="deleted" style="display:none;" class="info_in_red">
				<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt=""/>{#projectwasdeleted#}
			</span>
        </div>

        {literal}
            <script type="text/javascript">
                systemMsg('systemmsg');
            </script>
        {/literal}

        {if $isUpdated|default}
            {include file="updateNotify.tpl"}
            <br/>
        {/if}

        <h1>{#desktop#}</h1>

        <div id="block_index" class="block">
            {* Projects *}
            {include file="desktopProjects.tpl"}
            {* Tasks *}
            {include file="desktopTasks.tpl"}

            {* Milestones *}
            {if $tasknum}
                <div class="miles" style="padding-bottom:2px;">
                    <div class="headline">
                        <a href="javascript:void(0);" id="mileshead_toggle" class="win_none"
                           onclick="changeElements('a.win_block','win_none');toggleBlock('mileshead');"></a>

                        <div class="wintools">
                            <div class="progress" id="progress" style="display:none;">
                                <img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/loader-cal.gif"/>
                            </div>
                        </div>

                        <h2>
                            <img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/miles.png" alt=""/>{#calendar#}
                        </h2>
                    </div>

                    <div class="acc_toggle"></div>
                    <div class="block acc_content" id="mileshead" style="overflow:hidden;">
                        <div id="thecal" class="bigcal" style="height:270px;"></div>
                        <div class="content-spacer"></div>
                    </div> {* block END *}
                </div>
                {* miles END *}
                {* milestons END *}
            {/if}

          {include file="desktopMessages.tpl"}

        </div> {* block index end*}
        <div class="content-spacer"></div>
    </div> {* content-left-in END *}
</div> {* content-left END *}
<script type="text/javascript" src="include/js/views/index.js"></script>
{include file="sidebar-a.tpl"}
{include file="footer.tpl"}
