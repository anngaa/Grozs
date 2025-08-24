# Grozs — GitHub Copilot instrukcijas

## Projekts
- Platforma: WordPress ≥ 6.6, PHP ≥ 8.2.
- Spraudnis: “Grozs”. Visiem nosaukumiem lieto `Grozs\...` namespace. Teksta domēns: `grozs`.
- Mērķis: stabils, resurss–ekonomisks spraudnis ar skaidru API un minimālu ielāpu risku.

## Koda stils & drošība
- Ievēro WordPress Coding Standards (WPCS); kur nav pretrunu — PSR-12.
- Vienmēr sanitizē/escape (`sanitize_text_field`, `esc_html`, `esc_attr`, `wp_kses_post`).
- Visiem admin darbības endpointiem — nonce pārbaude un `current_user_can`.
- DB piekļuve tikai ar `$wpdb->prepare`; nekad nelasi tieši no `$_POST/$_GET` bez validācijas.

## Arhitektūra
- Mapes: `/includes`, `/admin`, `/public`. Bootstraps centralizē hooks reģistrāciju.
- Nelieto singletons; dod atkarības caur konstruktoriem. Modulāras klases + skaidri hooki/filtri.
- Funkciju prefikss `grozs_`; failu nosaukumi skaidri pēc atbildības.

## Performance
- Izvairies no N+1 vaicājumiem; `WP_Query` ar `fields => ids` vajadzības gadījumā.
- Kešo ar transients/`wp_cache_*`; smagus `options` neliec `autoload = yes`.
- Admin-AJAX: minimāls payload, debounce/throttle; paginācija lielām tabulām.

## Elementor & ACF
- Elementor widgetiem aizpildi `get_style_depends()`/`get_script_depends()`; neveido globālu noplūdi.
- ACF Repeater: nelieto dziļas cilpas; ja vajag, sagatavo indeksu/kešu.
- “Pielāgota grupa” (widget context): tas ir ID, kas sasaista Price/Button/Options pāri dažādiem layoutiem — saglabā API konsekvenci.

## E-pasti
- HTML e-pasti: tabulu layout, inline CSS; izvairies no sarežģītiem selektoriem; bildēm width/height, alt.
- Nedrīkst ārējus <script>; CSS tikai inline vai <style> ar minimāliem noteikumiem.

## Testi & CI
- PHPUnit/Pest ar WP test suite; unit + integrācijas testi kritiskajai loģikai.
- GitHub Actions: phpcs, phpstan (lvl piemērots), testi uz PHP 8.2/8.3.

## I18n
- Teksti caur `__()`, `_e()`, `_x()` ar domēnu `grozs`. Nehardkodē LV tekstus.

## Commit/PR prakse
- Conventional Commits (feat, fix, perf, refactor, test, docs).
- PR: īss “kāpēc/kā” + atpakaļsaderība + manuālo testu soļi.

## Kā atbildēt uz uzdevumiem
- Vispirms izklāsti plānu (soļi), tad ģenerē patch. Pievieno drošības pārbaudes, i18n, komentārus.
- Neievieš “maģiju”: nelieli, saprotami diffi; neskart nevajadzīgus failus.

## Ko NEDARĪT
- Nelikt inline `var_dump/die`; nelietot “quiet fail”.
- Nelādēt smagus assetus adminā, ja tos neizmanto konkrētajā ekrānā.