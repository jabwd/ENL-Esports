<?php
require 'config.php';

checkLogin();

if( $_POST['sendMessage'] )
{
	$subject = secureInput($_POST['subject']);
	$content = mysql_real_escape_string(nl2br($_POST['content']));
	$toUsername = secureInput($_POST['username']);
	
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
	
	if( ! $row['userID'] )
		$errors[] = 'The user '.$toUsername.' does not exist!';
	
	if( ! $errors )
	{
		if( mysql_query("INSERT INTO `messages` (`fromID`,`userID`,`subject`,`content`) VALUES (".(int)$userSession->userID.",".$row['userID'].",'".$subject."','".$content."')") )
		{
			$success = 'Your message has been sent';
		}
	}
	
	if( $success && ! $errors )
		unset($_POST);
}

require 'template/tp.head.php';
require 'template/tp.createMessage.php';
require 'template/tp.foot.php';
?>