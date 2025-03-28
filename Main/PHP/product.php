<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/../../Admin/backend/connection.php';

    // メイン画像用 + 商品名などもここから取ること
    $stmt = $pdo2 -> prepare("SELECT * FROM products AS p JOIN product_images AS pi ON pi.product_id = p.id WHERE p.id = :id AND pi.is_main = 1");
    $stmt -> bindValue(":id", $_SESSION['productId'], PDO::PARAM_INT);
    $stmt -> execute();
    $product = $stmt -> fetch(PDO::FETCH_ASSOC);

    // サブ画像
    $stmt = $pdo2 -> prepare("SELECT image_path FROM product_images WHERE product_id = :id AND is_main != 1");
    $stmt -> bindValue(":id", $_SESSION['productId'], PDO::PARAM_INT);
    $stmt -> execute();
    $subImg = $stmt -> fetch(PDO::FETCH_ASSOC);

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