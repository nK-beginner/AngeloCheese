<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/../../Admin/backend/connection.php';
    require_once __DIR__.'/../PHP/function/getImages.php';

    // クッキーからカートを取得
    $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

    $products = [];

    if (!empty($cart)) {
        // カート内の商品IDを取得
        $productIds = array_keys($cart);

        // SQLのプレースホルダー
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        // 商品情報を取得
        $stmt = $pdo2 -> prepare("SELECT * FROM products AS p JOIN product_images AS pi ON pi.product_id = p.id WHERE p.id IN ($placeholders) AND pi.is_main = 1");
        $stmt -> execute($productIds);
        $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 画像データ取得
    $recommendedProducts = fncGetImages($pdo2, 4, 1);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__.'/../backend/check.php';

        // ログインしてなければログイン画面へ強制遷移
        if(isset($_POST['toLogin'])) {
            $_SESSION['fromCart'] = bin2hex(random_bytes(32));

            header('Location: login.php');
            exit;

        } else {
            // die('lets purchase');
            die($_POST['quantity']);
        }
    }
?>

