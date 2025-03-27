




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