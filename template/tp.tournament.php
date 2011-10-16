<h3>ENL Tournaments</h3>
<?php
if( $controller )
{
	$tournaments = $controller->tournaments;
	if( $tournaments )
	{
		echo '<table>';
		foreach($tournaments as $tournament)
		{
			echo '<tr><td><a href="'.$baseURL.'viewTournament/?tID='.$tournament->tournamentID.'">'.$tournament->tournamentName.'</a></td></tr>';
		}
		echo '</table>';
	}
	else
	{
		echo '<i>There are currently no tournaments on ENL</i>';
	}
}
?>
<br />
<br />
<?php if( $userSession->adminLevel == $LevelAdmin )
{
?>
<a href="createTournament/">Create a new tournament</a>
<?php
}
?>