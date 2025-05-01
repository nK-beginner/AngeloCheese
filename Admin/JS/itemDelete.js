document.addEventListener('DOMContentLoaded', () => {
    /********** 行クリックでチェック付け **********/
    const rows  = document.querySelectorAll('.clickable-row');
    rows.forEach(row => {
        row.addEventListener('click', (e) => {
            const checkBox = row.querySelector('input[type="checkbox"]');

            if(checkBox.disabled) {
                return;
            }

            if (e.target !== checkBox) {
                checkBox.checked = !checkBox.checked;
            }
        });
    });

    /********** 削除制御 **********/
    const deleteBtn  = document.querySelector('.delete-btn');
    const checkBoxes = document.querySelectorAll('.check-boxes');
    deleteBtn.addEventListener('click', (e) => {
        const isChecked = Array.from(checkBoxes).some(checkBox => checkBox.checked);
        if(!isChecked) {
            // チェックがついていない時の制御
            e.preventDefault();
            alert('商品が選択されていません');

        } else {
            // 削除確認
            const confirmDelete = confirm('商品を画面から非表示します。よろしいですか？');
            if(!confirmDelete) {
                e.preventDefault();
            }
        }
    });
});

