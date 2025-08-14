<?php
namespace Includes\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Grozs_Gallery_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'grozs_gallery';
    }

    public function get_title() {
        return __( 'Grozs: Attēlu galerija', 'grozs' );
    }

    public function get_icon() {
        return 'eicon-product-images';
    }

    public function get_categories() {
        return [ 'grozs-widgets' ];
    }

    public function get_keywords() {
        return [ 'galerija', 'grozs', 'attēli', 'produkts' ];
    }

    public function get_style_depends() {
        return [ 'grozs-frontend' ];
    }

    public function get_script_depends() {
        return [ 'grozs-frontend' ];
    }

    protected function register_controls() {
        $this->start_controls_section( 'section_content_additional_settings', [
            'label' => __('Papildus Iestatījumi', 'grozs'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ] );

        // PIEVIENOJAM KUSTOM GRUPAS LAUKU
        $this->add_control('grozs_group', [
            'label' => 'Widgeta grupa',
            'type' => Controls_Manager::TEXT,
            'default' => 'pc',
            'description' => 'Norādi grupas nosaukumu, lai nošķirtu widgetus pēc loģikas (piem. "pc", "mobile", "custom1" u.c.).',
        ]);

        $this->end_controls_section();

        $this->start_controls_section( 'section_title_style', [
            'label' => 'Mazās Bildītes',
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'thumbs_padding', [
            'label'      => __( 'Sīkbilžu iekšējā atkāpe', 'grozs' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'default'    => [
                'top'      => 10,
                'right'    => 0,
                'bottom'   => 0,
                'left'     => 0,
                'unit'     => 'px',
                'isLinked' => false, // sākumā sasietas malas
            ],
            'selectors'  => [
                '{{WRAPPER}} .grozs-gallery-thumbs' =>
                    'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        global $post;
        if ( ! $post ) {
            return;
        }

        $settings = $this->get_settings_for_display();
        $group    = 'product-gallery' . $post->ID;
        $widget_group = !empty($settings['grozs_group']) ? $settings['grozs_group'] : 'pc';
        $featured = get_the_post_thumbnail_url( $post->ID, 'large' );
        $gallery  = get_field( 'galerija', $post->ID );
        $images   = [];

        if ( $featured ) {
            $images[] = esc_url( $featured );
        }

        if ( ! empty( $gallery ) && is_array( $gallery ) ) {
            foreach ( $gallery as $image ) {
                if ( isset( $image['url'] ) ) {
                    $images[] = esc_url( $image['url'] );
                }
            }
        }

        if ( empty( $images ) ) {
            return;
        }
        ?>
        <div class="grozs-gallery-widget" data-grozs-group="<?php echo esc_attr($widget_group); ?>">
            <!-- Galvenā bilde -->
            <a href="<?php echo $images[0]; ?>"
               data-elementor-open-lightbox="yes"
               data-elementor-lightbox-slideshow="<?php echo esc_attr( $group ); ?>"
               class="grozs-gallery-main"
			   style="background-image: url('<?php echo $images[0]; ?>');">
                <img src="<?php echo $images[0]; ?>" alt="" />
            </a>

            <!-- Thumbnails -->
            <div class="grozs-gallery-thumbs">
                <?php foreach ( $images as $i => $img_url ) : ?>
                    <div class="grozs-gallery-thumb-wrapper">
                        <img src="<?php echo $img_url; ?>"
                             class="grozs-gallery-thumb"
                             data-thumb-index="<?php echo $i; ?>"
                             alt="" />
                            <a href="<?php echo $img_url; ?>"
                               data-elementor-open-lightbox="yes"
                               data-elementor-lightbox-slideshow="<?php echo esc_attr( $group ); ?>"
                               style="display:none;">
                            </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <style>
        .grozs-gallery-widget {
            max-width: 600px;
            margin: 0 auto;
        }
        .grozs-gallery-main {
		  display: block;
		  width: 100%;
		  aspect-ratio: 4 / 3;
		  position: relative;
		  overflow: hidden;
		  cursor: zoom-in;
		  background-repeat: no-repeat;
		  background-position: center center;
		  background-size: cover;
		  transition: background-position 0.1s ease;
		}
        .grozs-gallery-main img {
		  width: 100%;
		  height: 100%;
		  object-fit: cover;
		  opacity: 0;
		  pointer-events: none;
		  user-select: none;
		}
		.grozs-gallery-prev,
        .grozs-gallery-next {
            position: absolute;
            top: 50%;
			line-height: 1;
            transform: translateY(-50%);
            background: #fff;
            padding: 15px;
			padding-top: 11px;
            font-size: 18px;
			color: #333;
            cursor: pointer;
            z-index: 2;
            border: none;
			opacity: 0.5;
        }

        .grozs-gallery-prev { left: 10px; }
        .grozs-gallery-next { right: 10px; }
		
		.grozs-gallery-prev:hover,
        .grozs-gallery-next:hover {
			opacity: 0.9;
		}
        .grozs-gallery-thumbs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .grozs-gallery-thumb-wrapper {
            flex: 0 0 calc((100% - 50px) / 6);
            aspect-ratio: 1 / 1;
            box-sizing: border-box;
        }
        .grozs-gallery-thumb {
            width: 100%;
            height: 100%;
            padding: 2px;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            cursor: pointer;
            border: 1px solid #eaeaea !important;
            border-radius: 4px;
            transition: border-color 0.2s ease;
        }
        .grozs-gallery-thumb:hover,
        .grozs-gallery-thumb.active {
            border-color: #333 !important;
        }

        @media (max-width: 767px) {
            .grozs-gallery-prev,
            .grozs-gallery-next {
                display: none;
            }

            .grozs-gallery-thumbs {
                padding: 0;
            }
        }
        </style>
        <?php
    }
}