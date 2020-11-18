<?php
/*

login.php [ロジック]

index.phpからのみアクセスされる。
渡された情報に問題があれば、エラーメッセージを含めてindex.phpにリダイレクトする。
問題がなければログイン情報をセッションに保存したあと、home.phpにリダイレクトする。

*/

require('common/common.php');

// POSTで渡された情報を変数に格納する（渡されていない場合はfalseを格納）
$user_id = $_POST['user_id'] ?? false;
$password = $_POST['password'] ?? false;

// ダイレクトにアクセスされた場合（どちらかの変数にfalseが入っている場合）
if (!$user_id || !$password) {
    header('Location: index.php?error_message=不正なアクセスです(login)');
    exit();
}

// ユーザ名が空な場合
if($user_id === '') {
    header('Location: index.php?error_message=ユーザ名を入力してください。');
    exit();
}

// パスワードが空な場合
if($password === '') {
    header('Location: index.php?error_message=パスワードを入力してください。');
    exit();
}

// usersテーブルから、指定されたユーザIDのレコードを取得する
$stmt = $db -> prepare('SELECT * FROM users WHERE id = :user_id');
$stmt -> bindValue(':user_id', $user_id, SQLITE3_TEXT);
$record = $stmt -> execute() -> fetchArray();

// ユーザ名が登録されていない場合（レコードが取得できなかった場合）
if (!$record) {
    header('Location: index.php?error_message=指定されたユーザは存在しません。');
    exit();
}

// POSTされたパスワードとユーザ名に対応するパスワード（ハッシュ化されてる）が一致しない場合
if (!password_verify($password, $record['password'])) {
    header('Location: index.php?error_message=パスワードが間違っています。');
    exit();
}

// セッションの登録
$_SESSION['is_login'] = true;
$_SESSION['user_id'] = $user_id;

// ページ遷移
header('Location: home.php');
exit();
