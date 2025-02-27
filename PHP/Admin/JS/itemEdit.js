




/********** 列クリックで編集 **********/
const rows = document.querySelectorAll('.row');
const form = document.querySelector('.hidden-form');
const inputId = document.querySelector('.product-id');

rows.forEach(row => {
    row.addEventListener('click', function() {
        console.log('clicked');
        const productId = this.getAttribute('data-id');
        inputId.value = productId;
        form.submit();
    });
});