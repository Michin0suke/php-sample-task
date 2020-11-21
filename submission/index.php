<?php
/*

submission.php [表示]

ログインしているユーザがすでに提出しているタスク一覧を表示する。

*/

require('../common/common.php');

// ログインしていないなら、index.phpに飛ばす
login_check();

// 現在ログインしているユーザのユーザID
$user_id = $_SESSION['user_id'];

// GETでエラーメッセージが渡されている場合は変数に格納
// 渡されていない場合は空文字
$error_message = $_GET['error_message'] ?? '';

// submissionsテーブルに保存されているレコードのうち、現在ログインしているユーザが投稿したものだけを取得
$stmt = $db -> prepare('SELECT * FROM submissions WHERE user_id = :user_id ORDER BY id ASC');
$stmt -> bindValue(':user_id', $user_id);
$records = $stmt -> execute();
?>

<!DOCTYPE html>
<html>
<head>
    <title>課題提出ページ</title>
    <?=f('../components/head.html')?>
</head>
<body class="container mt-5">
    <h1>課題提出ページ</h1>
    <p class="text-secondary">ログインユーザー: <?=h($user_id) // 現在ログインしているユーザID ?></p>
    <hr class="mb-5">
    <form action="insert.php" method="post" enctype="multipart/form-data" class="mb-5">
        <h2 class="mb-4">アップロードするファイルの選択</h2>
        <div class="form-group mb-4">
            <label for="title">タイトル:</label>
            <input type="text" name="title" size="50" id="title" class="form-control">
        </div>
        <div class="form-group mb-4">
            <label for="file">ファイル:</label>
            <input type="file" name="submission" size="50" id="file" class="form-control-file">
        </div>
        <div class="form-group mb-4">
            <label for="comment">コメント:</label>
            <textarea name="comment" rows="5" id="comment" class="form-control"></textarea>
        </div>
        <p style="color: red"><?=h($error_message) // エラーメッセージを表示 ?></p>
        <input type="submit" value="アップロード" class="btn btn-primary">
    </form>
    <hr class="mb-5">
    <h2 class="mb-5">課題提出状況</h2>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">タイトル</th>
                <th scope="col">ファイル名</th>
                <th scope="col">提出日時</th>
                <th scope="col">コメント</th>
                <th scope="col">変更</th>
            </tr>
        </thead>
        <tbody>
<?php $i = 1; while($record = $records->fetchArray()): // ファイル上部で取得したレコードの数だけループさせる ?>
            <tr>
                <th scope="row"><?=$i?></th>
                <td><?=h($record['title']) // アップロードしたときにつけた名前 ?></td>
                <td>ファイル名: <?=h($record['file_name_original']) // アップロードしたファイルの元々の名前 ?></td>
                <td>更新日時: <?=h($record['updated_at']) // submissionのアップロード（もしくは変更）の日時?></td>
                <td><?=h($record['comment'])?></td>
                <td>
                    <a href="update/index.php?submission_id=<?=h($record['id']) // submissionのID ?>" class="btn btn-info">変更</a>
                </td>
            </tr>
<?php $i++; endwhile // ループここまで ?>
        </tbody>
        <tfoot>
            <?php if ($i === 1): // submissionsテーブルから該当するレコードを一つも取得できなかったということなので ?>
                <td colspan="6" class="text-secondary">現在までに提出した課題はありません。</td>
            <?php endif ?>
        </tfoot>
    </table>

    <hr class="mb-5">

    <div class="mb-5">
        <a href="../home.php" class="btn btn-outline-primary">ホームに戻る</a>
        <a href="../logout.php" class="btn btn-outline-danger ml-3">ログアウト</a>
    </div>

    <?=f('../components/footer.html')?>
</body>
</html>