<?php
require 'config.php';

$pageName = 'Rules';

if( !$_GET['ajax'] )
require 'template/tp.head.php';
echo file_get_contents("resources/rules.txt");
if( !$_GET['ajax'] )
require 'template/tp.foot.php';
?>