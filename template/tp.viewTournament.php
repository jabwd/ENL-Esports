<a href="tournament/">&larr; Back to tournaments overview</a>
<br />
<br />
<?php
if( ! $errors && $success )
{
	echo '<span class="success">'.$success.'</span>';
}
else if( $errors )
{
	?>
<br />
<div class="errorDisplay">
<b>The message was not sent because of the following reason<?php if( count($errors) > 0 ) { echo 's'; } ?>:</b>
<br />
<ul>
	<?php
	foreach($errors as $error)
	{
		echo '<li>'.$error.'</li>';
	}
	?>
</ul>
</div>
<?php
}
?>
<h3><?php echo $tournament->tournamentName; ?></h3>
<div class="subMenu">
	<a <?php if( count($_GET) == 1 ) echo 'class="selected"'; ?> href="<?php echo $baseURL; ?>viewTournament/?tID=<?php echo $_GET['tID']; ?>"><img class="tableIcon" src="resources/chart_organisation.png"/>Tournament overview</a>
	<a <?php if( $_GET['rules'] ) echo 'class="selected"'; ?> href="<?php echo $baseURL; ?>viewTournament/?tID=<?php echo $_GET['tID']; ?>&rules=true"><img class="tableIcon" src="resources/book_open.png"/>Tournament specific rules</a>
	<a <?php if( $_GET['teams'] ) echo 'class="selected"'; ?> href="<?php echo $baseURL; ?>viewTournament/?tID=<?php echo $_GET['tID']; ?>&teams=true"><img class="tableIcon" src="resources/group.png"/>Signed up teams</a>
	<?php
	if( $tournament->allowSignups )
	{
	?>
	<a href="<?php echo $baseURL; ?>viewTournament/?tID=<?php echo $_GET['tID'].'&signup='.$tournament->tournamentID; ?>"><img class="tableIcon" src="resources/chart_organisation_add.png"/>Signup</a>
	<?php
	}
	if( $userSession->adminLevel == $LevelAdmin )
	{
		if( $tournament->allowSignups )
		{
			echo '<a href="'.$baseURL.'viewTournament/?tID='.$_GET['tID'].'&toggleSignups=true"><img class="tableIcon" src="resources/lock.png"/>Close signups</a>';
		}
		else
		{
			echo '<a href="'.$baseURL.'viewTournament/?tID='.$_GET['tID'].'&toggleSignups=true">Open signups</a>';
			echo '<a href="'.$baseURL.'viewTournament/?tID='.$_GET['tID'].'&enterMatch=true"><img class="tableIcon" src="resources/Trophy-Gold-icon-5.png"/>Enter score</a>';
		}
	}
	?>
