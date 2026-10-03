# SCI Design Blocks 1.2 — Release Candidate Gate

**Fase:** M4 Phase A — RC preparado para revisión manual

**Estado:** M4 RC Fix 02 static/package PASS · manual visual gate pending

**Branch:** `feature/tabs-styles-1.2`
**Baselines:** WP 7.1.2 files available; PHP CLI 8.5.4; metadata remains plugin 1.1.0 until manual PASS.

## Precheck / product inventory

| Gate | Resultado | Evidencia |
|---|---|---|
| Branch/repo | PASS | Branch `feature/tabs-styles-1.2`; origin `https://github.com/nettixperu/SCI-Design-Blocks.git`. No push/release during RC phase. |
| v1.1.0 source/tag | PASS | Tag `v1.1.0` remains at `424aa1bb82b13a24dcfe842587d7435a4cad3205`; ZIP local SHA-256 captured before M4: `d43d97cca7689e42ff7f8d101e5497bb2953a737810e9d271d14e4a24150098f`. No v1.1.0 file/tag edits in this task. |
| Patterns / Icons | PASS | Registration smoke: 9 patterns; 1 collection; 83 icons. |
| Accordion / Tabs | PASS | Exactly `sci-minimal`, `sci-bordered`; RC Fix 02 target has exactly `sci-underline`, `sci-pills`, `sci-connected`, `sci-filled`. |
| Custom blocks / JS / deps | PASS | 0 custom SCI block registrations; 0 SCI JS files; no package/Composer/vendor/CDN/runtime dependency. |
| Platform metadata | PASS | Plugin source: Version 1.1.0, Requires at least 7.1, Requires PHP 7.4, GPL-2.0-or-later. |

## Static certification

- PHP lint: PASS for all product/test PHP files.
- Core registration, inventory and translation-label smoke: PASS.
- Core 7.1.2 `parse_blocks()` → `serialize_blocks()` → `parse_blocks()` for Underline and Pills: PASS, with three panels and Paragraph, Heading, List, Buttons, Group, Columns and Image content.
- Tabs CSS: 631 bytes, 4 rules, 5 selectors, no `!important`, media query, fixed width/height or hardcoded color; responsive rules are scoped to both SCI styles.
- Accordion CSS: 1,076 bytes; unchanged. Total product CSS: 1,707 bytes.
- SVG/security and dependency checks: PASS; 83 source SVGs match manifest; no product JS or dependency manifest.
- i18n: Underline/Pills labels use `__()` with `sci-design-blocks`; slugs are fixed identifiers.
- Security/semantics: no new endpoint, AJAX/REST, remote fetch, dynamic user CSS, eval, script injection or proprietary panel markup.
- `git diff --check`: PASS.
- Build/lint tooling: N/A; the repository has no npm/Composer build system for this CSS-only addition.

## Environment and limitations

- WordPress Core **7.1.2** source is installed locally at `/home/mgarcia/dev01/nettix-proposal-lab/wordpress` and was used for parser/source checks.
- PHP CLI **8.5.4**. PHP 7.4 runtime is not installed for an execution test.
- Twenty Twenty-Three, Twenty Twenty-Four and Twenty Twenty-Five theme directories are installed.
- No connected browser/editor was available. `http://localhost/` returned the default nginx page. No visual, activation, editor, front-end, deactivate/reactivate, narrow viewport or interactive keyboard result is claimed.
- Current WP stable beyond 7.1.2 and PHP 7.4 were not run in this environment.

## RC package

- Target: `sci-design-blocks-1.2-rc-test.zip` (one internal root: `sci-design-blocks/`).
- Runtime payload: bootstrap; `includes/`; `icons/manifest.php` and 83 SVGs; Accordion/Tabs CSS; README; GPL license; third-party MIT notice.
- Exclusions: `.git`, `poc/`, spike/internal reports, `tests/`, prior ZIPs, logs, caches, IDE files, and development tooling.
- Plugin metadata intentionally remains 1.1.0 in this RC. Stable version bump, ZIP, tag and publication wait for Product Owner confirmation of all six manual tests.
- ZIP integrity, extracted artifact checks and SHA-256: recorded after generation below.

## Manual gate for Product Owner

