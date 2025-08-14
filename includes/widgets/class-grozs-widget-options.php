<?php
// === class-grozs-widget-options.php ===

namespace Includes\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Grozs_Widget_Options extends Widget_Base {

    public function get_name() {
        return 'grozs_options';
    }

    public function get_title() {
        return 'Grozs: Produkta Opcijas';
    }

    public function get_icon() {
        return 'eicon-select';
    }

    public function get_categories() {
        return [ 'grozs-widgets' ];
    }

    public function get_keywords() {
        return [ 'grozs', 'opcijas', 'produkts' ];
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
            'label' => 'Etiķetes',
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .grozs-option h6',
        ] );

        $this->add_responsive_control( 'title_color', [
            'label' => 'Etiķetes krāsa',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .grozs-option h6' => 'color: {{VALUE}}',
            ],
        ] );

        // Atkāpe: responsīvs slīdnis
        $this->add_responsive_control( 'title_spacing', [
            'label' => 'Etiķetes atkāpe (px)',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default' => [ 'size' => 5, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .grozs-option' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_option_style', [
            'label' => 'Opcijas',
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'spacing_between_options', [
            'label' => 'Atstarpe starp opcijām (px)',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
            'default' => [ 'size' => 20, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}} .grozs-options' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_color_style', [
            'label' => 'Krāsu rimbuļi',
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        // Šeit izmantojam CSS mainīgos, ko vēlāk pielietojam savā CSS.
        $this->add_responsive_control( 'color_circle_size', [
            'label' => 'Rimbuļa izmērs',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
            'default' => [ 'size' => 25, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--grozs-circle-size: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( '_color_circle_spacing', [
            'label' => 'Atstarpe starp rimbuļiem',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
            'default' => [ 'size' => 5, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--grozs-circle-spacing: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'color_circle_border_width', [
            'label' => 'Border biezums',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 10 ] ],
            'default' => [ 'size' => 2, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--grozs-circle-border-width: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'color_circle_border_color', [
            'label' => 'Border krāsa',
            'type' => Controls_Manager::COLOR,
            'default' => '#eaeaea',
            'selectors' => [
                '{{WRAPPER}}' => '--grozs-circle-border-color: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'color_circle_padding', [
            'label' => 'Iekšējais padding',
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 10 ] ],
            'default' => [ 'size' => 2, 'unit' => 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--grozs-circle-padding: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'circle_hover_border_color', [
            'label' => 'Hover border krāsa',
            'type' => Controls_Manager::COLOR,
            'default' => '#999999',
            'selectors' => [
                '{{WRAPPER}}' => '--grozs-circle-hover-border-color: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'circle_focus_border_color', [
            'label' => 'Focus border krāsa',
            'type' => Controls_Manager::COLOR,
            'default' => '#333333',
            'selectors' => [
                '{{WRAPPER}}' => '--grozs-circle-focus-border-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id = get_the_ID();

        // === Drošinātājs: pārbauda, vai ir iespējams aprēķināt primāro cenu ===
        $terms = get_the_terms($post_id, 'kategorijas');
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

        $has_valid_price = false;

        if ($is_bed) {
            if (have_rows('matracu_izmeri', $post_id)) {
                while (have_rows('matracu_izmeri', $post_id)) {
                    the_row();
                    $izmers = get_sub_field('matraca_izmers');
                    $priede = get_sub_field('cena_priedei');
                    $osis = get_sub_field('cena_osim');
                    $ozols = get_sub_field('cena_ozolam');
                    if ($izmers && $priede && $osis && $ozols) {
                        $has_valid_price = true;
                        break;
                    }
                }
            }
        }

        if ($is_nightstand_or_dresser) {
            if (have_rows('produkta_izmeri_un_cenas', $post_id)) {
                while (have_rows('produkta_izmeri_un_cenas', $post_id)) {
                    the_row();
                    $izmers = get_sub_field('produkta_izmers');
                    $priede = get_sub_field('sk_cena_priedei');
                    $osis = get_sub_field('sk_cena_osim');
                    $ozols = get_sub_field('sk_cena_ozolam');
                    if ($izmers && $priede && $osis && $ozols) {
                        $has_valid_price = true;
                        break;
                    }
                }
            }
        }

        if (! $has_valid_price) {
            return;
        }

        $krases = get_field( 'produktu_krasas', $post_id );
        $matraci = get_field( 'matracu_izmeri', $post_id );
        $produkta_izmeri = get_field('produkta_izmeri_un_cenas', $post_id);
        $atvilknes = [
            'priede' => get_field( 'atvilknes_cena_priedei', $post_id ),
            'osis'   => get_field( 'atvilknes_cena_osim', $post_id ),
            'ozols'  => get_field( 'atvilknes_cena_ozolam', $post_id ),
        ];
        $pacelejamais = [
            'priede' => get_field( 'pm_cena_priedei', $post_id ),
            'osis'   => get_field( 'pm_cena_osim', $post_id ),
            'ozols'  => get_field( 'pm_cena_ozolam', $post_id ),
        ];

        // PIEVIENOJAM data-grozs-group HTML atribūtu
        $group = !empty($settings['grozs_group']) ? $settings['grozs_group'] : 'pc';

        echo '<div class="grozs-options" data-grozs-group="' . esc_attr($group) . '">';

        if ( $krases ) {
            echo '<div class="grozs-option grozs-option-colors"><div class="grozs-option-label"><h6>Populārākās krāsas:</h6></div><div class="grozs-option-input" style="min-width: auto;"><div class="grozs-colors">';
            // Paslēptais "Izvēlēties" radio input
            echo '<input type="radio" name="grozs_krasa" value="izveleties" checked style="display:none;">';
            foreach ( $krases as $i => $krasa ) {
                echo '<label class="grozs-color-label" title="' . esc_attr( $krasa['krasas_nosaukums'] ) . '">';
                echo '<input type="radio" name="grozs_krasa" value="' . esc_attr( $krasa['krasas_nosaukums'] ) . '">';
                echo '<span class="grozs-color-circle" style="background-image:url(' . esc_url( $krasa['krasas_attels']['url'] ) . ');"></span>';
                echo '<span class="grozs-color-preview" aria-hidden="true" style="background-image:url(' . esc_url( $krasa['krasas_attels']['url'] ) . ');"></span>';
                echo '</label>';
            }
            echo '</div></div></div>';
        }

        if ($is_nightstand_or_dresser && $produkta_izmeri) {
            echo '<div class="grozs-option"><div class="grozs-option-label"><h6>Izmērs:</h6></div><div class="grozs-option-input"><select name="grozs_produkta_izmers" class="grozs-produkta-izmers">';
            echo '<option value="izveleties" disabled selected>Izvēlēties</option>';
            foreach ( $produkta_izmeri as $item ) {
                echo '<option value="' . esc_attr( $item['produkta_izmers'] ) . '" '
                    . ' data-priede="' . esc_attr( $item['sk_cena_priedei'] ) . '"'
                    . ' data-osis="' . esc_attr( $item['sk_cena_osim'] ) . '"'
                    . ' data-ozols="' . esc_attr( $item['sk_cena_ozolam'] ) . '">' . esc_html( $item['produkta_izmers'] ) . '</option>';
            }
            echo '</select></div></div>';
        }

        if ( $matraci ) {
            echo '<div class="grozs-option"><div class="grozs-option-label"><h6>Matrača izmērs:</h6></div><div class="grozs-option-input"><select name="grozs_matracis" class="grozs-izmers">';
            echo '<option value="izveleties" disabled selected>Izvēlēties</option>';
            foreach ( $matraci as $item ) {
                echo '<option value="' . esc_attr( $item['matraca_izmers'] ) . '" '
                    . ' data-priede="' . esc_attr( $item['cena_priedei'] ) . '"'
                    . ' data-osis="' . esc_attr( $item['cena_osim'] ) . '"'
                    . ' data-ozols="' . esc_attr( $item['cena_ozolam'] ) . '">' . esc_html( $item['matraca_izmers'] ) . '</option>';
            }
            echo '</select></div></div>';
        }

        echo '<div class="grozs-option"><div class="grozs-option-label"><h6>Materiāls:</h6></div><div class="grozs-option-input"><select name="grozs_materials" class="grozs-material">';
        echo '<option value="izveleties" disabled selected>Izvēlēties</option>';
        echo '<option value="priede">Priede</option>';
        echo '<option value="osis">Osis (iesakām)</option>';
        echo '<option value="ozols">Ozols</option>';
        echo '</select></div></div>';

        $atvilknes_ir = !empty($atvilknes['priede']) && !empty($atvilknes['osis']) && !empty($atvilknes['ozols']);
        if ($is_bed && $atvilknes_ir) {
            echo '<div class="grozs-option"><div class="grozs-option-label"><h6>Atvilknes zem gultas:</h6></div><div class="grozs-option-input"><select name="grozs_atvilknes" class="grozs-atvilknes">';
            echo '<option value="izveleties" disabled selected>Izvēlēties</option>';
            echo '<option value="velos"'
                . ' data-priede="' . esc_attr( $atvilknes['priede'] ) . '"'
                . ' data-osis="' . esc_attr( $atvilknes['osis'] ) . '"'
                . ' data-ozols="' . esc_attr( $atvilknes['ozols'] ) . '">Vēlos</option>';
            echo '<option value="nevelos">Nevēlos</option>';
            echo '</select></div></div>';
        }

        $pacelejamais_ir = !empty($pacelejamais['priede']) && !empty($pacelejamais['osis']) && !empty($pacelejamais['ozols']);
        if ($is_bed && $pacelejamais_ir) {
            echo '<div class="grozs-option"><div class="grozs-option-label"><h6>Paceļams matracis:</h6></div><div class="grozs-option-input"><select name="grozs_pacelams" class="grozs-pacelams">';
            echo '<option value="izveleties" disabled selected>Izvēlēties</option>';
            echo '<option value="velos"'
                . ' data-priede="' . esc_attr( $pacelejamais['priede'] ) . '"'
                . ' data-osis="' . esc_attr( $pacelejamais['osis'] ) . '"'
                . ' data-ozols="' . esc_attr( $pacelejamais['ozols'] ) . '">Vēlos</option>';
            echo '<option value="nevelos">Nevēlos</option>';
            echo '</select></div></div>';
        }

        echo '</div>'; // .grozs-options

        ?>
        <style>
            .grozs-colors {
                display: flex;
                gap: var(--grozs-circle-spacing);
            }
            .grozs-color-label {
                display: block;
                position: relative;
                cursor: pointer;
                border-radius: 50%;
                border: var(--grozs-circle-border-width) solid var(--grozs-circle-border-color);
            }
            .grozs-color-label input {
                display: none;
            }
            .grozs-color-circle {
                display: block;
                border-radius: 50%;
                background-size: cover;
                background-position: center;
                width: var(--grozs-circle-size);
                height: var(--grozs-circle-size);
                margin: var(--grozs-circle-padding);
                box-sizing: border-box;
                transition: border-color 0.2s ease;
            }
            .grozs-color-label:has(input:checked) {
                border-color: var(--grozs-circle-focus-border-color);
                transition: border-color 0.2s ease;
            }
            .grozs-color-label:hover {
                border-color: var(--grozs-circle-hover-border-color);
            }
            .grozs-color-preview {
                opacity: 0;
                visibility: hidden;
                position: absolute;
                top: -50%;
                left: 50%;
                transform: translate(-50%, -100%) scale(0.5);
                width: 100px;
                height: 100px;
                border-radius: 50%;
                background-size: cover;
                background-position: center;
                border: 1px solid #eaeaea;
                box-shadow: 0 2px 6px rgba(0,0,0,.10);
                z-index: 50;
                transition:
                opacity 160ms ease,
                transform 220ms ease,
                visibility 220ms; /* uzreiz kļūst “redzams” */
            }
            .grozs-color-label:hover .grozs-color-preview {
                opacity: 1;
                visibility: visible;
                transform: translate(-50%, -100%) scale(1);
                transition:
                opacity 160ms ease,
                transform 220ms ease,
                visibility 0s; /* uzreiz kļūst “redzams” */
            }
            .grozs-options {
                display: flex;
                flex-direction: column;
                --grozs-circle-size: 25px;
                --grozs-circle-spacing: 5px;
                --grozs-circle-border-width: 2px;
                --grozs-circle-border-color: #eaeaea;
                --grozs-circle-padding: 2px;
                --grozs-circle-hover-border-color: #999999;
                --grozs-circle-focus-border-color: #333333;
            }
            .grozs-option {
                min-height: 50px;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
            }
            .grozs-option-label {
                display: flex;
                align-items: center;
            }
            .grozs-option-label h6 {
                margin: 0 !important;
                padding: 0 !important;
            }

            .grozs-option-input {
                min-width: 50%;
            }

            @media (max-width: 1024px) {
                .grozs-option {
                    flex-direction: column;
                    align-items: flex-start;;
                }
                .grozs-option-input {
                    width: 100%;
                }
            }
        </style>
        <?php
    }
}
