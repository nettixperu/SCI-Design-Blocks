# SCI Design Blocks — Software Design Document 1.1

**Status:** M1 Icon Library Foundation implemented on the development branch; M2 catalog expansion is not started.
**Development version:** `1.1.0-dev`
**Minimum WordPress:** 7.1
**PHP minimum:** 7.4 (unchanged plugin baseline)
**Text domain:** `sci-design-blocks`
**License:** GPL-2.0-or-later

This document records the approved architectural contract for the SCI Icon Library foundation. The 1.0.0 tag, GitHub release, and release ZIP remain frozen and represent the WordPress 7.0+ line.

## 1. Scope

M1 establishes a maintainable registry and source manifest for exactly seven Bootstrap Icons fixtures. It does not implement the proposed 89-icon catalog, new patterns, Accordion Styles, Tabs, branded icons, SVG upload, custom blocks, or a release package.

The nine existing design patterns remain Core block compositions and remain registered by the existing pattern code.

## 2. Compatibility and WordPress Core API

The 1.1 development line requires WordPress 7.1 because the public icon registration API is available in that version. There is no WordPress 7.0 compatibility layer, polyfill, or proprietary fallback.

The plugin uses:

- `wp_register_icon_collection()` to register the single `sci-design-blocks` collection, displayed as **SCI**;
- `wp_register_icon()` to register each named SVG using a trusted local `file_path`;
- WordPress `core/icon` as the editor block, picker, controls, and server renderer. Core’s renderer calls `wp_get_icon()` for the stored handle; SCI does not create a second rendering path.

Handles use the Core form `collection/icon-name`, e.g. `sci-design-blocks/hdd-rack`. No `sci/icon`, custom Icon block, picker, modal, search UI, endpoint, or editor package is added.

## 3. Collection and fixture manifest

`icons/manifest.php` is the static source of truth. Each record has an explicit local name, translatable label, upstream library/version/name, conceptual category, and modification flag. The associated source is exactly `icons/svg/<local_name>.svg`; each manifest entry must have one SVG and every SVG must correspond to one manifest entry.

The collection contains exactly:

- `hdd-rack`
- `shield-lock`
- `envelope-check`
- `briefcase`
- `person-lines-fill`
- `camera-reels`
- `cloud-arrow-up`

All are unmodified Bootstrap Icons v1.13.1 sources. Core searches by icon name and label; it does not accept a separate keyword, alias, or category field. Conceptual categories are inventory metadata and do not create extra Core collections or UI.

## 4. Content-first no-lock-in

Approved trade-off: **CONTENT-FIRST NO-LOCK-IN**.

- Patterns and surrounding content remain WordPress Core blocks.
- A selected icon is stored as the namespaced handle in `core/icon`; the SVG is not copied into post content.
- With SCI inactive, the `core/icon` block and handle remain, while the SCI glyph may not render.
- With SCI reactivated, the same handle should resolve and the glyph should return without reselection.
- The Product Owner reports that manual PoC testing confirmed restoration after reactivation. No custom fallback, content mutation, custom block, picker, font, frontend JavaScript, or serialization workaround is permitted.

## 5. Licensing and asset provenance

The plugin remains GPL-2.0-or-later. The seven redistributed Bootstrap Icons files retain their source/version/name and MIT provenance in the manifest. `THIRD-PARTY-NOTICES.md` includes the complete MIT text. The plugin’s principal `LICENSE` is unchanged; no library license is mixed into that file.

Catalog growth is a manually reviewed, pinned snapshot. There are no runtime downloads, CDN assets, npm/composer runtime packages, or automatic upstream updates.

## 6. Security and Core SVG sanitization

Only trusted static repository SVGs are registered. No user-provided SVG, upload handling, MIME changes, or custom sanitizer is present. The source must remain compatible with WordPress 7.1’s SVG allowlist (`svg`, `path`, `polygon` and supported attributes); Core performs sanitization on registration/read. Validation checks local sources against the inspected Core allowlist, while WordPress integration testing remains the final runtime check.

The manifest path is code-owned. Icon names are validated before constructing local file paths. Registration uses `file_path`; raw SVG content is not concatenated into frontend markup by SCI.

## 7. Performance and assets

- The editor discovers the registered collection through Core’s same-site, authenticated REST API and Core picker.
- The picker loads the selected collection’s icons; M1 caps this collection at seven fixtures. Search is Core behavior over icon name and label.
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

The PHP static smoke test stubs Core registration and checks one collection, seven unique icon registrations and exactly the existing nine pattern names. It does not substitute for a real WordPress install. The Python check validates manifest/source correspondence, SVG XML and observed Core allowlist constraints, and absence of plugin CSS/JS/dependency manifests.

## 10. Release boundaries

This branch is development-only at `1.1.0-dev`. No 1.1.0 release, release ZIP, tag, or GitHub publication is authorized by this SDD. M1 stops with the seven fixtures; M2 requires separate authorization and review of the curated catalog.

## References

- [WordPress 7.1 icon registration and rendering API](https://make.wordpress.org/core/2026/07/24/registering-and-rendering-svg-icons-in-wordpress-7-1/)
- [WordPress Developer Blog: 7.1 Icon Registration API](https://developer.wordpress.org/news/2026/08/hands-on-with-the-wordpress-7-1-icon-registration-api/)
- Local WordPress Core 7.1.2 source inspected during the pre-SDD spike: `wp-includes/icons.php`, `wp-includes/class-wp-icons-registry.php`, `wp-includes/blocks/icon.php`, `wp-includes/blocks/icon/block.json`, and `wp-includes/blocks/icon/style.css`.
