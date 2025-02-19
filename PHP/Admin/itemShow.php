<?php
require_once __DIR__ . '/backend/connection.php';

// カテゴリーIDに対応するカテゴリー名
$categoryNames = [
    1 => '人気商品',
    2 => 'チーズケーキサンド',
    3 => 'チーズケーキ',
    99 => 'その他'
];

// データベースから商品情報を取得（メイン画像のみ）
$stmt = $pdo2->prepare("
    SELECT pi.image_path, p.name, p.category_id
    FROM product_images pi
    JOIN products p ON pi.product_id = p.id
    WHERE pi.is_main = 1
    ORDER BY p.category_id, p.id
");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// カテゴリーごとに商品を分類
$categorizedProducts = [];
foreach ($products as $product) {
    $category = $categoryNames[$product['category_id']] ?? '不明';
    $categorizedProducts[$category][] = $product;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <style>
        .category-section {
            margin-bottom: 30px;
        }
        .category-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .product {
            width: 200px;
            text-align: center;
        }
        .product img {
            width: 100%;
            height: auto;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>商品一覧</h1>

    <?php if (!empty($categorizedProducts)): ?>
        <?php foreach ($categorizedProducts as $category => $items): ?>
            <div class="category-section">
                <div class="category-title"><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="product-container">
                    <?php foreach ($items as $product): ?>
                        <div class="product">
                            <img src="<?= htmlspecialchars($product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">
                            <p><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>メイン画像が登録されている商品はありません。</p>
    <?php endif; ?>
</body>
</html>
