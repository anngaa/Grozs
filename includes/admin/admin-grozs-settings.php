<?php
namespace Grozs\Admin;

add_action('admin_head', __NAMESPACE__ . '\grozs_admin_head_setup_settings');
function grozs_admin_head_setup_settings() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'produkti_page_grozs_settings') {

        // 1. Noņem visus admin_notices, lai tie netiktu rādīti augšpusē
        remove_all_actions('admin_notices');

        // 2. Ielādē administrācijas CSS no assets — izdrukā saiti head, lai stili būtu pieejami uzreiz
        $css_file = plugin_dir_path(__FILE__) . '../assets/css/admin.css';
        $css_url  = plugin_dir_url(__FILE__) . '../assets/css/admin.css';
        $ver = file_exists($css_file) ? filemtime($css_file) : null;
        if ($ver) {
            echo '<link rel="stylesheet" href="' . esc_url( $css_url . '?ver=' . $ver ) . '" />';
        } else {
            echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '" />';
        }
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
            <form method="post" action="options.php" class="grozs-notification-toggles grozs-form--stacked">
				<?php settings_fields('grozs_settings_group'); ?>

                <label class="grozs-form-row">
					<span class="grozs-toggle">
						<input type="checkbox" name="grozs_notify_admin_email" id="grozs_notify_toggle_admin_email" value="1" <?php checked(get_option('grozs_notify_admin_email'), '1'); ?>>
						<span class="grozs-slider"></span>
					</span>
					<span for="grozs_notify_toggle_admin_email">Sūtīt paziņojumus uz Admina e-pastu</span>
				</label>

                <label class="grozs-form-row">
					<span class="grozs-toggle custom-email">
						<input type="checkbox" name="grozs_notify_custom_email_enabled" id="grozs_notify_toggle_custom_email" class="grozs-toggle-custom-email" value="1" <?php checked(get_option('grozs_notify_custom_email_enabled'), '1'); ?>>
						<span class="grozs-slider"></span>
					</span>
					<span for="grozs_notify_toggle_custom_email">Sūtīt paziņojumus uz pielāgotu e-pastu</span>
				</label>

                <input type="text" id="grozs_custom_notification_email" name="grozs_custom_notification_email" class="grozs-input grozs-input--wide" value="<?php echo esc_attr(get_option('grozs_custom_notification_email')); ?>">

                <label class="grozs-form-row">
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
            <form method="post" enctype="multipart/form-data" class="grozs-form--spaced">
                <label class="custom-file-upload">
                    <input type="file" name="grozs_csv_import_file" accept=".csv" id="grozs-csv-upload">
                    Izvēlēties CSV failu
                </label>
                <span id="file-name" class="grozs-file-name">Nav izvēlēts neviens fails</span>
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

            <form method="post" class="grozs-form--spaced">
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

    // Notīrām jebkādu buferi, lai pirms headeriem netiktu izdrukāts saturs
    if (function_exists('ob_get_level')) {
        while (ob_get_level() > 0) { @ob_end_clean(); }
    }

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

    $query = new \WP_Query([
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
            // Papildu lauki (Atvilknes un Paceļams matracis) drukājas tikai pirmajai rindai katram produktam
            $extras_written = false;
            foreach ($matraci as $rinda) {
                $extras = [];
                if (!$extras_written) {
                    $extras = [
                        get_field('atvilknes_cena_priedei', $post_id),
                        get_field('atvilknes_cena_osim', $post_id),
                        get_field('atvilknes_cena_ozolam', $post_id),
                        get_field('pm_cena_priedei', $post_id),
                        get_field('pm_cena_osim', $post_id),
                        get_field('pm_cena_ozolam', $post_id),
                    ];
                    $extras_written = true;
                } else {
                    // Tukšas kolonnas, lai neradītu mulsumu - šie lauki nav atkarīgi no izmēra
                    $extras = ['', '', '', '', '', ''];
                }

                $row = [
                    $title,
                    $slug,
                    $rinda['default_option'] ?? false ? 'x' : '',
                    $rinda['matraca_izmers'] ?? '',
                    $rinda['cena_priedei'] ?? '',
                    $rinda['cena_osim'] ?? '',
                    $rinda['cena_ozolam'] ?? '',
                    ...$extras,
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
        $is_skapis = in_array('naktsskapisi', $terms) || in_array('kumodes', $terms);

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

            // Papildu laukus saglabājam tikai vienreiz (un tikai, ja vērtības nav tukšas)
            if (!isset($imported[$post_id]['extra_set'])) {
                $has_any_extra = false;
                $extra_map = [
                    'atvilknes_cena_priedei' => 'Atvilknes cena priedei',
                    'atvilknes_cena_osim'    => 'Atvilknes cena osim',
                    'atvilknes_cena_ozolam'  => 'Atvilknes cena ozolam',
                    'pm_cena_priedei'        => 'Cena pac. matr. priedei',
                    'pm_cena_osim'           => 'Cena pac. matr. osim',
                    'pm_cena_ozolam'         => 'Cena pac. matr. ozolam',
                ];
                foreach ($extra_map as $field_key => $csv_key) {
                    if (isset($data[$csv_key]) && $data[$csv_key] !== '') {
                        update_field($field_key, $data[$csv_key], $post_id);
                        $has_any_extra = true;
                    }
                }
                if ($has_any_extra) {
                    $imported[$post_id]['extra_set'] = true;
                }
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
