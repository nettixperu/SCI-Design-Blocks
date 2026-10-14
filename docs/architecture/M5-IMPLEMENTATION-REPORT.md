# M5 Implementation Report — Hero Patterns & Block Style CSS Compatibility

**Project:** SCI Design Blocks<br>
**Milestone:** M5 — Implementation<br>
**Branch:** `feature/sci-hero-patterns-css-compat`<br>
**Baseline:** stable SCI Design Blocks 1.4.0 (frozen)<br>
**Outcome:** PASS WITH LIMITATIONS<br>
**Release status:** none; M5 formally accepted by the Product Owner as PASS WITH LIMITATIONS; open gates transferred to M6

## A. Executive summary

Implemented the approved type-level stylesheet associations and two Core-only patterns, Hero Cover and Hero Split. Regression tests now expect 22 registered patterns while retaining checks of the existing inventory, and runtime checks exercised the supported themes and the plugin-off persistence path. Automated/static and local server evidence passed. This is not a visual, editor, minimum-version, accessibility, or release certification: those gates remain open as itemized below.

## B. Baseline

The stable 1.4.0 release contains 20 patterns, 83 curated icons, seven Block Styles, three opt-in CSS files, no custom SCI blocks, no SCI frontend JavaScript, and no external runtime dependencies. The published 1.4.0 artifact and checksum were not changed. M5 adds two patterns only on this implementation branch.

## C. Branch and Git status

Work is on `feature/sci-hero-patterns-css-compat`, based on the approved investigation state at `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`. The pre-existing M4 SDD/index edits were preserved. No commit, push, merge, tag, release, or change to `main` was made. At M5 closeout, M6 had not yet been authorized; the subsequent formal transition and M6 evidence are recorded in [M6-CERTIFICATION-REPORT.md](M6-CERTIFICATION-REPORT.md).

## D. Block Styles CSS delivery

Kept `register_block_style()` registrations, public style names, classes, stylesheet handles, CSS selectors, and the three CSS files. Added standard `wp_enqueue_block_style()` associations: `core/accordion` → Accordion CSS; `core/tabs` → Tabs CSS; `core/post-featured-image` → editorial image CSS. There is no custom loader or theme-specific branch.

## E. Hero Cover

Added `sci-design-blocks/hero-cover` in `patterns/sci-hero-cover.html`, registered through `includes/hero-patterns.php`. It uses Core Cover, Group, Paragraph, Heading, Buttons, and Button blocks; has an editable H2, neutral sample copy and local example anchors; and has no remote/default image URL. Its Cover starts without a selected image so an editor can choose media.

## F. Hero Split

Added `sci-design-blocks/hero-split` in `patterns/sci-hero-split.html`. It uses Core Columns, Column, Group, Paragraph, Heading, Buttons/Button, and Image blocks. The image is an empty Core Image placeholder, the composition stacks on mobile, and copy/CTAs are editable local examples. No Astra, Spectra, custom block, or proprietary attribute is required.

## G. Inventory

The branch registry contains 22 patterns (the prior 20 plus the two Heroes), 83 icons, and the same seven Block Style variants. The existing three stylesheets remain the only SCI frontend CSS assets. No custom SCI block or frontend JavaScript was introduced. Runtime WordPress pattern-registry count includes theme patterns and is not used as the SCI inventory; the test asserts SCI slugs directly.

## H. Regression coverage

The PHP registration checks assert the complete expected pattern slug set, icon inventory, seven styles, enqueue associations, and Core serialization. Existing Editorial, Pricing/Comparison, Tabs, and Accordion fixture checks passed. Original patterns and style identifiers remain explicitly exercised; no pattern content was migrated or rewritten.

## I. Conditional CSS delivery evidence

On local WordPress 7.1.3, HTTP output was checked with Twenty Twenty-Five and Twenty Twenty-One. Pages containing each of the seven style variants emitted one matching stylesheet URL. Standard Core Accordion/Tabs/Featured Image controls without an SCI variant emitted the type-associated sheet, consistent with the approved type-level contract. A mixed page with duplicate Accordion variants emitted one Accordion sheet and one each of Tabs and Editorial Image sheets. A page with no relevant block type emitted none. Each of the three emitted asset URLs returned HTTP 200. The observed links are server-output evidence, not computed-style or visual proof.

