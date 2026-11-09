# SCI Design Blocks

Lightweight Gutenberg-native design patterns, icons, and Core Block Styles for WordPress.

Build editorial, commercial, and interface layouts from WordPress Core blocks—without a page builder or proprietary content blocks. Patterns insert editable Core content; selected Core blocks can also use optional SCI styles.

[![Latest release](https://img.shields.io/github/v/release/nettixperu/SCI-Design-Blocks?label=release)](https://github.com/nettixperu/SCI-Design-Blocks/releases/latest)
[![WordPress 7.1+](https://img.shields.io/badge/WordPress-7.1%2B-21759B?logo=wordpress&logoColor=white)](https://wordpress.org/)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/supported-versions.php)
[![License: GPL-2.0-or-later](https://img.shields.io/badge/License-GPL--2.0--or--later-blue.svg)](COPYING.md)

**Current source and test package:** 1.7.4. It is a test build and is not a tagged or production release. The latest published GitHub release remains [SCI Design Blocks 1.4.0](https://github.com/nettixperu/SCI-Design-Blocks/releases/tag/v1.4.0).

## What’s included

The 1.7.4 source contains 31 patterns, 83 icons, seven Core Block Styles, and one optional dynamic Gutenberg block: SCI Market Pricing. Published 1.4.0 remains immutable and contains 20 patterns, 83 icons, two Accordion styles, four Tabs styles, and an optional Featured Image Hover Zoom style.

| Family | Included |
| --- | --- |
| General and content | Feature — Icon Vertical, Feature — Image Vertical, Feature — Icon Left, Feature — Image Left, CTA, Testimonial, Stats, Icon List, Accordion |
| Editorial | Featured Hero, Editorial Lead, Compact List, Editorial Grid, Editorial Stack, Editorial Sections |
| Pricing | Pricing Cards, Pricing Featured, Pricing Compact, Pricing — Market Selector |
| Comparison | Comparison — 2 Options, Comparison — Feature Table |
| Hero and sections | Hero Cover, Hero Split, Section Intro, Benefits Grid, Services Image Cards, Process — 3 Steps |
| Commercial CTA | Pricing Split Offer, CTA Horizontal |
| Core Block Styles | Accordion: Minimal and Bordered; Tabs: Underline, Pills, Connected, Filled, with horizontal scrolling on narrow screens; Featured Image: optional Hover Zoom |

The current source adds Core-only compositions, including the SCI Process — 3 Steps pattern, and SCI Market Pricing to connect Core pricing cards to market-specific Fluent Forms URLs. Responsive card grids use Core Grid so cards stay centered and fill their available area. Minimum requirements for the current source are WordPress 7.1+ and PHP 8.3+; 1.4.0 remains unchanged at PHP 7.4+. See the [Market Pricing guide](docs/blocks/market-pricing.md), [M7 equivalence and design decisions](docs/architecture/M7-PATTERN-EXPANSION-DECISIONS.md), [M8 implementation report](docs/architecture/M8-IMPLEMENTATION-REPORT.md), [M8-FIX-01 report](docs/architecture/M8-FIX-01-REPORT.md), [M6 PHP 8.3 decision](docs/architecture/M6-PHP83-PO-DECISION.md), [M9 final hardening report](docs/architecture/M9-FINAL-HARDENING-REPORT.md), and [pattern inventory](docs/patterns/1.5.0-preview.md).

The 83 curated icons are available from the **SCI** collection in the Core Icon block workflow. See [Third-Party Notices](THIRD-PARTY-NOTICES.md) for icon licensing details.

Pricing patterns use Core Group Grid and start with three plans. Duplicate or remove plan Groups with normal Gutenberg editing; the Product Owner reports validating up to six plans. Six is not a software limit. Comparison Feature Table uses the semantic Core Table block; narrow-screen horizontal scrolling remains within the table. SCI Market Pricing is an optional wrapper around the Core cards. It stores editable market/currency data and plan prices, then links Core Button blocks to the configured Fluent Forms URL for that market. It supports additional currencies and plan IDs; it does not render or style replacement cards.

## Download and install

**Latest published stable release: 1.4.0**
[Download the plugin ZIP](https://github.com/nettixperu/SCI-Design-Blocks/releases/download/v1.4.0/sci-design-blocks-1.4.0.zip) · [Release notes](https://github.com/nettixperu/SCI-Design-Blocks/releases/tag/v1.4.0)

SHA-256:

```text
1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93
```

1. Download `sci-design-blocks-1.4.0.zip`.
2. In WordPress, open **Plugins → Add New Plugin → Upload Plugin**.
3. Upload the ZIP, install, and activate SCI Design Blocks.
4. Open the block inserter and choose a pattern from the **SCI Design Blocks** category.

Stable release 1.4.0 requirements: WordPress 7.1+, PHP 7.4+, and the WordPress Block Editor. The current source requires WordPress 7.1+ and PHP 8.3+. No npm or build tools are required to install or use the plugin.

## Quick start

1. Insert an SCI pattern from the Gutenberg inserter.
2. Edit its text, images, colors, and spacing with the normal block controls and your active theme’s settings.
3. Save or publish your post or template.

CTA buttons in pattern examples are Core Button placeholders. Set and verify a real destination in the editor before publishing; the examples do not invent site-specific URLs.

There is no proprietary builder workflow.

## Core-first and theme-native

SCI Design Blocks favors reusable compositions of WordPress Core blocks. Patterns insert ordinary Core content; selected Core blocks receive optional Block Styles; and the SCI icon collection is integrated into Gutenberg’s icon workflow.

- **Core patterns remain portable.** Inserted card content remains editable WordPress Core blocks. SCI Market Pricing is an optional dynamic wrapper; if SCI is deactivated, its saved attributes remain in the post, but its selector and market-price behavior are unavailable until SCI is active again.
- **Theme-native.** Patterns use Core controls and respect theme.json and Global Styles.
- **Market Pricing uses WordPress APIs and browser standards.** Its editor adds market/price fields and plan IDs to Core paragraph/button blocks; its frontend script updates marked prices and sends only market and plan IDs to Fluent Forms. It does not send a price as checkout authority, calculate exchange rates, or add a payment runtime.
- The Pricing and Comparison patterns remain Core markup. A small selector style and frontend script load only when the optional Market Pricing block is present.
- When the plugin is inactive, inserted pattern content remains Core content. Registered SCI icons and optional visual styles may no longer be available.

Editorial patterns use Core Query and Post blocks to display posts. Query settings in separate patterns are independent; SCI does not synchronize their filters or guarantee non-overlapping results.

## Visual showcase

No approved product screenshots are currently included in the repository. We do not use mock screenshots. The showcase is ready for real captures of Featured Hero, Editorial Sections, Pricing Featured, Pricing Cards, Comparison 2 Options, Comparison Feature Table, Tabs, and an icon/feature pattern. Recommended filenames and README placement are documented in [`docs/assets/README.md`](docs/assets/README.md).

## Accessibility and performance

Patterns rely on Core block semantics and interactions where available, including Core Table semantics and Core Accordion/Tabs behavior. Hover Zoom is opt-in and respects `prefers-reduced-motion`. This project does not claim formal WCAG certification. SCI Market Pricing adds a small frontend script only on pages containing the block; it has no external runtime dependencies. Fluent Forms must define fixed payment items/prices for each market's currency and accept the `plan` value as a selection/default. No performance benchmark claims are made.

## Releases

The latest published stable release is **SCI Design Blocks 1.4.0**. Version 1.7.4 is a test package and has not been tagged as a GitHub release.

- [Download SCI Design Blocks 1.4.0](https://github.com/nettixperu/SCI-Design-Blocks/releases/download/v1.4.0/sci-design-blocks-1.4.0.zip)
- [Read the 1.4.0 release notes](https://github.com/nettixperu/SCI-Design-Blocks/releases/tag/v1.4.0)
- SHA-256: `1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93`

## Support and contributions

Report bugs and request features through [GitHub Issues](https://github.com/nettixperu/SCI-Design-Blocks/issues). See [Contributing](CONTRIBUTING.md) before opening a pull request. For security reports, follow [SECURITY.md](SECURITY.md) and do not disclose vulnerabilities in public issues.

Engineering history and architecture references are organized in the [documentation index](docs/README.md).

## License

SCI Design Blocks is licensed under [GPL-2.0-or-later](COPYING.md); the canonical GNU GPL version 2 text is in [LICENSE](LICENSE). See [Third-Party Notices](THIRD-PARTY-NOTICES.md) for third-party icon attribution.
