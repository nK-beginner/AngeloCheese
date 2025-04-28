<?php require_once __DIR__.'/../PHP/itemEdit.php' ?>

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

            <?php if(!isset($editItem)): ?>
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
                    <form autocomplete="off" action="itemEdit.php" method="POST" class="hidden-form">
                        <input autocomplete="off" type="hidden" name="id" class="product-id">
                    </form>
                </div>

            <?php else: ?>
                <a href="itemEdit.php">一覧へ</a>
                <form autocomplete="off" method="POST" class="product-form" enctype="multipart/form-data">
                    <div class="product-info">
                        <input autocomplete="off" type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input autocomplete="off" type="hidden" name="item_id" value="<?php echo htmlspecialchars($editItem['id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="form-block">
                            <div class="block">
                                <h3>商品表示状態</h3>
                                <label class="user-radio"><input autocomplete="off" type="radio" value="on"  id="on"  name="display" <?php if($editItem['hidden_at'] === NULL) echo 'checked'; ?> >表示</label>
                                <label class="user-radio"><input autocomplete="off" type="radio" value="off" id="off" name="display" <?php if($editItem['hidden_at'] !== NULL) echo 'checked'; ?> >非表示</label>
                            </div>
                            <div class="block">
                                <h3>メイン画像</h3>
                                <div class="preview-container <?php echo isset($editItem['image_path']) ? 'show' : '' ?>">
                                    <?php if(isset($editItem['image_path'])): ?>
                                        <img class="main-img" src="<?php echo htmlspecialchars('/../AngeloCheese/Admin/uploads/' . $editItem['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
                                        <div class="delete-main-img">✖</div>
                                    <?php endif; ?>
                                </div>
                                <div class="drop-area main-drop">画像をドラッグ&ドロップ、またはクリックで選択</div>
                                <input autocomplete="off" type="file" class="file-input main-file" accept="image/*" name="image">
                            </div>

                            <div class="block">
                                <h3>サブ画像（複数追加可能）</h3>
                                <div class="sub-preview-wrapper">
                                    <?php foreach($subImages as $subImage): ?>
                                        <div class="sub-preview-container <?php echo isset($subImage) ? 'show' : '' ?>">
                                            <img class="sub-img" src="<?php echo htmlspecialchars('/../AngeloCheese/Admin/uploads/' . $subImage, ENT_QUOTES, 'UTF-8'); ?>" alt="">
                                            <div class="delete-sub-img">✖</div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="drop-area sub-drop" id="sub-drop-area">画像をドラッグ&ドロップ、またはクリックで選択</div>
                                <input autocomplete="off" type="file" class="sub-file" accept="image/*" name="images[]" multiple>
                            </div>
                        </div>

                        <div class="form-block">
                            <div class="block">
                                <h3>商品名</h3>
                                <input autocomplete="name" type="text" class="user-input" name="productName" placeholder="例：チーズケーキサンド" value="<?php echo htmlspecialchars($editItem['name'], ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="block">
                                <h3>商品説明</h3>
                                <textarea autocomplete="off" class="user-input" name="description"><?php echo htmlspecialchars($editItem['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="grid-block">
                            <div class="form-block">
                                <div class="block">
                                    <h3>商品カテゴリー</h3>
                                    <select autocomplete="off" class="user-input" name="category">
                                        <option value="0"  <?php if($editItem['category_id'] == 0) echo 'selected'; ?>>選択してください。</option>
                                        <option value="1"  <?php if($editItem['category_id'] == 1) echo 'selected'; ?>>人気商品</option>
                                        <option value="2"  <?php if($editItem['category_id'] == 2) echo 'selected'; ?>>チーズケーキサンド</option>
                                        <option value="3"  <?php if($editItem['category_id'] == 3) echo 'selected'; ?>>アンジェロチーズ</option>
                                        <option value="99" <?php if($editItem['category_id'] ==99) echo 'selected'; ?>>その他</option>
                                    </select>
                                </div>

                                <div class="block">
                                    <h3>キーワード</h3>
                                    <input autocomplete="off" type="text" class="user-input" name="keyword" placeholder="例：北海道産" value="<?php echo htmlspecialchars($editItem['keyword'], ENT_QUOTES, 'UTF-8'); ?>">                       
                                </div>
                            </div>

                            <div class="form-block">
                                <div class="block">
                                    <h3>サイズ(cm)</h3>
                                    <div class="sub-block">
                                        <input autocomplete="off" type="text" class="user-input" name="size1" inputmode="numeric" placeholder="例：15" maxlength="3" value="<?php echo htmlspecialchars($editItem['size1'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <p>✖</p>
                                        <input autocomplete="off" type="text" class="user-input" name="size2" inputmode="numeric" placeholder="例：15" maxlength="3" value="<?php echo htmlspecialchars($editItem['size2'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-block">
                            <div class="block">
                                <h3>税率</h3>
                                <div class="sub-block">
                                    <label class="user-radio"><input autocomplete="off" type="radio" value="0.1"  pattern="\d*" name="tax-rate" <?php if($editItem['tax_rate'] == '0.10') echo 'checked'; ?> >10%</label>
                                    <label class="user-radio"><input autocomplete="off" type="radio" value="0.08" pattern="\d*" name="tax-rate" <?php if($editItem['tax_rate'] == '0.08') echo 'checked'; ?> >8%</label>
                                </div>
                            </div>

                            <div class="block">
                                <div class="sub-block">
                                    <div class="sub-block2">
                                        <h3>価格</h3>
                                        <input autocomplete="off" type="text" class="user-input" name="price" id="price" pattern="\d*" inputmode="numeric" value="<?php echo htmlspecialchars($editItem['price'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    
                                    <div class="sub-block2">
                                        <h3>原価</h3>
                                        <input autocomplete="off" type="text" class="user-input" name="cost" pattern="\d*" inputmode="numeric" value="<?php echo htmlspecialchars($editItem['cost'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="block">
                                <h3>税込み価格（自動計算）</h3>
                                <input autocomplete="off" type="text"   value="¥0" class="user-input hidden-input" id="tax-included-price-display" readonly>
                                <input autocomplete="off" type="hidden" value=""  name="tax-included-price" id="tax-included-price-hidden">
                            </div>
                        </div>

                        <div class="form-block">
                            <div class="block">
                                <h3>消費期限</h3>
                                <div class="sub-block">
                                    <input autocomplete="off" type="text" class="user-input" name="expiration-date-min1" pattern="\d*" inputmode="numeric" maxlength="3" value="<?php echo htmlspecialchars($editItem['expirationDate_min1'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <p>〜</p>
                                    <input autocomplete="off" type="text" class="user-input" name="expiration-date-max1" pattern="\d*" inputmode="numeric" maxlength="3" value="<?php echo htmlspecialchars($editItem['expirationDate_max1'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <p>日間</p>
                                </div>
                            </div>

                            <div class="block">
                                <h3>消費期限（解凍後）</h3>
                                <div class="sub-block">
                                    <input autocomplete="off" type="text" class="user-input" name="expiration-date-min2" pattern="\d*" inputmode="numeric" maxlength="3" value="<?php echo htmlspecialchars($editItem['expirationDate_min2'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <p>〜</p>
                                    <input autocomplete="off" type="text" class="user-input" name="expiration-date-max2" pattern="\d*" inputmode="numeric" maxlength="3" value="<?php echo htmlspecialchars($editItem['expirationDate_max2'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <p>日間</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit">送信</button>
                </form>
            <?php endif; ?>
        </main>
    </div>
    <script type="module" src="/../AngeloCheese/Admin/JS/sidebar.js"></script> <!-- サイドバー -->
    <script type="module" src="/../AngeloCheese/Admin/JS/itemEdit.js"></script> <!-- 編集用 -->
</body>
</html>