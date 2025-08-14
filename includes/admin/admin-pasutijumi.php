<?php
namespace Grozs\Admin;

use function Grozs\Emails\grozs_render_email;

add_action('admin_head', __NAMESPACE__ . '\grozs_admin_head_setup_orders');
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
            .wp-list-table .is-open td {
                color: #fff;
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
            #wpbody-content {
                padding-bottom: 65px !important;
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

            .wp-list-table tr.order-row { cursor: pointer; }
            .wp-list-table tr.order-row:hover { background: #333 !important; }

            /* Grozs pasūtījumu tabulas stili */
            .wp-list-table.grozs-orders-table {
                border:1px solid #444;
            }
            .wp-list-table.grozs-orders-table thead {
                background:#1e1e1e;
                border-bottom:1px solid #444;
            }
            .wp-list-table.grozs-orders-table th,
            .wp-list-table.grozs-orders-table td {
                padding:10px;
            }
            .wp-list-table.grozs-orders-table td.order-details-cell {
                padding:0;
            }
            .order-empty-row {
                background:#2b2b2b;
                color:#fff;
            }
            .order-details-row {
                display:none;
                background:#232323;
            }
            .order-details-cell {
                padding:0;
            }
            /* Modal un produktu detaļu stili */
            .grozs-modal-wrapper {
                display:flex;
                justify-items:stretch;
                flex-wrap:wrap;
                padding:20px;
                gap:20px;
            }
            .form-table-container {
                max-width: 30%;
                flex: 0 0 auto;
                padding:20px;
                background:#2b2b2b;
                border-radius:10px;
            }
            .form-table-container h2 {
                color:#fff;
                margin:0 0 20px 0;
            }
            .form-table {
                margin:0;
                color:#999;
            }
            .pasutitie-produkti-container {
                flex: 1 1 0;
                min-width:0;
                padding:20px;
                background:#2b2b2b;
                border-radius:10px;
            }
            .pasutitie-produkti-container h2 {
                color:#fff;
                margin:0 0 20px 0;
            }
            .pp-wraper {
                display:flex;
                gap:20px;
                flex-wrap:wrap;
            }
            .product-details-table {
                width:100%;
                margin:0;
                color:#999;
                border-collapse:collapse;
            }
            .product-details-table thead {
                background:#1e1e1e;
                border-bottom:1px solid #444;
            }
            .product-details-table th,
            .product-details-table td {
                padding:10px;
            }
            .product-details-table td.text-center,
            .product-details-table th.text-center {
                text-align:center;
            }
            .product-details-table tr.product-details-total-row {
                background:#1e1e1e;
            }
            .product-details-table tr.product-details-row {
                border-bottom:1px solid #444;
            }
            .product-image-thumb {
                width:25px;
                height:auto;
                border:1px solid #444;
            }
            .grozs-modal-footer {
                display: flex;
                gap: 5px;
                flex: 0 0 100%;
                flex-direction: row;
                justify-content: space-between;
                padding:20px;
                box-sizing:border-box;
                background:#2b2b2b;
                border-radius:10px;
            }
            .grozs-resend-buttons {
                display: flex;
                gap: 5px;
                align-items: center;
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
        'no_found_rows'  => true,
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
                    <table class="wp-list-table widefat fixed striped grozs-orders-table">
                        <thead>
                            <tr>
                                <th>Pasūtījuma ID</th>
                                <th>Vārds</th>
                                <th>E-pasts</th>
                                <th>Telefons</th>
                                <th>Produkti</th>
                                <th>Datums</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (empty($orders)) {
                            echo '<tr class="order-empty-row"><td colspan="6">Nav neviena pasūtījuma.</td></tr>';
                        } else {
                            $i = 0;
                            foreach ($orders as $order) {
                                $vards      = get_post_meta($order->ID, 'vards', true);
                                $epasts     = get_post_meta($order->ID, 'epasts', true);
                                $telefons   = get_post_meta($order->ID, 'telefons', true);
                                $public_id  = get_post_meta($order->ID, 'order_public_id', true);
                                $grozs_items= json_decode(get_post_meta($order->ID, 'grozs', true), true);
                                $titles     = [];
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
                                <tr class="order-row" data-order-id="<?= esc_attr($order->ID) ?>" style="background:<?= esc_attr($bg) ?>;">
                                    <td>#<?= $public_id ? esc_html($public_id) : '—' ?></td>
                                    <td><?= esc_html($vards) ?></td>
                                    <td><?= esc_html($epasts) ?></td>
                                    <td><?= esc_html($telefons) ?></td>
                                    <td><?= $produkti ?></td>
                                    <td><?= esc_html(get_the_date('d.m.Y | H:i', $order)) ?></td>
                                </tr>
                                <tr class="order-details-row" id="order-details-<?= esc_attr($order->ID) ?>">
                                    <td colspan="6" class="order-details-cell"></td>
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

    <!-- Pogas JavaScript funkcionalitāte pasūtījumu detalizētajam skatam -->
    <script>
    (function($) {

        const AJAXURL             = (typeof ajaxurl !== 'undefined') ? ajaxurl : '<?php echo esc_url( admin_url('admin-ajax.php') ); ?>';
        const GROZS_DETAIL_NONCE  = '<?php echo esc_js( wp_create_nonce('grozs_detail') ); ?>';
        const GROZS_RESEND_NONCE  = '<?php echo esc_js( wp_create_nonce('grozs_resend_email') ); ?>';

        function closeAllDetails() {
            $('.order-details-row').hide();
            $('.order-details-cell').html('');
            $('.order-row').removeClass('is-open');
        }

        function loadOrderDetails(orderId) {
            const $detailsRow  = $('#order-details-' + orderId);
            const $detailsCell = $detailsRow.find('.order-details-cell');

            // Ja jau atvērts – aizveram
            if ($detailsRow.is(':visible')) {
                $detailsRow.hide();
                $detailsCell.html('');
                $('[data-order-id="'+orderId+'"]').removeClass('is-open');
                return;
            }

            // Aizveram citus un atveram izvēlēto
            closeAllDetails();
            $('[data-order-id="'+orderId+'"]').addClass('is-open');
            $detailsCell.html('<div style="padding:20px;">Notiek ielāde...</div>');
            $detailsRow.show();

            $.post(AJAXURL, {
                action:   'grozs_load_order_detail',
                order_id: orderId,
                security: GROZS_DETAIL_NONCE
            }, function(response) {
                if (response && response.success) {
                    $detailsCell.html(response.data.content);
                } else {
                    const msg = (response && response.data) ? response.data : 'Nezināma kļūda';
                    $detailsCell.html('<div style="padding:20px;">Kļūda: ' + msg + '</div>');
                }
            }).fail(function(){
                $detailsCell.html('<div style="padding:20px;">Kļūda: servera kļūda.</div>');
            });
        }

        // Klikšķis pa rindu (izņemot “Dzēst” pogu vai citus linkus/inputs)
        $(document).on('click', '.order-row', function(e) {
            if ($(e.target).closest('.button-delete, a, button, input, label, select, textarea').length) {
                return; // nereaģējam, ja klikšķis ir interaktīvā elementā
            }
            const orderId = $(this).data('order-id');
            loadOrderDetails(orderId);
        });

        // Klaviatūras pieejamība: Enter/Space uz rindas
        $(document).on('keydown', '.order-row', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const orderId = $(this).data('order-id');
                loadOrderDetails(orderId);
            }
        }).on('focus', '.order-row', function() {
            // ja gribi fokusējamības stilu, vari pievienot klasi
        });

        // Ja URL ir ?open=ID, automātiski atveram šī pasūtījuma detaļas
        const openId = new URLSearchParams(window.location.search).get('open');
        if (openId && $('#order-details-' + openId).length) {
            setTimeout(function() {
                const $row = $('.order-row[data-order-id="' + openId + '"]');
                if ($row.length) $row.trigger('click');
            }, 300);
        }

        // Aizver, ja klikšķis ārpus pašas tabulas
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.wp-list-table').length) {
                closeAllDetails();
            }
        });

        // Padarām rindas fokusējamas ar TAB (pieejamība)
        $(function() {
            $('.order-row').attr('tabindex', 0);
        });

        // ===== Helpers statusam =====
        function setStatus($el, text, color) {
            $el.text(text).css('color', color);
        }

        // ===== Resend: ADMIN =====
        $(document).on('click', '.grozs-resend-admin', function(e){
            e.stopPropagation();
            const $btn    = $(this);
            const orderId = $btn.data('order-id');
            const $status = $btn.closest('.grozs-modal-footer').find('.grozs-resend-status');

            setStatus($status, 'Sūta...', '#ccc');
            $btn.prop('disabled', true);

            $.post(AJAXURL, {
                action:   'grozs_resend_admin_email',
                order_id: orderId,
                security: GROZS_RESEND_NONCE
            }, function(resp){
                if (resp && resp.success) {
                    setStatus($status, (resp.data && resp.data.message) ? resp.data.message : 'Nosūtīts.', '#9acd32');
                } else {
                    const msg = (resp && resp.data && resp.data.message) ? resp.data.message : 'Nezināma kļūda';
                    setStatus($status, 'Kļūda: ' + msg, '#d63638');
                }
            }).fail(function(){
                setStatus($status, 'Kļūda: servera kļūda.', '#d63638');
            }).always(function(){
                $btn.prop('disabled', false);
                setTimeout(function(){
                    if ($status.text().length && $status.text() !== 'Sūta...') $status.text('');
                }, 10000);
            });
        });

        // ===== Resend: KLIENTS =====
        $(document).on('click', '.grozs-resend-client', function(e){
            e.stopPropagation();
            const $btn    = $(this);
            const orderId = $btn.data('order-id');
            const $status = $btn.closest('.grozs-modal-footer').find('.grozs-resend-status');

            setStatus($status, 'Sūta...', '#ccc');
            $btn.prop('disabled', true);

            $.post(AJAXURL, {
                action:   'grozs_resend_client_email',
                order_id: orderId,
                security: GROZS_RESEND_NONCE
            }, function(resp){
                if (resp && resp.success) {
                    setStatus($status, (resp.data && resp.data.message) ? resp.data.message : 'Nosūtīts.', '#9acd32');
                } else {
                    const msg = (resp && resp.data && resp.data.message) ? resp.data.message : 'Nezināma kļūda';
                    setStatus($status, 'Kļūda: ' + msg, '#d63638');
                }
            }).fail(function(){
                setStatus($status, 'Kļūda: servera kļūda.', '#d63638');
            }).always(function(){
                $btn.prop('disabled', false);
                setTimeout(function(){
                    if ($status.text().length && $status.text() !== 'Sūta...') $status.text('');
                }, 10000);
            });
        });

    })(jQuery);
    </script>

    <?php
}

