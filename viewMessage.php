<?php
require 'config.php';

checkLogin();

$query = mysql_query("SELECT `messages`.`messageID`,`messages`.`creationDate`,`messages`.`content`,`messages`.`subject`,`users`.`username`,`users`.`nickname` FROM `messages`,`users` WHERE `messages`.`messageID`=".(int)$_GET['mID']." AND `users`.`userID`=`messages`.`fromID`");
if( $query )
{
	$message = mysql_fetch_array($query);
}

if( ! $message )
{
	header("location: ".$baseURL);
	exit();
}
else if( $message['read'] == 0 )
{
	$messagesCount--;
	mysql_query("UPDATE `messages` SET `read`=1 WHERE `messageID`=".(int)$message['messageID']);
}

if( !$_GET['ajax'] )
require 'template/tp.head.php';


require 'template/tp.viewMessage.php';

if( !$_GET['ajax'] )
require 'template/tp.foot.php';
?>