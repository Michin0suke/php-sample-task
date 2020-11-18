<?php
/*

task/insert.php [ロジック]

task/index.phpからのみアクセスされる。
渡された情報に問題がなければデータベースに追加する。

*/

require('../common/common.php');

// ログインしていないなら、index.phpに飛ばす
login_check();

// 管理者のみ登録可
if (!is_admin()) {
    header('Location: index.php?error_message=不正なアクセスです。(task/insert - requires admin)');
    exit();
}

// 現在ログインしているユーザのユーザID
$user_id = $_SESSION['user_id'];

// 各情報を変数に格納（情報が渡されていない場合はfalseを格納）
$filepath_tmp = $_FILES['task']['tmp_name'] ?? false;
$title = $_POST['title'] ?? false;
$file_name_original = $_FILES['task']['name'] ?? false;

/*
task_change.phpからのアクセスであれば全ての情報が渡されているはずなので、
どれか一つの情報が渡されていない時点で、task_change.phpからの正常なアクセスではない
*/
if (!$filepath_tmp || !$title || !$file_name_original) {
    header('Location: index.php?error_message=不正なアクセスです。(task/insert - form data is not valid)');
    exit();
}

// 空の項目がある場合
if ($filepath_tmp === '' || $title === '' || $file_name_original === '') {
    header('Location: index.php?error_message=空の項目があります。');
    exit();
}

// 拡張子判別
preg_match_all('/\.\w+/', $file_name_original, $file_ext_matches);
$file_ext = end($file_ext_matches[0]); // aaa.bbb.img のような場合も考慮する

// 実際に保存するファイルの名前
$file_name_internal = sprintf('%s_%s%s', date('Ymd-his'), sha1_file($filepath_tmp), $file_ext);

// 情報をデータベースに登録
$stmt = $db -> prepare( <<<SQL
    INSERT INTO tasks
    (user_id, title, file_name_internal, file_name_original)
    VALUES
    (:user_id, :title, :file_name_internal, :file_name_original)
SQL);

$stmt -> bindValue(':user_id', $user_id, SQLITE3_TEXT);
$stmt -> bindValue(':title', $title, SQLITE3_TEXT);
$stmt -> bindValue(':file_name_internal', $file_name_internal, SQLITE3_TEXT);
$stmt -> bindValue(':file_name_original', $file_name_original, SQLITE3_TEXT);
$stmt -> execute() or die('ファイルがなぜか追加できませんでした。');

// ファイルの移動先のパスを指定
$filepath = '../uploaded_files/task/'.$file_name_internal;

// 一時的に保存されているファイルを移動する
move_uploaded_file($filepath_tmp, $filepath);

// index.phpに戻る
header('Location: index.php');