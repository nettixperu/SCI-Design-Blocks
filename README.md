# SCI Design Blocks 1.2 RC

A lightweight Gutenberg-native design library for WordPress.

This release candidate adds two opt-in visual styles to WordPress Core Tabs. It is awaiting the compact manual WordPress gate; it is not the stable 1.2.0 release.

SCI Design Blocks is **Core-first**, **pattern-based**, and **theme-native**. Its patterns use ordinary WordPress Core blocks and preserve a content-first, no-lock-in structure.

## Requirements

- WordPress 7.1 or later
- PHP 7.4 or later
- The WordPress Block Editor

## Included

- 9 Gutenberg patterns
- SCI Icon Library with 83 curated icons
- Core Accordion styles: Minimal and Bordered
- Core Tabs styles: Underline and Pills, with horizontal scrolling on narrow viewports

The SCI icon set uses Bootstrap Icons v1.13.1 under the MIT license. SCI Design Blocks is licensed under GPL-2.0-or-later; see [LICENSE](LICENSE) and [THIRD-PARTY-NOTICES.md](THIRD-PARTY-NOTICES.md).

## Architecture

- Patterns are composed from WordPress Core blocks; there are no custom SCI blocks.
- SCI icons use the WordPress 7.1 Core Icon API and `core/icon`.
- No SCI frontend JavaScript or external runtime dependencies.
- Small, scoped CSS provides the opt-in Accordion and Core Tabs appearances.

## Behavior when deactivated

SCI registered icons require SCI Design Blocks to be active to render. The stored `core/icon` handle and surrounding content remain when the plugin is deactivated; reactivating the plugin restores the icon. Accordion and Tabs content and interaction remain WordPress Core; SCI visual styles require the plugin to be active.
