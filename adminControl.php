<?php
// this file is the main control  for admins. On several pages there can be 
// links that an admin has to press in order to do a certain action.
// This file will perform the various actions
require 'config.php';

$pageName = 'Admin section';
checkLogin($LevelAdmin);

$selectedUser = new User($_GET['userID']);

if( $_POST['banUser'] )
{
	$reason = secureInput($_POST['reason']);
	$userID = (int)$_POST['username'];
	die("This feature is not implemented just yet.");
	mysql_query("INSERT INTO `");
	
	$success = 'Banned user ID '.$userID;
	
	
	// make sure that the form is resetted
	unset($_POST);
}

if( $_POST['ipBan'] )
{
	$address = secureInput($_POST['IPAddress']);
	
	if( strlen($address) > 15 || strlen($address) < 1 )
	{
		$errors[] = 'The IP address you specified is not correct';
	}
	
	if( count(explode(".",$address)) != 4 )
	{
		$errors[] = 'The IP address you specified is not correct';
	}
	
	if( ! $errors )
	{
		$query = mysql_query("SELECT `IP` FROM `ip_ban` WHERE `IP`='".$address."'");
		if( mysql_num_rows($query) < 1 )
			mysql_query("INSERT INTO `ip_ban` (`IP`) VALUES ('".$address."')");
		else
			$errors[] = 'The IP you specified is already banned on ENL';
	}
}

if( $_GET['deleteIPBan'] )
{
	mysql_query("DELETE FROM `ip_ban` WHERE `entryID`=".(int)$_GET['deleteIPBan']);
}

$query = mysql_query("SELECT `entryID`,`IP` FROM `ip_ban`");
while($row = mysql_fetch_array($query))
{
	$ipBans[] = $row;
}

require 'template/tp.head.php';
if( $_GET['ban'] )
	require 'template/tp.adminBan.php';
else
	require 'template/tp.adminControl.php';
require 'template/tp.foot.php';
?>