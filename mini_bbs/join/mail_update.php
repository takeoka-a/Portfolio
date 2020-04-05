<?php
//データベースへ接続
session_start();
require('../dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    //ログインしている場合
    $_SESSION['time'] = time();
    $sql = sprintf(
        'SELECT * FROM members WHERE id=%d',
        mysqli_real_escape_string($db, $_SESSION['id'])
    );
    $record = mysqli_query($db, $sql) or die(mysqli_error($db));
    $member = mysqli_fetch_assoc($record);
} else {
    //ログインしていない場合
    header('Location: login.php');
    exit();
}

if (empty($error)) {
    $sql = sprintf(
        'SELECT COUNT(*) AS cnt FROM members WHERE email="%s"',
        mysqli_real_escape_string($db, $_POST['email'])
    );
    $record = mysqli_query($db, $sql) or die(mysqli_error($db));
    $table = mysqli_fetch_assoc($record);

    if (!empty($_POST)) {
        if ($_POST['email'] == '') {
            $error['email'] = 'blank';
        }    
         if ($table['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }
        if (empty($error)) {
            $_SESSION['join'] = $_POST;
            header('Location: mail_update_do.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/update.css">
    <title>Document</title>
</head>

<body>
    <header>
        <h1>登録内容編集</h1>
    </header>
    <div class="form">
        <form action="" method="POST">
            <div class="content">
                <label class="label_style" for="email">新しいメールアドレス</label><br>
                <input type="text" id="email" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php if ($error['email'] == 'blank') : ?>
                    <p class="error">* メールアドレスを入力してください</p>
                <?php endif; ?>
                <?php if ($error['email'] == 'duplicate') : ?>
                    <p class="error">* 指定されたメールアドレスはすでに登録されています</p>
                <?php endif; ?>
            </div>
    </div>
    <div class="box">
        <input class="submit" type="submit" value="変更する" name="update">
        <input type="hidden" name="id" value="<?php print(htmlspecialchars($member['id'], ENT_QUOTES, 'utf-8')) ?>">
        </form>
        <a class="cancel" href="../infomation.php">キャンセル</a>
    </div>
</body>

</html>