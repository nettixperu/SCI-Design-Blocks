# Changelog

User-facing highlights for stable releases. For full release details, see [GitHub Releases](https://github.com/nettixperu/SCI-Design-Blocks/releases).

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
