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
// In some cases you might find obvious situations where you can use smalltalk.
// However in my opinion code readability is more important than smalltalk.

require_once 'lib/std.php';
require_once 'class/User.php';

class Team
{
	var $teamID;
	var $teamName;
	var $joinPassword;
	var $creationDate;
	var $tag;
	var $channel;
	var $homepage;
	
	function Team($p_teamID)
	{
		if( $p_teamID != 0 )
		{
			$query = mysql_query("SELECT `homepage`,`ircChannel`,`tag`,`teamID`,`teamName`,`joinPassword`,`creationDate` FROM `teams` WHERE `teamID`=".(int)$p_teamID);
			if( $query )
			{
				$row = mysql_fetch_array($query);
				if( $row )
				{
					$this->teamID 		= (int)$row['teamID'];
					$this->teamName 	= stripslashes($row['teamName']);
					$this->joinPassword = $row['joinPassword'];
					$this->creationDate = $row['creationDate'];
					$this->tag			= stripslashes($row['tag']);
					$this->channel		= stripslashes($row['ircChannel']);
					$this->homepage		= stripslashes($row['homepage']);
				}
			}
			else
				die("Fatal error occured, please contact the system administrator.");
		}
	}
	
	// this function is not done yet, and should be finished
	function addMember($p_user,$p_password)
	{
		if( $p_user->userID != 0 )
		{
			$password = passwordHash($p_password);
			$user	  = (int)$p_user;
	
			$query = mysql_query("SELECT `userID` FROM `team_members` WHERE `teamID`='".(int)$this->teamID."'");
			if( $query )
			{
				while($row= mysql_fetch_array($query))
				{
					if( $row['userID'] == $p_user->userID )
					{
						$errors[] = 'You are already a member of this team';
						break;
					}
				}
			}
	
			if( ! $errors )
			{
				if( $this->joinPassword == $password )
				{
					// the password is correct, the player can join the team
					if( mysql_query("INSERT INTO `team_members` (`teamID`,`userID`) VALUES (".(int)$this->teamID.",".(int)$p_user->userID.")") )
					{
						$success 			= 'Successfully joined the team '.$this->teamName;
						$newRow['user']		= $p_user;
						$newRow['teamID'] 	= $currentTeam['teamID'];
						$newRow['level'] 	= 0;
						$members[] 			= $newRow;
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
			
			if( $success && ! $errors )
				return $success;
			else
				return $errors;
		}
	}
	
	function removeMember($p_user)
	{
		ENLog("Team::removeMember is not implemented yet");
	}
}
?>