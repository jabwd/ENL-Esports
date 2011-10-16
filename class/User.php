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


class User
{
	var $username;
	var $nickname;
	var $userID;
	var $slacID;
	var $cheater;
	var $preferredClass;
	var $preferredFormat;
	var $cless;
	var $creationDate;
	var $adminLevel;
	var $profileVisits;
	var $avatar;
	var $email;
	var $securityQuestion;
	var $securityAnswer;
	var $homepage;
	var $xfire;
	
	function User($p_id)
	{
		if( $p_id )
		{
			$query = mysql_query("SELECT * FROM `users` WHERE `userID`=".(int)$p_id);
			if( $query )
			{
				$_rowArray = mysql_fetch_array($query);
				
				$this->username 		= stripslashes($_rowArray['username']);
				$this->nickname 		= stripslashes($_rowArray['nickname']);
				$this->userID	  		= (int)$_rowArray['userID'];
				$this->slacID  			= (int)$_rowArray['slacID'];
				$this->cheater			= 0; // to be replaced by something else
				$this->preferredClass 	= (int)$_rowArray['preferredClass'];
				$this->preferredFormat 	= (int)$_rowArray['preferredFormat'];
				$this->cless			= (int)$_rowArray['cless'];
				//$this->creationDate		= $_rowArray['creationDate'];
				$this->adminLevel		= (int)$_rowArray['adminLevel'];
				$this->profileVisits	= (int)$_rowArray['profileVisits'];
				$this->avatar			= $_rowArray['avatar'];
				$this->email			= stripslashes($_rowArray['email']);
				$this->securityQuestion = stripslashes($_rowArray['securityQuestion']);
				$this->securityAnswer	= stripslashes($_rowArray['securityAnswer']);
				$this->homepage			= stripslashes($_rowArray['homepage']);
				$this->xfire			= stripslashes($_rowArray['xfire']);
				
				// modify the creation date to something that is more general to our region
				$time = strtotime($_rowArray['creationDate']);
				if( $time < 1000 )
					$this->creationDate = $_rowArray['creationDate']; // in case something goes wrong use the default behaviour
				
				$this->creationDate = date("d F Y",$time);
			}
		}
	}
	
	function displayName()
	{
		if( strlen($this->nickname) > 0 )
		{
			if( $this->adminLevel >= 10 )
				return 'ENL\\'.$this->nickname;
			return $this->nickname;
		}
		return $this->username;
	}
	
	/****************************************/
	/***** Basic setters *******************/
	function setNickname($p_new)
	{
		$this->nickname = $nickname;
	}
	
	function setCless($p_new)
	{
		$this->cless = $p_new;
	}
	
	function setPreferredClass($p_new)
	{
		$this->preferredClass = $p_new;
	}
	
	function setPreferredFormat($p_new)
	{
		$this->preferredFormat = $p_new;
	}
	
	function setAdminLevel($p_new)
	{
		if( $_SESSION['user']['adminLevel'] >= 10 )
		{
			$this->adminLevel = (int)$p_new;
		}
	}
	/***************************************/
	
	
	
	/* When we are done making changes to the object we can finish */
	function saveChanges()
	{
		// only when the user is properly authenticated
		if( $_SESSION['user']['userID'] == $this->userID || $_SESSION['user']['adminLevel'] >= 10 )
			mysql_query("UPDATE `users` SET `adminLevel`=".$this->adminLevel.",`nickname`='".secureInput($this->nickname)."',`cless`=".(int)$this->cless.",`preferredClass`=".(int)$this->preferredClass.",`preferredFormat`=".(int)$this->preferredFormat." WHERE `userID`=".(int)$this->userID);
	}
	
	
	
	
	// this function determines whether this user is allowed to play a match on ENL
	// simply by querying the database for any such information
	function canPlay()
	{
		$query = mysql_query("SELECT `reason`,`expirationDate` FROM `bans` WHERE `userID`=".$this->userID);
		if( $query )
		{
			$row = mysql_fetch_array($query);
			if( strtotime($row['expirationDate']) > time() )
				return false;
			else
				return true;
		}
	}
	
	
	
	// this function will return the reason,expirationDate,creationDate when a user is banned
	function isBanned()
	{
		$query = mysql_query("SELECT `reason`,`creationDate`,`expirationDate` FROM `bans` WHERE `userID`=".$this->userID);
		while( $row = mysql_fetch_array($query) )
		{
			if( strtotime($row['expirationDate']) < time() )
			{
				continue; // skip this ban
			}
			$output[] = $row;
		}
		return $output;
	}
	
	
	// increments the current amount of profile visits in the database
	function incrementProfileVisits()
	{
		mysql_query("UPDATE `users` SET `profileVisits`=(`profileVisits`+1) WHERE `userID`=".$this->userID);
		$this->profileVisits++;
	}
}
?>