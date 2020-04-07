<?php
require('../dbconnect.php');
session_start();

if (!empty($_POST)) {
    if ($_POST['name'] == '') {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] == '') {
        $error['email'] = 'blank';
    }
    if (strlen($_POST['password']) < 4) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }

    $fileName = $_FILES['image']['name'];

    if (!empty($fileName)) {
        $ext = substr($fileName, -3);
        if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
            $error['image'] = 'type';
        }
    }
    // 重複アカウントのチェック
    if (empty($error)) {
        $sql = sprintf(
            'SELECT COUNT(*) AS cnt FROM members WHERE email="%s"',
            mysqli_real_escape_string($db, $_POST['email'])
        );
        $record = mysqli_query($db, $sql) or die(mysqli_error($db));
        $table = mysqli_fetch_assoc($record);
        if ($table['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }
    }

    if (empty($error)) {
        $image = date('YmdHis') . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
        header('Location: check.php');
        exit();
    }
}
// 登録の書き直し
if ($_REQUEST['action'] == 'rewrite') {
    $_POST = $_SESSION['join'];
    $error['rewrite'] = true;
}
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/join.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <title>登録フォーム</title>
</head>

<body>
    <header>
        <h1 class="title">メンバー登録</h1>
    </header>
    <h2><span class="caption">次のフォームに必要事項をご記入ください。</span></h2>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="content">
            <label class="label_style" for="name">ニックネーム</label><br>
            <input type="text" id="name" name="name" required size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'); ?>">
            <?php if ($error['name'] == 'blank') : ?>
                <p class="error">* ニックネームを入力してください</p>
            <?php endif; ?>

        </div>
        <div class="content">
            <label class="label_style" for="email">メールアドレス</label><br>
            <input type="text" id="email" name="email" required size="35" maxlength="225" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'); ?>">
            <?php if ($error['email'] == 'blank') : ?>
                <p class="error">* メールアドレスを入力してください</p>
            <?php endif; ?>
            <?php if ($error['email'] == 'duplicate') : ?>
                <p class="error">* 指定されたメールアドレスはすでに登録されています</p>
            <?php endif; ?>
        </div>
        <div class="content">
            <label class="label_style" for="password">パスワード</label><br>
            <input type="password" id="password" name="password" required size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'); ?>">
            <?php if ($error['password'] == 'blank') : ?>
                <p class="error">* パスワードを入力してください</p>
            <?php endif; ?>
            <?php if ($error['password'] == 'length') : ?>
                <p class="error">* パスワードは4文字以上で入力してください</p>
            <?php endif; ?>

        </div>
        <div class="content">
            <label class="label_style" for="upload">アイコン</label><br>
            <input type="file" id="upload" name="image" size="35" required>
            <?php if ($error['image'] == 'type') : ?>
                <p class="error">* [.gif],[.jpg],[.png]の画像を指定してください</p>
            <?php endif; ?>
            <?php if (!empty($error)) : ?>
                <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
            <?php endif; ?>
        </div>
        <div class="box"><input class="submit" type="submit" value="入力内容を確認する"></div>
    </form>
</body>

</html>