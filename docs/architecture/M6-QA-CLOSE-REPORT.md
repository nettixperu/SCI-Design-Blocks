# M6-QA-CLOSE — QA final y preparación de release

**Fecha:** 2026-10-13
**Rama / HEAD:** `feature/sci-hero-patterns-css-compat` / `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`
**Baseline estable:** 1.4.0, sin modificar
**Candidato vigente:** 1.5.0-rc.5 (QA solamente)
**Dictamen:** **M6 ACTIVE — TECHNICAL NO-GO — RELEASE NO-GO**

## Resumen ejecutivo

La suite estática y el runtime de PHP 8.3/WP 7.1.3 pasan. RC.5 se instaló y activó en el fixture local a partir de RC.4; la base de datos, los 22 patterns y los siete registros de Block Styles pasan. Los contenidos Core conservaron su SHA-256 al desactivar y reactivar SCI.

La revisión reusó, con alcance explícito, capturas históricas a 1440/768/390/320 px para componentes cuyo markup no cambió. No se completó inspección visual post-RC.5 de los siete Block Styles, Tabs, Hero Cover ni de Pricing tras el ajuste M6-FIX-02. Firefox avisó que el usuario había cambiado la aplicación; no continué acciones de interfaz. Tampoco existe evidencia de instalación limpia RC.5 ni de actualización directa 1.4.0 → RC.5. Estos gates impiden TECHNICAL GO.

Se corrigió un defecto confirmado de contraste del overlay predeterminado de Hero Cover (2.49:1 medido previamente en el fallback claro). El overlay Core `#1e1e1e` subió de 40 % a 70 %; una regresión comprueba el atributo serializado. El cálculo de composición sobre el fondo blanco más luminoso da aproximadamente `#626262`, contraste teórico 6.10:1 con texto blanco. Falta confirmar el resultado renderizado en navegador y revisar contenido editorial real. La corrección invalida RC.4 como código candidato y originó RC.5.

## Baseline y alcance de cambios

Al comenzar, el worktree ya tenía cambios de M5/M6 sin commit. Se conservaron; no se cambió de rama ni de HEAD. En esta tarea se editaron únicamente:

- `patterns/sci-hero-cover.html`: overlay `dimRatio` 40 → 70 y clase Core de salida coherente.
- `tests/check-editorial-query-patterns.php`: regresión del atributo `core/cover` y overlay.
- `README.md`: distingue PHP 7.4+ para 1.4.0 estable de PHP 8.3+ para el candidato 1.5.0.
- Este informe.

No se reescribieron informes históricos. No se añadieron patterns, estilos, bloques, dependencias ni JavaScript. No se modificaron MariaDB, WordPress Core, temas ni producción.

## Integridad del Release Candidate

RC.5 se derivó del ZIP preservado RC.4, actualizando solamente Hero Cover y el encabezado de versión empaquetado a `1.5.0-rc.5`. Los manifiestos tienen 105 entradas y coinciden con los archivos del ZIP; `unzip -t` pasó.

| Artefacto | Ruta | SHA-256 | Estado |
|---|---|---|---|
| RC.5 ZIP | `${SCI_QA_ROOT}/release-candidates/sci-design-blocks-1.5.0-rc.5-m6.zip` | `a2c33fa331eb653d916bbcdf59d4341c98e6961add89e1f7be9c81df9dbffc73` | PASS — QA only |
| RC.5 package manifest | `RC-FILE-MANIFEST-rc.5.sha256` | `c181951be9262bb7b249ee8c7716a0180cf92f928e0845f89a4de2a16b15f38c` | 105 entradas |
| RC.5 source manifest | `SOURCE-FILE-MANIFEST-rc.5.sha256` | `9000cdc3769c4c23803db2afce09362fb7cb88bce4381a83b2f7f08e3ad9b993` | 105 entradas |
| RC.4 ZIP | `sci-design-blocks-1.5.0-rc.4-m6.zip` | `44b33c881b357cd456a5f6737e86bbc9d2c8c07e03ba139422f9452e3853de18` | Preservado, sin cambios |

