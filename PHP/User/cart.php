







<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/../Admin/Backend/connection.php';

    $stmt = $pdo2 -> prepare("SELECT * FROM products AS p JOIN product_images AS pi ON pi.product_id = p.id WHERE p.id = :id AND pi.is_main = 1");
    $stmt -> bindValue(":id", $_SESSION['productId'], PDO::PARAM_INT);
    $stmt -> execute();
    $product = $stmt -> fetch(PDO::FETCH_ASSOC);
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
            <form action="cart.php" method="POST">
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <h2 class="page-title"><span>C</span>art<span>.</span></h2>

                <div class="cart">
                    <div class="product">
                        <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $product['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">

                        <div class="product-info">
                            <div class="name-price">
                                <h1 class="product-name"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                                <h3 class="price"><span>¥</span><?php echo htmlspecialchars(number_format($product['price']), ENT_QUOTES, 'UTF-8'); ?></h3>                                
                            </div>
                            
                            <div class="quantity-delete">
                                <div class="quantity-container">
                                    <button><i class="minus fa-solid fa-minus"></i></button>
                                    <input type="text" class="quantity" value="<?php echo htmlspecialchars($_SESSION['quantity'], ENT_QUOTES, 'UTF-8'); ?>" maxlength="2" disabled>
                                    <input type="hidden" class="hidden-quantity" name="quantity">
                                    <button><i class="plus fa-solid fa-plus"></i></button>
                                </div>

                                <i class="trash-bin fa-solid fa-trash-can"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>

    <script>
        const plus     = document.querySelector('.plus');
        const minus    = document.querySelector('.minus');
        const quantity = document.querySelector('.quantity');
        const hiddenQuantity    = document.querySelector('.hidden-quantity');
        const quantityContainer = document.querySelector('.quantity-container');

        quantityContainer.addEventListener('click', (e) => {
            e.preventDefault();
        });

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

