<?php
/*

top_page.php [表示]

ログインしたユーザのトップページ。

*/

require('common/common.php');

// ログインしていないなら、index.phpに飛ばす
login_check();
?>

<!DOCTYPE html>
<html>
<head>
    <title>トップページ</title>
    <?=f('components/head.html')?>
</head>
<body>
    <h1>ホーム</h1>
    <hr>
    <p>ログインユーザー: <?=h($_SESSION['user_id']) // ログインしているユーザID ?></p>

    <ul>
        <li><a href="task/index.php">課題一覧</a></li>
        <li><a href="submission/index.php">提出した課題</a></li>
        <?php if(is_admin()): // 管理者のみに表示 (ここから) ?>
            <li><a href="submission/list.php">課題の提出状況</a></li>
        <?php endif // 管理者のみに表示 (ここまで) ?>
    </ul>

    <hr>
    <a href="logout.php">ログアウト</a>
    
    <?=f('components/footer.html')?>
</body>
</html>