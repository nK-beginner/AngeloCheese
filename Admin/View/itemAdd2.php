<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ヘッダータグ -->
    <?php include 'headTags.php' ?>
    <title>testView</title>
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/itemAdd2.css?v=<?php echo time(); ?>">
</head>
<body>
    <form method="POST" class="product-form" enctype="multipart/form-data">
        <div class="grid-container">
            <!-- サイドバー -->
            <?php include 'sidebar.php'; ?>
            <!-- 商品詳細エリア -->
            <main>
                <h1>商品追加</h1>
                <div class="product-info">
                    <div class="form-block">
                        <div class="block">
                            <h3>メイン画像</h3>
                            <div class="preview-container"></div>
                            <div class="drop-area main-drop">画像をドラッグ&ドロップ、またはクリックで選択</div>
                            <input type="file" class="file-input main-file" accept="image/*" name="image">
                        </div>

                        <div class="block">
                            <h3>サブ画像（複数追加可能）</h3>
                            <div class="sub-preview-wrapper"></div>
                            <div class="drop-area sub-drop" id="sub-drop-area">画像をドラッグ&ドロップ、またはクリックで選択</div>
                            <input type="file" class="sub-file" accept="image/*" name="images[]" multiple>
                        </div>
                    </div>

                    <div class="form-block">
                        <div class="block">
                            <h3>商品名</h3>
                            <input type="text" class="user-input" name="name" placeholder="例：チーズケーキサンド" >
                        </div>
                        <div class="block">
                            <h3>商品説明</h3>
                            <textarea class="user-input" name="description" ></textarea>
                        </div>
                    </div>

                    <div class="grid-block">
                        <div class="form-block">
                            <div class="block">
                                <h3>商品カテゴリー</h3>
                                <select class="user-input" name="category">
                                    <option value="0" selected>選択してください。</option>
                                    <option value="1">人気商品</option>
                                    <option value="2">チーズケーキサンド</option>
                                    <option value="3">アンジェロチーズ</option>
                                    <option value="99">その他</option>
                                </select>
                            </div>

                            <div class="block">
                                <h3>キーワード</h3>
                                <input type="text" class="user-input" name="keyword" placeholder="例：北海道産">                        
                            </div>
                        </div>

                        <div class="form-block">
                            <div class="block">
                                <h3>サイズ(cm)</h3>
                                <div class="sub-block">
                                    <input type="text" class="user-input" name="size1" inputmode="numeric" placeholder="例：15" maxlength="3" >
                                    <p>✖</p>
                                    <input type="text" class="user-input" name="size2" inputmode="numeric" placeholder="例：15" maxlength="3" >
                                </div>
                            </div>
                        </div>
                        
                    </div>       

                    <div class="form-block">
                        <div class="block">
                            <h3>税率</h3>
                            <div class="sub-block">
                                <label class="user-radio"><input type="radio" value="0.1"  pattern="\d*" name="tax-rate" checked>10%</label>
                                <label class="user-radio"><input type="radio" value="0.08" pattern="\d*" name="tax-rate">8%</label>
                            </div>
                        </div>

                        <div class="block">
                            <div class="sub-block">
                                <div class="sub-block2">
                                    <h3>価格</h3>
                                    <input type="text" class="user-input" name="price" pattern="\d*" name="numeric">
                                </div>
                                
                                <div class="sub-block2">
                                    <h3>原価</h3>
                                    <input type="text" class="user-input" name="cost" pattern="\d*" name="numeric">
                                </div>
                            </div>
                        </div>

                        <div class="block">
                            <h3>税込み価格（自動計算）</h3>
                            <input type="text"   class="user-input hidden-input" value="¥0" readonly>
                            <input type="hidden" value="0" name="tax-included-price">
                        </div>
                    </div>

                    <div class="form-block">
                        <div class="block">
                            <h3>消費期限</h3>
                            <div class="sub-block">
                                <input type="text" class="user-input" name="expiration-date-min1" pattern="\d*" name="numeric" maxlength="3">
                                <p>〜</p>
                                <input type="text" class="user-input" name="expiration-date-max1" pattern="\d*" name="numeric" maxlength="3">
                                <p>日間</p>
                            </div>
                        </div>

                        <div class="block">
                            <h3>消費期限（解凍後）</h3>
                            <div class="sub-block">
                                <input type="text" class="user-input" name="expiration-date-min2" pattern="\d*" name="numeric" maxlength="3">
                                <p>〜</p>
                                <input type="text" class="user-input" name="expiration-date-max2" pattern="\d*" name="numeric" maxlength="3">
                                <p>日間</p>
                            </div>
                        </div>
                    </div>

                </div> <!-- ここまで -->
                <button class="submit-btn" type="submit">送信</button>
            </main>
        </div>
    </form>

    <script type="module" src="/../AngeloCheese/Admin/JS/itemAdd2.js"></script>
</body>
</html>