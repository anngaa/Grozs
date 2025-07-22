<?php

// Reģistrēam jaunu sadaļu “Grozs Widgets” Elementor sidebar–ā
add_action( 'elementor/elements/categories_registered', function( $elements_manager ) {
    $elements_manager->add_category(
        'grozs-widgets',
        [
            'title' => __( 'Grozs', 'grozs' ),
            'icon'  => 'fa-solid fa-bag-shopping',
        ]
    );
} );

add_action( 'elementor/widgets/register', 'grozs_register_custom_widgets' );

function grozs_register_custom_widgets( $widgets_manager ) {
    // Ielādējam nepieciešamos failus
    require_once GROZS_PATH . 'includes/widgets/class-grozs-widget-options.php';
    require_once GROZS_PATH . 'includes/widgets/class-grozs-cena-widget.php';
    require_once GROZS_PATH . 'includes/widgets/class-grozs-addbutton-widget.php';
	require_once GROZS_PATH . 'includes/widgets/class-grozs-gallery-widget.php';
	require_once GROZS_PATH . 'includes/widgets/class-grozs-archiveprice-widget.php';
	require_once GROZS_PATH . 'includes/widgets/class-grozs-pricetable-widget.php';
	require_once GROZS_PATH . 'includes/widgets/class-grozs-checkout-form-widget.php';
	require_once GROZS_PATH . 'includes/widgets/class-grozs-checkout-summary-widget.php';

    // Reģistrējam visus widgetus
    $widgets_manager->register( new \Includes\Widgets\Grozs_Widget_Options() );
    $widgets_manager->register( new \Includes\Widgets\Grozs_Cena_Widget() );
    $widgets_manager->register( new \Includes\Widgets\Grozs_AddButton_Widget() );
	$widgets_manager->register( new \Includes\Widgets\Grozs_Gallery_Widget() );
	$widgets_manager->register(new \Includes\Widgets\Grozs_ArchivePrice_Widget());
	$widgets_manager->register(new \Includes\Widgets\Grozs_Pricetable_Widget());
	$widgets_manager->register(new \Includes\Widgets\Grozs_Checkout_Form_Widget());
	$widgets_manager->register(new \Includes\Widgets\Grozs_Checkout_Summary_Widget());
}