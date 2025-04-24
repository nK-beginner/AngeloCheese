import { fncSetupDrop, fncShowMainPreview, fncShowSubPreview, fncSubmitImages } from './function/functions.js';

document.addEventListener('DOMContentLoaded', () => {
    /******************** 画像のDnD処理 ********************/
    const mainDrop    = document.querySelector('.main-drop');
    const mainInput   = document.querySelector('.main-file');
    const mainPreview = document.querySelector('.preview-container');

    const subDrop     = document.querySelector('.sub-drop');
    const subInput    = document.querySelector('.sub-file');
    const subPreviewWrapper = document.querySelector('.sub-preview-wrapper');

    const form = document.querySelector('.product-form');

    let mainImage = null;
    let subImages = [];

    fncSetupDrop(mainDrop, mainInput, (files) => {
        if (files[0] && files[0].type.startsWith('image/')) {
            mainImage = files[0];
            fncShowMainPreview(mainImage, mainPreview);
        }
    });

    fncSetupDrop(subDrop, subInput, (files) => {
        const validFiles = files.filter(file => file.type.startsWith('image/'));
        subImages = subImages.concat(validFiles);
        fncShowSubPreview(validFiles, subPreviewWrapper);
    });

    form.addEventListener('submit', (e) => {
        e.preventDefault();
	    
        if (!mainImage) {
            alert('メイン画像を選択してください');
            return;
        }

        // fncSubmitImages('test3.php', form, mainImage, subImages);
        fncSubmitImages('/AngeloCheese/Admin/PHP/itemAdd.php', form, mainImage, subImages);
    });


    /******************** 数字のみ許容 ********************/
    const numInputs = document.querySelectorAll('input[inputmode="numeric"]');

    numInputs.forEach(numInput => {
        numInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    });

    /******************** 税込み価格自動計算 ********************/
    const priceInput         = document.querySelector('input[name="price"]');
    const taxRateInputs      = document.querySelectorAll('input[name="tax-rate"]');
    const taxIncludedDisplay = document.getElementById('tax-included-price-display');
    const taxIncludedHidden  = document.getElementById('tax-included-price-hidden');

    function fncCalcTaxIncludedPrice() {
        const priceStr        = priceInput.value.replace(/,/g, '');
        const price           = parseFloat(priceStr) || 0;
        const selectedTaxRate = parseFloat(document.querySelector('input[name="tax-rate"]:checked').value);
        const taxIncluded     = Math.floor(price * (1 + selectedTaxRate));

        taxIncludedDisplay.value = `¥${taxIncluded.toLocaleString()}`;
        taxIncludedHidden.value  = taxIncluded;
    }

    priceInput.addEventListener('input', fncCalcTaxIncludedPrice);
    taxRateInputs.forEach(input => {
        input.addEventListener('change', fncCalcTaxIncludedPrice);
    });

    fncCalcTaxIncludedPrice();
});