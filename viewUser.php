<?php
require 'config.php';

if( ! $_GET['userID'] )
{
	header("location: ".$baseURL);
	exit();
}



$selectedUser = new User($_GET['userID']);
$loadExtra = 'loadTZACID('.$selectedUser->slacID.',\'tzacStatus\');';
if( $selectedUser->userID > 0 && $userSession->userID != $selectedUser->userID )
	$selectedUser->incrementProfileVisits();
	
$pageName = $selectedUser->displayName().'\'s profile';

$query = mysql_query("SELECT `teams`.`teamID`,`teamName` FROM `teams`,`team_members` WHERE `team_members`.`userID`=".(int)$_GET['userID']." AND `teams`.`teamID`=`team_members`.`teamID`");
if( $query )
{
	while($row = mysql_fetch_array($query))
	{
		$teams[] = $row;
	}
}

if( $userSession->adminLevel == $LevelAdmin )
{
	//ENLog("Admin ".$userSession->displayName."[".$userSession->userID."] is reading ".$_GET['userID']."'s profile");
	
	$query = mysql_query("SELECT * FROM `users_ip` WHERE `userID`=".(int)$_GET['userID']." ORDER BY `date` DESC LIMIT 0,10");
	while($row=mysql_fetch_array($query))
	{
		$ipAddresses[] = $row;
	}
}

if( !$_GET['ajax'] )
require 'template/tp.head.php';
require 'template/tp.viewUser.php';
if( !$_GET['ajax'] )
require 'template/tp.foot.php';
?>