<?php
namespace Includes\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Grozs_AddButton_Widget extends Widget_Base {

    public function get_name() {
        return 'grozs_add_button';
    }

    public function get_title() {
        return 'Grozs: Pievienot Grozam Poga';
    }

    public function get_icon() {
        return 'eicon-product-add-to-cart';
    }

    public function get_categories() {
        return ['grozs-widgets'];
    }

    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => 'Saturs',
        ]);

        $this->add_control('button_text', [
            'label' => 'Pogas teksts',
            'type' => Controls_Manager::TEXT,
            'default' => 'Pievienot grozam',
        ]);

        $this->end_controls_section();

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

        $this->start_controls_section('section_style', [
            'label' => 'Stils',
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'default' => [
                'top' => 20,
                'right' => 60,
                'bottom' => 20,
                'left' => 60,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .grozs-add-to-cart-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $product_id = get_the_ID();
        $terms = get_the_terms($product_id, 'kategorijas');
        $is_bed = false;
        $is_nightstand_or_dresser = false;
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                if ($term->slug === 'gultas') {
                    $is_bed = true;
                }
                if ($term->slug === 'naktsskapisi' || $term->slug === 'kumodes') {
                    $is_nightstand_or_dresser = true;
                }
            }
        }
        $should_render = false;
        if ($is_bed && have_rows('matracu_izmeri', $product_id)) {
            while (have_rows('matracu_izmeri', $product_id)) {
                the_row();
                $izmers = get_sub_field('matraca_izmers');
                $priede = get_sub_field('cena_priedei');
                $osis = get_sub_field('cena_osim');
                $ozols = get_sub_field('cena_ozolam');
                if ($izmers && $priede && $osis && $ozols) {
                    $should_render = true;
                    break;
                }
            }
        }
        if ($is_nightstand_or_dresser && have_rows('produkta_izmeri_un_cenas', $product_id)) {
            while (have_rows('produkta_izmeri_un_cenas', $product_id)) {
                the_row();
                $izmers = get_sub_field('produkta_izmers');
                $priede = get_sub_field('sk_cena_priedei');
                $osis = get_sub_field('sk_cena_osim');
                $ozols = get_sub_field('sk_cena_ozolam');
                if ($izmers && $priede && $osis && $ozols) {
                    $should_render = true;
                    break;
                }
            }
        }
        if (! $should_render) return;

        $settings    = $this->get_settings_for_display();
        $product_id  = get_the_ID();
        $title       = get_the_title($product_id);
        $image       = get_the_post_thumbnail_url($product_id, 'thumbnail');
        $price       = 0; // fallback value; JS picks up the actual price
        $container_id = 'grozs-product-' . esc_attr($product_id);
        $link        = get_permalink($product_id);
        $group       = !empty($settings['grozs_group']) ? $settings['grozs_group'] : 'pc';
        ?>

        <div
            id="<?php echo esc_attr($container_id); ?>"
            class="grozs-product-container"
            style="display: flex; flex-direction: column; align-items: flex-end;"
            data-product-id="<?php echo esc_attr($product_id); ?>"
            data-title="<?php echo esc_js($title); ?>"
            data-image="<?php echo esc_url($image); ?>"
            data-link="<?php echo esc_url($link); ?>"
            data-grozs-group="<?php echo esc_attr($group); ?>"
        >
            <button class="grozs-add-to-cart-button grozs-add-button"
                type="button"
                data-original-text="<?php echo esc_attr($settings['button_text']); ?>"
                data-grozs-group="<?php echo esc_attr($group); ?>"
                disabled
            >
                <?php echo esc_html($settings['button_text']); ?>
            </button>

            <div class="grozs-cart-feedback"
                style="display: none; width: auto; padding: 15px; line-height: 1; color:green; font-size:14px; margin-top:10px; white-space:nowrap; border: solid 1px; border-color: green;">
                <i class="fa-solid fa-cart-arrow-down" style="margin-right: 5px;"></i> Produkts pievienots grozam
            </div>
        </div>
        <?php
    }
}
