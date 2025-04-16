<?php require_once __DIR__.'/../PHP/itemAdd.php' ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- ヘッダータグ -->
    <?php include 'headTags.php' ?>

    <title>商品追加</title>
    
    <!-- 商品追加用CSS -->
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/itemAdd.css?v=<?php echo time(); ?>">
</head>
<body>
    <form action="itemAdd.php" method="POST" enctype="multipart/form-data">
        <!-- CSRFトークン -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

        <div class="grid-container">

            <!-- サイドバー -->
            <?php include 'sidebar.php'; ?>

            <!-- 商品詳細エリア -->
            <main>
                <h1>商品追加</h1>
                <div class="product-info">
                    <!-- 商品名など -->
                    <div class="form-block">
                        <!-- CSRFトークン -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        
                        <!-- エラーメッセージ -->
                        <?php if(!empty($errors)): ?>
                            <div class="error-msg">
                                <?php foreach($errors as $error): ?>
                                    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- 商品名 -->
                        <div class="block">
                            <label for="product-name">商品名</label>
                            <input type="text" class="product-name" id="product-name" name="product-name" placeholder="例：チーズケーキサンド" required>                        
                        </div>

                        <!-- 商品説明 -->
                        <div class="block">
                            <label for="product-description" class="label">商品説明</label>
                            <textarea class="product-description" id="product-description" name="product-description" required></textarea>                        
                        </div>

                        <!-- 商品カテゴリー -->
                        <div class="block">
                            <label for="product-category" class="label">商品カテゴリー</label>
                            <select class="product-category" name="product-category" id="product-category">
                                <option value="0" selected>選択してください。</option>
                                <option value="1">人気商品</option>
                                <option value="2">チーズケーキサンド</option>
                                <option value="3">アンジェロチーズ</option>
                                <option value="99">その他</option>
                            </select>
                        </div>
                        
                        <!-- キーワード -->
                        <div class="block">
                            <label for="keyword" class="label">キーワード</label>
                            <input type="text" class="keyword" id="keyword" name="keyword" placeholder="例：北海道産">                        
                        </div>

                        <!-- サイズ -->
                        <div class="size-title">
                            <label for="size1" class="label">サイズ(cm)</label>
                        </div>
                        <div class="flex-block size-block">
                            <div class="block">
                                <input type="text" class="size" id="size1" name="size1" inputmode="numeric" placeholder="例：15" maxlength="3" required>
                            </div>
                            <p>✖</p>
                            <div class="block">
                                <input type="text" class="size" id="size2" name="size2" inputmode="numeric" placeholder="例：10" maxlength="3" required>                        
                            </div> 
                        </div>
                    </div>

                    <!-- 金額など -->
                    <div class="form-block">
                        <div class="">
                            <label>税率</label>
                            <div class="tax-rate">
                                <label><input type="radio" value="0.1"  pattern="\d*" class="tax" id="tax10" name="tax-rate" checked>10%</label>
                                <label><input type="radio" value="0.08" pattern="\d*" class="tax" id="tax8"  name="tax-rate">8%</label>
                            </div>
                        </div>
                        <div class="flex-block price-block">
                            <!-- 価格 -->
                            <div class="block">
                                <label for="price" class="label">価格</label>
                                <input type="text" pattern="\d*" class="price" id="price" name="price" inputmode="numeric" maxlength="5" required>                        
                            </div>

                            <!-- 税込み価格（自動計算） -->
                            <div class="block">
                                <label for="tax-included-price" class="label">税込み価格</label>
                                <!-- 表示用（ユーザーが編集できない） -->
                                <input type="text" class="tax-included-price" id="tax-included-price-display" readonly value="¥0">
                                <!-- データ送信用（hidden） -->
                                <input type="hidden" id="tax-included-price" name="tax-included-price" value="0">
                            </div>

                            <!-- 原価 -->
                            <div class="block">
                                <label for="cost" class="label">原価</label>
                                <input type="text" pattern="\d*" class="cost" id="cost" name="cost" inputmode="numeric" maxlength="5" required>                        
                            </div>
                        </div>
                    </div>

                    <!-- 消費期限 -->
                    <div class="form-block expiration">
                        <!-- 消費期限解凍前 -->
                        <div class="before">
                            <label for="expirationDate">消費期限</label>
                            <div class="flex-block date-block">
                                <div class="firstdate">
                                    <input type="text" name="expirationDate-min1"  maxlength="3" inputmode="numeric"><p>～</p>
                                </div>
                                <div class="lastdate">
                                    <input type="text" name="expirationDate-max1" maxlength="3" inputmode="numeric"><p>日間</p>
                                </div>
                            </div>
                        </div>

                        <!-- 消費期限解凍後 -->
                        <div class="after">
                            <label for="expirationDate">消費期限(解凍後)</label>
                            <div class="flex-block date-block">
                                <div class="firstdate">
                                    <input type="text" name="expirationDate-min2" maxlength="3" inputmode="numeric"><p>～</p>
                                </div>
                                <div class="lastdate">
                                    <input type="text" name="expirationDate-max2" maxlength="3" inputmode="numeric"><p>日間</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- 写真投稿エリア -->
            <aside class="photo-drop-area">
                <div class="photo-drop-container">
                    <!-- メイン画像 -->
                    <h3 class="thumbnail">メイン画像</h3>
                    <div class="drop-area">
                        <p id="drop-text">ここに画像をドロップ<br>または</p>
                        <label for="thumbnail" class="custom-button">ファイルを選択</label>
                        <input type="file" name="thumbnail" class="file-input" id="thumbnail" accept="image/*" style="position: absolute; top: 0; z-index: 100;">
                    </div>

                    <h3 class="subtitle">サブ画像<span>(ドラッグアンドドロップで追加できます。)</span></h3>
                    <div class="flex-block">

                        <!-- サブ画像 1 -->
                        <div class="drop-area">
                            <label for="file1" class="custom-button">+</label>
                            <input type="file" name="file1" class="file-input" id="file1" accept="image/*" style="position: absolute; top: 0; z-index: 100;">
                        </div>

                        <!-- サブ画像 2 -->
                        <div class="drop-area">
                            <label for="file2" class="custom-button">+</label>
                            <input type="file" name="file2" class="file-input" id="file2" accept="image/*" style="position: absolute; top: 0; z-index: 100;">
                        </div>

                        <!-- サブ画像 3 -->
                        <div class="drop-area">
                            <label for="file3" class="custom-button">+</label>
                            <input type="file" name="file3" class="file-input" id="file3" accept="image/*" style="position: absolute; top: 0; z-index: 100;">
                        </div>

                        <!-- サブ画像 4 -->
                        <div class="drop-area">
                            <label for="file4" class="custom-button">+</label>
                            <input type="file" name="file4" class="file-input" id="file4" accept="image/*" style="position: absolute; top: 0; z-index: 100;">
                        </div>

                        <!-- サブ画像 5 -->
                        <div class="drop-area">
                            <label for="file5" class="custom-button">+</label>
                            <input type="file" name="file5" class="file-input" id="file5" accept="image/*" style="position: absolute; top: 0; z-index: 100;">
                        </div>
                    </div>

                    <!-- ボタン -->
                    <div class="flex-btns">
                        <input type="reset" class="cancelbtn" value ="キャンセル"></input>
                        <input type="submit" class="savebtn" value ="保存"></input>
                    </div>
                </div>
            </aside>
        </div>
    </form>
    <script src="/../AngeloCheese/Admin/JS/sidebar.js"></script> <!-- サイドバー -->
    <script src="/../AngeloCheese/Admin/JS/itemAdd.js"></script> <!-- 追加一覧用 -->
</body>
</html>