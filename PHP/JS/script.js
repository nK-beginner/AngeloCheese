




/********** ログイン・登録 **********/
const mailInputs = document.querySelectorAll('input[type="email"]');

mailInputs.forEach(mailInput =>{
    mailInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/[^a-zA-Z0-9@._-]/g, '');
    });
});


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

/********** アカウント詳細画面 **********/
// ダイアログの開閉
const logoutBtn  = document.querySelector('.logout-button');
const logoutDlg  = document.querySelector('.logout-wrapper');
const confirmBtn = document.querySelector('.confirm');
const cancelBtn  = document.querySelector('.cancel');
const closeBtn   = document.querySelector('.close-btn');

logoutBtn.addEventListener('click', (e) => {
    e.preventDefault();
    logoutDlg.style.display = 'flex';
});

confirmBtn.addEventListener('click', () => {
    window.location.href = 'logout.php';
});

cancelBtn.addEventListener('click', () => {
    logoutDlg.style.display = 'none';
});

logoutDlg.addEventListener('click', (e) => {
    if(e.target === logoutDlg) {
        logoutDlg.style.display = 'none';
    }
});

closeBtn.addEventListener('click', () => {
    logoutDlg.style.display = 'none';
});




/********** コンタクト⇐ミス！本当はこれ退会フォーム **********/
// その他が選択された時のみテキストエリアを有効化
// const otherBtn = document.querySelector('#other');
// const textArea = document.querySelector('#reason');
// const btns = document.querySelectorAll('.radioBtn');

// otherBtn.addEventListener('change', () => {
//     if(otherBtn.checked) {
//         textArea.disabled = false;
//         textArea.placeholder = 'ご記入ください。';
//     }
// });

// btns.forEach(btn => {
//     btn.addEventListener('change', () => {
//         if(!otherBtn.checked) {
//             textArea.disabled = true;
//             textArea.placeholder = '';
//         }
//     });
// });





















