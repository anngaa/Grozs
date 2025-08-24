<?php
namespace Includes\Widgets;

if (!defined('ABSPATH')) exit;

class Grozs_ArchivePrice_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'grozs_archive_price';
    }

    public function get_title() {
        return __('Grozs: Arhīva cena', 'grozs');
    }

    public function get_icon() {
        return 'eicon-price-list';
    }

    public function get_categories() {
        return ['grozs-widgets'];
    }

    public function get_keywords() {
        return ['cena', 'arhīvs', 'grozs', 'sākot no'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Cenas Attēlojums', 'grozs'),
            ]
        );

        $this->add_control(
            'label_text',
            [
                'label' => __('Teksts pirms cenas', 'grozs'),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $post_id = get_the_ID();

        // Elementor rediģēšanas režīma gadījumā
        if (!$post_id) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                $label = $this->get_settings_for_display('label_text');
                echo '<div class="grozs-archive-price">' . esc_html($label) . ' € --</div>';
            }
            return;
        }

        $label = $this->get_settings_for_display('label_text');
        $min_price = null;

        // Gultām
        $matraci = get_field('matracu_izmeri', $post_id);
        if ($matraci && is_array($matraci)) {
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

        // Skapīši / kumodes
        $skapji = get_field('produkta_izmeri_un_cenas', $post_id);
        if ($skapji && is_array($skapji)) {
            foreach ($skapji as $item) {
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

        if ($min_price !== null) {
            echo '<div class="grozs-archive-price"><span class="grozs-price-currency">' . esc_html($label) . ' €</span>' . number_format($min_price, 2, '.', '') . '</div>';
        }
    }
}
