<?php
require 'config.php';

if( $_POST['upload'] )
{
	exit();
	if( $_FILES['file']['error'] == 0 )
	{
		if( 
			$_FILES['file']['type'] == 'image/png' ||
			$_FILES['file']['type'] == 'image/bmp' ||
			$_FILES['file']['type'] == 'image/gif' ||
			$_FILES['file']['type'] == 'image/jpeg' ||
			$_FILES['file']['type'] == 'image/pjpeg' ||
			$_FILES['file']['type'] == 'image/pict' ||
			$_FILES['file']['type'] == 'image/tiff' ||
			$_FILES['file']['type'] == 'image/x-tiff' )
		{
			if( $_FILES['file']['size'] < 1024000 )
			{
				$parts = explode(".",$_FILES['file']['name']);
				$link = "resources/avatars/user_".$userSession->userID.".".$parts[(count($parts)-1)];
				move_uploaded_file($_FILES["file"]["tmp_name"],$link);
				$success = 'Uploaded';
				$userSession->avatar = $link;
				$_SESSION['user'] = serialize($userSession);
				mysql_query("UPDATE `users` SET `avatar`='".secureInput($link)."' WHERE `userID`=".(int)$userSession->userID);
			}
			else
			{
				$errors[] = 'That file is too big, 1mb only.';
			}
		}
		else
		{
			$errors[] = 'That file type is not supported by ENL';
		}
	}
}

$query = mysql_query("SELECT * FROM `teams` WHERE `teamID`=".(int)$_GET['tID']);
if( $query )
{
	$currentTeam = mysql_fetch_array($query);
	
	// determine the admin level we have
	$query = mysql_query("SELECT * FROM `team_members`,`users` WHERE `team_members`.`teamID`=".(int)$_GET['tID']." AND `users`.`userID`=`team_members`.`userID` ORDER BY `team_members`.`level` DESC");
	if( $query )
	{
		while($row = mysql_fetch_array($query))
		{
			$members[] = $row;
			if( $row['userID'] == $userSession->userID )
			{
				$foundUser = true;
				$userLevel = $row['level'];
			}
		}
	}
}

if( $_GET['op'] )
{
	if( $userLevel == $LevelAdmin )
	{
		mysql_query("UPDATE `team_members` SET `level`=10 WHERE `userID`=".(int)$_GET['op']." AND `teamID`=".(int)$_GET['tID']);
		header("location: http://www.enl-esports.com/viewTeam/?tID=".(int)$_GET['tID']);
	}
}

if( $_GET['deop'] )
{
	if( $userLevel == $LevelAdmin )
	{
		mysql_query("UPDATE `team_members` SET `level`=0 WHERE `userID`=".(int)$_GET['deop']." AND `teamID`=".(int)$_GET['tID']);
		header("location: http://www.enl-esports.com/viewTeam/?tID=".(int)$_GET['tID']);
	}
}

if( ! $currentTeam )
{
	header("location: ".$baseURL."teams/");
	exit();
}

if( $_GET['ladderInfo'] )
{
	$query = mysql_query("SELECT * FROM `ladder_teams`,`ladders` WHERE `ladders`.`ladderID`=`ladder_teams`.`ladderID` AND `ladder_teams`.`teamID`=".(int)$_GET['tID']);
	while($row = mysql_fetch_array($query))
	{
		$ladders[] = $row;
	}
}

$pageName = $currentTeam['teamName'];

if( $_POST['leaveTeam'] )
{
	checkLogin();
	if( mysql_query("DELETE FROM `team_members` WHERE `userID`=".(int)$userSession->userID." AND `teamID`=".(int)$_GET['tID']) )
	{
		mysql_query("INSERT INTO `team_membersLog` (`teamID`,`userID`,`action`) VALUES (".(int)$_GET['tID'].",".(int)$userSession->userID.",1)");
		header("location: ".$baseURL."viewTeam/?tID=".(int)$_GET['tID']."&members=true");
		exit();
	}
	
}
else if( $_POST['cancelLeave'] )
{
	header("location: ".$baseURL."viewTeam/?tID=".(int)$_GET['tID']);
}

