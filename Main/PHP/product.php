<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/../../Admin/backend/connection.php';
    require_once __DIR__.'/../PHP/function/getImages.php';

    // メイン画像用 + 商品名などもここから取ること
    $product = fncGetImages($pdo2, 2, 0);

    // サブ画像
    $subImg = fncGetImages($pdo2, 3, 0);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__.'/../backend/check.php';
    
        $productId = (int)$_POST['productId'];
        $quantity  = (int)$_POST['quantity'];

        if ($quantity > 0) {
            // クッキーからカートを取得（存在しない場合は空配列）
            $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
        
            // すでにカートにあれば追加、なければ新規追加
            if (isset($cart[$productId])) {
                $cart[$productId] += $quantity;

            } else {
                $cart[$productId] = $quantity;
            }
        
            // クッキーに保存（JSONエンコードして格納）
            setcookie('cart', json_encode($cart), time() + 86400 * 30, '/'); // 30日間有効
        }
        
    
        header('Location: ../View/cart.php');
        exit;
    }
?>