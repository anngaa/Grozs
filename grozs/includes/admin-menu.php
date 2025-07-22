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
        'grozs_admin_orders_page'
    );

    add_submenu_page(
        'edit.php?post_type=produkti',
        'Grozs',
        'Grozs',
        'manage_options',
        'grozs_settings',
        'grozs_admin_settings_page'
    );
}

// Iestatījumu reģistrācija
add_action('admin_init', 'grozs_register_settings');
function grozs_register_settings() {
    register_setting('grozs_settings_group', 'grozs_notify_admin_email');
    register_setting('grozs_settings_group', 'grozs_notify_custom_email_enabled');
    register_setting('grozs_settings_group', 'grozs_custom_notification_email');
    register_setting('grozs_settings_group', 'grozs_notify_form_user_email');
}
