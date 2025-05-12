




/********** 商品ダブルクリックでダイアログ表示 **********/
const products = document.querySelectorAll('.product');
const dialog = document.querySelector('dialog');
const cancelBtn = document.querySelector('.cancel');
const confirmBtn = document.querySelector('.confirm');

let activeForm = null; // 現在のフォームを保持するフラグ

// ダイアログ表示（どの商品を編集するか記録する）
products.forEach(product => {
    product.addEventListener('dblclick', () => {
        setTimeout(() => { document.activeElement.blur(); }, 0);
        activeForm = product; // クリックしたフォームを記録
        dialog.showModal();
    });
});

// ダイアログを閉じる関数
const fncCloseDlg = () => {
    dialog.close();
    dialog.style.display = 'none';
    setTimeout(() => { dialog.style.removeProperty('display'); }, 0);
};

// 「キャンセル」ボタンで閉じる
cancelBtn.addEventListener('click', (e) => {
    e.preventDefault(); // デフォルトのボタン動作を防ぐ
    fncCloseDlg();
});

// 「はい」ボタンでフォーム送信
confirmBtn.addEventListener('click', (e) => {
    e.preventDefault(); // デフォルトのボタン動作を防ぐ
    if (activeForm) {
        activeForm.submit(); // 記録したフォームを送信
    }
});