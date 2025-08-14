<?php
namespace Grozs\Admin;

add_action('admin_head', __NAMESPACE__ . '\grozs_admin_head_setup_settings');
function grozs_admin_head_setup_settings() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'produkti_page_grozs_settings') {

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
            #wpcontent {
                padding: 0;
            }
            #wpfooter {
			padding: 20px;
			color: #999;
			background: #1e1e1e;
			border-top: solid 1px #444;
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
            .grozs-admin-content {
                padding: 0;
                color: #999;
            }
            .grozs-admin-content .button {
                border: solid 1px #333;
                color: #ddd;
                vertical-align: inherit;
            }
            .grozs-admin-content .button-primary {
                background: #333;
                border: solid 1px #444;
                color: #ddd
            }
            .grozs-admin-content .button:hover, .grozs-admin-content .button-primary:hover, .grozs-admin-content .button:focus, .grozs-admin-content .button-primary:focus {
                background: #444;
                border: solid 1px #555;
                color: #ddd;
                box-shadow: none;
            }
            .grozs-content-section {
                padding: 40px 25px;
            }
            .grozs-content-section input[type=text] {
                background: none;
                border-color: #444;
                color: #999;
            }
            .grozs-notification-toggles {
                color: #ddd;
            }
            .grozs-toggle {
                position: relative;
                display: inline-block;
                width: 40px;
                height: 20px;
                vertical-align: middle;
            }
            .grozs-toggle input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            .grozs-slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #444;
                transition: 0.4s;
                border-radius: 34px;
            }
            .grozs-slider:before {
                position: absolute;
                content: "";
                height: 14px;
                width: 14px;
                left: 3px;
                top: 3px;
                background-color: #999;
                transition: 0.4s;
                border-radius: 50%;
            }
            .grozs-toggle input:checked + .grozs-slider {
                background-color: #2271b1;
            }
            .grozs-toggle input:checked + .grozs-slider:before {
                background-color: #fff;
                transform: translateX(20px);
            }
            .custom-file-upload {
                display: inline-block;
                padding: 5px 10px;
                cursor: pointer;
                background: none;
                color: #ddd;
                border: solid 1px #444;
                border-radius: 3px;
                font-size: 13px;
                vertical-align: inherit;
            }
            .custom-file-upload:hover, .custom-file-upload:focus {
                background-color: #444;
                border-color: #555;
                color: #ddd;
            }
            input[type="file"] {
                display: none;
            }
        </style>';
    }
}

//Savieno cenu eksportēšanas html sadaļu ar pašu funkciju
add_action('admin_init', __NAMESPACE__ . '\grozs_handle_export_button');

function grozs_handle_export_button() {
    if (isset($_POST['grozs_export_csv'])) {
        grozs_export_prices_to_csv();
    }
}

add_action('admin_init', __NAMESPACE__ . '\grozs_handle_import_button');

function grozs_handle_import_button() {
    if (isset($_POST['grozs_import_csv'])) {
        grozs_import_prices_from_csv();
    }
}

