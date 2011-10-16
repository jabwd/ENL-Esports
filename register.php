<?php
require 'config.php';

if( $userSession )
{
	header("location: http://www.enl-esports.com/");
	echo 'You are logged in.';
	exit();
}

$pageName = 'Register';

if( $_POST['register'] )
{
	$username = strtolower(secureInput($_POST['username']));
	if( strlen($email) > 40 )
	{
		$errors[] = 'The username is too long';
	}
	else if( strlen($username) < 1 )
	{
		$errors[] = 'The username field is required';
	}
	
	$password 	= passwordHash($_POST['password']);
	$password2 	= passwordHash($_POST['password2']);
	$slacID		= secureInput($_POST['slacID']);
	$nickname 	= secureInput($_POST['nickname']);
	$email		= secureInput($_POST['email']);
	
	if( strlen($_POST['password']) < 1 )
	{
		$errors[] = 'A password is required, you don\'t want everyone to be able to login to your account do you?';
	}
	
	if( strlen($email) > 100 )
		$errors[] = 'The email you entered is too long';
	
	if( $slacID < 1 )
	{
		$errors[] = 'The TZAC id you entered is incorrect';
	}
	
	if( strlen($slacID) != 8 )
	{
		$errors[] = 'The TZAC id you entered is incorrect, it should be 8 numbers long';
	}
	
	// check for duplicate accounts
	$query = mysql_query("SELECT * FROM `users` WHERE email='".$email."' OR slacID='".$slacID."' OR username='".$username."'");
	if( mysql_num_rows($query) > 0 )
	{
		$row = mysql_fetch_array($query);
		if( $row['username'] == $username )
			$errors[] = $username.' is already in use';
		
		if( $row['slacID'] == $slacID )
			$errors[] = 'The TZAC ID '.$slacID.' is already in use';
		
		if( $row['email'] == $email )
			$errors[] = 'The email address '.$email.' is already in use!';
	}
	
	// using the post here as the nickname + htmlspecialchars could result in a larger
	// string then the user actually entered, therefore allowing the field in the database
	// to be longer than 20 characters
	if( strlen($_POST['nickname']) > 20 )
	{
		$errors[] = 'The nickname you entered is too long, try again';
	}
	
	if( $password != $password2 )
	{
		$errors[] = 'The passwords you entered do not match, try again';
	}
	
	if( ! $errors )
	{
		if( ! mysql_query("INSERT INTO users (`username`,`password`,`slacID`,`nickname`,`email`) VALUES ('".$username."','".$password."','".$slacID."','".$nickname."','".$email."') ") )
		{
			$errors[] = 'A server error occured, try again later or contact support';
		}
		else
		{
			$success = 'Successfully registered, you can now login <a href="'.$baseURL.'login/">here</a>';
		}
	}
}

if( !$_GET['ajax'] )
require 'template/tp.head.php';
require 'template/tp.register.php';
if( !$_GET['ajax'] )
require 'template/tp.foot.php';
?>