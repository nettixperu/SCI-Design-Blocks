# SCI Design Blocks 1.2 — M4 RC Fix 02

**Estado:** static/package PASS; Product Owner manual visual gate pending.
**Plugin metadata:** 1.1.0 (unchanged during RC work).
**Baseline:** `sci-design-blocks-1.2-rc-fix-01.zip` (preserved, SHA-256 below).

## Final Tabs style inventory

- Core Default remains unchanged.
- SCI opt-in styles: `sci-underline` / Underline, `sci-pills` / Pills, `sci-connected` / Connected, and `sci-filled` / Filled.
- Boxed remains `REDUNDANT — NOT INCLUDED`; Segmented is not registered. Icons in labels remain deferred; images in labels remain out of scope.
- All four styles share the scoped horizontal `nowrap` + `overflow-x: auto` rule. No custom block, frontend JS, dependency, equal-width rule, custom controls, media query, fixed dimensions, or changes to Core tab state/ARIA/focus/deep linking.

## Accent Color capability decision

**ACCENT COLOR: DERIVED FROM CORE/THEME COLORS**

Evidence inspected in the local WordPress Core 7.1.2 checkout (`/home/mgarcia/dev01/nettix-proposal-lab/wordpress`):

- `wp-includes/blocks/tabs/block.json`: `core/tabs` exposes text and background color supports.
- `wp-includes/blocks/tab-list/block.json`: `core/tab-list` exposes text/background for tab buttons. It also declares `__experimentalBorder.color` with selector `.wp-block-tab-list button`; this is an experimental general border control, not a stable dedicated accent control for selected state.
- No stable independent Accent Color attribute/control is available across the Tabs block styles. Therefore no SCI Inspector, picker, attribute, or custom editor code is added. Underline/Pills/Connected use `currentColor`; Filled derives a subtle fill from `currentColor`.
- WordPress Core 7.1.2 itself uses `color-mix(in srgb, currentColor ..., transparent)` in `wp-includes/blocks/playlist-track/style.css`. General Core color/theme behavior is described in [WordPress color settings](https://developer.wordpress.org/themes/global-settings-and-styles/settings/color/).

## Filled implementation

- Registration: `sci-filled`, translated label `Filled`, same `core/tabs` stylesheet handle.
- The tablist receives a low-opacity currentColor-derived surface. Buttons are small-radius rectangular segments; the active button receives a currentColor-derived tint image, contextual border, and inset line.
- The active tint uses `background-image`, leaving any `background-color` set by Core underneath it. No color identity is hardcoded and Core text/background controls remain available. This behavior still needs visual confirmation in the real editor/theme gate.
- Filled differs structurally from Pills' full-radius individual buttons and Connected's active tab-to-panel join.

## Static validation

- Registration: PASS — exactly four translated styles; 9 patterns, 83 icons/one collection, 2 Accordion styles.
- Core parser roundtrip: PASS on WordPress 7.1.2 for all four styles, each with three panels and nested Core content.
- PHP lint: PASS for plugin/test PHP and all five PHP files in extracted package.
- Tabs CSS: 1,906 bytes; 11 rules; 14 selectors; 0 `!important`; 0 media queries; no fixed widths/heights or brand colors. One neutral Connected shadow remains as approved in RC Fix 01. Filled uses `currentColor` mixing only.
- Accordion CSS: 1,076 bytes and unchanged. SVG/source inventory: 83 manifest-matched SVGs. No frontend JS, dependency manifest, or runtime external dependency.
- ZIP: `zip -T` PASS; 93 files (99 archive entries including directories), one `sci-design-blocks/` root, runtime payload/licenses included, development and internal files excluded.

## RC artifact

- `sci-design-blocks-1.2-rc-fix-02.zip`
- Size: 64,739 bytes.
- SHA-256: `5d95871403f4ae117112ef337b77ce39e94a84b991361d9c6ba26aa86a01e6e9`.
- RC Fix 01 remains unchanged: SHA-256 `930d331a6e1d9d64a9d1c1dd25048829cf1176dd07d237fc9298109a2493eed1`.
- Plugin version remains 1.1.0. No 1.2.0 stable ZIP, tag, or publication was made.

## Product Owner manual gate

1. Core Default unchanged.
2. Underline, Pills, Connected, and Filled in editor/frontend.
3. Core text/background colors, Group/background context, and light/dark theme context. No separate SCI Accent picker is expected; accent states derive from Core/theme colors.
4. Connected has no active bottom edge/gap and only a subtle shadow.
5. Filled is clearly distinct from Pills, has a clear selected state, respects theme colors, and does not force equal widths.
6. Long labels remain on one horizontal scrollable row on mobile without page-level overflow.

No browser/editor visual QA was available during this task; these checks remain pending before stable release preparation.
