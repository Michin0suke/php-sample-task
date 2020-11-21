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
<body class="container mt-5">
    <h1 class="mb-3">教材管理ページ</h1>
    <p class="text-secondary justify-content-end">ログインユーザ: <?=h($name) // ログインしているユーザID ?></p>

    <hr class="mb-5">
    
    <div class="mb-5">
        <h2 class="mb-3">公開されている課題</h2>

    <table class="table table-hover">
        <thead class="thead-light">
            <tr>
                <th scope="col">No</th>
                <th scope="col">ユーザーID</th>
                <th scope="col">タイトル</th>
                <th scope="col">提出日時</th>
                <?php if(is_admin()): // 管理者のみに表示（ここから）?>
                    <th scope="col">消去</th>
                <?php endif // 管理者の場合のみ（ここまで）?>
            </tr>
        </thead>
        
        <?php $i = 1; while($record = $records->fetchArray()): // tasks データベースに保存されているレコードの数だけ繰り返す ?>
            <tbody>
                <tr>
                    <td scope="row"><?=$i?></td>
                    <td scope="row"><?=h($record['user_id'])?></td>
                    <td scope="row">
                        <a href="../uploaded_files/task/<?=h($record['file_name_internal'])?>">
                            <?=h($record['title'])?>
                        </a>
                    </td>
                    <td scope="row">公開日時: <?=h($record['created_at'])?></td>

                    <?php if(is_admin()): // 管理者のみに表示（ここから）?>
                        <td scope="row">
                            <a href="delete.php?task_id=<?=h($record['id'])?>" class="btn btn-danger">消去</a>
                        </td>
                    <?php endif // 管理者の場合のみ（ここまで）?>
                </tr>
            </tbody>
        <?php $i++; endwhile ?>
    </table>

    <?php if($i === 1): // tasksデータベースにレコードが一つもない場合なので ?>
        <p class="text-muted">登録されている情報はありません</p>
    <?php endif ?>
    </div>
    

    <div class="mb-5">
    <?php if(is_admin()): // 管理者のみに表示 (ここから) ?>
        <h2 class="mb-3">アップロードするファイルの選択: </h2>
        
        <form action="insert.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">タイトル:</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>
            <div class="form-group">
                <label for="file">ファイル:</label>
                <input type="file" name="task" id="file" class="form-control-file">
            </div>
            <p class="text-danger mb-5"><?=h($error_message)?></p>
            <input type="submit" value="アップロード" class="btn btn-primary">
        </form>
    <?php endif // 管理者のみの表示 (ここまで) ?>
    </div>

    <hr>
    
    <div class="mb-5">
        <a href="../home.php" class="btn btn-outline-primary">ホームに戻る</a>
        <a href="../logout.php" class="btn btn-outline-danger ml-3">ログアウト</a>
    </div>

    <?=f('../components/footer.html')?>
</body>
</html>
