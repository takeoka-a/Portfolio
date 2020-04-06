<?php
session_start();
require('dbconnect.php');

if (empty($_REQUEST['id'])) {
    header('Location: index.php');
    exit();
}
// 投稿を取得
$sql = sprintf('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=%d ORDER BY p.created DESC',
	mysqli_real_escape_string($db, $_REQUEST['id'])
);
$posts = mysqli_query($db, $sql) or die(mysqli_error($db));
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/view.css">
    <title>個別画面</title>
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>ひとこと掲示板</h1>
        </div>
        <div id="content">
            <div class="box">
            <a class="submit" href="index.php">一覧に戻る</a>
            </div>
            <?php if ($post = mysqli_fetch_assoc($posts)) : ?>
                <div class="msg">
                    <img src="member_picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES, 'UTF-8'); ?>" width="48" height="48" alt="<?php echo htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'); ?>">
                    <p><?php echo htmlspecialchars($post['message'], ENT_QUOTES, 'UTF-8'); ?>
                        <span class="name">(<?php echo htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'); ?>)</span></p>
                    <p class="day"><?php echo htmlspecialchars($post['created'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php else : ?>
                <p>その投稿は削除されたか、URLを間違えています</p>
            <?php endif; ?>
        </div>
        <div id="foot">
        </div>
    </div>

</body>

</html>