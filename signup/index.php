<?php
/*

signup/index.php [表示]

index.phpからのみアクセスされる。
新規ユーザの登録フォームを表示する。
エラーメッセージがGETで渡されていれば表示する。

*/

require('../common/common.php');

// GETでエラーメッセージが渡されているなら、変数に入れておく
// （渡されていない場合は、空文字を入れる）
$error_message = $_GET['error_message'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>登録画面</title>
    <?=f('../components/head.html')?>
</head>
<body class="container mt-5">
    <h1 class="mb-5">ユーザ登録</h1>
    <form action="check_format.php" method="post" class="mb-3">
        <div class="form-group">
            <label for="user-id">ユーザーID:</label>
            <input type="text" name="user_id" size="30" maxlength="255" placeholder="ユーザーID" id="user-id" class="form-control">
        </div>
        <div class="form-group">
            <label for="password">パスワード:</label>
            <input type="password" name="password" size="30" maxlength="255" placeholder="パスワード" id="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="mail">メールアドレス:</label>
            <input type="mail" name="mail" size="30" maxlength="255" placeholder="メールアドレス" id="mail" class="form-control">
        </div>

        <p class="text-danger mb-5"><?=h($error_message) // GETで渡されたエラーメッセージを出力 ?></p>

        <input type="submit" value="登録" class="btn btn-primary">
        <input type="reset" value="リセット" class="btn btn-danger ml-3">
    </form>

    <a href="../index.php">
        <button class="btn btn-info">ログイン画面に戻る</button>
    </a>

    <?=f('../components/footer.html')?>
</body>
</html>