// AJAX pieprasījuma apstrāde pasūtījuma detalizētajam saturam
add_action('wp_ajax_grozs_load_order_detail', __NAMESPACE__ . '\grozs_render_order_detail_ajax');
function grozs_render_order_detail_ajax() {
    check_ajax_referer('grozs_detail', 'security');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Nav piekļuves', 403);
    }

    $order_id = intval($_POST['order_id']);

    ob_start();
    grozs_render_order_detail_content($order_id);
    $content = ob_get_clean();

    wp_send_json_success([
        'content' => $content,
    ]);
}

// Resend: ADMIN
add_action('wp_ajax_grozs_resend_admin_email', __NAMESPACE__ . '\grozs_resend_admin_email');
function grozs_resend_admin_email() {
    check_ajax_referer('grozs_resend_email', 'security');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Nav piekļuves.'], 403);
    }

    $order_id = (int) ($_POST['order_id'] ?? 0);
    if (!$order_id || get_post_type($order_id) !== 'pasutijumi') {
        wp_send_json_error(['message' => 'Nederīgs pasūtījuma ID.'], 400);
    }

    $payload = grozs_collect_order_payload($order_id);
    $html    = grozs_render_email('admin-order', $payload);

    if ($html === '') {
        wp_send_json_error(['message' => 'Neizdevās ģenerēt e-pasta veidni.'], 500);
    }

    $headers = grozs_email_headers();
    $sent_to = [];

    // tā pati loģika kā handle-order.php
    if (get_option('grozs_notify_admin_email')) {
        $admin_email = get_option('admin_email');
        if (is_email($admin_email)) {
            if (wp_mail($admin_email, 'Jauns pasūtījums - TavaGulta.lv', $html, $headers)) {
                $sent_to[] = $admin_email;
            }
        }
    }

    if (get_option('grozs_notify_custom_email_enabled') && is_email(get_option('grozs_custom_notification_email'))) {
        $custom = get_option('grozs_custom_notification_email');
        if (wp_mail($custom, 'Jauns pasūtījums - TavaGulta.lv', $html, $headers)) {
            $sent_to[] = $custom;
        }
    }

    if (empty($sent_to)) {
        wp_send_json_error(['message' => 'Sūtīšana atslēgta vai adreses nav derīgas. Pārbaudi Grozs iestatījumus.']);
    }

    wp_send_json_success([
        'message' => 'Admina e-pasts nosūtīts: ' . esc_html(implode(', ', $sent_to)),
    ]);
}

