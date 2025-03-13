




<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/../Admin/Backend/connection.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['quantity'])) {
        $productId = $_POST['id'];
        $newQuantity = (int) $_POST['quantity'];

        // セッションにカートがなければ作成
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // 数量を更新
        if ($newQuantity > 0) {
            $_SESSION['cart'][$productId] = $newQuantity;
        } else {
            unset($_SESSION['cart'][$productId]); // 0以下なら削除
        }

        // 合計金額を計算
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $id => $qty) {
            // ここで商品の価格を取得（例: DBから取得）
            $productPrice = getProductPrice($id); 
            $totalPrice += $productPrice * $qty;
        }

        // JSONでレスポンスを返す
        echo json_encode(['success' => true, 'total_price' => $totalPrice]);
        exit;
    }

    // エラーレスポンス
    echo json_encode(['success' => false]);
    exit;

    // 商品価格を取得
    function getProductPrice($id) {
        global $pdo2;

        $stmt = $pdo2 -> prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt -> bindValue(":id", $id, PDO::PARAM_INT);
        $stmt -> execute();

        $product = $stmt -> fetch(PDO::FETCH_ASSOC);

        return $product ? $product['tax_included_price'] : 0;
    }
?>