También siguen intactos RC.1 (`0cd716…a83ed0`), RC.2 (`f8d5d7…7ef477`) y RC.3 (`9b7ffdb…3cdd7`). Ningún RC fue etiquetado ni publicado.

## Entorno, instalación y actualización

| Gate | Evidencia | Estado | Bloqueante / acción |
|---|---|---|---|
| Runtime mínimo | PHP 8.3.35 arm64; WordPress 7.1.3; Twenty Twenty-Five 1.5 | PASS | No repetir investigación PHP 8.3 |
| RC.5 instalación/activación | WP-CLI instaló el ZIP RC.5 sobre RC.4 en `${SCI_QA_SITE_DIR}/wordpress`; plugin activo `1.5.0-rc.5` | PASS para actualización RC.4 → RC.5 | No equivale a instalación limpia |
| DB | `wp db check` tras actualización y reactivación | PASS | — |
| Instalación limpia exacta RC.5 | No se creó un sitio vacío nuevo | NOT VERIFIED | Sí; ejecutar en fixture aislado |
| Upgrade 1.4.0 → RC.5 | Hay evidencia histórica hasta RC.3, no prueba directa del ZIP vigente | NOT VERIFIED | Sí; ejecutar en fixture aislado preservando fixtures actuales |
| HTTP | Portada, Heroes, tres páginas Pricing y fixture Styles (IDs 19–21, 24–25, 27) respondieron 200; tres CSS respondieron 200 | PASS de disponibilidad | No es prueba visual ni editor |
| No-lock-in | Hash combinado de contenido Core en IDs 19, 20, 21, 24, 25 y 27: `80f5bea149d77a90db3c02680eea2180ea1e1d6043f0dad1929c6b34866f968a` antes, con SCI desactivado y tras reactivarlo; Core parseó 27 nodos top-level | PASS WITH LIMITATIONS | Persistencia probada; no certifica UX del editor |

El fixture utiliza la instancia MariaDB QA dedicada. No se modificó su configuración; SQLite, servicios compartidos y sitios de producción quedaron fuera del alcance.

## Inventario, registro y regresión

| Recurso | Resultado RC.5 |
|---|---:|
| Patterns SCI | 22 — registro real de WP |
| Iconos SVG | 83 — test de catálogo/procedencia |
| Block Styles SCI | 7 — registro real de WP |
| CSS | 3 — Accordion 1,076 B; Tabs 2,202 B; Hover Zoom 580 B |
| Bloques personalizados SCI | 0 |
| JavaScript propio de frontend | 0 |
| Dependencias runtime externas nuevas | 0 |

PHP lint de todos los archivos: PASS en PHP 8.3.35. Pasaron los cuatro tests PHP existentes: catálogo, patterns editoriales/parser, pricing/Core layout y Tabs round-trip. Pasaron los cuatro tests Python existentes: SVG y las tres hojas CSS. El parser de WP 7.1.3 cubrió los 22 patterns; la regresión de Hero Split confirma el `core/image` serializado, y el nuevo test de Hero Cover comprueba `core/cover` con `dimRatio:70` y color `#1e1e1e`. Los nueve Groups Pricing conservan la corrección de `verticalAlignment:space-between`. `git diff --check`: PASS.

La evidencia dinámica de WordPress verificó 22 patterns y los siete slugs de estilo: `sci-minimal`, `sci-bordered`, `sci-underline`, `sci-pills`, `sci-connected`, `sci-filled` y `sci-hover-zoom`. El fixture emitió una hoja de cada tipo CSS. La inspección de CSS confirma que Hover Zoom aplica la transformación en hover/focus cuando hay hover y `prefers-reduced-motion:no-preference`, y elimina transición/transformación bajo `prefers-reduced-motion:reduce`; no demuestra su resultado visual.

## Block Styles — verificación visual

La página local `M6 QA — Block Styles RC.4` (ID 27), creada antes para RC.4 y ahora servida con RC.5 activo, contiene los siete estilos; el frontend incluye cada clase y las tres hojas. La solicitud HTTP verifica entrega, no apariencia; no se capturó ni inspeccionó visualmente esa página en RC.5.

