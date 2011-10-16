<?php
// fetches tzac status for an ajax script

chdir("../");
require 'class/TzacQuery.php';

$tzacID = (int)$_GET['tzacID'];
if( $tzacID == 0 )
{
	echo 'User suspended on ENL';
}

$tzacQuery = new TzacQuery($tzacID);

if( $tzacID > 0 )
{
	if( strpos(" ".$tzacQuery->cheatStatus,"OK") > 0 )
		echo 'ok';
	else if( strpos(" ".$tzacQuery->cheatStatus,"CHEATER") > 0 )
		echo 'cheater';
	else
		echo 'Unknown, TZAC might be down';
}
else
{
	echo 'Fake TZAC ID';
}
?>