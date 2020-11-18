<?php
/*

logout.php [ロジック]

セッションの情報を全てクリアし、index.phpにリダイレクトする。

*/

// セッションスタートしないとアンセットできないので、一旦始める
session_start();

// $_SESSION に保存されている情報を全てクリアする
session_unset();

// index.phpに飛ばす
header('Location: index.php?error_message=ログアウトしました。');