1. **Default:** insert Core Tabs without SCI style; expect normal Core appearance and behavior.
2. **Underline:** select SCI Underline; expect visible style and working tabs.
3. **Pills:** select SCI Pills; expect visible style and working tabs.
4. **Theme/color:** use Core text/background controls or contrasting contexts; expect readable theme-native colors.
5. **Mobile:** use long labels at narrow viewport; expect horizontal scroll, no destructive wrapping or page-level overflow.
6. **Interaction:** click and keyboard/focus through tabs with nested panel content; expect Core behavior intact.

| RC validation | Resultado |
|---|---|
| `zip -T` | PASS — test of `sci-design-blocks-1.2-rc-test.zip` OK. |
| Archive root/content/exclusion inspection | PASS — 93 files under one `sci-design-blocks/` root; no nested ZIP, absolute/traversal paths, `poc/`, tests, SDD/spikes, caches, node_modules or vendor. Runtime PHP/includes, both CSS files, icon manifest/83 SVGs, README, GPL license and third-party notice present. |
| Extracted PHP lint/registration/inventory/CSS/license checks | PASS — 5 packaged PHP files lint; copied external validation harness passed registration, CSS, SVG/dependency and license/notices byte comparisons. The harness was not included in the archive. |
| RC SHA-256 | `03c400eaa239e620290590451360b1bf1195324561d6d52053a0002abd13df56` |
| Manual gate | PENDING Product Owner |
| Stable 1.2.0 release/tag/publication | NOT AUTHORIZED before manual PASS |

**RC path:** `/home/mgarcia/projects/php/SCI Design Blocks/sci-design-blocks-1.2-rc-test.zip`

**Plugin metadata in RC:** 1.1.0 (version bump reserved until all manual release blockers pass).

**v1.1.0 ZIP current SHA-256:** `d43d97cca7689e42ff7f8d101e5497bb2953a737810e9d271d14e4a24150098f` (captured precheck and unchanged during this task).

## M4 RC Fix 01 — replacement candidate (2026-10-03)

- Product Owner amendment approves `Connected` as the third style. Registration is now `sci-underline`, `sci-pills`, `sci-connected`; **Boxed is REDUNDANT / NOT INCLUDED**, since a square-corner variant of Pills would not be meaningfully distinct.
- Connected uses scoped currentColor borders, a transparent bottom edge and one-pixel overlap for the active tab/panel join, and a subtle neutral `rgba(0, 0, 0, 0.08)` upper/lateral shadow. Active indicator override is confined to Connected. Core focus/ARIA/interactions are untouched.
- Updated Tabs CSS: 1,265 bytes; 8 rules; 10 selectors; 0 `!important`; 0 media queries. Accordion remains 1,076 bytes. Total SCI product CSS: 2,341 bytes. No fixed dimensions or brand palette; the single neutral shadow is the authorized exception to the no-hardcoded-colors check.
- Updated static inventory and Core parser roundtrip cover the three styles. Nine patterns, 83 icons/collection and two Accordion styles remain.
- Replacement artifact target: `sci-design-blocks-1.2-rc-fix-01.zip`. Baseline `sci-design-blocks-1.2-rc-test.zip` remains preserved; neither is a stable release.
- Replacement package integrity, extracted checks and checksum are recorded below. Product Owner manual tests: Default, Underline/Core colors, Pills/Core colors, Connected join/shadow/theme, and Mobile horizontal scroll. Boxed test is not applicable.
- Version remains 1.1.0 pending manual PASS. No stable 1.2.0 ZIP/tag/publication before that PASS.

| Replacement RC validation | Resultado |
|---|---|
| `zip -T` | PASS — `sci-design-blocks-1.2-rc-fix-01.zip` OK. |
| Archive root/content/exclusion inspection | PASS — 93 files / 99 ZIP entries including directories under one `sci-design-blocks/` root; runtime payload present; no tests, `.git`, prior ZIPs, `poc/`, SDD/spike docs, node_modules, or development tooling. |
| Extracted PHP lint | PASS — all five packaged PHP files. |
| Extracted registration/inventory | PASS — 9 patterns, 83 icons/one collection, 2 Accordion styles, 3 Tabs styles; Core-only/no custom block registration. |
| Extracted CSS/SVG/dependency checks | PASS — Tabs CSS 1,265 bytes/8 rules/10 selectors; Accordion CSS unchanged at 1,076 bytes; 83 SVGs valid; no frontend JS or external runtime dependency. |
| Core parser roundtrip | PASS — WordPress 7.1.2 for Underline, Pills, Connected with nested Core content. |
| Replacement RC size | 64,573 bytes. |
| Replacement RC SHA-256 | `930d331a6e1d9d64a9d1c1dd25048829cf1176dd07d237fc9298109a2493eed1` |
| Original RC SHA-256 | `03c400eaa239e620290590451360b1bf1195324561d6d52053a0002abd13df56` — unchanged. |
| v1.1.0 ZIP SHA-256 | `d43d97cca7689e42ff7f8d101e5497bb2953a737810e9d271d14e4a24150098f` — unchanged. |
| Manual gate | PENDING Product Owner; no browser/editor visual QA claimed. |

