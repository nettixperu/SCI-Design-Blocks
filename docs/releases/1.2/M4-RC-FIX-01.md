# SCI Design Blocks 1.2 — M4 RC Fix 01

**Estado:** replacement RC generado y validación estática/extracción PASS; gate visual manual pendiente del Product Owner.

**Baseline:** `sci-design-blocks-1.2-rc-test.zip` (preservado).
**Release version:** no bump; source/RC metadata continúa en 1.1.0 hasta gate manual PASS.

## Cambio aprobado

- Añadido el Block Style Core `sci-connected`, label traducible `Connected`, con el mismo stylesheet `core/tabs`.
- Connected dibuja bordes contextuales en botones y `.wp-block-tab-panels`; el tab activo tiene borde inferior transparente, se solapa un píxel con el borde del panel y elimina el pseudo-underline Core solo bajo esta clase/estado. Añade una sombra superior/lateral neutral mínima `rgba(0, 0, 0, 0.08)`.
- Underline y Pills conservan sus reglas y contratos. Los tres estilos reciben nowrap + overflow horizontal. No cambia Core markup, JS, ARIA, focus handling, color controls, interacción ni contenido de panel.
- **Boxed: REDUNDANT — NOT INCLUDED.** La alternativa evaluada que solo haría cuadrado el borde/radio de Pills no es suficientemente distinta ni aporta utilidad separada de Connected. No se registra `sci-boxed`.
- No se añaden patterns, custom blocks, JS, dependencias, controles ni cambios de Accordion/Icon Library.

## CSS actualizado

- `assets/css/tabs-styles.css`: 1,265 bytes; 8 reglas; 10 selectores; 0 `!important`; 0 media queries.
- El único color fijo es la sombra neutral de baja opacidad autorizada por el brief; bordes/estados usan `currentColor`. No hay ancho/alto fijo, font rules, selector global o regla para contenido nested.
- Accordion CSS se mantiene en 1,076 bytes; CSS total SCI: 2,341 bytes.

## Targeted validation

- Registration smoke: exactamente `sci-underline`, `sci-pills` y `sci-connected` sobre `core/tabs`; labels pasan por `__()` con `sci-design-blocks`; sin `sci-boxed` u otro estilo.
- CSS test: responsive scope para los tres estilos; Underline/Pills protegidos; Connected verifica borde inferior activo transparente, pseudo-indicator local, sombra neutral y panel border; sin global rules.
- Parse/serialize/parse Core 7.1.2: tres fixtures con tres paneles y nested Core content, todos los estilos.
- Release regression: 9 patterns, 1 icon collection/83 iconos, 2 Accordion styles.
- PHP lint, SVG/dependency checks y `git diff --check`: ver resultado final tras empaquetado.

## Manual gate replacement RC

1. Core Default permanece igual.
2. Underline: color editable por Core y presentación.
3. Pills: color editable por Core y presentación.
4. Connected: sombra sutil, active tab unido al panel, sin borde inferior activo, adaptación al color/tema.
5. Mobile: labels largos, scroll horizontal sin overflow global.

Boxed no aplica porque fue descartado. La revisión visual real sigue a cargo del Product Owner; sin ese PASS no se crea ZIP estable, tag ni GitHub Release.

## Artefacto

- Replacement RC: `sci-design-blocks-1.2-rc-fix-01.zip`.
- Tamaño: 64,573 bytes. `zip -T`: PASS. El archivo contiene 93 archivos (99 entradas contando directorios), todos bajo `sci-design-blocks/`; no contiene tests, fuentes de desarrollo, SDDs/spikes, ZIPs, node_modules ni `.git`.
- Extracción temporal: PHP lint PASS en los cinco PHP empaquetados; registration smoke PASS (9 patterns, 83 iconos, 2 Accordion styles, 3 Tabs styles); Core parser roundtrip PASS en WordPress 7.1.2 para Underline, Pills y Connected; Tabs CSS, Accordion CSS, SVG, runtime dependency y license/notices checks PASS.
- SHA-256: `930d331a6e1d9d64a9d1c1dd25048829cf1176dd07d237fc9298109a2493eed1`.
- ZIP 1.2 RC anterior preservado sin cambios: SHA-256 `03c400eaa239e620290590451360b1bf1195324561d6d52053a0002abd13df56`. ZIP 1.1.0 preservado: SHA-256 `d43d97cca7689e42ff7f8d101e5497bb2953a737810e9d271d14e4a24150098f`.
- No se ejecutó QA visual/editorial en navegador WordPress; los cinco checks manuales siguen pendientes. No se creó versión estable, tag ni publicación.
