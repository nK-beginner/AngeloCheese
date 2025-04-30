<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__.'/../Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../Backend/config.php';
    require_once __DIR__.'/../PHP/function/functions.php';
    require_once __DIR__.'/../PHP/function/dataControl.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    // 一覧表示
    try {
        $products = fncGetData($pdo2, 1, 1);

    } catch(PDOException $e) {
        error_log('データベース接続エラー:' . $e -> getMessage());

        $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productId'])) {
        $_SESSION['edit_item_id'] = intval($_POST['productId']);

        header('Location: itemEdit.php');
        exit;
    }
?>