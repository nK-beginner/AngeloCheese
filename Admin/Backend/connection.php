




<?php
    $host     = 'localhost';
    $dbname   = 'angelo_cheese_management';
    $username = 'root';
    $password = '';

    try {
        $pdo2 = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8mb4", $username, $password);
        $pdo2 -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      // エラーモードをExceptionに設定
        $pdo2 -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // デフォルトのフェッチモードを連想配列に
        $pdo2 -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              // SQLインジェクション対策（プリペアドステートメントのエミュレーションを無効化）

    } catch(PDOException $e) {
        die("データベース接続エラー：".htmlspecialchars($e -> getMessage(), ENT_QUOTES, 'UTF-8'));
    }
?>