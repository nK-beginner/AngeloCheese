




<?php
    session_start();
    require_once __DIR__.'/../Admin/Backend/connection.php';
    require_once __DIR__.'/../Admin/Backend/csrf_token.php';

    // 画像データ取得
    $stmt = $pdo2 -> prepare("SELECT p.id, pi.image_path, p.name, p.tax_included_price, p.category_id, p.category_name, p.hidden_at
        FROM product_images AS pi
        JOIN products AS p ON pi.product_id = p.id
        WHERE pi.is_main = 1
        ORDER BY p.id
    ");
    $stmt -> execute();
    $products = $stmt -> fetchALl(PDO::FETCH_ASSOC);

    // カテゴリー商品分割
    $categorizedProducts = [];
    foreach($products as $product) {
        $category = $product['category_name'];
        $categorizedProducts[$category][] = $product;
    }

    // CSV出力
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo2 -> prepare("SELECT * FROM products");
        $stmt -> execute();
        $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        // 余分な行削除→これがないと15行余分な行が出てくる
        ob_clean();

        // CSVヘッダー設定
        header('Content-Type: text/csv; charset=shift_JIS');
        header('Content-Disposition: attachment; filename="products.csv"');

        // 出力バッファ
        $output = fopen('php://output', 'w');

        // CSVカラム名(Shift_JISに変換)
        fputcsv($output, array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), [
                '商品ID', '商品名', '商品説明', 'カテゴリー名', 'キーワード', 'サイズ1', 'サイズ2', '税率', '値段', '税込価格', '原価', 
                '消費期限1', '消費期限2', '消費期限(解凍後)1', '消費期限(解凍後)2', '作成日', '更新日', '商品表示状態'
        ]));
    
        // データ書き込み(Shift_JISに変換)
        foreach($products as $product) {
            fputcsv($output, array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), [
                $product['id'],
                $product['name'],
                $product['description'],
                $product['category_name'],
                $product['keyword'],
                $product['size1'] . 'cm',
                $product['size2'] . 'cm',
                $product['tax_rate'] * 100 . '%',
                '¥' . number_format($product['price']),
                '¥' . number_format($product['tax_included_price']),
                '¥' . number_format($product['cost']),
                $product['expirationdate_min1'] . '日',
                $product['expirationdate_max1'] . '日',
                $product['expirationdate_min2'] . '日',
                $product['expirationdate_max2'] . '日',
                $product['created_at'],
                $product['updated_at'],
                !is_null($product['hidden_at']) ? '非表示中' : '',
            ]));
        }

        // 出力を閉じる
        fclose($output);
        exit();
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- ヘッダータグ -->
    <?php include 'headTags.php' ?>

    <title>商品一覧</title>

    <!-- 商品編集用CSS -->
    <link rel="stylesheet" href="CSS/allItems.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="grid-container">
        <!-- サイドバー -->
        <?php include 'sidebar.php'; ?>

        <!-- 商品詳細エリア -->
        <main>
            <h1>商品一覧</h1>
            <form action="allItems.php" method="POST">
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
                                        <div class="product" data-id="<?php echo htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <img src="<?php echo htmlspecialchars('/AngeloCheese/php/admin/' . $item['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像">
                                            <h3><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                            <p>
                                                ¥<?php echo number_format($item['tax_included_price']); ?><span2>(税込)</span2>
                                                <?php echo !is_null($item['hidden_at']) ? '<strong>非表示中</strong>' : ''; ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <dialog>
                <div class="dialog-container">
                    <h3>商品を編集しますか？</h3>
                    <div class="btns">
                        <button class="cancel">キャンセル</button>
                        <button class="confirm">はい</button>
                    </div>
                </div>
            </dialog>
        </main>
    </div>
    <script src="JS/sidebar.js"></script> <!-- サイドバー -->
    <script src="JS/allItems.js"></script> <!-- 商品一覧用 -->
</body>
</html>
