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
    <title>課題の編集</title>
    <?=f('../../components/head.html')?>
</head>
<body class="container mt-5">
    <p id="error-message" class="text-danger"><?=h($error_message) // エラーメッセージがあれば表示されます ?></p>

    <h1 class="mb-4">課題の編集</h1>

    <form action="text.php?submission_id=<?=$submission_id?>" method="post" enctype="multipart/form-data" class="mb-4">
        <div class="form-group">
            <label for="title">タイトル</label>
            <input type="text" name="title" value="<?=h($record['title']) // ファイル名です ?>" id="title" class="form-control">
        </div>
        <div class="form-group">
            <label for="comment">コメント</label>
            <textarea name="comment" rows="5" id="comment" class="form-control"><?=h($record['comment']) // コメントです ?></textarea>
        </div>

        <input type="submit" value="変更する" class="btn btn-primary">
    </form>

    <hr class="mb-4">

    <form action="file.php?submission_id=<?=$submission_id?>" method="post" enctype="multipart/form-data" id="file-upload-form">
        <p>登録済ファイル: <?=h($record['file_name_original']) // ファイルのオリジナル名です ?></p>
        <div class="form-group mb-4">
            <label for="file">変更ファイル:</label>
            <label id="drop-area">
                <input type="file" id="file">ファイルを追加してください。</input>
            </label>
        </div>
        <input type="submit" value="変更する" class="btn btn-primary mb-3" id="file-upload-button">
        <br>
    </form>

    <hr>
    <a href="../index.php">←課題提出ページ</a>

    <?=f('../../components/footer.html')?>
    <script src="../../components/drag_and_drop.js"></script>
</body>
</html>