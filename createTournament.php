<?php
require 'config.php';

checkLogin($LevelAdmin);

if( $_POST['create'] )
{
	$tournamentName = secureInput($_POST['tournamentName']);
	$tournamentRules = secureInput($_POST['tournamentRules']);
	
	if( strlen($tournamentName) > 60 )
		$errors[] = 'The tournament name is too long';
	if( strlen($tournamentName) < 4 )
		$errors[] = 'The tournament name needs to be at least 4 characters long!';
	if( strlen($tournamentRules) < 1 )
		$tournamentRules = 'This tournament has no specific rules, so the regular ladder rules apply.';
	if( strlen($tournamentRules) > 6000 )
		$errors[] = 'Rules cannot be longer than 6000 characters, remove '.(strlen($tournamentRules)-6000).' characters!';
		
	if( ! $errors )
	{
		mysql_query("INSERT INTO `tournaments` (`tournamentName`,`rules`) VALUES ('".$tournamentName."','".$tournamentRules."')");
		header("location: ".$baseURL."tournament/");
		exit();
	}
}

if( ! $_GET['ajax'] )
	require 'template/tp.head.php';

require 'template/tp.createTournament.php';	

if( ! $_GET['ajax'] )
	require 'template/tp.foot.php';
?>