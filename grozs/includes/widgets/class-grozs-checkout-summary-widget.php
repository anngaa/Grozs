<?php
// === includes/widgets/class-grozs-checkout-summary-widget.php ===
namespace Includes\Widgets;

if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Grozs_Checkout_Summary_Widget extends Widget_Base {

    public function get_name() {
        return 'grozs_checkout_summary';
    }

    public function get_title() {
        return 'Grozs: Pasūtījuma kopsavilkums';
    }

    public function get_icon() {
        return 'eicon-purchase-summary';
    }

    public function get_categories() {
        return ['grozs-widgets'];
    }

    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => 'Virsraksts',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('summary_title_text', [
            'label' => 'Teksts',
            'type' => Controls_Manager::TEXT,
            'default' => 'Jūsu pasūtījums',
        ]);

        $this->add_control('summary_title_tag', [
            'label' => 'HTML selektors',
            'type' => Controls_Manager::SELECT,
            'default' => 'h5',
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

        $this->start_controls_section('section_style', [
            'label' => 'Virsraksts',
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'label' => 'Tipogrāfija',
            'name' => 'summary_title_typography',
            'fields_options' => [
                'typography' => [ 'default' => 'yes' ],
                'font_weight' => [ 'default' => 500 ],
            ],
            'selector' => '{{WRAPPER}} .checkout-sumary-tittle',
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="grozs-checkout-summary">
            <div class="checkout-sumary-header">
                <<?php echo esc_html($settings['summary_title_tag']); ?> class="checkout-sumary-tittle" style="margin: 0;"><?php echo esc_html($settings['summary_title_text']); ?></<?php echo esc_html($settings['summary_title_tag']); ?>>
            </div>
            <div id="grozs-order-summary"></div>
            <div class="grozs-order-total">
                <span class="grozs-total-text"><strong>Kopā:</strong></span> €&nbsp;
                <span id="grozs-total-sum">0.00</span>
            </div>
        </div>
        <?php
    }
}
