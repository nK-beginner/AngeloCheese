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
    <form id="product-form" enctype="multipart/form-data">
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
                            <div class="drop-area">画像をドラッグ&ドロップ、またはクリックで選択</div>
                            <input type="file" class="file-input" accept="image/*" name="image">
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
                </div>
                <button class="submit-btn" type="submit">送信</button>
            </main>
        </div>
    </form>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const dropArea = document.querySelector('.drop-area');
    const fileInput = document.querySelector('.file-input');
    const previewContainer = document.querySelector('.preview-container');
    const form = document.getElementById('product-form');

    let selectedFile = null;

    dropArea.addEventListener('click', () => fileInput.click());

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
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            selectedFile = file;
            showPreview(file);
        }
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file && file.type.startsWith('image/')) {
            selectedFile = file;
            showPreview(file);
        }
    });

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewContainer.innerHTML = `<img src="${e.target.result}">`;
            previewContainer.classList.add('show');
        };
        reader.readAsDataURL(file);
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        if (!selectedFile) {
            alert('画像を選択してください');
            return;
        }

        const formData = new FormData(form);
        formData.set('image', selectedFile); // 上書きして安全に送信

        fetch('test3.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(html => {
            document.body.innerHTML = html; // test2.phpの結果を表示
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