if( $_POST['joinTeam'] )
{
	checkLogin();
	$password = passwordHash($_POST['joinPassword']);
	
	$query = mysql_query("SELECT * FROM `team_members` WHERE `teamID`='".(int)$_GET['tID']."'");
	if( $query )
	{
		while($row= mysql_fetch_array($query))
		{
			if( $row['userID'] == $userSession->userID )
			{
				$errors[] = 'You are already a member of this team';
				break;
			}
		}
	}
	
	$query = mysql_query("SELECT * FROM `teams` WHERE `teamID`='".(int)$_GET['tID']."'");
	if( $query && ! $errors )
	{
		$row = mysql_fetch_array($query);
		if( $row['joinPassword'] == $password )
		{
			// the password is correct, the player can join the team
			if( mysql_query("INSERT INTO `team_members` (`teamID`,`userID`) VALUES (".(int)$_GET['tID'].",".(int)$userSession->userID.")") )
			{
				mysql_query("INSERT INTO `team_membersLog` (`teamID`,`userID`,`action`) VALUES (".(int)$_GET['tID'].",".(int)$userSession->userID.",0)");
				$success = 'Successfully joined the team '.$currentTeam['teamName'];
				/*$newRow = $_SESSION['user'];
				$newRow['teamID'] = $currentTeam['teamID'];
				$newRow['level'] = 0;
				$members[] = $newRow;*/
				// TBD
				header("location: ".$baseURL."viewTeam/?tID=".(int)$_GET['tID']."&members=true");
				echo ':D';
				exit();
			}
			else
			{
				$errors[] = 'A server error occured, please try again later';
			}
		}
		else
		{
			$errors[] = 'The password you entered is not correct';
		}
	}
	else
	{
		$errors[] = 'A server error occured, please try again later';
	}
}


// handle the kick GET request
if( $_GET['kick'] )
{
	checkLogin();
	if( $userLevel == $LevelAdmin )
	{
		$sql = "DELETE FROM `team_members` WHERE `teamID`='".(int)$_GET['tID']."' AND `userID`='".(int)$_GET['kick']."'";
		if( mysql_query($sql) )
		{
			$query = mysql_query("SELECT * FROM `users` WHERE `userID`='".(int)$_GET['kick']."'");
			if( $query )
			{
				$row = mysql_fetch_array($query);
				$displayName = $row['nickname'];
				if( strlen($displayName) < 1 )
					$displayName = $row['username'];
			}
			mysql_query("INSERT INTO `team_membersLog` (`teamID`,`userID`,`action`) VALUES (".(int)$_GET['tID'].",".(int)$_GET['kick'].",2)");
			$success = 'Successfully kicked '.$displayName;
			header("location: http://www.enl-esports.com/viewTeam/?tID=".(int)$_GET['tID']);
		}
		else
		{
			$errors[] = 'A server error has occured, please try again later';
		}
	}
	else
	{
		$errors[] = 'You are not authorized to kick players from this team!';
	}
}


// list all the matches played by this team
if( $_GET['matches'] )
{
	$query = mysql_query("SELECT `ladderID`,`ladderName` FROM `ladders`");
	while($row= mysql_fetch_array($query))
	{
		$ladderList[] = $row;
	}
	$query = mysql_query("SELECT `matches`.`pointsEarned`,`matches`.`winnerID`,`matches`.`challengerScore`,`matches`.`opponentScore`,`matches`.`challengerID`,`matches`.`opponentID`,`ladderID`,`matches`.`matchID` FROM `matches` WHERE `opponentID`=".(int)$_GET['tID']." OR `challengerID`=".(int)$_GET['tID']." ORDER BY `matches`.`dueDate` DESC");
	if( $query )
	{
		while($row = mysql_fetch_array($query))
		{
			// skip matches that haven't been played yet
			if( ! $row['winnerID'] )
				continue;

			foreach($ladderList as $ladder)
			{
				if( $ladder['ladderID'] == $row['ladderID'] )
				{
					$row['ladderName'] = $ladder['ladderName'];
				}
			}
			$matches[] = $row;
		}
	}
}


if( $_GET['members'] )
{
	$query = mysql_query("SELECT `action`,`date`,`username`,`nickname`,`users`.`userID` FROM `users`,`team_membersLog` WHERE `team_membersLog`.`teamID`=".(int)$_GET['tID']." AND `users`.`userID`=`team_membersLog`.`userID`");
	if( $query )
	{
		while($row = mysql_fetch_array($query))
		{
			$membersHistory[] = $row;
		}
	}
}

if( !$_GET['ajax'] )
require 'template/tp.head.php';
require 'template/tp.viewTeam.php';
if( !$_GET['ajax'] )
require 'template/tp.foot.php';
?>