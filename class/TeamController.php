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
require_once 'lib/std.php';

class TeamController
{
	var $teams;
	var $yourTeams;
	
	function TeamController()
	{
	}
	
	
	// use this to determine whether the current user is part of any teams
	// provide a minimum admin level if you want to validate his admin status
	function yourTeams($adminLevel = 0)
	{
		$userSession = unserialize($_SESSION['user']);
		if( ! $this->yourTeams )
		{
			$this->yourTeams = array();
			
			$query = mysql_query("SELECT `teams`.`tag`,`level`,`teams`.`teamID`,`teams`.`teamName` FROM `team_members`,`teams` WHERE `team_members`.`userID`=".(int)$userSession->userID." AND `teams`.`teamID`=`team_members`.`teamID`");
			while( $row = mysql_fetch_array($query) )
			{
				if( $row['level'] >= $adminLevel)
				{
					$team = new Team();
					
					$team->teamName = stripslashes($row['teamName']);
					$team->teamID	= (int)$row['teamID'];
					$team->tag		= stripslashes($row['tag']);
					
					$this->yourTeams[] = $team;
				}
			}
		}
		return $this->yourTeams;
	}
	
	
	// this function signs a team up for a tournament
	function addToTournament($teamID,$tournamentID)
	{
		$userSession = unserialize($_SESSION['user']);
		// first validate that the user is a team ADMIN
		$query 	= mysql_query("SELECT `level` FROM `team_members` WHERE `teamID`=".(int)$teamID." AND `userID`=".(int)$userSession->userID);
		$row  	= mysql_fetch_array($query);
		if( $row['level'] >= 10 )
		{
			// the user is an admin, now add the team to the tournament
			$query = mysql_query("SELECT `allowSignups` FROM `tournaments` WHERE `tournamentID`=".(int)$tournamentID);
			$row = mysql_fetch_array($query);
			if( $row['allowSignups'] > 0 )
			{
				// TODO: Make sure that the team has enough members to play in this tournament
				
				
				$infoQuery = mysql_query("SELECT * FROM `tournament_teams` WHERE `teamID`=".(int)$teamID);
				while($newRow = mysql_fetch_array($infoQuery))
				{
					if( $newRow['tournamentID'] == $tournamentID )
					{
						$errors[] = 'That team is already signed up for this tournament';
						return $errors;
					}
				}
				// the ladder still allows signups
				if( mysql_query("INSERT INTO `tournament_teams` (`teamID`,`tournamentID`) VALUES (".(int)$teamID.",".(int)$tournamentID.")") )
					return 'You signed your team up for the tournament!';
				else
					$errors[] = 'A server error occured, please try again later';
			}
			else
				$errors[] = 'The ladder no longer allows sign ups';
		}
		else
			$errors[] = 'You do not have enough permissions to sign up your team for a tournament';
		
		return $errors;
	}
}
?>