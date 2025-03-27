const plus     = document.querySelector('.plus');
const minus    = document.querySelector('.minus');
const quantity = document.querySelector('.quantity');
const hiddenQuantity = document.querySelector('.hidden-quantity');

hiddenQuantity.value = 1;

plus.addEventListener('click', (e) => {
    let currentValue = parseInt(quantity.value) || 0;
    if (currentValue < 99) {
        quantity.value = currentValue + 1;
        hiddenQuantity.value = quantity.value;
    }
});

minus.addEventListener('click', (e) => {
    let currentValue = parseInt(quantity.value) || 0;
    if (currentValue > 0) {
        quantity.value = currentValue - 1;
        hiddenQuantity.value = quantity.value;
    }
});