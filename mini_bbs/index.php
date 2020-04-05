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
// 投稿を記録
if (!empty($_POST)) {
    if ($_POST['message'] != '') {
        $sql = sprintf(
            'INSERT INTO posts SET member_id=%d, message="%s", reply_post_id=%d, created=NOW()',
            mysqli_real_escape_string($db, $member['id']),
            mysqli_real_escape_string($db, $_POST['message']),
            mysqli_real_escape_string($db, $_POST['reply_post_id'])
        );
        mysqli_query($db, $sql) or die(mysqli_error($db));
        header('Location: index.php');
        exit();
    }
}
// 投稿を取得
$page = $_REQUEST['page'];
if ($page == '') {
    $page = 1;
}
$page = max($page, 1);

//最終ページを取得
$sql = 'SELECT COUNT(*) AS cnt FROM posts';
$recordSet = mysqli_query($db, $sql);
$table = mysqli_fetch_assoc($recordSet);
$maxPage = ceil($table['cnt'] / 5);
$page = min($page, $maxPage);

$start = ($page - 1) * 5;
$start = max(0, $start);
$sql = sprintf(
    'SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id 
    ORDER BY p.created DESC LIMIT %d, 5',
    $start
);
$posts = mysqli_query($db, $sql) or die(mysqli_error($db));

// 返信
if (isset($_REQUEST['res'])) {
    $sql = sprintf(
        'SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=%d ORDER BY p.created DESC',
        mysqli_real_escape_string($db, $_REQUEST['res'])
    );
    $record = mysqli_query($db, $sql) or die(mysqli_error($db));
    $table = mysqli_fetch_assoc($record);
    $message = '@' . $table['name'] . ' ' .  $table['message'];
}
function Chars($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
function makeLink($value)
{
    return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>', $value);
}

?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index_style.css">
    <title>ひとこと掲示板</title>
</head>

<body>
    <div>
        <header>
            <h1>ひとこと掲示板</h1>
        </header>
        <div class="box">
            <a class="submit" href="infomation.php">登録情報の確認</a>
            <a class="submit" href="logout.php">ログアウト</a>
        </div>
        <section>
            <form action="" method="POST">
                <dl id="form_style">
                    <dt>
                        <h2><span class="caption">ようこそ！</span></h2>
                        <h2><span class="caption"><span class="user_name"><?php echo Chars($member['name']); ?></span>さん、メッセージをどうぞ</span></h2>
                    </dt>
                    <dd class="message_box">
                        <textarea id="message" name="message" cols="50" rows="5" placeholder="こちらにメッセージを入力して投稿ボタンを押してください"><?php echo Chars($message); ?></textarea>
                        <input type="hidden" name="reply_post_id" value="<?php echo Chars($_REQUEST['res']); ?>">
                    </dd>
                </dl>
                <div class="buttom">
                    <input class="submit_buttom" type="submit" value="投稿する">
                </div>
            </form>
        </section>
        <?php while ($post = mysqli_fetch_assoc($posts)) : ?>
            <div class="msg">
                <img class="user_imege" src="member_picture/<?php echo Chars($post['picture']); ?>" alt="<?php echo Chars($post['name']); ?>">
                <p><?php echo nl2br(makeLink(Chars($post['message'])));  ?>
                    <span class="name">(<?php echo Chars($post['name']); ?>)</span>
                    [<a href="index.php?res=<?php echo Chars($post['id']); ?>">Re</a>]</p>
                <p><a class="day" href="view.php?id=<?php echo Chars($post['id']); ?>"><?php echo Chars($post['created']); ?></a>
                    <?php if ($post['reply_post_id'] > 0) : ?>
                        <a class="reply" href="view.php?id=<?php echo Chars($post['reply_post_id']); ?>">返信元のメッセージ</a>
                    <?php endif; ?>
                    <?php if ($_SESSION['id']  == $post['member_id']) : ?>
                        [<a class="delete" href="delete.php?id=<?php echo Chars($post['id']); ?>">削除</a>]
                    <?php endif; ?>
                </p>
            </div>
        <?php endwhile; ?>
        <ul class="paging">
            <?php if ($page > 1) { ?>
                <li><a class="next" href="index.php?page=<?php print($page - 1); ?>">前のページへ</a></li>
            <?php } else { ?>
                <li>前のページへ</li>
            <?php }
            if ($page < $maxPage) {
            ?>
                <li><a href="index.php?page=<?php print($page + 1); ?>">次のページへ</a></li>
            <?php } else { ?>
                <li>次のページへ</li>
            <?php } ?>
        </ul>
    </div>
</body>

</html>