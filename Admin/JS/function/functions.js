/*======================================================*/
/* 用途：画像のセット                       			  */
/* 引数：DnDエリア、インプット、画像ファイル                */
/* 戻り値：なし											 */
/* 備考：なし											 */
/*======================================================*/
export function fncSetupDrop(dropArea, input, handleFile) {
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

/*======================================================*/
/* 用途：メイン画像の表示                    			  */
/* 引数：画像ファイル、プレビューエリア                    */
/* 戻り値：なし											 */
/* 備考：なし											 */
/*======================================================*/
export function fncShowMainPreview(file, mainPreview) {
    const reader = new FileReader();
    reader.onload = (e) => {
        mainPreview.innerHTML = 
            `<img src="${e.target.result}">
             <div class="delete-main-img">✖</div>`;
        mainPreview.classList.add('show');

        const deleteBtn = document.querySelector('.delete-main-img');
        deleteBtn.addEventListener('click', () => {
            mainPreview.innerHTML = '';
            mainPreview.classList.remove('show');
        });
    };
    reader.readAsDataURL(file);
}

/*======================================================*/
/* 用途：サブ画像の表示                     			  */
/* 引数：画像ファイル、プレビューラッパー                   */
/* 戻り値：なし											 */
/* 備考：なし											 */
/*======================================================*/
export function fncShowSubPreview(files, subPreviewWrapper) {
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const container = document.createElement('div');
            container.classList.add('sub-preview-container', 'show');

            const img = document.createElement('img');
            img.src = e.target.result;

            const deleteBtn = document.createElement('div');
            deleteBtn.textContent = '✖'
            deleteBtn.classList.add('delete-btn');

            deleteBtn.addEventListener('click', () => {
                container.remove();
            });

            subPreviewWrapper.appendChild(container);
            container.appendChild(img);
            container.appendChild(deleteBtn);
        };
        reader.readAsDataURL(file);
    });
}

/*======================================================*/
/* 用途：画像投稿用のAJAX                     			  */
/* 引数：url, form, mainImage, subImages                 */
/* 戻り値：なし											 */
/* 備考：なし											 */
/*======================================================*/
export function fncSubmitImages(url, form, mainImage, subImages) {
    const formData = new FormData(form);
    formData.set('image', mainImage);

    subImages.forEach(file => {
        formData.append('images[]', file);
    });

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    // .then(html => { document.body.innerHTML = html; })
    .then(response => { window.location.href = response; })
    .catch(err => {
        alert('送信に失敗しました');
        console.error(err);
    });
}