</div>
<br />
<?php
if( $tournament->tournamentID && $_GET['rules'] )
{
	if( $userSession->adminLevel >= $LevelAdmin )
	{
		echo '<a onclick="showEditRules();return false;" href="#">Edit rules</a><br /><br />';
		echo '<form method="post" id="rulesForm" style="display:none;">';
		echo '<textarea rows="10" cols="80" id="rulesArea" name="rules"></textarea><br />';
		echo '<input type="submit" name="updateRules" value="Update"/><br /><br />';
		echo '</form>';
	}
	echo '<div id="rulesBox">'.$tournament->rules.'</div>';
}
else if( $_GET['enterMatch'] && $tournament->winnerID == 0 )
{
?>
<h4>Enter match scores here</h4>
<form method="post">
<select name="matchCount">
<option value="0">Select match</option>
<?php
	$bracketOutput = $tourBracket->bracketOutput();
	$count = 1;
	foreach($bracketOutput as $output)
	{
		foreach($output as $team)
		{
			if( ! $set )
			{
				$set = $team;
				continue;
			}
			else
			{
				echo '<option value="'.$count.';'.$set['teamID'].';'.$team['teamID'].'">'.$set['name'].' vs '.$team['name'].'</option>';
				unset($set);
			}
		}
		$count++;
	}
?>
</select>
<br />
<select name="team1Score">
<option value="0">0</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select>
-
<select name="team2Score">
<option value="0">0</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
</select>
<br />
<br />
<input type="submit" name="enterScore" value="Enter score"/>
</form>

<br />
<br />
<?php
	$teams = $tournament->teams();
	if( count($teams) > 0 )
	{
?>
<h4>Or.. select the winner of this tournament</h4>
<form method="post">

<select name="winningTeam">
<?php
foreach($teams as $team)
{
	echo '<option value="'.$team->teamID.'">'.$team->teamName.'</option>';
}
?>
</select>
<br />
<br />
<input type="submit" name="enterWinningTeam" value="Go"/>
</form>
<?php
	}
}
else if( $_GET['toggleSignups'] )
{
	if( $tournament->allowSignups && ! $toggleResult )
	{
		echo 'Signups are now open.';
	}
	else
	{
		echo $toggleResult;
	}
}
else if( $_GET['signup'] )
{
	if( $yourTeams )
	{
		echo 'Select the team you want to use to signup';
		echo '<form method="post"><div>';
		echo '<select name="teamID">';
			foreach($yourTeams as $yourTeam)
			{
				echo '<option value="'.$yourTeam->teamID.'">'.$yourTeam->teamName.'</option>';
			}
		echo '</select><br /><input type="submit" name="signup" value="Sign up"/></div>';
		echo '</form>';
	}
	else
	{
		echo '<i>You do not have any teams to sign up with, either create a team or let your team admin sign up!</i>';
	}
}
else if( $_GET['teams'] )
{
	$teams = $tournament->teams();
	if( count($teams) < 1 )
	{
		echo '<i>There are currently no teams signed up for this tournament</i>';
	}
	else
	{
		$alternate = false;
		$extra = '';
		echo '<table>';
		foreach($teams as $team)
		{
			if( $alternate )
			{
				$extra = ' class="alternate"';
				$alternate = false;
			}
			else
			{
				$extra = '';
				$alternate = true;
			}
			$extraCell = '';
			if( $userSession->adminLevel == $LevelAdmin )
			{
				$extraCell = '<a href="viewTournament/?tID='.$_GET['tID'].'&signoff='.$team->teamID.'"><img src="resources/icons/delete.png" class="tableIcon" />Sign off</a>';
			}
			else
			{
				foreach($adminTeams as $adminteam)
				{
					if( $adminteam['teamID'] == $team->teamID && $tournament->allowSignups )
						$extraCell = '<a href="viewTournament/?tID='.$_GET['tID'].'&signoff='.$team->teamID.'"><img src="resources/icons/delete.png" class="tableIcon" />Sign off</a>';
				}
			}
			echo '<tr'.$extra.'><td><a href="viewTeam/?tID='.$team->teamID.'">'.$team->teamName.'</a></td><td>'.$extraCell.'</td></tr>';
		}
		echo '</table>';
	}
}
else if( $tournament->tournamentID )
{
	if( $tournament->winnerID )
	{
		$winningTeam = $tournament->winningTeam();
		if( $winningTeam->teamID )
		{
			echo '<h3><img src="resources/award_star_gold_1.png" class="tableIcon" /><a style="text-decoration:none;color:black;" href="viewTeam/?tID='.$winningTeam->teamID.'">'.$winningTeam->teamName.'</a> has won this tournament!</h3>';
			echo 'You can view the results of this tournament in the brackets below, however the tournament is over and a winner has been selected. You can no longer play in this tournament.';
		}
	}
	
	
	// no signups are allowed anymore which means we need to show the bracket
	// as the tournament has started
	if( $tournament->allowSignups < 1 && $tourBracket )
	{
		$teams = $tournament->teams();
		$brackets = new Brackets(count($teams));
		
		$brackets->addTeams($tourBracket->bracketOutput());
?>
<div class="bracketsContainer">
<table class="brackets" cellspacing="0" cellpadding="0">
<?php for($row = 1; $row <= $brackets->returnRows(); $row++): ?>
<tr>
	<?php for($round = 1; $round <= $brackets->returnRounds(); $round++): ?>
	<td
		<?php $brackets->showTeams($row, $round); ?>
	</td>
	<td class="blank">
		<?php $brackets->showImages($row, $round); ?>
	</td>
	<?php endfor; ?>
</tr>
<?php endfor; ?>
</table>
</div>
<?php	
	}
	else
		echo '<br /><br />The signing up for this tournament is still allowed, sign up now before you miss all the fun!';
}
else
{
	echo '<i>That tournament does not exist!</i>';
}
?>