# SCI Design Blocks 1.4.0 — Final Certification and Release

**Status:** Release certification in progress
**Stable version:** 1.4.0
**Requirements:** WordPress 7.1+; PHP 7.4+
**Baseline:** v1.3.0 (historical release immutable)

## Scope

SCI Design Blocks 1.4.0 adds exactly five Core-only patterns: SCI Pricing — Cards, SCI Pricing — Featured, SCI Pricing — Compact, SCI Comparison — 2 Options, and SCI Comparison — Feature Table. All other scope limits are documented in `SDD-1.4.md`; no monthly/annual toggle, pricing engine, checkout, calculator, custom pricing block, or highlighted table column was added.

## Product Owner manual visual QA

The Product Owner reports **SCI Design Blocks 1.4 RC: PASS**:

- Pricing Cards and scalability through six plans: PASS; Core Grid reflow and mobile stacking: PASS.
- Pricing Featured, unequal feature content, and insertion: PASS; prior invalid-block issue: NOT REPRODUCED.
- Pricing Compact: PASS; unequal-content CTA alignment: ACCEPTABLE.
- Comparison 2 Options desktop/mobile: PASS.
- Comparison Feature Table desktop/mobile and internal horizontal scroll: PASS; global destructive page overflow: NOT OBSERVED.
- Tabs regression and mobile horizontal scroll: PASS; no visual regression reported in tested editorial behavior.

This is Product Owner reported manual evidence. **Codex browser QA: NOT PERFORMED.** The report did not include browser screenshots, WP/PHP test versions, or viewport dimensions.

## Static and artifact certification

Environment: PHP 8.5.4; WordPress Core parser source 7.1.2. No PHP 7.4 runtime or database-backed WordPress activation was available/performed.

- PHP lint across runtime PHP source and extracted ZIP: PASS.
- Registration: 20 patterns exactly (15 baseline + 5 new), one existing SCI category, no duplicate/missing pattern slugs: PASS.
- Core parse/serialize/parse for all five new patterns: PASS.
- Pricing Cards duplicate/remove structural serialization for 2, 3, 4, 5, and 6 card Groups: PASS. This is parser evidence, not proof of visual reflow; visual reflow is Product Owner evidence.
- Featured 4/7/5 list regression, badge, setup-price copy, Core-only names, no Spacer: PASS.
- Comparison Table `core/table`, caption, column/row headers: PASS.
- Existing inventory and regression checks: 83 icons; two Accordion styles; four Tabs styles; one opt-in Hover Zoom style: PASS.
- New Pricing/Comparison SCI CSS: 0 bytes; SCI frontend JS: none; custom SCI blocks: 0; new external runtime dependencies: none.
- Security review: no new user-input processing, AJAX, REST routes, filesystem writes, database schema, remote fetch, or nonce/auth surface.
- Semantic review: Core Buttons, Lists, and Table semantics retained; no custom ARIA, hidden duplicate SEO content, JS-only content, or pricing schema markup added. This is not a formal WCAG certification.

## Release artifact

- Stable ZIP: `sci-design-blocks-1.4.0.zip`
- ZIP integrity (`zip -T`): PASS; archive contains one `sci-design-blocks/` root and 109 entries.
- Local SHA-256: `1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93`.
- Extracted artifact lint/registration/parser/inventory/exclusions: PASS. All eight runtime PHP files lint; extracted ZIP tests confirm 20 patterns, five Pricing/Comparison patterns, 83 icons, and excluded development/PoC files.
- Source/build tooling: no `package.json` or JS build pipeline exists; npm build is not applicable and no dependencies were installed.
- RC ZIP historical checksum `542e2bfbfa2cb221009e0a1942ac5c2fce8621ff5f340c53b7e15e8842a8dcf9` was verified before final metadata update and remains a separate artifact.

## Git and remote publication

- Release commit: pending.
- Main integration: pending.
- Annotated tag `v1.4.0`: pending.
- GitHub Release: pending.
- Exact uploaded asset: pending.
- Downloaded remote ZIP SHA-256 and `zip -T`: pending.
- Historical tags/releases v1.0.0–v1.3.0: verified present before publication; remote tag target hashes are recorded in the final closeout below.

The Product Owner manual visual gate is distinct from Codex evidence. Codex browser QA and database-backed WordPress activation QA were not performed.

## Final closeout

This document will record the final commit/tag, artifact hash, GitHub Release and asset URLs, local/remote checksum match, historical release integrity, and final working tree after publication.
