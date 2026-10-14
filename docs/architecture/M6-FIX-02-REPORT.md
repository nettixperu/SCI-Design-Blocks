# M6-FIX-02 — Pricing card height and CTA alignment

**Date:** 2026-10-13<br>
**Branch / baseline:** `feature/sci-hero-patterns-css-compat` / `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`<br>
**Decision:** **PARTIAL — Core fix implemented; post-fix browser/editor certification NOT VERIFIED.**<br>
**Release:** Local RC.4 candidate only; no publication authorized.

## Executive summary

Fixed the invalid Core layout attribute used by the card Groups in Pricing Cards, Pricing Featured, and Pricing Compact. In WordPress 7.1, a vertical Flex layout maps `verticalAlignment: "space-between"` to CSS `justify-content: space-between`; `justifyContent: "space-between"` is instead interpreted on the cross axis and is not a supported vertical alignment value. Core therefore did not distribute the free vertical space, leaving the CTA after each card's own content. The previous Pricing Cards capture shows visibly different CTA positions, although it is not a geometric measurement.

The minimal correction changes that one layout attribute on each of the nine card Groups. It adds no blocks, wrappers, CSS, JavaScript, dependencies, or migrations. A regression assertion now covers all three patterns. Static registration, parsing/serialization, lint, and asset regression pass. The WordPress QA database is currently unavailable, so no post-fix visual measurements, responsive capture, editor save/reopen, activation test, or install/activation of RC.4 was possible. Do not treat PARTIAL as visual PASS or release approval.

## Baseline and scope

Before this task, the worktree already contained user changes: 12 tracked files modified (including README/changelog, plugin metadata, CSS registration, and three tests) and numerous untracked M5/M6 reports plus Hero files. The full initial status was recorded in the task transcript; these changes were preserved. The three Pricing pattern files and the Pricing regression test were also already modified before this task in the worktree; the task added only the attribute correction and its test assertion to those files.

No branch switch, reset, clean, commit, push, merge, tag, release, stable `1.4.0` edit, Hero edit, or MariaDB operation was performed. The historical `SDD-1.4.md` was left intact; this M6 report records the correction without rewriting the frozen 1.4 design record.

The original RC.3 at `${SCI_QA_ROOT}/release-candidates/sci-design-blocks-1.5.0-rc.3-m6.zip` remains SHA-256 `9b7ffdbeca8f11ca0771bbe0b61b6d6ac6e8471cf011ec611a371a75c743cdd7`. Its RC/source manifests were not changed.

## Reproduction and root cause

The existing 1440-pixel Pricing Cards screenshot at `${SCI_QA_SITE_DIR}/screenshots/pricing-1440.png` shows three cards with content lengths of four, five, and five features. Their buttons visibly start at different vertical positions. That image is pre-fix, clipped at 1000 pixels high, and supplies visual reproduction only—not element bounding boxes or a measured CTA delta.

All nine card Groups had this layout contract:

```json
{"type":"flex","orientation":"vertical","justifyContent":"space-between"}
```

Inspection of the actual WordPress 7.1.3 `wp-includes/block-supports/layout.php` confirms the axis mapping: for vertical orientation, Core emits `flex-direction: column`; `justifyContent` maps to `align-items`, while `verticalAlignment` maps to `justify-content`. Thus `space-between` was placed in the wrong attribute and discarded as an unsupported `align-items` value. Without `justify-content: space-between`, the buttons follow the variable-length feature lists. The cause is in the pattern attributes, not attributed to the theme.

No M2/M3 equal-height or CTA-alignment reports are present in this repository's `docs/architecture` directory. The available design source, `SDD-1.4.md`, chose Core Grid and vertical Flex and explicitly recorded the prior Featured CTA result as acceptable; this task's new PO-approved objective is addressed here as a minor Core markup correction.

## Core alternatives and selected solution

