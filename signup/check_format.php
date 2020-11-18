<?php
/*

signup/check_format.php [ロジック]

signup/index.phpからのみアクセスされる。
渡された情報のフォーマット(パスワードの長さ等)を検査して、問題がなければsignup/insert.phpに情報を渡す。
問題があれば、signup/index.phpに戻す。

*/

require('../common/common.php');

// signup/index.phpからのアクセスでない場合
if (empty($_POST)) {
    header('Location: index.php?error_message=不正なアクセスです。(signup/check_format)');
    exit();
}

// 空の項目がある場合
if ($_POST['user_id'] === '' || $_POST['mail'] === '' || $_POST['password'] === '') {
    header('Location: index.php?error_message=空の項目があります。');
    exit();
}

// パスワードの長さが4未満である場合
if(strlen($_POST['password']) < 4) {
    header('Location: index.php?error_message=パスワードの長さは4文字以上にしてください。');
    exit();
}

// セッションにPOSTのデータを移行（遷移先のファイルで参照するため）
$_SESSION['signup_data'] = $_POST;

// 問題がないので、ページ遷移
header('Location: insert.php');