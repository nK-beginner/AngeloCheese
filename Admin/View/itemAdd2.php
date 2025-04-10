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
                        <div class="block file-upload">
                            <h3>メイン画像</h3>
                            <label for="thumbnail" id="drop-area" class="file-upload-label">
                                画像をドラッグ＆ドロップ または クリックして選択
                            </label>
                            <input type="file" class="file-input" name="thumbnail" id="thumbnail">
                            <div class="preview-container" id="preview-container">
                                <p>画像プレビュー</p>
                            </div>
                        </div>

                        <!-- <div class="block">
                            <h3>サブ画像</h3>
                            <div class="sub-block">
                                <input type="file" name="file1">
                                <input type="file" name="file2">
                                <input type="file" name="file3">
                                <input type="file" name="file4">
                                <input type="file" name="file5">
                            </div>
                        </div> -->
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
// DnDイベントの処理
const dropArea = document.getElementById('drop-area');
const fileInput = document.getElementById('thumbnail');
const previewContainer = document.getElementById('preview-container');

// 変数で選択されたファイルを保持
let selectedFile = null;

// ドラッグオーバーイベント
dropArea.addEventListener('dragover', function(event) {
    event.preventDefault();  // ブラウザのデフォルト動作を無効化
    dropArea.classList.add('dragover');  // ドラッグ中のスタイル変更
});

// ドロップイベント
dropArea.addEventListener('drop', function(event) {
    event.preventDefault();  // デフォルト動作無効化
    dropArea.classList.remove('dragover');  // スタイルを戻す

    const files = event.dataTransfer.files;  // ドロップされたファイルを取得
    if (files.length > 0) {
        selectedFile = files[0];  // 選択されたファイルを保持
        displayPreview(selectedFile);  // 画像プレビューを表示
        fileInput.files = files;  // input[type="file"] にファイルを設定
    }
});

// input[type="file"] で選択された場合の処理
fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        selectedFile = file;  // 選択されたファイルを保持
        displayPreview(file);  // 画像プレビューを表示
    }
});

// 画像プレビューの表示
function displayPreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const imgElement = document.createElement('img');
        imgElement.src = e.target.result;  // ファイルのデータを画像に設定
        previewContainer.innerHTML = '';  // 既存の内容をクリア
        previewContainer.appendChild(imgElement);  // 新しい画像を追加
    }
    reader.readAsDataURL(file);  // ファイルを読み込む
}

// 画像が選択された後にダイアログを再度開いたときに、選択した画像を保持する方法
function resetFileInput() {
    fileInput.value = ''; // ファイル選択をリセットすることで再度選択可能に
}

// リセット後に再度画像を表示
fileInput.addEventListener('click', function() {
    setTimeout(resetFileInput, 100);
});
</script>
</body>
</html>