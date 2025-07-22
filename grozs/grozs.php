<?php
/**
 * Plugin Name: Grozs
 * Description: Pielāgots groza spraudnis ar produktu un pasūtījumu pārvaldību.
 * Version: 1.0.0
 * Author: Andis
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // No direct access
}

// Definējam konstantus
define( 'GROZS_PATH', plugin_dir_path( __FILE__ ) );
define( 'GROZS_URL', plugin_dir_url( __FILE__ ) );

add_action('wp_enqueue_scripts', 'grozs_enqueue_frontend_styles', 1); // zems prioritātes skaitlis = agrāk
function grozs_enqueue_frontend_styles() {
    wp_enqueue_style('grozs-frontend', GROZS_URL . 'assets/css/frontend.css', [], '1.0', 'all');
}

// Ielādējam admina izvēlni un tā daļas
require_once GROZS_PATH . 'includes/admin-menu.php';
require_once GROZS_PATH . 'includes/admin/admin-pasutijumi.php';
require_once GROZS_PATH . 'includes/admin/admin-grozs-settings.php';

// Ielādējam pasūtīšanas formas AJAX daļu
require_once GROZS_PATH . 'includes/handle-order.php';

// Ielādējam Kartītes widgetu
require_once GROZS_PATH . 'includes/widgets/class-grozs-cart-widget.php';

// === Elementor widgetu ielāde ===
add_action( 'plugins_loaded', 'grozs_load_elementor_widgets' );

function grozs_load_elementor_widgets() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-warning"><p>Spraudnim "Grozs" nepieciešams Elementor.</p></div>';
        } );
        return;
    }

    require_once GROZS_PATH . 'includes/class-grozs-elementor-init.php';
}

// Ielādējam JS failus
function grozs_enqueue_assets() {
    wp_enqueue_script('jquery');

	wp_enqueue_script(
        'grozs-cena',
        GROZS_URL . 'assets/js/grozs-cena.js',
        ['jquery'],
        '1.0',
        true
    );
	
	wp_enqueue_script(
        'grozs-cart',
        GROZS_URL . 'assets/js/grozs-cart.js',
        ['jquery'],
        '1.0',
        true
    );
	
	wp_enqueue_script(
        'grozs-addbutton',
        GROZS_URL . 'assets/js/grozs-addbutton.js',
        ['jquery'],
        '1.0',
        true
    );
	wp_enqueue_script(
        'grozs-gallery',
        GROZS_URL . 'assets/js/grozs-gallery.js',
        ['jquery'],
        '1.0',
        true
    );
	
	wp_enqueue_script(
        'grozs-init',
        GROZS_URL . 'assets/js/grozs-init.js',
        ['jquery'],
        '1.0',
        true
    );

    wp_enqueue_script(
        'grozs-checkout',
        GROZS_URL . 'assets/js/grozs-checkout.js',
        ['jquery'],
        '1.0',
        true
    );

    wp_localize_script('grozs-checkout', 'grozs_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('grozs_order_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'grozs_enqueue_assets');