## J. Performance

The behavior remains conditional by rendered Core block type; stylesheets are not globally enqueued and duplicate variants did not duplicate links in the tested fixtures. CSS files were not expanded: Accordion 1,076 bytes; Tabs 2,202 bytes; Editorial Image 580 bytes. No JavaScript, runtime dependency, custom CSS framework, or additional asset was added.

## K. Portability and themes

Both Twenty Twenty-Five 1.5 and Twenty Twenty-One 2.9 returned the expected CSS links in the local QA site. The Hero markup uses WordPress Core blocks and standard attributes rather than Astra/Spectra APIs. This verifies registration/serialization and server output in the available environment, not visual equivalence across themes or full browser behavior.

## L. Accessibility

The Hero patterns use native Core content, an H2 heading, paragraph copy, and Core Buttons; image controls are native Core. No accessibility certification is claimed. Keyboard/focus review, contrast measurement, screen-reader review, and visual confirmation of heading hierarchy were not performed in a browser and remain open gates.

## M. Responsive behavior

Hero Split declares mobile stacking; its columns and button layout are Core-native. Static structure and HTTP rendering were checked, but widths 1440, 768, 390, and 320 pixels were not visually inspected. Image crop, text wrapping, button wrapping, and narrow viewport behavior remain PO/browser QA items.

## N. No-lock-in and plugin-off persistence

Local QA pages 89 (Hero Cover) and 90 (Hero Split) were parsed, serialized, and requested with SCI active. Their content uses registered Core blocks and includes editable copy, local CTA fragments, and QA-local media. SCI was temporarily deactivated: both saved pages remained parseable/serializable and retained their copy, media, and CTA content. SCI was reactivated afterward. The QA pages remain in the local QA database; they are fixtures, not repository assets.

## O. Maintainability and documentation

Hero registration is isolated in `includes/hero-patterns.php`, with static pattern markup under `patterns/`. Existing style registration remains adjacent to its module and uses WordPress's standard block-style API. README and changelog distinguish stable 1.4.0 from unreleased branch work. The SDD records the implementation state and remaining gates; this report is the M5 evidence handoff.

## P. Risks and limitations

- Available runtime: WordPress 7.1.3, PHP 8.5.7, WP-CLI 2.12.0, SQLite Integration 3.1.0, macOS.
- WordPress 7.1.0 and PHP 7.4 were unavailable; minimum-version compatibility is not certified.
- Browser CUA could not start because its Node runtime was unavailable. No browser/editor visual review or block recovery-warning check was completed.
- No PO visual review at the target viewport sizes, contrast check, keyboard/focus review, or accessibility audit was completed.
- HTTP status, parser/serializer output, theme switching, and generated stylesheet links do not prove visual CSS application or editor experience.

## Q. M6 readiness

The Product Owner formally closed M5 as PASS WITH LIMITATIONS and authorized M6 only for QA, minor corrections within the SDD, and RC preparation. The limitations listed here transfer to M6; they do not imply release approval. Keep 1.4.0 immutable and obtain separate explicit release authorization.

## R. Execution status and commands

Passed PHP syntax checks for the PHP source and tests, plus:

```sh
php tests/check-icon-library.php
php tests/check-editorial-query-patterns.php ${SCI_QA_ROOT}/site
php tests/check-pricing-comparison-patterns.php ${SCI_QA_ROOT}/site
php tests/check-tabs-roundtrip.php ${SCI_QA_ROOT}/site
python3 tests/check-icon-svg.py
python3 tests/check-accordion-css.py
python3 tests/check-tabs-css.py
python3 tests/check-editorial-image-css.py
```

The tests reported 22 patterns, 83 icons, seven styles, preserved Core parse/serialize behavior, and passed static asset checks. Runtime HTTP and plugin-off checks described above also passed in the local QA environment. Not run: PHP 7.4, WordPress 7.1.0, browser/editor QA, viewport screenshots, visual review, and accessibility audit. No tools or dependencies were installed. No commit or publication was performed.