// Resend: KLIENTS
add_action('wp_ajax_grozs_resend_client_email', __NAMESPACE__ . '\grozs_resend_client_email');
function grozs_resend_client_email() {
    check_ajax_referer('grozs_resend_email', 'security');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Nav piekļuves.'], 403);
    }

    $order_id = (int) ($_POST['order_id'] ?? 0);
    if (!$order_id || get_post_type($order_id) !== 'pasutijumi') {
        wp_send_json_error(['message' => 'Nederīgs pasūtījuma ID.'], 400);
    }

    if (!get_option('grozs_notify_form_user_email')) {
        wp_send_json_error(['message' => 'Klienta e-pasta sūtīšana ir atslēgta Grozs iestatījumos.']);
    }

    $payload = grozs_collect_order_payload($order_id);
    $html    = grozs_render_email('client-order', $payload);

    if ($html === '') {
        wp_send_json_error(['message' => 'Neizdevās ģenerēt e-pasta veidni.'], 500);
    }

    $to = $payload['form_data']['epasts'] ?? '';
    if (!is_email($to)) {
        wp_send_json_error(['message' => 'Pasūtījumam nav derīga klienta e-pasta adrese.']);
    }

    $headers = grozs_email_headers();
    if (!wp_mail($to, 'Pasūtījums - TavaGulta.lv', $html, $headers)) {
        wp_send_json_error(['message' => 'Neizdevās nosūtīt e-pastu.']);
    }

    wp_send_json_success([
        'message' => 'Klienta e-pasts nosūtīts: ' . esc_html($to),
    ]);
}