The existing outer Core Group Grid remains responsible for responsive columns and grid-row sizing. Replacing it with Columns would discard the established adaptive plan-count behavior and is not needed to correct this defect. The existing inner card Groups already use native vertical Flex. The correction sets `verticalAlignment: "space-between"`, the WordPress 7.1 Core attribute that maps to main-axis distribution for vertical Flex.

This uses only Core block attributes and retains the slugs, three-card defaults, editable headings/prices/lists/buttons, Featured emphasis, and Compact density. No CSS alternative or A3 approval was needed. The change does not set card heights; the row may grow naturally with its tallest card, while mobile single-card rows have no cross-card equal-height requirement.

## Markup changes

- `patterns/sci-pricing-cards.html`: corrected all three card Group attributes.
- `patterns/sci-pricing-featured.html`: corrected all three card Group attributes; kept the featured background attribute and badge unchanged.
- `patterns/sci-pricing-compact.html`: corrected all three card Group attributes; retained the compact 14rem Grid track.
- `tests/check-pricing-comparison-patterns.php`: added assertions for the three patterns' default cards and the vertical Flex `verticalAlignment` contract; rejects the prior `justifyContent` misuse.

No extra wrapper or Spacer was added, and card/list/button markup remains unchanged.

## Visual validation and measurements

| Pattern | Pre-fix evidence | Post-fix browser render / CTA bounding boxes |
|---|---|---|
| Pricing Cards | Existing 1440px screenshot shows staggered CTAs; no precise delta measured. | **NOT VERIFIED** |
| Pricing Featured | Source confirms unequal 4/7/5 lists; no current visual capture with measured CTA positions. | **NOT VERIFIED** |
| Pricing Compact | Source confirms varied 4/6/7 lists; no current visual capture with measured CTA positions. | **NOT VERIFIED** |

The fixture's WordPress Core source is present and identified as 7.1.3, but WP-CLI bootstrap returned “Error establishing a database connection”; ports 8088/8089 were not serving pages. Per the task boundary, MariaDB was not started or changed. Therefore there are no post-fix element `top`/`bottom`/`height` values and no claim of ≤2px CTA alignment. The cause and CSS mapping are source-verified; browser rendering is not.

## Responsive matrix

| Pattern | 1440 | 768 | 390 | 320 |
|---|---|---|---|---|
| Pricing Cards | NOT VERIFIED post-fix | NOT VERIFIED | NOT VERIFIED | NOT VERIFIED |
| Pricing Featured | NOT VERIFIED | NOT VERIFIED | NOT VERIFIED | NOT VERIFIED |
| Pricing Compact | NOT VERIFIED | NOT VERIFIED | NOT VERIFIED | NOT VERIFIED |

An earlier report captured only Pricing Cards before this change. No post-change claims are inferred from that evidence. Wrapping, natural height, overflow, focus indication, and button association need browser/PO recheck.

## Gutenberg editor and serialization

The existing PHP/WordPress 7.1.3 parser/serializer test runs against all 22 SCI patterns and passed after the edit. The new test verifies native layout attributes and card counts. The actual Gutenberg editor JavaScript validation path, interactive edits, save/close/reopen, and recovery-warning check were **NOT VERIFIED** in this task. The database-backed editor was unavailable. Since the patterns still serialize ordinary `core/group`, `core/heading`, `core/paragraph`, `core/list`, `core/list-item`, `core/buttons`, and `core/button` blocks, no unknown or custom block was added; this structural fact is not a substitute for editor validation.

## Regression and inventory

Executed with PHP 8.3.35 and the local WordPress 7.1.3 source fixture:

- PHP lint: **13 files PASS**.
- PHP tests: icon inventory, editorial patterns, Pricing/Comparison/Hero patterns, Tabs round-trip: **PASS**.
- Pricing Core parser/serializer and duplicate/remove fixtures for 2–6 cards: **PASS**.
- New Pricing CTA layout assertion: **PASS** for all three patterns / nine default cards.
- Python checks: SVGs, Accordion CSS, Tabs CSS, Editorial Image CSS: **PASS**.
- Inventory: **22 patterns, 83 icons, 7 Block Styles, 3 CSS files**; no new block/JS/CSS/dependency.
- `git diff --check`: **PASS**.

