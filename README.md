# SCI Design Blocks 1.2.0

Lightweight Gutenberg-native design extensions for WordPress. SCI Design Blocks follows Core-first, reuse-first, theme-native and content-first principles.

## Requirements

- WordPress 7.1 or later
- PHP 7.4 or later
- WordPress Block Editor

## Included

- 9 patterns built from WordPress Core blocks
- SCI Icon Library with 83 curated icons
- Core Accordion styles: Minimal and Bordered
- Core Tabs styles: Underline, Pills, Connected and Filled

The four SCI Tabs styles keep labels on one line and use horizontal overflow on narrow viewports. They are optional styles for WordPress Core Tabs; SCI does not add a Tabs block or frontend JavaScript. Colors derive from Core and theme styles.

The icon set uses Bootstrap Icons v1.13.1 under the MIT license. SCI Design Blocks is licensed under GPL-2.0-or-later; see [LICENSE](LICENSE) and [THIRD-PARTY-NOTICES.md](THIRD-PARTY-NOTICES.md).

## Architecture

- Patterns remain ordinary WordPress Core blocks.
- No custom SCI blocks, SCI frontend JavaScript or external runtime dependencies.
- Small, scoped CSS provides optional Accordion and Core Tabs appearances.

## When the plugin is inactive

SCI registered icons require the plugin to be active to render; their `core/icon` handles and surrounding content remain, and reactivation restores the icons. Accordion and Tabs content and interaction remain WordPress Core. Their SCI visual styles and responsive presentation require the plugin to be active.
