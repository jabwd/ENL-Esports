<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<base href="<?php echo $baseURL; ?>"/>
	<title><?php echo sprintf($baseTitle,$pageName); ?></title>
	<link type="text/css" rel="stylesheet" href="styles/<?php echo $themeKey; ?>/css/all.css"/>
	
	<link rel="stylesheet" type="text/css" href="resources/default.css"/>
	<link rel="shortcut icon" href="resources/favicon.ico" type="image/x-icon"/>
	
	<meta name="description" content="ET's Next Esports League: A powerful and easy to use esports league for Wolfenstein: Enemy Territory"/>
	<meta name="author" content="Antwan van Houdt" />
	<meta name="robots" content="all" />
	<meta name="revisit-after" content="1 day" />
	<meta name="keywords" content="Wolfenstein, Enemy, Territory, Esports, League, tournaments, official matches, Clanbase, ESL" />
	<!--[if lt IE 8]><link rel="stylesheet" href="styles/<?php echo $themeKey; ?>/css/ie.css" type="text/css" /><![endif]-->
	
	<script type="text/javascript" src="resources/javascript/jquery-1.6.2.min.js">
	</script>
	<script type="text/javascript" src="resources/javascript/jabwd.js">
	</script>
	
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25876013-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	
</head>
<body onload="<?php echo $loadExtra; ?>">

	<div id="preContentLoader">
	<img src="resources/button2_hover.png" alt=""/>
	</div>

	<!-- start header -->
	<div class="header-wrap">
		<div id="header">
			<div class="headerTop"></div>
			<div class="menubar">
				<a id="menu_index" class="menuItem 
				<?php 
				if( $_SERVER['SCRIPT_NAME'] == '/index.php' ) echo 'current'; 
				?>" href="">News</a>
				<a id="menu_rules" class="menuItem <?php if( $pageName == 'Rules' ) echo 'current'; ?>" href="rules/">Rules</a>
				<a id="menu_users" class="menuItem <?php if( $pageName == 'Users list' ) echo 'current'; ?>" href="users/">Users</a>
				<a id="menu_teams" class="menuItem <?php if( $pageName == 'Teams' ) echo 'current'; ?>" href="teams/">Teams</a>
				<a id="menu_ladders" class="menuItem <?php if( $pageName == 'Ladders' ) echo 'current'; ?>" href="ladders/">Ladders</a>
				<a id="menu_match" class="menuItem <?php if( $pageName == 'Matches' ) echo 'current'; ?>" href="match/">Matches</a>
				<a id="menu_tournament" class="menuItem <?php if( $_SERVER['SCRIPT_NAME'] == '/tournament.php' ) echo 'current'; ?>" href="tournament/">Tournaments</a>
	<?php
	if( ! $userSession )
	{
	?>
	<a id="menu_login" href="login/" class="menuItem">Login</a>
	<a id="menu_register" href="register/" class="menuItem">Register</a>
	<?php
	}
	else
	{
	?>
	<a id="menu_profile" href="profile/" class="menuItem <?php if( $pageName == 'Your profile' ) echo 'current'; ?>">Profile</a>
	<a id="menu_messages" href="messages/" class="menuItem <?php if( $pageName == 'Inbox' ) echo 'current'; ?>">Inbox<?php if( $messagesCount > 0) echo ' ('.$messagesCount.')'; ?></a>
	<?php
	}
	?>
			</div>
		</div>
	</div>
	<!-- end header -->

	<!-- start main -->
	<div id="main">