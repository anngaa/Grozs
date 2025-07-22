<?php
// === includes/widgets/class-grozs-checkout-form-widget.php ===
namespace Includes\Widgets;

if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Grozs_Checkout_Form_Widget extends Widget_Base {

    public function get_name() {
        return 'grozs_checkout_form';
    }

    public function get_title() {
        return 'Grozs: Pasūtīšanas forma';
    }

    public function get_icon() {
        return 'eicon-checkout';
    }

    public function get_categories() {
        return ['grozs-widgets'];
    }

    protected function register_controls() {
        $this->start_controls_section('section_tittle_content', [
            'label' => 'Pasūtīšanas forma',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('checkout_form_title_text', [
            'label' => 'Virsraksts',
            'type' => Controls_Manager::TEXT,
            'default' => 'Pasūtījuma noformēšana',
            'label_block' => true,
        ]);

        $this->add_control('checkout_form_description', [
            'label' => 'Apraksts',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Mums ir nepieciešami daži dati par jums, lai mēs varētu apstrādāt jūsu pasūtījumu un sazināties ar Jums.',
        ]);

        $this->add_control('checkout_form_button_text', [
            'label' => 'Pogas teksts',
            'type' => Controls_Manager::TEXT,
            'default' => 'Veikt pasūtījumu',
            'label_block' => true,
        ]);

        $this->add_control('checkout_form_title_tag', [
            'label' => 'Virsraksta HTML selektors',
            'type' => Controls_Manager::SELECT,
            'default' => 'h4',
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_tittle_style', [
            'label' => 'Virsraksts',
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'label' => 'Tipogrāfija',
            'name' => 'checkout_form_title_typography',
            'fields_options' => [
                'typography' => [ 'default' => 'yes' ],
                'font_weight' => [ 'default' => 500 ],
            ],
            'selector' => '{{WRAPPER}} .checkout-form-tittle',
        ] );

        $this->add_control( 'checkout-form-tittle_color', [
            'label' => 'Krāsa',
            'type' => Controls_Manager::COLOR,
            'default' => '#333',
            'selectors' => [
                '{{WRAPPER}} .checkout-form-tittle' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'checkout_form_title_spacing', [
            'label' => 'Atkāpe (px)',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 20 ],
            'selectors' => [
                '{{WRAPPER}} .checkout-form-tittle' => 'margin-bottom: {{SIZE}}px;',
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section('section_description_style', [
            'label' => 'Apraksts',
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'label' => 'Tipogrāfija',
            'name' => 'checkout_form_description_typography',
            'selector' => '{{WRAPPER}} .checkout-form-description',
        ] );

        $this->add_control( 'checkout_form_description_color', [
            'label' => 'Krāsa',
            'type' => Controls_Manager::COLOR,
            'default' => '#666',
            'selectors' => [
                '{{WRAPPER}} .checkout-form-description' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'checkout_form_description_spacing', [
            'label' => 'Atkāpe (px)',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 40 ],
            'selectors' => [
                '{{WRAPPER}} .checkout-form-description' => 'padding-bottom: {{SIZE}}px;',
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section('section_form_style', [
            'label' => 'Forma',
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control( 'checkout_form_label_spacing', [
            'label' => 'Etiķetes atkāpe (px)',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 5 ],
            'selectors' => [
                '{{WRAPPER}} #grozs-order-form label input, {{WRAPPER}} #grozs-order-form label textarea' => 'margin-top: {{SIZE}}px;',
            ],
        ] );

        $this->add_control( 'checkout_form_field_spacing', [
            'label' => 'Formas lauku atstarpe (px)',
            'type' => Controls_Manager::SLIDER,
            'default' => [ 'size' => 20 ],
            'selectors' => [
                '{{WRAPPER}} #grozs-order-form' => 'gap: {{SIZE}}px;',
                '{{WRAPPER}} #grozs-order-form .grozs-order-form-footer' => 'padding-top: {{SIZE}}px;',
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section('section_button_style', [
            'label' => 'Poga',
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('submit_button_padding', [
            'label' => 'Pogas iekšējā atkāpe',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'default' => [
                'top' => 15,
                'right' => 30,
                'bottom' => 15,
                'left' => 30,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .grozs-submit-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div id="grozs-checkout-form-wrapper" class="grozs-checkout-form">
            <<?php echo esc_html($settings['checkout_form_title_tag']); ?> class="checkout-form-tittle"><?php echo esc_html($settings['checkout_form_title_text']); ?></<?php echo esc_html($settings['checkout_form_title_tag']); ?>>
            <p class="checkout-form-description"><?php echo esc_html($settings['checkout_form_description']); ?></p>
            <form id="grozs-order-form" style="display: flex; flex-direction: column;">
                <label>Vārds <span style="color: red;">*</span><input type="text" name="vards" required></label>
                <label>Telefons <span style="color: red;">*</span><input type="tel" name="telefons" required></label>
                <label>E-Pasts <span style="color: red;">*</span><input type="email" name="epasts" required></label>
                <label>Piegādes Adrese<input type="text" name="adrese"></label>
                <label>Piezīmes<textarea name="piezimes" rows="6" style="height: auto !Important;"></textarea></label>
                <div class="grozs-order-form-footer" style="border-top: solid 1px #eaeaea;">
                    <button type="submit" class="grozs-submit-button"><?php echo esc_html($settings['checkout_form_button_text']); ?></button>
                </div>
            </form>
        </div>
		<div class="grozs-form-response" style="margin-top: 20px;"></div>
        <?php
    }
}