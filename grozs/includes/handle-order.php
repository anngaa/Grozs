<?php
if (!defined('ABSPATH')) exit;

// ===== AJAX handleris =====

add_action('wp_ajax_submit_grozs_order', 'grozs_handle_order');
add_action('wp_ajax_nopriv_submit_grozs_order', 'grozs_handle_order');

function grozs_handle_order() {
    check_ajax_referer('grozs_order_nonce', 'nonce');
    $form = $_POST['form'] ?? [];
        $cart = $_POST['cart'] ?? [];

    if (empty($form) || empty($cart)) {
        wp_send_json_error(['message' => 'Trūkst datu.']);
    }

    $form_data = [];
    foreach ($form as $field) {
        $form_data[$field['name']] = sanitize_text_field($field['value']);
    }

    $order_id = wp_insert_post([
        'post_type'   => 'pasutijumi',
        'post_status' => 'publish',
        'post_title'  => 'Pasūtījums no ' . ($form_data['vards'] ?? 'nezināms') . ' – ' . current_time('Y-m-d H:i'),
        'meta_input'  => [
            'vards'    => $form_data['vards']    ?? '',
            'epasts'   => $form_data['epasts']   ?? '',
            'telefons' => $form_data['telefons'] ?? '',
            'adrese'   => $form_data['adrese']   ?? '',
            'piezimes' => $form_data['piezimes'] ?? '',
            'grozs'    => json_encode($cart, JSON_UNESCAPED_UNICODE),
        ],
    ]);

    // ==== E-PASTA VEIDNES ====

    ob_start();
    ?>
    <h2>Jauns pasūtījums no TavaGulta.lv</h2>
    <table cellspacing="0" cellpadding="10" border="1" style="line-height:0.9;border-color: #eaeaea;border-collapse:collapse;font-family:sans-serif;">
        <?php foreach ($form_data as $key => $val): ?>
            <tr>
                <th style="text-align:left;background:#fafafa;"><?php echo ucfirst($key); ?></th>
                <td><?php echo nl2br(esc_html($val)); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h3 style="font-family:sans-serif;">Pasūtītie produkti:</h3>
    <?php foreach ($cart as $item): ?>
        <div style="margin-bottom:20px;padding:20px;border:1px solid #eaeaea;">
            <strong>
                <?php echo esc_html($item['title']); ?>
            </strong><br>
            Cena: €<?php echo esc_html($item['price']); ?>
            <?php if (!empty($item['quantity']) && $item['quantity'] > 1): ?>
                <strong>× <?php echo intval($item['quantity']); ?></strong>
            <?php endif; ?>
            <br>
            <?php if (!empty($item['krasa'])): ?>Krāsa: <?php echo esc_html($item['krasa']); ?><br><?php endif; ?>
            <?php if (!empty($item['izmers'])): ?>Matrača izmērs: <?php echo esc_html($item['izmers']); ?><br><?php endif; ?>
            <?php if (!empty($item['produkta_izmers'])): ?>Izmērs: <?php echo esc_html($item['produkta_izmers']); ?><br><?php endif; ?>
            <?php if (!empty($item['materials'])): ?>Materiāls: <?php echo esc_html($item['materials']); ?><br><?php endif; ?>
            <?php if (!empty($item['atvilknes'])): ?>Atvilknes zem gultas: <?php echo esc_html($item['atvilknes']); ?><br><?php endif; ?>
            <?php if (!empty($item['pacelams'])): ?>Paceļams matracis: <?php echo esc_html($item['pacelams']); ?><br><?php endif; ?>
        </div>
    <?php endforeach;

    $html_admin = ob_get_clean();

    ob_start(); ?>
    <h2>Paldies par pasūtījumu!</h2>
    <p>Mēs ar Jums sazināsimies, tiklīdz būsim to apstrādājuši.</p>
    <h3 style="font-family:sans-serif;">Pasūtītie produkti:</h3>
    <?php foreach ($cart as $item): ?>
        <div style="margin-bottom:20px;padding:20px;border:1px solid #eaeaea;">
            <strong>
                <?php echo esc_html($item['title']); ?>
            </strong><br>
            Cena: €<?php echo esc_html($item['price']); ?>
            <?php if (!empty($item['quantity']) && $item['quantity'] > 1): ?>
                <strong>× <?php echo intval($item['quantity']); ?></strong>
            <?php endif; ?>
            <br>
            <?php if (!empty($item['krasa'])): ?>Krāsa: <?php echo esc_html($item['krasa']); ?><br><?php endif; ?>
            <?php if (!empty($item['izmers'])): ?>Matrača izmērs: <?php echo esc_html($item['izmers']); ?><br><?php endif; ?>
            <?php if (!empty($item['produkta_izmers'])): ?>Izmērs: <?php echo esc_html($item['produkta_izmers']); ?><br><?php endif; ?>
            <?php if (!empty($item['materials'])): ?>Materiāls: <?php echo esc_html($item['materials']); ?><br><?php endif; ?>
            <?php if (!empty($item['atvilknes'])): ?>Atvilknes zem gultas: <?php echo esc_html($item['atvilknes']); ?><br><?php endif; ?>
            <?php if (!empty($item['pacelams'])): ?>Paceļams matracis: <?php echo esc_html($item['pacelams']); ?><br><?php endif; ?>
        </div>
    <?php endforeach;
    $html_client = ob_get_clean();

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: TavaGulta.lv <no-reply@tavagulta.lv>',
    ];

    // ADMIN
    if (get_option('grozs_notify_admin_email')) {
        wp_mail(get_option('admin_email'), 'Jauns pasūtījums - TavaGulta.lv', $html_admin, $headers);
    }

    // CUSTOM
    if (get_option('grozs_notify_custom_email_enabled') && is_email(get_option('grozs_custom_notification_email'))) {
        wp_mail(get_option('grozs_custom_notification_email'), 'Jauns pasūtījums - TavaGulta.lv', $html_admin, $headers);
    }

    // KLIENTAM
    if (get_option('grozs_notify_form_user_email') && !empty($form_data['epasts']) && is_email($form_data['epasts'])) {
        wp_mail($form_data['epasts'], 'Pasūtījums - TavaGulta.lv', $html_client, $headers);
    }

    wp_send_json_success(['message' => 'Pasūtījums saņemts.']);
}
