<?php
namespace Grozs\Ajax;
if (!defined('ABSPATH')) exit;

use function Grozs\Emails\grozs_render_email;

// ====== 1.b) Unikāls 7-ciparu ID CPT pasūtījumiem (postmeta) ======
function grozs_generate_public_order_id_cpt( $max_tries = 5 ) {
    for ($i = 0; $i < $max_tries; $i++) {
        $candidate = mt_rand(1000000, 9999999); // 7 cipari

        // pārbaudām meta konfliktus
        $existing = get_posts([
            'post_type'      => 'pasutijumi',
            'post_status'    => 'any',
            'fields'         => 'ids',
            'posts_per_page' => 1,
            'meta_query'     => [[
                'key'   => 'order_public_id',
                'value' => $candidate,
            ]],
        ]);

        if (empty($existing)) {
            return $candidate;
        }
    }
    // ļoti reti – fallback
    return (int) (time() . mt_rand(10,99));
}

// ===== AJAX handleris =====

add_action('wp_ajax_submit_grozs_order', __NAMESPACE__ . '\grozs_handle_order');
add_action('wp_ajax_nopriv_submit_grozs_order', __NAMESPACE__ . '\grozs_handle_order');

function grozs_handle_order() {
    check_ajax_referer('grozs_order_nonce', 'nonce');
    $form = $_POST['form'] ?? [];
    $cart = $_POST['cart'] ?? [];

    if (empty($form) || empty($cart)) {
        wp_send_json_error(['message' => 'Trūkst datu.']);
    }

    // normalizē formu

    $form_data = [];
    foreach ($form as $field) {
        $form_data[$field['name']] = sanitize_text_field($field['value']);
    }

    $public_id = grozs_generate_public_order_id_cpt();

    // saglabā CPT
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
            'order_public_id' => $public_id,
        ],
    ]);

    // === E-Pasta veidnes: ģenerējam HTML no templatiem ===

    $common = [
        'public_id' => $public_id,
        'form_data' => $form_data,
        'cart'      => $cart,
    ];

    $html_admin  = grozs_render_email('admin-order',  $common);
    $html_client = grozs_render_email('client-order', $common);

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

    wp_send_json_success([
        'message'  => 'Pasūtījums saņemts.',
        'order_id' => $public_id,
    ]);
}
