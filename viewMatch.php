<?php
require 'config.php';
require_once 'class/Match.php';

$pageName = 'Matches';

checkLogin();

if( ! $_GET['mID'] && ! $_GET['tID'] )
{
	header("location: ".$baseURL);
	exit();
}

$query = mysql_query("SELECT * FROm `team_members` WHERE `userID`=".(int)$userSession->userID." AND `teamID`=".(int)$_GET['tID']);
$teamMember = mysql_fetch_array($query);

if( $_GET['accept'] )
{
	$query = mysql_query("SELECT * FROM `team_members` WHERE `userID`=".(int)$userSession->userID);
	$found = false;
	if( $query )
	{
		while($row = mysql_fetch_array($query))
		{
			if( $row['teamID'] == $_GET['tID'] && $row['level'] == $LevelAdmin )
			{
				$found = true;
				break;
			}
		}
	}
	
	if( $userSession->adminLevel == $LevelAdmin )
		$found = true;
	
	if( $found )
	{
		$query = mysql_query("SELECT * FROM `matches` WHERE `matchID`=".(int)$_GET['mID']);
		if( $query )
		{
			$row = mysql_fetch_array($query);
			if( $row['challengerAccepted'] == 1 && $row['opponentAccepted'] == 1)
			{
				header("location: ".$baseURL);
				exit();
			}
		}
		else
		{
			exit();
		}
		
		
		if( $_GET['lal'] )
			$extra = 'challengerAccepted';
		else
			$extra = 'opponentAccepted';
		if( mysql_query("UPDATE `matches` SET `".$extra."`=1 WHERE `matchID`=".(int)$_GET['mID']) )
		{
			$success = 'Successfully accepted the score!';
			
			// now check the databes to see how much points the team should win
			$query = mysql_query("SELECT * FROM `matches` WHERE `matchID`=".(int)$_GET['mID']);
			if( $query )
			{
				$row = mysql_fetch_array($query);
				$score1 = (int)$row['opponentScore'];
				$score2 = (int)$row['challengerScore'];
				if( $score1 > $score2 )
				{
					$bedrag = 0;
					$query = mysql_query("SELECT * FROM `ladder_teams` WHERE `teamID`=".$row['opponentID']." AND `ladderID`=".$row['ladderID']);
					if( $query )
					{
						$oppoQuery = mysql_query("SELECT `wins`,`losses`,`points` FROM `ladder_teams` WHERE `teamID`=".$row['challengerID']." AND `ladderID`=".$row['ladderID']);
						$thirdRow = mysql_fetch_array($oppoQuery);
						$oppoPoints = $thirdRow['points'];
						$secondRow = mysql_fetch_array($query);
						$currentPoints = $secondRow['points'];
						$ourLosses = $secondRow['losses'];
						$theirWIns = $thirdRow['wins'];
						$ourLosses++;
						$theirWins++;
						// +10
						
						$bedrag = 50;
						
						if( $oppoPoints < $currentPoints )
						{
							$diff = $currentPoints - $oppoPoints; // negative or positive, depending on the diff
							$amount = pow(10, ($diff/1500));
							$amount = 1 / $amount; // to fix php gayness
							$bedrag *= $amount;
						}
						else if( $currentPoints < $oppoPoints )
						{
							$diff = $oppoPoints - $currentPoints; // negative or positive, depending on the diff
							$amount = pow(10, ($diff/1500));
							$amount = 1 / $amount; // to fix php gayness
							$amount = 1 + ( 1 - $amount );
							$bedrag *= $amount;
						}
						
						$currentPoints += $bedrag;
						
						mysql_query("UPDATE `ladder_teams` SET `losses`=".$ourLosses.",`points`=".$currentPoints." WHERE `entryID`=".$secondRow['entryID']);
						mysql_query("UPDATE `matches` SET `winnerID`=".$secondRow['teamID'].",`pointsEarned`=".$bedrag." WHERE `matchID`=".(int)$_GET['mID']);
					}
					
					// now update the score of the loser
					$query = mysql_query("SELECT * FROM `ladder_teams` WHERE `teamID`=".$row['challengerID']." AND `ladderID`=".$row['ladderID']);
					if( $query )
					{
						$secondRow = mysql_fetch_array($query);
						$currentPoints = $secondRow['points'];
						$currentPoints -= $bedrag;
						if( $currentPoints < 0 )
							$currentPoints = 0;
						mysql_query("UPDATE `ladder_teams` SET `wins`=".$theirWins.",`points`=".$currentPoints." WHERE `entryID`=".$secondRow['entryID']);
					}
				}
				else if( $score2 > $score1 )
				{
					// oppoPoints is the loser here
					$bedrag = 0;
					$query = mysql_query("SELECT * FROM `ladder_teams` WHERE `teamID`=".$row['challengerID']." AND `ladderID`=".$row['ladderID']);
					if( $query )
					{
						$oppoQuery = mysql_query("SELECT `wins`,`losses`,`points` FROM `ladder_teams` WHERE `teamID`=".$row['opponentID']." AND `ladderID`=".$row['ladderID']);
						$thirdRow = mysql_fetch_array($oppoQuery);
						$oppoPoints = $thirdRow['points'];
						$secondRow = mysql_fetch_array($query);
						$currentPoints = $secondRow['points'];
						$wins = $secondRow['wins'];
						$losses = $thirdRow['losses'];
						$losses++;
						$wins++;
						
						$bedrag = 50;
						
						if( $oppoPoints < $currentPoints )
						{
							$diff = $currentPoints - $oppoPoints; // negative or positive, depending on the diff
							$amount = pow(10, ($diff/1500));
							$amount = 1 / $amount; // to fix php gayness
							$bedrag *= $amount;
						}
						else if( $currentPoints < $oppoPoints )
						{
							$diff = $oppoPoints - $currentPoints; // negative or positive, depending on the diff
							$amount = pow(10, ($diff/1500));
							$amount = 1 / $amount; // to fix php gayness
							$amount += 1; // we have less points so we need to earn more!
							$bedrag *= $amount;
						}	
						
						$currentPoints += $bedrag;
						
						mysql_query("UPDATE `ladder_teams` SET `wins`=".$wins.",`points`=".$currentPoints." WHERE `entryID`=".$secondRow['entryID']);
						mysql_query("UPDATE `matches` SET `winnerID`=".$secondRow['teamID'].",`pointsEarned`=".$bedrag." WHERE `matchID`=".(int)$_GET['mID']);
					}
					
					// now update the score of the loser
					$query = mysql_query("SELECT * FROM `ladder_teams` WHERE `teamID`=".$row['opponentID']." AND `ladderID`=".$row['ladderID']);
					if( $query )
					{
						$secondRow = mysql_fetch_array($query);
						$currentPoints = $secondRow['points'];
						$currentPoints -= $bedrag;
						if( $currentPoints < 0 )
							$currentPoints = 0;
						mysql_query("UPDATE `ladder_teams` SET `losses`=".$losses.",`points`=".$currentPoints." WHERE `entryID`=".$secondRow['entryID']);
					}
				}
				else
				{
					// draw, nothing should happen
				}
			}
		}
	}
}

