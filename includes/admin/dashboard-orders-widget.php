<?php
/**
 * Dashboard: Grozs — nesenie pasūtījumi (pielāgots logrīks)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Reģistrē Groza pasūtījumu logrīku sākumekrānā.
 */
add_action('wp_dashboard_setup', 'grozs_register_orders_dashboard_widget');
function grozs_register_orders_dashboard_widget() {
    // Rādām tikai, ja lietotājam ir atbilstošas tiesības
    if (!current_user_can('manage_options')) {
        return;
    }

    wp_add_dashboard_widget(
        'grozs_orders_activity',                 // unikāls logrīka ID
        'Pasūtījumi',                            // virsraksts
        'grozs_render_orders_dashboard_widget'   // renderēšanas callback
    );
}

/**
 * Palīgfunkcija: pārveido cenu uz float no iespējamā virknes formāta.
 * (ievietota šeit lokāli; ja nākotnē vajag plašāk, var pārcelt uz helper failu)
 */
if ( ! function_exists('grozs_parse_price_to_float') ) {
    function grozs_parse_price_to_float($value) {
        $v = (string) $value;
        $v = str_replace(["\xC2\xA0", ' '], '', $v); // cietās & parastās atstarpes
        $v = str_replace(',', '.', $v);
        $v = preg_replace('/[^0-9.]/', '', $v);
        return (float) $v;
    }
}

/**
 * Renderē Groza pasūtījumu logrīku.
 */
function grozs_render_orders_dashboard_widget() {
    // Saites uz pasūtījumu lapu (zem "Produkti")
    $orders_url = admin_url('edit.php?post_type=produkti&page=grozs_orders');

    // Atlasa pēdējos 5 publicētos pasūtījumus
    $orders = get_posts([
        'post_type'      => 'pasutijumi',
        'post_status'    => 'publish',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);

    echo '<div class="grozs-orders-widget">';

    if (empty($orders)) {
        echo '<p>Nav pasūtījumu.</p>';
        echo '<p><a class="button button-secondary" href="' . esc_url($orders_url) . '">Atvērt pasūtījumu sarakstu</a></p>';
        echo '</div>';
        return;
    }

    echo '<h3>Jaunākie pasūtījumi</h3>';
    echo '<ul class="grozs-activity-list">';

    foreach ($orders as $order) {
        // Datums īsajā formātā: 11.08.2025 | 16:39 (lokālais laiks)
        $date_format = 'd.m.Y | H:i';
        $date_str = function_exists('get_date_from_gmt')
            ? get_date_from_gmt($order->post_date_gmt, $date_format)
            : date_i18n($date_format, strtotime($order->post_date));

        // Meta lauki (rādām tikai vārdu)
        $vards     = get_post_meta($order->ID, 'vards', true);
        $cart_json = get_post_meta($order->ID, 'grozs', true);

        // Aprēķinām produktu skaitu un kopējo cenu no groza JSON
        $count_products = 0;
        $total_price    = 0.0;

        $items = json_decode($cart_json, true);
        if (is_array($items)) {
            foreach ($items as $item) {
                $qty   = max(1, (int) ($item['quantity'] ?? 1));
                $price = grozs_parse_price_to_float($item['price'] ?? 0);
                $count_products += $qty;
                $total_price    += $price * $qty;
            }
        }

        $count_label = ($count_products === 1)
            ? '1 produkts'
            : sprintf('%d produkti', (int) $count_products);

        $total_str = '€ ' . number_format_i18n($total_price, 2);

        // Vienuma HTML ar saiti uz pasūtījumu lapu ar ?open=ID
        $order_link = add_query_arg(['open' => $order->ID], $orders_url);

        echo '<li class="grozs-activity-item">';
        echo '  <a href="' . esc_url($order_link) . '" aria-label="Atvērt pasūtījuma detaļas">';
        echo '      <span class="grozs-activity-date">' . esc_html($date_str) . '</span> — ';
        echo '      <strong>' . esc_html($vards ?: '–') . '</strong>';
        echo '      <span class="grozs-activity-count"> · ' . esc_html($count_label) . '</span>';
        echo '      <span class="grozs-activity-total"> · ' . esc_html($total_str) . '</span>';
        echo '  </a>';
        echo '</li>';
    }

    echo '</ul>';
    echo '<p style="margin-bottom: 0;"><a class="button button-primary" href="' . esc_url($orders_url) . '">Skatīt visus pasūtījumus</a></p>';
    echo '</div>';
}

/**
 * Neliels stils, lai saraksts izskatās kā aktivitāšu saraksts.
 */
add_action('admin_head-index.php', function () {
    echo '<style>
        /* Noņemam rimbuļus un iedobi sarakstam */
        .grozs-orders-widget .grozs-activity-list {
            margin: 0 0 12px 0;
            padding-left: 0;
            list-style: none;
            list-style-type: none;
        }
        .grozs-orders-widget .grozs-activity-item {
            margin: 6px 0;
            line-height: 1.4;
        }
        .grozs-orders-widget .grozs-activity-item a {
            text-decoration: none;
        }
        .grozs-orders-widget .grozs-activity-item a:hover {
            text-decoration: underline;
        }
        .grozs-orders-widget .grozs-activity-date {
            color: #666;
        }
    </style>';
});