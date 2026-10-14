# M6-QA-FINAL — Responsive, accessibility & PHP compatibility

**Dictamen: NO-GO para release; M6 continúa ACTIVE.** Se completó evidencia visual en los viewports solicitados para los dos Heroes y los componentes prioritarios disponibles en el fixture. Se confirmó una falla de contraste en el estado predeterminado de Hero Cover. PHP 7.4 runtime, Tabs en navegador y parte de la revisión manual de accesibilidad continúan sin verificar. RC.2 permanece intacto; no se generó RC.3 porque no hubo cambios de código.

## Executive summary

- En TT5 1.5, WordPress 7.1.3 y Firefox 154.0.1 se capturaron a **1440, 768, 390 y 320 CSS px**: Hero Cover, Hero Split, Accordion, CTA, Pricing Cards, Comparison — 2 Options y Comparison — Feature Table.
- Las composiciones examinadas se ajustan al ancho; Hero Split y las cards/opciones pasan a una columna en móvil. La tabla usa el `overflow-x:auto` nativo de `core/table`; a 320 px requiere desplazamiento horizontal y la captura solo muestra parte de sus columnas.
- El fondo de Hero Cover sin imagen seleccionada renderiza aproximadamente `#a4a4a4` bajo texto blanco. Contraste calculado: **2.49:1**, inferior a 4.5:1 para texto normal y 3:1 para texto grande. La fuente es el markup SCI: overlay `#1e1e1e` con `dimRatio:40` sobre el fondo claro del fixture.
- En Firefox interactivo, Accordion inicia cerrado; un botón abre el panel y `Space` lo cierra. No se comprobó Enter, focus visible, tecnología asistiva ni Tabs.
- No se encontró runtime PHP 7.4 ni analizador PHPCompatibility/PHPCS disponible. La sintaxis/runtime 7.4 siguen **NOT VERIFIED**; PHP 8.5.7 no sustituye ese gate.
- Pruebas PHP/Python existentes, lint, parser/serializer e integridad del ZIP RC.2 pasan. La comprobación de instalación/actualización documentada previamente se conserva, con su alcance indicado en informes M6 anteriores.

## Baseline y RC.2

| Elemento | Evidencia |
|---|---|
| Rama / HEAD | `feature/sci-hero-patterns-css-compat` / `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12` |
| Estable | 1.4.0, congelada; sin cambios |
| Candidato | `sci-design-blocks-1.5.0-rc.2-m6.zip`; SHA-256 `f8d5d7c65cb17b1645d407324d73f8c44c8cb1b4b1dbeb8977af23ef007ef477` — coincide con el valor esperado |
| Integridad | `unzip -t` PASS; manifiestos RC y source con 105 entradas cada uno, preservados junto a RC.2 |
| Fixture limpio | WP 7.1.3, PHP 8.5.7, MariaDB 13.0.2, RC.2 activo, Twenty Twenty-Five 1.5 activo; Twenty Twenty-One 2.9 instalado/inactivo |
| Inventario | 22 patterns, 83 iconos, 7 Block Styles, 3 hojas CSS, 0 bloques SCI personalizados, 0 JavaScript frontend propio, 0 dependencias runtime adicionales |

RC.1 también se conserva sin sobrescritura. RC.2 es candidato de QA, no release aprobado. No se ejecutaron commit, push, merge, tag ni publicación.

## Responsive matrix

URLs del fixture `http://${QA_HTTP_HOST}:${QA_HTTP_PORT}/?page_id=…`; cada PNG es de 1000 px de alto. Los anchos de archivo se verificaron con `sips`. Las capturas están en `${SCI_QA_SITE_DIR}/screenshots/`, con nombres `{pattern}-{1440,768,390,320}.png`.

