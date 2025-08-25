---
applyTo:
  - "includes/**/elementor/**/*.php"
  - "includes/**/Elementor/**/*.php"
  - "includes/**/widgets/**/*.php"
  - "includes/**/Widgets/**/*.php"
---

# Elementor widgeti
- Visi kontroles lauki ar saprātīgiem `default` un `responsive` atbalstu:
  - `add_responsive_control('x', [ 'type' => Controls_Manager::DIMENSIONS, ... ])`
- Renderēšanā izmanto `$settings = $this->get_settings_for_display();`.
- Asseti caur `get_script_depends()` / `get_style_depends()`, nevis globāli reģistrēti.
- Izvairies no inline JS; ja vajag uzvedību, data-* atribūti un atsevišķs JS modulis.
- I18n: visi redzamie teksti caur `__() / esc_html__()` ar domēnu `grozs`.