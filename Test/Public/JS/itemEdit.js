import { fncSetupDrop, fncShowMainPreview, fncShowSubPreview, fncSubmitImages, fncDeleteMainImg, fncDeleteSubImgs } from './function/functions.js';

document.addEventListener('DOMContentLoaded', () => {
    /******************** 列クリックで編集 ********************/
    const rows       = document.querySelectorAll('.row');

    rows.forEach(row => {
        row.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            if(productId) {
                window.location.href = `/Test/Public/product_edit.php?id=${productId}`;
            }
        });
    });

    /******************** 画像のDnD処理 ********************/
    const mainDrop       = document.querySelector('.main-drop');
    const mainInput      = document.querySelector('.main-file');
    const mainImgChanged = document.querySelector('.main-img-changed');
    const mainPreview    = document.querySelector('.preview-container');

    const subDrop           = document.querySelector('.sub-drop');
    const subInput          = document.querySelector('.sub-file');
    const subImgsChanged    = document.querySelectorAll('.sub-img-changed');
    const subPreviewWrapper = document.querySelector('.sub-preview-wrapper');
    const subPreviews       = subPreviewWrapper.querySelectorAll('.sub-preview-container');

    const form = document.querySelector('.product-form');

    let mainImage = mainPreview.querySelector('.main-img');
    let subImages = Array.from(
        subPreviewWrapper.querySelectorAll('.sub-img')
    );

    if (mainPreview && mainPreview.classList.contains('show')) {
        fncDeleteMainImg(mainPreview);
    }

    subPreviews.forEach(subPreview => {
        if (subPreview.classList.contains('show')) {
            fncDeleteSubImgs(subPreview);
        }
    });

    mainInput.addEventListener('change', () => {
        mainImgChanged.value = '1';
    });

    subInput.addEventListener('change', () => {
        subImgsChanged.forEach(input => {
            input.value  ='1';
        });
    });

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
        
        fncSubmitImages('./product_edit.php', form, mainImage, subImages);
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

    /******************** 画像変更時にフラグ ********************/
    // const mainImgValue = document.querySelector('input[name="mainImageChanged"]');

    // mainImgValue.addEventListener('changed', () => {
    //     console.log("CHANGED");
    // });
});