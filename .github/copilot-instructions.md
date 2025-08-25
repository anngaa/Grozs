# Grozs — Copilot instructions

## Ātrie fakti
- Repo struktūra: `assets/` (JS, CSS), `includes/` (admin, e-pasti, widgeti), saknē `grozs.php`.
- Mērķis: stabils, saprotams, resursu–ekonomisks spraudnis bez “maģijas”.

## Koda stils un drošība
- WordPress Coding Standards (WPCS) + (kur nav konflikta) PSR-12.
- Ievade → **validē/sanitizē**, izvade → **escape** (`esc_html`, `esc_attr`, `wp_kses_post`).
- Admin darbības: **nonce** + `current_user_can(...)`.
- DB vaicājumi tikai ar **$wpdb->prepare**; nekad nebalsties uz nevalidētu `$_POST/$_GET`.

## Arhitektūra
- Nelieli, atsevišķi moduļi. Atkarības caur konstruktoriem, nevis singletoni.
- Hooks/filters dokumentē ietekmi; neizmaini globālus stāvokļus klusām.
- Assetus ielādē kontekstuāli (tikai kur vajag).

## Performance
- Kešo smagas kalkulācijas (transients/object cache).
- `WP_Query` lieliem sarakstiem ar `fields => 'ids'`; paginē.
- Admin-AJAX: throttle/debounce uz meklēšanu; mazs payload.

## Elementor & ACF
- Widgetiem: `get_style_depends()`/`get_script_depends()`.
- `add_responsive_control(...)` izmanto atbilstoši; `get_settings_for_display()` renderēšanas brīdī.
- ACF repeater: izvairies no dziļām O(n*m) cilpām; sagatavo indeksus.

## E-pasti
- Tabulu layout + inline CSS; vienkārši selektori; bildēm width/height/alt; bez `<script>`.

## Kā atbildēt (Copilot/Agent)
1) īss plāns; 2) mazs, lokāls diff; 3) drošības pārbaudes; 4) i18n (`__()`, `esc_html__()` ar domēnu `grozs`);
5) komentāri kodā; 6) neskar nevajadzīgus failus.

## Ko nedarīt
- Bez `var_dump/die` kodā; neielādē smagus assetus vispārīgi; nepaaugstini `autoload` izvēli smagiem options.