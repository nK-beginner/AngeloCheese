<!DOCTYPE html>
<html lang="ja">
<head>
    <?php require_once __DIR__.'/../../Core/head_tags.php'; ?>
    <title>商品削除</title>
    <link rel="stylesheet" href="/../Test/Public/CSS/itemDelete.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/../Test/Public/CSS/table.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="grid-container">
        <?php include 'sidebar.php'; ?>

        <main>
            <h1>商品削除</h1>

            <form action="/Test/Public/product_delete.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">  
                
                <div class="table-records">
                    <table>
                        <thead>
                            <tr>
                                <!-- 17個 -->
                                <th></th>
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
                                <th>消費期限(解凍後)1</th>
                                <th>消費期限(解凍後)2</th>
                                <th>作成日</th>
                                <th>更新日</th>   
                                <th>商品表示状態</th>
                            </tr>                            
                        </thead>

                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <?php $isHidden = !is_null($product['hidden_at']); ?>
                                <tr style="color: <?php echo $isHidden ? 'red' : 'inherit'; ?>;" class="clickable-row">
                                    <td><input type="checkbox" name="delete[]" class="check-boxes" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $isHidden ? 'disabled' : ''; ?>></td>
                                    <td><?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($product['keyword'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo number_format(htmlspecialchars($product['size1'], ENT_QUOTES, 'UTF-8')); ?>cm</td>
                                    <td><?php echo number_format(htmlspecialchars($product['size2'], ENT_QUOTES, 'UTF-8')); ?>cm</td>
                                    <td><?php echo htmlspecialchars($product['tax_rate'] * 100, ENT_QUOTES, 'UTF-8'); ?>%</td>
                                    <td>¥<?php echo number_format(htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8')); ?></td>
                                    <td>¥<?php echo number_format(htmlspecialchars($product['tax_included_price'], ENT_QUOTES, 'UTF-8')); ?></td>
                                    <td>¥<?php echo number_format(htmlspecialchars($product['cost'], ENT_QUOTES, 'UTF-8')); ?></td>
                                    <td><?php echo htmlspecialchars($product['expirationDate_min1'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                                    <td><?php echo htmlspecialchars($product['expirationDate_max1'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                                    <td><?php echo htmlspecialchars($product['expirationDate_min2'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                                    <td><?php echo htmlspecialchars($product['expirationDate_max2'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                                    <td><?php echo htmlspecialchars($product['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($product['updated_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo $isHidden ? '非表示中' : ''; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <input type="submit" class="delete-btn" value="非表示"></input>                
            </form>
        </main>
    </div>
    <script type="module" src="/../Test/Public/JS/sidebar.js"></script>
    <script type="module" src="/../Test/Public/JS/itemDelete.js"></script>
</body>
</html>
