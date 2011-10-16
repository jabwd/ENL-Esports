<?php
// this page only needs to have mysql input validation, no actual
// admin validation. Everyone is allowed to see whether a team is in a ladder
// actively or not.
//
// By echoing error we tell the calling script that we encountered an error
// and that it can display it to the user accordingly.
//
// Currently displaying the button value is the only thing this script actually does
// more content could ( should ? ) be added later when more ladder options are added

chdir('../');
require 'config.php';

	
$query = mysql_query("SELECT * FROM `ladder_teams` WHERE `ladderID`=".(int)$_GET['ladderID']." AND `teamID`=".(int)$_GET['teamID']);
if( $query )
{
	$row = mysql_fetch_array($query);
	if( $row['inactive'] == 0 )
	{
		echo 'Go inactive';
	}
	else
	{
		echo 'Go active';
	}
}
else
	echo 'Error';
?>