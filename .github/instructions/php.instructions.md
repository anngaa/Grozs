---
applyTo:
  - "includes/**/*.php"
  - "grozs.php"
---

# PHP/WordPress guardrails
- PHP 8.2+ saderība; WPCS; izmanto tipus/collar type-hints, kur iespējams.
- Nekad nelasi nevalidētus `$_POST/$_GET`; izmanto `sanitize_*` un `filter_var`.
- Visi admin endpointi: `check_admin_referer` vai `wp_verify_nonce` + `current_user_can`.
- `$wpdb->prepare` obligāti; nekad neformatē SQL ar string konkatenāciju.
- Nelielas funkcijas/klases; atsevišķi faili pēc atbildības; testējamas vienības.
- Ielādes secība: neinicē admin assetus, ja ekrāns neatbilst (`get_current_screen()` sargs).