/**
 * Savāc ordera datus e-pastam no CPT
 */
function grozs_collect_order_payload(int $order_id): array {
    $form_data = [
        'vards'    => (string) get_post_meta($order_id, 'vards', true),
        'epasts'   => (string) get_post_meta($order_id, 'epasts', true),
        'telefons' => (string) get_post_meta($order_id, 'telefons', true),
        'adrese'   => (string) get_post_meta($order_id, 'adrese', true),
        'piezimes' => (string) get_post_meta($order_id, 'piezimes', true),
    ];
    $cart       = json_decode((string) get_post_meta($order_id, 'grozs', true), true) ?: [];
    $public_id  = get_post_meta($order_id, 'order_public_id', true);

    return [
        'public_id' => $public_id,
        'form_data' => $form_data,
        'cart'      => $cart,
    ];
}

/**
 * Vienoti e-pasta headeri
 */
function grozs_email_headers(): array {
    return [
        'Content-Type: text/html; charset=UTF-8',
        'From: TavaGulta.lv <no-reply@tavagulta.lv>',
    ];
}

// Pasūtījuma detalizētais saturs
function grozs_render_order_detail_content($order_id) {
    $vards    = get_post_meta($order_id, 'vards', true);
    $epasts   = get_post_meta($order_id, 'epasts', true);
    $telefons = get_post_meta($order_id, 'telefons', true);
    $adrese   = get_post_meta($order_id, 'adrese', true);
    $piezimes = get_post_meta($order_id, 'piezimes', true);
    $grozs    = json_decode(get_post_meta($order_id, 'grozs', true), true);

    // Aprēķina kopējo cenu
    $kopaina = 0.0;
    foreach ((array) $grozs as $item) {
        $qty = isset($item['quantity']) ? (int)$item['quantity'] : 1;
        $kopaina += (float)$item['price'] * max(1, $qty);
    }

    // Kopējais daudzums ar noklusēto 1, ja nav norādīts quantity
    $total_qty = 0;
    foreach ((array) $grozs as $it) {
        $total_qty += (int)($it['quantity'] ?? 1);
    }
    ?>

    <div class="grozs-modal-wrapper">
        <div class="form-table-container">
            <h2>Klienta dati</h2>
            <table class="form-table">
                <tr><th>Vārds:</th><td><?= esc_html($vards) ?></td></tr>
                <tr><th>E-pasts:</th><td><?= esc_html($epasts) ?></td></tr>
                <tr><th>Telefons:</th><td><?= esc_html($telefons) ?></td></tr>
                <tr><th>Adrese:</th><td><?= esc_html($adrese) ?></td></tr>
                <tr><th style="margin-bottom:0;">Piezīmes:</th><td style="margin-bottom:0;"><?= nl2br(esc_html($piezimes)) ?></td></tr>
            </table>
        </div>
        <div class="pasutitie-produkti-container">
            <h2>Pasūtītie produkti</h2>
            <div class="pp-wraper">
                <table class="product-details-table">
                    <thead>
                        <tr>
                            <th>Produkts</th>
                            <th>Produkta detaļas</th>
                            <th class="text-center">Daudzums</th>
                            <th class="text-center">Cena</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ((array) $grozs as $item): ?>
                        <tr class="product-details-row">
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?= esc_url($item['image']) ?>" alt="" class="product-image-thumb">
                                    <?php endif; ?>
                                    <strong><?= esc_html($item['title']) ?></strong>
                                </div>
                            </td>
                            <td>
                                <?php
                                $details = [];
                                if (!empty($item['krasa']))           $details[] = esc_html($item['krasa']);
                                if (!empty($item['izmers']))          $details[] = esc_html($item['izmers']);
                                if (!empty($item['produkta_izmers'])) $details[] = esc_html($item['produkta_izmers']);
                                if (!empty($item['materials']))       $details[] = esc_html($item['materials']);
                                if (!empty($item['atvilknes']) && $item['atvilknes'] === 'Vēlos') $details[] = 'Atvilknes zem gultas';
                                if (!empty($item['pacelams']) && $item['pacelams'] === 'Vēlos')   $details[] = 'Paceļams matracis';
                                echo implode(', ', $details);
                                ?>
                            </td>
                            <td class="text-center">
                                <?= !empty($item['quantity']) ? intval($item['quantity']) : 1 ?>
                            </td>
                            <td class="text-center">
                                €<?= esc_html($item['price']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="product-details-total-row">
                        <td colspan="2"><strong>Kopā:</strong></td>
                        <td class="text-center"><strong><?= (int) $total_qty ?></strong></td>
                        <td class="text-center"><strong>€<?= number_format($kopaina, 2, '.', '') ?></strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="grozs-modal-footer">

            <div class="grozs-resend-buttons">
                <button type="button" class="button button-small grozs-resend-admin"  data-order-id="<?= (int) $order_id; ?>">Atkārtoti nosūtīt admina e-pastu</button>
                <button type="button" class="button button-small grozs-resend-client" data-order-id="<?= (int) $order_id; ?>">Atkārtoti nosūtīt klienta e-pastu</button>
                <span class="grozs-resend-status" style="margin-left:10px;color:#9acd32;"></span>
            </div>

            <a href="<?= esc_url( wp_nonce_url( admin_url('admin.php?page=grozs_orders&delete=' . $order_id), 'delete_order_' . $order_id ) ) ?>" class="button button-small button-delete">Dzēst pasūtījumu</a>

        </div>
    </div>
    <?php
}