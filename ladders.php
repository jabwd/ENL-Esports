<?php
require 'config.php';

$pageName = 'Ladders';

if( $_POST['joinTeam'] )
{
	checkLogin();
	$ladderID 	= (int)$_GET['join'];
	$teamID 	= (int)$_POST['team'];
	
	$query = mysql_query("SELECT * FROM `ladders` WHERE `ladderID`=".$ladderID);
	$row = mysql_fetch_array($query);
	$minimum = (int)$row['ladderPlayers'];
	
	$query = mysql_query("SELECT * FROM `team_members` WHERE `teamID`=".$teamID);
	if( mysql_num_rows($query) < $minimum )
	{
		$errors[] = 'This team does not have enough players to play in this ladder';
	}
	
	$query = mysql_query("SELECT * FROM `ladder_teams` WHERE `teamID`=".$teamID);
	if( $query )
	{
		while($row = mysql_fetch_array($query))
		{
			if( $row['ladderID'] == $ladderID )
			{
				$errors[] = 'This team has already joined this ladder';
			}
		}
	}
	
	if( ! $errors )
	{
		if( mysql_query("INSERT INTO `ladder_teams` (`teamID`,`ladderID`) VALUES (".$teamID.",".$ladderID.")") )
		{
			$success = 'Successfully entered the team to the ladder';
		}
		else
		{
			$errors[] = 'A server error occured, please try again later';
		}
	}
}

$query = mysql_query("SELECT `ladderName`,`ladderID`,`ladderPlayers` FROM `ladders` ORDER BY `ladderName`");
if( $query )
{
	while($row=mysql_fetch_array($query) )
	{
		$ladders[] = $row;
	}
}

if( $_GET['join'] )
{
	checkLogin();
	
	
	// the ET 1on1 ladder is 4
	if( $_GET['join'] == 4 )
	{
		$query = mysql_query("SELECT `teamID` FROM `ladder_teams` WHERE `teamID`=".(int)$userSession->userID." AND `ladderID`=".(int)$_GET['join']);
		if( $query )
		{
			$row = mysql_fetch_array($query);
			if( !$row )
			{
				mysql_query("INSERT INTO `ladder_teams` (`teamID`,`ladderID`) VALUES (".(int)$userSession->userID.",".(int)$_GET['join'].")");
				header("location: ".$baseURL."viewLadder/?ladderID=".$_GET['join']);
				exit();
			}
			else
			{
				header("location: ".$baseURL."ladders/");
				exit();
			}
		}
	}
	
	$query = mysql_query("SELECT * FROM `team_members`,`teams` WHERE `team_members`.`userID`=".(int)$userSession->userID." AND `teams`.`teamID`=`team_members`.`teamID` AND `team_members`.`level`=".(int)$LevelAdmin);
	if( $query )
	{
		while($row=mysql_fetch_array($query))
		{
			$teams[] = $row;
		}
	}
}


if( !$_GET['ajax'] )
	require 'template/tp.head.php';
	
require 'template/tp.ladders.php';

if( !$_GET['ajax'] )
	require 'template/tp.foot.php';
?>