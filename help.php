<?php
require 'config.php';

$pageName = 'Help';

$content = file_get_contents("resources/help.txt");

require 'template/tp.head.php';
require 'template/tp.help.php';
require 'template/tp.foot.php';
?>