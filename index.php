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
    <meta charset="utf-8">
    <title>ログイン画面</title>
</head>
<body>
<h1>ログイン画面</h1>
<hr>
<form action="login.php" method="post">
    <label>
        ユーザーID: <br>
        <input type="text" name="user_id" size="30" maxlength="255">
    </label>
    <br>
    <label>
        パスワード: <br>
        <input type="password" name="password" size="30" maxlength="255">
    </label>
    <br>
    <br>
    <input type="submit" value="ログイン">
    <input type="reset" value="リセット">
    <br>
</form>
<p style='color: red'><?=h($error_message) // GETで渡されたエラーメッセージの表示?></p>
<p>登録されていない場合は<a href="signup/index.php">ココ</a>から登録できます</p>
</body>
</html>