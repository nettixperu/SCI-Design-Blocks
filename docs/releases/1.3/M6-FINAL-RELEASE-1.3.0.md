# SCI Design Blocks 1.3.0 — Final Release Certification

**Scope:** M6 hardening, final certification and release. Functional scope is frozen at the approved 1.3.0 patterns and styles.

## Approved release content

- WordPress minimum: 7.1; PHP minimum: 7.4.
- 15 insertion patterns: 9 historical patterns and 6 editorial Core Query patterns (Featured Hero, Editorial Lead, Compact List, Editorial Grid, Editorial Stack and Editorial Sections).
- One SCI icon collection with 83 icons; 2 Accordion styles; 4 Tabs styles; 1 opt-in Core Post Featured Image Hover Zoom style.
- Zero custom SCI blocks, zero SCI frontend JavaScript, zero external runtime dependencies, zero editorial layout CSS.
- Historical 1.0.0, 1.1.0 and 1.2.0 tags/assets are immutable. The published 1.2.0 ZIP SHA-256 remains `8e2b7ababbcbb793b7eed82f07f387fa10ea113381a3cd89bb627db0dd19a803`.
- RC04 remains an unchanged development artifact with SHA-256 `af6a75cef12854c214351ce488139c619e9f2db253b44b0a5f0952542ec30342`.

## M5 staging evidence

The Product Owner reports manually applying the proposed M5 CSS copy to staging: sections 4–5 were removed, sections 1–3 retained, the result passed visual review, and no breakage was observed. The reduction was 211 physical lines (365 to 154, approximately 58%); editorial layout CSS moved into the plugin: 0 bytes. Codex did not access or modify staging. See [`../../migration/M5-CSS-MIGRATION-MATRIX.md`](../../migration/M5-CSS-MIGRATION-MATRIX.md).

## Final validation

- PHP CLI: 8.5.4. PHP 7.4 runtime execution was not available; plugin source was linted and inspected for syntax/API features newer than its declared minimum.
- WordPress Core parser: 7.1.2, from `/tmp/sci-sources-qa/wp712`.
- Static registration smoke: PASS — 15 patterns, one SCI icon collection, 83 icons, 2 Accordion styles, 4 Tabs styles, one Hover Zoom style; Visual Grid absent and no custom block registration.
- Editorial Core parser: PASS for all six registered editorial patterns, with no unknown/non-Core blocks.
- Compact List contract: PASS — Query, Post Template, Core Row, Featured Image (160px, 16:9, contain), title/category group and Separator; no date/excerpt.
- Editorial Sections contract: PASS — three section columns and six Queries; primary/secondary offset pairs 0/1, three primary images and six title blocks; no secondary images or extra metadata; Query pairs remain independently configurable.
- Editorial Lead: PASS — one primary image Query and three secondary entries, with coordinated defaults and no synchronization code.
- Stable regression: PASS — nine historical patterns remain registered; icon/style inventory passes; Core Tabs parse/serialize round-trip passes for all four styles.
- PHP lint: PASS for all plugin runtime PHP, icon manifest and PHP tests under PHP 8.5.4.
- CSS checks: PASS — Accordion 1,076 bytes / 6 rules; Tabs 2,202 bytes / 12 rules / 18 selector arms; Hover Zoom 580 bytes / 4 rules / 5 selector arms / 2 media queries. All CSS has zero `!important`; Hover Zoom is scoped, opt-in, layout-neutral and respects reduced motion.
- Source scan: PASS — no custom Query engine, remote fetch, REST/AJAX endpoint, unsafe execution, frontend JS, dependency manifest or external runtime resource. Editorial patterns use Core blocks and theme presets; Query output remains Core server-rendered.
- Product Owner visual gate: PASS, reported by Product Owner using real SCI content on staging. Codex did not perform browser QA.
- Package: `sci-design-blocks-1.3.0.zip`; `zip -T` and extracted package smoke passed. It contains the runtime plugin tree only (102 archive entries including directories; 96 files), excluding tests, POCs, audit inputs, internal SDD/phase files and nested ZIPs.

## Distribution boundary

The release ZIP includes the bootstrap, five runtime includes, three CSS assets, icon manifest and 83 local SVGs, README, GPL license and third-party notices. The M5 CSS evidence files and this certification report are development/repository documentation and are not runtime assets. No npm build/audit was needed: there is no package manifest, JavaScript build source or frontend JavaScript.

Release ZIP SHA-256: `5845bc26b7e95ef9c51ea8f411f44a1d1b14d0676e9cb080a8be872f71dcee6b`.
