# M8 — Implementation and QA report

Status: **implementation complete — PASS WITH LIMITATIONS**. This is not a release approval. Baseline: `6a65d35051e6d2623d0970819e18daeb3c61103d`; branch: `feature/sci-pattern-library-expansion`.

## Result and inventory

M7 evaluated all 17 M0 compositions in [M7-PATTERN-EXPANSION-DECISIONS.md](M7-PATTERN-EXPANSION-DECISIONS.md). Five distinct, static patterns were selected; SCI-004 and SCI-007 share one service-card pattern. Header/footer compositions remain Site Editor responsibilities or deferred where they need an external form provider. No duplicate composition was added for content/alignment variants.

| Inventory | Baseline | Candidate |
| --- | ---: | ---: |
| SCI patterns | 22 | 27 |
| SVG icons | 83 | 83 |
| Block Styles | 7 | 7 |
| CSS files | 3 | 3 |
| Custom blocks / frontend JavaScript / runtime dependencies added | 0 | 0 |

The five additions are Section — Intro, Benefits — Grid, Services — Image Cards, Pricing — Split Offer, and CTA — Horizontal. Usage guidance and limitations are in [1.5.0-preview.md](../patterns/1.5.0-preview.md).

## Implementation and compatibility

Patterns are unsynced compositions of registered WordPress Core blocks, registered in the existing `sci-design-blocks` category. Service Cards uses fully serialized empty `core/image` markup compatible with the Gutenberg 7.1 serialization evidence already recorded for M6. Pricing Split and CTA Horizontal use Core Columns with mobile stacking. The source metadata remains `1.5.0-rc.3`, PHP 8.3+, WordPress 7.1+; no source version was rewritten for packaging.

PHP 8.3.35 lint passed for all 13 PHP files. Four PHP checks passed: icon/pattern inventory, pattern parser/serializer and markup checks, editorial-query regression, and tabs round-trip. The parser/serializer check passed against WordPress 7.1.3 and all 27 patterns; it checks registered Core block names, round trips and empty Image serialization. Four Python checks passed for the CSS/icon contracts (accordion, editorial-image, tabs and SVG/icon validation). `git diff --check` passed. The inventory test reports 27 patterns, 83 icons, seven styles and three CSS files.

The locally available WordPress 7.1.3 QA fixture registered all 27 SCI patterns and its database check passed. Five local QA pages were created from registered patterns (IDs 29–33: Section Intro, Benefits Grid, Service Cards, Pricing Split and CTA Horizontal); each returned HTTP 200. These pages contain generic sample copy and are not production content.

## Visual, portability and persistence checks

Service Cards was visually spot-checked using the fixture's Twenty Twenty-Five theme at 1440, 768, 390 and 320 CSS pixels. Its cards wrapped and stacked without observed clipping or horizontal overflow. This is a focused theme/browser spot check, not responsive certification for every pattern or theme. Pricing Split and CTA Horizontal rendered in the fixture but have not received complete multi-viewport visual review. Twenty Twenty-One compatibility is not verified.

The patterns comprise ordinary Core content and the parser confirmed their registered block names are Core-only. A fresh database query to re-check plugin-switch persistence could not be run at closeout because the local QA fixture returned “Error establishing a database connection”; therefore persistence across plugin deactivation is **not independently verified in this closeout**. The earlier runtime check used an existing populated environment with RC.5 installed, not a clean WordPress installation or a formal upgrade test from the v1.4.0 package. Existing RC.5 package files were not modified; RC.6 was staged separately.

## Accessibility and editor certification

Markup uses semantic headings, paragraphs, lists, buttons and image alt controls, with reading order preserved in source. Automated structural tests passed, but no WCAG audit, keyboard/screen-reader test or contrast certification was performed. **Gutenberg interactive validation is NOT VERIFIED:** authenticated editor insertion, save/reopen, selecting/editing Core Image media and browser `validateBlock` checks remain for M9. HTTP 200 and PHP parser/serializer round trips do not substitute for those editor checks.

## RC.6 artifact and reproducibility

The candidate is [sci-design-blocks-1.5.0-rc.6.zip](../../release-candidates/sci-design-blocks-1.5.0-rc.6.zip). SHA-256: `c30ec6e7405053341eb26eefce408db708bf8b46375680e29f613bad19513f63`. The source and package manifests each contain 111 files; both checksum validations passed, and ZIP integrity validation passed. The package applies `1.5.0-rc.6` to the plugin header in a staging copy only. Source remains `1.5.0-rc.3`; PHP and WordPress requirements are preserved in both.

Rebuild locally with `sh tools/build-rc6.sh release-candidates`. The builder packages runtime assets and emits source/package SHA-256 manifests. Re-run all checks after source changes; any such change requires rebuilding and re-verifying the candidate.

## Remaining gates and state

M8-FIX-01 subsequently repaired the unclosed first `core/column` in Pricing Split and CTA Horizontal. See [M8-FIX-01 report](M8-FIX-01-REPORT.md); RC.7 supersedes RC.6 as the repaired local candidate, while RC.6 remains preserved.

- M8 implementation: complete, PASS WITH LIMITATIONS.
- M9: not authorized; interactive editor validation, full responsive coverage, accessibility certification, clean-install/upgrade and remaining compatibility gates are not claimed as passed.
- 1.5.0: NOT RELEASED. RC.6 is a local candidate only.
- No commit, push, merge, tag or publication was performed. The working tree contains the implementation, documentation, tests and local candidate; it is not committed.
- v1.4.0, RC.2, RC.3 and RC.5 were not modified.
