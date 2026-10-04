# SCI Design Blocks

Lightweight Gutenberg-native design patterns, icons, and Core Block Styles for WordPress.

Build editorial, commercial, and interface layouts from WordPress Core blocks—without a page builder or proprietary content blocks. Patterns insert editable Core content; selected Core blocks can also use optional SCI styles.

[![Latest release](https://img.shields.io/github/v/release/nettixperu/SCI-Design-Blocks?label=release)](https://github.com/nettixperu/SCI-Design-Blocks/releases/tag/v1.4.0)
[![WordPress 7.1+](https://img.shields.io/badge/WordPress-7.1%2B-21759B?logo=wordpress&logoColor=white)](https://wordpress.org/)
[![PHP 7.4+](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/supported-versions.php)
[![License: GPL-2.0-or-later](https://img.shields.io/badge/License-GPL--2.0--or--later-blue.svg)](LICENSE)

**[Download SCI Design Blocks 1.4.0](https://github.com/nettixperu/SCI-Design-Blocks/releases/download/v1.4.0/sci-design-blocks-1.4.0.zip)** · **[View release notes](https://github.com/nettixperu/SCI-Design-Blocks/releases/tag/v1.4.0)**

## What’s included

20 patterns, 83 icons, two Accordion styles, four Tabs styles, and an optional Featured Image Hover Zoom style.

| Family | Included |
| --- | --- |
| General and content | Feature — Icon Vertical, Feature — Image Vertical, Feature — Icon Left, Feature — Image Left, CTA, Testimonial, Stats, Icon List, Accordion |
| Editorial | Featured Hero, Editorial Lead, Compact List, Editorial Grid, Editorial Stack, Editorial Sections |
| Pricing | Pricing Cards, Pricing Featured, Pricing Compact |
| Comparison | Comparison — 2 Options, Comparison — Feature Table |
| Core Block Styles | Accordion: Minimal and Bordered; Tabs: Underline, Pills, Connected, Filled, with horizontal scrolling on narrow screens; Featured Image: optional Hover Zoom |

The 83 curated icons are available from the **SCI** collection in the Core Icon block workflow. See [Third-Party Notices](THIRD-PARTY-NOTICES.md) for icon licensing details.

Pricing patterns use Core Group Grid and start with three plans. Duplicate or remove plan Groups with normal Gutenberg editing; the Product Owner reports validating up to six plans. Six is not a software limit. Comparison Feature Table uses the semantic Core Table block; narrow-screen horizontal scrolling remains within the table.

## Download and install

**Latest stable release: 1.4.0**
[Download the plugin ZIP](https://github.com/nettixperu/SCI-Design-Blocks/releases/download/v1.4.0/sci-design-blocks-1.4.0.zip) · [Release notes](https://github.com/nettixperu/SCI-Design-Blocks/releases/tag/v1.4.0)

SHA-256:

```text
1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93
```

1. Download `sci-design-blocks-1.4.0.zip`.
2. In WordPress, open **Plugins → Add New Plugin → Upload Plugin**.
3. Upload the ZIP, install, and activate SCI Design Blocks.
4. Open the block inserter and choose a pattern from the **SCI Design Blocks** category.

Requirements: WordPress 7.1+, PHP 7.4+, and the WordPress Block Editor. No npm or build tools are required to install or use the release.

## Quick start

1. Insert an SCI pattern from the Gutenberg inserter.
2. Edit its text, images, colors, and spacing with the normal block controls and your active theme’s settings.
3. Save or publish your post or template.

There is no proprietary builder workflow.

## Core-first and theme-native

SCI Design Blocks favors reusable compositions of WordPress Core blocks. Patterns insert ordinary Core content; selected Core blocks receive optional Block Styles; and the SCI icon collection is integrated into Gutenberg’s icon workflow.

- **No custom SCI blocks.** Inserted pattern content remains editable WordPress Core blocks.
- **Theme-native.** Patterns use Core controls and respect theme.json and Global Styles.
- **No SCI frontend JavaScript** and no external runtime dependencies.
- The five Pricing and Comparison patterns add no SCI CSS. The plugin does include CSS for selected opt-in Core Block Styles, including Accordion, Tabs, and Hover Zoom.
- When the plugin is inactive, inserted pattern content remains Core content. Registered SCI icons and optional visual styles may no longer be available.

Editorial patterns use Core Query and Post blocks to display posts. Query settings in separate patterns are independent; SCI does not synchronize their filters or guarantee non-overlapping results.

## Visual showcase

No approved product screenshots are currently included in the repository. We do not use mock screenshots. The showcase is ready for real captures of Featured Hero, Editorial Sections, Pricing Featured, Pricing Cards, Comparison 2 Options, Comparison Feature Table, Tabs, and an icon/feature pattern. Recommended filenames and README placement are documented in [`docs/assets/README.md`](docs/assets/README.md).

## Accessibility and performance

Patterns rely on Core block semantics and interactions where available, including Core Table semantics and Core Accordion/Tabs behavior. Hover Zoom is opt-in and respects `prefers-reduced-motion`. This project does not claim formal WCAG certification. SCI adds no frontend JavaScript or external runtime dependencies; no performance benchmark claims are made.

## Releases

The latest stable release is **SCI Design Blocks 1.4.0**.

- [Download SCI Design Blocks 1.4.0](https://github.com/nettixperu/SCI-Design-Blocks/releases/download/v1.4.0/sci-design-blocks-1.4.0.zip)
- [Read the 1.4.0 release notes](https://github.com/nettixperu/SCI-Design-Blocks/releases/tag/v1.4.0)
- SHA-256: `1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93`

## Support and contributions

Report bugs and request features through [GitHub Issues](https://github.com/nettixperu/SCI-Design-Blocks/issues). See [Contributing](CONTRIBUTING.md) before opening a pull request. For security reports, follow [SECURITY.md](SECURITY.md) and do not disclose vulnerabilities in public issues.

Engineering history and architecture references are organized in the [documentation index](docs/README.md).

## License

SCI Design Blocks is licensed under [GPL-2.0-or-later](LICENSE). See [Third-Party Notices](THIRD-PARTY-NOTICES.md) for third-party icon attribution.
