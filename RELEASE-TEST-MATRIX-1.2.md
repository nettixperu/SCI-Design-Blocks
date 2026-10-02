# SCI Design Blocks 1.2 — Release Candidate Gate

**Fase:** M4 Phase A — RC preparado para revisión manual

**Estado:** STATIC PASS · RC validation pending · manual gate pending

**Branch:** `feature/tabs-styles-1.2`
**Baselines:** WP 7.1.2 files available; PHP CLI 8.5.4; metadata remains plugin 1.1.0 until manual PASS.

## Precheck / product inventory

| Gate | Resultado | Evidencia |
|---|---|---|
| Branch/repo | PASS | Branch `feature/tabs-styles-1.2`; origin `https://github.com/nettixperu/SCI-Design-Blocks.git`. No push/release during RC phase. |
| v1.1.0 source/tag | PASS | Tag `v1.1.0` remains at `424aa1bb82b13a24dcfe842587d7435a4cad3205`; ZIP local SHA-256 captured before M4: `d43d97cca7689e42ff7f8d101e5497bb2953a737810e9d271d14e4a24150098f`. No v1.1.0 file/tag edits in this task. |
| Patterns / Icons | PASS | Registration smoke: 9 patterns; 1 collection; 83 icons. |
| Accordion / Tabs | PASS | Exactly `sci-minimal`, `sci-bordered`; exactly `sci-underline`, `sci-pills`. |
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
