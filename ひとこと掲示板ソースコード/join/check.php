<?php
session_start();
require('../dbconnect.php');

if (!isset($_SESSION['join'])) {
    header('Location: index.php');
    exit();
}
if (!empty($_POST)) {
    $sql = sprintf(
        'INSERT INTO members SET name="%s", email="%s", password="%s", picture="%s", created="%s"',
        mysqli_real_escape_string($db, $_SESSION['join']['name']),
        mysqli_real_escape_string($db, $_SESSION['join']['email']),
        mysqli_real_escape_string($db, sha1($_SESSION['join']['password'])),
        mysqli_real_escape_string($db, $_SESSION['join']['image']),
        date('Y-m-d H:i:s')
    );
    mysqli_query($db, $sql) or die(mysqli_error($db));
    unset($_SESSION['join']);
    header('Location: thanks.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/check.css">
    <title>確認</title>
</head>

<body>
    <h1 class="title">登録確認画面</h1>
    <form action="" method="post">
        <input type="hidden" name="action" value="submit">
        <dl>
            <dt><span class="table_style">ニックネーム</span></dt>
            <dd><?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES, 'UTF-8'); ?></dd>
            <dt><span class="table_style">メールアドレス</span></dt>
            <dd><?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES, 'UTF-8'); ?></dd>
            <dt><span class="table_style">パスワード</span></dt>
            <dd>[表示されません]</dd>
            <dt><span class="table_style">アイコン</span></dt>
            <dd>
                <img src="../member_picture/<?php echo htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES, 'UTF-8'); ?>" width="100" height="100" alt="">
            </dd>
        </dl>
        <div class="box">
            <a class="rewrite" href="index.php?action=rewrite">書き直す</a>
            <input class="submit" type="submit" value="登録する">
        </div>
    </form>
    <div class="buck_home">
    <a class="rewrite" href="login.php">HOMEへ戻る</a>
    </div>
</body>

</html>