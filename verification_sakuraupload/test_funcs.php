<?php
// エラー表示設定
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>funcs.php 読み込みテスト</h1>";

try {
    echo "<p>funcs.phpを読み込み中...</p>";
    require_once("funcs.php");
    echo "<p style='color: green;'>✓ funcs.php読み込み成功</p>";
    
    echo "<p>db_conn()関数をテスト中...</p>";
    $pdo = db_conn();
    echo "<p style='color: green;'>✓ db_conn()関数成功</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ エラー: " . $e->getMessage() . "</p>";
    echo "<p>エラー詳細: " . print_r($e, true) . "</p>";
}
?> 