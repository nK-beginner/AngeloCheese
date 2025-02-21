




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
            <div class="table-records">
                <table>
                    <tr>
                        <!-- 17個 -->
                        <th></th>
                        <th>商品id</th>
                        <th>商品名</th>
                        <th>商品説明</th>
                        <th>カテゴリーid</th>
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
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>11</td>
                        <td>チーズケーキサンド（プレーン）</td>
                        <td>Angelo Cheese大人気商品のチーズケーキサンド、プレーン味。</td>
                        <td>人気商品</td>
                        <td>キーワード</td>
                        <td>20</td>
                        <td>10</td>
                        <td>10%</td>
                        <td>350円</td>
                        <td>385円</td>
                        <td>120円</td>
                        <td>2</td>
                        <td>3</td>
                        <td>1</td>
                        <td>2</td>
                        <td>2025-02-20 21:20:25</td>
                        <td>2025-02-20 21:20:25</td>
                        <td>Null</td>
                    </tr>
                </table>
            </div>

            <button class="delete-btn">削除</button>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>
