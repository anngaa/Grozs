<?php
namespace Grozs\Emails;

if (!defined('ABSPATH')) exit;

/**
 * Atrod pirmo esošo veidnes ceļu (tēma → bērna tēma → plugins)
 * ļauj tēmā pārdefinēt: yourtheme/grozs/emails/<slug>.php
 */
function grozs_locate_email_template($slug) {
    $relative = 'grozs/emails/' . $slug . '.php';

    // meklē bērna tēmā un tēmā
    $theme_path = locate_template($relative);
    if ($theme_path) {
        return $theme_path;
    }

    // fallback uz pluginu
    return plugin_dir_path(__FILE__) . 'emails/' . $slug . '.php';
}

/**
 * Renderē e‑pasta veidni un atgriež HTML kā string
 * $data padodam kā asociatīvu masīvu; tas kļūst par lokāliem mainīgajiem
 */
function grozs_render_email($slug, array $data = []) {
    $template = grozs_locate_email_template($slug);
    if (!file_exists($template)) {
        return '';
    }

    // padodam datus lokālajā scope
    extract($data, EXTR_SKIP);

    // ielādējam arī daļu (partials) helperi
    $partials_dir = plugin_dir_path(__FILE__) . 'emails/parts/';
    $grozs_partial = function($partial_slug, $vars = []) use ($partials_dir) {
        $file = $partials_dir . $partial_slug . '.php';
        if (file_exists($file)) {
            extract($vars, EXTR_SKIP);
            include $file;
        }
    };

    ob_start();
    include $template; // šeit veidnē var lietot $grozs_partial('items-table', [...])
    return ob_get_clean();
}