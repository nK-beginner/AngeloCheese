




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

    // 税込み価格を計算
    const taxIncludedPrice = (price * (1 + taxRate)).toFixed(0);

    // 表示用フィールドの更新
    document.querySelector('.tax-included-price').value = `¥${parseInt(taxIncludedPrice).toLocaleString()}`;

    // 送信用 hidden フィールドの更新
    document.querySelector('#tax-included-price').value = taxIncludedPrice;
};

// イベントリスナー追加
document.querySelector('.price').addEventListener('input', calculateTaxIncludedPrice);
document.querySelectorAll('input[name="tax-rate"]').forEach(radio => {
    radio.addEventListener('change', calculateTaxIncludedPrice);
});
















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