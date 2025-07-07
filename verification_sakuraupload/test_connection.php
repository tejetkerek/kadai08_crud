<?php
// エラー表示設定
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>データベース接続テスト</h1>";

try {
    // データベース接続情報
    $db_name = 'tsekeiki_inventory';
    $db_id   = 'tsekeiki_inventory';
    $db_pw   = 'inventory0715';
    $db_host = 'mysql3108.db.sakura.ne.jp';
    
    echo "<p>接続情報:</p>";
    echo "<ul>";
    echo "<li>ホスト: $db_host</li>";
    echo "<li>データベース: $db_name</li>";
    echo "<li>ユーザー: $db_id</li>";
    echo "</ul>";
    
    // PDO接続
    $pdo = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✓ データベース接続成功</p>";
    
    // テーブル一覧を取得
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>存在するテーブル:</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ PDOエラー: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ 一般エラー: " . $e->getMessage() . "</p>";
}
?> 