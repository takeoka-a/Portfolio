<?php
session_start();
require('dbconnect.php');

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

?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/infomation.css">
    <link rel="stylesheet" href="css/responsive.css">
    <title>登録情報</title>
</head>

<body>
    <header>
        <h1>登録情報一覧</h1>
    </header>
    <table class="infomation">
        <tr>
            <th class="member_id">会員ID</th>
            <th>ニックネーム</th>
            <th>メールアドレス</th>
            <th>パスワード</th>
            <th>アイコン</th>
        </tr>
        <tr>
            <td class="member_id">
                <p><?php echo 'NO. ' . htmlspecialchars($member['id']); ?></p>
            </td>
            <td>
                <p><?php echo htmlspecialchars($member['name']); ?><br><br>
                    <a class="change" href="join/name_update.php">変更する</a></p>
            </td>
            <td>
                <p><?php echo htmlspecialchars($member['email']); ?><br><br>
                    <a class="change" href="join/mail_update.php">変更する</a></p>
            </td>
            <td>
                <p>[非表示]<br><br>
                    <a href="join/password_update.php" class="change">変更する</a></p>
            </td>
            <td>
            <a href="join/image_update.php"><img class="icon" src="member_picture/<?php echo htmlspecialchars($member['picture']); ?>"></a><br><br>
            </td>
        </tr>
    </table>
    <div class="box">
        <a class="link_style submit" href="index.php">掲示板へ戻る</a>
    </div>
    <div class="delete_box">
        <a class="link_style withdrawal" href="withdrawal.php" onclick="return confirm('このボタンを押すとデータは完全に削除され元には戻せません、よろしいですか？')">退会する</a>
    </div>
</body>

</html>