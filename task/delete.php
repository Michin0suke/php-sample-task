<?php
/*

task/delete.php [ロジック]

task/index.phpからのみアクセスされる。
渡された情報に問題がなければデータベースからレコードを削除する。

*/

require('../common/common.php');

// ログインしていないなら、index.phpに飛ばす
login_check();

// 管理者でない場合
if (!is_admin()) {
    header('Location: index.php');
    exit();
}

// GETで渡されているtask_idを変数に格納
// 渡されていない場合は、falseを入れる
$task_id = $_GET['task_id'] ?? false;

// 削除するファイルのIDが指定されていない場合
if (!$task_id) {
    header('Location: index.php');
    exit();
}

// 投稿を削除する
$stmt = $db -> prepare('DELETE FROM tasks WHERE id = :task_id');
$stmt -> bindValue(':task_id', $task_id, SQLITE3_INTEGER);
$stmt -> execute();

// index.phpに戻る
header('Location: index.php');