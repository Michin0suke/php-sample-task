<?php
/*

signup/insert.php [ロジック]

signup/check_format.phpからのみリダイレクトされる。
usersテーブルにユーザを登録する。
登録できなければ、すでに指定されたユーザIDが存在するとして、signup/index.phpに戻す。

*/

require('../common/common.php');

// セッションのデータを変数に格納
$user_id = $_SESSION['signup_data']['user_id'] ?? false;
$password = $_SESSION['signup_data']['password'] ?? false;
$mail = $_SESSION['signup_data']['mail'] ?? false;

// signup/check_format.phpからのアクセスでない場合
// signup/check_format.phpからのアクセスであれば、上記３つのセッションデータは格納されているはずであるから
if (!$user_id || !$password || !$mail) {
    header('Location: index.php?error_message=不正なアクセスです。(signup/insert)');
    exit();
}

// INSERT文をprepared statementを用いて実行
// INSERTできなかった場合は、$is_success_insertがfalseになる
$stmt = $db -> prepare('INSERT INTO users VALUES (:user_id, :password, :mail)');
$stmt -> bindValue(':user_id', $user_id, SQLITE3_TEXT);
$stmt -> bindValue(':password', password_hash($password, PASSWORD_BCRYPT), SQLITE3_TEXT);
$stmt -> bindValue(':mail', $mail, SQLITE3_TEXT);
$is_success_insert = $stmt->execute();

// INSERTできなかったということは、idが重複している(=名前がすでに使用されている)と考えられるので、遷移させる。
if(!$is_success_insert) {
    header("Location: index.php?error_message=その名前($user_id)はすでに使用されています。別名で登録してください。");
    exit();
}

// パスワードとかはセッションにもういらないので、セッションをクリアする。
session_unset();
?>

<!DOCTYPE html>
<html>
<head>
    <title>ログイン情報チェック</title>
    <?=f('../components/head.html')?>
</head>
<body class="container mt-5">
    <p>以下の情報で登録されました。</p>

    <ul>
        <li><b>user_id: </b><?=h($user_id) // エスケープして出力?></li>
        <li><b>Password: パスワードは忘れないようにしてください</b></li>
        <li><b>Mail: </b><?=h($mail) // エスケープして出力?></li>
    </ul>

    <a href="../index.php">ログイン画面</a>

    <?=f('../components/footer.html')?>
</body>
</html>