jQuery(document).ready(function ($) {

    
    function renderCheckoutSummary() {
        let cart = JSON.parse(localStorage.getItem('grozs_cart')) || [];
        const $summary = $('#grozs-order-summary');
        const $total = $('#grozs-total-sum');
        $summary.empty();

        let sum = 0;

        if (cart.length === 0) {
            $summary.html('<p class="grozs-cart-empty">Neviens produkts vēl nav pievienots.</p>');
            $total.text('0.00');
            $('#grozs-checkout-order-form-wrapper').hide();
            $('#grozs-empty-wrapper').show();
            return;
        }

        cart.forEach(function (item) {
            const quantity = item.quantity || 1;
            const unitPrice = parseFloat(item.price) || 0;
            const itemTotal = unitPrice * quantity;
            sum += itemTotal;

            let html = '<div class="checkout-item">';
            if (item.image) {
                html += '<div class="checkout-item-img"><a href="' + (item.link || '#') + '"><img src="' + item.image + '"></a></div>';
            }

            html += '<div class="checkout-item-title-wrapper">';
            html += '<div class="checkout-item-title"><strong><a href="' + (item.link || '#') + '">' + item.title + '</a></strong></div>';
            html += '<div class="checkout-item-price">€ ' + unitPrice.toFixed(2) + (quantity > 1 ? ' <strong>× ' + quantity : '') + '</strong></div>';
            html += '</div>';

            html += '<div class="checkout-item-options">';
            if (item.krasa) html += '<span class="checkout-item-krasa"><small>' + item.krasa + ' | </small></span>';
            if (item.izmers) html += '<span class="checkout-item-matracis"><small>' + item.izmers + ' | </small></span>';
            if (item.produkta_izmers) html += '<span class="checkout-item-izmers"><small>' + item.produkta_izmers + ' | </small></span>';
            if (item.materials) html += '<span class="checkout-item-materials"><small>' + item.materials + ' | </small></span>';
            if (item.atvilknes === 'Vēlos') html += '<span class="checkout-item-atvilknes"><small>Atvilknes zem gultas | </small></span>';
            if (item.pacelams === 'Vēlos') html += '<span class="checkout-item-pacelams"><small>Paceļams matracis</small></span>';
            html += '</div>';

            html += '<div class="grozs-checkout-item-remove"><button class="grozs-remove-checkout-item" aria-label="Noņemt produktu">&#10005;</button></div>';
            html += '</div>';

            $summary.append(html);
        });

        $total.text(sum.toFixed(2));
        $('#grozs-checkout-order-form-wrapper').show();
        $('#grozs-empty-wrapper').hide();
    }

    // ✅ Produkta noņemšanas poga
    $(document).on('click', '.grozs-remove-checkout-item', function () {
        let cart = JSON.parse(localStorage.getItem('grozs_cart')) || [];
        const index = $(this).closest('.checkout-item').index();

        if (index > -1 && index < cart.length) {
            cart.splice(index, 1);
            localStorage.setItem('grozs_cart', JSON.stringify(cart));
        }

        renderCheckoutSummary(); // pārzīmē kopsavilkumu
        if (typeof renderCartPreview === 'function') renderCartPreview(); // pārzīmē galvas kartīti, ja tāda ir
        updateCartIconCount();
    });

    $('#grozs-order-form').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serializeArray();
        const cart = JSON.parse(localStorage.getItem('grozs_cart')) || [];

        if (cart.length === 0) {
            $('.grozs-form-response').html('<p style="color:red;">Lai varētu veikt pasūtījumu, ir jāpievieno kāds produkts.</p>');
            return;
        }

        $.ajax({
            method: 'POST',
            url: grozs_ajax.ajax_url,
            data: {
                action: 'submit_grozs_order',
                nonce: grozs_ajax.nonce,
                form: formData,
                cart: cart
            },
            success: function (res) {
                if (res.success) {
                    $('.grozs-form-response').html('<p style="color:green;">Paldies par Jūsu pasūtījumu! Mēs ar Jums sazināsimies, tiklīdz būsim apstrādājuši pasūtījumu.</p>');
                    $('#grozs-order-form')[0].reset();
                    $('#grozs-order-summary').html('<p class="grozs-cart-empty">Neviens produkts vēl nav pievienots.</p>');
                    $('#grozs-total-sum').text('0.00');
                    localStorage.removeItem('grozs_cart');
                    updateCartIconCount();
                    $('#grozs-cart-count').hide();
                    $('#grozs-checkout-order-form-wrapper').hide();
                    $('#grozs-empty-wrapper').show();
                } else {
                    $('.grozs-form-response').html('<p style="color:red;">Kļūda! Mēģiniet vēlreiz.</p>');
                }
            }
        });
    });

    renderCheckoutSummary();
    updateCartIconCount();

});
