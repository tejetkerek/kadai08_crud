<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Simple Test</title>
</head>
<body>
    <h1>Simple PHP Test</h1>
    <?php
    echo "<p>PHP is working!</p>";
    echo "<p>PHP Version: " . phpversion() . "</p>";
    
    // データベース接続テスト
    try {
        require_once("funcs.php");
        $pdo = db_conn();
        echo "<p style='color: green;'>✓ Database connection successful</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html> 