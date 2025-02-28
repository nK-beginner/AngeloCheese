




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
        $stmt = $pdo2 -> prepare("SELECT * FROM products AS p JOIN product_images AS pi ON p.id = pi.product_id");
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
            $stmt = $pdo2 -> prepare("SELECT * FROM products AS p JOIN product_images AS pi ON p.id = pi.product_id WHERE p.id = :id");
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
                <a href="itemEdit.php">一覧へ</a>
                <!-- 商品編集画面 -->
                <div class="edit-grid-container">
                    <!-- 商品詳細 -->
                    <div class="product-info">
                        <!-- 商品名など -->
                        <div class="form-block">
                            <h3>商品名</h3>
                            <input class="user-input" type="text" value="<?php echo htmlspecialchars($editItem['name'], ENT_QUOTES, 'UTF-8'); ?>">

                            <h3>商品説明</h3>
                            <textarea class="user-input" type="text"><?php echo htmlspecialchars($editItem['name'], ENT_QUOTES, 'UTF-8'); ?></textarea>

                            <h3>カテゴリー名</h3>
                            <select class="user-input">
                                <option value="0" selected>選択してください。</option>
                                <option value="1">人気商品</option>
                                <option value="2">チーズケーキサンド</option>
                                <option value="3">アンジェロチーズ</option>
                                <option value="99">その他</option>
                            </select>

                            <h3>キーワード</h3>
                            <input class="user-input" type="text" value="<?php echo htmlspecialchars($editItem['keyword'], ENT_QUOTES, 'UTF-8'); ?>">

                            <h3>サイズ(cm)</h3>
                            <div class="block">
                                <input class="user-input" type="text" value="<?php echo htmlspecialchars($editItem['size1'], ENT_QUOTES, 'UTF-8'); ?>">
                                <p>×</p>
                                <input class="user-input" type="text" value="<?php echo htmlspecialchars($editItem['size2'], ENT_QUOTES, 'UTF-8'); ?>">                                
                            </div>

                        </div>

                        <!-- 金額など -->
                        <div class="form-block">
                            
                        </div>

                        <!-- 消費期限 -->
                        <div class="form-block">
                            
                        </div>
                    </div>

                    <!-- 商品画像 -->
                    <div class="product-pics">
                        <img class="product-image" src="<?php echo htmlspecialchars($editItem['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
                    </div>
                    
                </div>

            <?php else: ?>
                <!-- 商品一覧表示 -->
                <h3 class="choose-data">編集したい商品を選択してください。</h3>
                <div class="table-records">
                    <table>
                        <tr>
                            <!-- 17個 -->
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
                            <th>消費期限(解凍後)1</th>
                            <th>消費期限(解凍後)2</th>
                            <th>作成日</th>
                            <th>更新日</th>   
                            <th>商品表示状態</th>
                        </tr>
                        <?php 
                            for($i = 0; $i < count($products); $i++):
                                $isHidden = !is_null($products[$i]['hidden_at']); // hidden_at が null でなければ true
                        ?>
                        <tr class="row" data-id="<?php echo htmlspecialchars($products[$i]['id'], ENT_QUOTES, 'UTF-8'); ?>" style="color: <?php echo $isHidden ? 'red' : 'inherit'; ?>; cursor: pointer;">
                            <td><img src="<?php echo htmlspecialchars($products[$i]['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像" class="product-image"></td>
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
