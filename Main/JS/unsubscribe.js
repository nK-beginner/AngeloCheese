// textareaの有効化・無効化
const btns     = document.querySelectorAll('.reason-btn');
const otherBtn = document.getElementById('other');
const textArea = document.getElementById('reasonDetail');

btns.forEach(btn => {
    btn.addEventListener('change', () => {
        if(!otherBtn.checked) {
            textArea.style.display = "none";
            textArea.required = false;
        }
    });
});

otherBtn.addEventListener('change', () => {
    textArea.style.display = "block";
    textArea.required = true;
});