| Pattern / página | 1440 | 768 | 390 | 320 | Observaciones |
|---|---|---|---|---|---|
| Hero Cover / 24 | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | Contenido y botones se ajustan; los botones se apilan en móvil. Sin imagen escogida: fallback gris y falla de contraste descrita abajo. |
| Hero Split / 25 | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | Texto y botones legibles; botones apilados en móvil. El Image vacío no aporta imagen que permita evaluar crop/alt de medios reales. |
| Accordion / 12 | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | Encabezados y controles caben. Estado interactivo verificado en Firefox a tamaño de ventana no fijado; no equivale a validación de interacción en cada viewport. |
| Tabs | NOT VERIFIED | NOT VERIFIED | NOT VERIFIED | NOT VERIFIED | No hay pattern/página Tabs publicado en el fixture; sobreviven los tests estáticos de round-trip y CSS. |
| Pricing Cards / 19 | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | Tarjetas fluidas; se apilan en móvil y los CTA caben. Captura a 768 muestra parte de la fila; no se certifica interacción/edición. |
| Comparison — 2 Options / 22 | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | Dos opciones en columnas anchas y apiladas en móvil; copy y CTA visibles. |
| Comparison — Feature Table / 23 | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | El ancho excede la pantalla estrecha; WordPress Core aplica `.wp-block-table{overflow-x:auto}`. La captura recorta columnas que requieren scroll horizontal; el scroll por teclado/táctil no se certificó. |
| CTA / 8 | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | PASS WITH OBSERVATIONS | Texto y botón fluyen sin corte visible. |

Los PNG son frontend visual, no validación del editor. El fixture publica las páginas QA en la navegación de TT5: a 768 px la lista extensa de páginas se envuelve y alarga el encabezado; a móvil se muestra el menú compacto. Ese ruido pertenece al fixture/tema, no al contenido interno del pattern. Las capturas headless de Accordion pueden adelantarse a la hidratación; para la interacción se usó Firefox visible y se esperó al árbol accesible.

**Cobertura incompleta:** no se ejecutó la matriz visual de los otros patterns relevantes ni Tabs; tampoco se midieron recorte de imágenes reales o edición por viewport. No se extiende este resultado a los 22 patterns completos.

## Accessibility matrix

| Comprobación | Estado | Evidencia / límite |
|---|---|---|
| Hero Cover — heading y orden | STATIC PASS WITH LIMITATIONS | La página fixture tiene H1 del título y el Hero contiene H2. El patrón no trae imagen seleccionada; no hay imagen cuyo recorte o texto alternativo revisar. |
| Hero Cover — contraste predeterminado | **FAIL** | Texto blanco sobre muestra `#a4a4a4`: 2.49:1. Lo causa `dimRatio:40` en el pattern SCI sobre su fallback claro. Afecta texto normal y grande. |
| Hero Split — heading y lectura | STATIC PASS WITH LIMITATIONS | H1 del título de fixture seguido de H2 del Hero; bloques y CTA aparecen en orden de lectura. |
| Hero Split — alt/media | NOT VERIFIED | El Core Image vacío serializa con `alt=""`; PO confirma edición/selección manual del medio. No se probó un medio real con su alt significativo/decorativo en este gate. |
| Hero CTAs | PASS WITH OBSERVATIONS | Nombres descriptivos y orden visibles en árbol AX. Los destinos `#example-details` y `#example-start` son ejemplos/placeholders sin destino de negocio; reemplazarlos al usar el patrón. |
| Accordion | BROWSER PASS — parcial | Árbol de Firefox expone botones y paneles; panel cerrado inicialmente; click lo abre y Space lo cierra. Enter, focus visible, orden de tabulación, autoclose y lector de pantalla no se probaron. |
| Tabs / estados SCI | NOT VERIFIED | Sin fixture/browser de Tabs. Tests de parser/CSS no prueban teclado, estado seleccionado, foco ni árbol accesible. |
| Focus, contraste global y tecnología asistiva | NOT VERIFIED | Sin revisión completa de focus indicators, combinaciones de color dependientes del tema, lector de pantalla o auditoría integral. No se afirma conformidad WCAG. |

**Corrección mínima propuesta (no aplicada):** elevar la opacidad del overlay predeterminado de Hero Cover (por ejemplo, revisar `dimRatio` alrededor de 70) o usar un fondo oscuro sólido bajo el texto; volver a medir sobre el fallback claro y una selección representativa de imágenes. El contenido elegido puede cambiar el contraste. Esta tarea no modificó código ni invalida RC.2; hasta resolverlo, el gate de accesibilidad falla.

## PHP 7.4 compatibility

