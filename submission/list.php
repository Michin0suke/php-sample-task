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
<body class="container mt-5">
    <h1>提出されている課題一覧</h1>
    <hr>
    <p class="text-secondary">現在までの提出状況</p>

    <table class="table table-hover table-striped" class="mb-5">
        <thead class="thead-light">
            <th scope="col">No</th>
            <th scope="col">タイトル</th>
            <th scope="col">提出者ID</th>
            <th scope="col">ファイル</th>
            <th scope="col">投稿日時</th>
            <th scope="col">コメント</th>
        </thead>
        <tbody>
            <?php $i = 1; while($record = $records->fetchArray()): // submissionsテーブルの全レコードの数だけループさせる ?>
                <tr>
                    <th scope="row"><?=$i?></th>
                    <td><?=h($record['title']) // ファイルの名前?></td>
                    <td>提出者: <?=h($record['user_id']) // submissionを登録したユーザのユーザID ?></td>
                    <td>
                        <a href="../uploaded_files/submission/<?=h($record['file_name_internal']) // ファイルがサーバに実際に保存されている名前 ?>">
                            <?=h($record['file_name_original']) // ファイルのアップロード時の元々の名前 ?>
                        </a>
                    </td>
                    <td><?=h($record['updated_at']) // submissionsにレコードが保存された(もしくは修正された)日時 ?></td>
                    <td><?=h($record['comment']) // コメント ?></td>
                </tr>
            <?php $i++; endwhile // ループここまで ?>
        </tbody>
    </table>

    <div class="mb-5">
        <a href="../home.php" class="btn btn-outline-primary">ホームに戻る</a>
        <a href="../logout.php" class="btn btn-outline-danger ml-3">ログアウト</a>
    </div>

    <?=f('../components/footer.html')?>
</body>
</html>