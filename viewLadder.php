<?php
require 'config.php';

$pageName = 'Ladders';

if( ! $_GET['ladderID'] )
{
	header("location: ".$baseURL);
	exit();
}

$query = mysql_query("SELECT * FROM `ladders` WHERE `ladderID`=".(int)$_GET['ladderID']);
if( $query )
{
	$row = mysql_fetch_array($query);
	$ladder = $row;
}

if( ! $ladder )
{
	header("location: ".$baseURL);
	exit();
}

if( $_GET['ladderID'] != 4 )
{
	$query = mysql_query("SELECT `ladder_teams`.`inactive`,`ladder_teams`.`points`,`ladder_teams`.`losses`,`ladder_teams`.`wins`,`teams`.`teamName`,`teams`.`teamID` FROM `ladder_teams`,`teams` WHERE `ladder_teams`.`inactive`=0 AND `ladder_teams`.`ladderID`=".(int)$_GET['ladderID']." AND `teams`.`teamID`=`ladder_teams`.`teamID` ORDER BY `ladder_teams`.`points` DESC");
	if( $query )
	{
		while($row = mysql_fetch_array($query))
		{
			$teams[] = $row;
		}
	}
}
else
{
	$query = mysql_query("SELECT `ladder_teams`.`inactive`,`ladder_teams`.`points`,`users`.`username`,`users`.`nickname`,`users`.`userID` FROM `users`,`ladder_teams` WHERE `ladder_teams`.`ladderID`=".(int)$_GET['ladderID']." AND `users`.`userID`=`ladder_teams`.`teamID` ORDER BY `ladder_teams`.`points` DESC");
	if( $query )
	{
		while($row = mysql_fetch_array($query))
		{
			$users[] = $row;
		}
	}
}

if( !$_GET['ajax'] )
require 'template/tp.head.php';
require 'template/tp.viewLadder.php';
if( !$_GET['ajax'] )
require 'template/tp.foot.php';
?>