<?php

// select.phpから送られてくるidを$idで明記する
$id = $_GET["id"];

// DB接続
require_once("funcs.php");
$pdo = db_conn();   

//２．データ登録SQL作成

// SLECTでallではんくて、idを指定して絞ってもってくる
$stmt = $pdo->prepare('SELECT * FROM member_table WHERE id = :id;');
$stmt->bindValue(":id", $id, PDO::PARAM_INT);

$status = $stmt->execute();


//３．データ表示
$result = '';
if ($status === false) {
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} else {
    $result = $stmt->fetch(); // データ取得
}

// 日時データを分解
$startDateTime = new DateTime($result['workstart']);
$endDateTime = new DateTime($result['workend']);

$startDate = $startDateTime->format('Y-m-d');
$startTime = $startDateTime->format('H:i');
$endDate = $endDateTime->format('Y-m-d');
$endTime = $endDateTime->format('H:i');

// 12時間形式に変換
$startHour = intval($startDateTime->format('H'));
$startMinute = intval($startDateTime->format('i'));
$startPeriod = $startHour >= 12 ? 'PM' : 'AM';
if ($startHour > 12) $startHour -= 12;
if ($startHour === 0) $startHour = 12;

$endHour = intval($endDateTime->format('H'));
$endMinute = intval($endDateTime->format('i'));
$endPeriod = $endHour >= 12 ? 'PM' : 'AM';
if ($endHour > 12) $endHour -= 12;
if ($endHour === 0) $endHour = 12;

// breaktimeをTIME型から分に変換
$breakTime = $result['breaktime'];
if ($breakTime) {
    $breakParts = explode(':', $breakTime);
    $breakMinutes = intval($breakParts[0]) * 60 + intval($breakParts[1]) + (intval($breakParts[2]) / 60);
} else {
    $breakMinutes = 0;
}

?>

<!--
２．HTML
以下にindex.phpのHTMLをまるっと貼り付ける！
(入力項目は「登録/更新」はほぼ同じになるから)
※form要素 input type="hidden" name="id" を１項目追加（非表示項目）
※form要素 action="update.php"に変更
※input要素 value="ここに変数埋め込み"
-->

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>出勤人員編集</title>
    <link href="css/style.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 600px;
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
            margin-right: 10px;
        }
        
        .nav a:hover {
            background-color: #e9ecef;
        }
        
        .content {
            padding: 20px;
        }
        
        .time-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }
        .time-input {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .am-pm-buttons {
            display: flex;
            gap: 5px;
        }
        .am-pm-btn {
            padding: 5px 10px;
            border: 1px solid #ccc;
            background: #f0f0f0;
            cursor: pointer;
        }
        .am-pm-btn.active {
            background: #007bff;
            color: white;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>出勤人員編集</h1>
        </div>
        
        <div class="nav">
            <a href="read.php">登録済み一覧</a>
            <a href="index.php">ダッシュボード</a>
        </div>
        
        <div class="content">
        
        <form method="POST" action="update.php">
            <div>
                <fieldset>
                    <legend>出勤人員編集</legend>
                    <div>
                        <label>名前：<input type="text" name="name" value="<?= $result["name"] ?>" required></label>
                    </div>
                    
                    <div>
                        <label>勤務開始日：<input type="date" name="start_date" value="<?= $startDate ?>" required></label>
                    </div>
                    
                    <div class="time-input-group">
                        <label>勤務開始時間：</label>
                        <div class="time-input">
                            <select name="start_hour">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= $startHour == $i ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                            <span>:</span>
                            <select name="start_minute">
                                <option value="00" <?= $startMinute == 0 ? 'selected' : '' ?>>00</option>
                                <option value="30" <?= $startMinute == 30 ? 'selected' : '' ?>>30</option>
                            </select>
                            <div class="am-pm-buttons">
                                <button type="button" class="am-pm-btn <?= $startPeriod === 'AM' ? 'active' : '' ?>" data-type="start" data-period="AM" onclick="setAMPM(this)">AM</button>
                                <button type="button" class="am-pm-btn <?= $startPeriod === 'PM' ? 'active' : '' ?>" data-type="start" data-period="PM" onclick="setAMPM(this)">PM</button>
                            </div>
                            <input type="hidden" name="start_period" value="<?= $startPeriod ?>">
                        </div>
                    </div>
                    
                    <div>
                        <label>勤務終了日：<input type="date" name="end_date" value="<?= $endDate ?>" required></label>
                    </div>
                    
                    <div class="time-input-group">
                        <label>勤務終了時間：</label>
                        <div class="time-input">
                            <select name="end_hour">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= $endHour == $i ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                            <span>:</span>
                            <select name="end_minute">
                                <option value="00" <?= $endMinute == 0 ? 'selected' : '' ?>>00</option>
                                <option value="30" <?= $endMinute == 30 ? 'selected' : '' ?>>30</option>
                            </select>
                            <div class="am-pm-buttons">
                                <button type="button" class="am-pm-btn <?= $endPeriod === 'AM' ? 'active' : '' ?>" data-type="end" data-period="AM" onclick="setAMPM(this)">AM</button>
                                <button type="button" class="am-pm-btn <?= $endPeriod === 'PM' ? 'active' : '' ?>" data-type="end" data-period="PM" onclick="setAMPM(this)">PM</button>
                            </div>
                            <input type="hidden" name="end_period" value="<?= $endPeriod ?>">
                        </div>
                    </div>
                    
                    <div>
                        <label>休憩時間：</label>
                        <select name="breaktime">
                            <?php
                            // 15分単位で0分から8時間（480分）まで
                            for ($i = 0; $i <= 480; $i += 15) {
                                $hours = floor($i / 60);
                                $minutes = $i % 60;
                                $displayText = '';
                                if ($hours > 0) {
                                    $displayText = $hours . '時間';
                                    if ($minutes > 0) {
                                        $displayText .= $minutes . '分';
                                    }
                                } else {
                                    $displayText = $minutes . '分';
                                }
                                $selected = ($i == $breakMinutes) ? 'selected' : '';
                                echo "<option value=\"$i\" $selected>$displayText</option>";
                            }
                            ?>
                        </select>
                    </div>
                    

                    
                    <input type="hidden" name="id" value="<?= $result["id"] ?>">
                    
                    <input type="submit" value="更新" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2); margin-top: 20px;">
                </fieldset>
            </div>
        </form>
        </div>
    </div>

    <script>
        function setAMPM(button) {
            const type = button.dataset.type;
            const period = button.dataset.period;
            
            // 同じグループのボタンのアクティブ状態を切り替え
            const buttons = button.parentElement.querySelectorAll('.am-pm-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            // 隠しフィールドを更新
            const hiddenField = button.parentElement.parentElement.querySelector(`input[name="${type}_period"]`);
            hiddenField.value = period;
        }


    </script>
</body>

</html>

