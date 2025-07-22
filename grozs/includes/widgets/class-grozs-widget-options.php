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
        $this->start_controls_section( 'section_title_style', [
            'label' => 'Etiķetes',
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .grozs-option h6',
        ] );

        $this->add_control( 'title_color', [
            'label' => 'Etiķetes krāsa',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .grozs-option h6' => 'color: {{VALUE}}',
            ],
        ] );

        $this->add_control( 'title_spacing', [
            'label' => 'Etiķetes atkāpe (px)',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 10 ],
            'selectors' => [
                '{{WRAPPER}} .grozs-option h6' => 'margin-bottom: {{SIZE}}px;',
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_option_style', [
            'label' => 'Opcijas',
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'spacing_between_options', [
            'label' => 'Atstarpe starp opcijām (px)',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 20 ],
            'selectors' => [
                '{{WRAPPER}} .grozs-option' => 'margin-bottom: {{SIZE}}px;',
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_color_style', [
            'label' => 'Krāsu rimbuļi',
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'color_circle_size', [
            'label' => 'Rimbuļa izmērs (px)',
            'type' => Controls_Manager::NUMBER,
            'default' => 30,
        ] );

        $this->add_control( 'color_circle_border_width', [
            'label' => 'Border biezums (px)',
            'type' => Controls_Manager::NUMBER,
            'default' => 2,
        ] );

        $this->add_control( 'color_circle_border_color', [
            'label' => 'Border krāsa',
            'type' => Controls_Manager::COLOR,
            'default' => '#eaeaea',
        ] );

        $this->add_control( 'color_circle_padding', [
            'label' => 'Iekšējais padding (px)',
            'type' => Controls_Manager::NUMBER,
            'default' => 2,
        ] );

        $this->add_control( 'circle_hover_border_color', [
            'label' => 'Hover border krāsa',
            'type' => Controls_Manager::COLOR,
            'default' => '#333333',
        ] );

        $this->add_control( 'circle_focus_border_color', [
            'label' => 'Focus border krāsa',
            'type' => Controls_Manager::COLOR,
            'default' => '#333333',
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

        $style_vars = sprintf(
            '--grozs-color-size:%dpx;--grozs-border-width:%dpx;--grozs-border-color:%s;--grozs-padding:%dpx;--grozs-hover-border-color:%s;--grozs-focus-border-color:%s;',
            intval( $settings['color_circle_size'] ?: 30 ),
            intval( $settings['color_circle_border_width'] ?: 2 ),
            esc_attr( $settings['color_circle_border_color'] ?: '#eaeaea' ),
            intval( $settings['color_circle_padding'] ?: 2 ),
            esc_attr( $settings['circle_hover_border_color'] ?: '#333' ),
            esc_attr( $settings['circle_focus_border_color'] ?: '#333' )
        );

        echo '<div class="grozs-options" style="border: none;' . esc_attr( $style_vars ) . '">';

        if ( $krases ) {
            echo '<div class="grozs-option"><h6>Populārākās krāsas</h6><div class="grozs-colors">';
            // Paslēptais "Izvēlēties" radio input
            echo '<input type="radio" name="grozs_krasa" id="krasa_none" value="izveleties" checked style="display:none;">';
            foreach ( $krases as $i => $krasa ) {
                $id = 'krasa_' . $i;
                echo '<label class="grozs-color-label" for="' . esc_attr( $id ) . '" title="' . esc_attr( $krasa['krasas_nosaukums'] ) . '">';
                echo '<input type="radio" name="grozs_krasa" id="' . esc_attr( $id ) . '" value="' . esc_attr( $krasa['krasas_nosaukums'] ) . '">';
                echo '<span class="grozs-color-circle" style="background-image:url(' . esc_url( $krasa['krasas_attels']['url'] ) . ');"></span>';
                echo '</label>';
            }
            echo '</div></div>';
        }

        if ($is_nightstand_or_dresser && $produkta_izmeri) {
            echo '<div class="grozs-option"><h6>Izmērs</h6><select name="grozs_produkta_izmers" id="produkta_izmers">';
			echo '<option value="izveleties" disabled selected>Izvēlēties</option>';
            foreach ( $produkta_izmeri as $item ) {
                echo '<option value="' . esc_attr( $item['produkta_izmers'] ) . '" '
                    . ' data-priede="' . esc_attr( $item['sk_cena_priedei'] ) . '"'
                    . ' data-osis="' . esc_attr( $item['sk_cena_osim'] ) . '"'
                    . ' data-ozols="' . esc_attr( $item['sk_cena_ozolam'] ) . '">' . esc_html( $item['produkta_izmers'] ) . '</option>';
            }
            echo '</select></div>';
        }

        if ( $matraci ) {
            echo '<div class="grozs-option"><h6>Matrača izmērs</h6><select name="grozs_matracis" id="izmers">';
			echo '<option value="izveleties" disabled selected>Izvēlēties</option>';
            foreach ( $matraci as $item ) {
                echo '<option value="' . esc_attr( $item['matraca_izmers'] ) . '" '
                    . ' data-priede="' . esc_attr( $item['cena_priedei'] ) . '"'
                    . ' data-osis="' . esc_attr( $item['cena_osim'] ) . '"'
                    . ' data-ozols="' . esc_attr( $item['cena_ozolam'] ) . '">' . esc_html( $item['matraca_izmers'] ) . '</option>';
            }
            echo '</select></div>';
        }

        echo '<div class="grozs-option"><h6>Materiāls</h6><select name="grozs_materials" id="material">';
        echo '<option value="izveleties" disabled selected>Izvēlēties</option>';
		echo '<option value="priede">Priede</option>';
        echo '<option value="osis">Osis (iesakām)</option>';
        echo '<option value="ozols">Ozols</option>';
        echo '</select><span style="display: block; margin-top: 5px;">* Iesakām kā materiālu izvēlēties Osi.</span></div>';

        // Renderē "Atvilknes zem gultas" tikai, ja ir aizpildītas visas cenas
		$atvilknes_ir = !empty($atvilknes['priede']) && !empty($atvilknes['osis']) && !empty($atvilknes['ozols']);
		if ($is_bed && $atvilknes_ir) {
			echo '<div class="grozs-option"><h6>Atvilknes zem gultas</h6><select name="grozs_atvilknes" id="atvilknes">';
			echo '<option value="izveleties" disabled selected>Izvēlēties</option>';
			echo '<option value="velos"'
				. ' data-priede="' . esc_attr( $atvilknes['priede'] ) . '"'
				. ' data-osis="' . esc_attr( $atvilknes['osis'] ) . '"'
				. ' data-ozols="' . esc_attr( $atvilknes['ozols'] ) . '">Vēlos</option>';
			echo '<option value="nevelos">Nevēlos</option>';
			echo '</select></div>';
		}

		// Renderē "Paceļams matracis" tikai, ja ir aizpildītas visas cenas
		$pacelejamais_ir = !empty($pacelejamais['priede']) && !empty($pacelejamais['osis']) && !empty($pacelejamais['ozols']);
		if ($is_bed && $pacelejamais_ir) {
			echo '<div class="grozs-option"><h6>Paceļams matracis</h6><select name="grozs_pacelams" id="pacelsana">';
			echo '<option value="izveleties" disabled selected>Izvēlēties</option>';
			echo '<option value="velos"'
				. ' data-priede="' . esc_attr( $pacelejamais['priede'] ) . '"'
				. ' data-osis="' . esc_attr( $pacelejamais['osis'] ) . '"'
				. ' data-ozols="' . esc_attr( $pacelejamais['ozols'] ) . '">Vēlos</option>';
			echo '<option value="nevelos">Nevēlos</option>';
			echo '</select></div>';
		}

        echo '</div>'; // .grozs-options

        ?>
        <style>
            .grozs-colors {
                display: flex;
                gap: 10px;
            }
            .grozs-color-label {
                display: block;
                cursor: pointer;
                border-radius: 50%;
                border: var(--grozs-border-width) solid var(--grozs-border-color);
            }
            .grozs-color-label input {
                display: none;
            }
            .grozs-color-circle {
                display: block;
                border-radius: 50%;
                background-size: cover;
                background-position: center;
                width: var(--grozs-color-size);
                height: var(--grozs-color-size);
                margin: var(--grozs-padding);
                box-sizing: border-box;
                transition: border-color 0.2s ease;
            }
            .grozs-color-label:has(input:checked) {
                border-color: #333;
				transition: border-color 0.2s ease;
            }
            .grozs-color-label:hover {
                border-color: #999;
            }
            .grozs-option h5 {
                margin-bottom: 10px;
            }
            .grozs-option {
                margin-bottom: 30px;
            }
        </style>
        <?php
    }
}
