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

class MessageController
{
	function sendPrivateMessage($to,$subject,$content)
	{
		// login is required, otherwise we can't have a sender!
		$userSession = unserialize($_SESSION['user']);
		if( ! $userSession )
			return;
		
		$toID = (int)$to;
			
		// sanitize the user input, take this strain away from the other logic.
		$subject 	= secureInput($subject);
		$content 	= mysql_real_escape_string(nl2br($content));
		$toUsername = secureInput($to);
	
		if( strlen($subject) > 40 )
		{
			$errors[] = 'Your subject is too long ( please do not modify the html, thanks ;) )';
		}
	
		if( strlen($subject) < 1 )
		{
			$subject = 'No subject';
		}
	
		if( strlen($content) > 6000 )
		{
			$errors[] = 'Your content is too long, remove '.(strlen($content)-6000).' characters';
		}
	
		if( strlen($content) < 10 )
		{
			$errors[] = 'Your content needs to be atleast 10 characters long';
		}
	
		$query = mysql_query("SELECT `userID` FROM `users` WHERE `username`='".$toUsername."'");
		$row = mysql_fetch_array($query);
	
		if( ! $row['userID'] && $toID == 0 )
			$errors[] = 'The user '.$toUsername.' does not exist!';
		
		if( ! $errors )
		{
			if( $toID > 0 )
			{
				mysql_query("INSERT INTO `messages` (`fromID`,`userID`,`subject`,`content`) VALUES (".(int)$userSession->userID.",".$toID.",'".$subject."','".$content."')");
				return 'Your message was sent!';
			}
			else if( mysql_query("INSERT INTO `messages` (`fromID`,`userID`,`subject`,`content`) VALUES (".(int)$userSession->userID.",".$row['userID'].",'".$subject."','".$content."')") )
			{
				return 'Your message was sent!';
			}
		}
		
		// this is only reached when errors is actually set.
		// this array should be handled in whatever logic was calling
		// the function in the first place.
		return $errors;
	}
}

?>