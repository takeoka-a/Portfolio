<?php 
error_reporting(E_ALL & ~E_NOTICE);

$db = mysqli_connect('localhost', 'root', '', 'mini_bbs') or die(mysqli_connect_error());
mysqli_set_charset($db, 'utf8');
?>