<?php
/*

submission/update/index.php [表示]

submission/index.phpからのみアクセスされる。
submission/index.phpで指定した、変更するタスク１つを表示する。
対象のタスクについて、テキストだけ、ファイルだけの編集ができる。

*/

require('../../common/common.php');

// ログインしていないなら、index.phpに飛ばす
login_check();

// 現在ログインしているユーザのユーザID
$user_id = $_SESSION['user_id'];

// 変更するタスクのIDをGETから取得（指定されていない場合はfalseを格納）
$submission_id = $_GET['submission_id'] ?? false;

// 変更するタスクのIDがGETで指定されていない場合は、submission/index.phpに飛ばす
if (!$submission_id) {
    header('Location: ../index.php?error_message=提出物IDが指定されていません。');
    exit();
}

// 変更するタスクのIDをセッションに格納
$_SESSION['submission_id'] = $submission_id;

// エラーメッセージがGETで渡されている場合は変数に格納（渡されていない場合は空文字）
$error_message = $_GET['error_message'] ?? '';

// submissionsテーブルから、指定されたIDかつ、現在ログインしているユーザのタスクを取得します。
$stmt = $db -> prepare('SELECT * FROM submissions WHERE id = :submission_id AND user_id = :user_id');
$stmt -> bindValue(':submission_id', $submission_id, SQLITE3_INTEGER);
$stmt -> bindValue(':user_id', $user_id, SQLITE3_TEXT);
$record = $stmt -> execute() -> fetchArray();

/*
上記のSQLでレコードが取得できないのは、指定されたIDのタスクが存在しなかったり、
他人が投稿したタスクを変更しようとした場合です。
いずれにしても通常の使用では発生しない（不正なアクセス）なので、弾きます。
*/
if (!$record) {
    header('Location: ../index.php?error_message=不正なアクセスです。(submission/update/index)');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>課題の編集</title>
</head>
<body>
    <p style="color: red"><?=h($error_message) // エラーメッセージがあれば表示されます ?></p>

    <h2>編集欄</h2>
    <form action="text.php" method="post" enctype="multipart/form-data">
        <table border="1">
            <tr>
                <th>題名</th>
                <td colspan="3">
                    <input type="text" name="title" value="<?=h($record['title']) // ファイル名です ?>" size="80">
                </td>
            </tr>
            <tr>
                <th>コメント</th>
                <td colspan="3">
                    <textarea name="comment" cols="80" rows="5"><?=h($record['comment']) // コメントです ?></textarea>
                </td>
            </tr>
        </table>
        <input type="submit" value="変更する">
    </form>
    <form action="file.php" method="post" enctype="multipart/form-data">
        <p>登録済ファイル: <?=h($record['file_name_original']) // ファイルのオリジナル名です ?></p>
        <p>変更ファイル: <input type="file" name="submission"></p>
        <br>
        <input type="submit" value="変更する">
    </form>
    <hr>
    <a href="../index.php">←課題提出ページ</a>
</body>
</html>