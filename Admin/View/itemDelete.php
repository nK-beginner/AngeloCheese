<?php require_once __DIR__.'/../PHP/itemDelete.php' ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- ヘッダータグ -->
    <?php include 'headTags.php' ?>

    <title>商品削除</title>

    <!-- 商品削除用CSS -->
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/itemDelete.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="grid-container">
        <!-- サイドバー -->
        <?php include 'sidebar.php'; ?>

        <!-- 商品詳細エリア -->
        <main>
            <h1>商品削除</h1>
            <form action="itemDelete.php" method="POST">
                <!-- CSRFトークン -->
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
                            <?php
                                for($i = 0; $i < count($products); $i++):
                                    $isHidden = !is_null($products[$i]['hidden_at']); // hidden_at が null でなければ true
                            ?>
                            <tr style="color: <?php echo $isHidden ? 'red' : 'inherit'; ?>;">
                                <td><input type="checkbox" name="delete[]" class="check-boxes" value="<?php echo htmlspecialchars($products[$i]['id'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $isHidden ? 'disabled' : ''; ?>></td>
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
                                <td><?php echo htmlspecialchars($products[$i]['expirationdate_min1'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                                <td><?php echo htmlspecialchars($products[$i]['expirationdate_max1'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                                <td><?php echo htmlspecialchars($products[$i]['expirationdate_min2'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                                <td><?php echo htmlspecialchars($products[$i]['expirationdate_max2'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                                <td><?php echo htmlspecialchars($products[$i]['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($products[$i]['updated_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo $isHidden ? '非表示中' : ''; ?></td>
                            </tr>
                            <?php endfor; ?>                            
                        </tbody>
                    </table>
                </div>

                <input type="submit" class="delete-btn" value="削除"></input>                
            </form>

        </main>
    </div>
    <script src="/../AngeloCheese/Admin/JS/sidebar.js"></script> <!-- サイドバー -->
    <script src="/../AngeloCheese/Admin/JS/itemDelete.js"></script> <!-- 削除画面用 -->
</body>
</html>
