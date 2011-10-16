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


// this function is used to create a safe hash for the passwrods in the database
// this way there is no way you can reverse engineer the password by just having the hash
// a database dump will never be able to tell someone what the real password is.
function passwordHash($p_password)
{
	return sha1($saltString.'pass2'.$p_password.'lol');
}



// this function makes sure that the user has been authenticated with the website
// this can be used for pages that require you to have an account
// reason for writing this function is extreme lazyness
function checkLogin($levelCheck = 0)
{
	$userObject = unserialize($_SESSION['user']);
	if( $userObject->adminLevel < $levelCheck )
	{
		header("location: http://www.enl-esports.com/");
		exit();
	}
	if( ! $userObject->username )
	{
		header("location: http://www.enl-esports.com/login/");
		exit();
	}
}

function ENLog($p_input)
{
	file_put_contents("enl.log",$p_input."\n",FILE_APPEND);
}

// this function executes a mysql query, use this function if you want to make sure that the site
// counts the amount of queries you execute. It is also very useful for debug reasons, might something be wrong
// in your SQL syntax than it will be reported to the log file
$queryCount = 0;
function sql_exec($queryString)
{
	$queryCount++;
	if( mysql_query($queryString) )
	{
		return true;
	}
	else
	{
		ENLog("A MYSQL Error occured with query \"".$queryString."\": ".mysql_error());
	}
	return false;
}



// This function makes sure that any input
// is safe to use together with a mysql_query string
function secureInput($p_input)
{
	return mysql_real_escape_string(strip_tags(trim($p_input)));
}



// converts a level number to the appropriate string
// this is used in the GUI
function levelToString($level)
{
	switch($level)
	{
		case 10:
			return "Team admin";
		break;
		
		case 5:
			return "Team moderator";
		break;
		
		case 0:
			return "Team member";
		break;
	}
}


// this function turns a preferredClass id
// into a string.
function classToString($thingie)
{
	switch($thingie)
	{
		case 1:
			return "Soldier";
		break;
		
		case 2:
			return "Injecting medic";
		break;
		
		case 3:
			return "Rambo medic";
		break;
		
		case 4:
			return "Ninja engineer";
		break;
		
		case 5:
			return "Rifle engineer";
		break;
		
		case 6:
			return "Fieldops";
		break;
		
		case 7:
			return "Fieldops";
		break;
		
		case 8:
			return "Covertops";
		break;
	}
	return "Unknown class".$thingie;
}


// this function turns a preferredformat id to a string
function formatToString($thingie)
{
	switch($thingie)
	{
		case 1:
			return "1on1";
		break;
		
		case 2:
			return "2on2";
		break;
		
		case 3:
			return "3on3";
		break;
		
		case 6:
			return "6on6";
		break;
	}
	return "Unknown class".$thingie;
}



// this function turns a level id to a string
function skillLevelToString($level)
{
	switch($level)
	{
		case 1:
			return "low";
		break;
		
		case 2:
			return "low+";
		break;
		
		case 3:
			return "med-";
		break;
		
		case 4:
			return "med";
		break;
		
		case 5:
			return "med+";
		break;
		
		case 6:
			return "High skilled";
		break;
	}
	return "Unknown skill level";
}


// used for the team members history
function teamActionToString($action)
{
	switch($action)
	{
		case 0:
			return 'Joined';
		break;
		
		case 1:
			return 'Left';
		break;
		
		case 2:
			return 'Kicked';
		break;
	}
	return "Unknown action";
}
?>