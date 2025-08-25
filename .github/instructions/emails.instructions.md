---
applyTo:
  - "includes/**/email*/**/*.php"
  - "includes/**/Email*/**/*.php"
  - "includes/**/email*.php"
  - "includes/**/Email*.php"
---

# HTML e-pasti
- Tabulu layout; inline CSS; width/height uz bildēm; alt teksti; izvairies no sarežģītiem selektoriem.
- Nekādu `<script>`; ārējie fonti tikai ja tiešām vajag – paredz fallbacks.
- Escape vienmēr (ar `esc_html`, `esc_attr`, `wp_kses_post`); nekad neinjicē raw HTML no lietotāja ievades.
- Testē atšķirīgos klientus (Gmail/Outlook); neveido sarežģītu nested flexu/gridu.