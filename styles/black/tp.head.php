<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<base href="<?php echo $baseURL; ?>"/>
	<title><?php echo $siteTitle; ?></title>
	<link type="text/css" rel="stylesheet" href="styles/<?php echo $themeKey ?>/css/all.css"/>
	<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="js/loopedslider.js"></script>
	<script type="text/javascript" src="js/looped-options.js"></script>
	<script type="text/javascript" src="js/slideBlock.js"></script>
	<script type="text/javascript" src="js/latte.js"></script>
	<script type="text/javascript" src="js/jquery.roundabout.min.js"></script>
	<script type="text/javascript" src="js/loader.js"></script>
	<script type="text/javascript" src="js/ddsmoothmenu.js"></script>
	<script type="text/javascript" src="js/cufon.js"></script>
	<script type="text/javascript" src="js/cufon-fonts.js"></script>
	<script type="text/javascript" src="js/cufon-settings.js"></script>
	<script type="text/javascript" src="js/input.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<!--[if lt IE 8]><link rel="stylesheet" href="css/ie.css" type="text/css" /><![endif]-->
</head>
<body>
	<!-- start header -->
	<div class="header-wrap">
		<div id="header">
			<div class="header-holder">
				<div class="wrap">
					<ul id="nav" class="ddsmoothmenu">
						<li <?php if( $page == $homePage ) { echo 'class="active"'; } ?>><a href="home/"><span><em>Home</em></span></a></li>
						<li <?php if( $page == $forums ) { echo 'class="active"'; } ?>><a href="forums/"><span><em>Forums</em></span></a></li>
						<li <?php if( $page == $downloadsPage ) { echo 'class="active"'; } ?>><a href="downloads/"><span><em>Downloads</em></span></a></li>
						<li <?php if( $page == $sponsorsPage ) { echo 'class="active"'; } ?>><a href="sponsors/"><span><em>Sponsors</em></span></a></li>
					</ul>
				</div>
				<div class="form-wrap">
					<form action="#" class="search-form">
						<fieldset>
							<span class="text-wrap"><input type="text" class="text" value="Start searching..." /></span>
							<input type="submit" class="submit" value="Start searching..." />
						</fieldset>
					</form>
					<form action="#" class="login-form">
						<fieldset>
							<input type="button" class="button sign-up" value="Sign up" />
							<input type="button" class="button sign-in" value="Sign in" />
							<span class="text-wrap"><input type="text" class="text" value="Password..." /></span>
							<span class="text-wrap"><input type="text" class="text" value="Login..." /></span>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- end header -->

	<!-- start main -->
	<div id="main">