| Block Style | Visual | Interacción / observación pendiente |
|---|---|---|
| Accordion `sci-minimal` | NOT VERIFIED | comparar bordes, espaciado y estado abierto/cerrado |
| Accordion `sci-bordered` | NOT VERIFIED | comparar bordes, espaciado y foco visible |
| Tabs `sci-underline` | NOT VERIFIED | activo, panel, foco, overflow |
| Tabs `sci-pills` | NOT VERIFIED | activo, panel, foco, overflow |
| Tabs `sci-connected` | NOT VERIFIED | activo, panel, foco, overflow |
| Tabs `sci-filled` | NOT VERIFIED | activo, contraste, foco, overflow |
| Featured Image `sci-hover-zoom` | NOT VERIFIED | escala, crop, layout shift, foco y móvil |

No se cambió CSS: no apareció evidencia visual nueva que justificara esa modificación.

## Responsive matrix

Se reutiliza solo la evidencia visual previa registrada en `M6-QA-FINAL-REPORT.md` para markup sin cambios, en Firefox 154.0.1, WP 7.1.3 y TT5 1.5. Las capturas de esos componentes están en `${SCI_QA_SITE_DIR}/screenshots/`, con anchos exactos 1440/768/390/320 px. “PASS con observaciones” significa únicamente el alcance ya fotografiado, no una nueva captura de RC.5.

| Componente | 1440 | 768 | 390 | 320 | Evidencia / limitación |
|---|---|---|---|---|---|
| Hero Cover | PENDING | PENDING | PENDING | PENDING | Cambió overlay en RC.5; recapturar y revisar contraste |
| Hero Split | PASS con observaciones | PASS con observaciones | PASS con observaciones | PASS con observaciones | Capturas previas; PO reportó además móvil/tablet; revisar media real y alt |
| Pricing Cards | PENDING | PENDING | PENDING | PENDING | PO confirmó CTA alineados y móvil en RC.4, sin matriz exacta |
| Pricing Featured | PENDING | PENDING | PENDING | PENDING | Igual; contenido/layout cambió en M6-FIX-02 |
| Pricing Compact | PENDING | PENDING | PENDING | PENDING | Igual; contenido/layout cambió en M6-FIX-02 |
| Accordion | PASS con observaciones | PASS con observaciones | PASS con observaciones | PASS con observaciones | Capturas históricas; interacción solo parcialmente probada |
| Tabs | NOT VERIFIED | NOT VERIFIED | NOT VERIFIED | NOT VERIFIED | No había fixture visual previo |
| Hover Zoom / imagen destacada | NOT VERIFIED | NOT VERIFIED | NOT VERIFIED | NOT VERIFIED | CSS/reduced-motion inspeccionados; efecto y crop no vistos |
| CTA pattern | PASS con observaciones | PASS con observaciones | PASS con observaciones | PASS con observaciones | Capturas históricas; CTA de Pricing no queda cubierto por esta fila |
| Comparison — 2 Options | PASS con observaciones | PASS con observaciones | PASS con observaciones | PASS con observaciones | Capturas históricas sin cambios de markup |
| Comparison — Feature Table | PASS con observaciones | PASS con observaciones | PASS con observaciones | PASS con observaciones | En 320 px requiere scroll horizontal Core; recorrido táctil/teclado pendiente |

No se afirma ausencia de overflow para los componentes PENDING/NOT VERIFIED ni equivalencia de altura entre tarjetas móviles.

## Accesibilidad y Gutenberg

