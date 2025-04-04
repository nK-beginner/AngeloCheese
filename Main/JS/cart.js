// 合計金額を更新
const fncUpdateTotalPrice = () => {
    let total = 0;
    document.querySelectorAll('.product').forEach(product => {
        const quantityInput = product.querySelector('.quantity');
        const priceElement  = product.querySelector('.price');
        const quantity      = parseInt(quantityInput.value) || 0;
        const price         = parseInt(priceElement.dataset.price) || 0;
        total += price * quantity;
    });
    const totalPrice = document.querySelector('.total-price span2');
    totalPrice.innerHTML = `<span>¥</span>${total.toLocaleString()}`;
};

document.querySelectorAll('.quantity-container').forEach(container => {
    const plus           = container.querySelector('.plus');
    const minus          = container.querySelector('.minus');
    const quantity       = container.querySelector('.quantity');
    const hiddenQuantity = container.querySelector('.hidden-quantity');
    const product        = container.closest('.product');
    const trashBin       = product.querySelector('.trash-bin');
    const productId      = quantity.dataset.id;
    
    // AJAX処理：商品個数に応じて金額を自動計算
    const updateCart = (newQuantity) => {
        fetch('../php/updateCart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${productId}&quantity=${newQuantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (newQuantity === 0) {
                    // 数量が0なら商品をカートから削除
                    product.remove();
                    if (document.querySelectorAll('.product').length === 0) {
                        location.reload();
                    }

                } else {
                    // 数量を更新
                    quantity.value = newQuantity;
                    hiddenQuantity.value = newQuantity;
                }
                // 合計金額更新
                if (data.total_price !== undefined) {
                    document.querySelector('.total-price span2').innerHTML = `<span>¥</span>${data.total_price.toLocaleString()}`;
                }
                
            } else {
                alert('カートの更新に失敗しました');
            }
        })
        .catch(error => console.error('Error:', error));
    };

    plus.addEventListener('click', () => {
        let currentValue = parseInt(quantity.value) || 0;
        if (currentValue < 99) {
            updateCart(currentValue + 1);
        }
    });

    minus.addEventListener('click', () => {
        let currentValue = parseInt(quantity.value) || 0;
        if (currentValue > 0) {
            updateCart(currentValue - 1);
        }
    });

    trashBin.addEventListener('click', () => {
        updateCart(0);
    });
});

fncUpdateTotalPrice();

document.querySelector('form[action="cart.php"]').addEventListener('submit', () => {
    document.querySelectorAll('.hidden-quantity').forEach(hiddenInput => {
        const quantityInput = hiddenInput.closest('.quantity-container').querySelector('.quantity');
        hiddenInput.value = quantityInput.value;
    });
});