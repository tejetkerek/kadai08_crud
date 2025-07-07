<?php
// エラー表示設定
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>データベース接続テスト</h1>";

try {
    require_once("funcs.php");
    $pdo = db_conn();
    echo "<p style='color: green;'>✓ データベース接続成功</p>";
    
    // テーブル存在確認
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<h2>存在するテーブル:</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ エラー: " . $e->getMessage() . "</p>";
    echo "<p>エラー詳細: " . print_r($e, true) . "</p>";
}
?> 