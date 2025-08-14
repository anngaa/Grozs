<?php
/**
 * Dashboard: At a Glance (produkti + pasutijumi) un Activity (tikai produkti)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/** ——— Droša skaitīšana arī nereģistrētam post_type ——— */
function grozs_count_posts_sql( string $post_type, array $statuses ) : int {
    global $wpdb;
    $in = implode( "','", array_map('esc_sql', $statuses) );
    $sql = $wpdb->prepare(
        "SELECT COUNT(1) FROM {$wpdb->posts} WHERE post_type = %s AND post_status IN ('{$in}')",
        $post_type
    );
    $count = (int) $wpdb->get_var( $sql );
    return $count;
}

/** At a Glance: pievieno Produktu un Pasūtījumu skaitu (ar saitēm) */
add_filter('dashboard_glance_items', function(array $items) {

    // Ja vēlies, lai redz arī redaktori, nomaini uz 'edit_posts'
    if ( ! current_user_can('manage_options') ) return $items;

    // ——— PRODUKTI (izmantojam wp_count_posts, jo tas strādā reģistrētam CPT) ———
    if ( post_type_exists('produkti') ) {
        $counts    = wp_count_posts('produkti');
        $published = isset($counts->publish) ? (int) $counts->publish : 0;
        $text = sprintf(
            _n('%s Produkts', '%s Produkti', $published, 'grozs'),
            number_format_i18n($published)
        );
        $url = admin_url('edit.php?post_type=produkti');
        $items[] = sprintf(
            '<a class="grozs-at-a-glance-produkti" href="%s">%s</a>',
            esc_url($url),
            esc_html($text)
        );
    }

    // ——— PASŪTĪJUMI (SQL, lai strādā arī bez CPT reģistrācijas) ———
    // Pirmkārt mēģinām 'publish'. Ja 0, parādam "kopā" (ar draft/pending/private),
    // lai nebūtu tukšs, ja tev plūsma nav "publish".
    $orders_published = grozs_count_posts_sql('pasutijumi', ['publish']);
    $orders_total     = $orders_published > 0
        ? $orders_published
        : grozs_count_posts_sql('pasutijumi', ['publish','pending','draft','private']);

    $orders_text = sprintf(
        _n('%s Pasūtījums', '%s Pasūtījumi', $orders_total, 'grozs'),
        number_format_i18n($orders_total)
    );

    // Saite uz tavu pielāgoto pasūtījumu lapu
    $orders_url = admin_url('edit.php?post_type=produkti&page=grozs_orders');

    $items[] = sprintf(
        '<a class="grozs-at-a-glance-pasutijumi" href="%s">%s</a>',
        esc_url($orders_url),
        esc_html($orders_text)
    );

    return $items;
});

/** Ikonas At a Glance vienumiem (pārrakstām Dashicons ar FA) */
add_action('admin_head-index.php', function () {
    echo '<style>
        /* PRODUKTI */
        #dashboard_right_now .grozs-at-a-glance-produkti:before,
        .at-a-glance .grozs-at-a-glance-produkti:before {
            content: "\f174"; /* dashicons-cart/products */
            font: normal 20px/1 dashicons;
            speak: never;
        }
        /* PASŪTĪJUMI */
        #dashboard_right_now .grozs-at-a-glance-pasutijumi:before,
        .at-a-glance .grozs-at-a-glance-pasutijumi:before {
            content: "\f498"; /* dashicons-admin-post (universāls) */
            font: normal 20px/1 dashicons;
            speak: never;
        }
    </style>';
});

/** Activity: pievienojam TIKAI "produkti" (kā prasīji) */
add_filter('dashboard_recent_posts_query_args', function(array $args) {
    $existing = $args['post_type'] ?? 'post';
    $list     = (array) $existing;

    if ( post_type_exists('produkti') && ! in_array('produkti', $list, true) ) {
        $list[] = 'produkti';
    }
    $args['post_type'] = $list;

    return $args;
}, 10, 1);