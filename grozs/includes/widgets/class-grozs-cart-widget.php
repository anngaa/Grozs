<?php
// === includes/class-grozs-cart-widget.php ===

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Grozs Cart Popup Widget.
 *
 * Renders a cart‐button widget in header builder areas. The actual
 * popup slider markup, styles and scripts are injected once in the footer.
 */
class Grozs_Cart_Widget extends WP_Widget {

    /**
     * Constructor: register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'grozs_cart_widget',
            __( 'Grozs: Cart Popup', 'grozs' ),
            [
                'description' => __( 'Grozs popup slider ar pogu', 'grozs' ),
            ]
        );
    }

    /**
     * Front-end display of widget: just output the button.
     *
     * @param array $args     Widget display arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        ?>
        <button class="open-grozs-button" aria-label="<?php esc_attr_e( 'Atvērt grozu', 'grozs' ); ?>">
            <i class="fa-solid fa-bag-shopping"><span class="grozs-cart-all-count"></span></i>
        </button>
        <?php
        echo $args['after_widget'];
    }

    /**
     * Backend widget form: none for now.
     */
    public function form( $instance ) {
        // No settings.
    }

    /**
     * Sanitize and save widget form values: none for now.
     */
    public function update( $new_instance, $old_instance ) {
        return $old_instance;
    }
}

/**
 * Register the Grozs_Cart_Widget widget.
 */
add_action( 'widgets_init', function() {
    register_widget( 'Grozs_Cart_Widget' );
} );

/**
 * Inject popup slider markup, styles & JS once in the footer.
 */
add_action( 'wp_footer', function() {
    static $injected;

    if ( $injected ) {
        return;
    }
    $injected = true;
    ?>

    <!-- grozs kartiņa -->
    <div id="grozs-cart" class="grozs-cart">
        <div class="grozs-cart-header">
            <h5 style="margin: 0;"><?php esc_html_e( 'Grozs', 'grozs' ); ?></h5>
            <button id="close-grozs" class="grozs-cart-close-button">✕</button>
        </div>

        <div class="grozs-cart-body">
            <div id="grozs-cart-items"></div>
            <div id="grozs-cart-total" class="grozs-cart-total"></div>
			<div class="cart-empty">
                <?php esc_html_e( 'Grozs ir tukšs.', 'grozs' ); ?>
            </div>
        </div>

        <div class="grozs-cart-footer">
            <a href="<?php echo home_url('/produkti'); ?>" class="view-cart-button view-cart-button-produkti">
                <?php esc_html_e( 'Izvēlēties produktus', 'grozs' ); ?><i class="fi fi-bs-arrow-up-right"></i>
            </a>
            <a href="<?php echo home_url('/grozs'); ?>" class="view-cart-button view-cart-button-grozs">
                <?php esc_html_e( 'Veikt pasūtījumu', 'grozs' ); ?><i class="fi fi-bs-arrow-up-right"></i>
            </a>
        </div>
    </div>

    <!-- grozs popup slider script -->
    <script>
    document.addEventListener( 'DOMContentLoaded', function() {
        var buttons  = document.querySelectorAll( '.open-grozs-button' );
        var cart      = document.getElementById( 'grozs-cart' );
        var closeBtn  = document.getElementById( 'close-grozs' );

        // Open popup when any header button is clicked
        buttons.forEach( function( btn ) {
            btn.addEventListener( 'click', function() {
                cart.classList.add( 'open' );
            });
        });

        // Close popup
        if ( closeBtn ) {
            closeBtn.addEventListener( 'click', function() {
                cart.classList.remove( 'open' );
            });
        }
    });
    </script>

    <?php
}, 20 );
