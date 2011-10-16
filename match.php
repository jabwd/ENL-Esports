<?php
require 'config.php';
require_once 'class/MessageController.php';

$pageName = 'Matches';

checkLogin();

if( $_POST['challenge'] )
{
	if( !$userSession->canPlay() )
	{
		$errors[] = 'Your ENL account has been suspended and you are not allowed to play any official matches at this time.';
	}
	
	$ladderID 	= (int)$_POST['ladder'];
	$teamID 	= (int)$_POST['team1'];
	$oppoID 	= (int)$_POST['team2'];
	$matchDate 	= (int)strtotime($_POST['matchDate']);
	$map1		= secureInput($_POST['map1']);
	$map2 		= secureInput($_POST['map2']);
	
	if( $matchDate < time() )
	{
		$errors[] = 'The date you entered is not correct, try something else';
	}
	
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
				if( $row['inactive'] == 1 )
				{
					$found2 = true;
					$found1 = true;
					$errors[] = 'That team is not currently active in this ladder';
				}
			}
			if( $row['teamID'] == $oppoID )
			{
				$found2 = true;
				if( $row['inactive'] == 1 )
				{
					$found2 = true;
					$found1 = true;
					$errors[] = 'That team is not currently active in this ladder';
				}
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
		/*if( mysql_query("INSERT INTO `challenges` (`challengerID`,`opponentID`,`ladderID`) VALUES (".$teamID.",".$oppoID.",".$ladderID.")") )
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
		}*/
		
		mysql_query("INSERT INTO `matches` (`map`,`map2`,`challengerID`,`opponentID`,`ladderID`,`dueDate`) VALUES ('".$map1."','".$map2."',".$teamID.",".$oppoID.",".$ladderID.",'".date("Y:m:d h:i:s",$matchDate)."')");
		
		$query = mysql_query("SELECT * FROM `ladders`,`matches` WHERE `matches`.`matchID`=".mysql_insert_id()." AND `ladders`.`ladderID`=`matches`.`ladderID`");
			$challenge = mysql_fetch_array($query);
		$query = mysql_query("SELECT * FROM `team_members` WHERE `teamID`=".$oppoID);
				while($row= mysql_fetch_array($query))
				{
					// we also need the challengeID
					if( $row['level'] == $LevelAdmin )
					{
						if( ! mysql_query("INSERT INTO `messages` (`fromID`,`userID`,`subject`,`content`) VALUES (-1,'".(int)$row['userID']."','".$team['teamName']." got challenged','Your team ".$team['teamName']." got challenged by ".$otherTeam['teamName'].".<br />Date: ".secureInput($_POST['matchDate'])."<br />Ladder: ".$challenge['ladderName'].".<br />Match link: <a href=\"warInfo/?matchID=".$challenge['matchID']."\">here</a>')") )
						{
							$errors[] = 'A server error occured, please try again later'.mysql_error();
						}
					}
				}
				$success = 'You sucessfully challenged the team, you can play whenever you want, but make sure it is before the date you have set!';
	}
}

	// notice, I find this code extremely ugly, I want to clean this up but getting it to work
	// at the moment has the highest priortiy
	$query = mysql_query("SELECT * FROM `teams`,`team_members` WHERE `team_members`.`userID`=".(int)$userSession->userID." AND `teams`.`teamID`=`team_members`.`teamID` AND `team_members`.`level`=".(int)$LevelAdmin);
	if( $query )
	{
		while($row = mysql_fetch_array($query))
		{
			$yourTeams[] = $row;
			$secondQuery = mysql_query("SELECT * FROM `matches` WHERE `matches`.`challengerID`=".(int)$row['teamID']." OR `matches`.`opponentID`=".(int)$row['teamID']);
			while($secondRow=mysql_fetch_array($secondQuery))
			{
				if( $secondRow['opponentAccepted'] && $secondRow['challengerAccepted'] )
					continue;
				$useFulID = $secondRow['challengerID'];
				if( $useFulID == $row['teamID'] )
				{
					$useFulID = $secondRow['opponentID'];
				}
				$lolQuery = mysql_query("SELECT * FROM `teams` WHERE `teamID`=".(int)$useFulID);
				$lolRow = mysql_fetch_array($lolQuery);
				$secondRow['opponentName'] = $lolRow['teamName'];
				$secondRow['yourName'] 	= $row['teamName'];
				$secondRow['yourTeamID'] = $row['teamID'];
				$yourMatches[] 			= $secondRow;
			}
		}
	}

	$query = mysql_query("SELECT * FROM `teams`");
	if( $query )
	{
		while($row = mysql_fetch_array($query))
		{
			$teams[] = $row;
		}
	}

	$query = mysql_query("SELECT * FROM `ladders` ORDER BY `ladderName`");
	if( $query )
	{
		while($row = mysql_fetch_array($query))
		{
			$ladders[] = $row;
		}
	}

if( $_GET['challenge'] )
{
	$matchID = (int)$_GET['challenge'];
		$query 		= mysql_query("SELECT * FROM `challenges` WHERE `challengeID`=".$matchID);
		$challenge	= mysql_fetch_array($query);
	
		$query 	= mysql_query("SELECT * FROM `team_members` WHERE `userID`=".(int)$userSession->userID);
		while($row = mysql_fetch_array($query))
		{
			if( $row['level'] >= $LevelAdmin )
			{
				if( $row['teamID'] == $challenge['opponentID'] )
				{
					// you are authorized
					if( $_GET['accept'] )
					{
						mysql_query("INSERT INTO `matches` (`challengerID`,`opponentID`,`ladderID`) VALUES ('".(int)$challenge['challengerID']."','".(int)$challenge['opponentID']."','".(int)$challenge['ladderID']."')");
					
						$success = 'You successfully accepted the challenge.';
					}
					else
					{
						$success = 'You declined the challenge!';
					}
					mysql_query("DELETE FROM `challenges` WHERE `challengeID`=".$matchID);
					
					// last: message the other opponent's captain that you have accepted the challenge
					if( $row['teamID'] == $challenge['challengerID'] )
					{
						$userQuery = mysql_query("SELECT * FROM `team_members`,`users` WHERE `team_members`.`teamID`=".$challenge['opponentID']." AND `users`.`userID`=`team_members`.`userID`");
					}
					else
					{
						$userQuery = mysql_query("SELECT * FROM `team_members`,`users` WHERE `team_members`.`teamID`=".$challenge['challengerID']." AND `users`.`userID`=`team_members`.`userID`");
					}
					
					$userRow = mysql_fetch_array($userQuery);
					if( strlen($userRow['username']) > 0 )
					{
						if( $_GET['accept'] )
						{
							$teamObject = new Team($row['teamID']);
							if( ! $teamObject ) die("An odd error occured, your challenge was accepted though..");
							$subject = 'Challenge accepted!';
							$content = $teamObject->teamName.' has accepted your challenge.';
						}
						else
						{
							$teamObject = new Team($row['teamID']);
							$subject = 'Challenge declined..';
							$content = $teamObject->teamName.' has declined your challenge.';
						}
						MessageController::sendPrivateMessage($userRow['username'],$subject,$content);
					}
					else
						ENLog("A match was accepted but were unable to send a message to team captain: team captain does not exist or server returned an unexpected error");
						
					break;
				}
			}
	}
}


if( !$_GET['ajax'] )
	require 'template/tp.head.php';
require 'template/tp.match.php';
if( !$_GET['ajax'] )
	require 'template/tp.foot.php';
?>