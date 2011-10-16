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
require_once 'class/User.php';

class UserController
{
	var $users;
	
	function loadUserList($lowerLimit = 0,$upperLimit = 0)
	{
		if( $upperLimit > 0 )
		{
			$sql = "SELECT * FROM `users` LIMIT ".$lowerLimit.",".$upperLimit;
		}
		else
		{
			$sql = "SELECT `username`,`nickname`,`userID`,`cless`,`creationDate` FROM `users`";
		}
		$query = mysql_query($sql);
		if( $query )
		{
			while($row = mysql_fetch_array($query))
			{
				$user = new User();
				
				$user->username 	= stripslashes($row['username']);
				$user->nickname		= stripslashes($row['nickname']);
				$user->userID		= (int)$row['userID'];
				$user->cless		= (int)$row['cless'];
				$user->creationDate = (int)$row['creationDate'];
				
				$this->users[] = $user;
			}
		}
	}
	
	
	// this function is used for logging in, also fills this object with data
	// it receives from the database.
	function authenticate($p_username,$p_password)
	{
		$username = strtolower(secureInput($p_username));
		$password = passwordHash($p_password);
		
		if( strlen($username) > 100 )
		{
			ENLog("username is too long");
			return false;
		}
			
		// the password is a sha1 hash so it should always be 40 characters long,
		// this method should actually be useless.. but just in case.
		if( strlen($password) != 40 )
		{
			ENLog("password is not 40 characters");
			return false;
		}
			
		$query 		= mysql_query("SELECT * FROM `users` WHERE `username`='".$username."'");
		$_rowArray 	= mysql_fetch_array($query);
		if( $_rowArray['password'] == $password )
		{
			$userObject = new User();
			// make sure that we log this login, we might want to use his IP to ban him again!
			mysql_query("INSERT INTO `users_ip` (`IP`,`userID`) VALUES ('".secureInput($_SERVER['REMOTE_ADDR'])."',".$_rowArray['userID'].")");
			
			// fill up this object with the correct values
			$userObject->username 			= stripslashes($_rowArray['username']);
			$userObject->nickname 			= stripslashes($_rowArray['nickname']);
			$userObject->userID	  			= (int)$_rowArray['userID'];
			$userObject->slacID  			= (int)$_rowArray['slacID'];
			$userObject->suspended			= (int)$_rowArray['suspensionID'];
			$userObject->preferredClass 	= (int)$_rowArray['preferredClass'];
			$userObject->preferredFormat 	= (int)$_rowArray['preferredFormat'];
			$userObject->cless				= (int)$_rowArray['cless'];
			$userObject->creationDate		= $_rowArray['creationDate'];
			$userObject->adminLevel			= (int)$_rowArray['adminLevel'];
			$userObject->email				= stripslashes($_rowArray['email']);
			$userObject->avatar				= $_rowArray['avatar'];
			$userObject->homepage			= stripslashes($_rowArray['homepage']);
			$userObject->xfire				= stripslashes($_rowArray['xfire']);
			
			return $userObject;
		}
		else
		{
			ENLog("Password does not match: ".$_rowArray['password']." - ".$password);
		}
		
		// once we get till here the login will have failed
		return false;
	}
	
	
	// registering users
	function register()
	{
		// tbd.
	}
}
?>