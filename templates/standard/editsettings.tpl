{include file="header.tpl" jsload="ajax" }
{include file="tabsmenue-admin.tpl" settingstab="active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="neutral">
		
			<div class="infowin_left" style="display:none;" id="systemmsg">
				{if $mode == "edited"}
					<span class="info_in_yellow">
						<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/system-settings.png" alt="" />
						{#settingsedited#}
					</span>
		        {elseif $mode == "imported"}
					<span class="info_in_green">
						<img src="templates/{$settings.template}/theme/{$settings.theme}/images/symbols/basecamp.png" alt="" />
						{#importsuccess#}
					</span>
				{/if}
				
			</div>
			
			{literal}
				<script type = "text/javascript">
					systemMsg('systemmsg');
				</script>
			{/literal}
			
			<h1>{#administration#}<span>/ {#systemadministration#}</span></h1>
			
			<div class="headline">
				<a href="javascript:void(0);" id="block_system_toggle" class="win_block" onclick="toggleBlock('block_system');"></a>
				<h2>
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/system-settings.png" alt="" />{#systemadministration#}</a>
				</h2>
			</div>
			
			<div id="block_system" class="block">
				{include file="settings_system.tpl"}
				<div class="tablemenue"></div>
			</div> {* block_system END *}
			
			<div class="content-spacer"></div>
			
			<div class="headline">
				<a href="javascript:void(0);" id="block_email_toggle" class="win_block" onclick="toggleBlock('block_email');"></a>
				<h2>
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/msgs.png" alt="" />{#email#}</a>
				</h2>
			</div>
			
			<div id="block_email" class="block">
				{include file="settings_email.tpl"}
				<div class="tablemenue"></div>
			</div> {* Block END *}
			
			<div class="content-spacer"></div>
			
		</div> {* neutral END *}
	</div> {* content-left-in END *}
</div> {* content-left END *}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}