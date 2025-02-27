




<?php
    session_start();
    require_once __DIR__.'/../Admin/Backend/connection.php';
    require_once __DIR__.'/../Admin/Backend/csrf_token.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $_SESSION['edit_item_id'] = intval($_POST['id']);
        header('Location: itemEdit.php');
        exit();
    }

    // 一覧表示
    try {
        $stmt = $pdo2 -> prepare("SELECT * FROM products");
        $stmt -> execute();
        $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        $errors[] = 'データベース接続エラー:' . $e -> getMessage();
    }

    // allItems.phpから商品を選択されてきた時
    $editItem = null;
    if(isset($_SESSION['edit_item_id'])) {
        $editItemId = $_SESSION['edit_item_id'];
        unset($_SESSION['edit_item_id']);
        try {
            $stmt = $pdo2 -> prepare("SELECT * FROM products WHERE id = :id");
            $stmt -> bindValue(':id', $editItemId, PDO::PARAM_INT);
            $stmt -> execute();
            $editItem = $stmt -> fetch(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            $errors[] = 'データベース接続エラー:' . $e -> getMessage();
        }

    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- ヘッダータグ -->
    <?php include 'headTags.php' ?>

    <title>商品編集</title>

    <!-- 商品削除CSS（テーブルスタイル流用） -->
    <link rel="stylesheet" href="CSS/itemDelete.css?v=<?php echo time(); ?>">

    <!-- 商品編集用CSS -->
    <link rel="stylesheet" href="CSS/itemEdit.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="grid-container">
        <!-- サイドバー -->
        <?php include 'sidebar.php'; ?>

        <!-- 商品詳細エリア -->
        <main>
            <h1>商品編集</h1>

            <?php if($editItem): ?>
                <!-- 商品編集画面 -->

            <?php else: ?>
                <!-- 商品一覧表示 -->
                <h3 class="choose-data">編集したいデータを選択してください。</h3>
                <div class="table-records">
                    <table>
                        <tr>
                            <!-- 17個 -->
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
                        <?php 
                            $productCnt = count($products);
                            for($i = 0; $i < $productCnt; $i++):
                                $isHidden = !is_null($products[$i]['hidden_at']); // hidden_at が null でなければ true
                        ?>
                        <tr class="row" data-id="<?php echo htmlspecialchars($products[$i]['id'], ENT_QUOTES, 'UTF-8'); ?>" style="color: <?php echo $isHidden ? 'red' : 'inherit'; ?>; cursor: pointer;">
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
                    </table>
                    <!-- 隠しフォーム -->
                    <form action="itemEdit.php" method="POST" class="hidden-form">
                        <input type="hidden" name="id" class="product-id">
                    </form>
                </div>

            <?php endif; ?>

        </main>
    </div>
    <script src="JS/sidebar.js"></script> <!-- サイドバー -->
    <script src="JS/itemEdit.js"></script> <!-- サイドバー -->
</body>
</html>
