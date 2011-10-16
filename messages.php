<?php
require 'config.php';

$pageName = 'Inbox';

checkLogin();

if( $_GET['delete'] )
{
	$query = mysql_query("SELECT `userID` FROM `messages` WHERE `messageID`=".(int)$_GET['delete']);
	if( $query )
	{
		$row = mysql_fetch_array($query);
		if( $row['userID'] == $userSession->userID )
		{
			mysql_query("DELETE FROM `messages` WHERE `messageID`=".(int)$_GET['delete']);
		}
	}
}

$query = mysql_query("SELECT `messages`.`read`,`messages`.`seen`,`messages`.`creationDate`,`messages`.`subject`,`messages`.`messageID`,`users`.`userID`,`users`.`username`,`users`.`nickname` FROM `messages`,`users` WHERE `messages`.`userID`=".(int)$userSession->userID." AND `users`.`userID`=`messages`.`fromID` ORDER BY `messages`.`creationDate` DESC");
if( $query )
{
	while($row= mysql_fetch_array($query))
	{
		$messages[] = $row;
	}
}

if( !$_GET['ajax'] )
require 'template/tp.head.php';
require 'template/tp.messages.php';
if( !$_GET['ajax'] )
require 'template/tp.foot.php';
?>