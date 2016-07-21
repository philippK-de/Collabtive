<div id="sitebody">
	<div id="header-wrapper">
		<div id="header">
			<div class="header-in">

				<div class="left">
					<div class="logo">
						<h1>
							<a href="index.php" title="{#desktop#}">
								<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/logo-b.png" alt="" />
							</a>
							<span class="title">{$settings.name}
								<span class="subtitle"> {if $settings.subtitle}/ {$settings.subtitle} {/if} </span>
							</span>
						</h1>
					</div>
				</div> {* left END *}

				<div class="right">

					{if $loggedin == 1}
						<ul id="mainmenue">
							<li class="desktop">
								<a class="{$mainclasses.desktop}" href="index.php"><span>{#desktop#}</span></a>
							</li>

							{if $usergender == "f"}
								<li class="profil-female">
									<a class="{$mainclasses.profil}" href="manageuser.php?action=profile&amp;id={$userid}"><span>{#myaccount#}</span></a>
								</li>
							{else}
								<li class="profil-male">
									<a class="{$mainclasses.profil}" href="manageuser.php?action=profile&amp;id={$userid}"><span>{#myaccount#}</span></a>
								</li>
							{/if}

							{if $userpermissions.admin.add}
								<li class="admin">
									<a class="{$mainclasses.admin}" href="admin.php?action=projects"><span>{#administration#}</span><span class="submenarrow"></span></a>
									<div class="submen">
										<ul>
											<li class="project-settings"><a class="{$classes.overview|default}" href="admin.php?action=projects"><span>{#projectadministration#}</span></a></li>
											<li class="customer-settings"><a class="{$classes.customer|default}" href="admin.php?action=customers"><span>{#customeradministration#}</span></a></li>
											<li class="user-settings"><a class="{$classes.users|default}" href="admin.php?action=users"><span>{#useradministration#}</span></a></li>
											<li class="system-settings"><a class="{$classes.system|default}" href="admin.php?action=system"><span>{#systemadministration#}</span></a></li>
										</ul>
									</div>
								</li>
							{/if}

							<li class="logout"><a href="manageuser.php?action=logout"><span>{#logout#}</span></a></li>
						</ul>
					{/if}

				</div> <!-- right END -->

			</div> <!-- header-in END -->
		</div> <!-- header END -->
	</div> <!-- header-wrapper END -->

	<div id="contentwrapper">
