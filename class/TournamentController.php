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

require_once 'class/Tournament.php';

class TournamentController
{
	var $tournaments;
	
	function TournamentController($load = true)
	{
		if( $load )
		{
			$query = mysql_query("SELECT `tournamentID`,`tournamentName`,`allowSignups` FROM `tournaments`");
			if( $query )
			{
				while( $row = mysql_fetch_array($query) )
				{
				
					$tournament = new Tournament();
					
					$tournament->tournamentID 	= $row['tournamentID'];
					$tournament->tournamentName = stripslashes($row['tournamentName']);
					$tournament->allowSignups	= (int)$row['allowSignups'];
				
					$this->tournaments[] = $tournament;
				}
			}
		}
	}
}
?>