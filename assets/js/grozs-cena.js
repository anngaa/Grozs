jQuery(document).ready(function($) {

    // =========================
    // === CENU APRĒĶINS AR GRUPU ===
    // =========================

    function parsePrice(value) {
        value = value ? value.toString().replace(',', '.').replace(/\s+/g, '') : '0';
        return parseFloat(value) || 0;
    }

    // Aprēķina cenu tikai konkrētai grupai
    function calculatePrice(group) {
        var $container = $('.grozs-options[data-grozs-group="' + group + '"]');
        var $priceWidget = $('.grozs-cena[data-grozs-group="' + group + '"]');
        var material = $container.find('.grozs-material').val() || '';
        var total = 0;

        var sizePrice = 0;
        var atvilknesPrice = 0;
        var pacelamsPrice = 0;

        if ($container.find('.grozs-produkta-izmers').length) {
            var selectedSize = $container.find('.grozs-produkta-izmers option:selected');
            var sizeValue = selectedSize.val();

            // Ja vēl nav izvēlēts izmērs vai materiāls, neturpinām
            if (!sizeValue || sizeValue === 'izveleties' || !material || material === 'izveleties') return;

            sizePrice = selectedSize.length ? parsePrice(selectedSize.attr('data-' + material)) : 0;
            total = sizePrice;
        } else {
            var selectedSize      = $container.find('.grozs-izmers option:selected');
            var selectedAtvilknes = $container.find('.grozs-atvilknes option:selected');
            var selectedPacelams  = $container.find('.grozs-pacelams option:selected');

            var sizeValue = selectedSize.val();
            if (!sizeValue || sizeValue === 'izveleties' || !material || material === 'izveleties') return;

            sizePrice      = selectedSize.length      ? parsePrice(selectedSize.data(material))      : 0;
            atvilknesPrice = selectedAtvilknes.length ? parsePrice(selectedAtvilknes.data(material)) : 0;
            pacelamsPrice  = selectedPacelams.length  ? parsePrice(selectedPacelams.data(material))  : 0;

            total = sizePrice + atvilknesPrice + pacelamsPrice;
        }

        // Tikai ja nonākuši līdz šim, pārrakstām cenu konkrētajā widgetā
        const priceHtml = '<span class="grozs-price-value"><span class="grozs-price-currency">€</span><span class="grozs-price-numb">' + total.toFixed(2) + '</span></span>';
        $priceWidget.html(priceHtml);
    }

    // Inicializē kalkulatoru katrai grupai
    function initCalculator() {
        $('.grozs-options').each(function() {
            var group = $(this).data('grozs-group');
            var $container = $(this);

            if ($container.find('.grozs-material').length) {
                $container.find('.grozs-material, .grozs-izmers, .grozs-atvilknes, .grozs-pacelams, .grozs-produkta-izmers').on('change', function() {
                    calculatePrice(group);
                });
                calculatePrice(group);
            }
        });
    }

    window.initCalculator = initCalculator;
    initCalculator();
});