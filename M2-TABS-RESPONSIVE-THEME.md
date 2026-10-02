# SCI Design Blocks 1.2 — M2 Responsive + Theme Integration

- **Resultado:** PASS — listo para revisión; manual visual QA pendiente para M3/M4.
- **Branch:** `feature/tabs-styles-1.2`
- **Base:** M1 commit `2101cb4` (`Add Core Tabs Underline and Pills styles`).
- **Plugin metadata:** 1.1.0 (unchanged during M1–M3).

## Entorno y evidencia

- WordPress Core instalado en `/home/mgarcia/dev01/nettix-proposal-lab/wordpress`: **7.1.2**, leído de `wp-includes/version.php`.
- PHP CLI: **8.5.4**.
- Block themes disponibles en disco: Twenty Twenty-Three, Twenty Twenty-Four y Twenty Twenty-Five.
- La instalación no ofreció una sesión HTTP/editor utilizable: `http://localhost/` devolvió la página predeterminada de nginx; no hay WP-CLI ni browser/editor conectado a ese sitio. No se simula evidencia visual.
- Core `wp-includes/blocks/tab-list/block.json`: supports de color text/background, layout flex con justificación y padding/block gap; `flexWrap` por defecto es `wrap`, pero `allowWrap` está deshabilitado en el control Core.
- Core `wp-includes/blocks/tab-list/style.css`: botones usan `width: max-content`, `color: inherit`, tipografía heredada y padding de presets spacing. Core `core/tabs` ofrece color text/background, tipografía, block gap, margin y padding.

## Matriz M2

| Criterio | Resultado | Evidencia |
|---|---|---|
| Underline y Pills opt-in | PASS | `tests/check-icon-library.php`: exactamente `sci-underline`/Underline y `sci-pills`/Pills registrados en `core/tabs`, sin default SCI. |
| Core default intacto | PASS estático | Los selectores requieren las clases SCI de estilo; no hay regla para `.wp-block-tabs` sin clase SCI. |
| Horizontal nowrap y overflow | PASS estático | `assets/css/tabs-styles.css`: `flex-wrap: nowrap` y `overflow-x: auto` solo para la lista de tabs dentro de ambos estilos. |
| Labels largos, scroll nativo, touch/trackpad y sin overflow de página | PENDIENTE visual | El código conserva un list horizontal con overflow del navegador; no se pudo insertar/probarlo en editor/frontend. |
| Ancho automático | PRESERVED estático | SCI no define `width`, `min-width`, `max-width` ni `flex`; Core define `width: max-content`. |
| Justificación left/center/distributed | PRESERVED estático | SCI no modifica `justify-content` ni layout; Core `tab-list` expone layout flex y justificación. |
| Colores Core y presets | PRESERVED estático | No hay color/paleta hardcodeada. El borde y estado activo usan `currentColor`; SCI no define color/text/background. Core declara supports text/background. |
| Tipografía y herencia del tema | PASS estático | Sin declaraciones SCI `font-*`; Core Tab List hereda `color`/font y Core declara typography/font-size. |
| Padding, gap y wrapper spacing | PRESERVED estático | SCI no define padding/margin/gap; Core declara padding/blockGap para lista y margin/padding/blockGap para Tabs. |
| Fondos claros/oscuros | PASS estático; visual pendiente | SCI no presupone fondo ni foreground; los acentos estructurales derivan de `currentColor`. |
| Foco visible / tabs fuera del viewport con teclado | PASS estático; visual pendiente | SCI no oculta scrollbar ni modifica `outline`/focus; keyboard y navegación permanecen bajo Core. La visibilidad en navegador requiere QA visual/manual. |
| Contenido nested Core sin estilos SCI | PASS estático | Selectores alcanzan únicamente la lista directa o botones de lista; no apuntan a paneles ni contenido descendiente. |
| CSS/JS/dependencias | PASS | CSS Tabs: 631 bytes; 4 reglas; 5 selectores; cero `!important`, media queries, dimensiones fijas o colores hardcodeados. Cero JS Tabs y dependencias añadidas. |

## Regresión SCI 1.1

`php tests/check-icon-library.php` confirma una categoría, **9 patterns**, una collection, **83 iconos**, dos estilos Accordion y dos estilos Tabs. El source conserva el registro mediante Core `register_block_style()`; no se encontró registro de un custom Tabs block. `python3 tests/check-icon-svg.py` confirma 83 assets y que los únicos CSS de producto son Accordion y Tabs, sin frontend JS ni manifest de dependencias.

## Validación ejecutada

| Comando | Resultado |
|---|---|
| `php -l sci-design-blocks.php` | PASS |
| `php -l includes/tabs-styles.php` | PASS |
| `php -l includes/accordion-styles.php` | PASS |
| `php tests/check-icon-library.php` | PASS — registra dos estilos Core Tabs además de la regresión 1.1. |
| `python3 tests/check-tabs-css.py` | PASS — scope, nowrap/overflow, theme-native, sin dimensiones/color/!important/media queries. |
| `python3 tests/check-accordion-css.py` | PASS |
| `python3 tests/check-icon-svg.py` | PASS — 83 iconos; CSS Tabs y Accordion admitidos; sin JS runtime. |
| `git diff --check` | PASS |

El harness existente se actualizó para reconocer el stylesheet y los dos registros Core Tabs. El check de assets excluye `poc/` (material de prueba no runtime), que ya contenía CSS del spike; no se modificaron esos archivos.

## Archivos M2

- Actualizados: `tests/check-icon-library.php`, `tests/check-icon-svg.py`.
- Añadidos: `tests/check-tabs-css.py`, este informe.
- Sin cambios de producción: CSS/registro M1, Accordion, Icon Library, patterns, versión 1.1.0.

**M2:** PASS para aceptación estática y compromiso del milestone. **Manual visual QA:** PENDING M3/M4 en WordPress editor/frontend, narrow viewport, controles Core, temas claros/oscuros y foco/scroll por teclado.
