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
<b>Your challenge wasn't created because of the following reason<?php if( count($errors) > 0 ) { echo 's'; } ?>:</b>
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
if( $recents )
{
	echo '<h3>Recent matches</h3><table><tr><th>Team name</th><th>Opponent name</th><th>Ladder</th></tr>';
	foreach($recents as $match)
	{
		$ladderName = 'Unknown';
		foreach($ladders as $ladder)
		{
			if( $ladder['ladderID'] == $match['ladderID'] )
				$ladderName = $ladder['ladderName'];
		}
		echo '<tr><td>'.$match['yourName'].'</td><td>'.$match['opponentName'].'</td><td>'.$ladderName.'</td></tr>';
	}
	echo '</table>';
}


if( $yourMatches )
{
	echo '<h3>Your pending matches</h3><table><tr><th>Team name</th><th>Opponent name</th><th></th><th>Ladder</th></tr>';
	foreach($yourMatches as $match)
	{
		$ladderName = 'Unknown';
		foreach($ladders as $ladder)
		{
			if( $ladder['ladderID'] == $match['ladderID'] )
				$ladderName = $ladder['ladderName'];
		}
		echo '<tr><td>'.$match['yourName'].'</td><td>'.$match['opponentName'].'</td><td><a href="'.$baseURL.'viewMatch/?mID='.$match['matchID'].'&tID='.$match['yourTeamID'].'">Match details &rarr;</a></td><td>'.$ladderName.'</td></tr>';
	}
	echo '</table>';
}
?>
<h3>Create a new match</h3>

<form method="post">
<table>
	<tr>
		<td>Ladder</td>
		<td>
			<select name="ladder">
				<?php
				if( $ladders )
				{
					foreach($ladders as $ladder)
					{
						$extra = '';
						if( $ladder['ladderID'] == $_GET['ladderID'] )
							$extra = 'selected="true"';
						echo '<option '.$extra.' value="'.$ladder['ladderID'].'">'.$ladder['ladderName'].'</option>';
					}
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Your team</td>
		<td>
		<select name="team1">
				<?php
				if( $yourTeams )
				{
					foreach($yourTeams as $yourTeam)
					{
						echo '<option value="'.$yourTeam['teamID'].'">'.$yourTeam['teamName'].'</option>';
					}
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Opponent</td>
		<td>
		<select name="team2">
				<?php
				if( $teams )
				{
					foreach($teams as $team)
					{
						$extra = '';
						$extra = '';
						if( $team['teamID'] == $_GET['tID'] )
							$extra = 'selected="true"';
						echo '<option '.$extra.' value="'.$team['teamID'].'">'.$team['teamName'].'</option>';
					}
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Match time</td><td><input type="text" name="matchDate" value="<?php if( $_POST['matchDate'] ) echo $_POST['matchDate']; else echo date("d-m-Y H:i", (time()+3600)); ?>"/></td>
	</tr>
	
	<tr class="alternate">
		<td>&nbsp; </td><td></td>
	</tr>
	
	<tr>
		<td></td><td><input type="submit" name="challenge" value="Challenge"/></td>
	</tr>
	<br />
</table>
</form>