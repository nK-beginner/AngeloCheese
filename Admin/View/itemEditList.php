<?php require_once __DIR__.'/../PHP/itemEditList.php' ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include 'headTags.php' ?>
    <title>商品編集</title>
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/itemDelete.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/itemEdit.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/itemAdd.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="grid-container">
        <?php include 'sidebar.php'; ?>

        <main>
            <h1>商品編集</h1>

            <h3 class="choose-data">編集したい商品を選択してください。</h3>
            <div class="table-records">
                <table>
                    <tr>
                        <th>商品画像</th>
                        <th>商品id</th>
                        <th>商品名</th>
                        <th>商品説明</th>
                        <th>カテゴリー名</th>
                        <th>キーワード</th>
                        <th>サイズ1</th>
                        <th>サイズ2</th>
                        <th>税率</th>
                        <th>値段</th>
                        <th>税込価格</th>
                        <th>原価</th>
                        <th>消費期限1</th>
                        <th>消費期限2</th>
                        <th>消費期限1<br>(解凍後)</th>
                        <th>消費期限2<br>(解凍後)</th>
                        <th>商品表示状態</th>
                    </tr>
                    <?php
                        for($i = 0; $i < count($products); $i++):
                            $isHidden = !is_null($products[$i]['hidden_at']);
                    ?>
                    <tr class="row" data-id="<?php echo htmlspecialchars($products[$i]['id'], ENT_QUOTES, 'UTF-8'); ?>" style="color: <?php echo $isHidden ? 'red' : 'inherit'; ?>; cursor: pointer;">
                        <td><img src="<?php echo htmlspecialchars('/../AngeloCheese/Admin/uploads/' . $products[$i]['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像" class="product-image"></td>
                        <td><?php echo htmlspecialchars($products[$i]['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($products[$i]['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($products[$i]['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($products[$i]['category_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($products[$i]['keyword'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo number_format(htmlspecialchars($products[$i]['size1'], ENT_QUOTES, 'UTF-8')); ?>cm</td>
                        <td><?php echo number_format(htmlspecialchars($products[$i]['size2'], ENT_QUOTES, 'UTF-8')); ?>cm</td>
                        <td><?php echo htmlspecialchars($products[$i]['tax_rate'] * 100, ENT_QUOTES, 'UTF-8'); ?>%</td>
                        <td>¥<?php echo number_format(htmlspecialchars($products[$i]['price'], ENT_QUOTES, 'UTF-8')); ?></td>
                        <td>¥<?php echo number_format(htmlspecialchars($products[$i]['tax_included_price'], ENT_QUOTES, 'UTF-8')); ?></td>
                        <td>¥<?php echo number_format(htmlspecialchars($products[$i]['cost'], ENT_QUOTES, 'UTF-8')); ?></td>
                        <td><?php echo htmlspecialchars($products[$i]['expirationDate_min1'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                        <td><?php echo htmlspecialchars($products[$i]['expirationDate_max1'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                        <td><?php echo htmlspecialchars($products[$i]['expirationDate_min2'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                        <td><?php echo htmlspecialchars($products[$i]['expirationDate_max2'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                        <td><?php echo $isHidden ? '非表示中' : ''; ?></td>
                    </tr>
                    <?php endfor; ?>
                </table>
                <form autocomplete="off" action="itemEditList.php" method="POST" class="hidden-form">
                    <input autocomplete="off" type="hidden" name="productId" class="product-id">
                </form>
            </div>
        </main>
    </div>
    <script type="module" src="/../AngeloCheese/Admin/JS/sidebar.js"></script> <!-- サイドバー -->
    <script type="module" src="/../AngeloCheese/Admin/JS/itemEdit.js"></script> <!-- 編集用 -->
</body>
</html>