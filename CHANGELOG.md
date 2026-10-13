# Changelog

User-facing highlights for stable releases. For full release details, see [GitHub Releases](https://github.com/nettixperu/SCI-Design-Blocks/releases).

## Unreleased — 1.5.0 M7/M8 development

- Adds five Core-only patterns: Section Intro, Benefits Grid, Image Service Cards, Split Offer, and Horizontal CTA, expanding the branch inventory to 27 patterns (with 83 icons, seven Core Block Styles, and three CSS files). These are development-candidate contents, not a published release.
- M8-FIX-01 corrects Core block nesting in Pricing Split and CTA Horizontal, and adds explicit Core save-markup regression coverage for empty Image blocks in Hero Split and Service Cards; interactive media selection/save/reopen remains unverified.
- M8 responsive pass gives all five new sections Core full-width wrappers with readable wide inner content; Service Cards uses a responsive auto-fit Grid, 4:3 Core Image placeholders, and aligned CTAs.
- Existing Pricing Cards, Pricing Featured, and Pricing Compact retain Core flex-based CTA alignment. Hero Cover receives a stronger dark overlay for text contrast.
- Preserves the published 1.4.0 artifact and its requirements.

## Unreleased — 1.5.0 final hardening

- Removes example CTA fragment destinations that had no matching target and marks destination-free Core Button examples with an editor instruction to configure the link before publishing.
- Uses local CSS file modification times consistently for cache invalidation across Accordion, Tabs, and Hover Zoom block styles.
- Adds regression checks for unconfigured Core Button placeholders, unresolved in-pattern fragments, and stylesheet cache versions. No new blocks, JavaScript, dependencies, patterns, or CSS were added.
- RC.10 is the final local verification candidate for this hardening pass; RC.9 is preserved and superseded, not a stable or published release.

## Unreleased — 1.5.0 M6 candidate

- Added Core-only Hero Cover and Hero Split patterns. Existing Accordion, Tabs, and Featured Image Block Style CSS is associated with its Core block type for conditional delivery, retaining three CSS files and adding no frontend JavaScript or runtime dependencies.
- By approved Product Owner decision (2026-10-13), the planned minimum PHP version for 1.5.0 is 8.3. Stable 1.4.0 remains unchanged and continues to require PHP 7.4+.
- Regression coverage exercises the full pattern inventory, Core parser/serializer round-trips, block-comment nesting, empty Image serialization, icon/style registration, and CSS/SVG constraints. These automated checks are not a substitute for interactive Gutenberg or formal accessibility certification.
- These changes are development-candidate work only; no release has been published and stable 1.4.0 remains unchanged. See the [M5 implementation report](docs/architecture/M5-IMPLEMENTATION-REPORT.md) and [M6 PHP 8.3 decision](docs/architecture/M6-PHP83-PO-DECISION.md).

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