| Área | Estado | Evidencia y límites |
|---|---|---|
| Hero Cover | PASS WITH LIMITATIONS para contraste estático; browser pendiente | Overlay al 70 % da ~6.10:1 teórico incluso compositado sobre blanco; no se midió el render RC.5. Headings/CTA Core; probar focus, contraste real y contenido seleccionado |
| Hero Split | PASS WITH LIMITATIONS | Semántica Core y orden de lectura estáticos; PO informó edición/guardado/reapertura anteriores. Alt editable/media real y teclado RC.5 no repetidos |
| Pricing / CTA | PASS — PO MANUAL QA en RC.4 | Tres variantes con CTA alineado, móvil, guardado/reapertura y sin bloque inválido según PO. Sin contraste, teclado o viewport exacto automatizados |
| Accordion | PARTIAL — browser evidence histórica | Firefox: click abre y Space cierra; Enter, Shift+Tab, foco visible y lector de pantalla pendientes |
| Tabs | NOT VERIFIED | Round-trip de parser pasa, pero no hubo browser/teclado, flechas, selección/panel o foco |
| Hover Zoom | PASS estático para reduced-motion; visual pendiente | La hoja respeta `prefers-reduced-motion`; probar foco, crop, overflow y que contenido esencial no dependa de hover |
| Imágenes / alt | NOT VERIFIED completamente | Hero Split conserva imagen Core editable y alt vacío serializable; falta imagen informativa/decorativa real y comprobación de alt |
| Contraste global / WCAG | NOT VERIFIED | Sin auditoría integral ni afirmación de conformidad WCAG |

No se hizo validación del editor Gutenberg JavaScript para RC.5. Los reportes del PO sobre Heroes y Pricing son **manuales** y se limitan a las operaciones/RC que el PO indicó; no sustituyen una inspección de consola, editor y teclado de RC.5.

### Gutenberg interactivo

| Evidencia | Resultado | Límite |
|---|---|---|
| PO: insertar, editar, guardar y reabrir Hero Cover y Hero Split | PASS — PO MANUAL QA anterior durante M6 | No equivale a una prueba JS automatizada; RC.5 no se abrió en el editor |
| PO: Pricing Cards, Featured y Compact en RC.4, guardar/reabrir y sin invalid blocks | PASS — PO MANUAL QA | No cubre otros patterns ni la actualización de RC.5 |
| Parser/serializer Core del repositorio | PASS para 22 patterns; `core/image` vacío de Hero Split tiene markup de save válido | No verifica interacciones del editor Gutenberg ni la consola del navegador |
| Tabs / Styles / todos los 22 patterns en editor | NOT VERIFIED | No declarar certificación interactiva fuera del subconjunto reportado |

## Seguridad, compatibilidad, performance

- Se reutiliza la auditoría estática anterior de 144 rutas (cero hallazgos reportables) y se revisa incrementalmente el diff actual: pattern Core, test y aclaración documental; no añadió PHP ejecutable, endpoint, secreto, dependencia, recurso remoto ni JavaScript. No es pentest ni nueva auditoría integral.
- Compatibilidad de mínimo: PHP 8.3.35 real + WP 7.1.3; PASS. No se repitió el estudio PHP 8.3.
- Performance: tres CSS existentes (3,858 bytes en total), cero JavaScript propio; sin benchmark ni afirmación cuantitativa de rendimiento.
- No-lock-in: el contenido almacenado son bloques Core y su hash permaneció intacto plugin-off/on en este fixture.

## Matriz de gates y responsables

