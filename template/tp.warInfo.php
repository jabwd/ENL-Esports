<?php
echo '<h3><i><a style="text-decoration:none;color:black;" href="viewTeam/?tID='.$match->challenger->teamID.'">'.$match->challenger->teamName.'</a></i> vs. <i><a style="text-decoration:none;color:black;" href="viewTeam/?tID='.$match->opponent->teamID.'">'.$match->opponent->teamName.'</a></i></h3>';
echo '<table>';
echo '<tr><td>Ladder</td><td>'.$match->ladder->ladderName.'</td></tr>';
if( !$match->winner )
{
	echo '<tr><td>Match status</td><td><b><i>To be played on '.$match->dueDate.'</i></b></td></tr>';
}
else
{
	echo '<tr><td>Match status</td><td>'.$match->winner->teamName.' won</td></tr>';
	echo '<tr><td>Score</td><td>'.$match->challengerScore.'-'.$match->opponentScore.'</td></tr>';
	echo '<tr><td>Points</td><td>'.$match->points.'</td></tr>';
	echo '<tr><td>Map</td><td>'.$match->map.'</td></tr>';
}
echo '</table>';
?>