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
                                            <h2>Paldies par pasūtījumu!</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 20px; text-align: center;">
                                            <p>Mēs ar Jums sazināsimies, tiklīdz būsim to izskatījuši.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 20px; text-align: center;">
                                            <p><strong>Jūsu pasūtījuma ID:</strong> #<?php echo esc_html($public_id); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">
                                            <h3 style="margin-top: 20px; font-family:sans-serif;">Pasūtītie produkti</h3>
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