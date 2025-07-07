<?php

//1. DB接続
/**
 * require_onceでfuncs.phpを取得
 * 関数を使えるようにする。
 */

require_once("funcs.php");
$pdo = db_conn();   

// //func.phpファイルをつかっているので削除
// try {
//     $db_name = 'gs_db_class3';    //データベース名
//     $db_id   = 'root';      //アカウント名
//     $db_pw   = '';      //パスワード：MAMPは'root'
//     $db_host = 'localhost'; //DBホスト
//     $pdo = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
// } catch (PDOException $e) {
//     exit('DB Connection Error:' . $e->getMessage());
// }

//２．データ登録SQL作成
$stmt = $pdo->prepare('SELECT * FROM member_table ORDER BY indate DESC;');
$status = $stmt->execute();

//３．データ表示
$view = '';
if ($status === false) {
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} else {
    $view .= '<table class="member-table">';
    $view .= '<thead>';
    $view .= '<tr>';
    $view .= '<th>ID</th>';
    $view .= '<th>名前</th>';
    $view .= '<th>勤務開始</th>';
    $view .= '<th>勤務終了</th>';
    $view .= '<th>休憩時間</th>';
    $view .= '<th>労働時間</th>';
    $view .= '<th>登録日</th>';
    $view .= '<th>操作</th>';
    $view .= '</tr>';
    $view .= '</thead>';
    $view .= '<tbody>';
    
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // デバッグ用：データベースの値を確認
        $workHours = floatval($result['work_hours']);
        
        // 労働時間が0の場合は再計算
        if ($workHours <= 0) {
            $startDateTime = new DateTime($result['workstart']);
            $endDateTime = new DateTime($result['workend']);
            
            // breaktimeをTIME型から分に変換
            $breakTime = $result['breaktime'];
            if ($breakTime) {
                $breakParts = explode(':', $breakTime);
                $breakMinutes = intval($breakParts[0]) * 60 + intval($breakParts[1]) + (intval($breakParts[2]) / 60);
            } else {
                $breakMinutes = 0;
            }
            
            // 総勤務時間を秒単位で計算
            $totalWorkSeconds = $endDateTime->getTimestamp() - $startDateTime->getTimestamp();
            
            // 休憩時間を秒単位で引く
            $netWorkSeconds = $totalWorkSeconds - ($breakMinutes * 60);
            
            // 時間に変換（小数点以下2桁）
            $workHours = round($netWorkSeconds / 3600, 2);
            
            // 負の値の場合は0に設定
            if ($workHours < 0) $workHours = 0;
        }
        
        // 時間と分に変換
        $hours = floor($workHours);
        $minutes = round(($workHours - $hours) * 60);
        
        $workTimeDisplay = $hours . '時間 ' . $minutes . '分';
        
        // 日時表示用のDateTimeオブジェクトを作成
        $startDateTime = new DateTime($result['workstart']);
        $endDateTime = new DateTime($result['workend']);
        
        // breaktimeをTIME型から分に変換して表示
        $breakTime = $result['breaktime'];
        if ($breakTime) {
            $breakParts = explode(':', $breakTime);
            $breakMinutes = intval($breakParts[0]) * 60 + intval($breakParts[1]) + (intval($breakParts[2]) / 60);
        } else {
            $breakMinutes = 0;
        }
        
        $view .= '<tr>';
        $view .= '<td>' . $result['id'] . '</td>';
        $view .= '<td>' . htmlspecialchars($result['name']) . '</td>';
        $view .= '<td class="time-column">' . $startDateTime->format('m/d H:i') . '</td>';
        $view .= '<td class="time-column">' . $endDateTime->format('m/d H:i') . '</td>';
        $view .= '<td class="time-column">' . $breakMinutes . '分</td>';
        $view .= '<td class="time-column">' . $workTimeDisplay . '</td>';
        $view .= '<td class="time-column">' . date('m/d H:i', strtotime($result['indate'])) . '</td>';
        $view .= '<td class="action-buttons">';
        $view .= '<a href="detail.php?id=' . $result['id'] . '" class="btn btn-update">更新</a>';
        $view .= '<a href="delete.php?id=' . $result['id'] . '" class="btn btn-delete" onclick="return confirm(\'本当に削除しますか？\')">削除</a>';
        $view .= '</td>';
        $view .= '</tr>';
    }
    
    $view .= '</tbody>';
    $view .= '</table>';
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>出勤人員一覧</title>
    <link href="css/style.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 2em;
        }
        
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .nav {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .nav a {
            color: #007bff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .nav a:hover {
            background-color: #e9ecef;
        }
        
        .content {
            padding: 20px;
        }
        
        .member-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .member-table th {
            background: #f8f9fa;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }
        
        .member-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        .time-column {
            text-align: right;
        }
        
        .member-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .btn-update {
            background-color: #28a745;
            color: white;
        }
        
        .btn-update:hover {
            background-color: #218838;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            text-align: center;
            transition: all 0.3s ease;
            border: none;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #6c757d;
            margin-top: 8px;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .member-table {
                font-size: 0.875rem;
            }
            
            .member-table th,
            .member-table td {
                padding: 8px 4px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }
            
            .btn {
                padding: 4px 8px;
                font-size: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>出勤人員一覧</h1>
        </div>
        
        <div class="nav">
            <a href="register.php">新規登録</a>
            <a href="index.php">ダッシュボード</a>
        </div>
        
        <div class="content">
            <?php
            // 統計情報を取得
            $statsStmt = $pdo->prepare('SELECT COUNT(*) as total_members, SUM(work_hours) as total_work_hours FROM member_table;');
            $statsStmt->execute();
            $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
            
            // 労働時間合計を時間と分に変換
            $totalWorkHours = floatval($stats['total_work_hours']);
            $totalHours = floor($totalWorkHours);
            $totalMinutes = round(($totalWorkHours - $totalHours) * 60);
            $totalWorkTimeDisplay = $totalHours . '時間 ' . $totalMinutes . '分';
            ?>
            
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['total_members'] ?></div>
                    <div class="stat-label">登録人員数</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $totalWorkTimeDisplay ?></div>
                    <div class="stat-label">労働時間合計</div>
                </div>
            </div>
            
            <?= $view ?>
        </div>
    </div>
</body>

</html>
