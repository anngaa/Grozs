jQuery(document).ready(function($) {
	
    // =========================
    // === GROZA PĀRVALDĪBA ====
    // =========================

    
    function renderCartItems() {
        $('.cart-empty').remove();
        var cartData = JSON.parse(localStorage.getItem('grozs_cart')) || [];
        var $cartContainer = $('#grozs-cart-items');
        var $totalContainer = $('#grozs-cart-total');
        $cartContainer.empty();
        $totalContainer.empty();

        // ✅ Atjauno groza skaitu visos layoutos
        updateCartIconCount();

        if (cartData.length === 0) {
            $cartContainer.append('<p class="cart-empty">Grozs ir tukšs.</p>');
            return;
        }

        let totalSum = 0;

        cartData.forEach(function(item, index) {
            const quantity = item.quantity || 1;
            const price = parseFloat(item.price || 0);
            const subtotal = quantity * price;
            totalSum += subtotal;

            var productHTML = ''
              + '<div class="grozs-cart-item" data-index="' + index + '">'
              +   '<div class="grozs-cart-image">'
              +     '<a href="' + item.link + '">'
              +       '<img src="' + item.image + '" alt="' + item.title + '" />'
              +     '</a>'
              +   '</div>'
              +   '<div class="grozs-cart-details">'
              +     '<div class="grozs-cart-title">'
              +       '<a href="' + item.link + '"><strong>' + item.title + '</strong></a>'
              +     '</div>'
              +     '<div class="grozs-cart-price">€ ' + item.price + (item.quantity && item.quantity > 1 ? ' <strong>× ' + item.quantity : '') + '</strong></div>'
              +   '</div>'
              +   '<div class="grozs-cart-remove">'
              +     '<button class="grozs-remove-item" aria-label="Noņemt produktu">&#10005;</button>'
              +   '</div>'
              + '</div>';
            $cartContainer.append(productHTML);
        });

        // ✅ Pievieno kopējo summu apakšā
        $totalContainer.html('<strong>Kopā:</strong> <span class="total-price">€ ' + totalSum.toFixed(2) + '</span>');

        $('.grozs-remove-item').on('click', function() {
            var index = $(this).closest('.grozs-cart-item').data('index');
            cartData.splice(index, 1);
            localStorage.setItem('grozs_cart', JSON.stringify(cartData));
            renderCartItems();
            updateCartIconCount()
        });
    }
	
	// =========================
    // === INIT =================
    // =========================

    initCalculator();
    renderCartItems();
    updateCartIconCount()

    // ✅ Pievieno resize handleri, lai pār-renderē groza skaitu
    $(window).on('resize', function () {
        clearTimeout(window._grozsResizeTimeout);
        window._grozsResizeTimeout = setTimeout(renderCartItems, 200);
    });

    $('.open-grozs-button').on('click', renderCartItems);

    setTimeout(initCalculator, 500);
	
});