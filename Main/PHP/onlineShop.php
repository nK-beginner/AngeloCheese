



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

        header('Location: product.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Shop</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- Online Shop用CSS -->
    <link rel="stylesheet" href="../css/onlineShop.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <div class="top-container">
        <img src="../images/trimmedTop2.jpg" alt="top image">
        <h1>大切な人に届けたい<span>。</span></h1>
        <h2>誰にでも愛されるチーズケーキ<span>。</span></h2>
    </div>

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
                                <div class="forms">
                                    <form action="onlineShop.php" method="POST" class="product">
                                        <button type="submit">
                                            <input type="hidden" name="productId" value="<?php echo htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $item['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">                                        
                                        </button>

                                        <h3><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <p>¥<?php echo number_format($item['tax_included_price']); ?><span2>(税込)</span2></p>
                                    </form>

                                    <form action="product.php" method="POST" class="to-cart">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="productId" value="<?php echo htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" value="1" name="quantity">

                                        <button class="cart">
                                            <p>カートに追加する</p>
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </button>
                                    </form>
                                </div>

                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include __DIR__.'/../common/footer.php'; ?>
</body>
</html>