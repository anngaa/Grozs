jQuery(document).ready(function($) {

    // =========================
    // === PRODUKTA PIEVIENOŠANA GROZĀ ===
    // =========================

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
    
    $('.grozs-add-to-cart-button').on('click', function() {
        var $container = $(this).closest('.grozs-product-container');
        var cartData = JSON.parse(localStorage.getItem('grozs_cart')) || [];

        var item = {
            id:    $container.data('product-id'),
            title: $container.data('title'),
            image: $container.data('image'),
            price: $('#price-value').text().trim(),
            link:  $container.data('link') || '#',
            quantity: 1
        };

        var form = $('.grozs-options');
        item.krasa           = form.find('[name="grozs_krasa"]:checked').val() || '';
        item.izmers          = form.find('#izmers').val() || '';
        item.produkta_izmers = form.find('#produkta_izmers').val() || '';
        item.materials       = form.find('#material option:selected').text() || '';
        item.atvilknes       = form.find('#atvilknes option:selected').text() || '';
        item.pacelams        = form.find('#pacelsana option:selected').text() || '';

        // === Pārbaude: identiska konfigurācija
        var existingIndex = cartData.findIndex(existing =>
            existing.id === item.id &&
            existing.krasa === item.krasa &&
            existing.izmers === item.izmers &&
            existing.produkta_izmers === item.produkta_izmers &&
            existing.materials === item.materials &&
            existing.atvilknes === item.atvilknes &&
            existing.pacelams === item.pacelams
        );

        if (existingIndex !== -1) {
            if (confirm('Šāds produkts jau ir grozā. Vai vēlies pievienot vēl vienu?')) {
                cartData[existingIndex].quantity = (cartData[existingIndex].quantity || 1) + 1;
            } else {
                return;
            }
        } else {
            // === Papildu pārbaude: produkts ar citu konfigurāciju jau ir grozā
            var sameProductDifferentConfig = cartData.find(existing =>
                existing.id === item.id &&
                (
                    existing.krasa !== item.krasa ||
                    existing.izmers !== item.izmers ||
                    existing.produkta_izmers !== item.produkta_izmers ||
                    existing.materials !== item.materials ||
                    existing.atvilknes !== item.atvilknes ||
                    existing.pacelams !== item.pacelams
                )
            );

            if (sameProductDifferentConfig) {
                if (!confirm('Šis produkts jau ir grozā ar citu konfigurāciju. Vai pievienot arī šo variantu?')) {
                    return;
                }
            }

            // Pievieno kā jaunu ierakstu
            cartData.push(item);
        }

        localStorage.setItem('grozs_cart', JSON.stringify(cartData));

        $container.find('.grozs-cart-feedback')
            .css('display', 'inline-block')
            .hide()
            .fadeIn(500)
            .delay(5000)
            .fadeOut(500);

        updateCartIconCount();
        renderCartItems();
    });

    // =========================
    // === POGAS AKTIVIZĀCIJA ===
    // =========================

    function checkOptionsReady() {
        console.log('[grozs] Pārbaudu izvēles...');
        let allSelected = true;

        document.querySelectorAll('.grozs-option select').forEach(select => {
            if (!select.value || select.value === 'izveleties') {
                allSelected = false;
            }
        });

        // Pārbauda visus izvēlētos radio inputus
        document.querySelectorAll('.grozs-option input[type="radio"]:checked').forEach(radio => {
            if (!radio.value || radio.value === 'izveleties') {
                allSelected = false;
            }
        });

        const addButton = document.querySelector('.grozs-add-button');
        if (addButton) {
            const originalText = addButton.dataset.originalText || 'Pievienot grozam2';
            if (allSelected) {
                addButton.disabled = false;
                addButton.innerHTML = originalText;
            } else {
                addButton.disabled = true;
                addButton.innerHTML = 'Izvēlieties opcijas <i class="fa-solid fa-arrow-up" style="margin-left: 5px;"></i>';
            }
        }
    }

    checkOptionsReady();
    $('.grozs-option select').on('change', checkOptionsReady);
    $('.grozs-option input[type="radio"]').on('change', checkOptionsReady);

});
