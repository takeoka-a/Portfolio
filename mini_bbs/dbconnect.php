<?php 
error_reporting(E_ALL & ~E_NOTICE);

$db = mysqli_connect('mysql1.php.xdomain.ne.jp', 'take111_admin', 'admin10pass', 'take111_minibbs') or die(mysqli_connect_error());
mysqli_set_charset($db, 'utf8');
?>