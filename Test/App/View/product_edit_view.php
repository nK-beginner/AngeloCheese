<!DOCTYPE html>
<html lang="ja">
<head>
    <?php require_once __DIR__.'/../../Core/head_tags.php'; ?>
    <title>商品編集</title>
    <link rel="stylesheet" href="/../Test/Public/CSS/itemDelete.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/../Test/Public/CSS/itemEdit.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/../Test/Public/CSS/itemAdd.css?v=<?php echo time(); ?>">
</head>
<body>
    <form method="POST" class="product-form" enctype="multipart/form-data">
        <div class="grid-container">
            <?php require_once __DIR__.'/../View/sidebar.php'; ?>

            <main>
                <div class="main-container">
                    <h1>商品編集</h1>

                    <a href="/Test/Public/product_edit.php">一覧へ</a>

                    <?php if(!empty($_SESSION['errors'])): ?>
                        <div class="error-container">
                            <?php foreach($_SESSION['errors'] as $error): ?>
                                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                            <?php endforeach; ?>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <div class="product-info">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="productId" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="form-block">
                            <div class="block">
                                <h3>商品表示状態</h3>
                                <label class="user-radio"><input type="radio" value="on"  id="on"  name="display" <?php if($product['hidden_at'] === NULL) echo 'checked'; ?> >表示</label>
                                <label class="user-radio"><input type="radio" value="off" id="off" name="display" <?php if($product['hidden_at'] !== NULL) echo 'checked'; ?> >非表示</label>
                            </div>
                            <div class="block">
                                <h3>メイン画像</h3>
                                <input type="hidden" name="mainImgChanged" class="main-img-changed" value="0">

                                <div class="preview-container <?php echo isset($mainImg['image_path']) ? 'show' : '' ?>">
                                    <?php if(isset($mainImg['image_path'])): ?>
                                        <img class="main-img" src="<?php echo htmlspecialchars('/../Test/Public/uploads/' . $mainImg['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="メイン画像">
                                        <div class="delete-main-img">✖</div>
                                    <?php endif; ?>
                                </div>
                                <div class="drop-area main-drop">画像をドラッグ&ドロップ、またはクリックで選択</div>
                                <input type="file" class="file-input main-file" accept="image/*" name="image">
                            </div>

                            <!-- <div class="block">
                                <h3>サブ画像（複数追加可能）</h3>
                                <div class="sub-preview-wrapper">
                                    <?php foreach($subImgs as $subImg): ?>
                                        <input type="hidden" name="SubImgChanged" class="sub-img-changed" value="0">
                                        <div class="sub-preview-container <?php isset($subImg) ? 'show' : '' ?>">
                                            <img class="sub-img" src="<?php htmlspecialchars('/../Test/Public/uploads/' . $subImg['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="サブ画像">
                                            <div class="delete-sub-img">✖</div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="drop-area sub-drop" id="sub-drop-area">画像をドラッグ&ドロップ、またはクリックで選択</div>
                                <input type="file" class="sub-file" accept="image/*" name="images[]" multiple>
                            </div> -->

                            <div class="block">
                                <h3>サブ画像（複数追加可能）</h3>
                                <div class="sub-preview-wrapper">
                                    <?php foreach($subImgs as $index => $subImg): ?>
                                        <div class="sub-preview-container <?php echo isset($subImg) ? 'show' : '' ?>">
                                            <input type="hidden" name="subImgChanged[<?php echo $index; ?>]" class="sub-img-changed" value="0">
                                            <img class="sub-img" src="<?php echo htmlspecialchars('/../Test/Public/uploads/' . $subImg['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="サブ画像">
                                            <div class="delete-sub-img">✖</div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="drop-area sub-drop" id="sub-drop-area">画像をドラッグ&ドロップ、またはクリックで選択</div>
                                <input type="file" class="sub-file" accept="image/*" name="images[]" multiple>
                            </div>
                        </div>

                        <div class="form-block">
                            <div class="block">
                                <h3>商品名</h3>
                                <input type="text" class="user-input" name="name" placeholder="例：チーズケーキサンド" value="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="block">
                                <h3>商品説明</h3>
                                <textarea class="user-input" name="description"><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>

                        <div class="grid-block">
                            <div class="form-block">
                                <div class="block">
                                    <h3>商品カテゴリー</h3>
                                    <select class="user-input" name="category">
                                        <option value="0"  <?php if($product['category_id'] == 0) echo 'selected'; ?>>選択してください。</option>
                                        <option value="1"  <?php if($product['category_id'] == 1) echo 'selected'; ?>>人気商品</option>
                                        <option value="2"  <?php if($product['category_id'] == 2) echo 'selected'; ?>>チーズケーキサンド</option>
                                        <option value="3"  <?php if($product['category_id'] == 3) echo 'selected'; ?>>アンジェロチーズ</option>
                                        <option value="99" <?php if($product['category_id'] ==99) echo 'selected'; ?>>その他</option>
                                    </select>
                                </div>

                                <div class="block">
                                    <h3>キーワード</h3>
                                    <input type="text" class="user-input" name="keyword" placeholder="例：北海道産" value="<?php echo htmlspecialchars($product['keyword'], ENT_QUOTES, 'UTF-8'); ?>">                       
                                </div>
                            </div>

                            <div class="form-block">
                                <div class="block">
                                    <h3>サイズ (縦×横)</h3>
                                    <div class="sub-block">
                                        <input type="text" class="user-input" name="size1" inputmode="numeric" placeholder="例：15" maxlength="3" value="<?php echo htmlspecialchars($product['size1'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <p>✖</p>
                                        <input type="text" class="user-input" name="size2" inputmode="numeric" placeholder="例：15" maxlength="3" value="<?php echo htmlspecialchars($product['size2'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-block">
                            <div class="block">
                                <h3>税率</h3>
                                <div class="sub-block">
                                    <label class="user-radio"><input type="radio" value="0.1"  pattern="\d*" name="tax-rate" <?php if($product['tax_rate'] == '0.10') echo 'checked'; ?> >10%</label>
                                    <label class="user-radio"><input type="radio" value="0.08" pattern="\d*" name="tax-rate" <?php if($product['tax_rate'] == '0.08') echo 'checked'; ?> >8%</label>
                                </div>
                            </div>

                            <div class="block">
                                <div class="sub-block">
                                    <div class="sub-block2">
                                        <h3>価格</h3>
                                        <input type="text" class="user-input" name="price" id="price" pattern="\d*" inputmode="numeric" value="<?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    
                                    <div class="sub-block2">
                                        <h3>原価</h3>
                                        <input type="text" class="user-input" name="cost" pattern="\d*" inputmode="numeric" value="<?php echo htmlspecialchars($product['cost'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="block">
                                <h3>税込み価格（自動計算）</h3>
                                <input type="text"   value="¥0" class="user-input hidden-input" id="tax-included-price-display" readonly>
                                <input type="hidden" value=""  name="tax-included-price" id="tax-included-price-hidden">
                            </div>
                        </div>

                        <div class="form-block">
                            <div class="block">
                                <h3>消費期限</h3>
                                <div class="sub-block">
                                    <input type="text" class="user-input" name="expiration-date-min1" pattern="\d*" inputmode="numeric" maxlength="3" value="<?php echo htmlspecialchars($product['expirationDate_min1'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <p>〜</p>
                                    <input type="text" class="user-input" name="expiration-date-max1" pattern="\d*" inputmode="numeric" maxlength="3" value="<?php echo htmlspecialchars($product['expirationDate_max1'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <p>日間</p>
                                </div>
                            </div>

                            <div class="block">
                                <h3>消費期限（解凍後）</h3>
                                <div class="sub-block">
                                    <input type="text" class="user-input" name="expiration-date-min2" pattern="\d*" inputmode="numeric" maxlength="3" value="<?php echo htmlspecialchars($product['expirationDate_min2'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <p>〜</p>
                                    <input type="text" class="user-input" name="expiration-date-max2" pattern="\d*" inputmode="numeric" maxlength="3" value="<?php echo htmlspecialchars($product['expirationDate_max2'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <p>日間</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="submit-btn" type="submit">送信</button>                    
                </div>
            </main>
        </div>
    </form>

    <script type="module" src="/../Test/Public/JS/sidebar.js"></script> <!-- サイドバー -->
    <script type="module" src="/../Test/Public/JS/itemEdit.js"></script> <!-- 編集用 -->
</body>
</html>