**Replacement RC path:** `/home/mgarcia/projects/php/SCI Design Blocks/sci-design-blocks-1.2-rc-fix-01.zip`

## M4 RC Fix 02 — final visual candidate (2026-10-03)

- Final approved visual inventory is Core Default plus Underline, Pills, Connected and Filled. No other Tabs style is authorized for 1.2; Boxed remains excluded as redundant, Segmented is not registered, icons remain deferred and images remain out of scope.
- Accent Color: **DERIVED FROM CORE/THEME COLORS**. WordPress 7.1.2 local evidence: `wp-includes/blocks/tabs/block.json` exposes text/background on `core/tabs`; `wp-includes/blocks/tab-list/block.json` exposes text/background and `__experimentalBorder.color` targeting tab buttons. No stable, dedicated accent control exists for active state. Filled uses `currentColor` + `color-mix()`; that CSS mechanism is used by Core 7.1.2 in `wp-includes/blocks/playlist-track/style.css`. No SCI color UI is added.
- Filled uses a lightly tinted tablist surface, modest rectangular segments, and an active currentColor-derived overlay/border. The active overlay is a `background-image` so Core's underlying `background-color` remains applied. It does not force equal widths or alter Core gap/justification.
- Fix 02 static tests require exactly four unique style registrations and scopes, shared nowrap/overflow, no global CSS, custom blocks, JS, dependencies, fixed dimensions, `!important`, or media queries. The only literal RGB value remains Connected's previously approved neutral shadow.
- Replacement package target: `sci-design-blocks-1.2-rc-fix-02.zip`. Fix 01 artifact remains immutable with SHA-256 `930d331a6e1d9d64a9d1c1dd25048829cf1176dd07d237fc9298109a2493eed1`.
- Source and extracted static checks: PASS. Replacement package integrity/checksum below. Manual Product Owner gate after RC: Core Default, Underline, Pills, Connected, Filled, Core/theme text/background color behavior, and mobile horizontal scroll. No stable 1.2.0 package/tag/release until manual PASS.

| RC Fix 02 validation | Resultado |
|---|---|
| Registration/inventory | PASS — 9 patterns, 83 icons/one collection, 2 Accordion styles, exactly 4 unique Tabs styles (Underline/Pills/Connected/Filled). |
| PHP lint | PASS — bootstrap, Tabs include, registration/roundtrip tests; all five packaged PHP files also pass. |
| WordPress Core roundtrip | PASS — WordPress 7.1.2 parse/serialize/parse fixtures for all four styles, three panels and nested Core content. |
| Tabs CSS | PASS — 1,906 bytes, 11 scoped rules, 14 selectors; no fixed dimensions, `!important`, or media queries. One previously approved neutral Connected shadow uses rgba; Filled's only new tints are `currentColor` `color-mix()` values. |
| Accordion/SVG/runtime inventory | PASS — Accordion CSS remains 1,076 bytes; all 83 SVGs match manifest; no frontend JS or runtime dependency. |
| `zip -T` / archive inspection | PASS — one `sci-design-blocks/` root, 93 files / 99 entries including directories; runtime files and licenses present, development/internal files excluded. |
| RC Fix 02 size | 64,739 bytes. |
| RC Fix 02 SHA-256 | `5d95871403f4ae117112ef337b77ce39e94a84b991361d9c6ba26aa86a01e6e9` |
| RC Fix 01 SHA-256 | `930d331a6e1d9d64a9d1c1dd25048829cf1176dd07d237fc9298109a2493eed1` — unchanged. |
| Manual UI/editor gate | PENDING Product Owner; not performed in this environment. |

**RC Fix 02 path:** `/home/mgarcia/projects/php/SCI Design Blocks/sci-design-blocks-1.2-rc-fix-02.zip`
