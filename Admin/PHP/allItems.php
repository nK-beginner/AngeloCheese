<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once __DIR__.'/../Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../Backend/config.php';
    require_once __DIR__.'/../PHP/function/functions.php';
    require_once __DIR__.'/../PHP/function/dataControl.php';

    // 画像データ取得
    $products = fncGetData($pdo2, 1, 1);

    // カテゴリー商品分割
    $categorizedProducts = [];
    foreach($products as $product) {
        $category = $product['category_name'];
        $categorizedProducts[$category][] = $product;
    }

    // CSV出力
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        exportCSV($pdo2);
    }
?>