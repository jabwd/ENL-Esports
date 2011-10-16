<?php
if( $success )
	echo '<span style="color:green;">'.$success.'</span>';

if( $matchObject->matchID )
{
	echo '<h3>Match details</h3>';
	
	echo '<table>';
	echo '<tr><td>Ladder</td><td>'.$matchObject->ladder->ladderName.'</td></tr>';
	echo '<tr><td>Challenger</td><td>'.$matchObject->challenger->teamName.'</td></tr>';
	echo '<tr><td>Challengee</td><td>'.$matchObject->opponent->teamName.'</td></tr>';
	if( !$matchObject->winner )
	{
		if( $matchObject->challengerScore > 0 || $matchObject->opponentScore > 0 )
			$status = 'Waiting for score';
		else
			$status = '<b><i>To be played on '.$matchObject->dueDate.'</i></b>';
		echo '<tr><td>Match status</td><td>'.$status.'</td></tr>';
	}
	else
	{
		echo '<tr><td>Match status</td><td>'.$matchObject->winner->teamName.' won</td></tr>';
		echo '<tr><td>Score</td><td>'.$matchObject->challengerScore.'-'.$matchObject->opponentScore.'</td></tr>';
		echo '<tr><td>Points</td><td>'.$matchObject->points.'</td></tr>';
		echo '<tr><td>Map</td><td>'.$matchObject->map.'</td></tr>';
	}
	echo '</table>';
	
	
	echo '<br />';
	echo '<br />';
	
	if( strtotime($matchObject->dueDate) < time() && ($teamMember['level'] == $LevelAdmin || $userSession->adminLevel == $LevelAdmin) )
	{
	
	/*if( $matchObject->challengerScore < 1 )
		$matchObject->challengerScore = "--";
	if( $matchObject->opponentScore < 1 )
		$matchObject->opponentScore = '--';*/
	
	$score = $matchObject->challengerScore . ' - ' . $matchObject->opponentScore;
	echo '<table>';
	if( $matchObject->challenger->teamID == $_GET['tID'] )
	{
		echo '<tr><td>Your score</td><td>'.$matchObject->challengerScore.'</td></tr><tr><td>The score of your opponent</td><td>'.$matchObject->opponentScore.'</td></tr>';
		// we are the challenger
		if( $matchObject->challengerAccepted == 0 && $matchObject->opponentAccepted )
		{
			echo '<tr><td>Accept the score</td><td><a href="'.$baseURL.'viewMatch/?mID='.$_GET['mID'].'&accept=true&tID='.$_GET['tID'].'">Accept score</a></td></tr>';
		}
		else if( $matchObject->challengerAccepted == 1 )
		{
			if( $matchObject->opponentAccepted == 0 )
				echo 'Waiting for the opponent to accept';
		}
		else
		{
			echo 'Enter the score of the match:<br />';
?>
<form method="post">
<tr><td>Your team score</td><td><input type="text" name="teamScore" value="" maxlength="2"/></td></tr>
<tr><td>Your opponents score</td><td><input type="text" name="oppoScore" value="" maxlength="2"/></td></tr>
<tr><td></td><td><input type="submit" name="setScore" value="Save"/></td></tr>
<input type="hidden" name="lol" value="1"/>
</form>
<?php
		}
	}
	else
	{
		echo '<tr><td>Your score</td><td>'.$matchObject->opponentScore.'</td></tr><tr><td>The score of your opponent</td><td>'.$matchObject->challengerScore.'</td></tr>';
		// we are the opponent
		if( $matchObject->opponentAccepted == 0 && $matchObject->challengerAccepted )
		{
			echo '<tr><td>Accept the score</td><td><a href="'.$baseURL.'viewMatch/?mID='.$_GET['mID'].'&accept=true&tID='.$_GET['tID'].'">Accept score</a></td></tr>';
		}
		else if( $matchObject->opponentAccepted == 1 )
		{
			if( $matchObject->challengerAccepted == 0 )
				echo 'Waiting for the opponent to accept';
		}
		else
		{
			echo '<tr><td>Enter the score of the match</td><td></td></tr>';
?>
<form method="post">
<tr><td>Your team score</td><td><input type="text" name="oppoScore" value="" maxlength="2"/></td></tr>
<tr><td>Your opponents score</td><td><input type="text" name="teamScore" value="" maxlength="2"/></td></tr>
<tr><td></td><td><input type="submit" name="setScore" value="Save"/></td></tr>
<input type="hidden" name="lol" value="0"/>
</form>
<?php
		}
	}
	echo '</table>';
	
	}
	else if( strtotime($matchObject->dueDate) < time() )
	{
	}
	else
	{
		echo '<i><b>The match hasn\'t been played yet, you can not enter the score just yet!</b></i>';
	}
}
else
{
	echo 'Incorrect match was selected, try again later';
}
?>
<br />
<br />
<p>
Whenever the other team has entered an incorrect score you can conflict the match. Do this by contacting an admin in our IRC channel. #enl.et on xs4all.quakenet.org.
<br />
Note that valid proof ( Screenshot, demo ) is required.
</p>