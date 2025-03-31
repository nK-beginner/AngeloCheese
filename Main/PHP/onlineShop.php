<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/../../Admin/backend/connection.php';
    require_once __DIR__.'/../PHP/function/getImages.php';

    // 画像データ取得
    $products = fncGetImages($pdo2, 1, 1);

    // カテゴリーごとに商品分割
    $categorizedProducts = [];
    foreach($products as $product) {
        $category = $product['category_name'];
        $categorizedProducts[$category][] = $product;
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['productId'] = $_POST['productId'];

        header('Location: product.php');
        exit;
    }
?>