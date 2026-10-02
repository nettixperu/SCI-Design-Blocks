# SCI Design Blocks

A lightweight Gutenberg-native design pattern library for WordPress.

SCI Design Blocks is **Core-first**, **pattern-based**, and **theme-native**. Patterns insert ordinary WordPress Core blocks. In the 1.1 development line, selected SCI icons use Core’s registered icon handle; the glyph requires the plugin to be active and returns after reactivation, while surrounding Core content and the handle remain.

## Requirements

- Current stable release 1.0.0: WordPress 7.0+ and PHP 7.4+
- Development line 1.1.0-dev: WordPress 7.1+ and PHP 7.4+
- The WordPress Block Editor

## Patterns

- SCI Feature — Icon Vertical
- SCI Feature — Image Vertical
- SCI Feature — Icon Left
- SCI Feature — Image Left
- SCI CTA
- SCI Testimonial
- SCI Stats
- SCI Icon List
- SCI Accordion

## Development 1.1

The development branch contains a curated set of 83 Bootstrap Icons v1.13.1 in the WordPress 7.1 Core Icon API collection. The 1.1 line is not released; the 1.0.0 ZIP remains the current stable installation. See [SDD-1.1.md](SDD-1.1.md) for the architecture, catalog decisions, and validation commands.

## Installation

1. Download `sci-design-blocks-1.0.0.zip` from the GitHub release.
2. In WordPress, go to **Plugins → Add New → Upload Plugin** and select the ZIP.
3. Activate **SCI Design Blocks**.
4. In the Gutenberg inserter, search for **SCI**.

Inserted patterns become regular WordPress Core blocks and remain in the page if the plugin is deactivated. SCI icons are stored as Core icon handles; their glyphs may disappear while this plugin is inactive and return after reactivation.

## Architecture

- WordPress Core blocks only; no custom SCI blocks.
- No SCI frontend CSS or JavaScript.
- No external runtime dependencies or icon fonts.
- Theme typography, colors, spacing, and layout remain under Core and the active theme.

## Known limitations

- Icon List favors flexible Core editing, but does not use native `<ul>/<li>` list semantics.
- Tabs are deferred; they are a future 1.1+ candidate, not part of this release.
