<?php
//データベースへ接続
session_start();
require('../dbconnect.php');

//処理
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
if (!empty($_POST)) {
    if ($_POST['name'] == '') {
        $error['name'] = 'blank';
    }
    if (empty($error)) {
        $_SESSION['join'] = $_POST;
        header('Location: name_update_do.php');
        exit();
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
                <label class="label_style" for="name">新しいニックネーム</label><br>
                <input type="text" id="name" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES, 'utf-8'); ?>"><br>
                <?php if ($error['name'] == 'blank') : ?>
                    <p class="error">* ニックネームを入力してください</p>
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