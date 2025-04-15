import { fncSetupDrop, fncShowMainPreview, fncShowSubPreview, fncSubmitImages } from './function/functions.js';

document.addEventListener('DOMContentLoaded', () => {
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
        fncSubmitImages('/../AngeloCheese/Admin/PHP/itemAdd2.php', form, mainImage, subImages);
    });
});
