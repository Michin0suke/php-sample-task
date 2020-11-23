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
<body class="container mt-5">
    <h1>ホーム</h1>
    <hr>

    <p class="text-secondary mb-5">ログインユーザー: <?=h($_SESSION['user_id']) // ログインしているユーザID ?></p>

    <div class="row">
        <a href="task/index.php" class="col-md mb-5">
            <button class="btn btn-lg btn-primary col">課題一覧</button>
        </a>

        <a href="submission/index.php" class="col-md mb-5">
            <button class="btn btn-lg btn-primary col">課題の提出</button>
        </a>

<?php if(is_admin()): // 管理者のみに表示 (ここから) ?>
        <a href="submission/list.php" class="col-md mb-5">
            <button class="btn btn-lg btn-info col">課題の提出状況</button>
        </a>
<?php endif // 管理者のみに表示 (ここまで) ?>
    </div>

    <hr>
    <a href="logout.php">ログアウト</a>

    <?=f('components/footer.html')?>
</body>
</html>