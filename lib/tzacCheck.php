<?php
chdir("../");

require 'config.php';
require 'class/TzacQuery.php'; 

echo 'This script will display any incorrect slac accounts ( if any ).';

$query = mysql_query("SELECT `slacID`,`username`,`userID` FROM `users`");
while($row=mysql_fetch_array($query))
{
	if( $row['userID'] > 0 )
	{
		$tzacQuery = new TzacQuery($row['slacID']);
		if( $tzacQuery->tzacID > 0 && strpos($tzacQuery->cheatStatus,"OK") )
		{
			//echo $row['username'].' OK.<br />';
		}
		else
		{
			echo $row['username'].' Error.<br />';
		}
	}
}
?>