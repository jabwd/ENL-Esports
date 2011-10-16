<?php
require 'config.php';
require_once 'class/Match.php';

$pageName = 'War info';

if( ! $_GET['matchID'] )
{
	header("location: ".$baseURL);
	exit();
}

$match = new Match($_GET['matchID']);

require 'template/tp.head.php';
require 'template/tp.warInfo.php';
require 'template/tp.foot.php';
?>