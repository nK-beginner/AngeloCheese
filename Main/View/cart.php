<?php require_once __DIR__.'/../PHP/cart.php'; ?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#242424">
    <title>カート</title>

    <!-- headタグ -->
    <?php include __DIR__.'../common/headTags.php'; ?>

    <!-- カート用CSS -->
    <link rel="stylesheet" href="../css/cart.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'../common/header.php'; ?>

    <main>
        <div class="main-container">
            <?php if(empty($cart)): ?>
                <h2 class="page-title no-items">カート内に商品がありません。</h2>
                <h2 class="recommended-title">こちらの商品がおすすめです。</h2>

            <?php else: ?>
                <form action="../PHP/cart.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <h2 class="page-title"><span>C</span>art<span>.</span></h2>

                    <div class="cart">
                        <?php foreach ($products as $product): ?>
                            <div class="product">
                                <img src="<?php echo htmlspecialchars('/../AngeloCheese/Admin/uploads/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">

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

            <?php endif; ?>

            <div class="product-container">
                <?php foreach($recommendedProducts as $product): ?>
                    <div class="forms">
                        <form action="../PHP/onlineShop.php" method="POST" class="recommended-product">
                            <button>
                                <input type="hidden" name="productId" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                
                                <img src="<?php echo htmlspecialchars('/../AngeloCheese/Admin/uploads/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">                             
                            </button>
                            <h3><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p>¥<?php echo number_format($product['tax_included_price']); ?><span2>(税込)</span2></p>    
                        </form>

                        <form action="../PHP/product.php" method="POST" class="to-cart">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="productId" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" value="1" name="quantity">

                            <button class="cart">
                                <p>カートに追加する</p>
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>

                <a href="OnlineShop.php" class="to-shop">もっと見る</a>
            </div>
            
        </div>
    </main>

    <?php include __DIR__.'../common/footer.php'; ?>

    <script src="../JS/cart.js"></script>

</body>
</html>