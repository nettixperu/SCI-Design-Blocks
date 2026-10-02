# SCI Design Blocks — Software Design Document 1.1

**Status:** M1 and M2 implemented on the development branch; no 1.1.0 release has been created.
**Development version:** `1.1.0-dev`
**Minimum WordPress:** 7.1
**PHP minimum:** 7.4 (unchanged plugin baseline)
**Text domain:** `sci-design-blocks`
**License:** GPL-2.0-or-later

This document records the approved architecture and M2 catalog decisions for the SCI Icon Library. The 1.0.0 tag, GitHub release, and release ZIP remain frozen and represent the WordPress 7.0+ line.

## 1. Scope

M1 established the registry and source manifest. M2 expands it to the final curated set of **83** Bootstrap Icons from the 89-name proposal. Six proposed assets were omitted where the Core inventory already identifies a GOOD or ACCEPTABLE equivalent. No new patterns, Accordion Styles, Tabs, brand icons, SVG upload, custom blocks, or release package are included.

The nine existing design patterns remain Core block compositions and remain registered by the existing pattern code.

## 2. Compatibility and WordPress Core API

The 1.1 development line requires WordPress 7.1 because the public icon registration API is available in that version. There is no WordPress 7.0 compatibility layer, polyfill, or proprietary fallback.

The plugin uses:

- `wp_register_icon_collection()` to register the single `sci-design-blocks` collection, displayed as **SCI**;
- `wp_register_icon()` to register each named SVG using a trusted local `file_path`;
- WordPress `core/icon` as the editor block, picker, controls, and server renderer. Core’s renderer calls `wp_get_icon()` for the stored handle; SCI does not create a second rendering path.

Handles use the Core form `collection/icon-name`, e.g. `sci-design-blocks/hdd-rack`. No `sci/icon`, custom Icon block, picker, modal, search UI, endpoint, or editor package is added.

## 3. Collection and curated manifest

`icons/manifest.php` is the static source of truth. Each record has an explicit local name, translatable label, upstream library/version/name, conceptual category, and modification flag. The associated source is exactly `icons/svg/<local_name>.svg`; each manifest entry must have one SVG and every SVG must correspond to one manifest entry.

The collection contains 83 icons across the nine conceptual categories in [ICON-INVENTORY-1.1.md](ICON-INVENTORY-1.1.md). The 89 proposed names were reduced by excluding these Core-equivalent candidates:

- `chat-dots` — Core `comment` covers messages.
- `link-45deg` — Core `external` is an acceptable link symbol.
- `lightbulb` — Core `tip` is an acceptable idea symbol.
- `chat-quote` — Core `quote` covers quotations.
- `graph-up-arrow` — Core `chart-bar` is an acceptable growth/analytics symbol.
- `list-check` — Core `check` is acceptable for checklist/task use.

The remaining 83 are unmodified Bootstrap Icons v1.13.1 files. They total **56,805 raw SVG bytes**; `icons/manifest.php` is **28,687 bytes**, for an approximate manifest-plus-source size of **85,492 bytes** before PHP/runtime representation. Core searches by icon name and label; it does not accept a separate keyword, alias, or category field. Conceptual categories are manifest metadata and do not create extra Core collections or UI.

## 4. Content-first no-lock-in

Approved trade-off: **CONTENT-FIRST NO-LOCK-IN**.

- Patterns and surrounding content remain WordPress Core blocks.
- A selected icon is stored as the namespaced handle in `core/icon`; the SVG is not copied into post content.
- With SCI inactive, the `core/icon` block and handle remain, while the SCI glyph may not render.
- With SCI reactivated, the same handle should resolve and the glyph should return without reselection.
- The Product Owner reports that manual PoC testing confirmed restoration after reactivation. No custom fallback, content mutation, custom block, picker, font, frontend JavaScript, or serialization workaround is permitted.

## 5. Licensing and asset provenance

The plugin remains GPL-2.0-or-later. The 83 redistributed Bootstrap Icons files retain their source/version/name and MIT provenance in the manifest. `THIRD-PARTY-NOTICES.md` includes the complete MIT text. The plugin’s principal `LICENSE` is unchanged; no library license is mixed into that file.

Catalog growth is a manually reviewed, pinned snapshot. There are no runtime downloads, CDN assets, npm/composer runtime packages, or automatic upstream updates.

## 6. Security and Core SVG sanitization