// "Grozs" iestatījumu sadaļa
function grozs_admin_settings_page() {

    ?>
    <div class="wrap">
        <div class="grozs-admin-header">
            <h1 class="grozs-admin-header-title"><i class="fa-solid fa-bag-shopping"></i> Grozs</h1>
            <?php if (!empty($GLOBALS['grozs_admin_notice'])) echo $GLOBALS['grozs_admin_notice']; ?>
        </div>
        
        <div class="grozs-admin-content">
		
		<!-- ========================== -->
        <!-- === EPasta paziņojumi === -->
        <!-- ========================== -->
        <div class="grozs-content-section section-email-notifications border-bottom">
            <h2>E-Pasta Paziņojumi</h2>
            <p>Izvēlieties kādi e-pasta paziņojumi tiek sūtīti kad tiek veikts pasūtījums.</p>
			<form method="post" action="options.php" class="grozs-notification-toggles" style="display: flex; flex-direction: column; align-items: flex-start; gap: 10px;">
				<?php settings_fields('grozs_settings_group'); ?>

				<label style="display: flex; align-items: center; gap: 10px;">
					<span class="grozs-toggle">
						<input type="checkbox" name="grozs_notify_admin_email" id="grozs_notify_toggle_admin_email" value="1" <?php checked(get_option('grozs_notify_admin_email'), '1'); ?>>
						<span class="grozs-slider"></span>
					</span>
					<span for="grozs_notify_toggle_admin_email">Sūtīt paziņojumus uz Admina e-pastu</span>
				</label>

				<label style="display: flex; align-items: center; gap: 10px;">
					<span class="grozs-toggle custom-email">
						<input type="checkbox" name="grozs_notify_custom_email_enabled" id="grozs_notify_toggle_custom_email" class="grozs-toggle-custom-email" value="1" <?php checked(get_option('grozs_notify_custom_email_enabled'), '1'); ?>>
						<span class="grozs-slider"></span>
					</span>
					<span for="grozs_notify_toggle_custom_email">Sūtīt paziņojumus uz pielāgotu e-pastu</span>
				</label>

				<input type="text" id="grozs_custom_notification_email" name="grozs_custom_notification_email" style="width: 300px; margin-bottom: 10px;" value="<?php echo esc_attr(get_option('grozs_custom_notification_email')); ?>">

				<label style="display: flex; align-items: center; gap: 10px;">
					<span class="grozs-toggle">
						<input type="checkbox" name="grozs_notify_form_user_email" id="grozs_notify_toggle_form_email" value="1" <?php checked(get_option('grozs_notify_form_user_email'), '1'); ?>>
						<span class="grozs-slider"></span>
					</span>
					<span for="grozs_notify_toggle_form_email">Sūtīt paziņojumus arī formas aizpildītājiem</span>
				</label>

				<?php submit_button('Saglabāt', 'primary', '', false, ['style' => 'margin-top: 10px;']); ?>
			</form>

        </div>

        <!-- ========================== -->
        <!-- === Importēt no CSV === -->
        <!-- ========================== -->  
        
        <div class="grozs-content-section border-bottom">
            <h2>Cenu Importēšana no  CSV Faila</h2>
            <p>Augšupielādē CSV failu ar cenām, kas tiks importētas uz produktu ACF laukiem pēc produkta sluga.</p>
            <form method="post" enctype="multipart/form-data" style="margin-top: 20px;">
                <label class="custom-file-upload">
                    <input type="file" name="grozs_csv_import_file" accept=".csv" id="grozs-csv-upload">
                    Izvēlēties CSV failu
                </label>
                <span id="file-name" style="margin: 0 20px 0 5px; color: #999;">Nav izvēlēts neviens fails</span>
                <?php submit_button('Importēt cenas', 'primary', 'grozs_import_csv', false); ?>
            </form>
        </div>

        <script>
        document.getElementById('grozs-csv-upload').addEventListener('change', function(e) {
            const fileName = e.target.files.length ? e.target.files[0].name : 'Nav izvēlēts neviens fails';
            document.getElementById('file-name').textContent = fileName;
        });
        </script>
        
        <!-- ========================== -->
        <!-- === Eksportēt uz CSV === -->
        <!-- ========================== -->
        <div class="grozs-content-section section-excel-export border-bottom">
            <h2>Cenu Eksportēšana uz CSV failu</h2>
            <p>Lejupielādē CSV failu ar visu produktu cenām, balstoties uz ACF laukiem.</p>

            <form method="post" style="margin-top: 20px;">
                <?php submit_button('Eksportēt cenas', 'primary', 'grozs_export_csv', false); ?>
            </form>
        </div>
            
        </div>
    </div>
    <?php
}

