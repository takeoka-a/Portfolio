<?php
require('dbconnect.php');
session_start();

if ($_COOKIE['email'] != '') {
    $_POST['email'] = $_COOKIE['email'];
    $_POST['password'] = $_COOKIE['password'];
    $_POST['save'] = 'on';
}
//ログイン処理
if (!empty($_POST)) {
    if ($_POST['email'] != '' && $_POST['password'] != '') {
        $sql = sprintf(
            'SELECT * FROM members WHERE email="%s" AND password="%s"',
            mysqli_real_escape_string($db, $_POST['email']),
            mysqli_real_escape_string($db, sha1($_POST['password']))
        );
        $record = mysqli_query($db, $sql) or die(mysqli_error($db));
        if ($table = mysqli_fetch_assoc($record)) {
            //ログインに成功した場合
            $_SESSION['id'] = $table['id'];
            $_SESSION['name'] = $table['name'];
            $_SESSION['email'] = $table['email'];
            $_SESSION['time'] = time();

            if ($_POST['save'] == 'on') {
                //ログイン情報の記録
                setcookie('email', $_POST['email'], time() + 60 * 60 * 24 * 14);
                setcookie('password',  $_POST['password'], time() + 60 * 60 * 24 * 14);
            }
            header('Location: index.php');
            exit();
        } else {
            $error['login'] = 'failed';
        }
    } else {
        $error['login'] = 'blank';
    }
}
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/responsive.css">
    <title>トップページ</title>
</head>

<body>
    <h1>ひとこと掲示板</h1>
    <div class="box">
        <a class="submit_manual" href="manual.html">掲示板の使い方</a>
        <a class="submit_entry" href="join/">入会手続きをする</a>
    </div><br>
    <div>
        <h2 class="fontsize_h2"><span class="caption">メールアドレスとパスワードを記入してログインしてください。</span></h2>
        <h2 class="fontsize_h2"><span class="caption">入会手続きがまだの方は、先に手続きを完了させてください。</span></h2>
    </div><br>
    <form action="" method="POST">
        <div class="content">
            <label class="label_style" for="email">メールアドレス</label><br>
            <input type="text" id="email" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email']); ?>">
            <?php if ($error['login'] == 'blank') : ?>
                <p class="error">* メールアドレスとパスワードをご記入ください</p>
            <?php endif; ?>
            <?php if ($error['login'] == 'failed') : ?>
                <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
            <?php endif; ?>
        </div>
        <div class="content">
            <label class="label_style" for="password">パスワード</label><br>
            <input type="password" id="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password']); ?>">
        </div>
        <div class="content">
            <label class="label_style">ログイン情報の記録</label><br><br>
            <input class="save" type="checkbox" id="save" name="save" value="on">
            <label for="save">次回からは自動的にログインする</label><br>
        </div>
        <div class="box">
            <input class="login" type="submit" value="ログインする">
        </div>
    </form>
</body>

</html>