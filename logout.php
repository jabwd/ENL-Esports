<?php
require 'config.php';

// destroy the session
session_destroy();

// destroy the cookies
setcookie("username", "", time()-3600, "/");
setcookie("ultimate", "", time()-3600, "/");

// just for thu lulz! no, redirect the user
header("location: ".$baseURL."login/");
?>