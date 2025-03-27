/********** レビュー画面 **********/
// 星数
const stars = document.querySelectorAll('.star');

stars.forEach(star => {
    star.addEventListener('click', () => {
        const value = star.getAttribute('data-value');

        stars.forEach(s => {
           s.textContent = "☆" 
           s.classList.remove('selected');
        });

        for(let i = 0; i < value; i++) {
            stars[i].textContent = "★";
            stars[i].classList.add('selected');
        }
    });
});

// ラジオボタン未選択時アラート
const form = document.querySelector('.form');

form.addEventListener('submit', (e) => {
    const radioBtn = document.querySelector('.policy-radio');

    if(!radioBtn.checked) {
        e.preventDefault();
        alert('個人情報ポリシーに同意してください。');
        radioBtn.focus();
    }
});