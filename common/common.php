<?php
/*

common/common.php [ロジック]

ほとんど全てのファイルから参照される共通ファイル。

*/

// 環境変数の読み込み
include_once('env.php');

// データベースへの接続（SQLiteを使用）
class MyDB extends SQLite3 {
    function __construct() {
        $this -> open(__DIR__.'/'.getenv('database_path'));
    }
}
$db = new MyDB();
if(!$db) die('接続失敗です。'.$sqlite_error);

// 管理者IDの指定
$admin_user_id = getenv('admin_user_id');

// デフォルトタイムゾーンの設定（ファイル名の設定に必要）
date_default_timezone_set(getenv('timezone'));

// セッションの設定

    // セッションの持続時間をすぎると、100%の確率でセッション情報を削除する
    ini_set('session.gc_divisor', 1);

    // セッションの持続時間の設定
    ini_set('session.gc_maxlifetime', getenv('session_lifetime'));

    // セッションの開始
    session_start();


// Webルートの指定
$web_root = getenv('web_root');

// ログインしていない場合に、index.phpに転送する関数
function login_check() {
    global $web_root;

    // ログインしている場合は、$_SESSION['is_login'] にtrueが入っているはず
    $is_login = $_SESSION['is_login'] ?? false;

    // ログインしていない場合は、index.phpに飛ばす
    if (!$is_login) {
        header("Location: $web_root/index.php?error_message=セッションの有効期限が切れました。再度ログインしてください。");
        exit();
    }
}

// 管理者かどうかを判定し、論理値を返す関数
function is_admin() {
    // globalを使うと関数の外の変数が使える
    global $admin_user_id;
    // 現在のセッションユーザのIDが、このファイル上部で設定した管理者ユーザIDと一致する場合は、true、そうでない場合はfalseを返す
    return $_SESSION['user_id'] === $admin_user_id;
}

// 以下は、ラッパー関数

// SQLエスケープ
function s($str) {
    // globalを使うと関数の外の変数が使える
    global $db;
    return $db -> escapeString($str, ENT_QUOTES);
}

// HTMLエスケープ
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// 外部ファイルをインポートする
function f($path) {
    return file_get_contents($path);
}