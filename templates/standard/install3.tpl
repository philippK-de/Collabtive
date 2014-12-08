{include file="header.tpl" title="install" showheader="no"}
				
		<div class="install" style="text-align:center; padding:5% 0;">
			<div style="text-align:left;width:500px;margin:0 auto;padding:25px 25px 0px 25px;background:white;border:1px solid;">
				
				<h1>{#installstatus#}</h1>
				
				<div style="padding:16px 0 20px 0;">
					
					<h2>{#installsuccess#}</h2>
					
					{#installsuccesstext#}
					
				</div>
				
				<div class="row-butn-bottom">
					<button onclick="window.open('http://www.collabtive.o-dyn.de/plugins.php')" onfocus="this.blur();">Learn more about Plugins</button>
				</div>

				<div class="row-butn-bottom">
					<button onclick="location.href='index.php'" onfocus="this.blur();">{#close#}</button>
				</div>
				
				<div class="content-spacer"></div>
			</div>
		</div> {* install END *}
		
	</body>
</html>
