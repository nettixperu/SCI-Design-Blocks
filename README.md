# SCI Design Blocks

A lightweight Gutenberg-native design pattern library for WordPress.

SCI Design Blocks is **Core-first**, **pattern-based**, and **theme-native**. Patterns insert ordinary WordPress Core blocks, so content stays portable with **no lock-in**.

## Requirements

- WordPress 7.0 or later
- PHP 7.4 or later
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

## Installation

1. Download `sci-design-blocks-1.0.0.zip` from the GitHub release.
2. In WordPress, go to **Plugins → Add New → Upload Plugin** and select the ZIP.
3. Activate **SCI Design Blocks**.
4. In the Gutenberg inserter, search for **SCI**.

Inserted patterns become regular WordPress Core blocks and remain in the page if the plugin is deactivated.

## Architecture

- WordPress Core blocks only; no custom SCI blocks.
- No SCI frontend CSS or JavaScript.
- No external runtime dependencies or icon fonts.
- Theme typography, colors, spacing, and layout remain under Core and the active theme.

## Known limitations

- Icon List favors flexible Core editing, but does not use native `<ul>/<li>` list semantics.
- Tabs are deferred; they are a future 1.1+ candidate, not part of this release.
