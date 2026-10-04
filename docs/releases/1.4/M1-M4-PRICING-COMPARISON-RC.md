# SCI Design Blocks 1.4.0-rc — Pricing + Comparison RC Gate

**Candidate:** `sci-design-blocks-1.4.0-rc.zip`
**Plugin metadata:** 1.4.0-rc
**Stable 1.4.0:** M5 authorization received after this RC report was written
**Branch:** `spike/pricing-comparison-1.4`
**Baseline:** `v1.3.0` remains at its original commit; no tag was moved.

## M1–M4 implementation

- M1 added **SCI Pricing — Cards**.
- M2 added **SCI Pricing — Featured** and **SCI Pricing — Compact**, retaining the corrected PoC Core serialization and adding the targeted regression checks.
- M3 added **SCI Comparison — 2 Options** and **SCI Comparison — Feature Table**.
- M4 ran registration/parser/source checks and packaged the release candidate.
- Final count: **20 patterns** (15 baseline + 5 additions), under the existing SCI pattern category.

All five new patterns are unsynced Core-block insertion patterns. Pricing uses `core/group` Grid; its card count is not constrained in code. Three plans are the insertion default. The Product Owner reports 2–6 as the visually validated practical Cards range; more than six is allowed but not visually validated. The Table remains `core/table` with caption and semantic headers.

## Product Owner Manual Visual QA

The following was reported by the Product Owner after real Gutenberg editor/frontend testing of the corrected PoC:

- Pricing Cards 3 plans: PASS; 6 plans: PASS; Core Grid reflow: PASS; mobile stacking: PASS.
- Pricing Featured: PASS; unequal 4/7/5 lists: PASS; new insertion: PASS; prior invalid-block issue: NOT REPRODUCED.
- CTA alignment for unequal content: ACCEPTABLE.
- Pricing Compact: PASS.
- Comparison 2 Options desktop/mobile: PASS.
- Comparison Feature Table desktop/mobile: PASS; internal horizontal scroll: PASS; destructive global page overflow: NOT OBSERVED.
- Reported new custom SCI CSS 0, frontend JS 0, custom blocks 0, external runtime dependencies 0.

**Attribution:** Product Owner manual QA performed. Codex browser QA not performed. The Product Owner did not provide WP/PHP versions, viewport dimensions, or screenshots. Intermediate counts 2/4/5 for Cards and specific Compact counts were not individually enumerated in the report.

## Codex static QA

Environment: PHP 8.5.4 and WordPress Core source 7.1.2 at `/tmp/sci-sources-qa/wp712`. PHP 7.4 runtime was unavailable; source stays within PHP 7.4 syntax. No database-backed WordPress activation/browser run was performed.

- `php -l`: plugin bootstrap, new registration include, and targeted test PASS.
- `php tests/check-icon-library.php`: PASS; 20 expected patterns and existing icon/style inventory.
- `php tests/check-editorial-query-patterns.php /tmp/sci-sources-qa/wp712`: PASS; six existing editorial patterns.
- `php tests/check-pricing-comparison-patterns.php /tmp/sci-sources-qa/wp712`: PASS; exact five additions, Core-only parse/serialize/parse, Cards 2–6 duplicate/remove serialization, Featured 4/7/5 and setup copy, Compact three-card default, Comparison two-card grid, Table caption/header semantics.
- `php tests/check-tabs-roundtrip.php /tmp/sci-sources-qa/wp712`: PASS; four existing styles.
- Existing image/Tabs/Accordion CSS and 83 SVG inventory checks: PASS. No CSS/JS was added for Pricing/Comparison.
- Core registration smoke is static API stubbing; it is not claimed as a real WordPress activation test.

## Release-candidate archive

Archive policy follows the 1.3.0 installable ZIP: one `sci-design-blocks/` root with the main plugin, `includes/`, `patterns/`, `assets/`, `icons/`, README, LICENSE and third-party notices. Internal SDD, audit files, tests, PoC material, Git metadata, dependencies, and ZIPs are excluded.

- ZIP integrity: PASS (`zip -T`).
- Extracted runtime PHP lint: PASS.
- Static registration/parser check against extracted package: PASS.
- SHA-256: `542e2bfbfa2cb221009e0a1942ac5c2fce8621ff5f340c53b7e15e8842a8dcf9`.

## Compact Product Owner RC Gate

The Product Owner's reported visual evidence above covers the five pattern compositions. If testing the packaged RC specifically, install this archive on a disposable WP 7.1+ / PHP 7.4+ site, activate it, confirm 20 patterns and no invalid-block prompts, inspect Core editing, then deactivate/reactivate and confirm inserted content remains Core-editable. Record actual versions/viewports. This document does not mark any Codex browser test PASS.

- Package SHA-256: `542e2bfbfa2cb221009e0a1942ac5c2fce8621ff5f340c53b7e15e8842a8dcf9`
- Product Owner package-specific observations / date:
- At the time this RC report was first written, stable release approval had not yet been given; M5 authorization was subsequently provided.

## Final Product Owner gate update

The Product Owner subsequently authorized M5 and reported the final RC gate PASS, including Pricing Cards scalability through six plans, Core Grid reflow, Featured regression not reproduced, Compact, both Comparison patterns, Tabs regression, and Tabs mobile horizontal scroll. No visual regression was reported in tested editorial behavior. This remains Product Owner evidence; Codex browser QA was not performed.

## Outcome

M1–M4 are complete. The Product Owner subsequently authorized M5; final certification and remote verification are recorded in `M5-RELEASE-1.4.0.md`.
