<?php
/*

index.php [表示]

ユーザが最初にアクセスするファイル。
GETでエラーメッセージを渡されると表示する。

*/

require('common/common.php');

// すでにログインしている場合は、top_pageに飛ばす
if (isset($_SESSION['is_login'])) {
    header("Location: home.php");
    exit();
}

// GETでエラーメッセージが渡されているなら、変数に入れておく
// （渡されていない場合は、空文字を入れる）
$error_message = $_GET['error_message'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>ログイン画面</title>
    <?=f('components/head.html')?>
</head>
<body class="container mt-5">
    <h1>ログイン画面</h1>
    <hr>

    <form action="login.php" method="post">
        <div class="form-group">
            <label for="user-id">ユーザーID:</label>
            <input type="text" name="user_id" size="30" maxlength="255" placeholder="ユーザーID" id="user-id" class="form-control">
        </div>
        <div class="form-group mb-5">
            <label for="password">パスワード:</label>
            <input type="password" name="password" size="30" maxlength="255" placeholder="パスワード" id="password" class="form-control">
        </div>
        <div class="mb-2">
            <input type="submit" value="ログイン" class="btn btn-primary">
            <input type="reset" value="リセット" class="btn btn-danger ml-3">
        </div>
        <br>
    </form>

    <p class="text-danger"><?=h($error_message) // GETで渡されたエラーメッセージの表示?></p>
    <p class="text-muted">登録されていない場合は<a href="signup/index.php">ココ</a>から登録できます</p>

    <?=f('components/footer.html')?>
</body>
</html>