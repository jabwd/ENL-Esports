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


class TzacQuery
{
	var $tzacID;
	var $cheatStatus;
	var $username;
	
	function TzacQuery($p_tzacID = 0)
	{
		$content = file_get_contents("http://tz-ac.com/profile.php?id=".(int)$p_tzacID);
		
		if( strpos($content,"A user with the specified ID has not been found.") > 0 )
		{
			$this->tzacID = 0;
			// this means that the user profile was not found.
			return;
		}
		
		// get the username associated with the tzacID
		// this div has an ID so it should be the only one in the HTML source..
		// hopefully chaplja knows how to write some decent HTML ;)
		$usernamePos = strpos($content,"<div id=\"box3\" class=\"box-style3\">
							<h2 class=\"title\">");
		$usernamePos += 60;
		$usernameLen = strpos($content,"'s TZAC user") - $usernamePos;
		
		
		
		// get the position of the cheat status in the HTML Content
		$position = strpos($content,"<strong>Anti-cheat status:</strong>");
		$position += 35;
		$len		= strpos($content,"<br />
															<strong>Used cheats in:") - $position;
															
		// this would mean the cheat status is "OK" and therefore "used cheats in" won't exist
		if( $len <= 0 )
		{
			$len		= strpos($content,"<br />
															<strong>Account status:") - $position;
		}
		
		
		
		$this->tzacID 		= $p_tzacID;
		$this->cheatStatus 	= substr($content,$position,$len);
		$this->username 	= substr($content,$usernamePos,$usernameLen);
	}
}
?>