---
applyTo:
  - "includes/**/admin/**/*.php"
  - "includes/**/Admin/**/*.php"
  - "includes/**/admin*.php"
  - "includes/**/Admin*.php"
---

# Admin ekrāni, AJAX un saraksti
- Admin ekrāna gate: `if ( ($s = get_current_screen()) && $s->id === 'produkti_page_grozs_orders' ) { ... }`.
- Nelieto `remove_all_actions('admin_notices')` globāli; scope to konkrētajam ekrānam, ja ļoti vajag.
- AJAX: definē `wp_ajax_grozs_*` (un tikai ja nepieciešams — `wp_ajax_nopriv_*`), pārbaudi:
  - `check_ajax_referer('grozs_nonce')`
  - `current_user_can('manage_options')` (vai konkrētāka spēja)
  - Sanitizē/validate visus param.
- Lieli saraksti: paginācija server–side; meklēšana ar debounce; kolonnas minimālas pēc noklusējuma.
- UI: izvairies no popup “overlays” lieliem datiem; labāk inline–detalizācija rindā (lazy-load).
- Veiktspēja: neglabā smagas struktūras `autoload=yes`; lieto transients/`wp_cache_*`.