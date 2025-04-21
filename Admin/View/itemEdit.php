<?php require_once __DIR__.'/../Backend/config.php' ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- ヘッダータグ -->
    <?php include 'headTags.php' ?>

    <title>商品編集</title>

    <!-- 商品削除CSS（テーブルスタイル流用） -->
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/itemDelete.css?v=<?php echo time(); ?>">

    <!-- 商品編集用CSS -->
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/itemEdit.css?v=<?php echo time(); ?>">
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
                <form class="edit-grid-container" action="itemEdit.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">  
                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($editItem['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <!-- 商品詳細 -->
                    <div class="product-info">
                        <!-- 商品名など -->
                        <div class="form-block">
                            <h3>商品表示状態</h3>
                            <div class="v-block">
                                <label class="radio"><input type="radio" value="on"  id="on"  name="display" <?php if($editItem['hidden_at'] === NULL) echo 'checked'; ?> >表示</label>
                                <label class="radio"><input type="radio" value="off" id="off" name="display" <?php if($editItem['hidden_at'] !== NULL) echo 'checked'; ?> >非表示</label>
                            </div>

                            <h3>商品名</h3>
                            <input class="user-input" type="text" name="product-name" value="<?php echo htmlspecialchars($editItem['name'], ENT_QUOTES, 'UTF-8'); ?>">

                            <h3>商品説明</h3>
                            <textarea class="user-input" type="text" name="product-description"><?php echo htmlspecialchars($editItem['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>

                            <h3>カテゴリー名</h3>
                            <select class="user-input" name="product-category">
                                <option value="0"  <?php if($editItem['category_id'] == 0) echo 'selected'; ?>>選択してください。</option>
                                <option value="1"  <?php if($editItem['category_id'] == 1) echo 'selected'; ?>>人気商品</option>
                                <option value="2"  <?php if($editItem['category_id'] == 2) echo 'selected'; ?>>チーズケーキサンド</option>
                                <option value="3"  <?php if($editItem['category_id'] == 3) echo 'selected'; ?>>アンジェロチーズ</option>
                                <option value="99" <?php if($editItem['category_id'] ==99) echo 'selected'; ?>>その他</option>
                            </select>

                            <h3>キーワード</h3>
                            <input class="user-input" type="text" name="keyword" value="<?php echo htmlspecialchars($editItem['keyword'], ENT_QUOTES, 'UTF-8'); ?>">

                            <h3>サイズ(cm)</h3>
                            <div class="h-block">
                                <input class="user-input number" type="text" inputmode="numeric" maxlength="3" name="size1" value="<?php echo htmlspecialchars($editItem['size1'], ENT_QUOTES, 'UTF-8'); ?>">
                                <p>×</p>
                                <input class="user-input number" type="text" inputmode="numeric" maxlength="3" name="size2" value="<?php echo htmlspecialchars($editItem['size2'], ENT_QUOTES, 'UTF-8'); ?>">                                
                            </div>
                        </div>

                        <!-- 金額など -->
                        <div class="form-block">
                            <h3>税率</h3>
                            <div class="v-block tax-rate">
                                <label class="radio" for="tax10"><input type="radio" value="0.1"  pattern="\d*" id="tax10" name="tax-rate" <?php if($editItem['tax_rate'] == '0.10') echo 'checked'; ?> >10%</label>
                                <label class="radio" for="tax8" ><input type="radio" value="0.08" pattern="\d*" id="tax8"  name="tax-rate" <?php if($editItem['tax_rate'] == '0.08') echo 'checked'; ?> >8%</label>
                            </div>
                            
                            <div class="h-block price-block">
                                <div class="v-block">
                                    <h3>価格</h3>
                                    <input class="price user-input" type="text" inputmode="numeric" id="price" name="price" value="<?php echo htmlspecialchars(number_format($editItem['price']), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>

                                <div class="v-block">
                                    <h3>税込み価格</h3>
                                    <input type="text" class="user-input" id="tax-included-price-show" readonly value="¥0">
                                    <!-- データ送信用（hidden） -->
                                    <input type="hidden" id="tax-included-price-hidden" name="tax-included-price" value="0">
                                </div>
                            </div>

                            <div class="v-block">
                                <h3 class="cost">原価</h3>
                                <input class="user-input cost-input" type="text" name="cost" inputmode="numeric" value="<?php echo htmlspecialchars(number_format($editItem['cost']), ENT_QUOTES, 'UTF-8'); ?>">                                
                            </div>
                        </div>

                        <!-- 消費期限 -->
                        <div class="form-block">
                            <h3>消費期限</h3>
                            <div class="h-block">
                                <input class="user-input number" type="text" name="expirationDate-min1" inputmode="numeric" value="<?php echo htmlspecialchars(number_format($editItem['expirationdate_min1']), ENT_QUOTES, 'UTF-8'); ?>" maxlength="3">
                                <p>～</p>
                                <input class="user-input number" type="text" name="expirationDate-max1" inputmode="numeric" value="<?php echo htmlspecialchars(number_format($editItem['expirationdate_max1']), ENT_QUOTES, 'UTF-8'); ?>" maxlength="3">
                                <p>日間</p>
                            </div>

                            <h3>消費期限(解凍後)</h3>
                            <div class="h-block">
                                <input class="user-input number" type="text" name="expirationDate-min2" inputmode="numeric" value="<?php echo htmlspecialchars(number_format($editItem['expirationdate_min2']), ENT_QUOTES, 'UTF-8'); ?>" maxlength="3">
                                <p>～</p>
                                <input class="user-input number" type="text" name="expirationDate-max2" inputmode="numeric" value="<?php echo htmlspecialchars(number_format($editItem['expirationdate_max2']), ENT_QUOTES, 'UTF-8'); ?>" maxlength="3">
                                <p>日間</p>
                            </div>
                        </div>
                        <input type="submit">
                    </div>

                    <!-- 商品画像 -->
                    <div class="product-pics">
                        <img class="product-image" src="<?php echo htmlspecialchars('/../AngeloCheese/Admin/uploads/' . $editItem['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
                    </div>
                </form>

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
                            <th>消費期限1<br>(解凍後)</th>
                            <th>消費期限2<br>(解凍後)</th>
                            <th>商品表示状態</th>
                        </tr>
                        <?php 
                            for($i = 0; $i < count($products); $i++):
                                $isHidden = !is_null($products[$i]['hidden_at']); // hidden_at が null でなければ true
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
                            <td><?php echo htmlspecialchars($products[$i]['expirationdate_min1'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                            <td><?php echo htmlspecialchars($products[$i]['expirationdate_max1'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                            <td><?php echo htmlspecialchars($products[$i]['expirationdate_min2'], ENT_QUOTES, 'UTF-8'); ?>日</td>
                            <td><?php echo htmlspecialchars($products[$i]['expirationdate_max2'], ENT_QUOTES, 'UTF-8'); ?>日</td>
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
    <script src="/../AngeloCheese/Admin/JS/sidebar.js"></script> <!-- サイドバー -->
    <script src="/../AngeloCheese/Admin/JS/itemEdit.js"></script> <!-- 編集用 -->
</body>
</html>