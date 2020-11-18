<?php
// 管理者ユーザID
putenv('admin_user_id=admin');

// タイムゾーン
putenv('timezone=Asia/Tokyo');

// セッションの持続時間（秒）
putenv('session_lifetime=900');

// データベース(SQLite)のパス
putenv('database_path=db.sqlite3');

/*
Webルートの指定

このファイルが https://example.com/app/common/env.php に位置する場合、
'$web_root=/app' と指定する
https://example.com/common/env.php の場合は、
'$web_root=' と指定する
*/
putenv('web_root=');
