<?php
/*

submission/list.php [表示]

home.phpからのみアクセスされる。
管理者のみが閲覧できる。
すでに提出された全員分のタスクを閲覧できる。

*/

require('../common/common.php');

// ログインしていないなら、index.phpに飛ばす
login_check();

// 管理者でない場合は、home.phpに飛ばす
if (!is_admin()) {
    header('Location: home.php');
    exit();
}

// 現在ログインしているユーザのユーザID
$user_id = $_SESSION['user_id'];

// submissionsテーブルから全てのレコードを取得する
$sql = sprintf('SELECT * FROM submissions ORDER BY id ASC');
$records = $db->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>課題の提出状況</title>
    <?=f('../components/head.html')?>
</head>
<body>
    <h1>提出されている課題一覧</h1>
    <hr>
    <p>現在までの提出状況</p>

<?php $i = 1; while($record = $records->fetchArray()): // submissionsテーブルの全レコードの数だけループさせる ?>

        <table border="1">
            <tr>
                <th width="20">
                    <?=$i?>
                </th>
                <th colspan="3">
                    <?=h($record['title']) // ファイルの名前?>
                </th>
            </tr>
            <tr>
                <td colspan="2">
                    提出者: <?=h($record['user_id']) // submissionを登録したユーザのユーザID ?>
                </td>
                <td width="180">
                    <a href="../uploaded_files/submission/<?=h($record['file_name_internal']) // ファイルがサーバに実際に保存されている名前 ?>">
                        <?=h($record['file_name_original']) // ファイルのアップロード時の元々の名前 ?>
                    </a>
                </td>
                <td width="80">
                    <?=h($record['updated_at']) // submissionsにレコードが保存された(もしくは修正された)日時 ?>
                </td>
            </tr>
            <tr>
                <td width="50">
                    コメント
                </td>
                <td colspan="3">
                    <?=h($record['comment']) // コメント ?>
                </td>
            </tr>
        </table>

<?php $i++; endwhile // ループここまで ?>

    <p><a href="../home.php">ホームに戻る</a></p>
    <a href="../logout.php">ログアウト</a>

    <?=f('../components/footer.html')?>
</body>
</html>