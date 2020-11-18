<?php
/*

submission/update/file.php [ロジック]

submission/index.phpからのみアクセスされる。
タスクのファイル情報のみ書き換える場合にアクセスされる。

*/

require('../../common/common.php');

// ログインしていないなら、index.phpに飛ばす
login_check();

// 各情報を変数に格納する（渡されていない場合はfalseを格納）
$submission_id = $_SESSION['submission_id'] ?? false;
$file_name_original = $_FILES['submission']['name'] ?? false;
$filepath_tmp = $_FILES['submission']['tmp_name'] ?? false;

/*
submission_change.phpからのアクセスであれば全ての情報が渡されているはずなので、
どれか一つの情報が渡されていない時点で、submission_change.phpからの正常なアクセスではない
*/
if (!$submission_id || !$file_name_original || !$filepath_tmp) {
    header('Location:index.php?error_message=不正なアクセスです。(submission/update/file)');
    exit();
}

// 空文字が渡されている場合は弾く
if ($submission_id === '' || $file_name_original === '' || $filepath_tmp === '') {
    header('Location: index.php?error_message=空の項目があります。');
    exit();
}

// 拡張子判別
preg_match_all('/\.\w+/', $file_name_original, $file_ext_matches);
$file_ext = end($file_ext_matches[0]);

// 実際に保存するファイルの名前を決める。他のファイルと絶対に被らないように、ハッシュを求めている。
$file_name_internal = sprintf('%s_%s%s', date('Ymd-his'), sha1_file($filepath_tmp), $file_ext);

// submissionsテーブルの指定したIDのレコードのファイルのオリジナル名、サーバの内部での名前、更新日時のみを変更する
$stmt = $db -> prepare(<<<SQL
    UPDATE submissions
    SET
        file_name_original = :file_name_original,
        file_name_internal = :file_name_internal
    WHERE
        id = :submission_id
SQL);

$stmt -> bindValue(':submission_id', $submission_id, SQLITE3_INTEGER);
$stmt -> bindValue(':file_name_original', $file_name_original, SQLITE3_TEXT);
$stmt -> bindValue(':file_name_internal', $file_name_internal, SQLITE3_TEXT);
$stmt -> execute() or die('エラー');

// ファイルの移動先のパス
$filepath = '../../uploaded_files/submission/'.$file_name_internal;

// 一時的に保存されているファイルを移動する
move_uploaded_file($filepath_tmp, $filepath);

// もういらないので解放する
unset($_SESSION['submission_id']);

// submission.phpに戻す
header('Location: ../index.php');