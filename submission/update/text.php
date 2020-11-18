<?php
/*

submission/update/text.php [ロジック]

submission/update/index.phpからのみアクセスされる。
タスクのテキスト情報のみ書き換える場合にアクセスされる。

*/

require('../../common/common.php');

// ログインしていないなら、index.phpに飛ばす
login_check();

// 各情報を変数に格納する（渡されていない場合はfalseを格納）
$submission_id = $_SESSION['submission_id'] ?? false;
$title = $_POST['title'] ?? false;
$comment = $_POST['comment'] ?? false;

/*
submission_change.phpからのアクセスであれば全ての情報が渡されているはずなので、
どれか一つの情報が渡されていない時点で、submission_change.phpからの正常なアクセスではない
*/
if (!$submission_id || !$title || !$comment) {
    header('Location: index.php?error_message=不正なアクセスです。(submission/update/text)');
    exit();
}

// 空文字が渡されていれば弾く
if ($submission_id === '' || $title === '' || $comment === '') {
    header('Location: index.php?error_message=空の項目があります。');
    exit();
}

// submissionsテーブルの指定したIDのレコードのファイル名、コメント、更新日時のみを変更する
$stmt = $db -> prepare(<<<SQL
    UPDATE submissions
    SET
        title = :title,
        comment = :comment
    WHERE
        id = :submission_id
SQL);

$stmt -> bindValue(':submission_id', $submission_id, SQLITE3_INTEGER);
$stmt -> bindValue(':title', $title, SQLITE3_TEXT);
$stmt -> bindValue(':comment', $comment, SQLITE3_TEXT);
$stmt -> execute() or die('エラー');

// もういらないので解放する
unset($_SESSION['submission_id']);

// submission.phpに戻す
header('Location: ../index.php');