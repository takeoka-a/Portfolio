<?php
require('dbconnect.php');
session_start();
$sql = sprintf(
    "DELETE FROM members WHERE id=%d",
    mysqli_real_escape_string($db, $_SESSION['id'])
);
mysqli_query($db, $sql) or die(mysqli_error($db));
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}
session_destroy();

setcookie('email', '', time() - 3600);
setcookie('password', '', time() - 3600);

?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/withdrawal.css">
    <title>Document</title>
</head>

<body>
    <header>
        <h1>退会完了</h1>
    </header>
    <h2><span class="caption">登録情報を削除しました。</span></h2>
    <div class="box"><a class="submit" href="login.php">トップヘージへ</a></div>
</body>

</html>