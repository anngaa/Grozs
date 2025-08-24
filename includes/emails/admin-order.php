<?php if (!defined('ABSPATH')) exit; ?>
<div style="width: 100%; min-width: 100%; height: 100%; background-color: #fafafa; font-family:sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" class="body" role="presentation">
        <tr>
            <td><!-- Apzināti tukšs, lai atbalstītu vienādu izmēru un izkārtojumu vairākos e-pasta klientos. --></td>
            <td align="center" valign="top" class="body-inner" width="700" style="padding: 20px 10px;">
                <div class="wrapper" width="100%">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="container" role="presentation">
                        <tr>
                            <td class="header" align="center" valign="middle" style="padding: 20px 0;">
                                <div class="header-image">
                                    <img src="https://tavagulta.lv/wp-content/uploads/2025/08/logo.png" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" style="height: 70px;" />
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td class="content" bgcolor="#ffffff" style="padding: 40px; border-radius: 10px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" >
                                    <tr>
                                        <td style="text-align: center;">
                                            <h2>Jauns pasūtījums no TavaGulta.lv</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 20px; text-align: center;">
                                            <p><strong>Pasūtījuma ID:</strong> #<?php echo esc_html($public_id); ?></p>
                                        </td>
                                    </tr>
                                    <?php if (!empty($form_data) && is_array($form_data)) : ?>
                                    <?php
                                    // Izvelkam konkrētus laukus ar drošiem noklusējumiem
                                    $vards    = isset($form_data['vards'])    ? trim((string)$form_data['vards'])    : '';
                                    $epasts   = isset($form_data['epasts'])   ? trim((string)$form_data['epasts'])   : '';
                                    $telefons = isset($form_data['telefons']) ? trim((string)$form_data['telefons']) : '';
                                    $adrese   = isset($form_data['adrese'])   ? trim((string)$form_data['adrese'])   : '';
                                    $piezimes = isset($form_data['piezimes']) ? trim((string)$form_data['piezimes']) : '';
                                    ?>
                                    <tr>
                                        <td>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                <tr>
                                                    <td width="30%" style="text-align: left;">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                            <tr>
                                                                <td style="padding-bottom:5px;"><strong>Vārds</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?php echo esc_html($vards); ?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="40%" style="padding: 0 10px; text-align: left;">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                            <tr>
                                                                <td style="padding-bottom:5px;"><strong>E-Pasts</strong></td>
                                                            </tr>
                                                            <tr>
                                                            <?php
                                                                $mailto = 'mailto:' . $epasts . '?subject=' . rawurlencode( 'Pasūtījums #' . $public_id . ' - TavaGulta.lv' );
                                                                ?>
                                                                <td><a href="<?php echo esc_attr( $mailto ); ?>"><?php echo esc_html( $epasts ); ?></a></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="30%" style="text-align: left;">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                            <tr>
                                                                <td style="text-align:left; padding-bottom:5px;"><strong>Telefons</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?php echo esc_html($telefons); ?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; padding-top: 20px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                <tr>
                                                    <td style="padding-bottom:5px;"><strong>Adrese</strong></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo nl2br(esc_html($adrese)); ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; padding-top: 20px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
                                                <tr>
                                                    <td style="padding-bottom:5px;"><strong>Piezīmes</strong></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo nl2br(esc_html($piezimes)); ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <h3 style="margin-top: 40px; font-family:sans-serif;">Pasūtītie produkti</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php $grozs_partial('items-table', ['cart' => $cart]); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 20px; text-align: left;">
                                            <p>Šis e-pasts ir sagatavots un izsūtīts automātiski no <strong><a href="https://tavagulta.lv">tavagulta.lv</a></strong>.</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td><!-- Apzināti tukšs, lai atbalstītu vienādu izmēru un izkārtojumu vairākos e-pasta klientos. --></td>
        </tr>
    </table>
</div>