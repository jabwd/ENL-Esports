<h3>Welcome to ENL!</h3>

This is ENL, a newly created League for Wolfenstein: Enemy Territory.
<br />
The goal of this website is to make it easier for Wolfenstein ET players to schedule official matches.
Most sections on this website require you to have an account, please <a href="register/">register</a> or <a href="login/">login</a> before continuing.
<br />
<div class="seperator"></div>
<br />

<h3>News</h3>
<?php
if( $news )
{
	require_once 'class/BBParser.php';
	$parser = new BBParser();
	foreach($news as $newsItem)
	{
		echo '<div class="newsItem">
<div class="newsTitle">'.$newsItem['title'].'</div><div class="newsContent" id="newsItem'.$newsItem['itemID'].'">
'.$parser->parse($newsItem['content']).'
<br />
<span class="newsAnnotation">Written by '.$newsItem['author'].' on '.$newsItem['creationDate'].'</span>
</div>
</div>
<br />';
	}
}
?>
<br />
<div class="seperator"></div>
<br />
<h3>About ENL-Esports.com</h3>

ENL is a project found by Antwan 'Excite' van Houdt. It is a community focussed Wolfenstein: Enemy Territory League.
<br />
Its main goal is to provide a new way of playing official matches against each other, by both making it easy to access and with global rules everyone is used to.<br />
<br />
It all started back in May of 2011. 2 Wolfenstein ET players, abzes and Excite, were not happy with the current leagues available to the players. They decided to make their own. This project was called igl-esports. However IGL was targeted for more competitive games, rather than one which ENL currently is targeted too. Because of the project was too big to handle for 1 programmer, therefore the project soon died out. In August 2011 Excite decided to give IGL another go. To make it easier he renamed it and moved to supporting only 1 game, greatly reducing the amount of work needed to complete the basic website. The result is ENL!
<br />
<br />
Currently this project is under heavy development, however it is already possible to sign up and play ladder matches if you want to.