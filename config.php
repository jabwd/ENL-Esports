<?php
// Wolfenstein: Enemy Territory League
// Copyright © 2011 Antwan van Houdt
//******************************************************************************
//	  This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//******************************************************************************
//
// Some notes:
// The code of this website is not always as clean as I wanted it to be.
// I am still working on most of the things, so hopefully I will get round to
// cleaning up parts soon as well.
//
// I do not like using smalltalk as much as it makes it harder to read the code
// for people that don't know the code
//


// if the server doesn't display errors by default, uncomment if a script fails to run.
// This is _debug_ only and should never be enabled in releases
//ini_set("display_errors","1");

// this variable is used to calculate the amount of time the script needed to execute
$_startTime = microtime(true);


$baseURL 		= 'http://www.enl-esports.com/';
$saltString 	= '..191297ehtehaeunth';
$siteVersion	= '1.0.11';
$baseTitle		= "ENL - %s";
$pageName		= "";
$themeKey 		= 'midnight-blue'; // midnight-blue

$databasePassword = '';
$databaseUsername = 'root';

$LevelAdmin 	= 10;
$LevelModerator = 5;
$LevelPlayer 	= 0;

// connect to the database, almost every page needs it anyways
if( ! $mysqlConnection = mysql_connect("localhost",$databaseUsername,$databasePassword) )
	ENLog("Unable to connect to mysql server: ".mysql_error());
if( ! mysql_select_db("wolfenstein",$mysqlConnection) )
	ENLog("Unable to select database wolfenstein");


// make sure that the user is not banned on this website:
$banQuery = mysql_query("SELECT `IP` FROM `ip_ban` WHERE `IP`='".$_SERVER['REMOTE_ADDR']."'");
if( $banQuery )
{
	if( mysql_num_rows($banQuery) > 0 )
	{
		// TEMP Error message, doesn't really look properly but this works..
		die("You are banned on ENL-ESports.com, appeal this ban by going to our IRC channel on quakenet OR email the system administrator: jabwdr@gmail.com");
	}
}


// set up a secure session:
session_start();
if( $_SESSION['secureSessionKey'] )
{	
	if( $_SESSION['secureSessionKey'] != md5($_SERVER['REMOTE_ADDR']."929D:0(7".$_SERVER['HTTP_USER_AGENT']) )
	{
		// red alert: this session has been hi-jacked
		session_destroy();
		exit();
	}
}
else
{
	$_SESSION['secureSessionKey'] = md5($_SERVER['REMOTE_ADDR']."929D:0(7".$_SERVER['HTTP_USER_AGENT']);
}

// these are some basic "libraries" we always will need
require_once 'lib/std.php';
require_once 'class/User.php';
require_once 'class/Team.php';
require_once 'class/UserController.php';

if( $_SESSION['user'] )
{
	$userSession = unserialize($_SESSION['user']);
	
	// check for unread messages
	$not_query = mysql_query("SELECT `content`,`messageID`,`subject`,`seen`,`read` FROM `messages` WHERE `userID`=".$userSession->userID." AND `read`=0");
	if( $not_query )
	{
		while($row = mysql_fetch_array($not_query))
		{
			if( $row['seen'] == 0 )
			{
				mysql_query("UPDATE `messages` SET seen=1 WHERE `messageID`=".(int)$row['messageID']);
				$notifications[] = $notification;
			}
			$messagesCount++;
		}
	}
}

if( $_COOKIE['username'] && ! $userSession  )
{
	$query = mysql_query("SELECT `password`,`userID` FROM `users` WHERE `userID`='".secureInput($_COOKIE['username'])."'");
	$row = mysql_fetch_array($query);
	if( $row )
	{
		if( passwordHash($row['password'].$_SERVER['REMOTE_ADDR']) == $_COOKIE['ultimate'] )
		{
			mysql_query("INSERT INTO `users_ip` (`IP`,`userID`) VALUES ('".secureInput($_SERVER['REMOTE_ADDR'])."',".$row['userID'].")");
			$userSession = new User($row['userID']); // don't self fill here: obvious optimization reason but still do not do it.
			$_SESSION['user'] = serialize($userSession);
		}
	}
}

header('Access-Control-Allow-Origin: *');
?>