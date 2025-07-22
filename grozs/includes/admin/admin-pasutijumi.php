<?php

add_action('admin_head', 'grozs_admin_head_setup_orders');
function grozs_admin_head_setup_orders() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'produkti_page_grozs_orders') {

        // 1. Noņem visus admin_notices, lai tie netiktu rādīti augšpusē
        remove_all_actions('admin_notices');

        // 2. Pievieno CSS stilus
        echo '<style>
            body.wp-admin {
                background-color: #2b2b2b !important;
            }
            #wpwrap, #wpcontent, #wpbody, #wpbody-content {
                background-color: #2b2b2b !important;
            }
            .wrap .notice, .wrap .grozs-admin-header .notice {
                display: inline-block; 
                margin: 15px 0 0 0; 
                background:#333; 
                color:#ddd; 
                border: solid 1px #444; 
                border-left: solid 4px #666;
            }
            .grozs-admin-header .notice.notice-error {
                border-left-color: #d63638;
            }
            .form-table td, .form-table th { 
                width: auto; 
                margin-bottom: 10px; 
                padding: 0 10px 0 0 !important; 
                color: #999; 
                display:inline-block; 
            }
            .form-table th { 
                color: #ddd; 
            }
            .wp-list-table thead th { 
                border-bottom: 1px solid #444 !important; 
                box-shadow: none !important; 
                color: #fff !important; 
            }
            .wp-list-table th, .wp-list-table td { 
                vertical-align: middle; 
                color: #999; 
            }
            .wp-list-table .button.button-small { 
                min-height: 20px; 
                vertical-align: middle; 
                border-color: #444; 
                color: #ddd; 
                background: #333; 
                line-height: 2; 
            }
            .wp-list-table .button.button-small.button-delete {
                color: #d63638;
            }
            .wp-list-table .button-small:hover, .wp-list-table .button-small:focus {
                border-color: #555; 
                background: #444; 
                color: #ddd; 
                box-shadow: none;
            }
            .grozs-modal-header .button.close-button { 
                padding: 0;
                background: none;
                border: solid 1px #444;
                border-radius: 50%;
                color: #666;
                font-size: 14px;
                text-align: center;
                line-height: 1;
                width: 30px;
                height: 30px; 
            }
            .grozs-modal-header .button.close-button:hover { 
                color: #999 !important; 
                border-color: #666 !important; 
            }
            #grozs-modal-content { 
                scrollbar-width: thin; 
                scrollbar-color: #444 #1e1e1e; 
            }
            #grozs-modal-content::-webkit-scrollbar { 
                width: 8px; 
            }
            #grozs-modal-content::-webkit-scrollbar-track { 
                background: #1e1e1e; 
            }
            #grozs-modal-content::-webkit-scrollbar-thumb {
                background-color: #444; 
                border-radius: 10px; 
                border: 2px solid #1e1e1e;
            }
            #wpbody, #wpbody-content { 
                background: #2b2b2b; 
            }
            #wpfooter { 
                padding: 20px; color: #999; 
                color: #999;
                background: #1e1e1e; 
                border-top: solid 1px #444; 
            }
            #wpcontent, #wpbody-content { 
                padding: 0 !important; 
            }
            ul#adminmenu .menu-icon-produkti a.wp-has-current-submenu:after { 
                border-right-color: #2b2b2b; 
            }
            .wrap {
                margin: 0;
                padding: 0;
                background: #2b2b2b;
            }
            hr {
                margin: 20px 0;
                border: none;
                border-top: solid 1px #444;
            }
            .grozs-admin-content h2 {
                margin: 0;
                color: #fff;
            }
            .border-bottom {
                border: none;
                border-bottom: solid 1px #444;
            }
            .grozs-admin-header {
                padding: 30px 25px;
                background: #1e1e1e;
                border-bottom: solid 1px #444;
            }
            .grozs-admin-header .grozs-admin-header-title {
                padding: 0;
                margin: 0;
                color: #fff;
                line-height: 1;
            }
            .grozs-admin-header .grozs-admin-header-title i {
                margin-right: 3px;
            }
            .grozs-admin-content {
                padding: 0;
                color: #999;
            }
            .grozs-content-section {
                padding: 40px 25px;
            }
            .grozs-orders-full { 
                max-width: none !important; 
                margin: 0 !important; 
                padding: 0 20px; 
            }
        </style>';
    }
}