| Verificación | Estado |
|---|---|
| PHP local | Solo PHP 8.5.7 encontrado; `php7.4`, `php74` y Homebrew `php@7.4` ausentes |
| Runtime aislado | Docker y Podman ausentes; no se instaló ni descargó runtime |
| Analizador estático | PHPCS/PHPCompatibility no disponibles en PATH ni Composer global |
| Sintaxis/runtime del código | **PHP 7.4 RUNTIME — NOT VERIFIED**; lint exitoso solo con PHP 8.5.7. El análisis previo de fuentes no encontró construcciones PHP 8-only, pero no prueba compatibilidad real |

PHP 7.4 está fuera de soporte de seguridad; no usarlo en producción. Mantener el requisito y el gate abiertos hasta probar en runtime aislado de compatibilidad confiable.

## PO QA evidence

Se conserva como **PASS — PO MANUAL QA**, no automated pass: el PO reportó Hero Cover insertado, editado, guardado y reabierto, vista móvil y sin bloques inválidos; Hero Split pasó esas mismas operaciones, móvil y tablet, sin bloques inválidos. No se solicitaron de nuevo. Esa evidencia no mide contraste ni reemplaza esta matriz exacta.

## Regression, security, and Git

**Regresión ejecutada en esta corrida:** PHP lint de todos los PHP del árbol; `check-icon-library.php`; `check-editorial-query-patterns.php` con WP 7.1.3; `check-pricing-comparison-patterns.php` con WP 7.1.3; `check-tabs-roundtrip.php` con WP 7.1.3; cuatro checks Python (`check-icon-svg.py`, `check-accordion-css.py`, `check-tabs-css.py`, `check-editorial-image-css.py`); `git diff --check`. Todo **PASS**. El parser/serializer cubre los 22 patterns; revisión de autocerrados: 38 usos permitidos Core, cero `core/image` autocerrados. Inventario reportado: 22/83/7/3.

La auditoría estática previa cubrió 144 rutas y reportó cero hallazgos reportables. Como esta corrida no modificó código productivo, no se repitió; no es una garantía de ausencia absoluta de vulnerabilidades.

Árbol Git al inicio: 10 archivos rastreados modificados y 11 no rastreados, preexistentes. Esta tarea añadió únicamente este informe al repositorio. Se conservaron intactos código, RC.1, RC.2 y manifiestos. Rama y HEAD permanecen como arriba; sin operaciones Git de publicación. `git diff --check` pasó.

## Gates, dictamen y siguientes pasos

| Gate | Estado | Bloqueante | Siguiente acción / responsable |
|---|---|---:|---|
| Responsive de Heroes y subset P2 | PASS WITH OBSERVATIONS | Sí, por cobertura | Añadir Tabs y completar restantes relevantes; revisar scroll de tabla y medios reales / Ingeniería+PO |
| Hero Cover contrast | FAIL | Sí | Aplicar corrección mínima tras decisión de diseño; volver a probar fallback e imágenes / Ingeniería |
| Accesibilidad interactiva integral | NOT VERIFIED | Sí | Teclado completo, focus, Tabs, estados, alt real y lector de pantalla; no declarar WCAG / PO+QA |
| PHP 7.4 runtime | NOT VERIFIED | Sí | Encontrar runtime aislado confiable o mantener gate expresamente abierto / Ingeniería |
| PHP/Python, parser/serializer, inventario, lint | PASS | No | Ninguna para esta regresión |
| Integridad RC.2 | PASS | No | Conservar artefacto y manifiestos; si cambia código productivo, RC.2 no representa ese código y se requerirá RC.3 |
| PO browser QA de Heroes | PASS — PO MANUAL QA | No, con alcance reportado | No repetir si el código no cambia |
| Install/upgrade y preservación | PASS WITH LIMITATIONS — evidencia M6-ENV-03 | Reutilizado | Reabrir solo si cambia código/artefacto o aparece evidencia nueva |
| Seguridad | PASS — revisión previa, alcance citado | No | Revisión incremental si cambia código |

**Decisión final: NO-GO. M6 sigue ACTIVE. No publicar.** RC.2 conserva el hash certificado, pero no se recomienda aprobarlo como release mientras exista el fallo de contraste y sigan abiertos PHP 7.4 runtime y accesibilidad completa. Si se modifica código, generar RC.3 con manifiestos y SHA-256 propios y repetir los gates afectados; no alterar RC.1, RC.2, `main` ni 1.4.0.
