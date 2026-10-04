# SCI Design Blocks 1.4.0 — Software Design Document

**Status:** M1–M4 COMPLETE — M5 certification and stable release in progress
**Product:** SCI Design Blocks
**Baseline release:** 1.3.0 (`v1.3.0`, immutable)
**Release target:** 1.4.0
**WordPress:** 7.1 or later
**PHP:** 7.4 or later

This document is the implementation contract for the 1.4 Pricing + Comparison feature family. M5 stable release has been explicitly authorized and is in progress. Final release evidence is recorded in `M5-RELEASE-1.4.0.md`.

## 1. Purpose and scope

SCI Design Blocks 1.4 adds exactly five unsynced patterns to the existing SCI Design Blocks pattern library and its existing `sci-design-blocks` category:

1. **SCI Pricing — Cards** — general-purpose pricing; three plans by default.
2. **SCI Pricing — Featured** — three plans by default with Business emphasized.
3. **SCI Pricing — Compact** — denser, three-plan pricing composition.
4. **SCI Comparison — 2 Options** — two-option editorial/commercial comparison.
5. **SCI Comparison — Feature Table** — semantic feature comparison using Core Table.

Pattern slugs are `sci-design-blocks/pricing-cards`, `pricing-featured`, `pricing-compact`, `comparison-2-options`, and `comparison-feature-table`.

The manually observed Product Owner gate is recorded as product evidence in the Pre-SDD report and manual gate. Codex did not perform browser QA.

## 2. Architecture decisions

### Core-only pattern architecture

Use ordinary Core blocks and Core block attributes. Pricing cards use `core/group` with WordPress Core Grid layout, containing Core Groups, Heading/Paragraph content, List/List Item features, and Buttons/Button CTAs. Comparison uses the same Core card family where suitable; Feature Table uses `core/table`.

Patterns are registered with the existing SCI pattern category and repository registration approach. Pattern insertion copies ordinary Core block markup into the post/template; it does not depend on the pattern registration remaining active after insertion.

### Grid over Columns for scalable pricing

Use `core/group` with native `layout.type: grid`, `minimumColumnWidth`, and `autoFit` attributes supported by the WordPress 7.1 baseline. Three cards are the clean default. Authors add/remove plans through ordinary Gutenberg duplicate/delete actions; 2–6 is the Product Owner validated practical range for Pricing Cards, not a code-enforced limit. More than six is permitted but not visually validated; use a feature table or another information architecture when many options become difficult to scan.

Core Grid owns reflow. Rows need not be symmetrical, and the plugin defines no custom breakpoints. Core Columns remains appropriate for known fixed compositions, but its desktop columns do not provide the adaptive wrapping desired for variable pricing counts. The source evidence is WordPress Core 7.1.2 `wp-includes/block-supports/layout.php`, `wp-includes/blocks/columns/style.css`, and Twenty Twenty-Five pricing/Grid patterns; the Product Owner supplied real-editor evidence for Cards at three and six plans, automatic reflow, and mobile stacking.

### Pricing is presentation content

Prices, descriptions, billing text, optional setup pricing, features, and CTAs remain editable Core content. They are illustrative pattern copy. Pricing is not a data model or executable service.

## 3. Pattern contracts

### Pricing — Cards

- Three plans on insertion, with plan name, description, price copy, feature List, and Core Button.
- Core Group Grid uses an 18rem minimum track and native auto-fit behavior.
- Duplicate/remove Core card Groups to change plan count. No add-plan UI or proprietary control.
- Example prices are editable illustrative text, not product claims.

### Pricing — Featured

- Same structural family as Cards; three plans on insertion.
- Business is emphasized with ordinary editable badge copy and Core theme-preset background attributes; no proprietary badge block, brand color, transform, CSS, spacer hack, or fixed-height requirement.
- Default feature counts remain unequal: Starter 4, Business 7, Enterprise 5. Setup-price copy is ordinary Paragraph content.
- Optimized for the three-plan Starter / featured Business / Enterprise hierarchy. Editing remains possible at other counts, but the visual hierarchy is not guaranteed to remain centered/optimal.
- Product Owner rated CTA alignment with unequal content ACCEPTABLE. No pixel-equality remediation is allowed in this scope.

### Pricing — Compact

- Three short-content plans by default; denser spacing and narrower Core Grid tracks distinguish it from Cards.
- Price/features are prominent; no description-heavy copy.
- Plan count remains editable through Core duplication/removal. No fixed three-column declaration.

### Comparison — 2 Options

- Two editable Core card Groups in a responsive Core Grid.
- No price requirement and no universal commercial claims. Any example service copy is illustrative/editable.
- Product Owner reported desktop and mobile PASS.

### Comparison — Feature Table

- Core Table with caption, header row, column headers, and row-header cells where appropriate.
- Preserve normal table semantics and Core editing. Do not recreate the table with Groups or a custom block.
- Internal horizontal scrolling on mobile is accepted; no global destructive page overflow was observed by the Product Owner. Do not transform the table into cards or add a custom highlighted column.

## 4. Theme, responsiveness, and accessibility

- Inherit theme typography; use Core editable color/background, spacing, border, and button controls. Do not hardcode SCI/Nettix colors or font families.
- The featured card may use an available Core/theme preset. Theme authors and users remain able to change it.
- Core Grid and Core Table supply responsive behavior; no SCI layout CSS or media queries are introduced.
- Keep plan names as headings with sensible document hierarchy, feature descriptions as readable text/List items, CTAs as Core Buttons, and comparison relationships in a semantic Core Table with caption/header cells.
- No meaning may depend on color alone. No custom ARIA or JavaScript interaction is required.

