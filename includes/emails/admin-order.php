<?php if (!defined('ABSPATH')) exit; ?>
<h2>Jauns pasūtījums no TavaGulta.lv</h2>

<div>
    <p><strong>Pasūtījuma ID:</strong> #<?php echo esc_html($public_id); ?></p>

    <?php if (!empty($form_data) && is_array($form_data)) : ?>
    <table cellspacing="0" cellpadding="10" border="1" style="line-height:0.9;border-color:#eaeaea;border-collapse:collapse;font-family:sans-serif;width:100%;margin-bottom:16px;">
        <?php foreach ($form_data as $key => $val): ?>
            <tr>
                <th style="text-transform:capitalize;text-align:left;background:#fafafa;padding:10px;"><?php echo esc_html($key); ?></th>
                <td style="padding:10px;"><?php echo nl2br(esc_html($val)); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <h3 style="font-family:sans-serif;">Pasūtītie produkti:</h3>
    <?php $grozs_partial('items-table', ['cart' => $cart]); ?>
</div>