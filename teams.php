<?php
require 'config.php';

$pageName = 'Teams';

if( ! $_GET['page'] )
	$cPage = 0;
else
	$cPage = (((int)$_GET['page'])-1);
if( $cPage < 0 )
	$cPage = 0;
$cPageCount = 25;
if( $_POST['search'] || $_GET['filter'] )
{
	$cPage = 0;
	$cPageCount = 10000;
}


if( ! $_POST['search'] )
{
	$query 		= mysql_query("SELECT * FROM `teams` ORDER BY `teamName`");
	$pagesCount = ceil(mysql_num_rows($query)/25);
	if( $query )
	{
		$count = ($cPage*25);
		$count2 = 0;
		while($row = mysql_fetch_array($query))
		{
			if( $count <= 0 )
			{
				$count2++;
				$teams[] = $row;
				if( $count2 >= 25 )
					break;
			}
			else
				$count--;
		}
	}
}
else
{
	$searchQuery = strtolower(secureInput($_POST['teamName']));
	
	if( strlen($searchQuery) > 0 )
	{
		$query = mysql_query("SELECT `teamName`,`tag`,`teamID` FROM `teams` ORDER BY `teamName`");
		while($row = mysql_fetch_array($query))
		{
			if( strpos(strtolower(" ".$row['teamName'].$row['tag']),$searchQuery) )
			{
				$teams[] = $row;
			}
		}
	}
}

if( $_GET['register'] )
	checkLogin();

if( $_POST['registerTeam'] )
{
	checkLogin();
	$teamName 		= secureInput($_POST['teamName']);
	$teamPassword 	= passwordHash($_POST['joinPassword']);
	$teamTag		= mysql_real_escape_string(strip_tags($_POST['teamTag']));
	$teamIRC		= secureInput($_POST['teamIRC']);
	
	if( strlen($teamName) > 60 )
		$errors[] = 'The team name you specified is too long';
	if( strlen($teamName) < 1 )
		$errors[] = 'Please fill in a team name, this field is required!';
	if( strlen($teamTag) > 9 )
		$errors[] = 'The team tag you entered is too long';
	if( strlen($teamIRC) > 20 )
		$errors[] = 'The IRC channel you entered is too long..';
	
	// make sure that the team name is not already in use
	// case INsensitive, as the team name really should be different
	// from the other team's name
	foreach($teams as $team)
	{
		if( strcasecmp($teamName,$team['teamName']) == 0 )
		{
			$errors[] = 'The team name you picked is already in use, try another one ( case insensitive! )';
		}
	}
	
	if( ! $errors )
	{
		$sql = "INSERT INTO `teams` (`teamName`,`joinPassword`,`tag`,`ircChannel`) VALUES ('".$teamName."','".$teamPassword."','".$teamTag."','".$teamIRC."')";
		if( mysql_query($sql) )
		{
			$query 	= mysql_query("SELECT * FROM `teams` WHERE `teamName`='".$teamName."'");
			$row 	= mysql_fetch_array($query);
			
			// add the current user as an admin of this team, that way he can modify the settings..
			// if these lines are not executed well then you will have a team that you cannot use
			mysql_query("INSERT INTO `team_members` (`teamID`,`userID`,`level`) VALUES ('".(int)$row['teamID']."','".(int)$userSession->userID."',".$LevelAdmin.")");


			// add history to the database, admins have to be able to track when what is done..
			// action 1 is created
			// action 2 is deleted
			mysql_query("INSERT INTO `teams_log` (`teamID`,`action`,`userID`) VALUES (".$row['teamID'].",1,".(int)$userSession->userID.")");
			
			
			// all done here, redirect the user to the team page, exit the script as we no longer need
			// to do anything here.
			$success = true;
			header("location: ".$baseURL."viewTeam/?tID=".$row['teamID']);
			echo 'You should be redirected now..';
			exit();
		}
		else
		{
			$errors[] = 'A server occured, please try again later';
		}
	}
}

if( !$_GET['ajax'] )
require 'template/tp.head.php';
	require 'template/tp.teams.php';
if( !$_GET['ajax'] )
require 'template/tp.foot.php';
?>