<?php
require 'config.php';

$pageName = 'Users list';

if( ! $_GET['page'] )
	$cPage = 0;
else
	$cPage = (((int)$_GET['page'])-1);
if( $cPage < 0 )
	$cPage = 0;
$cPageCount = 25;
if( $_POST['search'] || $_GET['filter'] )
{
	$cPage = 0;
	$cPageCount = 10000;
}

$query = mysql_query("SELECT `users`.`cless`,`bans`.`expirationDate`,`users`.`country`,`users`.`skillLevel`,`users`.`userID`,`users`.`username`,`users`.`nickname`,`users`.`preferredClass`,`users`.`preferredFormat` FROM `users` LEFT JOIN `bans` ON `users`.`userID`=`bans`.`userID` ORDER BY `users`.`userID` ASC LIMIT ".($cPage*25).",".$cPageCount."");
$searchQuery = strtolower(secureInput($_POST['userName']));

if( ! $_POST['search'] && !$_GET['filter'] )
{
	$numberQuery = mysql_query("SELECT `userID` FROM `users`");
	$pagesCount = ceil(mysql_num_rows($numberQuery)/25);
}
//$pagesCount++;
//$pagesCount = mysql_num_rows($numberQuery);
//$pagesCount = 0;
while($row = mysql_fetch_array($query))
{
	// skip system users
	if( $row['userID'] < 0 ) continue;
	
	if( strtotime($row['expirationDate']) > time() )
		{
			$row['suspended'] = true;
		}
		else
			$row['suspended'] = false;
	
	if( $_GET['filter'] )
	{
		if( $_GET['skill'] > $row['skillLevel'] )
			continue;
		
		if( $_GET['clessOnly'] && ! $row['cless'] )
			continue;
			
		if( $_GET['cheatersOnly'] &&! $row['suspended'] )
			continue;
	}
	if( $_POST['search'] )
	{
		//ENLog("User: ".strtolower($row['username'].$row['nickname'].$row['userID'].$row['slacID'])." Query: ".$searchQuery);
		if( strpos(strtolower(" ".$row['username'].$row['nickname'].$row['userID'].$row['slacID']),$searchQuery) > 0 )
		{
			$users[] = $row;
		}
		else
			continue;
	}
	else
		$users[] = $row;
}


if( !$_GET['ajax'] )
require 'template/tp.head.php';
require 'template/tp.users.php';
if( !$_GET['ajax'] )
require 'template/tp.foot.php';
?>