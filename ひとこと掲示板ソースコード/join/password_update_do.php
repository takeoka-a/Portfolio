<?php
require('../dbconnect.php');
session_start();

if (!isset($_SESSION['join'])) {
    header('Location: ../infomation.php');
    exit();
}
if (!empty($_SESSION['join'])) {
    $sql = mysqli_prepare($db, 'UPDATE members SET password=? WHERE id=?');
    mysqli_stmt_bind_param($sql, 'si', sha1($_SESSION['join']['password']), $_SESSION['id']);
    mysqli_stmt_execute($sql);
    unset($_SESSION['join']);
    header('Location: ../infomation.php');
    exit();
}
unset($_SESSION['join']);
header('Location: ../infomation.php');
exit();
