<?php
// === class-grozs-pricetable-widget.php ===

namespace Includes\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

class Grozs_Pricetable_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'grozs_pricetable';
    }

    public function get_title() {
        return __( 'Grozs: Cenu tabula', 'grozs' );
    }

    public function get_icon() {
        return 'eicon-table';
    }

    public function get_categories() {
        return [ 'grozs-widgets' ];
    }

    public function get_keywords() {
        return [ 'grozs', 'price', 'table', 'product' ];
    }

    protected function render() {
        $product_id = get_the_ID();

        // Nosaka kategoriju
        $is_bed = has_term('gultas', 'kategorijas', $product_id);
        $is_nightstand = has_term('naktsskapisi', 'kategorijas', $product_id);
        $is_dresser = has_term('kumodes', 'kategorijas', $product_id);

        // Nosaka virsrakstu
        if ($is_bed) {
            $title1 = 'Matrača izmērs';
			$title2 = 'Gultas cena';
            $data_rows = get_field('matracu_izmeri', $product_id);
            $row_label = 'matraca_izmers';
            $prices = ['cena_priedei', 'cena_osim', 'cena_ozolam'];
        } elseif ($is_nightstand) {
            $title1 = 'Naktsskapīša izmērs';
			$title2 = 'Naktsskapīša cena (gabala)';
            $data_rows = get_field('produkta_izmeri_un_cenas', $product_id);
            $row_label = 'produkta_izmers';
            $prices = ['sk_cena_priedei', 'sk_cena_osim', 'sk_cena_ozolam'];
        } elseif ($is_dresser) {
            $title1 = 'Kumodes izmērs';
			$title2 = 'Kumodes cena';
            $data_rows = get_field('produkta_izmeri_un_cenas', $product_id);
            $row_label = 'produkta_izmers';
            $prices = ['sk_cena_priedei', 'sk_cena_osim', 'sk_cena_ozolam'];
        } else {
            return;
        }

        if (empty($data_rows)) return;
        ?>
        <table class="grozs-price-table" style="width:100%; margin: 0; line-height: 1; border-collapse: collapse; font-size: 15px;">
            <thead style="background:#fafafa; border:1px solid #eaeaea;">
                <tr>
                    <th rowspan="2" style="text-align:left; padding:15px; border:1px solid #eaeaea;"><?= esc_html($title1) ?></th>
                    <th colspan="3" style="text-align:center; padding:15px; border:1px solid #eaeaea;"><?= esc_html($title2) ?></th>
                </tr>
                <tr>
                    <th style="text-align:center; padding:15px; border:1px solid #eaeaea;">Priede</th>
                    <th style="text-align:center; padding:15px; border:1px solid #eaeaea;">Osis</th>
                    <th style="text-align:center; padding:15px; border:1px solid #eaeaea;">Ozols</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $row_index = 0;

                // Definē atļautos izmērus un normalizatoru tikai vienu reizi
                $allowed_sizes = [];
                if ($is_bed) {
                    $allowed_sizes = ['140x200', '160x200', '180x200'];
                    function grozs_normalize_size($size) {
                        return trim(str_replace([' ', '(cm)'], '', $size));
                    }
                }

                foreach ($data_rows as $rinda) :
                    $bg = ($row_index % 2 === 0) ? '#fff' : '#fafafa';
                    $row_class = '';
                    if ($is_bed) {
                        $current_size = grozs_normalize_size($rinda[$row_label]);
                        if (!in_array($current_size, $allowed_sizes)) {
                            $row_class = 'hide-mobile';
                        }
                    }
                ?>
                    <tr class="sizes-row <?= esc_attr($row_class) ?>" style="background:<?= $bg ?>;">
                        <td style="padding:15px; border:1px solid #eaeaea;"><?= esc_html($rinda[$row_label]) ?></td>
                        <td style="text-align:center; padding:15px; border:1px solid #eaeaea;"><?= !empty($rinda[$prices[0]]) ? esc_html($rinda[$prices[0]]) . '€' : '' ?></td>
                        <td style="text-align:center; padding:15px; border:1px solid #eaeaea;"><?= !empty($rinda[$prices[1]]) ? esc_html($rinda[$prices[1]]) . '€' : '' ?></td>
                        <td style="text-align:center; padding:15px; border:1px solid #eaeaea;"><?= !empty($rinda[$prices[2]]) ? esc_html($rinda[$prices[2]]) . '€' : '' ?></td>
                    </tr>
                <?php
                    $row_index++;
                endforeach;
                ?>

                <?php if ($is_bed) :
                    $atvilknes = [
                        'priede' => get_field('atvilknes_cena_priedei', $product_id),
                        'osis' => get_field('atvilknes_cena_osim', $product_id),
                        'ozols' => get_field('atvilknes_cena_ozolam', $product_id)
                    ];
                    if (!empty($atvilknes['priede']) || !empty($atvilknes['osis']) || !empty($atvilknes['ozols'])) :
                        $bg = ($row_index % 2 === 0) ? '#fff' : '#fafafa'; ?>
                        <tr style="background:<?= $bg ?>; border-top: solid 2px #999;">
                            <td style="padding:15px; border:1px solid #eaeaea;">Atvilknes zem gultas (komplekts)</td>
                            <td style="text-align:center; padding:15px; border:1px solid #eaeaea;"><?= esc_html($atvilknes['priede']) ?>€</td>
                            <td style="text-align:center; padding:15px; border:1px solid #eaeaea;"><?= esc_html($atvilknes['osis']) ?>€</td>
                            <td style="text-align:center; padding:15px; border:1px solid #eaeaea;"><?= esc_html($atvilknes['ozols']) ?>€</td>
                        </tr>
                        <?php $row_index++; endif;

                    $pacelejamais = [
                        'priede' => get_field('pm_cena_priedei', $product_id),
                        'osis' => get_field('pm_cena_osim', $product_id),
                        'ozols' => get_field('pm_cena_ozolam', $product_id)
                    ];
                    if (!empty($pacelejamais['priede']) || !empty($pacelejamais['osis']) || !empty($pacelejamais['ozols'])) :
                        $bg = ($row_index % 2 === 0) ? '#fff' : '#fafafa'; ?>
                        <tr style="background:<?= $bg ?>;">
                            <td style="padding:15px; border:1px solid #eaeaea;">Paceļams matracis, kaste zem gultas</td>
                            <td style="text-align:center; padding:15px; border:1px solid #eaeaea;"><?= esc_html($pacelejamais['priede']) ?>€</td>
                            <td style="text-align:center; padding:15px; border:1px solid #eaeaea;"><?= esc_html($pacelejamais['osis']) ?>€</td>
                            <td style="text-align:center; padding:15px; border:1px solid #eaeaea;"><?= esc_html($pacelejamais['ozols']) ?>€</td>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php
    }
}
