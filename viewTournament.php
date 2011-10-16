<?php
require 'config.php';
require_once 'class/TournamentController.php';
require_once 'class/TournamentBracket.php';
require_once 'class/TeamController.php';
require_once 'class/Brackets.php';
require_once 'class/MessageController.php';
require_once 'class/BBParser.php';

$tournament = new Tournament($_GET['tID']);
$tourBracket = new TournamentBracket($tournament->tournamentID);

if( $_POST['enterWinningTeam'] && $tournament->winnerID == 0 )
{
	checkLogin($LevelAdmin);
	$teamID = (int)$_POST['winningTeam'];
	
	$tournament->setWinningTeam($teamID);
}

if( $_POST['updateRules'] )
{
	checkLogin($LevelAdmin);
	$newRules = secureInput($_POST['rules']);
	mysql_query("UPDATE `tournaments` SET `rules`='".$newRules."' WHERE `tournamentID`=".(int)$_GET['tID']);
	$success = 'The rules have been updated';
	header("location: ".$baseURL."viewTournament/?tID=".$_GET['tID']."&rules=true");
}


// filling in the score of the tournament
if( $_POST['enterScore'] && $tournament->winnerID == 0 )
{
	$team1Score = (int)$_POST['team1Score'];
	$team2Score = (int)$_POST['team2Score'];
	$matchCount = secureInput($_POST['matchCount']);
	$tempBuff = explode(";",$matchCount);
	if( count($tempBuff) != 3 )
		$errors[] = 'Something just went wrong, please try again by refreshing this page.';
	
	$stageNumber 	= (int)$tempBuff[0];
	$team1ID 		= (int)$tempBuff[1];
	$team2ID 		= (int)$tempBuff[2];
	
	if( $team1ID == 0 || $team2ID == 0 )
		$errors[] = 'Something just went wrong';
	
	if( $stageNumber < 1 )
		$errors[] = 'Incorrect stage number';
		
	if( $team1Score > $team2Score )
	{
		$winnerID = $team1ID;
	}
	else if( $team1Score < $team2Score )
	{
		$winnerID = $team2ID;
	}
	else
		$errors[] = 'A draw is not possible in a tournament!';
		
	if( ! $erorrs )
	{
		// no errors have occured so just insert the match into the database
		mysql_query("INSERT INTO `tournament_matches` (`tournamentID`,`stageID`,`team1ID`,`team2ID`,`winnerID`,`team1Score`,`team2Score`) VALUES (".$tournament->tournamentID.",".$stageNumber.",".$team1ID.",".$team2ID.",".$winnerID.",".$team1Score.",".$team2Score.")");
		$success = 'Successfully entered the score.';
	}
}


// finishing the signup of a team
if( $_POST['signup'] && $tournament->winnerID == 0 )
{	
	$query = mysql_query("SELECT * FROM `tournament_teams` WHERE `tournamentID`=".(int)$_GET['signup']);
	$teamsCount = mysql_num_rows($query);
	if( $teamsCount >= 8 )
	{
		$errors[] = 'Too many teams have already signed up';
	}
	else
	{
		$query = mysql_query("SELECT * FROM `team_members` WHERE `teamID`=".(int)$_POST['teamID']);
		if( mysql_num_rows($query) >= $tournament->minPlayers )
		{
			$teamController = new TeamController();
			$value = $teamController->addToTournament($_POST['teamID'],$_GET['signup']);
			if( is_array($value) )
			{
				$errors[] = $value;
			}
			else
			{
				$success = $value;
				header("location: ".$baseURL."viewTournament/?tID=".$_GET['signup']."&teams=true");
				exit();
			}
		}
		else
			$errors[] = 'Not enough players to join';
	}
}

if( $_GET['toggleSignups'] && $tournament->winnerID == 0 )
{
	if( !$tournament->allowSignups )
	{
		$tournament->toggleSignup();
	}
	else
	{
		$teams = $tournament->teams();
	
		$count = count($teams);
		while(!($count%2))
		{
			$count /= 2;
		}
	
		if( $count == 1 )
		{
			$tournament->toggleSignup();
			$toggleResult = 'The signups are now closed.';
		}
		else
		{
			$toggleResult = 'Not enough ( or too many ) teams to start the tournament.';
		}
	}
}

if( $_GET['signup'] )
{
	$teamController = new TeamController();
	$yourTeams = $teamController->yourTeams($LevelAdmin);
}

if( $_GET['signoff'] )
{
	if( $userSession->adminLevel == $LevelAdmin )
	{
		if( $tournament->allowSignups )
		{
			mysql_query("DELETE FROM `tournament_teams` WHERE `teamID`=".(int)$_GET['signoff']." AND `tournamentID`=".$tournament->tournamentID);
			$adminQuery = mysql_query("SELECT * FROM `team_members` WHERE `teamID`=".(int)$_GET['signoff']);
			$team = new Team($_GET['signoff']);
			while($adminRow = mysql_fetch_array($adminQuery))
			{
				if( $adminRow['level'] == $LevelAdmin )
				{
					$message = 'Your team '.$team->teamName.' has been removed from the '.$tournament->tournamentName.' tournament by '.$userSession->displayName().'.<br />This usually happens when you have done something that is not allowed. If you would like to appeal this you can always contact an admin in our IRC channel on quakenet: #enl.et<br />If you do not want to use IRC, you can contact an admin through email or through xfire.<br />Email: admin@enl-esports.com<br />Xfire: jabwd<br /><br />';
					MessageController::sendPrivateMessage($adminRow['userID'],"Kicked from tournament",$message);
				}
			}
		}
	}
	else
	{
		$yourteams = $teamController->yourteams($LevelAdmin);
		$found = false;
		foreach($yourTeams as $yourTeam)
		{
			if( $yourTeam->teamID == $_GET['signoff'] )
			{
				$found = true;
				break;
			}
		}
		if( $tournament->allowSignups && $found )
		{
			mysql_query("DELETE FROM `tournament_teams` WHERE `teamID`=".(int)$_GET['signoff']." AND `tournamentID`=".$tournament->tournamentID);
		}
	}
	header("location: ".$baseURL."viewTournament/?tID=".$_GET['tID']."&teams=true");
	echo 'Done';
	exit();
}


if( strlen($tournament->tournamentName) < 1 )
{
	header("location: ".$baseURL."tournament/");
	echo 'You should have been redirected now';
	exit();
}

if( $tournament && $tourBracket )
	$pageName = $tournament->tournamentName;
else
	$pageName = 'Not found';
	
if( $_GET['teams'] )
{
	$query = mysql_query("SELECT * FROM `team_members` WHERE `userID`=".(int)$userSession->userID);
	while($row = mysql_fetch_array($query))
	{
		if( $row['level'] == $LevelAdmin )
		{
			$adminTeams[] = $row;
		}
	}
}

require 'template/tp.head.php';
require 'template/tp.viewTournament.php';
require 'template/tp.foot.php';
?>