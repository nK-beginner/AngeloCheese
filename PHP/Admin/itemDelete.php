




<?php
    session_start();
    require_once __DIR__ . '/Backend/connection.php';
    require_once __DIR__ . '/Backend/csrf_token.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    // データ取得・表示
    try {
        $stmt = $pdo2 -> prepare("SELECT * FROM products");
        $stmt -> execute();
        $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        $errors[] = 'データベース接続エラー:' . $e -> getMessage();
    }

    // データ削除
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $errors[] = 'CSRFトークン不一致エラー';
            
        } else {
            // CSRFトークン再生成
            unset($_SESSION['csrf_token']);
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));            
        }

        // フォームデータ取得
        $deletingItemsId = $_POST['delete'] ?? [];

        // 削除
        try {
            $pdo2 -> beginTransaction();

            if(!empty($deletingItemsId)) {
                 // プレースホルダー（？）を$deletingItemsIdの個数分作成 → 削除対象のID数が変わっても対応する
                $id = implode(',', array_fill(0, count($deletingItemsId), '?'));
                $stmt = $pdo2 -> prepare("UPDATE products SET hidden_at = NOW() WHERE id IN ($id)");

                foreach($deletingItemsId as $index => $id) {
                    $stmt -> bindValue($index + 1, (int)$id, PDO::PARAM_INT);
                }
                $stmt -> execute();
                $pdo2 -> commit();

                header('Location: itemDelete.php');
                exit();
            }

        } catch(PDOException $e) {
            $pdo2 -> rollback();
            error_log('データベース接続エラー:' . $e -> getMessage());
            $errors[] = 'データベース接続エラーが発生しました。管理者にお問い合わせください';
        }

    }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- ヘッダータグ -->
    <?php include 'headTags.php' ?>

    <title>商品削除</title>

    <!-- 商品削除用CSS -->
    <link rel="stylesheet" href="CSS/itemDelete.css?v=<?php echo time(); ?>">
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
                        <?php 
                            $productCnt = count($products);
                            for($i = 0; $i < $productCnt; $i++):
                                $isHidden = !is_null($products[$i]['hidden_at']); // hidden_at が null でなければ true
                        ?>
                        <tr style="color: <?php echo $isHidden ? 'red' : 'inherit'; ?>;">
                            <td>
                                <input type="checkbox" name="delete[]" class="check-boxes" value="<?php echo htmlspecialchars($products[$i]['id'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $isHidden ? 'disabled' : ''; ?>>
                            </td>
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
                </div>

                <input type="submit" class="delete-btn" value="削除"></input>                
            </form>

        </main>
    </div>
    <script src="JS/sidebar.js"></script> <!-- サイドバー -->
    <script src="JS/itemDelete.js"></script> <!-- 削除画面用 -->
</body>
</html>
