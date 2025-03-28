/********** アカウント詳細画面 **********/
// ダイアログの開閉
const logoutBtn  = document.querySelector('.logout-button');
const logoutDlg  = document.querySelector('.logout-wrapper');
const confirmBtn = document.querySelector('.confirm');
const cancelBtn  = document.querySelector('.cancel');
const closeBtn   = document.querySelector('.close-btn');

logoutBtn.addEventListener('click', (e) => {
    e.preventDefault();
    console.log('here');
    logoutDlg.style.display = 'flex';
});

confirmBtn.addEventListener('click', () => {
    window.location.href = '../backend/logout.php';
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