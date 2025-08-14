<?php
namespace Includes\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Grozs_Cena_Widget extends Widget_Base {

    public function get_name() {
        return 'grozs_cena_widget';
    }

    public function get_title() {
        return 'Grozs: Produkta Cena';
    }

    public function get_icon() {
        return 'eicon-product-price';
    }

    public function get_categories() {
        return [ 'grozs-widgets' ];
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

        $this->start_controls_section( 'section_sizes-style', [
            'label' => 'Cenas noformējums',
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'title_color', [
            'label' => 'Cenas krāsa',
            'type' => Controls_Manager::COLOR,
            'default' => '#333333',
            'selectors' => [
                '{{WRAPPER}} .grozs-cena' => 'color: {{VALUE}}',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name' => 'price_typography',
            'label' => __('Cenas tipogrāfija', 'grozs'),
            'fields_options' => [
                'typography' => [ 'default' => 'yes' ],
                'font_size'   => [ 'default' => [ 'unit'=>'px','size'=> 28 ] ],
                'font_weight' => [ 'default' => 700 ],
            ],
            'selector' => '{{WRAPPER}} .grozs-cena',
        ] );

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
        $settings = $this->get_settings_for_display();
        $product_id = $settings['product_id'] ?: get_the_ID();

        $min_price = null;

        $matraci = get_field('matracu_izmeri', $product_id);
        if ($is_bed && $matraci && is_array($matraci)) {
            foreach ($matraci as $item) {
                foreach (['cena_priedei', 'cena_osim', 'cena_ozolam'] as $key) {
                    if (!empty($item[$key]) && is_numeric($item[$key])) {
                        $price = floatval($item[$key]);
                        if ($min_price === null || $price < $min_price) {
                            $min_price = $price;
                        }
                    }
                }
            }
        }

        $izm_cenas = get_field('produkta_izmeri_un_cenas', $product_id);
        if ($is_nightstand_or_dresser && $izm_cenas && is_array($izm_cenas)) {
            foreach ($izm_cenas as $item) {
                foreach (['sk_cena_priedei', 'sk_cena_osim', 'sk_cena_ozolam'] as $key) {
                    if (!empty($item[$key]) && is_numeric($item[$key])) {
                        $price = floatval($item[$key]);
                        if ($min_price === null || $price < $min_price) {
                            $min_price = $price;
                        }
                    }
                }
            }
        }

        $group = !empty($settings['grozs_group']) ? $settings['grozs_group'] : 'pc';
        
        ?>
        <div class="grozs-cena-widget grozs-cena-widget-<?php echo esc_attr($group); ?>">
            <div class="grozs-cena" data-grozs-group="<?php echo esc_attr($group); ?>">
                <span class="grozs-price-value disabled" data-min-price="<?php echo esc_attr($min_price); ?>">
                    <span class="grozs-price-currency">€</span><span class= "grozs-price-numb"><?php echo number_format($min_price, 2, '.', ''); ?></span>
                </span>
            </div>
        </div>
        <?php
    }
}
