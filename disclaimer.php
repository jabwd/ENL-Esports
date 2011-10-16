<?php
require 'config.php';

require 'template/tp.head.php';
echo file_get_contents("resources/terms.txt");
require 'template/tp.foot.php';
?>