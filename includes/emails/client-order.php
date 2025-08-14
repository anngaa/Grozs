<?php if (!defined('ABSPATH')) exit; ?>
<h2>Paldies par pasūtījumu!</h2>
<p><strong>Jūsu pasūtījuma ID:</strong> #<?php echo esc_html($public_id); ?></p>
<p>Mēs ar Jums sazināsimies, tiklīdz būsim to apstrādājuši.</p>

<h3 style="font-family:sans-serif;">Pasūtītie produkti:</h3>
<?php $grozs_partial('items-table', ['cart' => $cart]); ?>