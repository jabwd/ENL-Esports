<?php
require 'config.php';

$pageName = 'Team settings';

checkLogin();

if( ! $_GET['tID'] )
{
	header("location: ".$baseURL."teams/");
	exit();
}

// make sure that this is an admin of the desired team
// any other member is not allowed to view this page
// this is the only line of defense this script actually has,
// but it should be more than sufficient
$query = mysql_query("SELECT `level` FROM `team_members` WHERE `userID`=".(int)$userSession->userID." AND `teamID`=".(int)$_GET['tID']);
$row = mysql_fetch_array($query);
if( $row['level'] < $LevelAdmin )
{
	header("location: ".$baseURL."viewTeam/?tID=".(int)$_GET['tID']);
	exit();
}

$query 	= mysql_query("SELECT `homepage`,`ircChannel`,`tag`,`teamName` FROM `teams` WHERE `teamID`=".(int)$_GET['tID']);
$team 	= mysql_fetch_array($query);

$query = mysql_query("SELECT `ladder_teams`.`inactive`,`ladders`.`ladderName`,`ladders`.`ladderID`,`ladder_teams`.`teamID` FROM `ladders`,`ladder_teams` WHERE `ladder_teams`.`teamID`=".(int)$_GET['tID']." AND `ladders`.`ladderID`=`ladder_teams`.`ladderID`");
while($row = mysql_fetch_array($query))
{
	$ladders[] = $row;
}

if( $_POST['toggleLadderActivity'] )
{
	$ladderID 	= (int)$_POST['ladderSelect'];
	$teamID		= (int)$_GET['tID'];
	
	if( $teamID != 0 && $ladderID != 0 )
	{
		foreach($ladders as $ladder)
		{
			if( $ladder['ladderID'] == $ladderID )
			{
				if( $ladder['inactive'] == 0 )
				{
					$ladder['inactive'] = 1;
					$status = 'inactive';
				}
				else
				{
					$status = 'active';
					$ladder['inactive'] = 0;
				}
				mysql_query("UPDATE `ladder_teams` SET `inactive`=".($ladder['inactive'])." WHERE `ladderID`=".$ladderID." AND `teamID`=".$teamID);
				$success = 'Successfully went '.$status.' on the '.$ladder['ladderName'];
				break;
			}
		}
	}
}


if( $_POST['save'] )
{
	$teamName 	= secureInput($_POST['teamName']);
	$teamTag 	= mysql_real_escape_string(strip_tags($_POST['teamTag']));
	$teamIRC 	= secureInput($_POST['teamIRC']);
	$homepage 	= secureInput($_POST['homepage']);
	
	if( strlen($teamName) > 60 )
		$errors[] = 'The team name you specified is too long';
	if( strlen($teamName) < 1 )
		$errors[] = 'The field team name is required!';
	if( strlen($teamTag) > 9 )
		$errors[] = 'The tag you specified is too long.';
	if( strlen($teamIRC) > 20 )
		$errors[] = 'The IRC channel you specified ';
	if( strlen($homepage) > 100 )
		$errors[] = 'The homepage you specified is too long.';
		
	if( $teamName != $team['teamName'] )
	{
		// update the team name
		$query = mysql_query("SELECT `teamName` FROM `teams`");
		while($row = mysql_fetch_array($querry))
		{
			if( strcasecmp($teamName,$row['teamName']) == 0 )
			{
				$errors[] = 'The team name you picked is already in use, try another one ( case insensitive! )';
			}
		}
		
		if( strlen($_POST['password']) > 0 && ! $errors )
		{
			$password = passwordHash($_POST['password']);
			
			mysql_query("UPDATE `teams` SET `ircChannel`='".$teamIRC."',`tag`='".$teamTag."',`joinPassword`='".$password."',`teamName`='".$teamName."' WHERE `teamID`=".(int)$_GET['tID']);
			$success = 'The settings have been saved';
			$team['tag'] = $teamTag;
			$team['teamName'] = $teamName;
			$team['ircChannel'] = $teamIRC;
		}
		else if( ! $errors )
		{
			mysql_query("UPDATE `teams` SET `homepage`='".$homepage."',`ircChannel`='".$teamIRC."',`tag`='".$teamTag."',`teamName`='".$teamName."' WHERE `teamID`=".(int)$_GET['tID']);
			$success = 'The settings have been saved';
			$team['tag'] = $teamTag;
			$team['teamName'] = $teamName;
			$team['ircChannel'] = $teamIRC;
		}
	}
	else if( strlen($_POST['password']) > 0 )
	{
		$password = passwordHash($_POST['password']);
		
		mysql_query("UPDATE `teams` SET `homepage`='".$homepage."',`ircChannel`='".$teamIRC."',`tag`='".$teamTag."',`joinPassword`='".$password."' WHERE `teamID`=".(int)$_GET['tID']);
		$success = 'The settings have been saved';
	}
	else if( $team['tag'] != $teamTag && ! $errors )
	{
		mysql_query("UPDATE `teams` SET `homepage`='".$homepage."',`ircChannel`='".$teamIRC."',`tag`='".$teamTag."' WHERE `teamID`=".(int)$_GET['tID']);
		$success = 'The settings have been saved';
		$team['tag'] = $teamTag;
		$team['ircChannel'] = $teamIRC;
	}
	else if( $team['ircChannel'] != $teamIRC && ! $errors )
	{
		mysql_query("UPDATE `teams` SET `homepage`='".$homepage."',`ircChannel`='".$teamIRC."' WHERE `teamID`=".(int)$_GET['tID']);
		$success = 'The new IRC channel was saved';
		$team['ircChannel'] = $teamIRC;
	}
	else if( $team['homepage'] != $homepage && ! $errors )
	{
		mysql_query("UPDATE `teams` SET `homepage`='".$homepage."' WHERE `teamID`=".(int)$_GET['tID']);
		$success = 'The new homepage was saved';
		$team['homepage'] = $homepage;
	}
}

if( $_POST['deleteTeamConfirm'] )
{
	// only delete the team from visible existence, the rest should be maintained
	mysql_query("DELETE FROM `ladder_teams` WHERE `teamID`=".(int)$_GET['tID']);
	mysql_query("DELETE FROM `teams` WHERE `teamID`=".(int)$_GET['tID']);
	
	
	// action 1 created
	// action 2 deleted
	mysql_query("INSERT INTO `teams_log` (`teamID`,`action`,`userID`) VALUES (".(int)$_GET['tID'].",2,".(int)$userSession->userID.")");
	header("location: ".$baseURL."teams/");
	exit();
}

// why query the server 2 times?? Find out why I wrote this..
/*$query = mysql_query("SELECT `teamName` FROM `teams` WHERE `teamID`=".(int)$_GET['tID']);
$team = mysql_fetch_array($query);*/

require 'template/tp.head.php';
require 'template/tp.teamSettings.php';
require 'template/tp.foot.php';
?>