//Cenu eksportēšanas uz csv funkcija
function grozs_export_prices_to_csv() {
    if (!current_user_can('manage_options')) return;

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="tg-cenu-eksports.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF"); // UTF-8 BOM

    $header = [
        'Produkta nosaukums',
        'Slug',
        'Noklusējums',
        'Matrača/Produkta izmērs',
        'Cena priedei',
        'Cena osim',
        'Cena ozolam',
        'Atvilknes cena priedei',
        'Atvilknes cena osim',
        'Atvilknes cena ozolam',
        'Cena pac. matr. priedei',
        'Cena pac. matr. osim',
        'Cena pac. matr. ozolam',
    ];
    fputcsv($output, $header, ';');

    $query = new WP_Query([
        'post_type'      => 'produkti',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ]);

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $title   = get_the_title();
        $slug    = get_post_field('post_name', $post_id);

        $matraci = get_field('matracu_izmeri', $post_id);
        $produkts_izmeri = get_field('produkta_izmeri_un_cenas', $post_id);
        $is_gulta = is_array($matraci) && count($matraci);
        $is_skapis = is_array($produkts_izmeri) && count($produkts_izmeri);

        if ($is_gulta) {
            foreach ($matraci as $rinda) {
                $row = [
                    $title,
                    $slug,
                    $rinda['default_option'] ?? false ? 'x' : '',
                    $rinda['matraca_izmers'] ?? '',
                    $rinda['cena_priedei'] ?? '',
                    $rinda['cena_osim'] ?? '',
                    $rinda['cena_ozolam'] ?? '',
                    get_field('atvilknes_cena_priedei', $post_id),
                    get_field('atvilknes_cena_osim', $post_id),
                    get_field('atvilknes_cena_ozolam', $post_id),
                    get_field('pm_cena_priedei', $post_id),
                    get_field('pm_cena_osim', $post_id),
                    get_field('pm_cena_ozolam', $post_id),
                ];
                fputcsv($output, $row, ';');
            }
        } elseif ($is_skapis) {
            foreach ($produkts_izmeri as $rinda) {
                $row = [
                    $title,
                    $slug,
                    $rinda['default_option'] ?? false ? 'x' : '',
                    $rinda['produkta_izmers'] ?? '',
                    $rinda['sk_cena_priedei'] ?? '',
                    $rinda['sk_cena_osim'] ?? '',
                    $rinda['sk_cena_ozolam'] ?? '',
                    '', '', '', '', '', '',
                ];
                fputcsv($output, $row, ';');
            }
        }
    }

    wp_reset_postdata();
    fclose($output);
    exit;
}

