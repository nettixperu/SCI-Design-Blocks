# Changelog

User-facing highlights for stable releases. For full release details, see [GitHub Releases](https://github.com/nettixperu/SCI-Design-Blocks/releases).

## 1.6.5 (unreleased)

- Uses WordPress's theme.json filter to set `font-display: swap` for Manrope when Twenty Twenty-Five is the parent theme; keeps the local font and avoids an added stylesheet or preload.

## 1.6.4 (unreleased)

- Loads Product Story CSS only when its Core Group marker exists in the queried page, and inlines it in the head to avoid a separate render-blocking request without a flash of unstyled content.
- Provides SCI Accordion style CSS through the Core Block Styles API inline, avoiding a separate CSS request; the selectors and visual rules remain unchanged.

## 1.6.3 (unreleased)

- Adds lightweight, per-button hover controls to the Core Button block for hover background color or gradient, text and border colors, shadow, and subtle lift.
- Makes the FAQ pattern easy to find as “SCI FAQ — Acordeón desplegable”, adds a Spanish heading and editable examples, and aligns the accordion to the wide layout.
- Updates the page-title editor asset version for the current source.

## 1.5.1

- Adds the responsive SCI Features Grid and Firefox-inspired SCI Product Story patterns.
- Adds a sitewide page-title setting that removes generated page titles in the editor and on the front end.
- Gives standalone editorial, hero, pricing, comparison, and product-story sections full-width roots with centered wide content.
- Vertically centers the image/icon feature columns and centers the SCI Stats metrics.
- Fixes malformed block JSON in six product-story cards that could trigger Gutenberg's invalid-content warning.

## 1.5.0

- Adds seven Core-only patterns: Hero Cover, Hero Split, Section Intro, Benefits Grid, Services Image Cards, Pricing Split Offer, and CTA Horizontal; total inventory is 27 patterns, 83 curated icons, seven Core Block Styles, and three CSS files.
- Improves full-width commercial sections, responsive Services cards, pricing CTA alignment, and Hero Cover text contrast using WordPress Core layout and block controls.
- Corrects Gutenberg block serialization in Hero Split, Pricing Split, CTA Horizontal, and other affected patterns; regression checks cover Core parser/serializer compatibility.
- Replaces broken example CTA fragments with editable Core Button placeholders that instruct editors to configure a real destination before publishing.
- Makes block-style CSS cache versions consistent with local asset modification times and adds related regressions.
- Raises the minimum PHP requirement to 8.3; WordPress 7.1 remains the minimum. Stable 1.4.0 and its PHP 7.4 requirement are unchanged.
- No SCI custom blocks, SCI frontend JavaScript, new runtime dependencies, or additional CSS were introduced.
- Automated and Product Owner manual Gutenberg checks are documented in the final release report. This release does not claim formal WCAG certification.

## 1.4.0

- Added Pricing Cards, Pricing Featured, Pricing Compact, Comparison — 2 Options, and Comparison — Feature Table patterns.
- Pricing uses editable Core blocks and responsive Core Grid; the comparison table uses Core Table.

## 1.3.0

- Added six editorial Query patterns and the optional Featured Image Hover Zoom style.

## 1.2.0

- Added Underline, Pills, Connected, and Filled styles for the Core Tabs block.

## 1.1.0

- Added the SCI icon collection and Minimal/Bordered Core Accordion styles.

## 1.0.0

- First stable release of the Gutenberg-native pattern library.
