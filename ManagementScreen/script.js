




/********** サイドバーの項目開閉 **********/
const items = document.querySelectorAll('.sidebar-contents .item');

items.forEach(item => {
    item.addEventListener('click', () => {
        // アコーディオン項目の開閉
        const subMenu = item.querySelector('.sidebar-contents .submenu');
        subMenu.classList.toggle('toggle');

        // caretアイコンの回転
        const caretIcon = item.querySelector('.sidebar-contents .fa-solid');
        caretIcon.classList.toggle('toggle');

        // submenu内のli要素を押したときに親のitemが閉じないようにする
        subMenu.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });
});



/********** 数字のみ入力許可 **********/
const numInputs = document.querySelectorAll('input[inputmode="numeric"]');

numInputs.forEach(numInput => {
    numInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });
});



/********** 税込み価格自動計算 **********/
const calculateTaxIncludedPrice = () => {
    const price   = parseFloat(document.querySelector('.price').value) || 0;
    const taxRate = parseFloat(document.querySelector('input[name="tax-rate"]:checked').value) || 0.1;

    // 税込み価格を計算してフォーマット
    const taxIncludedPrice = (price * (1 + taxRate)).toFixed(0);
    document.querySelector('.tax-included-price').value = `¥${parseFloat(taxIncludedPrice).toLocaleString()}`;
};

// 値段欄と税率選択時に自動計算
document.querySelector('.price').addEventListener('input', calculateTaxIncludedPrice);
document.querySelectorAll('input[name="tax-rate"]').forEach(radio => {
    radio.addEventListener('change', calculateTaxIncludedPrice);
});




/********** 画像ファイルのドラッグ＆ドロップ **********/
// ドラッグアンドドロップ処理
const dropAreas = document.querySelectorAll('.drop-area');

// ドラッグ中のスタイルを適用
const addDragOverStyle = (e) => {
    e.preventDefault();
    e.currentTarget.classList.add('drag-over');
};

// ドラッグ終了時のスタイルをリセット
const removeDragOverStyle = (e) => {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
};

// 画像を表示
const displayImage = (file, dropArea) => {
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();

        reader.onload = () => {
            // 既存の画像コンテナがある場合は削除
            let existingContainer = dropArea.querySelector('.image-container');
            if (existingContainer) {
                existingContainer.remove();
            }

            // 新しい画像コンテナを作成
            const container = document.createElement('div');
            container.className = 'image-container';

            const img = document.createElement('img');
            img.src = reader.result;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';

            const removeButton = document.createElement('button');
            removeButton.textContent = '✖';
            removeButton.className = 'remove-button';
            removeButton.addEventListener('click', () => {
                container.remove();
            });

            container.appendChild(img);
            container.appendChild(removeButton);

            // ドロップエリアに画像コンテナを追加
            dropArea.appendChild(container);
        };

        reader.readAsDataURL(file);
        
    } else {
        alert('画像ファイルを選択してください。');
    }
};

// ファイルがドロップされたときの処理
const handleDrop = (e) => {
    e.preventDefault();
    const dropArea = e.currentTarget;
    dropArea.classList.remove('drag-over');

    const files = e.dataTransfer.files;
    
    // 複数ファイルがドロップされたら1枚目を挿入
    if (files.length > 0) {
        displayImage(files[0], dropArea);
    }
};

// ファイル選択時の処理
const handleFileSelect = (e) => {
    const input = e.target;
    const dropArea = input.closest('.drop-area');

    if (input.files.length > 0) {
        displayImage(input.files[0], dropArea);
    }
};

// 各ドロップエリアにイベントを設定
for (const dropArea of dropAreas) {
    dropArea.addEventListener('dragover', addDragOverStyle);
    dropArea.addEventListener('dragleave', removeDragOverStyle);
    dropArea.addEventListener('drop', handleDrop);

    // ファイル選択ボタンのイベント設定
    const fileInput = dropArea.querySelector('.file-input');
    if (fileInput) {
        fileInput.addEventListener('change', handleFileSelect);
    }
}




















/********** 使ったメソッド **********/
// stopPropagation()：親要素へのイベント伝播を防ぐ
// 
// parseFloat()：浮動小数点を返す
// 
// toFixed()：指定の桁数で四捨五入
// 
// toLocaleString()：カンマを入れる
// 
// preventDefault()：ブラウザでのドロップイベント（他のタブで開くの）を防ぐ
// 
// startsWith()：文字列が引数で指定された文字列で始まるかを判定
// 
// createElement()：要素を作成する
// 
// appendChild()：特定の親要素の中に要素を追加する
// 
// readAsDataURL()：指定された Blob または File の内容を読み込む
// 
// displayImage()：画像を表示
// 