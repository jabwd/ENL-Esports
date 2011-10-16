<?php
require 'config.php';
require_once 'class/TournamentController.php';
require_once 'class/TeamController.php';

$pageName = "Tournaments";
$controller = new TournamentController();

if( !$_GET['ajax'] )
	require 'template/tp.head.php';
require 'template/tp.tournament.php';
if( !$_GET['ajax'] )
	require 'template/tp.foot.php';
?>