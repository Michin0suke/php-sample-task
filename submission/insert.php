<?php
/*

submission/insert.php [ロジック]

submission/index.phpからのみアクセスされる。
渡された情報に問題がなければ、submissionのレコードを追加する

*/

require('../common/common.php');

// ログインしていないなら、index.phpに飛ばす
login_check();

// 現在ログインしているユーザのユーザID
$user_id = $_SESSION['user_id'];

// 各情報を変数に格納する（渡されていない場合はfalseを格納）
$title = $_POST['title'] ?? false;
$file_name_original = $_FILES['file']['name'] ?? false;
$comment = $_POST['comment'] ?? false;
$filepath_tmp = $_FILES['file']['tmp_name'] ?? false;

/*
渡されていない情報がある場合（一つでも変数にfalseが入っている場合）は弾く
submission/index.phpからのアクセスなら全て渡されているはずなので、直アクセスなどだと考えられる
*/
if (!$title || !$file_name_original || !$comment || !$filepath_tmp) {
    header('Location: index.php?error_message=不正なアクセスです。(submission/insert)');
    exit();
}

// どれか一つの情報でも空文字があれば弾く
if ($title === '' || $file_name_original === '' || $comment === '' || $filepath_tmp === '') {
    header('Location: index.php?error_message=空の項目があります。');
    exit();
}

// 拡張子判別
preg_match_all('/\.\w+/', $_FILES['file']['name'], $file_ext_matches);
$file_ext = end($file_ext_matches[0]);

// 実際に保存するファイルの名前を決める。他のファイルと絶対に被らないように、ハッシュを求めている。
$file_name_internal = sprintf('%s_%s%s', date('Ymd-his'), sha1_file($filepath_tmp), $file_ext);

// submissionsテーブルにレコードを登録する
$stmt = $db -> prepare(<<<SQL
    INSERT INTO submissions
    (user_id, title, file_name_original, file_name_internal, comment)
    VALUES
    (:user_id, :title, :file_name_original, :file_name_internal, :comment)
SQL);

$stmt -> bindValue(':user_id', $user_id, SQLITE3_TEXT);
$stmt -> bindValue(':title', $title, SQLITE3_TEXT);
$stmt -> bindValue(':file_name_original', $file_name_original, SQLITE3_TEXT);
$stmt -> bindValue(':file_name_internal', $file_name_internal, SQLITE3_TEXT);
$stmt -> bindValue(':comment', $comment, SQLITE3_TEXT);
$stmt -> execute() or die('エラー');

// ファイルの移動先のパス
$filepath = '../uploaded_files/submission/'.$file_name_internal;

// 一時的に保存されているファイルを移動する
move_uploaded_file($filepath_tmp, $filepath);

// index.phpに戻す
header('Location: index.php');