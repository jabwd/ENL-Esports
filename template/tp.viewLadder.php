<a href="ladders/">&larr; Back to the ladders list</a>

<?php
if( $ladder && $teams )
{
	echo '<h3>'.$ladder['ladderName'].'</h3>';
	echo '<table><tr><th>Rank</th><th>Team</th><th>Points</th><th>Wins</th><th>Losses</th><th></th></tr>';
	$count = 1;
	$alternate = false;
	foreach($teams as $team)
	{
		if( $alternate )
		{
			$extra = 'class="alternate"';
			$alternate = false;
		}
		else
		{
			$extra = '';
			$alternate = true;
		}
		if( $team['inactive'] == 1 )
			$extra = 'class="inactive"';
		echo '<tr '.$extra.'><td>'.$count.'.</td><td><a href="'.$baseURL.'viewTeam/?tID='.$team['teamID'].'">'.$team['teamName'].'</a></td><td>'.$team['points'].'</td><td>'.$team['wins'].'</td><td>'.$team['losses'].'</td><td><a href="match/?tID='.$team['teamID'].'&amp;ladderID='.(int)$_GET['ladderID'].'">Challenge</a></td></tr>';
		$count++;
	}
	echo '</table>';
}
else if( $ladder && $ladder['ladderID'] == 4 )
{
	if( $users )
	{
		echo '<h3>'.$ladder['ladderName'].'</h3><table><tr><th>Rank</th><th>User</th><th>Points</th></tr>';
		$count = 1;
		foreach($users as $user)
		{
			$displayName = $user['nickname'];
			if( strlen($displayName) < 1 )
			{
				$displayName = $user['username'];
			}
			echo '<tr><td>'.$count.'.</td><td><a href="'.$baseURL.'viewUser/?userID='.$user['userID'].'">'.$displayName.'</a></td><td>'.$user['points'].'</td><td></td></tr>';
			$count++;
		}
		echo '</table>';
	}
	else
	{
		echo 'There is currently no one in the ET 1on1 ladder';
	}
}
else
{
	echo 'There are currently no teams signed up in this ladder';
}
?>