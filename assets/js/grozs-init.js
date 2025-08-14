jQuery(document).ready(function ($) {

    // =========================
    // === INIT (droši) ========
    // =========================

    if (typeof initCalculator === 'function') {
        initCalculator();
        setTimeout(initCalculator, 500); // arī šeit, tikai ja funkcija eksistē
    }

    if (typeof renderCartItems === 'function') {
        renderCartItems();
        $('.open-grozs-button').on('click', renderCartItems);
    }

});
