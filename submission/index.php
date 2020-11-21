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
<body>
    <h1>課題提出ページ</h1>
    <hr>
    <p>ログインユーザー: <?=h($user_id) // 現在ログインしているユーザID ?></p>
    <hr>
    <form action="insert.php" method="post" enctype="multipart/form-data">
        <p>アップロードするファイルの選択:</p>
        <table border="1">
            <tr><th>タイトル</th><td><input type="text" name="title" size="50"></td></tr>
            <tr><th>ファイル</th><td><input type="file" name="submission" size="50"></td></tr>
            <tr><th>コメント</th><td><textarea name="comment" cols="50" rows="5"></textarea></td></tr>
        </table>
        <p style="color: red"><?=h($error_message) // エラーメッセージを表示 ?></p>
        <input type="submit" value="アップロード">
    </form>
    <hr>
    <p>課題提出状況</p>
<?php $i = 1; while($record = $records->fetchArray()): // ファイル上部で取得したレコードの数だけループさせる ?>
    <table border="1">
            <tr>
                <th width="20"><?=$i?></th>
                <th colspan="3"><?=h($record['title']) // アップロードしたときにつけた名前 ?></th>
            </tr>
            <tr>
                <td colspan="2">
                    ファイル名: <?=h($record['file_name_original']) // アップロードしたファイルの元々の名前 ?>
                </td>
                <td width="180">
                    更新日時: <?=h($record['updated_at']) // submissionのアップロード（もしくは変更）の日時?>
                </td>
                <td width="40">
                    <a href="update/index.php?submission_id=<?=h($record['id']) // submissionのID ?>">変更</a>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <?=h($record['comment'])?>
                </td>
            </tr>
    </table>
    <br>
<?php $i++; endwhile // ループここまで ?>

<?php if ($i === 1): // submissionsテーブルから該当するレコードを一つも取得できなかったということなので ?>
    <p>現在までに提出した課題はありません。</p>
<?php endif ?>

    <hr>
    <p><a href="../home.php">ホームに戻る</a></p>
    <a href="../logout.php">ログアウト</a>

    <?=f('../components/footer.html')?>
</body>
</html>