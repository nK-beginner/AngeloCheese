




/********** 商品ダブルクリックでダイアログ表示 **********/
const products = document.querySelectorAll('.product');
const dialog = document.querySelector('dialog');
const cancelBtn = document.querySelector('.cancel');
const confirmBtn = document.querySelector('.confirm');

// ダイアログ表示
products.forEach(product => {
    product.addEventListener('dblclick', () => {
        setTimeout(() => { document.activeElement.blur(); }, 0);
        dialog.showModal();
    });

    // 「はい」クリックでitemEdit.phpへ
    // confirmBtn.addEventListener('click', () => {
    //     const productId = product.getAttribute('data-id');
    //     if (productId) {
    //         window.location.href = `itemEdit.php?id=${productId}`;
    //     }
    // });
});

// ダイアログを閉じるファンクション
const fncCloseDlg = () => {
    dialog.close();
    dialog.style.display = 'none';
    setTimeout(() => { dialog.style.removeProperty('display'); }, 0);
}

cancelBtn.addEventListener('click', () => {
    fncCloseDlg();
});

dialog.addEventListener('click', (e) => {
    if(e.target === dialog) {
        fncCloseDlg();
    }
});



