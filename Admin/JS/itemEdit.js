




/********** 列クリックで編集 **********/
const rows = document.querySelectorAll('.row');
const form = document.querySelector('.hidden-form');
const inputId = document.querySelector('.product-id');

rows.forEach(row => {
    row.addEventListener('click', function() {
        console.log('clicked');
        const productId = this.getAttribute('data-id');
        inputId.value = productId;
        form.submit();
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
    const price   = parseFloat(document.getElementById('price').value) || 0;
    const taxRate = parseFloat(document.querySelector('input[name="tax-rate"]:checked').value) || 0.1;

    // 税込み価格を計算
    const taxIncludedPrice = (price * (1 + taxRate)).toFixed(0);

    // 表示用フィールドの更新
    document.getElementById('tax-included-price-show').value = `¥${parseInt(taxIncludedPrice).toLocaleString()}`;

    // 送信用 hidden フィールドの更新
    document.getElementById('tax-included-price-hidden').value = taxIncludedPrice;
};

// イベントリスナー追加
document.getElementById('price').addEventListener('input', calculateTaxIncludedPrice);
document.querySelectorAll('input[name="tax-rate"]').forEach(radio => {
    radio.addEventListener('change', calculateTaxIncludedPrice);
});
document.addEventListener('DOMContentLoaded', calculateTaxIncludedPrice);















/********** 画像ファイルのドラッグ＆ドロップ **********/
const dropAreas = document.querySelectorAll('.drop-area');
let lastFile = null; // 直前に選択された画像を保持

const addDragOverStyle = (e) => {
    e.preventDefault();
    e.currentTarget.classList.add('drag-over'); // ドラッグ中のスタイル
};

const removeDragOverStyle = (e) => {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over'); // ドラッグ終了時のスタイルをリセット
};

// 画像を表示 & `<input type="file">` にセット
const displayImage = (file, dropArea) => {
    if (file.type.startsWith('image/')) {
        lastFile = file; // 最後に選択したファイルを保存
        const reader = new FileReader();

        reader.onload = () => {
            let existingContainer = dropArea.querySelector('.image-container');
            if (existingContainer) {
                existingContainer.remove();
            }

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
                dropArea.querySelector('.file-input').value = '';
                // `lastFile` を保持して、キャンセル後も再送信できるようにする
            });

            container.appendChild(img);
            container.appendChild(removeButton);
            dropArea.appendChild(container);
        };

        reader.readAsDataURL(file);

        // `hidden` の `<input type="file">` にファイルをセット
        const fileInput = dropArea.querySelector('.file-input');
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
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

    if (files.length > 0) {
        displayImage(files[0], dropArea);
    }
};

// ファイル選択時の処理
const handleFileSelect = (e) => {
    e.preventDefault();
    const input = e.currentTarget;
    const dropArea = input.closest('.drop-area');

    if (input.files.length > 0) {
        displayImage(input.files[0], dropArea);

    } else if (lastFile) {
        // `lastFile` がある場合、削除後でも再設定できるようにする
        displayImage(lastFile, dropArea);
    }
};

// 各ドロップエリアにイベントを設定
for (const dropArea of dropAreas) {
    dropArea.addEventListener('dragover', addDragOverStyle);
    dropArea.addEventListener('dragleave', removeDragOverStyle);
    dropArea.addEventListener('drop', handleDrop); 
    // ここまで：現時点だと画像フォルダ単体でドラドロした時は行けるけど、
    // 「ファイルを選択ボタン」で開いた画像フォルダからドラドロするとバグる（1枚目の画像が最後に切り替わる）

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