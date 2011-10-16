<?php
require 'config.php';

if( $_GET['key'] )
{
	$query = mysql_query("SELECT * FROM `users` WHERE `userID`=".(int)$_GET['userID']);
	$row = mysql_fetch_array($query);
	if( sha1($row['email'].":D") == $_GET['key'] )
	{
		$password = substr(md5(time()),0,5);
		$passwordHash = passwordHash($password);
		mysql_query("UPDATE `users` SET `password`='".$passwordHash."' WHERE `userID`=".(int)$_GET['userID']);
		mail($row['email'],"Password was reset","You have resetted your password on ENL to: ".$password."\n\nIf you have any further questions feel free to contact an ENL admin through admin@enl-esports.com","From: no-reply@enl-esports.com");
		$success = 'Your password has been reset';
	}
}
else if( strlen($_GET['username']) < 1 )
{
	
}
else
{
	$query = mysql_query("SELECT * FROM `users` WHERE `username`='".secureInput($_GET['username'])."'");
	$row = mysql_fetch_array($query);
	$email = $row['email'];
	if( strlen($email) < 5 )
	{
		$error = 'That account is not associated with an email address. You can try contacting an ENL admin on our IRC channel or by e-mailing admin@enl-esports.com';
	}
	else
	{
		mail($email, "Password reset", "Someone has requested to reset the password of your ENL account registered with ".$email.". If it was not you you can simply ignore this email. \n\nClick on this link to reset your password: http://www.enl-esports.com/iforgot.php?key=".sha1($email.":D")."&userID=".$row['userID'],"From: no-reply@enl-esports.com");
		
	}
}

require 'template/tp.head.php';
require 'template/tp.iforgot.php';
require 'template/tp.foot.php';
?>