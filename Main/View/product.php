<?php
    require_once __DIR__.'/../PHP/product.php';
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#242424">
    <title>商品詳細</title>

    <!-- headタグ -->
    <?php include __DIR__.'../common/headTags.php'; ?>

    <!-- 商品詳細用CSS -->
    <link rel="stylesheet" href="../css/product.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'../common/header.php'; ?>

    <main>
        <div class="grid-container">
            <div class="item-img-container">
                <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">

                <div class="item-img-subcontainer">
                    <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">
                    <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">
                    <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">
                    <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">
                    <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">
                </div>
            </div>

            <form action="../PHP/product.php" method="POST">
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                
                <input type="hidden" name="productId" value="<?php echo htmlspecialchars($_SESSION['productId'], ENT_QUOTES, 'UTF-8');?>">

                <div class="info-container">
                    <h1 class="product-name"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                    <h3 class="price"><span>¥</span><?php echo htmlspecialchars(number_format($product['tax_included_price']), ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars(($product['description']), ENT_QUOTES, 'UTF-8'); ?></p>  
                </div>

                <div class="btn-container">
                    <div class="quantity-container">
                        <button type="button"><i class="minus fa-solid fa-minus"></i></button>

                        <input type="text" class="quantity" value="1" maxlength="2" disabled>
                        <input type="hidden" class="hidden-quantity" name="quantity">
                        
                        <button type="button"><i class="plus fa-solid fa-plus"></i></button>
                    </div>
                    
                    <button type="submit" class="into-cart">カートに入れる<i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__.'../common/footer.php'; ?>

    <script src="../JS/product.js"></script>
</body>
</html>