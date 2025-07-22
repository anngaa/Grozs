jQuery(document).ready(function($) {

    // =========================
    // === CENU APRĒĶINS =======
    // =========================

    function parsePrice(value) {
        value = value ? value.toString().replace(',', '.').replace(/\s+/g, '') : '0';
        return parseFloat(value) || 0;
    }

    function calculatePrice() {
        var material = $('#material').val() || '';
        var total = 0;

        var sizePrice = 0;
        var atvilknesPrice = 0;
        var pacelamsPrice = 0;

        if ($('#produkta_izmers').length) {
            var selectedSize = $('#produkta_izmers option:selected');
            var sizeValue = selectedSize.val();

            // Ja vēl nav izvēlēts izmērs vai materiāls, neturpinām
            if (!sizeValue || sizeValue === 'izveleties' || !material || material === 'izveleties') return;

            sizePrice = selectedSize.length ? parsePrice(selectedSize.attr('data-' + material)) : 0;
            total = sizePrice;
        } else {
            var selectedSize      = $('#izmers option:selected');
            var selectedAtvilknes = $('#atvilknes option:selected');
            var selectedPacelams  = $('#pacelsana option:selected');

            var sizeValue = selectedSize.val();
            if (!sizeValue || sizeValue === 'izveleties' || !material || material === 'izveleties') return;

            sizePrice      = selectedSize.length      ? parsePrice(selectedSize.data(material))      : 0;
            atvilknesPrice = selectedAtvilknes.length ? parsePrice(selectedAtvilknes.data(material)) : 0;
            pacelamsPrice  = selectedPacelams.length  ? parsePrice(selectedPacelams.data(material))  : 0;

            total = sizePrice + atvilknesPrice + pacelamsPrice;
        }

        // Tikai ja nonākuši līdz šim, pārrakstām cenu
        const priceHtml = '€ <span id="price-value">' + total.toFixed(2) + '</span>';
        $('.grozs-cena').html(priceHtml);

    }

    function initCalculator() {
        if ($('#material').length) {
            $('#material, #izmers, #atvilknes, #pacelsana, #produkta_izmers').on('change', calculatePrice);
            calculatePrice();
        }
    }
	
	window.initCalculator = initCalculator;
	
});