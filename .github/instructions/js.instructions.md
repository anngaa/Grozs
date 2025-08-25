---
applyTo:
  - "assets/**/*.js"
---

# JS (assets/)
- Moduļu pieeja; neglobāli `window.*`.
- Event delegation admin sarakstiem; throttle/debounce uz ievadiem/scroll.
- Nelieli bundļi; lazy-load, ja konkrētais ekrāns neizmanto.
- Neatstāj `console.log` produkcijā; ieliec aizsargus pret dubult–initialization.