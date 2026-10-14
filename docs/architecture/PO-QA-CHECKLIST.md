# SCI Design Blocks 1.5.0-rc.2 — Product Owner QA

**Purpose:** independent certification on a disposable/staging WordPress site. This checklist is not a release approval. Do not run against production.

## Candidate and prerequisites

- Candidate: `SCI Design Blocks 1.5.0-rc.2` (M6 local QA candidate; includes M6-FIX-01 Hero Split Image serialization repair)
- ZIP: `sci-design-blocks-1.5.0-rc.2-m6.zip`
- SHA-256: `f8d5d7c65cb17b1645d407324d73f8c44c8cb1b4b1dbeb8977af23ef007ef477`
- Preserved artifacts: `${SCI_QA_ROOT}/release-candidates/`
- Requirements: WordPress 7.1+; PHP 7.4+. Record exact installed versions. PHP 7.4 has not been certified by the local M6 run.
- Use a disposable staging site, supported theme (prefer Twenty Twenty-Five; optionally repeat Twenty Twenty-One), and current full files/database backup. Keep the verified stable 1.4.0 ZIP available for rollback.

## PO-reported manual evidence (RC.2)

The Product Owner reports successful insertion, editing, saving, and reopening without invalidation for Hero Cover and Hero Split; the original invalid-content error did not recur. Hero Cover was reported visually correct on mobile; Hero Split on mobile and tablet. Record these as **PASS — PO MANUAL QA**, not automated results. Exact browser/CMS/PHP versions and viewport widths were not supplied. This evidence does not certify unreported viewports or replace the remaining installation, upgrade, accessibility, or full responsive checks. See [M6-CERT-FINAL gate assessment](M6-CERT-FINAL-REPORT.md).

Before install, recompute the ZIP SHA-256 and compare it with the value above. Do not install on a mismatch; report the actual checksum and stop that test. RC.1 and its manifests are retained as historical evidence and must not be overwritten.

## Install and inventory

- [ ] Install through the normal WordPress plugin upload/update path on staging.
- [ ] Activate and record the visible plugin version.
- [ ] Confirm 22 SCI patterns, 83 icons, seven Block Styles, and three SCI CSS assets.
- [ ] Confirm no custom SCI block is required and no recovery/invalid-block notice appears.
- [ ] Record WordPress, PHP, theme, browser and platform versions.

Expected inventory: 22 patterns; 83 icons; Accordion `sci-minimal` and `sci-bordered`; Tabs `sci-underline`, `sci-pills`, `sci-connected`, `sci-filled`; Featured Image `sci-hover-zoom`.

## Gutenberg editor

- [ ] Insert Hero Cover into a draft; edit heading text and heading level, choose a cover image, adjust overlay, and edit both CTA labels/targets.
- [ ] Insert Hero Split into a draft; confirm the original Image invalid-block notice is absent.
- [ ] Select the empty Hero Split image placeholder; choose/change the image, set useful alt text, and verify Core Image controls remain available.
- [ ] Edit Hero Split heading, description, and CTA labels/targets.
- [ ] Save each draft, leave the editor, reopen it, and verify content/settings persist.
- [ ] Confirm no invalid-block prompt, recovery warning, save error or relevant console error after reopening.
- [ ] Confirm the patterns remain composed of ordinary Core blocks.

## Block Styles and interactions

- [ ] Accordion: default Core, Minimal, and Bordered.
- [ ] Tabs: default Core, Underline, Pills, Connected, and Filled; test keyboard operation and long labels.
- [ ] Featured Image: default Core and Hover Zoom.
- [ ] Verify each style is attached to the intended Core block and a page with no matching block does not receive unrelated SCI CSS.
- [ ] Verify no duplicate stylesheet link for repeated blocks and no unexpected global styling.

## Responsive and visual checks

Repeat for Hero Cover, Hero Split, Accordion, Tabs, and Hover Zoom at **1440, 768, 390, and 320 px**. Record screenshots and any defect with viewport.

- [ ] No horizontal overflow or clipped text/controls.
- [ ] Text wraps legibly; headings do not collide with buttons or images.
- [ ] Hero Split stacks in the intended order; image crop remains meaningful.
- [ ] Cover crop/overlay preserves readable contrast.
- [ ] CTAs fit, remain distinguishable, and have usable tap targets.
- [ ] Spacing and stacking remain coherent at narrow sizes.
- [ ] Accordion and Tabs remain understandable and operable.

## Accessibility basics

- [ ] Heading levels are meaningful and ordered; one page title remains the primary H1.
- [ ] Images have appropriate alt text (or empty alt for decorative images).
- [ ] Keyboard-only navigation reaches controls in a logical order with visible focus.
- [ ] Tabs and Accordions can be operated by keyboard and expose clear selected/expanded state.
- [ ] Links and buttons have descriptive names; repeated CTAs are distinguishable in context.
- [ ] Check text/control contrast and touch-target usability; record method and any unresolved issue.
- [ ] Check reading order at mobile widths and with images unavailable.

This checklist is a basic review, not a complete WCAG conformance audit. Do not state WCAG conformance unless a separately scoped audit supports it.

## Upgrade and content persistence

- [ ] On a separate staging copy running the exact published 1.4.0 artifact, take a fresh backup and create a representative content fixture.
- [ ] Upgrade only that staging copy to the candidate. Confirm activation, content persistence, and no invalid blocks.
- [ ] Deactivate SCI on a disposable copy and verify inserted Core headings, text, images and links remain usable. Optional SCI styling/icons may disappear.
- [ ] Restore the backup or reinstall the verified 1.4.0 ZIP and verify representative content and active version.
- [ ] Never overwrite, alter, or upload over the published 1.4.0 artifact; do not test on production.

## Results and defect report

Return one overall classification:

- **PASS** — all required checks executed and passed.
- **PASS WITH OBSERVATIONS** — no blocker, but observations are documented and accepted.
- **FAIL** — a reproducible defect or release blocker exists.
- **NOT EXECUTED** — a required test could not be run; state why.

For each defect provide: ID, classification/severity, candidate SHA-256, exact WordPress/PHP/theme/browser versions, steps, expected vs. actual result, viewport/editor context, screenshot or console evidence, reproducibility, and whether 1.4.0 is affected. Do not send credentials or production data.

Return the completed checklist and explicit Product Owner decision separately from technical QA. **Publication remains prohibited unless the Product Owner explicitly authorizes it.**
