jQuery(function($) {
    window.updateCartIconCount = function() {
        const cart = JSON.parse(localStorage.getItem('grozs_cart')) || [];
        const totalQuantity = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);

        $('.grozs-cart-all-count').each(function () {
            if (totalQuantity > 0) {
                $(this).text(totalQuantity).show();
            } else {
                $(this).hide();
            }
        });
    };

    // Initialize icon count on load
    updateCartIconCount();
});
