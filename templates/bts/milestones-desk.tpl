{*Milestones*}
{if $myprojects}
<div class="miles">
	<div class="headline navbar navbar-inverse clearfix">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="mytasks.php" title="{#mytasks#}">{#calendar#}</a>
					<ul class="nav pull-right">
						<li class="divider-vertical"></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="icon-cog big"></i>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a href="javascript:void(0);" id="mileshead_toggle" class="{$milebar}" onclick="toggleBlock('mileshead');"><i class="icon-resize-vertical"></i> {#toggle#}</a>
									<div class="progress pull-right" id="progress" style="display:none; height:0;"></div>
								</li>
							</ul>
						</li>
					</ul>
			</div>
		</div>
	</div>
	<div class="block" id="mileshead" style="{$tmilestyle}">
		<div id="thecal" class="bigcal"></div>
	</div>
</div>
{*Milestons END*}
{/if}