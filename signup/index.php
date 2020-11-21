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
<body>
    <form action="check_format.php" method="post">
        <table>
            <caption>ユーザ登録</caption>
            <tr><th>ユーザID</th><td><input type="text" name="user_id" size="40" maxlength="63"></td></tr>
            <tr><th>パスワード</th><td><input type="password" name="password" size="40" maxlength="255"></td></tr>
            <tr><th>メール</th><td><input type="text" name="mail" size="40" maxlength="255"></td></tr>
        </table>

        <p style="color: red"><?=h($error_message) // GETで渡されたエラーメッセージを出力 ?></p>

        <input type="submit" value="submit">
        <input type="reset" value="reset">
    </form>

    <a href="../index.php">
        <button>ログイン画面に戻る</button>
    </a>

    <?=f('../components/footer.html')?>
</body>
</html>
