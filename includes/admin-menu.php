<?php
// Reģistrē "Pasūtījumi" un "Grozs" kā apakšizvēlnes zem CPT "produkti"
add_action('admin_menu', 'grozs_register_admin_submenu');
function grozs_register_admin_submenu() {
    add_submenu_page(
        'edit.php?post_type=produkti',
        'Pasūtījumi',
        'Pasūtījumi',
        'manage_options',
        'grozs_orders',
        '\Grozs\Admin\grozs_admin_orders_page'
    );

    add_submenu_page(
        'edit.php?post_type=produkti',
        'Grozs',
        'Grozs',
        'manage_options',
        'grozs_settings',
        '\Grozs\Admin\grozs_admin_settings_page'
    );
}

    /**
     * Ielādē spraudņa administrācijas CSS tikai attiecīgajās Grozs admin lapās.
     */
    function grozs_enqueue_admin_css($hook) {
        // Nosakām atļautās admin lapas hook nosaukumus, kur ielādēt CSS
        $allowed_hooks = array(
            // submenu under 'produkti' produces hooks in format 'produkti_page_{menu_slug}'
            'produkti_page_grozs_settings',
            'produkti_page_grozs_orders',
        );

        // Ja šī lapa nav mūsu sarakstā, iziet
        if (!in_array($hook, $allowed_hooks, true)) {
            return;
        }

        $css_path = plugin_dir_url(__FILE__) . '../assets/css/admin.css';
        wp_register_style('grozs-admin', $css_path, array(), filemtime(plugin_dir_path(__FILE__) . '../assets/css/admin.css'));
        wp_enqueue_style('grozs-admin');
    }
    add_action('admin_enqueue_scripts', 'grozs_enqueue_admin_css');
// Iestatījumu reģistrācija
add_action('admin_init', 'grozs_register_settings');
function grozs_register_settings() {
    register_setting('grozs_settings_group', 'grozs_notify_admin_email');
    register_setting('grozs_settings_group', 'grozs_notify_custom_email_enabled');
    register_setting('grozs_settings_group', 'grozs_custom_notification_email');
    register_setting('grozs_settings_group', 'grozs_notify_form_user_email');
}
