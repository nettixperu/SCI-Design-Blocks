# SCI Design Blocks 1.2.0 — Software Design Document

- **Estado:** APPROVED / FROZEN FOR IMPLEMENTATION
- **Versión objetivo:** 1.2.0
- **Producto base:** SCI Design Blocks 1.1.0
- **WordPress mínimo:** 7.1
- **PHP mínimo:** 7.4, sin cambios salvo evidencia técnica y aprobación explícita
- **Feature:** estilos SCI opt-in y scroll horizontal visual para el bloque Core `core/tabs`
- **Rama de implementación:** `feature/tabs-styles-1.2`, derivada del commit etiquetado `v1.1.0`

Este documento es el contrato aprobado de SCI Design Blocks 1.2.0. El discovery está cerrado y sus decisiones de arquitectura y alcance quedan fijadas aquí. Las enmiendas M4 autorizan Connected (2026-10-03) y Filled (2026-10-03); Boxed sigue excluido. La arquitectura Core Tabs + Block Styles + CSS permanece intacta. El Product Owner autorizó este RC Fix 02 como último ajuste funcional antes del freeze visual. No se inicia otro feature sin solicitud explícita.

---

## 1. Propósito y resultado

SCI Design Blocks 1.2.0 añade cuatro presentaciones opt-in al bloque de WordPress Core Tabs. SCI no crea ni registra un bloque Tabs propio.

```text
SCI Tabs experience
= core/tabs
+ Underline Block Style
+ Pills Block Style
+ Connected Block Style
+ Filled Block Style
+ CSS acotado para scroll horizontal
```

El estilo visual será theme-native y conservará los controles, la estructura de contenido y la interacción de Core. Los tabs seguirán horizontales en todos los viewports; si los labels exceden el espacio, la lista podrá desplazarse horizontalmente.

## 2. Baseline y protección del release

SCI Design Blocks 1.1.0 publicado contiene:

- WordPress mínimo 7.1 y PHP mínimo 7.4;
- 9 patterns Core;
- una SCI Icon Library con 83 iconos curados de Bootstrap Icons v1.13.1 bajo MIT;
- estilos Core Accordion Minimal y Bordered;
- cero bloques SCI custom;
- cero JavaScript frontend SCI;
- cero dependencias runtime externas.

El commit/tag `v1.1.0`, el ZIP `sci-design-blocks-1.1.0.zip`, el GitHub Release y su checksum son inmutables. El desarrollo 1.2 parte del commit al que apunta `v1.1.0`, en una nueva rama de feature. Ningún milestone puede mover, recrear o reescribir tags anteriores.

La rama actual de investigación `spike/tabs-1.2` contiene documentación y PoC aislados. Tras la aprobación del SDD, la implementación comienza en una rama nueva desde `v1.1.0`; solo se incorpora el SDD aprobado y el cambio de producto autorizado. No integrar el PoC experimental automáticamente.

## 3. Autoridad y principios

Prioridad:

1. WordPress Core;
2. APIs Core estables;
3. Block Styles y patterns Core, si un patrón futuro demuestra valor;
4. CSS SCI mínimo y scoppado;
5. bloque SCI custom únicamente si Core no resuelve el caso y el Technical Director aprueba el cambio de arquitectura.

Principios obligatorios: Core first, reuse first, sencillez, mantenibilidad, seguridad, accesibilidad por defecto, theme-native y contenido Core portable.

Codex puede corregir automáticamente defectos locales de CSS, registro, traducción, compatibilidad visual, lint, documentación y empaquetado dentro del contrato. Debe detenerse y escalar si un requisito exige un bloque custom, JavaScript frontend, override de Core, nuevo runtime dependency, cambio de WordPress mínimo, gran arquitectura CSS, o cambio material de alcance/accesibilidad.

## 4. Decisiones de arquitectura cerradas

1. Usar `core/tabs`, `core/tab-list`, `core/tab-panels` y `core/tab-panel` de WordPress 7.1+.
2. Registrar exactamente cuatro estilos Core sobre `core/tabs`: `sci-underline`/`Underline`, `sci-pills`/`Pills`, `sci-connected`/`Connected` y `sci-filled`/`Filled`, todos traducibles.
3. No añadir patterns 1.2 por defecto. El inserter Core crea dos tabs; añadir un tercero es una operación nativa y simple.
4. Mantener las interacciones, serialización y relaciones ARIA/IDs de Core. SCI no crea, duplica ni modifica estado del frontend.
5. No añadir JavaScript, Interactivity API code de SCI, dependencias runtime, endpoints, requests remotos ni build pipeline.
6. Aplicar `flex-wrap: nowrap` y `overflow-x: auto` (o equivalente justificado) a la lista Core solamente cuando se aplique un estilo SCI.
7. Mantener Underline, Pills, Connected y Filled como estilos opt-in. El estilo Core por defecto no cambia.
8. Los colores, tipografía, spacing, gap y justificación expuestos por Core siguen bajo los controles Core y `theme.json`/Global Styles.
9. Sin color de marca SCI. El indicador Underline usa `currentColor` cuando el indicador Core existente sea suficiente.
10. Core URL-fragment activation queda habilitado sin cambios. SCI no implementa, personaliza ni deshabilita deep linking.
11. Labels con iconos: deferred. Labels con imágenes: fuera del alcance. No crear Tabs custom para soportar ninguno.
12. Sin requisito de tabs de ancho completo/igual. La justificación distribuida Core separa botones de ancho automático.
13. Si SCI se desactiva, Core Tabs, sus contenidos e interacción permanecen; las clases/presentaciones SCI y su scroll CSS pueden desaparecer. Este comportamiento es aceptado.
14. Enmienda M4 aprobada por Product Owner (2026-10-03): Connected se añade como tercer estilo. Boxed se evalúa y queda fuera por redundante con Pills; no se registra `sci-boxed`.
15. Enmienda RC Fix 02 aprobada por Product Owner (2026-10-03): Filled es el cuarto y último estilo para 1.2. Tabs visual scope queda congelado en Core Default, Underline, Pills, Connected y Filled; no añadir otro estilo en 1.2.
16. Accent Color: **DERIVED FROM CORE/THEME COLORS**. No existe un control Core estable e independiente para un acento activo de Tabs: `core/tabs` ofrece text/background; `core/tab-list` ofrece text/background y `__experimentalBorder.color` general para los botones, no un Accent Color estable dedicado al estado activo. No crear control SCI. Los estados SCI derivan de `currentColor` y, en Filled, de una mezcla de `currentColor` con transparencia.

