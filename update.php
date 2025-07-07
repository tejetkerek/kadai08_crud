<?php

//1. POSTデータ取得
$name = $_POST['name'];
$start_date = $_POST['start_date'];
$start_hour = intval($_POST['start_hour']);
$start_minute = intval($_POST['start_minute']);
$start_period = $_POST['start_period'];
$end_date = $_POST['end_date'];
$end_hour = intval($_POST['end_hour']);
$end_minute = intval($_POST['end_minute']);
$end_period = $_POST['end_period'];
$break_minutes = intval($_POST['breaktime']);
$id = $_POST['id'];

// 24時間形式に変換
if ($start_period === 'PM' && $start_hour !== 12) $start_hour += 12;
if ($start_period === 'AM' && $start_hour === 12) $start_hour = 0;

if ($end_period === 'PM' && $end_hour !== 12) $end_hour += 12;
if ($end_period === 'AM' && $end_hour === 12) $end_hour = 0;

// datetime形式に変換
$workstart = $start_date . ' ' . 
             str_pad($start_hour, 2, '0', STR_PAD_LEFT) . ':' . 
             str_pad($start_minute, 2, '0', STR_PAD_LEFT) . ':00';

$workend = $end_date . ' ' . 
           str_pad($end_hour, 2, '0', STR_PAD_LEFT) . ':' . 
           str_pad($end_minute, 2, '0', STR_PAD_LEFT) . ':00';

// 労働時間を計算
$startDateTime = new DateTime($workstart);
$endDateTime = new DateTime($workend);

// 総勤務時間を秒単位で計算
$totalWorkSeconds = $endDateTime->getTimestamp() - $startDateTime->getTimestamp();

// 休憩時間を秒単位で引く
$netWorkSeconds = $totalWorkSeconds - ($break_minutes * 60);

// 時間に変換（小数点以下2桁）
$workHours = round($netWorkSeconds / 3600, 2);

// 負の値の場合は0に設定
if ($workHours < 0) $workHours = 0;

//2. DB接続します
//*** function化する！  *****************
require_once("funcs.php");
$pdo = db_conn();   

// 現在のindateを取得
$currentStmt = $pdo->prepare('SELECT indate FROM member_table WHERE id = :id;');
$currentStmt->bindValue(':id', $id, PDO::PARAM_INT);
$currentStmt->execute();
$currentData = $currentStmt->fetch();

//３．データ登録SQL作成
$stmt = $pdo->prepare("UPDATE member_table
                        SET name = :name,
                        workstart = :workstart,
                        workend = :workend,
                        breaktime = :breaktime,
                        work_hours = :work_hours,
                        indate = :indate
                        WHERE id = :id;
                        ");

// breaktimeをTIME型形式に変換（分 → HH:MM:SS）
$breakHours = floor($break_minutes / 60);
$breakMins = $break_minutes % 60;
$breakTime = sprintf('%02d:%02d:00', $breakHours, $breakMins);

// 数値の場合 PDO::PARAM_INT
// 文字の場合 PDO::PARAM_STR
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':workstart', $workstart, PDO::PARAM_STR);
$stmt->bindValue(':workend', $workend, PDO::PARAM_STR);
$stmt->bindValue(':breaktime', $breakTime, PDO::PARAM_STR);
$stmt->bindValue(':work_hours', $workHours, PDO::PARAM_STR);
$stmt->bindValue(':indate', $currentData['indate'], PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT); //PARAM_INTなので注意

$status = $stmt->execute(); //実行

//４．データ登録処理後
if ($status === false) {
    //*** function化する！******\
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} else {
    redirect("read.php");
    // //*** function化する！*****************
    // header('Location: select.php');
    // exit();
}