// "Grozs" Pasūtījumi sadaļa
function grozs_admin_orders_page() {
    if (isset($_GET['delete']) && current_user_can('delete_posts')) {
        $delete_id = (int) $_GET['delete'];
        if (wp_verify_nonce($_GET['_wpnonce'], 'delete_order_' . $delete_id)) {
            wp_delete_post($delete_id, true);
            $GLOBALS['grozs_admin_notice'] = '<div class="notice notice-success"><p>Pasūtījums tika izdzēsts.</p></div>';
        } else {
            $GLOBALS['grozs_admin_notice'] = '<div class="notice notice-error"><p>Nederīgs pieprasījums. Neizdevās izdzēst.</p></div>';
        }
    }

    $orders = get_posts([
        'post_type'      => 'pasutijumi',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    ?>
    <div class="wrap">
        <div class="grozs-admin-header">
            <h1 class="grozs-admin-header-title"><i class="fa-solid fa-file-invoice"></i> Pasūtījumi</h1>
            <?php if (!empty($GLOBALS['grozs_admin_notice'])) echo $GLOBALS['grozs_admin_notice']; ?>
        </div>
        <div class="grozs-admin-content">
            <div class="grozs-content-section section-orders-table">
                <div class="pasutijumi-table">
                    <table class="wp-list-table widefat fixed striped" style="border:1px solid #444;">
                        <thead style="background:#1e1e1e;border-bottom:1px solid #444;">
                            <tr>
                                <th style="padding:12px;">Datums</th>
                                <th style="padding:12px;">Vārds</th>
                                <th style="padding:12px;">E-pasts</th>
                                <th style="padding:12px;">Telefons</th>
                                <th style="padding:12px;">Produkti</th>
                                <th style="padding:12px;">Darbības</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (empty($orders)) {
                            echo '<tr style="background:#2b2b2b;color:#fff;"><td colspan="6" style="padding:12px;">Nav neviena pasūtījuma.</td></tr>';
                        } else {
                            $i = 0;
                            foreach ($orders as $order) {
                                $vards    = get_post_meta($order->ID, 'vards', true);
                                $epasts   = get_post_meta($order->ID, 'epasts', true);
                                $telefons = get_post_meta($order->ID, 'telefons', true);

                                $grozs_items = json_decode(get_post_meta($order->ID, 'grozs', true), true);
                                $titles = [];
                                if (is_array($grozs_items)) {
                                    foreach ($grozs_items as $item) {
                                        if (!empty($item['title'])) {
                                            $titles[] = esc_html($item['title']);
                                        }
                                    }
                                }
                                $produkti = $titles ? implode(', ', $titles) : '-';
                                $bg = ($i++ % 2 === 0) ? '#2b2b2b' : '#1e1e1e';
                                ?>
                                <tr style="background:<?= esc_attr($bg) ?>;">
                                    <td style="padding:12px;"><?= esc_html(get_the_date('d.m.Y | H:i', $order)) ?></td>
                                    <td style="padding:12px;"><?= esc_html($vards) ?></td>
                                    <td style="padding:12px;"><?= esc_html($epasts) ?></td>
                                    <td style="padding:12px;"><?= esc_html($telefons) ?></td>
                                    <td style="padding:12px;"><?= $produkti ?></td>
                                    <td style="padding:12px;">
                                        <a href="#" class="button button-small button-details" onclick="return grozsOpenModal(<?= esc_js($order->ID) ?>)">Skatīt</a>
                                        <a href="<?= esc_url(wp_nonce_url(admin_url('admin.php?page=grozs_orders&delete=' . $order->ID), 'delete_order_' . $order->ID)) ?>" class="button button-small button-delete">Dzēst</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="grozs-modal" style="display:none;position:fixed;top:50px;left:50%;transform:translateX(-50%);width:80%;max-width:900px;z-index:9999;background:#1e1e1e;border:1px solid #444;box-shadow:0 5px 10px rgba(0,0,0,0.05);padding:0;">
        <div class="grozs-modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:solid 1px #444;">
            <h2 style="margin:0;padding:0; color:#fff;">Pasūtījuma detaļas</h2>
            <button class="button close-button">✕</button>
        </div>
        <div id="grozs-modal-content" style="background:#2b2b2b;color:#999;padding:0;overflow-y: auto;max-height: calc(80vh - 140px);"></div>
        <div id="grozs-modal-footer-wrapper"></div>
    </div>

    <script>
    (function($) {
        window.grozsOpenModal = function(orderId) {
            var $modal = $('#grozs-modal'),
                $content = $('#grozs-modal-content'),
                $footer = $('#grozs-modal-footer-wrapper');
            $content.html('<p style="padding:20px;">Notiek ielāde...</p>');
            $footer.html('');
            $modal.show();

            $.post(ajaxurl, {
                action: 'grozs_load_order_detail',
                order_id: orderId,
                security: '<?php echo wp_create_nonce('grozs_detail'); ?>'
            }, function(response) {
                if (response.success) {
                    $content.html(response.data.content);
                    $footer.html(response.data.footer);
                } else {
                    $content.html('<p>Kļūda: ' + response.data + '</p>');
                }
            });

            return false;
        };

        $(document).on('click', '#grozs-modal .close-button', function() {
            $('#grozs-modal').hide();
        });
    })(jQuery);
    </script>
    <?php
}

// AJAX atbildes sadalīšana content/footer
add_action('wp_ajax_grozs_load_order_detail', 'grozs_render_order_detail_ajax');
function grozs_render_order_detail_ajax() {
    check_ajax_referer('grozs_detail', 'security');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Nav piekļuves', 403);
    }

    $order_id = intval($_POST['order_id']);

    ob_start();
    grozs_render_order_detail_content($order_id);
    $content = ob_get_clean();

    ob_start();
    grozs_render_order_detail_footer($order_id);
    $footer = ob_get_clean();

    wp_send_json_success([
        'content' => $content,
        'footer'  => $footer,
    ]);
}

// MODĀLAIS SATURS (bez footer)
function grozs_render_order_detail_content($order_id) {
    $vards    = get_post_meta($order_id, 'vards', true);
    $epasts   = get_post_meta($order_id, 'epasts', true);
    $telefons = get_post_meta($order_id, 'telefons', true);
    $adrese   = get_post_meta($order_id, 'adrese', true);
    $piezimes = get_post_meta($order_id, 'piezimes', true);
    $grozs    = json_decode(get_post_meta($order_id, 'grozs', true), true);
    ?>
    <div class="form-table-container" style="padding:20px;border-bottom:solid 1px #444;">
        <table class="form-table" style="margin:0;color:#999;">
            <tr><th>Vārds:</th><td><?= esc_html($vards) ?></td></tr>
            <tr><th>E-pasts:</th><td><?= esc_html($epasts) ?></td></tr>
            <tr><th>Telefons:</th><td><?= esc_html($telefons) ?></td></tr>
            <tr><th>Adrese:</th><td><?= esc_html($adrese) ?></td></tr>
            <tr><th style="margin-bottom:0;">Piezīmes:</th><td style="margin-bottom:0;"><?= nl2br(esc_html($piezimes)) ?></td></tr>
        </table>
    </div>
    <div class="pasutitie-produkti-container" style="padding:20px;">
        <h2 style="color:#fff;margin:0 0 20px 0;">Pasūtītie produkti</h2>
        <div class="pp-wraper" style="display:flex;gap:20px;flex-wrap:wrap;">
        <?php foreach ((array) $grozs as $item): ?>
            <div style="display:flex;gap:10px;align-items:flex-start;padding:10px;color:#999;border:solid 1px #444;">
                <?php if (!empty($item['image'])): ?>
                    <img src="<?= esc_url($item['image']) ?>" alt="" style="width:80px;height:auto;border:1px solid #444;">
                <?php endif; ?>
                <div>
                    <strong><?= esc_html($item['title']) ?></strong><br>
                    Cena: €<?= esc_html($item['price']) ?>
                    <?php if (!empty($item['quantity']) && $item['quantity'] > 1): ?>
                    <strong>× <?= intval($item['quantity']) ?></strong>
                    <?php endif; ?><br>
                    <?php foreach ([
                        'krasa' => 'Krāsa',
                        'izmers' => 'Matrača izmērs',
                        'produkta_izmers' => 'Izmērs',
                        'materials' => 'Materiāls',
                        'atvilknes' => 'Atvilknes zem gultas',
                        'pacelams' => 'Paceļams matracis'
                    ] as $key => $label):
                        if (!empty($item[$key])) {
                            echo $label . ': ' . esc_html($item[$key]) . '<br>';
                        }
                    endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
    <?php
}

// MODĀLAIS FOOTER
function grozs_render_order_detail_footer($order_id) {
    $grozs = json_decode(get_post_meta($order_id, 'grozs', true), true);
    $kopaina = 0;
    foreach ((array) $grozs as $item) {
        $kopaina += floatval($item['price']) * (isset($item['quantity']) ? intval($item['quantity']) : 1);
    }
    ?>
    <div class="grozs-modal-footer" style="text-align:right;font-size:18px;font-weight:bold;color:#fff;padding:25px 20px;background:#1e1e1e;border-top:solid 1px #444;">
        Kopā: €<?= number_format($kopaina, 2, '.', '') ?>
    </div>
    <?php
}
