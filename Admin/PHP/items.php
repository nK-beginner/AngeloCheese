<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__.'/../Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../Backend/config.php';
    require_once __DIR__.'/../PHP/function/functions.php';
    require_once __DIR__.'/../PHP/function/dataControl.php';

    $editItem  = null;
    $subImages = [];
    
    $itemId = $_SESSION['edit_item_id'] ?? null;
    
    if ($itemId !== null) {
        try {
            $editItem  = fncGetProduct($pdo2, $itemId);
            $subImages = fncGetSubImages($pdo2, $itemId);

        } catch(PDOException $e) {
            error_log('データベース接続エラー:' . $e->getMessage());
            $_SESSION['errors'] = '商品情報の取得中にエラーが発生しました。';
        }

    } else {
        header('Location: itemEditList.php');
        exit;
    }
?>