Only trusted static repository SVGs are registered. No user-provided SVG, upload handling, MIME changes, or custom sanitizer is present. WordPress 7.1.2’s inspected allowlist accepts `svg`, `path`, and `polygon` with supported attributes. Bootstrap’s source files set `fill="currentColor"` on the root `svg`; Core strips that one unsupported presentation attribute, and Core’s `core/icon` stylesheet supplies `fill: currentColor`. The static check rejects other unsupported tags/attributes and active or remote content. WordPress runtime integration remains the final check.

The manifest path is code-owned. Icon names are validated before constructing local file paths. Registration uses `file_path`; raw SVG content is not concatenated into frontend markup by SCI.

## 7. Performance and assets

- The editor discovers the registered collection through Core’s same-site, authenticated REST API and Core picker.
- The picker loads all 83 icons when the SCI collection is selected; search is Core behavior over icon name and label. No SCI lazy-loading mechanism is added.
- Frontend rendering uses Core’s `core/icon` renderer and emits only the selected icon SVG inline. The full catalog is not enqueued as a frontend asset.
- SCI frontend CSS: none.
- SCI frontend JavaScript: none.
- External frontend requests and icon fonts: none.

## 8. Internationalization

Collection and icon labels use literal `__()` calls with text domain `sci-design-blocks`, allowing standard WordPress translation extraction. Technical handle names are stable lowercase slugs and are not translated. No new i18n tooling is introduced.

## 9. Validation

Run:

```sh
php -l sci-design-blocks.php
php -l includes/icon-library.php
php -l icons/manifest.php
php tests/check-icon-library.php
python3 tests/check-icon-svg.py
```

The PHP static smoke test stubs Core registration and checks one collection, 83 unique icon registrations across the nine categories, and exactly the existing nine pattern names. The Python check validates manifest/source correspondence, provenance, SVG XML and the effective Core allowlist (including the known root-fill stripping), and absence of plugin CSS/JS/dependency manifests. These checks do not substitute for a real WordPress install.

## 10. M2 validation matrix

| Area | Static result / manual follow-up |
| --- | --- |
| A. Catalog integrity | PASS — 83 names, unique local names/handles, valid categories, manifest/SVG one-to-one. |
| B. Registration | PASS — one `SCI` collection and 83 Core icon registrations; nine patterns still register. |
| C. Picker/search | Pending manual M4 — real editor picker/search UI was not available in this run. |
| D. Core controls and accessibility | Static PASS — `core/icon` declares width, color, alignment, and `ariaLabel` support. Core `wp_get_icon()` marks unlabeled decorative icons hidden and labeled icons as `role="img"` with `aria-label`. UI sample remains for M4. |
| E. Feature compatibility | Static PASS — patterns unchanged and retain Core `core/icon`; editor save/reopen sample remains for M4. |
| F. Icon List compatibility | Static PASS — pattern unchanged; editor replacement/layout sample remains for M4. |
| G. Frontend | Static PASS — Core renderer outputs only selected inline SVG; no SCI frontend CSS/JS or external assets. Browser check remains for M4. |
| H. Security | PASS — all files parsed, allowed tags/attributes checked, no scripts, handlers, links, remote references, styles, or strokes. |
| I. Performance | PASS static estimate — 83 icons, 56,805 SVG bytes, 85,492 bytes manifest plus raw SVG; no browser picker timing available. |
| J. Licensing | PASS — pinned Bootstrap Icons v1.13.1, MIT notice retained, 83 asset provenance entries. |

The Product Owner’s prior PoC gate reported successful handle restoration after reactivation. M2 does not change that registration architecture. Real editor checks are not claimed as independently performed; record them in M4.

## 11. Release boundaries

This branch is development-only at `1.1.0-dev`. No 1.1.0 release, release ZIP, tag, or GitHub publication is authorized by this SDD. M2 completes the curated catalog; further work requires separate authorization and review.

## References

- [WordPress 7.1 icon registration and rendering API](https://make.wordpress.org/core/2026/07/24/registering-and-rendering-svg-icons-in-wordpress-7-1/)
- [WordPress Developer Blog: 7.1 Icon Registration API](https://developer.wordpress.org/news/2026/08/hands-on-with-the-wordpress-7-1-icon-registration-api/)
- Local WordPress Core 7.1.2 source inspected during the pre-SDD spike: `wp-includes/icons.php`, `wp-includes/class-wp-icons-registry.php`, `wp-includes/blocks/icon.php`, `wp-includes/blocks/icon/block.json`, and `wp-includes/blocks/icon/style.css`.
