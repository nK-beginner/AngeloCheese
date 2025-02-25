




<?php
    session_start();
    require_once __DIR__.'/../Admin/Backend/connection.php';
    require_once __DIR__.'/../Admin/Backend/csrf_token.php';

    // 画像データ取得
    $stmt = $pdo2 -> prepare("SELECT pi.image_path, p.name, p.tax_included_price, p.category_id, p.category_name
        FROM product_images AS pi
        JOIN products AS p ON pi.product_id = p.id
        WHERE pi.is_main = 1
        AND hidden_at IS NULL
        ORDER BY p.id
    ");
    $stmt -> execute();
    $products = $stmt -> fetchALl(PDO::FETCH_ASSOC);

    // カテゴリー商品分割
    $categorizedProducts = [];
    foreach($products as $product) {
        $category = $product['category_name'];
        $categorizedProducts[$category][] = $product;
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- ヘッダータグ -->
    <?php include 'headTags.php' ?>

    <title>商品一覧</title>

    <!-- 商品編集用CSS -->
    <link rel="stylesheet" href="CSS/allItems.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="grid-container">
        <!-- サイドバー -->
        <?php include 'sidebar.php'; ?>

        <!-- 商品詳細エリア -->
        <main>
            <h1>商品一覧</h1>

            <div class="products">
                <div class="container">
                    <?php if(!empty($categorizedProducts)): ?>
                        <?php foreach($categorizedProducts as $category => $items): ?>
                            <div class="category-section">
                                <div class="category-title">
                                    <h2><?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></h2>
                                </div>
                                <div class="product-container">
                                    <?php foreach($items as $item): ?>
                                        <div class="product">
                                            <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $item['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">
                                            <h3><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                            <p>¥<?php echo number_format($item['tax_included_price']); ?><span2>(税込)</span2></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    <script src="JS/sidebar.js"></script> <!-- サイドバー -->
</body>
</html>
