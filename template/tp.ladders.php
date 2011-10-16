<?php
if( ! $errors && $success )
{
	echo '<span style="color:green;">'.$success.'</span>';
}
else if( $errors )
{
	?>
<br />
<div class="errorDisplay">
<b>Could not join the team because:</b>
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

<?php
if( ! $_GET['join'] )
{
	if( $ladders )
	{
		echo '<table><tr><th>Ladder name</th><th>Number of players required to join</th><th>Join</th></tr>';
		foreach($ladders as $ladder)
		{
			echo '<tr><td><a href="'.$baseURL.'viewLadder/?ladderID='.(int)$ladder['ladderID'].'">'.$ladder['ladderName'].'</a></td><td style="width:150px;">'.$ladder['ladderPlayers'].'</td><td><a href="'.$baseURL.'ladders/?join='.$ladder['ladderID'].'">Join ladder</a></td></tr>';
		}
		echo '</table>';
	}
	else
	{
		echo 'There are currently no ladders available, try again later';
	}
}
else if( $teams )
{
?>
<form method="post">
<div>
Select the team you would like to enter in the ladder:
<br />
<select name="team" style="min-width:200px;">
<?php
foreach($teams as $team)
{
	echo '<option value="'.$team['teamID'].'">'.$team['teamName'].'</option>';
}
?>
</select>
<br />
<input type="submit" name="joinTeam" value="Join Ladder"/>
</div>
</form>
<?php
}
else
{
	echo 'You have no teams that you can enter in this ladder.';
}
?>