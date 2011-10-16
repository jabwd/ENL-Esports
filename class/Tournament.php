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

require_once 'class/BBParser.php';

class Tournament
{
	var $tournamentID;
	var $tournamentName;
	var $allowSignups;
	var $rules;
	var $winnerID;
	
	var $teams;
	
	function Tournament($p_id)
	{
		if( $p_id != 0 )
		{
			$query = mysql_query("SELECT `minPlayers`,`winnerID`,`tournamentID`,`tournamentName`,`rules`,`allowSignups` FROM `tournaments` WHERE `tournamentID`=".(int)$p_id);
			if( $query )
			{
				$row = mysql_fetch_array($query);
				
				$this->tournamentID 		= (int)$p_id;
				$this->tournamentName 		= stripslashes($row['tournamentName']);
				$this->allowSignups			= (int)$row['allowSignups'];
				$parser = new BBParser();
				$this->rules				= $parser->parse(nl2br(stripslashes($row['rules'])));
				$this->winnerID				= (int)$row['winnerID'];
				$this->minPlayers			= (int)$row['minPlayers'];
			}
		}
	}
	
	// this function queries the database for all the teams that have signed up for
	// this tournament, saves them to the memory for faster lookup next time.
	function teams()
	{
		if( ! $this->teams )
		{
			$this->teams = array();
			$query = mysql_query("SELECT `teams`.`teamID`,`teams`.`teamName` FROM `teams`,`tournament_teams` WHERE `tournament_teams`.`tournamentID`=".$this->tournamentID." AND `teams`.`teamID`=`tournament_teams`.`teamID`");
			while( $row = mysql_fetch_array($query) )
			{ 
				$team = new Team();
				
				$team->teamID 	= $row['teamID'];
				$team->teamName = $row['teamName'];
				$this->teams[] = $team;
			}
		}
		return $this->teams;
	}
	
	
	function winningTeam()
	{
		if( $this->winnerID > 0 )
		{	
			return new Team($this->winnerID);
		}
	}
	
	function setWinningTeam($teamID)
	{
		// you can't re-set the winner of this tournament.
		if( $teamID != 0 && $this->winnerID == 0 )
		{
			mysql_query("UPDATE `tournaments` SET `winnerID`=".(int)$teamID." WHERE `tournamentID`=".$this->tournamentID);
			$this->winnerID = $teamID;
		}
	}
	
	
	// toggle the status whether the tournament allows signups or not.
	// this should only be called by league admins or tournament admins
	function toggleSignup()
	{
		if( $this->allowSignups )
		{
			mysql_query("UPDATE `tournaments` SET `allowSignups`=0 WHERE `tournamentID`=".$this->tournamentID);
			$this->allowSignups = 0;
			
			// now make sure that the correct stages are set for the tournament
			mysql_query("DELETE FROM `tournament_stages` WHERE `tournamentID`=".$this->tournamentID);
			
			$count = count($this->teams());
			$stages = 1;
			while(!($count%2))
			{
				$stages++;
				$count /= 2;
			}
			if( $count == 1 )
			{
				$stageNumber = 0;
				while($stages > 0 )
				{
					$stages--;
					$stageNumber++;
					mysql_query("INSERT INTO `tournament_stages` (`tournamentID`,`stageNumber`) VALUES (".$this->tournamentID.",".$stageNumber.")");
				}
			}
		}
		else
		{
			mysql_query("UPDATE `tournaments` SET `allowSignups`=1 WHERE `tournamentID`=".$this->tournamentID);
			$this->allowSignups = 1;
		}
	}
}
?>