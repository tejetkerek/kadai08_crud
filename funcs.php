<?php
//XSS対応（ echoする場所で使用！）
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//DB接続関数：db_conn() 
//※関数を作成し、内容をreturnさせる。
//※ DBname等、今回の授業に合わせる。
function db_conn(){
    try {
        $db_name = '**************';    //データベース名
        $db_id   = '*************';      //アカウント名
        $db_pw   = '************';      //パスワード：MAMPは'root'
        $db_host = '****************'; //DBホスト
        $pdo = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
        return $pdo;    //関数の外でもPDOが動くように1行追加
    } catch (PDOException $e) {
        exit('DB Connection Error:' . $e->getMessage());
    }
}

//SQLエラー関数：sql_error($stmt)


//リダイレクト関数: redirect($file_name)
function redirect ($file_name){
    header('Location:' . $file_name);
    exit();
}
?>