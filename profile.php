<?php
require 'config.php';

checkLogin();

$loadExtra = 'loadTZACID('.$userSession->slacID.',\'tzacStatus\');hideBox();';

$pageName = 'Your profile';

if( $_POST['upload'] )
{
	if( $_FILES['file']['error'] == 0 )
	{
		if( 
			$_FILES['file']['type'] == 'image/png' ||
			$_FILES['file']['type'] == 'image/bmp' ||
			$_FILES['file']['type'] == 'image/gif' ||
			$_FILES['file']['type'] == 'image/jpeg' ||
			$_FILES['file']['type'] == 'image/pjpeg' ||
			$_FILES['file']['type'] == 'image/pict' ||
			$_FILES['file']['type'] == 'image/tiff' ||
			$_FILES['file']['type'] == 'image/x-tiff' )
		{
			if( $_FILES['file']['size'] < 1024000 )
			{
				$randomHash = sha1(microtime().":D");
				$uniqueID = substr($randomHash,0,5);
				$parts = explode(".",$_FILES['file']['name']);
				$link = "resources/avatars/user_".$userSession->userID."-".$uniqueID.".".$parts[(count($parts)-1)];
				move_uploaded_file($_FILES["file"]["tmp_name"],$link);
				$success = 'Uploaded';
				$userSession->avatar = $link;
				$_SESSION['user'] = serialize($userSession);
				mysql_query("UPDATE `users` SET `avatar`='".secureInput($link)."' WHERE `userID`=".(int)$userSession->userID);
			}
			else
			{
				$errors[] = 'That file is too big, 1mb only.';
			}
		}
		else
		{
			$errors[] = 'That file type is not supported by ENL';
		}
	}
}

if( $_POST['save'] )
{
	$nickname 	= secureInput($_POST['nickname']);
	$class		= (int)$_POST['class'];
	$format		= (int)$_POST['matchFormat'];
	$cless		= (int)$_POST['cless'];
	$email		= secureInput($_POST['email']);
	$question 	= secureInput($_POST['securityQuestion']);
	$answer		= secureInput($_POST['securityAnswer']);
	
	$homepage	= secureInput($_POST['homepage']);
	$xfire		= secureInput($_POST['xfire']);
	
	if( strlen($xfire) > 40 )
		$errors[] = 'Your xfire username is too long';
	if( strlen($homepage) > 100 )
		$errors[] = 'Your home page is too long';
	
	if( $_POST['password'] )
	{
		$password 	= passwordHash($_POST['password']);
		$password2 	= passwordHash($_POST['password2']);
	}
	
	if( strlen($_POST['email']) > 100 )
		$errors[] = 'The email you entered is too long';
	if( strlen($_POST['email']) > 0 && $_POST['email'] != $userSession->email )
	{
		if( strpos($_POST['email'],"@") == 0 )
			$errors[] = 'The email address you entered is not valid';
	}
	if( $password != $password2 )
		$errors[] = 'The passwords you entered do not match';
	if( strlen($nickname) > 40 )
		$errors[] = 'The nickname you entered is too long';
	if( strlen($question) > 255 )
		$errors[] = 'The security question you entered is too long';
	if( strlen($answer) > 255 )
		$errors[] = 'The security question\'s answer you entered is too long';
	
	if( ! $errors )
	{
		$sql = "UPDATE `users` SET `cless`=".$cless.",`preferredClass`=".$class.", `preferredFormat`=".$format.",`nickname`='".$nickname."'";
		if( $_POST['password'] )
		{
			$sql .= ",`password`='".$password."'";
		}
		
		if( $email != $userSession->email )
		{
			$sql .= ',`email`=\''.$email.'\'';
		}
		if( $userSession->homepage != $homepage )
		{
			$sql .= ',`homepage`=\''.$homepage.'\'';
		}
		if( $userSession->xfire != $xfire )
		{
			$sql .= ',`xfire`=\''.$xfire.'\'';
		}
		
		// no need to escape the SESSION username as it is already escaped
		$sql .= " WHERE `username`='".$userSession->username."'";
		if( mysql_query($sql) )
		{
			$success = 'Saved';
			$userSession->nickname 			= $nickname;
			$userSession->preferredClass 	= $class;
			$userSession->preferredFormat 	= $format;
			$userSession->cless				= $cless;
			$userSession->email				= $email;
			$userSession->securityQuestion 	= $question;
			$userSession->securityAnswer	= $answer;
			$userSession->xfire				= $xfire;
			$userSession->homepage			= $homepage;
			
			// don't forget to save the changes to the session too!
			// otherwise the user would have to re-login to keep his changes
			$_SESSION['user'] = serialize($userSession);
		}
		else
		{
			$errors[] = 'A server error occured, try again later';
		}
	}
	
}

if( !$_GET['ajax'] )
require 'template/tp.head.php';
require 'template/tp.profile.php';
if( !$_GET['ajax'] )
require 'template/tp.foot.php';
?>