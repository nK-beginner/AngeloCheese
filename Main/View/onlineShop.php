<?php require_once __DIR__.'/../PHP/OnlineShop.php'; ?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧 | Angelo Cheese</title>

    <!-- headタグ -->
    <?php include __DIR__.'../common/headTags.php'; ?>

    <!-- Online Shop用CSS -->
    <link rel="stylesheet" href="../css/onlineShop.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'../common/header.php'; ?>

    <div class="top-container">
        <h1 class="title">ONLINE SHOP.</h1>
    </div>

    <main>
        <div class="about-us">
            <h1>大切な人に届けたい。</h1>
            <h2>誰にでも愛されるチーズケーキ。</h2>

            <p>
                ふんわりとした口どけと、ほどよい甘さが広がる一口は、誰もが笑顔になれる特別な時間を演出します。
                素材ひとつひとつにこだわり、じっくり丁寧に焼き上げたこのチーズケーキは、小さなお子様からご年配の方まで、どなたにも愛される優しい味わい。
                大切な人と囲むひととき、久しぶりに会う友人との語らいの時間、大切な記念日に。
                「美味しいね」と言い合いながら、心がつながる瞬間をお届けします。
                贈る人の想いと、受け取る人の笑顔をつなぐ一品を、心を込めてお届けします。
            </p>
        </div>

        <div class="categories">
            <img src="/../AngeloCheese/images/category1.jpg" alt="">
            <img src="/../AngeloCheese/images/category2.jpg" alt="">
            <img src="/../AngeloCheese/images/category3.jpg" alt="">
            <img src="/../AngeloCheese/images/category4.jpg" alt="">
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
                                                <img src="<?php echo htmlspecialchars('/AngeloCheese/admin/' . $item['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">                                        
                                            </button>

                                            <div class="product-info">
                                                <h3><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                                <p>¥<?php echo number_format($item['tax_included_price']); ?><span2>(税込)</span2></p>                                                
                                            </div>

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
    </main>

    <?php include __DIR__.'../common/footer.php'; ?>
</body>
</html>