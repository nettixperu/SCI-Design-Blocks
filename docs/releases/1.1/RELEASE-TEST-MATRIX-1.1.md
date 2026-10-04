# SCI Design Blocks 1.1.0 — Release Test Matrix

**Release:** 1.1.0
**WordPress minimum:** 7.1
**PHP minimum:** 7.4

The Product Owner's final release instruction reports the RC manual gate complete and approves the listed behaviors. Manual results below are attributed to that report; Codex did not independently run a browser-based WordPress session.

| Area | Status | Evidence |
| --- | --- | --- |
| A. Installation / requirements | PASS | Plugin metadata requires WordPress 7.1 and PHP 7.4. The Product Owner reports the RC manual gate complete. |
| B. Existing patterns | PASS | Static registration confirms nine existing Core-block patterns. The Product Owner confirms SCI icons integrate with existing content/patterns and the final manual gate passed. |
| C. Icon catalog integrity | PASS | Manifest/source check: 83 unique entries, labels, provenance, nine conceptual categories, and one-to-one SVG correspondence; no orphan asset. |
| D. Icon picker / editor | PASS | The Product Owner reports the SCI Icon Library visible and functional, and confirms SCI icons render and integrate with existing content/patterns. |
| E. Icon frontend | PASS | Static architecture confirms Core renders only the selected icon; no SCI frontend JavaScript, icon font, CDN, or external request code. Product Owner reports SCI icons render correctly. |
| F. Content-first no-lock-in | PASS | Product Owner validates that the stored icon handle survives deactivation and reactivation restores the icon. Accepted trade-off: the glyph requires the plugin active; surrounding Core structure/content remain. |
| G. Accordion styles | PASS | Static registration confirms exactly two opt-in styles (Minimal, Bordered), no default override, and scoped CSS. Product Owner reports the styles function correctly and Accordion interaction remains Core. |
| H. Responsive | PASS | Product Owner reports responsive behavior acceptable for the RC manual gate. |
| I. Accessibility | PASS | Core Accordion interaction is retained, as confirmed in the Product Owner's report. This is not a formal WCAG certification. |
| J. SEO / semantics | PASS | Static source retains Core blocks and Core Accordion markup; no schema injection, shortcode, custom content container, or AJAX-rendered content was found. |
| K. Security | PASS | All 83 SVGs parse and satisfy the checked WordPress 7.1 sanitizer allowlist; no scripts, event handlers, remote references, embedded content, or unsafe CSS. Static source scan found no SCI REST/AJAX endpoint, user SVG upload, or remote fetch path. |
| L. Performance | PASS | 83 SVG files total 56,805 raw bytes; manifest is 28,687 bytes; combined approximate catalog/manifest size is 85,492 bytes. Accordion CSS is 1,076 bytes, six scoped rules, and zero `!important`. No full catalog frontend asset or SCI frontend JS exists. Browser timing was not measured. |
| M. Licensing | PASS | Plugin declares GPL-2.0-or-later and includes GPL v2 text. Third-party notice identifies Bootstrap Icons v1.13.1, MIT, and the 83 included unmodified assets. |
| N. Packaging | PASS | Final ZIP validated with `zip -T`, archive inventory/exclusions, extracted PHP lint, registration smoke, and manifest/source checks. |

## Static validation performed

- PHP lint on the plugin bootstrap, both include files, and icon manifest: PASS.
- PHP registration smoke: PASS — nine patterns, one collection, 83 icons, two opt-in Accordion styles, provenance, and MIT notice.
- SVG checks: PASS — 83 SVGs, manifest/source correspondence, checked Core sanitizer compatibility, no unexpected frontend CSS/JS or dependency manifests.
- Accordion CSS check: PASS — 1,076 bytes, six scoped rules, zero `!important`.
- `git diff --check`: PASS.

## Manual gate result

The Product Owner reports PASS for:

1. Icon Library visibility, rendering, integration, and Core icon controls.
2. Icon integration with existing patterns/content.
3. Deactivation/reactivation and handle persistence; accepted content-first trade-off.
4. Minimal, Bordered, and unchanged Core Accordion behavior.
5. Responsive behavior.

No additional manual QA was repeated because the final delta changes release metadata and documentation only, not features or styling.
