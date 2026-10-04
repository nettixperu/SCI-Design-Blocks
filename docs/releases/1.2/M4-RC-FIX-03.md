# SCI Design Blocks 1.2 — M4 RC Fix 03

**Bug:** long tab labels wrap within a tab on mobile.
**Status:** targeted static/package PASS; Product Owner mobile retest PASS (Product Owner-reported in final release authorization).
**Plugin metadata:** 1.1.0 (unchanged).

## Root cause

The confirmed browser symptom was label wrapping under a narrow viewport. Inspection of WordPress Core 7.1.2 located the relevant markup and layout behavior:

- `wp-includes/blocks/tab-list/block.json` stores each RichText label in a direct `button` child selected by `button`.
- `wp-includes/blocks/tab-list/style.css` sets the tab button to `width: max-content`, but does not set `flex-shrink` or `white-space`. A flex item therefore retains the initial `flex-shrink: 1`; the button also retains normal whitespace wrapping. In a constrained horizontal tablist, it can shrink and wrap the label.
- This explains the PO-observed result: the tablist stays horizontal and scrollable, but a long label breaks across lines inside its button.

## Fix

One shared CSS rule targets `.wp-block-tab-list button` only under the four SCI `core/tabs` style wrappers and applies:

```css
flex-shrink: 0;
white-space: nowrap;
```

The button keeps its Core content width and each label stays on one line. The existing scoped list rule still provides `flex-wrap: nowrap` and `overflow-x: auto`. Core Default receives neither rule. No width/min-width, ellipsis, hidden overflow, font-size change, media query, `!important`, JS, or visual redesign was added. Core justification remains untouched; no fixed/equal tab width was introduced.

## Validation

- WordPress Core markup/CSS evidence: checked from the local WordPress 7.1.2 source tree at `/home/mgarcia/dev01/nettix-proposal-lab/wordpress/wp-includes/blocks/tab-list/`.
- Fixture labels: `PRUEBA`, `PRUEBA2`, `MARTIN GARCIA SALAZAR`, `SERVICIOS DE INFRAESTRUCTURA`, `SEGURIDAD Y CUMPLIMIENTO`.
- Core 7.1.2 parse/serialize/parse: PASS for Underline, Pills, Connected, Filled; each fixture has five tabs/panels and existing nested Core content.
- Static registration: PASS — 9 patterns, 1 icon collection/83 icons, 2 Accordion styles, exactly 4 Tabs styles.
- PHP lint: PASS for plugin and modified PHP tests, and all five PHP files in extracted ZIP.
- Tabs CSS: 2,202 bytes, 12 rules, 18 scoped selectors; no new colors, fixed dimensions, truncation, font sizing, media queries, or `!important`.
- Accordion CSS: 1,076 bytes unchanged. SVG checks: all 83 manifest assets pass. No frontend JS or runtime dependency.
- Package: `zip -T` PASS; 93 files / 99 ZIP entries under one `sci-design-blocks/` root; runtime files and licenses present; source tests/internal docs/spikes/PoC/older ZIPs excluded.

Static tests establish selector scope and serialization but cannot prove browser layout or absence of page-level overflow. The Product Owner's targeted mobile visual retest remains required.

## Artifact

- Path: `/home/mgarcia/projects/php/SCI Design Blocks/sci-design-blocks-1.2-rc-fix-03.zip`
- Size: 64,779 bytes.
- SHA-256: `6f28037c3d1b40ae7d7681f37df9959b8e2c1cc8b0c73d7500581c74d20c0589`.
- Frozen RC Fix 02 SHA-256: `5d95871403f4ae117112ef337b77ce39e94a84b991361d9c6ba26aa86a01e6e9` (unchanged).

## Product Owner retest — mobile only

Use a narrow viewport and these labels: `PRUEBA`, `PRUEBA2`, `MARTIN GARCIA SALAZAR`, plus one longer label. Confirm all remain single-line in one horizontal row, full text is readable by scrolling the tablist, and the page itself does not gain horizontal overflow. The shared selector statically covers all four SCI styles; one representative style is sufficient unless visual differences appear.

**At RC Fix 03 completion:** release was blocked pending the Product Owner retest; no stable 1.2.0 ZIP, tag, or GitHub Release existed at that point. The Product Owner later reported the targeted mobile retest PASS; final release evidence is in `RELEASE-TEST-MATRIX-1.2.md`.
