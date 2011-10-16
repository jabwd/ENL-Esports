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

class Ladder
{
	var $ladderName;
	var $ladderID;
	var $ladderPlayers;
	
	function Ladder($p_ladderID)
	{
		if( $p_ladderID != 0 )
		{
			$query 	= mysql_query("SELECT `ladderName`,`ladderID`,`ladderPlayers` FROM `ladders` WHERE `ladderID`=".(int)$p_ladderID);
			$row 	= mysql_fetch_array($query);
			
			$this->ladderName		= $row['ladderName'];
			$this->ladderID			= $row['ladderID'];
			$this->ladderPlayers 	= $row['ladderPlayers'];
		}
	}
}
?>