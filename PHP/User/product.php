




<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    session_regenerate_id(true);

    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/../Admin/Backend/connection.php';

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
        if(!fncVerifyToken($_POST['hidden'])) {
            header('Location: product.php');
            exit;
        }
    
        $productId = (int)$_POST['productId'];
        $quantity  = (int)$_POST['quantity'];

        if ($quantity > 0) {
            // クッキーからカートを取得
            $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
        
            // すでにカートにあれば追加、なければ新規追加
            if (isset($cart[$productId])) {
                $cart[$productId] += $quantity;

            } else {
                $cart[$productId] = $quantity;
            }
        
            // クッキーに保存（JSONエンコードして格納）+ データ暗号化
            $encryptedCart = openssl_encrypt(json_encode($cart), 'aes-256-cbc', 'your-encryption-key', 0, 'your-encryption-iv');
            setcookie('cart', $encryptedCart, time() + 86400 * 30, '/'); // 30日間有効
        }
    
        header('Location: cart.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#242424">
    <title>商品詳細</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- 商品詳細用CSS -->
    <link rel="stylesheet" href="../css/product.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <!-- <a href="onlineShop.php">一覧へ</a> -->
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

            <form action="product.php" method="POST">
                <!-- CSRFトークン -->
                <input type="hidden" name="hidden" value="<?php echo htmlspecialchars($_SESSION['hidden'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                
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

    <?php include __DIR__.'/../common/footer.php'; ?>

    <script>
        const plus     = document.querySelector('.plus');
        const minus    = document.querySelector('.minus');
        const quantity = document.querySelector('.quantity');
        const hiddenQuantity = document.querySelector('.hidden-quantity');

        hiddenQuantity.value = 1;

        plus.addEventListener('click', (e) => {
            let currentValue = parseInt(quantity.value) || 0;
            if (currentValue < 99) {
                quantity.value = currentValue + 1;
                hiddenQuantity.value = quantity.value;
            }
        });

        minus.addEventListener('click', (e) => {
            let currentValue = parseInt(quantity.value) || 0;
            if (currentValue > 0) {
                quantity.value = currentValue - 1;
                hiddenQuantity.value = quantity.value;
            }
        });
    </script>
</body>
</html>