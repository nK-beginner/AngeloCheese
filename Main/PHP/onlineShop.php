<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/../../Admin/backend/connection.php';

    // 画像データ取得
    $stmt = $pdo2 -> prepare("SELECT p.id, pi.image_path, p.name, p.tax_included_price, p.category_id, p.category_name
        FROM product_images AS pi
        JOIN products AS p ON pi.product_id = p.id
        WHERE pi.is_main = 1
        AND  hidden_at IS NULL
        ORDER BY p.id
    ");
    $stmt -> execute();
    $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    // カテゴリーごとに商品分割
    $categorizedProducts = [];
    foreach($products as $product) {
        $category = $product['category_name'];
        $categorizedProducts[$category][] = $product;
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['productId'] = $_POST['productId'];

        header('Location: ../View/product.php');
        exit;
    }
?>