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
    <form action="#" method="POST" enctype="multipart/form-data">
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
                            <label for="thumbnail" class="drop-area">画像をドラッグ＆ドロップ または クリックして選択</label>
                            <input type="file"     class="thumbnail" name="thumbnail" id="thumbnail">
                        </div>

                        <div class="block">
                            <h3>サブ画像</h3>
                            <div class="sub-image-container"></div>
                            <label for="sub-pics" class="drop-area">ドラッグアンドドロップ</label>
                            <input type="file"    class="sub-pics" name="sub-pics[]" id="sub-pics" multiple>
                        </div>
                    </div>

                    <div class="form-block">
                        <div class="block">
                            <h3>商品名</h3>
                            <input type="text" class="user-input" name="name" placeholder="例：チーズケーキサンド" required>
                        </div>

                        <div class="block">
                            <h3>商品説明</h3>
                            <textarea class="user-input" name="description" required></textarea>
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
                                    <input type="text" class="user-input" name="size1" inputmode="numeric" placeholder="例：15" maxlength="3" required>
                                    <p>✖</p>
                                    <input type="text" class="user-input" name="size2" inputmode="numeric" placeholder="例：15" maxlength="3" required>
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
                </div>
            </main>
        </div>
    </form>

    <script>
        const dropArea         = document.querySelector(".drop-area");
        const fileInput        = document.querySelector(".thumbnail");
        const previewContainer = document.querySelector(".preview-container");

        let lastSelectedFile = null;

        // ドラッグ処理
        dropArea.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropArea.classList.add("hover");
        });

        dropArea.addEventListener("dragleave", () => {
            dropArea.classList.remove("hover");
        });

        dropArea.addEventListener("drop", (e) => {
            e.preventDefault();
            dropArea.classList.remove("hover");
            previewContainer.classList.add("show");
            const file = e.dataTransfer.files[0];
            if (file) {
                handleFile(file);
            }
        });

        // input経由の選択
        fileInput.addEventListener("change", () => {
            if (fileInput.files.length > 0) {
                handleFile(fileInput.files[0]);

            } else if (lastSelectedFile) {
                // キャンセルされたら前のファイルを再設定
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(lastSelectedFile);
                fileInput.files = dataTransfer.files;
            }
        });






        const handleFile = (file) => {
            if (!file || !file.type.startsWith("image/")) return;

            lastSelectedFile = file;

            const reader = new FileReader();
            reader.onload = (e) => {
                // 既存のプレビュー画像をクリア
                previewContainer.querySelectorAll("img").forEach(img => img.remove());

                const img = document.createElement("img");
                img.src = e.target.result;
                previewContainer.appendChild(img);
            };

            reader.readAsDataURL(file);

            // inputに強制的に再設定
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
        }
    </script>
</body>
</html>