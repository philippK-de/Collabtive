<div id="sitebody">
	<div id="header-wrapper" class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
				<a class="brand" href="index.php" title="{#desktop#}">{$settings.name}{if $settings.subtitle}<span class="subtitle"> {$settings.subtitle}</span>{/if}</a>
				{if $loggedin == 1}
				<div class="nav-collapse collapse">
					<p class="navbar-text pull-right">
						Logged in as <a href="manageuser.php?action=profile&amp;id={$userid}" class="navbar-link">{$username}</a>
					</p>
					<ul class="nav">
						<li class="desktop {$mainclasses.desktop}">
							<a class="{$mainclasses.desktop}" href="index.php"><span><i class="icon-home"></i> {#desktop#}</span></a>
						</li>
						{if $usergender == "f"}
						<li class="profil-female {$mainclasses.profil}">
							<a class="{$mainclasses.profil}" href="manageuser.php?action=profile&amp;id={$userid}"><span><i class="icon-user"></i> {#myaccount#}</span></a>
						</li>
						{else}
						<li class="profil-male {$mainclasses.profil}">
							<a class="{$mainclasses.profil}" href="manageuser.php?action=profile&amp;id={$userid}"><span><i class="icon-user"></i> {#myaccount#}</span></a>
						</li>
						{/if}
						{if $userpermissions.admin.add}
						<li class="dropdown {$mainclasses.admin}">
							<a class="{$mainclasses.admin} dropdown-toggle" href="admin.php?action=projects" data-toggle="dropdown"><i class="icon-wrench"></i> {#administration#} <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li class="project-settings {$classes.overview}">
									<a class="{$classes.overview}" href="admin.php?action=projects"><span><i class="icon-th-list"></i> {#projectadministration#}</span></a>
								</li>
								<li class="user-settings {$classes.users}">
									<a class="{$classes.users}" href="admin.php?action=users"><span><i class="icon-user"></i> {#useradministration#}</span></a>
								</li>
								<li class="system-settings {$classes.system}">
									<a class="{$classes.system}" href="admin.php?action=system"><span><i class="icon-cog"></i> {#systemadministration#}</span></a>
								</li>
							</ul>
						</li>
						{/if}
						<li class="logout">
							<a href="manageuser.php?action=logout"><i class="icon-off"></i> {#logout#}</a>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
				{/if}
			</div>
		</div>
	</div>
	<div class="container-fluid clearfix wrapper">

