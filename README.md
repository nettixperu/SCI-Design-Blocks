# SCI Design Blocks 1.3.0-dev

Lightweight Gutenberg-native design extensions for WordPress. SCI Design Blocks follows Core-first, reuse-first, theme-native and content-first principles.

## Requirements

- WordPress 7.1 or later
- PHP 7.4 or later
- WordPress Block Editor

## Included

- 14 patterns built from WordPress Core blocks, including five editorial Query patterns
- SCI Icon Library with 83 curated icons
- Core Accordion styles: Minimal and Bordered
- Core Tabs styles: Underline, Pills, Connected and Filled
- Editorial Query patterns: Featured Hero, Editorial Lead, Compact List, Editorial Grid and Editorial Stack
- Opt-in Core Post Featured Image style: SCI — Hover Zoom

The four SCI Tabs styles keep labels on one line and use horizontal overflow on narrow viewports. They are optional styles for WordPress Core Tabs; SCI does not add a Tabs block or frontend JavaScript. Colors derive from Core and theme styles.

The icon set uses Bootstrap Icons v1.13.1 under the MIT license. SCI Design Blocks is licensed under GPL-2.0-or-later; see [LICENSE](LICENSE) and [THIRD-PARTY-NOTICES.md](THIRD-PARTY-NOTICES.md).

## Architecture

- Patterns remain ordinary WordPress Core blocks.
- No custom SCI blocks, SCI frontend JavaScript or external runtime dependencies.
- Small, scoped CSS provides optional Accordion, Core Tabs and Post Featured Image appearances.
- Editorial Query patterns serialize as WordPress Core blocks and remain editable when the plugin is inactive.

Editorial Lead uses two coordinated Core Query Loops. Its default offsets avoid duplicate posts when both loops retain equivalent filters, ordering and sticky behavior. If you change one query, maintain equivalent settings in the other to preserve the sequence; SCI does not synchronize the two queries.

Editorial Grid presents three horizontal cards with compact title typography, excerpt and metadata. Editorial Stack presents three posts vertically with larger featured images, excerpts and metadata.

SCI — Hover Zoom is an optional style on Core Post Featured Image. It is off unless selected on an image block and respects reduced-motion preferences.

The 1.3 development series does not remove or modify site-level editorial CSS. CSS replacement requires a separate visual migration review.

## When the plugin is inactive

SCI registered icons require the plugin to be active to render; their `core/icon` handles and surrounding content remain, and reactivation restores the icons. Accordion and Tabs content and interaction remain WordPress Core. Their SCI visual styles and responsive presentation require the plugin to be active.
