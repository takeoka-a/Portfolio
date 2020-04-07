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
    $fileName = $_FILES['image']['name'];

    if (!empty($fileName)) {
        $ext = substr($fileName, -3);
        if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
            $error['image'] = 'type';
        }
    }
    if (empty($error)) {
        $image = date('YmdHis') . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
        header('Location: image_update_do.php');
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
    <link rel="stylesheet" href="../css/responsive.css">
    <title>Document</title>
</head>

<body>
    <header>
        <h1>登録内容編集</h1>
    </header>
    <h2><span class="caption fontsize_image">アイコンを変更する場合はファイルを選択し、「変更する」を押してください</span></h2>
    <div class="form">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="content">
                <input type="file" id="upload" name="image" required>
                <?php if($error['image'] == 'type'): ?>
                    <p class="error">* [.gif],[.jpg],[.png]の画像を指定してください</p>
                <?php endif;?>
                <?php if(!empty($error)): ?>
                    <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
                <?php endif;?>
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