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
    <form method="POST" id="product-form" enctype="multipart/form-data">
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
                            <h3>サブ画像</h3>
                            <div class="sub-preview-container"></div>
                            <div class="drop-area sub-drop">画像をドラッグ&ドロップ、またはクリックで選択</div>
                            <input type="file" class="file-input sub-file" accept="image/*" name="images[]" multiple>
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

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const mainDrop = document.querySelector('.main-drop');
    const mainInput = document.querySelector('.main-file');
    const mainPreview = document.querySelector('.preview-container');

    const subDrop = document.querySelector('.sub-drop');
    const subInput = document.querySelector('.sub-file');
    const subPreview = document.querySelector('.sub-preview-container');

    const form = document.getElementById('product-form');

    let mainImage = null;
    let subImages = [];

    function setupDrop(dropArea, input, handleFile) {
        dropArea.addEventListener('click', () => input.click());

        ['dragenter', 'dragover'].forEach(event => {
            dropArea.addEventListener(event, (e) => {
                e.preventDefault();
                dropArea.classList.add('hover');
            });
        });

        ['dragleave', 'drop'].forEach(event => {
            dropArea.addEventListener(event, (e) => {
                e.preventDefault();
                dropArea.classList.remove('hover');
            });
        });

        dropArea.addEventListener('drop', (e) => {
            const files = Array.from(e.dataTransfer.files);
            handleFile(files);
        });

        input.addEventListener('change', () => {
            const files = Array.from(input.files);
            handleFile(files);
        });
    }

    function showMainPreview(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            mainPreview.innerHTML = `<img src="${e.target.result}">`;
            mainPreview.classList.add('show');
        };
        reader.readAsDataURL(file);
    }

    function showSubPreview(files) {
        subPreview.innerHTML = '';
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                subPreview.innerHTML = `<img src="${e.target.result}">`;
                subPreview.classList.add('show');
            };
            reader.readAsDataURL(file);
        });
    }

    setupDrop(mainDrop, mainInput, (files) => {
        if (files[0] && files[0].type.startsWith('image/')) {
            mainImage = files[0];
            showMainPreview(mainImage);
        }
    });

    setupDrop(subDrop, subInput, (files) => {
        subImages = subImages.concat(files.filter(file => file.type.startsWith('image/')));
        showSubPreview(subImages);
    });

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        if (!mainImage) {
            alert('メイン画像を選択してください');
            return;
        }

        const formData = new FormData(form);
        formData.set('image', mainImage);

        subImages.forEach((file, index) => {
            formData.append('images[]', file);
        });

        fetch('test3.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(html => {
            document.body.innerHTML = html;
        })
        .catch(err => {
            alert('送信に失敗しました');
            console.error(err);
        });
    });
});
</script>



</body>
</html>