




/********** ログイン・登録 **********/
const mailInputs = document.querySelectorAll('input[type="email"]');

mailInputs.forEach(mailInput =>{
    mailInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/[^a-zA-Z0-9@._-]/g, '');
    });
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





