## 5. Non-goals and security boundaries

No custom block, frontend JavaScript, new CSS, external runtime dependency, custom post type, price entity, database table, metadata model, pricing API, REST/AJAX endpoint, settings page, checkout, cart, payment, tax/discount engine, currency conversion, calculator, dynamic source, or synchronized monthly/annual toggle.

Pattern registration loads only fixed local markup files and uses WordPress registration APIs. It does not execute pattern content as PHP or accept user-supplied code. Inserted markup consists of Core blocks. No new remote URLs, company identifiers, taxonomy IDs, or runtime asset fetches are part of these patterns.

## 6. Compatibility

- WordPress 7.1+; do not raise the minimum.
- PHP 7.4+; do not raise the minimum.
- Core Grid attributes and Core Table are verified against WordPress 7.1.2 source.
- The available local PHP runtime was 8.5.4; PHP 7.4 runtime execution was not available in this QA run. Source syntax must remain PHP 7.4 compatible.

## 7. No-lock-in behavior

Patterns are unsynced insertion templates. Once inserted, all plan/table data remains ordinary Core Group/Grid, Heading, Paragraph, List, Buttons, Button, and Table blocks. Deactivating SCI Design Blocks does not remove or transform inserted content. Pattern registration itself is needed only to offer future insertions.

## 8. QA and acceptance

### Static checks

- PHP lint on plugin files.
- Registration smoke: existing category, five exact new names, 20 total patterns (15 baseline + 5 additions).
- WordPress Core 7.1.2 parse → serialize → parse stability for all five patterns.
- Assert Core-only block names, 3-card defaults, Featured 4/7/5 lists and badge/setup content, Compact distinction, 2-card comparison, Table caption/headers.
- Generate static serialization fixtures for Cards with 2, 3, 4, 5, and 6 card Groups by ordinary block duplication/removal.
- Verify no CSS/JS/dependencies or product-specific external references were added.

### Product Owner visual evidence

The Product Owner reported: Cards 3/6 PASS; automatic Grid reflow PASS; mobile stacking PASS; Featured PASS; unequal 4/7/5 content PASS; corrected Featured insertion PASS with prior invalid-block issue NOT REPRODUCED; CTA alignment ACCEPTABLE; Compact PASS; Comparison 2 Options desktop/mobile PASS; Feature Table desktop/mobile PASS; internal table horizontal scrolling PASS; global destructive overflow NOT OBSERVED. Product Owner also reported 0 custom CSS, 0 frontend JS, 0 custom SCI blocks, and 0 external runtime dependencies.

This is manually reported evidence, not Codex browser automation. Browser QA by Codex was not performed. The individual intermediate counts 2/4/5 for Cards and 4/5 Compact were not separately reported; the 2–6 Cards range is recorded as the Product Owner's validated practical range, not as individually attributed measurements for every count.

### M4 release-candidate gate

- Full PHP lint and project static checks pass.
- Exact pattern count is 20.
- Targeted pattern registration and Core 7.1.2 round-trip checks pass.
- Package contains runtime plugin files only and passes ZIP integrity and extracted-source checks.
- Produce and record the compact Product Owner manual gate for the release candidate.
- Stable release requires separate M5 authorization. The Product Owner has now authorized M5; this certification is executing under that authorization. Historical releases remain immutable.

## 9. Milestones

| Milestone | Scope | Status |
|---|---|---|
| M1 | Register Pricing Cards; verify 2–6 card serialization and Core round-trip. | Complete in this candidate. |
| M2 | Register Featured and Compact; add invalid-markup regression and Core round-trip checks. | Complete in this candidate. |
| M3 | Register Comparison 2 Options and Feature Table; verify semantics and Core round-trip. | Complete in this candidate. |
| M4 | Hardening, complete static checks, archive validation, compact manual gate. | Complete for RC review; manual PO gate already reported complete. |
| M5 | Product Owner final gate, final certification, and stable 1.4.0 release. | In progress; final result recorded in `M5-RELEASE-1.4.0.md`. |

## 10. Decision log

| ID | Decision | State |
|---|---|---|
| D1401 | Exactly five Pricing/Comparison patterns are in 1.4 scope. | APPROVED |
| D1402 | Pricing is editable presentation content built from Core blocks. | APPROVED |
| D1403 | Core Group Grid is preferred over Columns for variable pricing counts. | APPROVED |
| D1404 | Three is the default; 2–6 is the PO-reported practical Cards range, not a software limit. | APPROVED |
| D1405 | Featured emphasis and badge use ordinary Core content/theme attributes. | APPROVED |
| D1406 | Compact uses a denser three-card Core Grid preset. | APPROVED |
| D1407 | Comparison 2 Options remains two Core cards. | APPROVED |
| D1408 | Comparison Feature Table uses Core Table semantics. | APPROVED |
| D1409 | No new SCI blocks, frontend JS, CSS, or runtime dependencies. | APPROVED |
| D1410 | Monthly/annual synchronized pricing is deferred. | APPROVED |
| D1411 | Stable release 1.4.0 and M5 require separate authorization. | APPROVED |
