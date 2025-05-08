<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__.'/../Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../Backend/config.php';
    require_once __DIR__.'/../PHP/function/functions.php';
    require_once __DIR__.'/../PHP/class/product.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    // データ取得・表示
    try {
        $productAll = new Product($pdo2);
        $products = $productAll -> getProductAll();

    } catch(PDOException $e) {
        error_log('データベース接続エラー:' . $e -> getMessage());
        $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
    }

    // データ削除
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        fncCheckCSRF();

        // フォームデータ取得
        $deletingItemIds = $_POST['delete'] ?? [];

        // 削除
        $pdo2 -> beginTransaction();
        try {
            if(!empty($deletingItemIds)) {
                fncHideProducts($pdo2, $deletingItemIds);

                $pdo2 -> commit();

                header('Location: itemDelete.php');
                exit();
            }

        } catch(PDOException $e) {
            $pdo2 -> rollback();

            error_log('データベース接続エラー:' . $e -> getMessage());
            $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
        }

    }
?>