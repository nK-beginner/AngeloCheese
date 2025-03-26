







<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/../Admin/Backend/connection.php';

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
    $stmt = $pdo2 -> prepare("SELECT p.id, pi.image_path, p.name, p.tax_included_price
        FROM product_images AS pi
        JOIN products AS p ON pi.product_id = p.id
        WHERE pi.is_main = 1
        AND p.category_id = 2
        AND  p.hidden_at IS NULL
        ORDER BY p.id
    ");
    $stmt -> execute();
    $recommendedProducts = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRFトークン不一致エラー');
        }

        // CSRFトークン再生成：既存のトークンを無効化し再生成 ⇒ 使い回しを防ぐ
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // ログインしてなければログイン画面へ強制遷移
        if(isset($_POST['toLogin'])) {
            $_SESSION['fromCart'] = bin2hex(random_bytes(32));

            header('Location: login.php');
            exit;

        } else {
            die('lets purchase');
        }
    }
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#242424">
    <title>カート</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- カート用CSS -->
    <link rel="stylesheet" href="../css/cart.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <?php if(empty($cart)): ?>
                <h2 class="page-title no-items">カート内に商品がありません。</h2>
                <h2 class="recommended-title">こちらの商品がおすすめです。</h2>

                <div class="product-container">
                    <?php foreach($recommendedProducts as $product): ?>
                        <form action="onlineShop.php" method="POST" class="recommended-product">
                            <button>
                                <input type="hidden" name="productId" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                
                                <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">
                                <h3><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <p>¥<?php echo number_format($product['tax_included_price']); ?><span2>(税込)</span2></p>                                 
                            </button>
                        </form>
                    <?php endforeach; ?>

                    <a href="OnlineShop.php" class="to-shop">もっと見る</a>
                </div>

            <?php else: ?>
                <form action="cart.php" method="POST">
                    <input type="hidden" name="hidden" value="<?php echo htmlspecialchars($_SESSION['hidden'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <h2 class="page-title"><span>C</span>art<span>.</span></h2>

                    <div class="cart">
                        <?php foreach ($products as $product): ?>
                            <div class="product">
                                <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">

                                <div class="product-info">
                                    <div class="name-price">
                                        <h1 class="product-name"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h1>

                                        <h3 class="price" data-price="<?php echo htmlspecialchars($product['tax_included_price'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <span>¥</span><?php echo htmlspecialchars(number_format($product['tax_included_price']), ENT_QUOTES, 'UTF-8'); ?>
                                        </h3>
                                    </div>

                                    <div class="quantity-delete">
                                        <div class="quantity-container">
                                            <button type="button"><i class="minus fa-solid fa-minus"></i></button>
                                            <input  type="text" class="quantity" data-id="<?php echo $product['id']; ?>" value="<?php echo htmlspecialchars($cart[$product['id']], ENT_QUOTES, 'UTF-8'); ?>" maxlength="2" disabled>
                                            <input  type="hidden" class="hidden-quantity" name="quantity">
                                            <button type="button"><i class="plus fa-solid fa-plus"></i></button>
                                        </div>

                                        <i class="trash-bin fa-solid fa-trash-can"></i>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="total-price">
                        <h1>合計：<span2></span2>(税込)</h1>

                        <p>※送料、保冷剤代が別途かかります。</p>
                    </div>

                    <div class="to-btns">
                        <?php if(isset($_SESSION['user_id']) || isset($_COOKIE['remember_token'])): ?>
                            <button class="to-purchase">お会計へ</button>
                        <?php else: ?>
                            <input type="hidden" name="toLogin">
                            <button class="to-login">ログインして買う</button>
                        <?php endif; ?>
                    </div>
                </form>

                <h2 class="recommended-here">こちらの商品もおすすめです。</h2>

                <div class="product-container">
                    <?php foreach($recommendedProducts as $product): ?>
                        <form action="onlineShop.php" method="POST" class="recommended-product">
                            <button>
                                <input type="hidden" name="productId" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                
                                <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">
                                <h3><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <p>¥<?php echo number_format($product['tax_included_price']); ?><span2>(税込)</span2></p>                                 
                            </button>
                        </form>
                    <?php endforeach; ?>

                    <a href="OnlineShop.php" class="to-shop">もっと見る</a>
                </div>
            <?php endif; ?>
            
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>

    <script>
        // 合計金額を更新
        const fncUpdateTotalPrice = () => {
            let total = 0;

            document.querySelectorAll('.product').forEach(product => {
                const quantityInput = product.querySelector('.quantity');
                const priceElement  = product.querySelector('.price');
                const quantity      = parseInt(quantityInput.value) || 0;
                const price         = parseInt(priceElement.dataset.price) || 0;

                total += price * quantity;
            });

            const totalPrice = document.querySelector('.total-price span2');
            totalPrice.innerHTML = `<span>¥</span>${total.toLocaleString()}`;
        };

        document.querySelectorAll('.quantity-container').forEach(container => {
            const plus           = container.querySelector('.plus');
            const minus          = container.querySelector('.minus');
            const quantity       = container.querySelector('.quantity');
            const hiddenQuantity = container.querySelector('.hidden-quantity');
            const product        = container.closest('.product'); // 商品要素
            const trashBin       = product.querySelector('.trash-bin');
            const productId      = quantity.dataset.id;

            // AJAX処理：商品個数に応じて金額を自動計算
            const updateCart = (newQuantity) => {
                fetch('update_cart.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `id=${productId}&quantity=${newQuantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (newQuantity === 0) {
                            // 数量が0なら商品をカートから削除
                            product.remove();

                            if (document.querySelectorAll('.product').length === 0) {
                                location.reload();
                            }

                        } else {
                            // 数量を更新
                            quantity.value = newQuantity;
                            hiddenQuantity.value = newQuantity;
                        }
                        // 合計金額更新
                        if (data.total_price !== undefined) {
                            document.querySelector('.total-price span2').innerHTML = `<span>¥</span>${data.total_price.toLocaleString()}`;
                        }
                        
                    } else {
                        alert('カートの更新に失敗しました');
                    }
                })
                .catch(error => console.error('Error:', error));
            };

            plus.addEventListener('click', () => {
                let currentValue = parseInt(quantity.value) || 0;
                if (currentValue < 99) {
                    updateCart(currentValue + 1);
                }
            });

            minus.addEventListener('click', () => {
                let currentValue = parseInt(quantity.value) || 0;
                if (currentValue > 0) {
                    updateCart(currentValue - 1);
                }
            });

            trashBin.addEventListener('click', () => {
                updateCart(0);
            });

        });

        // 初期時点から表示
        fncUpdateTotalPrice();
    </script>

</body>
</html>

