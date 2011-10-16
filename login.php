<?php
require 		'config.php';
require_once 	'class/UserController.php';

$pageName = 'Login to ENL';

if( $userSession )
{
	header("location: ".$baseURL);
	echo 'It should have redirected you';
	exit();
}

if( $_POST['login'] )
{
	$userObject = UserController::authenticate($_POST['username'],$_POST['password']);
	if( $userObject )
	{
		if( $userObject->userID > 0 )
		{
			// create a cookie to remember the user
			if( $_POST['rememberMe'] )
			{
				$expire = time()+60*60*24*30; // 1 month, that is long enough ( security reasons ) 
				setcookie("username", $userObject->userID, $expire, "/");
				setcookie("ultimate", passwordHash(passwordHash($_POST['password']).$_SERVER['REMOTE_ADDR']), $expire, "/");
			}
			
			$_SESSION['user'] = serialize($userObject);
			
			header("location: ".$baseURL);
			echo 'You should be redirected now..';
			exit();
		}
	}
	else
	{
		$errors[] = 'Username and / or password is incorrect';
	}
}

if( !$_GET['ajax'] )
require 'template/tp.head.php';
require 'template/tp.login.php';
if( !$_GET['ajax'] )
require 'template/tp.foot.php';
?>