| Gate | Componente | Requisito | Evidencia | Entorno | RC | Estado | Bloquea | Responsable / próxima acción |
|---|---|---|---|---|---|---|---:|---|
| Runtime mínimo | Plugin/Core | PHP 8.3 y WP 7.1 | Runtime 8.3.35; WP-CLI 7.1.3 | arm64, QA MariaDB | RC.5 | PASS | No | Ingeniería — cerrado en alcance probado |
| Regresión | Patterns/iconos/styles/CSS | 22/83/7/3, lint y suites | PHP/Python, Core parser/serializer, registros WP | WP 7.1.3 / PHP 8.3.35 | RC.5/source | PASS | No | Ingeniería — repetir si cambia código |
| Integridad | ZIP y manifiestos | hash, ZIP y 105 entradas verificables | SHA y `unzip -t`; package/source manifiestos coinciden | artefacto persistente | RC.5 | PASS | No | Ingeniería — preservar |
| Instalación limpia | Plugin | instalar/activar candidato en sitio vacío | no se ejecutó | Fixture nuevo pendiente | RC.5 | NOT VERIFIED | Sí | Ingeniería — nuevo fixture aislado |
| Upgrade | Plugin/contenido | 1.4.0 → RC actual con contenido preservado | evidencia previa llega a RC.3; directa a RC.5 ausente | Fixture upgrade RC.5 pendiente | RC.5 | NOT VERIFIED | Sí | Ingeniería — fixture separado, conservar datos QA actuales |
| No-lock-in | Contenido Core | sobrevivir plugin off/on | hash idéntico antes/durante/después | WP QA local / PHP 8.3.35 | RC.5 | PASS WITH LIMITATIONS | No | QA — completar flujo visual/editor si requerido |
| Block Styles visual | Accordion/Tabs/Hover Zoom | certificar siete variantes | markup, CSS, clases y HTTP; sin inspección visual | página QA ID 27, TT5 | RC.5 | NOT VERIFIED | Sí | PO/QA — capturas, estados, foco y contraste |
| Responsive | Heroes/Pricing/Accordion/Tabs/CTA/Comparison/Hover Zoom | 1440/768/390/320 | capturas históricas solo para patrones sin cambios; Pricing y Tabs abiertos | Firefox/TT5 histórico y QA actual | RC.5 | PENDING | Sí | PO/QA — completar componentes anotados |
| Gutenberg interactivo | Heroes/Pricing/Tabs/Styles | insertar, editar, guardar, reabrir, consola | PASS manual PO anterior para Heroes y Pricing; sin editor RC.5 | Firefox activo por usuario | RC.5 | PENDING PO VALIDATION | Sí | PO/QA — confirmar RC.5 y cubrir Tabs/Styles |
| Accesibilidad | Contenido/estilos | semántica, teclado, contraste, alt/foco | overlay calculado; click+Space parcial; inspección integral ausente | WP/Core y evidencia histórica | RC.5 | PASS WITH LIMITATIONS / NOT VERIFIED | Sí | PO/QA — teclado, contraste renderizado, alt y orden |
| Seguridad | Diff M6 | revisión incremental, sin nuevas superficies | auditoría previa 144 rutas + revisión markup/test/docs | fuente + artefacto | RC.5 | PASS WITH LIMITATIONS | No | Ingeniería — reabrir ante nuevo diff ejecutable |
| Aprobación del PO | Release | aceptación final independiente | no recibida | — | RC.5 | PENDING PO VALIDATION | Sí | PO — revisar evidencia tras cerrar gates |

## Release readiness y dictamen

**TECHNICAL NO-GO. M6 permanece ACTIVE. No recomendar ni publicar 1.5.0 todavía.** Los gates obligatorios de instalación limpia, upgrade RC.5, visual de Block Styles, matriz responsive, editor interactivo y accesibilidad siguen abiertos. El ajuste de contraste estático reduce el defecto identificado, pero aún exige comprobación renderizada. RC.5 es un candidato de QA, no una versión aprobada.

Procedimiento futuro, solo después de TECHNICAL GO y aprobación expresa del PO: congelar el código aprobado; preparar versión/changelog 1.5.0; revisión y commit propuesto; push a la rama aprobada; tag `v1.5.0`; generar ZIP estable y SHA-256; validar manifiesto/instalación; publicar release notes y plan de rollback. Ninguno de esos actos está autorizado ni se ejecutó en esta tarea.

## Estado Git y laboratorio

La rama sigue `feature/sci-hero-patterns-css-compat`, HEAD sigue `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`; cambios M5/M6 previos y los cambios de esta tarea permanecen sin commit. `git diff --check` pasó. No hubo checkout, reset, clean, commit, push, merge, tag ni publicación.

WP QA quedó con RC.5 activo, DB íntegra y tema TT5 1.5 activo; TT21 2.9 permanece instalado/inactivo. La instancia MariaDB ya estaba levantada; un intento inicial redundante de arranque encontró el lock del proceso existente, abortó sin tomar el datadir y dejó el registro de error de inicio en `the local MariaDB service log`. El servicio existente siguió respondiendo y la DB pasó sus checks; no se cambió configuración ni se alteró su contenido por ese intento. Los RC previos están preservados. Firefox/editor no se alteró después de que la automatización notificó actividad del usuario. No se modificaron servicios compartidos, la instalación estable 1.4.0 ni sitios de producción.
