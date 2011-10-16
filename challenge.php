<?php
require 'config.php';

$pageName = 'Challenge';

checkLogin();

if( $_POST['challenge'] )
{
	$ladderID 	= (int)$_POST['ladderID'];
	$teamID 	= (int)$_POST['yourTeam'];
	$oppoID 	= (int)$_POST['opponentID'];
	
	if( $teamID == $oppoID )
	{
		$errors[] = 'You can not challenge your own team';
	}
	
	$query = mysql_query("SELECT * FROM `team_members` WHERE `userID`=".(int)$userSession->userID." AND `teamID`=".(int)$teamID);
	if( $query )
	{
		$row = mysql_fetch_array($query);
		if( $row['level'] != $LevelAdmin )
		{
			$errors[] = 'You do not have enough permissions to enter your team in any matches';
		}
	}
	else
	{
		$errors[] = 'A server error occured, please try again later';
	}
	
	$found1 = false;
	$found2 = false;
	$query = mysql_query("SELECT * FROM `ladder_teams` WHERE `ladderID`=".$ladderID);
	if( $query )
	{
		while($row=mysql_fetch_array($query))
		{
			if( $row['teamID'] == $teamID )
			{
				$found1 = true;
			}
			if( $row['teamID'] == $oppoID )
			{
				$found2 = true;
			}
		}
	}
	else
	{
		$errors[] = 'A server error occured, please try again later';
	}
	
	if( ! $found1 )
	{
		$errors[] = 'Your team is not in that ladder';
	}
	
	if( ! $found2 )
	{
		$errors[] = 'Your opponent is not in that ladder';
	}
	
	if( ! $errors )
	{
		$query = mysql_query("SELECT * FROM `teams` WHERE `teamID`=".(int)$oppoID);
		if( $query )
		{
			$team = mysql_fetch_array($query);
		}
		$query = mysql_query("SELECT * FROM `teams` WHERE `teamID`=".(int)$teamID);
		if( $query )
		{
			$otherTeam = mysql_fetch_array($query);
		}
		if( mysql_query("INSERT INTO `challenges` (`challengerID`,`opponentID`,`ladderID`) VALUES (".$teamID.",".$oppoID.",".$ladderID.")") )
		{
			$query = mysql_query("SELECT * FROM `ladders`,`challenges` WHERE `challenges`.`challengeID`=".mysql_insert_id()." AND `ladders`.`ladderID`=`challenges`.`ladderID`");
			$challenge = mysql_fetch_array($query);
			// now select all the LEADERS of the team and send them a message
			$query = mysql_query("SELECT * FROM `team_members` WHERE `teamID`=".$oppoID);
			if( $query )
			{
				while($row= mysql_fetch_array($query))
				{
					// we also need the challengeID
					if( $row['level'] == $LevelAdmin )
					{
						if( ! mysql_query("INSERT INTO `messages` (`fromID`,`userID`,`subject`,`content`) VALUES (-1,'".(int)$row['userID']."','".$team['teamName']." got challenged','Your team ".$team['teamName']." got challenged by ".$otherTeam['teamName'].".<br />You need to either <a href=\"".$baseURL.'match/?challenge='.(int)$challenge['challengeID'].'&accept=true'."\">Accept</a> or <a href=\"".$baseURL.'match/?challenge='.(int)$challenge['challengeID']."\">Decline</a> the challenge. It is for the ".$challenge['ladderName'].".')") )
						{
							$errors[] = 'A server error occured, please try again later'.mysql_error();
						}
					}
				}
			}
			$success = 'You successfuly challenged the team, you can play as soon as they accept your challenge.';
		}
		else
		{
			$errors[] = 'A server error occured, please try again later';
		}
	}
}

$query = mysql_query("SELECT `teamName` FROM `teams` WHERE `teamID`=".(int)$_GET['tID']);
$opponentTeam = mysql_fetch_array($query);

if( ! $opponentTeam )
{
	header("location: ".$baseURL."ladders/");
	exit();
}

$query = mysql_query("SELECT `teams`.`teamID`,`teams`.`teamName` FROM `teams`,`team_members` WHERE `team_members`.`userID`=".(int)$userSession->userID." AND `teams`.`teamID`=`team_members`.`teamID` AND `team_members`.`level`=".(int)$LevelAdmin);
if( $query )
{
	while($row = mysql_fetch_array($query))
	{
		$yourTeams[] = $row;
	}
}

require 'template/tp.head.php';
require 'template/tp.challenge.php';
require 'template/tp.foot.php';
?>