if( $_POST['setScore'] )
{
	$isChallenger = (int)$_POST['lol'];
	$teamScore = (int)$_POST['teamScore'];
	$oppoScore = (int)$_POST['oppoScore'];
	
	// make sure that the user is a valid user
	$query = mysql_query("SELECT * FROM `team_members` WHERE `userID`=".(int)$userSession->userID);
	$found = false;
	if( $query )
	{
		while($row = mysql_fetch_array($query))
		{
			if( $row['teamID'] == $_GET['tID'] && $row['level'] == $LevelAdmin )
			{
				$found = true;
				break;
			}
		}
	}
	
	if( $found )
	{
		if( $isChallenger )
			$extra = ',`challengerAccepted`=1';
		else
			$extra = ',`opponentAccepted`=1';
		if( mysql_query("UPDATE `matches` SET `challengerScore`=".$teamScore.",`opponentScore`=".$oppoScore.' '.$extra." WHERE `matchID`=".(int)$_GET['mID']) )
		{
			//echo "UPDATE `matches` SET `challengerScore`='".$teamScore."' AND `opponentScore`='".$oppoScore."' WHERE `matchID`=".(int)$_GET['mID'];
			$success = 'Successfully entered the score';
		}
	}
}

/*$query = mysql_query("SELECT * FROM `matches` WHERE `matchID`=".(int)$_GET['mID']);
if( $query )
{
	$match = mysql_fetch_array($query);
}*/

$matchObject = new Match($_GET['mID']);

require 'template/tp.head.php';
require 'template/tp.viewMatch.php';
require 'template/tp.foot.php';
?>