<?php
/*

task/index.php [表示]

課題を表示する。

*/

require('../common/common.php');

// ログインしていないなら、index.phpに飛ばす
login_check();

// ログインしているユーザのユーザID
$name = $_SESSION['user_id'];

// GETでエラーメッセージが渡されているなら、変数に入れておく
// （渡されていない場合は、空文字を入れる）
$error_message = $_GET['error_message'] ?? '';

// leaning テーブルに保存されている全てのレコードを取得する
$sql = 'SELECT * FROM tasks ORDER BY id ASC';
$records = $db -> query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>教材管理ページ</title>
    <?=f('../components/head.html')?>
</head>
<body>
    <h1>教材管理ページ</h1>
    <p>ログインユーザ: <?=h($name) // ログインしているユーザID ?></p>
    <hr>
    <p>公開されている課題</p>

<?php $i = 1; while($record = $records->fetchArray()): // tasks データベースに保存されているレコードの数だけ繰り返す ?>
    <table border="1">
            <tr>
                <th width="20"><?=$i?></th>
                <th colspan="3"><?=h($record['user_id'])?></th>
            </tr>
            <tr>
                <td colspan="2">
                    <a href="../uploaded_files/task/<?=h($record['file_name_internal'])?>">
                        <?=h($record['title'])?>
                    </a>
                </td>
                <td width="180">公開日時: <?=h($record['created_at'])?></td>
                <?php if(is_admin()): // 管理者のみに表示（ここから）?>
                    <td width="40">
                        <a href="delete.php?task_id=<?=h($record['id'])?>">消去</a>
                    </td>
                <?php endif // 管理者の場合のみ（ここまで）?>
            </tr>
    </table>
    <br>
<?php $i++; endwhile ?>

<?php if($i === 1): // tasksデータベースにレコードが一つもない場合なので ?>
    <p>登録されている情報はありません</p>
<?php endif ?>

<?php if(is_admin()): // 管理者のみに表示 (ここから) ?>
    <hr>
    <form action="insert.php" method="post" enctype="multipart/form-data">
        <p>アップロードするファイルの選択: </p>
        <table border="1">
            <tr><th>題名</th><td><input type="text" name="title" size="30"></td></tr>
            <tr><th>file</th><td><input type="file" name="task" size="50"></td></tr>
        </table>
        <p style="color: red"><?=h($error_message)?></p>
        <input type="submit" value="アップロード">
    </form>
<?php endif // 管理者のみの表示 (ここまで) ?>

    <hr>
    <p><a href="../home.php">ホームに戻る</a></p>
    <a href="../logout.php">ログアウト</a>

    <?=f('../components/footer.html')?>
</body>
</html>
