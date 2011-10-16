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
require_once 'class/Team.php';
require_once 'class/Ladder.php';

class Match
{
	var $matchID;
	var $winner;
	var $loser;
	var $challenger;
	var $opponent;
	var $ladder;
	var $challengerScore;
	var $opponentScore;
	var $challengerAccepted;
	var $opponentAccepted;
	var $points;
	var $map;
	var $dueDate;
	
	function Match($p_matchID)
	{
		if( $p_matchID != 0 )
		{
			$query = mysql_query("SELECT * FROM `matches`,`ladders` WHERE `matches`.`ladderID`=`ladders`.`ladderID` AND `matches`.`matchID`=".(int)$p_matchID);
			if( $query )
			{
				$row 						= mysql_fetch_array($query);
				
				
				$this->matchID				= (int)$p_matchID;
				$this->challenger 			= new Team($row['challengerID']);
				$this->opponent	 			= new Team($row['opponentID']);
				$this->dueDate				= $row['dueDate'];
				$this->points				= $row['pointsEarned'];
				$this->opponentScore 		= $row['opponentScore'];
				$this->challengerScore 		= $row['challengerScore'];
				$this->challengerAccepted 	= $row['challengerAccepted'];
				$this->opponentAccepted 	= $row['opponentAccepted'];
				$this->map					= $row['map'];
				
				
				
				// create the ladder object
				$this->ladder 					= new Ladder();
				$this->ladder->ladderName 		= $row['ladderName'];
				$this->ladder->ladderID	 		= $row['ladderID'];
				$this->ladder->ladderPlayers 	= $row['ladderPlayers'];
				
				// determine who is the winner and who is the loser
				if( $row['winnerID'] == $this->challenger->teamID )
				{
					$this->winner 	= $this->challenger;
					$this->loser 	= $this->opponent;
				}
				else if( $row['winnerID'] != 0 )
				{
					$this->winner 	= $this->opponent;
					$this->loser	= $this->challenger;
				}
				
				// some checks
				if( strlen($this->challenger->teamName) < 1 )
					$this->challenger->teamName = '<i>Deleted team</i>';
			}
			else
				ENLog("There is an error in your SQL syntax, please check your script ".$_SERVER['SCRIPT_NAME']);
		}
	}
}
?>