La inspección de capacidades se realizó en WordPress 7.1.2: `/home/mgarcia/dev01/nettix-proposal-lab/wordpress/wp-includes/blocks/tabs/block.json` declara text/background para `core/tabs`; `.../blocks/tab-list/block.json` declara text/background y `__experimentalBorder.color` para botones mediante el selector Core `.wp-block-tab-list button`. Ese soporte de borde es experimental y general, no un control independiente estable de Accent Color para el estado activo. Por tanto, Accent Color es **DERIVED FROM CORE/THEME COLORS**. La superficie/estado de Filled deriva de `currentColor` con `color-mix`; Core 7.1.2 también usa este mecanismo en `wp-includes/blocks/playlist-track/style.css`. No se añade UI SCI.

Referencias: [Tabs block](https://wordpress.org/documentation/article/tabs-block/), [register_block_style](https://developer.wordpress.org/reference/functions/register_block_style/), [Block Supports](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-supports/), [Color settings and theme.json](https://developer.wordpress.org/themes/global-settings-and-styles/settings/color/), [theme.json](https://developer.wordpress.org/block-editor/reference-guides/theme-json-reference/theme-json-living/), [Interactivity API](https://developer.wordpress.org/block-editor/reference-guides/interactivity-api/), [WAI-ARIA Tabs Pattern](https://www.w3.org/WAI/ARIA/apg/patterns/tabs/).

## 5. Alcance funcional

### Incluido

- Cuatro Block Styles traducibles en `core/tabs`: Underline, Pills, Connected y Filled.
- Scroll horizontal nativo en las cuatro presentaciones SCI cuando los tabs no caben.
- Compatibilidad con justificación Core izquierda/centro/distribuida y botones de ancho automático.
- Visualización respetuosa de texto/background colors y typography configurados por Core/tema.
- CSS mínimo, scoppado a clases de estilo SCI.
- Regresión estática y manual de Core Tabs y del contenido existente del plugin.

### No incluido

- Bloques SCI `tabs`, `tab`, `tab-panel` o cualquier bloque nuevo.
- JavaScript frontend SCI, Interactivity API de SCI, jQuery o librerías.
- Vertical tabs, íconos o imágenes en labels, full/equal-width buttons.
- Boxed, Classic, Cards, Segmented, Vertical u otros estilos fuera de la biblioteca aprobada.
- Pattern de tres tabs por defecto.
- Accordion responsive, dropdown, hamburger o transformación vertical.
- Auto-switching, autoplay, animaciones/framework de movimiento, carousel, flechas o arrastre.
- Deep-link customization o hash state de SCI.
- AJAX, REST, remote content, datos dinámicos, persistencia propia o schema SEO.
- Cambios al Icon Library, Accordion, los 9 patterns existentes u otros bloques Core.

## 6. Contrato de estilos

### 6.1 Identificadores

| Elemento | Contrato |
|---|---|
| Block type objetivo | `core/tabs` |
| Style slugs | `sci-underline`, `sci-pills`, `sci-connected`, `sci-filled` |
| Visible style labels | `Underline`, `Pills`, `Connected`, `Filled` |
| Text domain | `sci-design-blocks` |
| Wrapper classes generadas por Core | `.is-style-sci-underline`, `.is-style-sci-pills`, `.is-style-sci-connected`, `.is-style-sci-filled` |

Los slugs CSS son técnicos y no traducibles; los labels visibles son traducibles.

### 6.2 Underline

- Mantener el indicador activo Core basado en `currentColor`; no duplicarlo si Core ya entrega el tratamiento requerido.
- Presentación editorial ligera, sin colores SCI hardcodeados, tipografía fija, sombra grande ni gradiente.
- Habilitar fila no envolvente y scroll horizontal al seleccionar este estilo.
- No modificar roles, estados, atributos ni handlers Core.

### 6.3 Pills

- Añadir solo la forma estructural necesaria: borde y radius; la API Core border/radius puede estar presente pero es experimental en el baseline verificado.
- Diferenciar estructuralmente el tab activo mediante estilos scoppados al estado Core `[aria-selected="true"]`; no depender exclusivamente de una pequeña diferencia de color.
- Heredar colores y valores del tema. Se permiten `currentColor` y valores de presets/variables Core; no paletas propias ni colores de marca.
- Preservar el foco visible nativo. No reutilizar ni suprimir el outline de foco para simular el estado activo.
- Si una decisión CSS de contraste depende del tema/browser, validar en fondos claros y oscuros durante M2. No usar un color global fijo para invertir foreground/background.
- Habilitar la misma fila no envolvente y scroll horizontal que Underline.

### 6.4 Connected (enmienda M4 aprobada)

- Usa bordes `currentColor` en los botones y en el contenedor Core `.wp-block-tab-panels`; el botón activo pierde su borde inferior y se solapa un píxel con el borde del panel para crear continuidad.
- El pseudo-elemento underline de Core se suprime solo en el botón activo de Connected; ARIA/estado Core no se modifica.
- Una sombra superior mínima `rgba(0, 0, 0, 0.08)` aporta separación sin introducir color de marca. El borde sigue el color contextual. Sin fondo forzado, controles propios ni reglas para el contenido dentro del panel.
- Recibe el mismo nowrap + overflow horizontal scoped que Underline y Pills.

Boxed se evaluó y se descarta como redundante con Pills. Mantenerlo fuera del registro.

### 6.5 Filled (enmienda M4 RC Fix 02 aprobada)

- El tablist completo recibe una superficie tenue derivada de `currentColor` mediante `color-mix(in srgb, currentColor 5%, transparent)`. No añade una paleta ni decide identidad cromática.
- Los botones mantienen una forma rectangular de radio corto; el botón activo recibe un borde `currentColor`, un fondo translúcido derivado de `currentColor` y una línea inset para marcar el estado con estructura además de color.
- El fill activo se compone como `background-image` sobre el `background-color` de Core, para conservar debajo el valor establecido mediante los controles Core. Texto sigue heredado de Core.
- No fuerza igual ancho, no modifica gap/justificación ni paneles y comparte la regla de nowrap + overflow horizontal.
- Se distingue de Pills (píldoras individuales de radio completo y borde/inset) y Connected (tab activo unido al panel con borde/sombra).

### 6.6 Reglas compartidas

- Selectores comienzan desde `.wp-block-tabs.is-style-sci-underline`, `.wp-block-tabs.is-style-sci-pills`, `.wp-block-tabs.is-style-sci-connected` o `.wp-block-tabs.is-style-sci-filled`.
- Los botones tab de los cuatro estilos usan `white-space: nowrap` y `flex-shrink: 0` para preservar labels largos de una línea y su ancho de contenido. Core 7.1.2 define botones directos como hijos flexibles con `width: max-content`, pero deja el shrink por defecto activo y wrapping normal; el fix no se aplica a Core Default.
- No afectar `core/tab-panel` ni bloques hijos dentro del panel.
- No usar selectores globales, resets, alta especificidad innecesaria, `!important`, valores de color marca o font rules.
- No esconder scrollbars.
- No añadir media queries salvo necesidad demostrada; no introducir JS responsive.
- Preservar flex justification y auto width de Core; no forzar full width.
- La hoja de estilos Tabs se mantiene pequeña y legible; métricas actuales del RC Fix 02 quedan registradas en `../releases/1.2/M4-RC-FIX-02.md`. No imponer un límite artificial de bytes.

### 6.7 Registro y asset

Registrar estilos mediante `register_block_style()` para `core/tabs`. Usar un CSS pequeño dedicado (`assets/css/tabs-styles.css`) salvo que durante M1 se demuestre una opción más sencilla sin acoplar Tabs a Accordion. Registrar/enqueue el handle mediante la API Core. Favorecer carga asociada a estilos Core cuando la API lo permita. No añadir tooling de build para CSS plano.

No cambiar `assets/css/accordion-styles.css` ni la lógica de Accordion como parte de la arquitectura Tabs. El include nuevo debe seguir el namespace/estilo PHP existente del plugin y tener nombres únicos. El header/versión del plugin permanece 1.1.0 durante M1–M3; M4 cambia a 1.2.0 cuando se autorice preparar el release.

## 7. Contrato Core y compatibilidad de contenido

Todas estas operaciones son **CORE CONTRACT / REGRESSION GATE**, no funciones SCI:

- insertar y seleccionar tabs;
- crear, quitar, renombrar, reordenar y duplicar tabs/paneles;
- editar contenido anidado en paneles;
- guardar, cerrar/reabrir, serializar y undo/redo;
- active tab state, click y teclado;
- ARIA, focus y relaciones tab-panel;
- URL-fragment activation heredado sin modificación.

SCI no debe interceptar los controles/acciones del editor ni volver a representar los botones/paneles. El CSS no debe impedir Paragraph, Heading, Image, List, Buttons, Group, Columns, Accordion, Feature compositions u otros Core blocks dentro de paneles.

`core/tabs` permanece en el documento y en el editor con SCI desactivado. No hay migración ni fallback SCI que construir.

## 8. Requisitos explícitos

### Arquitectura

- **ARCH-01:** Implementar Tabs exclusivamente sobre los bloques Core Tabs disponibles desde WordPress 7.1.
- **ARCH-02:** No registrar ningún custom block type ni una arquitectura de bloques/paneles SCI.
- **ARCH-03:** Registrar exactamente cuatro Block Styles mediante `register_block_style()` para `core/tabs`.
- **ARCH-04:** No añadir paquetes/dependencias runtime ni pipeline build para CSS plano.
- **ARCH-05:** Mantener el desarrollo de 1.2 en rama nueva basada en `v1.1.0`; no alterar la historia/tag/release 1.1.0.

### Tabs y estilos

- **TAB-01:** Core conserva label, panel, editor, serialización, interacción, estado y operaciones de Tabs.
- **TAB-02:** `activeTabIndex`/default selection y la relación tab-panel permanecen bajo Core.
- **TAB-03:** Label sigue siendo el formato Core RichText; icono deferred, imagen fuera de scope.
- **STYLE-01:** Registrar slug `sci-underline`, label traducible `Underline`.
- **STYLE-02:** Registrar slug `sci-pills`, label traducible `Pills`.
- **STYLE-08:** Registrar slug `sci-connected`, label traducible `Connected`; unir visualmente el tab activo con el panel usando CSS scoped y sin modificar Core state.
- **STYLE-09:** Registrar slug `sci-filled`, label traducible `Filled`; superficie visual de navegación y estado seleccionado segmentado, derivados de `currentColor`, sin sustituir el `background-color` Core.
- **STYLE-03:** Los estilos son opt-in; tabs sin clase SCI conserva presentación Core por defecto.
- **STYLE-04:** Underline mantiene el indicador `currentColor` Core donde corresponda.
- **STYLE-05:** Pills diferencia activo estructuralmente usando estado Core, sin identidad cromática SCI.
- **STYLE-06:** Estilos no bloquean controles de texto/fondo, tipografía, spacing, gap, border o radius disponibles en Core/tema.
- **STYLE-07:** Estilos no alteran contenido ni presentación de bloques internos de los paneles.

### Responsive / tema

- **RESP-01:** Con cualquiera de los cuatro estilos SCI, la fila permanece horizontal, cada label permanece en una sola línea y no wrappea al superar su ancho disponible; el tablist admite scroll horizontal.
- **RESP-02:** En desbordamiento, la lista tablist admite desplazamiento horizontal nativo.
- **RESP-03:** No hay conversión a Accordion, dropdown, hamburger, vertical tabs ni controles carousel.
- **RESP-04:** No hay JavaScript responsive; preservar foco y acceso teclado a tabs fuera del viewport.
- **THEME-01:** Core/Global Styles gobiernan paleta, tipografía y presets expuestos.
- **THEME-02:** Justificación Core izquierda, centro y distribuida sigue operativa.
- **THEME-03:** Tabs usan ancho automático; no prometen botones iguales/full width.
- **THEME-04:** Revisar fondos claros/oscuros, color contrastante, labels largos y herencia sin hardcodear identidad.

### Accesibilidad / semántica

- **A11Y-01:** Roles `tablist`, `tab`, `tabpanel`; `aria-selected`, `aria-controls`, `aria-labelledby`, `tabindex`, IDs y su sincronización permanecen bajo Core.
- **A11Y-02:** Keyboard activation/focus behavior Core se mantiene; SCI no duplica keyboard handlers ni state.
- **A11Y-03:** Los estilos no eliminan outlines/focus visible, hacen tab controls inalcanzables ni comunican estado solo con un cambio cromático sutil.
- **A11Y-04:** QA verifica click, teclado, focus y panel; no reclamar certificación WCAG formal.

### SEO / performance / seguridad / i18n / compatibilidad

- **SEO-01:** Todo panel permanece en markup Core entregado; no AJAX, schema ni cambios de jerarquía de headings.
- **PERF-01:** Cero JS frontend SCI, requests remotos o dependencias runtime nuevas.
- **PERF-02:** Tabs CSS es pequeño, scoppado y asociado a los Block Styles cuando Core pueda cargarlo así.
- **SEC-01:** Cero REST/AJAX, user HTML processing SCI, CSS dinámico inseguro, inyección de scripts, eval o endpoint nuevo.
- **I18N-01:** Labels visibles Underline/Pills/Connected/Filled usan text domain `sci-design-blocks`; slugs técnicos no se traducen.
- **COMPAT-01:** WordPress mínimo sigue siendo 7.1; PHP mínimo sigue siendo 7.4.
- **COMPAT-02:** Sin compatibilidad hacia WordPress anterior a 7.1 ni polyfills.
- **REG-01:** Mantener nueve patterns en total.
- **REG-02:** Mantener una icon collection y sus 83 iconos; no cambiar procedencia, licencia ni comportamiento.
- **REG-03:** Mantener únicamente los dos estilos existentes de Accordion (Minimal, Bordered) y su interacción.
- **REG-04:** No introducir bloques custom, JS frontend SCI, ni estilos por defecto no opt-in como regresión.
- **REG-05:** No producir invalid block markup, PHP warnings/notices, JS errors del plugin o errores de estilo atribuibles al cambio.

## 9. Datos, IDs y persistencia

SCI no tiene modelo de datos Tabs. No añade atributos, metadata, opciones, tablas, transients, REST, query, endpoints, anchor generation ni IDs. Core conserva el árbol de bloques y deriva/gestiona los vínculos de acuerdo con su implementación. No guardar un estado SCI activo separado.

## 10. Seguridad y privacidad

La feature es CSS y registro local de Block Styles. No recibe ni procesa datos de usuario en PHP/JS, no genera markup de usuario, no ejecuta código, no se comunica con terceros, no añade telemetría y no persiste información adicional. Los paneles siguen bajo APIs, permisos y renderizado Core.

## 11. Estrategia de pruebas

### 11.1 Validación por milestone

M1–M3 usan validación dirigida al delta. M4 corre una certificación completa una sola vez. No ejecutar toda la matriz por cada ajuste de CSS.

### 11.2 Static validation

- PHP lint del bootstrap e includes modificados.
- Registration smoke: exactamente cuatro estilos Tabs registrados en `core/tabs`, labels/slugs correctos, sin custom blocks.
- CSS selector/scope check: únicamente wrapper styles SCI y tab list/button requerido; cero reglas para contenido de panel global; sin `!important`/brand color, media queries, anchos o altos fijos.
- Filled conserva `background-color` Core bajo la capa de `background-image`; su tinte se deriva de `currentColor` con `color-mix`.
- Labels `PRUEBA`, `PRUEBA2`, `MARTIN GARCIA SALAZAR`, `SERVICIOS DE INFRAESTRUCTURA` y `SEGURIDAD Y CUMPLIMIENTO` pasan por los cuatro Core parser roundtrips; el CSS check exige `white-space: nowrap` + `flex-shrink: 0` únicamente en botones bajo las cuatro clases SCI.
- Checks rechazan `text-overflow`, truncation por `overflow:hidden`, fixed dimensions, global selectors y media queries.
- Accent Color documentado como `DERIVED FROM CORE/THEME COLORS`; no existe control SCI.
- Confirmar cero assets JS nuevos, cero `package.json`/lockfile o build tooling añadidos y cero runtime dependencies.
- `git diff --check`; revisar diff staged y no staged por separado durante release.

### 11.3 Manual UI gate compacto

1. Core Tabs sin estilo SCI conserva apariencia/controles por defecto.
2. Underline se selecciona y funciona en editor y frontend.
3. Pills se selecciona y funciona en editor y frontend.
4. Connected muestra unión tab/panel, borde inferior activo ausente y sombra sutil.
5. Filled se ve como superficie segmentada distinta de Pills; estado activo claro sin anchos iguales forzados.
6. Cambiar text/background colors, Group/background y contexto contrastante; comprobar herencia/theme controls. No esperar picker Accent SCI; los acentos son derivados.
7. Labels largos en viewport estrecho: horizontal scroll, una fila, sin overflow horizontal de página.

### 11.4 Regression

- Core: insertar, añadir, borrar (sin permitir 0), renombrar, reordenar, duplicar tab/panel, guardar/reabrir y undo/redo.
- Content: Paragraph, Heading, Image, List, Buttons, Group, Columns, Accordion y feature pattern dentro de panel.
- Layout: Group y Column como ancestros; tabs internos no son criterio de soporte.
- Themes: Twenty Twenty-Five y al menos otro block theme Core disponible; fondos claros/oscuros.
- SCI 1.1: 9 patterns, 83 iconos y collection; Minimal/Bordered Accordion; sin invalid blocks.
- Deactivation: Core Tabs, contenidos e interacción siguen; las apariencias/scroll SCI pueden desaparecer.

### 11.5 Compatibilidad

Certificación manual en WordPress 7.1.x como versión mínima (preferir 7.1.2 si la instalación desechable está disponible) y versión WordPress estable actual en M4. PHP 7.4 debe al menos aceptar sintaxis/runtime del plugin; M4 registra las versiones realmente probadas. No alegar que se probó una versión no disponible.

## 12. Milestones y release gates

| Milestone | Objetivo | Fin requerido |
|---|---|---|
| M1 — Tabs Styles Foundation | Registrar Underline/Pills, CSS estructural y smoke estático. | M1 acceptance PASS y revisión explícita antes de M2. |
| M2 — Responsive + Theme Integration | Probar overflow, controles Core, tema y estados visuales. | UI/responsive/theme acceptance PASS y revisión explícita antes de M3. |
| M3 — QA / Core Regression | Asegurar que Core y 1.1 mantienen comportamiento/contenido. | Regresión y contenido PASS, sin defectos blocker, revisión antes de M4. |
| M4 — Hardening + Release 1.2.0 | Certificación final, versionado, paquete y release. | Release gates completos y publicación solo dentro de autorización explícita. |

### M1 acceptance

- Ambos estilos registrados exactamente una vez y en `core/tabs`.
- Default Core permanece sin cambio.
- Estilos son opt-in, scoped y traducciones presentes.
- Interacción Core intacta; cero JS SCI.
- CSS cumple budget de sencillez; PHP lint y registration/static checks PASS.

### M2 acceptance

- Underline/Pills no envuelven tabs; overflow horizontal usable en viewport estrecho.
- Justificación izquierda/centro/distribuida, ancho automático, colores, tipografía, padding/gap y controles Core disponibles permanecen efectivos.
- Tema claro/oscuro, labels largos y foco visible aceptables.
- Ningún JS ni CSS global; sin desbordamiento horizontal de la página causado por el componente.

### M3 acceptance

- Operaciones Core listadas en §7 pasan gate manual dirigido.
- Panels admiten contenido anidado previsto, guardar/reabrir y duplicar/reordenar no invalida bloques.
- Deep-link Core heredado no se altera.
- Regresión 1.1 completa: 9 patterns, 83 iconos, collection, 2 Accordion styles.
- No plugin errors/warnings atribuibles, no custom blocks, scripts o dependencias.

#### M3 execution record — 2026-10-02

- **Static/Core source gate:** PASS en WordPress Core 7.1.2; cuatro metadata `block.json` de Tabs/Tab List/Tab Panels/Tab Panel presentes. Core mantiene edición/operaciones, serialización, ARIA, teclado, foco y deep linking; SCI solo registra dos Block Styles y no añade interacción.
- **Parse/serialize:** PASS con fixtures de tres panels y contenido Core anidado para ambos estilos, mediante el parser/serializador Core 7.1.2.
- **SCI 1.1 regression:** PASS: nueve patterns, una icon collection con 83 iconos, dos estilos Accordion y dos Tabs; metadata sigue en 1.1.0, mínimo WordPress 7.1 y PHP 7.4.
- **Static checks:** PHP lint, registration/i18n smoke, Tabs/Accordion CSS, SVG/dependency inventory y `git diff --check`: PASS. No se modificó CSS de producto, Accordion, Icon Library, patterns, versión, ZIP ni tags.
- **Entorno visual:** PHP CLI 8.5.4; temas Twenty Twenty-Three, Twenty Twenty-Four y Twenty Twenty-Five instalados. No había editor/browser conectado: localhost devolvió la página predeterminada de nginx. Interacción visual, focus/scroll en viewport estrecho y deactivate/reactivate quedan para el gate manual M4.
- **Estado M3:** PASS para QA Core estática y regresión; listo para revisión antes de M4. Esta anotación no inicia ni autoriza M4.

### M4 acceptance / Definition of Done

- Todo requirement aplicable PASS o excepción con aprobación escrita del Technical Director/Product Owner.
- WordPress 7.1.x gate y versión WP estable actual registrados; PHP mínimo verificado.
- CSS, PHP, i18n, seguridad, accesibilidad, SEO/semántica y performance gates completos.
- Release packaging excluye temporales/development artifacts según política existente, ZIP pasa `zip -T`, estructura y runtime revisados, smoke desde ZIP pasa.
- Plugin metadata, README, changelog/release notes, version 1.2.0, tag anotado y GitHub Release son coherentes.
- SHA-256 registrado; artefacto instalado probado es exactamente el release ZIP publicado.
- v1.1.0/tag/ZIP/release/checksum siguen intactos.

#### M4 Phase A — RC status — 2026-10-02

- Certificación estática, regresión 1.1, documentación RC y validación del ZIP RC completadas; versión del plugin se mantiene en 1.1.0 mientras el gate visual esté pendiente.
- WordPress/editor/browser real no estuvo disponible para completar las seis pruebas manuales. El RC queda para evaluación del Product Owner; no se crea ni publica tag/release 1.2.0 ni ZIP estable hasta recibir confirmación manual PASS.
- Evidencia detallada: `../releases/1.2/RELEASE-TEST-MATRIX-1.2.md`.

#### M4 RC Fix 01 — visual amendment — 2026-10-03

- Product Owner aprueba `Connected` como tercer Core Block Style y autoriza evaluar `Boxed`. Se implementa `sci-connected`; `sci-boxed` se declara redundante con Pills y queda fuera.
- Connected aplica borde contextual, elimina el borde inferior solo al botón activo, une visualmente con el borde de `.wp-block-tab-panels` y usa una sombra neutral muy sutil. Core mantiene interacción, estados, foco, ARIA y deep linking.
- Boxed no se añade porque solo cuadrar el radio de Pills sería una variante redundante. No cambia arquitectura, versión, mínimo de plataforma, cantidad de patterns, custom blocks ni JS.
- RC anterior preservado; reemplazo: `sci-design-blocks-1.2-rc-fix-01.zip`. SHA-256 y targeted validation se registran en `../releases/1.2/M4-RC-FIX-01.md`.
- El manual gate del Product Owner sigue pendiente; no publicar ni etiquetar versión estable 1.2.0 antes del PASS manual.
- Reemplaza el RC previo `sci-design-blocks-1.2-rc-test.zip`; el ZIP previo se conserva sin modificar.

#### M4 RC Fix 02 — final Tabs visual scope — 2026-10-03

- Product Owner aprueba `Filled` como cuarto y último estilo SCI Tabs para 1.2. Inventario final: Core Default, Underline, Pills, Connected y Filled; no se añaden más estilos en 1.2.
- Accent Color: **DERIVED FROM CORE/THEME COLORS**. Evidencia WP 7.1.2: `core/tabs/block.json` expone text/background; `core/tab-list/block.json` expone text/background y `__experimentalBorder.color`, sin control estable e independiente para el acento del estado activo. No se añade control SCI. `color-mix()` usado por Filled también está presente en el CSS Core `blocks/playlist-track/style.css` del mismo checkout.
- Filled presenta una superficie tenue en el tablist y segmento activo con borde y fill derivado de `currentColor`. Usa `background-image` para conservar debajo el `background-color` que Core aplica. Sin JS, controles propios, palette de marca, igual ancho forzado ni styling de paneles.
- Boxed permanece `REDUNDANT — NOT INCLUDED`; Segmented no se registra; iconos deferred e imágenes fuera de scope. CSS/responsive sigue scoped y compartido.
- El nuevo RC se valida estáticamente y desde extracción; WordPress visual/editor gate queda para el Product Owner. Plugin metadata permanece 1.1.0; no se crea stable ZIP/tag/release sin PASS manual.
- Evidencia, métricas y checksum: `../releases/1.2/M4-RC-FIX-02.md` y `../releases/1.2/RELEASE-TEST-MATRIX-1.2.md`.

#### M4 RC Fix 03 — long labels in mobile tablist — 2026-10-03

- Product Owner reportó wrapping en labels largos al estrechar el viewport, aunque la lista ya permitía scroll horizontal.
- Root cause inspeccionada en Core 7.1.2: `tab-list/block.json` guarda labels como texto RichText de cada `button`; `tab-list/style.css` da a esos botones `width: max-content`, pero no cambia `flex-shrink` (su valor flex inicial sigue siendo `1`) ni `white-space` (wrapping normal). El tablist horizontal puede comprimir un botón y partir su label.
- Fix mínimo compartido: en botones descendientes de los cuatro wrappers SCI, `white-space: nowrap` y `flex-shrink: 0`. No se aplica a Core Default; no añade width/min-width, truncation, font adjustment, breakpoint ni JavaScript.
- Test fixture usa cinco labels representativos y verifica roundtrip Core 7.1.2 en los cuatro estilos. Static test verifica selector compartido, propiedades, scope y ausencia de truncation/global leakage.
- RC Fix 02 se preserva sin cambios; replacement RC Fix 03 y su checksum se documentan en `../releases/1.2/M4-RC-FIX-03.md` y abajo. Manual retest requerido: viewport móvil y labels largos; no repetir el gate visual completo salvo regresión.
- Plugin version permanece 1.1.0. Release 1.2.0 queda bloqueado hasta que el Product Owner confirme PASS móvil.

#### M4 Final Release — 1.2.0 — 2026-10-03

- El Product Owner confirmó el gate manual final: Underline, Pills, Connected y Filled mantienen labels en una sola línea y permiten overflow/scroll horizontal en móvil; Core Default conserva su comportamiento nativo, sin no-wrap SCI. El resultado se registra como PASS / EXPECTED, respectivamente. Esta evidencia manual procede del Product Owner; no se atribuye a una prueba de navegador realizada por Codex.
- Se actualizó únicamente la versión del plugin a 1.2.0, README de release, la aserción de versión del smoke test y documentación de certificación. Requisitos permanecen WordPress 7.1+ y PHP 7.4+; las capacidades de 1.1 se mantienen.
- Certificación source y del ZIP final, checksum, integración `main`, tag anotado y GitHub Release se consignan en `../releases/1.2/RELEASE-TEST-MATRIX-1.2.md` al completarse cada gate.
- El ZIP/tag/release/checksum de v1.1.0 y todos los RC ZIPs permanecen inmutables.

## 13. Asset y release policy

Mantener CSS Tabs en `assets/css/tabs-styles.css` y registro en un include PHP pequeño siguiendo `SCI\DesignBlocks`. No editar CSS del Accordion salvo que una colisión concreta esté reproducida y aprobada. No añadir Node, npm, Composer ni dependencia de build para una hoja CSS pequeña.

En M1–M3 conservar versión de producción 1.1.0 en headers y ZIP; los artefactos de test deben tener nombre distintivo y no sobrescribir v1.1.0. En M4 cambiar plugin/README/release metadata a 1.2.0 y generar un ZIP nuevo, nunca regenerar o sustituir 1.1.0. El release final no se considera completo hasta que se valida desde ZIP.

Tag recomendado `v1.2.0`; no tocar tags preexistentes. Push y creación de GitHub Release/asset ocurren únicamente cuando el pedido de ejecución M4 los autorice de forma expresa y las credenciales/remotes coincidan con el repositorio SCI Design Blocks esperado.

## 14. Open issues

No hay blocker arquitectónico. El patrón Core de tres tabs fue explícitamente rechazado como requisito inicial. Iconos, imágenes y full-width tabs tienen estado/alcance fijado en §4. Boxed sigue excluido; Filled es el cuarto y último estilo. El comportamiento hash Core fue aceptado. El soporte experimental border/radius no es contrato obligatorio ni se usa como Accent Color. El SDD está APPROVED / FROZEN FOR IMPLEMENTATION; solo los milestones expresamente autorizados pueden ejecutarse.

## 15. Trazabilidad resumida

| Requirement group | Principal verification |
|---|---|
| ARCH | Source diff, registration smoke, no custom block/dependency/build. |
| TAB | Confirmación de Core block tree y operaciones Core en gate manual. |
| STYLE | Inserter/style selector, opt-in default, colors/currentColor, scoped CSS. |
| RESP | Una fila, scroll horizontal, touch/keyboard/focus en narrow viewport. |
| THEME | Core controls, palettes, presets, justification, light/dark themes. |
| A11Y | Core ARIA/keyboard/focus conservados; no outlines ocultos. |
| SEO | Panel content stays in Core document markup; no schema/AJAX. |
| PERF | No JS/requests/dependencies; CSS small and style-associated. |
| SEC | Static source/diff checks; no endpoints, dynamic unsafe CSS or user-code path. |
| I18N | Underline/Pills translated via correct domain; slugs stable. |
| COMPAT | WP 7.1.x and current WP gates; PHP 7.4 compatibility. |
| REG | 9 patterns, 83 icons/collection, two Accordion styles unchanged. |

---

# Implementation Prompts — M1 to M4

Los siguientes prompts son artefactos de ejecución para las fases futuras. Copiar solo el prompt de la fase autorizada. No comenzar un milestone por la mera presencia de su prompt en este documento.

## Prompt M1 — Tabs Styles Foundation

```text
PROYECTO: SCI Design Blocks
VERSIÓN OBJETIVO: 1.2.0
MILESTONE: M1 — Tabs Styles Foundation
ESTADO DE AUTORIZACIÓN: Ejecutar únicamente cuando el Product Owner solicite M1.

ROLES
Product Owner: Martín García
Technical Director: ChatGPT
Developer: Codex

CONTRATO
Lee SDD-1.2.md completo antes de modificar. El SDD gobierna este trabajo.
No vuelvas a investigar si hace falta un bloque Tabs custom: la decisión está cerrada.
Usar exclusivamente core/tabs y la API Core register_block_style().

BASELINE INMUTABLE
- WordPress mínimo: 7.1.
- PHP mínimo: 7.4.
- Rama de implementación nueva basada en el commit/tag v1.1.0. No modificar main, tag, commit, ZIP, Release ni checksum v1.1.0.
- La rama de spike puede contener SDD/PoC no release. Incorporar solo el SDD aprobado y los cambios autorizados; no copiar PoC experimental al plugin automáticamente.
- Plugin y paquete siguen siendo 1.1.0 durante M1–M3.

OBJETIVO
Registrar exactamente dos estilos sobre core/tabs:
- slug sci-underline, label traducible “Underline”;
- slug sci-pills, label traducible “Pills”.
Añadir la base CSS necesaria y preservar el estilo Core default.

CAMBIOS PERMITIDOS
- Añadir un include pequeño, preferentemente includes/tabs-styles.php.
- Añadir assets/css/tabs-styles.css.
- Añadir el require del include en sci-design-blocks.php, siguiendo namespace SCI\DesignBlocks y convenciones existentes.
- Actualizar documentación/reporte M1 solicitados por el gate.

RESTRICCIONES
- No custom blocks ni patterns nuevos.
- No JavaScript de editor o frontend; no Interactivity API SCI.
- No dependencias, Node/npm/build system, endpoints, options, DB, red remota o telemetría.
- No tocar icon library, patrones existentes, Accordion, sus estilos ni su comportamiento.
- No cambiar version metadata ni generar ZIP de release.
- CSS solo bajo .wp-block-tabs.is-style-sci-underline / .is-style-sci-pills y descendientes tab-list/button requeridos. No afectar contenido interior.
- No hardcodear colores de marca, tipografía ni spacing tokens SCI; conservar currentColor/tema/Core. Mantener outline/focus Core.
- Underline reutiliza el indicador Core currentColor donde sea suficiente.
- Pills debe distinguir seleccionado estructuralmente usando estado Core sin depender solo de cambio leve de color, preservar foco y no asumir color del fondo. Si no puede lograrse sin violar theme-native/contraste, reporta el caso; no introduzcas paleta fija.
- Nombre visible y traducción exactos. Slugs técnicos no traducibles.

MÉTODO
1. Verifica branch, HEAD/tag base, git status y diff antes de editar. Conserva todo untracked previo.
2. Inspecciona el estilo Accordion solo para seguir patrón de registro/namespacing; no lo refactorices.
3. Registra CSS con un handle y register_block_style() para core/tabs.
4. Implementa únicamente registro y CSS de presentación mínima. Default Core sin clase SCI debe quedar intacto.
5. Ejecuta php -l en bootstrap/includes tocados, registration smoke para exactamente dos estilos y cero custom blocks, CSS scope/static checks, git diff --check.
6. Revisa visualmente el diff para confirmar que no entraron artefactos ajenos.
7. Documenta validaciones reales. No declares browser QA que no se haya ejecutado.

ACCEPTANCE M1
- Underline/Pills aparecen una vez en estilos de core/tabs.
- Core Tabs default no cambia.
- Core controls/interactions siguen siendo responsabilidad de Core.
- CSS acotado; cero JS SCI, dependencias, build tooling, patrones/bloques nuevos.
- PHP lint y static registration smoke PASS.

SI HAY DEFECTO LOCAL MENOR
Diagnostica, corrige con el cambio mínimo y valida dirigido. No pares por typos, selector scope o error de registro solucionable dentro del contrato.

DETENTE SI
Se requiere custom block, frontend JS, override de Core, dependencia, WordPress >7.1, o cambio material de accesibilidad/scope. Documenta evidencia y no continúes fuera del SDD.

ENTREGA M1
- archivos creados/modificados;
- slugs/labels y registro;
- CSS rule/selector count;
- comandos y resultados;
- qué no se probó en navegador;
- regresiones Core/1.1 observadas;
- estado M1 PASS / BLOCKED;
- confirmación explícita: no iniciar M2.
```

## Prompt M2 — Responsive + Theme Integration

```text
PROYECTO: SCI Design Blocks
VERSIÓN OBJETIVO: 1.2.0
MILESTONE: M2 — Responsive + Theme Integration
ESTADO DE AUTORIZACIÓN: Ejecutar únicamente después de revisión PASS de M1 y solicitud explícita del Product Owner.

Lee SDD-1.2.md y el reporte aprobado de M1 antes de trabajar. No reabrir la decisión Core Tabs. Rama/versión/immutabilidad siguen como define el SDD.

OBJETIVO
Validar y terminar el comportamiento responsive/theme-native de los dos estilos SCI sobre core/tabs. Se permiten fixes CSS pequeños dentro del contrato; no se implementa feature adicional.

VALIDAR
- Underline y Pills continúan como estilos opt-in; Core default queda normal.
- nowrap + overflow-x horizontal solo con clase SCI; no Tabs→Accordion/dropdown/vertical.
- left/center/distributed Core justification sigue operativa.
- Botones siguen ancho automático; no full-width/equal-width.
- Text/background colors Core y presets del tema.
- Font size Core, typography inheritance; sin controls/styles SCI tipográficos.
- Padding, gap y wrapper margin/padding Core.
- Contenido nested Core no recibe estilos SCI.
- Fondos claros/oscuros, contextos de color contrastante, labels cortos/largos, scrollbar nativo, touch/trackpad y viewport estrecho.
- Focus visible y tabs que están fuera del viewport permanecen alcanzables con teclado.
- No ocultar scrollbar, outlines ni controles. No JS.

ENTORNO
Usa una instalación WordPress real disponible. Identifica WP/PHP/theme exactos usados. Probar Twenty Twenty-Five y otro block theme Core cuando estén disponibles; si el segundo tema o versión no está instalado, reporta limitación y no simules evidencia. Realiza browser QA focalizado, no repitas release gate completo.

AUTOFIX
Diagnostica y corrige defectos reproducibles de selector, overflow, theme inheritance, contraste estructural o focus styling con cambios mínimos CSS. No pares por problemas menores. No uses color marca, !important, media query o specificity global como parche rápido. Una media query solo se acepta con caso reproducible/documentado.

VALIDACIÓN
- QA manual de Underline/Pills en editor y frontend.
- Prueba mobile/narrow con label largo; scroll horizontal funciona, lista no envuelve y página no adquiere overflow lateral accidental.
- Confirma left/center/distributed y Core color/spacing controls.
- Revisa modo/fondo claro y oscuro, foco teclado.
- Corre solamente validaciones afectadas por los fixes (CSS/static checks; PHP lint si PHP cambia).
- Revisa diff y git diff --check.

NO HACER
No cambiar version; no ZIP final; no M3; no patrón; no feature; no editar SDD fuera de documentar una contradicción reproducida y bloqueante. No cambiar JS porque está prohibido.

ENTREGA M2
Reporta entornos exactos, matriz compacta de los escenarios anteriores, defectos/fixes y evidencia, comandos, archivos modificados, riesgos no verificados y estado M2 PASS / BLOCKED. Espera revisión; no iniciar M3.
```

## Prompt M3 — QA / Core Regression

```text
PROYECTO: SCI Design Blocks
VERSIÓN OBJETIVO: 1.2.0
MILESTONE: M3 — QA / Core Regression
ESTADO DE AUTORIZACIÓN: Ejecutar únicamente después de revisión PASS de M2 y solicitud explícita del Product Owner.

Lee SDD-1.2.md, reportes M1/M2 y baseline v1.1.0. Esta fase es QA dirigida y hardening de defectos dentro de scope, no añade funcionalidad.

OBJETIVO
Demostrar que SCI styles no alteran contrato Core Tabs ni regresan SCI 1.1.

CORE TABS GATE
- Inserta Core Tabs y usa default sin estilo: se conserva presentación Core.
- Selecciona Underline/Pills y guarda/cierra/reabre; estilos persisten.
- Add, remove, rename, reorder, duplicate tab/panel, undo/redo.
- Panel incluye Paragraph, Heading, Image, List, Buttons, Group, Columns, Accordion y Feature composition.
- Tabs bajo Group y Column.
- Click y keyboard: Tab, Left/Right, Home/End, Enter/Space según comportamiento Core 7.1; confirma ARIA, tab-panel linkage y focus visible.
- Deep-link/URL-fragment Core behavior sigue heredado e intacto, sin código SCI.
- Plugins desactivado/reactivado: core/tabs, paneles y contenido quedan Core; solo styling responsive/visual SCI puede perderse.
- No invalid blocks ni JS/PHP errors atribuibles al plugin.

SCI 1.1 REGRESSION GATE
- Los 9 patterns originales siguen registrados y disponibles.
- La collection Icon Library sigue igual y contiene los 83 iconos.
- Minimal y Bordered Accordion siguen siendo los dos estilos SCI de Accordion; interacción Core y contenido no cambian.
- No custom SCI blocks, no frontend JS, no runtime deps.
- Verifica current release metadata sigue 1.1.0 en M1–M3 y no se alteró el ZIP/tag/release de 1.1.

FIX POLICY
Reproducible small CSS/registration/i18n/docs defects: fix immediately and run targeted validation. No refactor, algorithm change, third style, pattern, custom block, JS, runtime dependency, changed min version, override Core, or changed SDD contract. Si un defecto cae fuera del contrato, detente y escala con pasos de reproducción y evidencia.

VALIDACIÓN
Usa una instalación real y anota WordPress/PHP/theme/browser. Mantén reporte de test compacto; no generes decenas de screenshots. Corre linters/static checks según archivos cambiados, registration smoke, diff check. No hagas npm ci/build/audit: esta feature no tiene build tooling, salvo que el repositorio haya adquirido dependencias con aprobación externa, en cuyo caso detente y consulta el SDD.

ENTREGA M3
- resultados Core gate;
- resultados 1.1 regression;
- bugs/fixes y targeted validation;
- WP/PHP/theme/browser exactos;
- comandos y resultados;
- diff de archivos;
- limitaciones manuales;
- estado M3 PASS / BLOCKED.
Espera revisión explícita. No iniciar M4 ni preparar release.
```

## Prompt M4 — Hardening + Release 1.2.0

```text
PROYECTO: SCI Design Blocks
MILESTONE: M4 — Hardening / Release 1.2.0
ESTADO DE AUTORIZACIÓN: Ejecutar solo después de M3 PASS, review del Technical Director y solicitud explícita del Product Owner para release.

Lee SDD-1.2.md y los resultados aprobados de M1, M2 y M3. No añadir funcionalidades ni reabrir decisiones aprobadas. Este es el único gate de certificación completa.

PRECHECK
- Confirmar repo/owner/remotes/branch y permisos esperados para SCI Design Blocks; no cambiar origin automáticamente.
- Confirmar base v1.1.0 intacta y todos los cambios de 1.2 revisados.
- Confirmar nueve patterns, 83 icons/collection, dos Accordion styles, cuatro Tabs styles, cero custom blocks, cero SCI frontend JS, cero external runtime deps.
- Confirmar PHP minimum 7.4, WP minimum 7.1 o escalar si evidencia exige cambio.
- Mantener archivos locales/user work no relacionados. No hacer `git add .`.

FINAL CERTIFICATION
1. Ejecutar PHP lint para todos los PHP del plugin.
2. Validar registro de 9 patterns, icon collection/83 assets, 2 Accordion styles y 4 Tabs styles.
3. Validar CSS scope, no colores marca, no global leakage, no unnecessary !important, tabs CSS pequeño.
4. Confirmar sin JS build/runtime SCI nuevo, build tools o runtime dependency.
5. Revisar requisitos ARCH/TAB/STYLE/RESP/THEME/A11Y/SEO/PERF/SEC/I18N/COMPAT/REG.
6. Ejecutar `git diff --check`, inspeccionar staged/unstaged, no incluir `poc/`, fase/spike interno ni artefactos no aprobados en ZIP.
7. Ejecutar el manual WordPress gate compacto para Core Default, Underline, Pills, Connected, Filled, color/theme y mobile horizontal scroll. No esperar un control Accent SCI: el estado se deriva de colores Core/tema. Boxed no está incluido.
8. Probar con WP 7.1.x (preferir 7.1.2 disponible) y versión WP estable actual; probar PHP 7.4 si el entorno existe. Registrar versiones exactas y no afirmar pruebas no ejecutadas.
9. Validar deactivate/reactivate content-first behavior.

VERSION / PACKAGE
- Solo ahora cambiar la versión de plugin y docs a 1.2.0.
- Actualizar README y changelog/release notes si existe política en el repo; no reescribir historia de 1.1.
- Generar `sci-design-blocks-1.2.0.zip` mediante la política de package aprobada; no incluir node_modules, ZIP previo, temporales, SDD/spikes internos, Poc ni IDE metadata salvo que release policy indique explícitamente lo contrario.
- `zip -T`, inventory/exclusions, extraer a directorio temporal, PHP lint y registration smoke desde ZIP extraído.
- Calcular SHA-256 y probar el ZIP exacto que se publicará.

GIT / PUBLICACIÓN
- Preparar cambios con staging explícito y verificar listado/estadística antes del commit.
- Crear commit y tag anotado `v1.2.0` únicamente en el commit validado.
- Push branch/tag y crear GitHub Release/asset solo cuando esta solicitud autorice publicación y remote/permisos estén verificados. Sin autorización de publicación explícita, detenerse después del ZIP validado y checksum y reportar listo para publicación.
- No force push, rebase destructivo ni reescritura de historia/tag.
- Nunca reemplazar/modificar tag, commit, ZIP, GitHub Release o checksum v1.1.0.

RELEASE GATE
Si hay discrepancia, fallo de compatibilidad, test crítico fallido, checksum inesperado, remote/branch distinto, error de autenticación o archivo no autorizado, detener publicación y reportar. No ocultar ni corregir cambios fuera del SDD.

ENTREGA M4
Reporta versión, WordPress/PHP/theme/browser exactos, todos los gates con PASS/FAIL/NOT RUN, build/linters si aplican, PHP/static/registration, ZIP y ruta, zip -T, inventario/exclusiones, smoke desde ZIP, SHA-256, commit/tag/push/release/asset si autorizados, git status, archivos modificados y estado `SCI DESIGN BLOCKS 1.2.0 RELEASE READY` o `RELEASE BLOCKED`.
STOP al terminar; no iniciar otra feature.
```
