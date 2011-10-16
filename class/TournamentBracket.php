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

class TournamentBracket
{
	var $tournamentID;
	var $teams;
	var $matches;
	
	function TournamentBracket($tournamentID = 0)
	{
		if( $tournamentID != 0 )
		{
			$this->tournamentID = (int)$tournamentID;
			$this->teams 		= array();
			
			$query = mysql_query("SELECT `teams`.`tag`,`teams`.`teamID`,`teams`.`creationDate`,`teams`.`teamName` FROM `tournament_teams`,`teams` WHERE `tournament_teams`.`tournamentID`=".$this->tournamentID." AND `teams`.`teamID`=`tournament_teams`.`teamID`");
			while( $row = mysql_fetch_array($query) )
			{
				$team = new Team();
				
				$team->teamID = $row['teamID'];
				$team->creationDate = $row['creationDate'];
				$team->teamName = stripslashes($row['teamName']);
				$team->tag		= stripslashes($row['tag']);
				
				$this->teams[] = $team;
			}
			
			$query = mysql_query("SELECT * FROM `tournament_stages`,`tournament_matches` WHERE `tournament_matches`.`tournamentID`=".$this->tournamentID." AND `tournament_stages`.`stageNumber`=`tournament_matches`.`stageID`");
			while( $row = mysql_fetch_array($query) )
			{
				$this->matches[] = $row;
			}
		}
	}
	
	// this method returns the bracket output for the current tournament
	// this output is used in the Brackets class
	function bracketOutput()
	{
		$output = array();
		$stagesCount = (count($this->teams) / 2 );
		$stagesCount++;
		$teamsBuffer = $this->teams;
		
		// the temporary buffer is used to store the array of teams that are in the next stage
		// this way it is easy to find out what teams should be in the next "wave".
		$tempBuffer;
		$matches = $this->matches;
		if( ! $matches ) $matches = array();
		for($i=0;$i<$stagesCount;$i++)
		{
			$teams = array();
			
			$count = count($teamsBuffer);
			for($r=0;$r<$count;$r++)
			{
				$team = $teamsBuffer[$r];
				$_team = array();
				
				if( strlen($team->tag) > 0 )
					$_team['name'] = $team->tag;
				else
					$_team['name'] = $this->clippedTeamName($team->teamName);
				$_team['score'] = '--';
				$_team['teamID'] = $team->teamID;
				
				// handle all the matches for this stage
				foreach($matches as $match)
				{
					if( $match['stageNumber'] == ($i+1) )
					{
						// determine whether the match is for this team
						// rather using 2 seperate IF statements for this so
						// its easier to get the score out of the array as well..
						if( $match['team1ID'] == $team->teamID )
						{
							$_team['score'] = $match['team1Score'];
						}
						else if( $match['team2ID'] == $team->teamID )
						{
							$_team['score'] = $match['team2Score'];
						}
						
						// the team that won is the team that will go on to the next array!
						if( $match['winnerID'] == $team->teamID )
						{
							$tempBuffer[] = $team;
							unset($team);
						}
					}
				}
				
				$teams[] = $_team;
			}
			// copy over the teams for the next stage!
			unset($teamsBuffer);
			$teamsBuffer = $tempBuffer;
			unset($tempBuffer);
			
			
			$output[($i+1)] = $teams;
		}
		return $output;
	}
	
	
	
	// this function is used to make sure that the name
	// of the team will never overflow its container	
	private function clippedTeamName($p_teamName)
	{
		if( $p_teamName )
		{
			$teamName = $p_teamName;
			if( strlen($teamName) > 10 )
			{
				$teamName = substr($teamName, 0, 10);
				$teamName .= '..'; // make it feel wrapped
			}
			return $teamName;
		}
	}
}
?>