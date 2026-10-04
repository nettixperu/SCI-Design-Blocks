# SCI Design Blocks 1.2 — M3 QA / Core Regression

- **Resultado:** PASS estático / listo para revisión.
- **Branch:** `feature/tabs-styles-1.2`
- **Base:** M2 `3849f03`; M1 `2101cb4`.
- **Versión del plugin:** 1.1.0 (sin cambios; M4 es dueño del version bump).
- **WordPress inspeccionado:** Core 7.1.2 en `/home/mgarcia/dev01/nettix-proposal-lab/wordpress`.
- **PHP CLI:** 8.5.4. **Temas presentes:** Twenty Twenty-Three, Twenty Twenty-Four y Twenty Twenty-Five.
- **Browser/editor:** no disponible para el sitio; `http://localhost/` responde con la página default de nginx. El registro no se presenta como QA de UI y las pruebas visuales quedan pendientes de M4.

## Core Tabs gate

| Criterio | Resultado | Evidencia |
|---|---|---|
| Core Tabs y children reconocidos | PASS estático | Core 7.1.2 tiene `blocks/{tabs,tab-list,tab-panels,tab-panel}/block.json`, con nombres `core/*`; `tabs.php`, `tab-list.php` y `tab-panel.php` registran render/context cuando aplica. `core/tab-panels` se registra desde metadatos Core. |
| SCI registra solo styles en `core/tabs` | PASS | `includes/tabs-styles.php` llama dos veces a `register_block_style('core/tabs', ...)`; no hay `register_block_type()` del plugin ni nombres `sci/tabs`, `sci/tab` o `sci/tab-panel`. |
| Underline/Pills, sin duplicados | PASS | Registration smoke encontró exactamente `sci-underline`/Underline y `sci-pills`/Pills con stylesheet local y strings pasadas por `__()` usando `sci-design-blocks`. |
| Core default sin style SCI | PASS estático | Las reglas Tabs requieren `.is-style-sci-underline` o `.is-style-sci-pills`; no hay selector que cambie un `.wp-block-tabs` default. |
| Add/remove/rename/reorder/selection | Core-owned; sin overrides SCI | Core 7.1.2 `wp-includes/js/dist/block-library.js`, módulo Tabs: `useTabActions` define `insertTab`, `removeTab`, `moveTab`; `useTabListItemsSync` sincroniza etiquetas; `Edit22` usa `InnerBlocks` con template Core. SCI no añade editor code ni handlers. |
| Parse → serialize → parse, ambos estilos | PASS | `tests/check-tabs-roundtrip.php` contra parser/serializador Core 7.1.2: fixtures con tres panels, clases de estilo, Paragraph, Heading, List/List Item, Buttons/Button, Group, Columns/Column e Image. Resultado serializado estable y árbol Core preservado. |
| ARIA / keyboard / focus / deep link | Core-owned; no override SCI | Core `blocks/tabs.php`, `tab-list.php`, `tab-panel.php` añade contexto/interactividad, ids y relaciones `aria-controls`/`aria-labelledby`, estado/`tabindex`; `js/dist/script-modules/block-library/tabs/view.js` contiene click, Left/Right, Home/End, foco y lectura del hash al iniciar. SCI no contiene ARIA, roles, eventos, hash, URL ni reglas de focus/outline. Enter/Space usan el comportamiento nativo del botón; UI manual pendiente. |
| Contenido anidado y estilos de panel | PASS estático | Selectores SCI solo alcanzan la lista directa y sus botones. No seleccionan `.wp-block-tab-panel`, paneles ni bloques internos. Roundtrip confirma los bloques anidados especificados. |

## SCI 1.1 regression e inventario

- **Patterns:** 9; **icon collection:** 1; **SCI Icons:** 83; **Accordion styles:** 2 (`sci-minimal`, `sci-bordered`); **Tabs styles:** 2. Verificado por `tests/check-icon-library.php`.
- Los patterns originales y el registro del Accordion no cambiaron en el rango M1–M3; no se añadió un Tabs pattern.
- **Custom SCI blocks:** 0. **SCI frontend JS:** NONE. **Runtime dependencies:** NONE (sin npm/Composer manifests, vendor tree, CDN, fetch o terceros runtime).
- **SCI CSS:** Accordion 1,076 bytes + Tabs 631 bytes = **1,707 bytes** en dos hojas de producto. Tabs tiene 4 reglas, 5 selectores, 0 `!important`, 0 media queries y 0 colores hardcodeados. Sin ancho/alto fijo; preserva `max-content`, justificación, color, tipografía y spacing Core.
- **Seguridad/semántica:** sin AJAX, REST, endpoints, eval, scripts de usuario o CSS dinámico. Core conserva markup/serialización del contenido; SCI no añade wrappers ni carga paneles por red.
- **Compatibilidad declarada:** WordPress >= 7.1, PHP >= 7.4. Los metadatos siguen `Version: 1.1.0`, `Requires at least: 7.1`, `Requires PHP: 7.4`. Los archivos Tabs usan sintaxis compatible con PHP 7.4; no se dispone de runtime PHP 7.4 para ejecución real.
- No se modificaron ZIP, tag o release 1.1.0.

## Comandos y resultados

| Comando/check | Resultado |
|---|---|
| `find . -path './.git' -prune -o -path './poc' -prune -o -name '*.php' -type f -print0 \| xargs -0 -n1 php -l` | PASS — todo PHP de producto y test sin errores. |
| `php tests/check-icon-library.php` | PASS — patterns, colección/iconos y estilos Accordion/Tabs; valida strings traducibles. |
| `php tests/check-tabs-roundtrip.php /home/mgarcia/dev01/nettix-proposal-lab/wordpress` | PASS — ambos estilos, tres panels y contenido Core anidado en Core 7.1.2. |
| `python3 tests/check-tabs-css.py` | PASS — scope y contratos visuales estáticos. |
| `python3 tests/check-accordion-css.py` | PASS. |
| `python3 tests/check-icon-svg.py` | PASS — 83 SVG; únicamente CSS Accordion/Tabs; sin JS/dependencias. |
| Source inspection / `rg` de custom block registration, JS, URL/hash, ARIA, handlers y dependencias | PASS — sin implementación SCI de Tabs ni nuevas dependencias. |
| `git diff --check` | PASS. |

## QA manual compacto pendiente para M4

1. Default Core Tabs; Underline; Pills: insertar, guardar, cerrar/reabrir y probar controles de color/tema.
2. Viewport móvil con labels largos: scroll horizontal, sin wrapping ni overflow de página; foco y teclado incluyendo Left/Right, Home/End, Enter/Space.
3. Paneles con contenido anidado y Tabs bajo Group/Column; guardar/reabrir, orden/duplicado y verificar ARIA/deep link.
4. Deactivate/reactivate: Core Tabs y contenido sobreviven; puede desaparecer solo la presentación SCI.

**Conclusión:** M3 PASS para Core source/static regression. La sesión real de UI, navegación de teclado y persistencia visual no se simuló; el gate visual compacto queda pendiente en M4. No se inicia M4.