//Cenu importēšana no csv funkcija
function grozs_import_prices_from_csv() {
    if (!current_user_can('manage_options')) return;
    if (!isset($_FILES['grozs_csv_import_file']) || empty($_FILES['grozs_csv_import_file']['tmp_name'])) {
        $GLOBALS['grozs_admin_notice'] = '<div class="notice notice-error"><p><strong>Lūdzu, vispirms pievienojiet CSV failu importam.</strong></p></div>';
        return;
    }

    $file = $_FILES['grozs_csv_import_file']['tmp_name'];
    if (!file_exists($file)) return;

    $handle = fopen($file, 'r');
    if (!$handle) return;

    $header = fgetcsv($handle, 0, ';');
    if (substr($header[0], 0, 3) === "\xEF\xBB\xBF") {
        $header[0] = substr($header[0], 3);
    }

    // Pārbauda, vai visi nepieciešamie virsraksti ir CSV failā
    $required_headers = [
        'Produkta nosaukums', 'Slug', 'Matrača/Produkta izmērs',
        'Cena priedei', 'Cena osim', 'Cena ozolam', 'Noklusējums',
        'Atvilknes cena priedei', 'Atvilknes cena osim', 'Atvilknes cena ozolam',
        'Cena pac. matr. priedei', 'Cena pac. matr. osim', 'Cena pac. matr. ozolam'
    ];

    foreach ($required_headers as $required) {
        if (!in_array($required, $header)) {
            $GLOBALS['grozs_admin_notice'] = '<div class="notice notice-error"><p><strong>Trūkst CSV kolonnas:</strong> ' . esc_html($required) . '</p></div>';
            fclose($handle);
            return;
        }
    }

    $imported = [];

    while (($row = fgetcsv($handle, 0, ';')) !== false) {
        $data = array_combine($header, $row);
        if (!$data || empty($data['Produkta nosaukums'])) continue;

        $post = null;
        $title = trim($data['Produkta nosaukums']);
        $slug  = trim($data['Slug']);

        // Meklē vispirms pēc slug, tad pēc nosaukuma
        if (!empty($slug)) {
            $post = get_page_by_path($slug, OBJECT, 'produkti');
        }
        if (!$post) {
            $post = get_page_by_title($title, OBJECT, 'produkti');
        }
        if (!$post) continue;

        $post_id = $post->ID;

        // Nosaka, kuras kategorijas ir pie produkta
        $terms = wp_get_post_terms($post_id, 'kategorijas', ['fields' => 'slugs']);
        $is_gulta = in_array('gultas', $terms);
        $is_skapis = in_array('naktsskapji', $terms) || in_array('kumodes', $terms);

        // Inicializē, ja vajadzīgs
        if (!isset($imported[$post_id])) {
            $imported[$post_id] = [
                'matraci' => [],
                'izmeri'  => [],
                'cleared' => false
            ];
        }

        // Vienreiz attīram visus laukus
        if (!$imported[$post_id]['cleared']) {
            update_field('matracu_izmeri', [], $post_id);
            update_field('produkta_izmeri_un_cenas', [], $post_id);
            update_field('atvilknes_cena_priedei', '', $post_id);
            update_field('atvilknes_cena_osim', '', $post_id);
            update_field('atvilknes_cena_ozolam', '', $post_id);
            update_field('pm_cena_priedei', '', $post_id);
            update_field('pm_cena_osim', '', $post_id);
            update_field('pm_cena_ozolam', '', $post_id);
            $imported[$post_id]['cleared'] = true;
        }

        // === GULTA ===
        if ($is_gulta) {
            $imported[$post_id]['matraci'][] = [
                'default_option' => strtolower(trim($data['Noklusējums'])) === 'x' ? 1 : 0,
                'matraca_izmers' => $data['Matrača/Produkta izmērs'],
                'cena_priedei'   => $data['Cena priedei'],
                'cena_osim'      => $data['Cena osim'],
                'cena_ozolam'    => $data['Cena ozolam'],
            ];

            // Papildu laukus saglabājam tikai vienreiz
            if (!isset($imported[$post_id]['extra_set'])) {
                update_field('atvilknes_cena_priedei', $data['Atvilknes cena priedei'], $post_id);
                update_field('atvilknes_cena_osim',    $data['Atvilknes cena osim'], $post_id);
                update_field('atvilknes_cena_ozolam',  $data['Atvilknes cena ozolam'], $post_id);
                update_field('pm_cena_priedei',        $data['Cena pac. matr. priedei'], $post_id);
                update_field('pm_cena_osim',           $data['Cena pac. matr. osim'], $post_id);
                update_field('pm_cena_ozolam',         $data['Cena pac. matr. ozolam'], $post_id);
                $imported[$post_id]['extra_set'] = true;
            }
        }

        // === SKAPIS / KUMODE ===
        if ($is_skapis) {
            $imported[$post_id]['izmeri'][] = [
                'default_option' => strtolower(trim($data['Noklusējums'])) === 'x' ? 1 : 0,
                'produkta_izmers' => $data['Matrača/Produkta izmērs'],
                'sk_cena_priedei' => $data['Cena priedei'],
                'sk_cena_osim'    => $data['Cena osim'],
                'sk_cena_ozolam'  => $data['Cena ozolam'],
            ];
        }
    }

    // Beigās uzraksta sakrātos masīvus uz lauku
    foreach ($imported as $post_id => $values) {
        if (!empty($values['matraci'])) {
            update_field('matracu_izmeri', $values['matraci'], $post_id);
        }
        if (!empty($values['izmeri'])) {
            update_field('produkta_izmeri_un_cenas', $values['izmeri'], $post_id);
        }
    }

    fclose($handle);
    $GLOBALS['grozs_admin_notice'] = '<div class="notice notice-success"><p>Cenu imports veiksmīgi pabeigts.</p></div>';
}