No tests or assertions were removed. The WordPress runtime activation/deactivation, RC install/activation, and content persistence were not rerun because the fixture database could not connect.

## Cross-theme, no-lock-in, performance, and security

- **Cross-theme:** NOT VERIFIED after the fix in Twenty Twenty-Five or Twenty Twenty-One. The changed rule is generated by Core's vertical Flex layout support, not a theme selector; visual theme behavior still needs confirmation.
- **No-lock-in:** The correction uses the public native `core/group` layout attribute. Stored content remains Core markup and no migration or proprietary SCI class is introduced. Plugin-off runtime behavior was not re-tested in this task.
- **Performance:** three JSON layout-attribute substitutions and one test assertion; block count, nesting, DOM shape, CSS count/bytes, and frontend assets unchanged. No benchmark was run.
- **Security:** no executable behavior, data flow, endpoint, remote resource, or dependency changed. No new attack surface identified; no full security scan was repeated.
- **Accessibility:** no semantic or interactive block type changed, and the CTA remain Core Buttons. Focus, keyboard, screen-reader order, contrast, and WCAG checks were not performed; no accessibility conformance claim is made.

## RC.4 candidate

Created from the preserved RC.3 archive, replacing only the three Pricing patterns and staging the plugin header as `1.5.0-rc.4`:

- ZIP: `${TMPDIR}/sci-m6-fix02/sci-design-blocks-1.5.0-rc.4-m6.zip`
- ZIP SHA-256: `44b33c881b357cd456a5f6737e86bbc9d2c8c07e03ba139422f9452e3853de18`
- RC manifest: `${TMPDIR}/sci-m6-fix02/RC-FILE-MANIFEST-rc.4.sha256` (105 entries; manifest SHA-256 `51f382e10c599d1c9fa00dbff37023dee0d9a3f31a6ce1ae1add69bd52eaa660`)
- Source manifest: `${TMPDIR}/sci-m6-fix02/SOURCE-FILE-MANIFEST-rc.4.sha256` (105 entries; manifest SHA-256 `2c49ff3ba088334a95d95d3766370281463c37203e73dd2fa06a09ca3d6d03d3`)
- Both manifests verify; ZIP integrity test **PASS**. Diff against RC.3 manifests shows only the three Pricing patterns and staged plugin version changed.

RC.4 has **not** been installed or activated. This is a QA candidate and not a release. RC.3 remains intact at its original location/hash.

## Git status and files

This task's additions are the three Pricing pattern attribute corrections, regression assertions in `tests/check-pricing-comparison-patterns.php`, and this report. Pre-existing worktree changes remain untouched. No commit, push, merge, tag, or release was made. Branch remains `feature/sci-hero-patterns-css-compat`; the baseline commit remains `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`.

## Open gates and next steps

1. Restore the approved local QA database availability without altering the existing lab configuration, then install RC.4 into the disposable clean fixture.
2. Create/verify distinct-content test cases for each Pricing pattern, including long titles/descriptions/CTA labels, two-line prices, optional description removal, added content, and the 4/8/5 and 8/4/8 list distributions.
3. Capture and measure card and CTA bounding boxes at 1440px (≤2px same-row CTA delta), and test 768/390/320px for wrapping, natural mobile height, overflow, and text clipping.
4. Repeat visuals in Twenty Twenty-Five and Twenty Twenty-One.
5. In Gutenberg, insert, edit lists/prices/CTAs, save, close/reopen, and verify no invalid-block or recovery warning; validate plugin-off content persistence.
6. Recheck focus/keyboard/accessibility and inventory on the installed RC.4 candidate.
7. Obtain independent Product Owner QA. M6 remains **ACTIVE / TECHNICAL NO-GO** and release publication remains unauthorized.
