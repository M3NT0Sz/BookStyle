// bookShow.js

document.addEventListener('DOMContentLoaded', function() {
    // Efeito de foco no campo de cupom
    const couponInput = document.querySelector('.add-cart-form input[name="coupon_code"]');
    if (couponInput) {
        couponInput.addEventListener('focus', function() {
            this.style.backgroundColor = '#f0fff4';
        });
        couponInput.addEventListener('blur', function() {
            this.style.backgroundColor = '';
        });
    }

    // Animação ao adicionar ao carrinho
    const addCartBtn = document.querySelector('.add-cart-form button');
    if (addCartBtn) {
        addCartBtn.addEventListener('click', function() {
            addCartBtn.classList.add('clicked');
            setTimeout(() => addCartBtn.classList.remove('clicked'), 300);
        });
    }
});
