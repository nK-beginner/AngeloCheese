<!DOCTYPE html>
<html lang="ja">
<head>
    <?php require_once __DIR__.'/../../Core/head_tags.php'; ?>
    <title>商品一覧</title>
    <link rel="stylesheet" href="/../Test/Public/CSS/allItems.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="grid-container">
        <!-- サイドバー -->
        <?php require_once 'sidebar.php'; ?>

        <!-- 商品詳細エリア -->
        <main>
            <h1>商品一覧</h1>
            <form action="all_products_view.php" method="POST">
                <button type="submit" class="csv-btn">
                    <i class="fa-solid fa-file-csv"></i> <br>
                    CSV出力
                </button>
            </form>

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
                                        <form action="itemEditList.php" method="POST" class="product">
                                            <input type="hidden" name="productId" value="<?php echo htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <img src="<?php echo htmlspecialchars('/../Test/Public/uploads/' . $item['main_img'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">
                                            
                                            <h3><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                            <p>
                                                ¥<?php echo number_format($item['tax_included_price']); ?><span2>(税込)</span2>
                                                <?php echo !is_null($item['hidden_at']) ? '<strong>非表示中</strong>' : ''; ?>
                                            </p>
                                            <dialog>
                                                <div class="dialog-container">
                                                    <h3>商品を編集しますか？</h3>
                                                    <div class="btns">
                                                        <button class="cancel">キャンセル</button>
                                                        <button class="confirm">はい</button>
                                                    </div>
                                                </div>
                                            </dialog>
                                        </form>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    <script type="module" src="/../Test/Public/JS/sidebar.js"></script>
    <script type="module" src="/../Test/Public/JS/allItems.js"></script>
</body>
</html>