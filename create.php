<?php
// エラー表示設定（デバッグ用）
error_reporting(E_ALL);
ini_set('display_errors', 1);

//1. POSTデータ取得
$members = $_POST['members'];

//2. DB接続します
//*** function化する！  *****************
require_once("funcs.php");
$pdo = db_conn();   

// 各メンバーを処理
foreach ($members as $memberId => $memberData) {
    // 日付と時間を組み合わせてdatetime形式に変換
    $startHour = intval($memberData['start_hour']);
    $startMinute = intval($memberData['start_minute']);
    $startPeriod = $memberData['start_period'];
    
    $endHour = intval($memberData['end_hour']);
    $endMinute = intval($memberData['end_minute']);
    $endPeriod = $memberData['end_period'];
    
    // 24時間形式に変換
    if ($startPeriod === 'PM' && $startHour !== 12) $startHour += 12;
    if ($startPeriod === 'AM' && $startHour === 12) $startHour = 0;
    
    if ($endPeriod === 'PM' && $endHour !== 12) $endHour += 12;
    if ($endPeriod === 'AM' && $endHour === 12) $endHour = 0;
    
    // datetime形式に変換
    $workstart = $memberData['start_date'] . ' ' . 
                 str_pad($startHour, 2, '0', STR_PAD_LEFT) . ':' . 
                 str_pad($startMinute, 2, '0', STR_PAD_LEFT) . ':00';
    
    $workend = $memberData['end_date'] . ' ' . 
               str_pad($endHour, 2, '0', STR_PAD_LEFT) . ':' . 
               str_pad($endMinute, 2, '0', STR_PAD_LEFT) . ':00';
    
    $breakMinutes = intval($memberData['breaktime']);
    
    // 休憩時間をTIME型に変換（HH:MM:SS形式）
    $breakHours = floor($breakMinutes / 60);
    $breakMins = $breakMinutes % 60;
    $breakTime = sprintf('%02d:%02d:00', $breakHours, $breakMins);
    
    // 労働時間を計算
    $startDateTime = new DateTime($workstart);
    $endDateTime = new DateTime($workend);
    
    // 総勤務時間を秒単位で計算
    $totalWorkSeconds = $endDateTime->getTimestamp() - $startDateTime->getTimestamp();
    
    // 休憩時間を秒単位で引く
    $netWorkSeconds = $totalWorkSeconds - ($breakMinutes * 60);
    
    // 時間に変換（小数点以下2桁）
    $workHours = round($netWorkSeconds / 3600, 2);
    
    // 負の値の場合は0に設定
    if ($workHours < 0) $workHours = 0;
    
    //３．データ登録SQL作成
    $stmt = $pdo->prepare(
        'INSERT INTO
            member_table(
                name, workstart, workend, breaktime, work_hours, age, email, indate
            )
        VALUES (
                :name, :workstart, :workend, :breaktime, :work_hours, 0, "", now()
            );'
    );

    // 数値の場合 PDO::PARAM_INT
    // 文字の場合 PDO::PARAM_STR
    $stmt->bindValue(':name', $memberData['name'], PDO::PARAM_STR);
    $stmt->bindValue(':workstart', $workstart, PDO::PARAM_STR);
    $stmt->bindValue(':workend', $workend, PDO::PARAM_STR);
    $stmt->bindValue(':breaktime', $breakTime, PDO::PARAM_STR);
    $stmt->bindValue(':work_hours', $workHours, PDO::PARAM_STR);
    $status = $stmt->execute(); //実行

    //４．データ登録処理後
    if ($status === false) {
        //*** function化する！******\
        $error = $stmt->errorInfo();
        exit('SQLError:' . print_r($error, true));
    }
}

// 全員登録完了後、一覧ページにリダイレクト
redirect("read.php");
