




<?php
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/../../Admin/backend/connection.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['quantity'])) {
        $productId = (int) $_POST['id'];
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

        // 合計金額を計算（最適化: 一括取得）
        $totalPrice = calculateTotalPrice($cart);

        // JSONレスポンスを返す
        echo json_encode(['success' => true, 'total_price' => $totalPrice]);
        exit;
    }

    // エラーレスポンス
    echo json_encode(['success' => false]);
    exit;

    function calculateTotalPrice($cart) {
        global $pdo2;

        if (empty($cart)) {
            return 0;
        }

        $ids = array_keys($cart);
        $inQuery = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $pdo2 -> prepare("SELECT id, tax_included_price FROM products WHERE id IN ($inQuery)");
        $stmt -> execute($ids);

        $prices = $stmt -> fetchAll(PDO::FETCH_KEY_PAIR);
        $totalPrice = 0;

        foreach ($cart as $id => $qty) {
            $totalPrice += ($prices[$id] ?? 0) * $qty;
        }

        return $totalPrice;
    }
?>
