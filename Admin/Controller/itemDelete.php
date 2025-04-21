<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__.'/../Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../PHP/function/functions.php';
    require_once __DIR__.'/../PHP/function/dataControl.php';
    require_once __DIR__.'/../Model/itemDelete.php';

    $productModel = new ProductModel($pdo2);
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        fncCheckCSRF();
        $deletingItemIds = $_POST['delete'] ?? [];

        try {
            if (!empty($deletingItemIds)) {
                $pdo2->beginTransaction();
                $productModel->hideProducts($deletingItemIds);
                $pdo2->commit();

                header('Location: itemDelete.php');
                exit();
            }
            
        } catch (PDOException $e) {
            $pdo2->rollBack();
            error_log('削除エラー: ' . $e->getMessage());
            $errors[] = '削除に失敗しました。もう一度お試しください。';
        }
    }

    try {
        $products = $productModel->getAllVisibleProducts();

    } catch (PDOException $e) {
        error_log('取得エラー: ' . $e->getMessage());
        $errors[] = '商品情報の取得に失敗しました。';
    }

    $_SESSION['errors'] = $errors;

    // 表示ファイル読み込み
    require_once __DIR__.'/../View/itemDelete.php';
?>