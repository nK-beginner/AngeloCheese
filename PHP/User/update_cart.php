




<?php
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/../Admin/Backend/connection.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['quantity'])) {
        $productId = $_POST['id'];
        $newQuantity = (int) $_POST['quantity'];

        // クッキーからカートを取得（存在しない場合は空配列）
        $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

        // 数量を更新（0以下なら削除）
        if ($newQuantity > 0) {
            $cart[$productId] = $newQuantity;
        } else {
            unset($cart[$productId]);
        }

        // クッキーに更新データを保存
        setcookie('cart', json_encode($cart), time() + 86400 * 30, '/'); // 30日間有効

        // 合計金額を計算
        $totalPrice = 0;
        foreach ($cart as $id => $qty) {
            // 商品の価格を取得（DBから取得）
            $productPrice = getProductPrice($id);
            $totalPrice += $productPrice * $qty;
        }

        // JSONレスポンスを返す
        echo json_encode(['success' => true, 'total_price' => $totalPrice]);
        exit;
    }

    // エラーレスポンス
    echo json_encode(['success' => false]);
    exit;

    // 商品価格を取得
    function getProductPrice($id) {
        global $pdo2;

        $stmt = $pdo2->prepare("SELECT tax_included_price FROM products WHERE id = :id LIMIT 1");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        return $product ? $product['tax_included_price'] : 0;
    }
?>