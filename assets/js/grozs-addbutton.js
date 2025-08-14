jQuery(document).ready(function($) {

    // --- Palīgfunkcijas cenām ---
    function normalizePrice(v){
        if (typeof v !== 'string') return Number(v || 0);
        // noņem € + atstarpes (t.sk. NBSP), nomaina komatu uz punktu
        v = v.replace(/[€\s\u00A0]/g, '').replace(',', '.');
        // atstāj tikai ciparus, punktu un mīnusu
        v = v.replace(/[^0-9.\-]/g, '');
        return Number(v || 0);
    }
    function formatEUR(n){
        return '€' + (Number(n||0)).toFixed(2);
    }

    // --- Vienreizēja “legacy” groza tīrīšana (ja agrāk cenā bija “€” ) ---
    (function fixLegacyCart(){
        try {
        var c = JSON.parse(localStorage.getItem('grozs_cart') || '[]');
        var changed = false;
        c.forEach(function(it){
            if (it && typeof it.price !== 'undefined') {
            var n = normalizePrice(it.price);
            if (!Number.isNaN(n) && n !== it.price) { it.price = n; changed = true; }
            }
        });
        if (changed) localStorage.setItem('grozs_cart', JSON.stringify(c));
        } catch(e){}
    })();

    // =========================
    // === PRODUKTA PIEVIENOŠANA GROZĀ ===
    // =========================

    $('.grozs-add-to-cart-button').on('click', function() {
        var $button = $(this);
        var group = $button.data('grozs-group');
        var $container = $button.closest('.grozs-product-container[data-grozs-group="' + group + '"]');
        var cartData = JSON.parse(localStorage.getItem('grozs_cart')) || [];

        // Cenu nolasām pēc grupas!
        var price = $('.grozs-cena[data-grozs-group="' + group + '"] .grozs-price-value').text().trim();
        var priceText = $('.grozs-cena[data-grozs-group="' + group + '"] .grozs-price-value').text().trim();
        var price = normalizePrice(priceText); // ← GLABĀ SKAITLI, BEZ “€”

        var item = {
            id:    $container.data('product-id'),
            title: $container.data('title'),
            image: $container.data('image'),
            price: price, // number
            link:  $container.data('link') || '#',
            quantity: 1
        };

        var $form = $('.grozs-options[data-grozs-group="' + group + '"]');
        item.krasa           = $form.find('[name="grozs_krasa"]:checked').val() || '';
        item.izmers          = $form.find('.grozs-izmers').val() || '';
        item.produkta_izmers = $form.find('.grozs-produkta-izmers').val() || '';
        item.materials       = ($form.find('.grozs-material option:selected').text() || '') .replace(/\s*\(iesak\u0101m\)\s*$/i, '') .trim();
        item.atvilknes       = $form.find('.grozs-atvilknes option:selected').text() || '';
        item.pacelams        = $form.find('.grozs-pacelams option:selected').text() || '';

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
    // === POGAS AKTIVIZĀCIJA AR GRUPU ===
    // =========================

    function checkOptionsReady(group) {
        let allSelected = true;

        $('.grozs-options[data-grozs-group="' + group + '"] .grozs-option select').each(function() {
            if (!this.value || this.value === 'izveleties') {
                allSelected = false;
            }
        });

        $('.grozs-options[data-grozs-group="' + group + '"] .grozs-option input[type="radio"]:checked').each(function() {
            if (!this.value || this.value === 'izveleties') {
                allSelected = false;
            }
        });

        var $addButton = $('.grozs-add-button[data-grozs-group="' + group + '"]');
        if ($addButton.length) {
            var originalText = $addButton.data('original-text') || 'Pievienot grozam';
            if (allSelected) {
                $addButton.prop('disabled', false);
                $addButton.html(originalText);
            } else {
                $addButton.prop('disabled', true);
                $addButton.html('Izvēlieties opcijas <i class="fa-solid fa-arrow-up" style="margin-left: 5px;"></i>');
            }
        }
    }

    $('.grozs-add-button').each(function() {
        var group = $(this).data('grozs-group');
        checkOptionsReady(group);
    });

    $(document).on('change', '.grozs-options select, .grozs-options input[type="radio"]', function() {
        var $form = $(this).closest('.grozs-options');
        var group = $form.data('grozs-group');
        checkOptionsReady(group);
    });

});
